<?php
/**
* DefaultController - Controller for User login
*
*
* @package 
* @subpackage class
* @author reymar.guerrero@concentrix.com
*/
namespace Feedback\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

use Admin\MaintenanceBundle\DependencyInjection\ConcessionaireSerializer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_login")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $conc_repo = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findAll();
        $concs = new ConcessionaireSerializer($conc_repo);
        $retval = array('survey_type'=> $concs->jsonSerialize());
        
        return $retval;
    }

    /**
     * @Route("/login_check", name="_login_check")
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
        
        $user_authentication->setLoginInfo($username, $password);
        
        if(!$conc_selected){
            $response = new JsonResponse();
            $response->setData(array('msg' => "Survey type is required", 'status' => 'invalidSelect'));
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

    /**
     * @Route("/load-login-concessionaire", name="_load_login_concessioanire")
     */
    public function loadLoginConcessionaire(Request $reqeust) {
        $em = $this->getDoctrine()->getManager();
        $conc_repo = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findAll();
        
        $concs = new ConcessionaireSerializer($conc_repo);

        //echo json_encode(array('data'=>$item));
        $retval = array('data'=>$concs->jsonSerialize(), 'totalCount' => count($conc_repo));
        
        $response = new Response(json_encode($retval));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;       
    }

    public function isValidSelects($string) {
        // only alphaNumberic hyphen dash...
        $isValid = true;
        $msg = "";
        $data = array();
        if ( empty($string) )
        {
            return array('isValid' => false, "msg" => "Concessionaire Field is Required.");
        }
        else if ( !is_string($string) || preg_match("/[^,;a-zA-Z0-9_-]|[,;]$/s", $string) )
        {
            return array('isValid' => false, "msg" => "Invalid Characters on Input. $string.");
        }
    
        try {
            $em = $this->getDoctrine()->getManager();
            $conc = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findByDescription($string);
            if ( empty($conc) )  {
                //throw $this->createNotFoundException(
                    $msg = implode(", ", $conc)." Invalid input. $string not found.";
                    $isValid = false;
                //);
            }
            else 
                $data = $conc[0]->getIdconcessionaire();
        } catch (Exception $e) {
             $msg = "Invalid input: ".$e;
        }
        
        return array('isValid' => $isValid, "msg" => $msg, "data" => $data);
    }

    /**
     * @Route("/tyr_test", name="_tyr_test")
     * @param \Request $request
     */
    public function tyrTest(Request $request)
    {
        die("with nothing");
    }
}
