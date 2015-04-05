<?php

namespace Feedback\AnonymousBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

use Admin\MaintenanceBundle\DependencyInjection\ConcessionaireSerializer;
/**
 * @Route("/specific-survey")
 * 
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function specificSurvey(){
        $error = array(
            'title' => "Error Found:",
            'desc' => "Invalid Survey Url"
        );
        $retval = array('error' => $error);
        return $this->render('FeedbackAnonymousBundle:Default:error.html.twig', $retval); 
    }   
    /**
     * @Route("/type/{survey_id}", name="_specific_survey_type")
     * @Template()
     */
    public function indexAction($survey_id)
    {
        try {
            $crypt_key = $this->container->getParameter('crypt_key');;
            $encryption = $this->get('encryption');
            $survey_id = $encryption->decrypt($survey_id, $crypt_key);
            $em = $this->getDoctrine()->getManager();
            $numeric_check = is_numeric($survey_id);
            if(!$numeric_check)
                throw new \Exception("Invalid survey link", 1);
            $survey = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findOneBy(array('idconcessionaire'=>$survey_id));
            
            
        } catch (\Exception $e) {
            $error = array(
                'title' => "Error Found:",
                'desc' => $e->getMessage()
            );
            $retval = array('error' => $error);
            return $this->render('FeedbackAnonymousBundle:Default:error.html.twig', $retval);     
        }
        //-- manually generates the survey_id
        # M1376W1384 : 13
        # B1400Q1392 : 75
        // $hashed_id = "75";
        // $test = $encryption->encrypt($hashed_id, $crypt_key);
        // print '<pre>';
        // print_r($survey);
        // die();
        
        $retval = array(
            'survey_id' => $survey_id,
            'survey_name' => $survey->getDescription(),
            'anonymous' => 1,
            'survey_url' => $this->generateUrl('_survey'),
            'submit_url' => $this->generateUrl('_login_check_anonymous')
        );
        return $retval;
    }

    /**
     * @Route("/login-check", name="_login_check_anonymous")
     * @param \Request $request
     */
    public function loginCheck(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        $anonymous = $request->request->get('anonymous');
        $conc_selected = $request->request->get('selected_values-inputEl');
     
        $user_authentication = $this->get('user_authentication');
        $session = new Session();
        $session->start();
        // $user_authentication->setLoginInfo($username, $password);
        
        if(!$conc_selected){
            $response = new JsonResponse();
            $response->setData(array('msg' => "Concessionaire is required", 'status' => 'invalidSelect'));
            return $response;
        }
        
        $session->set('conc_id', $conc_selected);
        
        if(($anonymous))
        {
            $session->set('username', 'Anonymous');
            
            $response = new JsonResponse();
            $response->setData(array('msg' => 'You will now be redirected to the survey form', 'status' => 'success'));
            
            return $response;
        }
        else if($user_authentication->isValid())
        {
            $session->set('username', $username);
            
            $response = new JsonResponse();
            $response->setData(array('msg' => 'Authentication Successfull. You will now be redirected to the survey form', 'status' => 'success'));
            
            return $response;
            
            //return $this->redirect($this->generateUrl('_survey'));
        }
        else
        {
            $session->clear();
            return new JsonResponse(array('msg' => 'Authentication Failed', 'status' => 'failure'));
        }
    }
}
