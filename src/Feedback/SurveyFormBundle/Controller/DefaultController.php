<?php

namespace Feedback\SurveyFormBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\MaintenanceBundle\Entity\EmployeeAnswers;
use Admin\MaintenanceBundle\Entity\Concessionaire;
use Admin\EmailRecipientBundle\Entity\EmailReciEmailRecipientBundlepient;

use Feedback\SurveyFormBundle\DependencyInjection\phpmailer\PHPMailer;
use Symfony\Component\Config\FileLocator;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_survey")
     * @Template()
     */
    public function indexAction(Request $request)
    {
       $session = $request->getSession();
       
       if($session->has('username') && $session->has('conc_id'))             
           return $this->render('FeedbackSurveyFormBundle:Default:index.html.twig');     
       else
           return $this->redirect($this->generateUrl('_login'));
    }
    
    /**
     * @Route("/load_questions", name="_load-questions")
     * 
     */   
    public function loadQuestion(Request $request)
    {
             $data = array();
             $temp = array();
             $session = $request->getSession();

             $categories = $this->getDoctrine()
                      ->getRepository('AdminMaintenanceBundle:Category')
                      ->findByIdconcessionaire($session->get('conc_id'));

             foreach ($categories as $key => $category) {                 
                 $question_object = $this->getDoctrine()
                      ->getRepository('AdminMaintenanceBundle:Questions')
                      ->findAllQuestions($category);
               
                 foreach ($question_object as $key => $question)
                 {    
                     
                     $q_type = $question->getType();
                     $q_id = $question->getId();    
                     $options = array();
                     if($q_type == 'combobox'){
                        $answers = $this->getDoctrine()
                            ->getRepository('FeedbackSurveyFormBundle:Options')
                            ->findBy(array('qId' => $q_id));
                        
                        if($answers)
                            foreach ($answers as $key => $opt) 
                                $options[] = $opt->getOptionDesc();
                     }
                     // $temp['id'] = $question->getId();    
                     // $temp['description'] = $question->getDescription();
                     // $temp['type'] = $q_type;
                     $temp = array(
                        'id' => $q_id,
                        'description' => $question->getDescription(),
                        'type' => $q_type,
                        'optional' => $question->getOptional(),
                        'options' => $options
                     );
                     $data[$category->getCategoryName()][] = $temp;
                     
                     unset($temp);
                }
             
                unset($question_object);

             }                                                      
             return new JsonResponse($data);
    }

    /**
     * @Route("/save_answers", name="_save-answers")
     */   
     public function saveAnswers(Request $request)
     {
         $session = $request->getSession();  
         $answers = json_decode($request->request->get('data'));
         $user = $session->get('username');

        try
        {       
             $em = $this->getDoctrine()->getManager();
             $datetime = new \Datetime();
             $question_repo = $this->getDoctrine()->getRepository('AdminMaintenanceBundle:Questions');

             foreach($answers as $qid => $answer)
             {
                 $employee_answers = new EmployeeAnswers();
                 $question = $question_repo->findOneById($qid);
                 if( !$question )
                    throw $this->createNotFoundException(
                        'No question found for id '.$qid
                    );
                if ( "" == pg_escape_string($user) )
                    $user = "Anonymous";
                
                 $employee_answers->setUsername($user);
                 $employee_answers->setValue($answer);
                 $employee_answers->setQ($question);
                 $employee_answers->setActionstamp($datetime);
                $em->persist($employee_answers);
            }
        
            $conc_id = $session->get('conc_id'); 
            if ( $this->sendNotification($user, $conc_id) ) {
                
                $em->flush();
                $em->clear();
                
                return new JsonResponse(array('data' => 'Successfully Saved', 'status'=>'success'));
            }
            else
            {
                return new JsonResponse(array('data' => 'Form Submission failed. please re-submit', 'status'=> false)); 
            }
        }
        catch( Exception $e )         
        {
            $log = $this->get('logger');
            $log->error(json_encode($e));
            die(json_encode($e));
        }
     }
    
    /**
     * @Route("/thank_you", name="_thank-you")
     * 
     */  
     
    public function thankAction(request $request)
    {
        
        $session = $request->getSession();
        
        if($session->has('username'))
        {
           $session->clear();             
           return $this->render('FeedbackUserBundle:Default:thanky_you.html.twig');
        }
        else
           return $this->redirect($this->generateUrl('_login')); 
        
    }
    
    /**
     * @Route("/test", name="_test")
     */   
    /*public function test() {
        $session = $this->get('session');  
        $user = $session->get('username');
        $conc_id = $session->get('conc_id');
         
        $this->sendNotification('$user', 57);//$conc_id);
        return new JsonResponse(array("msg" => ($this->sendNotification('$user', 14)) ? "yes" : "no"));
    }*/
    
    public function sendNotification($user, $conc_id)
    {
        try
        {
            $locator = new FileLocator('/db/webuser/html/online_feedback/src/Feedback/SurveyFormBundle/Resources/public');
            $email_template_path = $locator->locate('email_template.html', null, true);
                
            $mail = new PHPMailer();
            $mail->From         =   'applications.development@concentrix.com';
            $mail->FromName     =   'Online Feedback System';
            $mail->Subject      =   'Online Feedback Survey - '.$user;
            //$mail->Host           =   "10.120.1.6";
            //$mail->Host           =   "10.100.1.158";
            $mail->Host         =   "10.1.6.153";
            //$mail->Port           = 26;
        
            $conc_id = pg_escape_string($conc_id);
            if ( $conc_id != "" )
                $conc_name = $this->getConcessionaireName($conc_id);
            else
                return false;
            if ( $conc_name == '' )
                return false;
            $datetime = new \Datetime();
            $date = $datetime->format("Y-m-d H:i:s");
            //$html = "A $conc_name survey was taken by $user last $date.";
            $html = file_get_contents($email_template_path);
        
            $mail->Body         =   $this->fillEmailTemplate($html, $conc_name, $user, $date);
            $mail->Mailer       =   "smtp";
            //$mail->SMTPDebug = 2;
            $mail->isHTML(true);
            //$mail->IsSMTP();
            
            /*$mail->ClearAddresses();
            $mail->ClearCCs();
            $mail->ClearBCCs();
            $mail->ClearReplyTos();
            $mail->ClearAllRecipients();
            $mail->ClearAttachments();
            $mail->ClearCustomHeaders();*/
            
            $recipients = $this->getRecipients();
            if ( !$recipients['success'] )
                return false;
            foreach( $recipients['data'] as $recipient )
            {
                $mail->AddAddress($recipient);
            }
            //$mail->AddAddress('reymar.guerrero@concentrix.com');
            if ( ! $mail->send() ) {
                return false;
            }
        }
        catch ( Exception $e )
        {
            $log = $this->get('logger');
            $log->error(json_encode($e));
            return false;
        }
        
        //die(json_encode(array('success' => true, 'msg' => 'Successfully email survey details to tantyrohunter@gmail.com.', 'data' => '1')));
        return true;
    }
    
    function getConcessionaireName($conc_id) {
        try
        {
            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findByIdconcessionaire($conc_id);
            if ( !empty($repo) ) {
                return $repo[0]->getDescription();
            }
            throw $this->createNotFoundException(implode(", ", $conc)." Invalid input. $conc_id not found.");
        }
        catch ( Exception $e )
        {
            $logger = $this->get('logger');
            $logger->error(json_encode($e));
            die(json_encode($e));   
        }
    }

    function fillEmailTemplate($html, $conc_name, $user, $date) {
        $html = str_replace('%ConcessionaireValue%', $conc_name, $html);
        $html = str_replace('%UserValue%', $user, $html);
        $html = str_replace('%DateSubmittedValue%', $date, $html);
        
        $href = 'dev-online-feedback.concentrix.com/cx-admin';
        $html = str_replace('%href%', $href, $html);
        
        return $html;
    }
    
    function getRecipients() {
        $retVal = array('success' => false);
        try
        {
            $em = $this->getDoctrine()->getManager();
            $repos = $em->getRepository("AdminEmailRecipientBundle:EmailRecipient")->findBy(
                array('active' => true)
            );
            $retVal['data'] = array();
            if ( !empty($repos) )
            {
                foreach( $repos as $repo ) {
                    array_push($retVal['data'], $repo->getEmail());
                    array_push($retVal['data'], $repo->getEmail());
                }
            }
            $retVal['success'] = true;
            return $retVal;
        }
        catch ( Exception $e )
        {
            $log = $this->get('logger');
            $log->error(json_encode($e));
            die(json_encode($e));
        }
        return $retVal;
    }
    
    /*tyr 20141209 1041 */
    /**
     * @Route("/tyr-email-test", name="_tyr_email_test")
     */
     
    public function tyrEmailTest(Request $req) {
        $pw = $req->query->get('password');
        if ( empty($pw) || $req->query->get('password') !== 'tyro123' )
            die("stop!\n");
     $mail = new PHPMailer();
     $mail->From        = "helpdesk_ph@concentrix.com";
     //$mail->From      = "applications.development@concentrix.com";
     //$mail->From      = "applications.development@concentrix.com";         
     $mail->FromName    = "Concentrix Survey - tyro test";       
     $mail->Subject     = '$FormData["subject"].$Resend';
     //tyr 20141205 1544 $mail->Host        = "mail.link2support.com";
     $mail->Host        = "zmsrvsg-ph-mstore01.concentrix.com";
     //$mail->Host      = "10.1.6.153";
     //$mail->Body      = '$Html from zmesarviesji-ph-mstore01 please tell/forward to tyro. plssss :('.$mail->Host;
     //$mail->Mailer        = "smtp";
     $mail->IsSMTP();
     $mail->isHTML(true);
     //$mail->AddAddress("john.mangornong@concentrix.com"); // for UTP testing 
     //$mail->AddAddress("orvin.gulleban@concentrix.com"); // for UTP testing 
     $mail->AddAddress("reymar.guerrero@concentrix.com"); // for UTP testing 
     $mail->AddAddress("tyro.tan@concentrix.com"); // for UTP testing 
     $mail->AddAddress("erwin.baldoman@concentrix.com"); // for UTP testing
//   $mail->AddAddress($EmployeeData->getEmailAddress(), ucwords(strtolower($EmployeeData->getFirstname()." ".$EmployeeData->getLastname())));
     //$mail->AddAddress("applications.development@concentrix.com");
     
     $strdata = '';
     foreach($mail as $k => $v) {
        $strdata .= $k.' = '.$v.",<br>";
     }
     $mail->Body = $strdata; 
     
     if (!$mail->Send()) {
        die("i was NOT called\n".$mail->ErrorInfo);
     }
     
     die("i got called\n");
  }
}
