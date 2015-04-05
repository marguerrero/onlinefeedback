<?php

namespace Admin\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Admin\UserManagementBundle\DependencyInjection\Bcrypt;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_cx_admin")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

     /**
     * @Route("/cx-admin-check", name="_cx_admin_check")
     * @param \Request $request
     */
    public function cxAdminCheck(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
		$user_auth = $this->bcrypt_auth($username, $password);
		$redirection = $this->redirect($this->generateUrl('_cx_admin')); 
		
		if ( $user_auth['valid'] )
		{
			$session = $this->get('session');
	        $session->start();
			$session->set('role', $user_auth['role']);
			$session->set('username', $username);
			
			if ( $user_auth['role'] == 'report' )
				$redirection = $this->redirect($this->generateUrl('_report'));
			else
				$redirection = $this->redirect($this->generateUrl('_maintenance'));
			
			// return $this->redirect($this->generateUrl('_maintenance'));
		}
		
		return $redirection;
    }
    
     /**
     * @Route("/cx-admin-logout", name="_cx_admin_logout")
     * @param \Request $request
     */
    public function logoutAction(Request $request)
    {
        $session = $this->get('session');
        
        $session->clear();
        
        return $this->redirect($this->generateUrl('_cx_admin'));
    }
	
	public function bcrypt_auth($username, $password) {
		$bcrypt = new Bcrypt(15);
		$db_pass = 'dummy';
		$user_found = 0;
		try
		{
			$em = $this->getDoctrine()->getManager();
			$user = $em->getRepository("FeedbackSurveyFormBundle:UserAccount")->findByUsername($username);
			if ( !empty($user) ) {
				$user_found = 1;
				$db_pass = $user[0]->getPassword();
			}
			$matched = $bcrypt->verify($password, $db_pass);
			
			if ( $user_found && $matched && $user[0]->getActive()) {
				return array('valid' => true, 'role' => $user[0]->getUserRole());
			}
		}
		catch( Exception $e )
		{
			return array('valid' => false, 'error' => json_encode($e));
		}
		
		return array('valid' => false);
	}
}
