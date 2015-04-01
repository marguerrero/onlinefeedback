<?php
/**
* DefaultController - Controller class for User Management Module
*
*
* @package 
* @subpackage class
* @author tyro.tan@concentrix.com
*/

namespace Admin\UserManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Admin\UserManagementBundle\Entity\UserAccount;
use Admin\UserManagementBundle\Entity\SysUserActionLogs;

use Admin\UserManagementBundle\DependencyInjection\Bcrypt;
use Admin\UserManagementBundle\DependencyInjection\UserAccountSerializer;


class DefaultController extends Controller
{
	/**
	 * @Route("/", name="_user")
	 * @Template()
	 */
    public function indexAction()
    {
    	if ( $this->isAdministrator()['not'] )
			return $this->redirect($this->generateUrl('_login'));
		 
		$session = $this->get('session'); 
		  
        //return array();
		return $this->render('AdminUserManagementBundle:Default:index.html.twig',
			array('modules' => $this->getSideBarDataPerRole(),
				  'username' => $session->get('username')
			)
		);
    }
	
	/**
	 * @Route("/save-user", name="_save_user")
	 */
	public function saveUser(Request $req)
	{
		if ( $this->isAdministrator()['not'] ) {
			return $this-isAdministrator()['response'];
		}
		
		$isValidFormReturn = $this->isValidForm($req);
		if ( $isValidFormReturn['not'] ) {
			return $this->response400($isValidFormReturn['msg']);
		}

		try
		{
			$em = $this->getDoctrine()->getManager();
			$SysUserActionLogs = $em->getRepository("AdminUserManagementBundle:SysUserActionLogs");
			$requestSerialized = $this->requestParamsSerializer($req);
			$session = $this->get('session');
			$user_logs = new SysUserActionLogs();
			$user_logs->setUsername($session->get('username'));
			$user_logs->setModule(__METHOD__);
			$user_logs->setMethod(__FUNCTION__);
			$user_logs->setAffectedData(json_encode($requestSerialized));
			$datetime = new \Datetime();
			$user_logs->setActionstamp($datetime);
										
			$em = $this->getDoctrine()->getManager();
			$accounts = $em->getRepository("AdminUserManagementBundle:UserAccount");
			$new_user = new UserAccount();
			$bcrypt = new Bcrypt(15);			
			
			//$user_logs->setAffectedId($SysUserActionLogs->getNextId());
			$user_logs->setAffectedId($accounts->getNextId());
						
			$new_user->setId($accounts->getNextId());
			$new_user->setUsername($req->request->get('username'));
			$new_user->setUserRole($req->request->get('user_role'));
			$new_user->setPassword($bcrypt->hash($req->request->get('password')));
			$new_user->setEmail($req->request->get('email'));
			$new_user->setActive($req->request->get('active'));
			
	        $em->persist($new_user);
	        $em->persist($user_logs);
	        $em->flush();
				
			//$retVal = array("success" => true, "data" => new UserAccountSerializer($accounts), "total_count" => 1);
			$retVal = array("success" => true);		
	    }
		catch (Exception $e)
		{
			$retVal = array("success" => false, 'error' => 'exception', 'status' => 500, 'msg' => json_encode($e));
			return new JsonResponse($retVal);
		}
		
		$response = new JsonResponse($retVal);
		return $response;
	}

	/**
	 * @Route("/update-user", name="_update_user")
	 */
	public function updateUser(Request $req)
	{
		if ( $this->isAdministrator()['not'] ) {
			return $this-isAdministrator()['response'];
		}
		
		$isValidFormReturn = $this->isValidForm($req);
		if ( $isValidFormReturn['not'] ) {
			return $this->response400($isValidFormReturn['msg']);
		}
		
		try
		{
			$em = $this->getDoctrine()->getManager();
			$user = $em->getRepository("AdminUserManagementBundle:UserAccount")
				->findByUsername(pg_escape_string($req->request->get('oldUsername')));
				
			if ( !empty($user) )
			{
				$data = $req->request;
				$bcrypt = new Bcrypt(15);
				
				if ( $data->get('username') != "Administrator" && $data->get('oldUsername') == "Administrator" )
				{
					return $this->response400("Please contact the administrator.");
				}
				
				//$user[0]->setId($em->getRepository("AdminUserManagementBundle:UserAccount")->getNextId());
				$user[0]->setId($user[0]->getId());
				$user[0]->setUsername($data->get('username'));
				$user[0]->setEmail($data->get('email'));
				$user[0]->setUserRole($data->get('user_role'));
				
				$pass = $data->get('password');
				if ( !(empty($pass)) )
					$user[0]->setPassword($bcrypt->hash($pass));
				$user[0]->setActive($data->get('active'));
				
				$SysUserActionLogs = $em->getRepository("AdminUserManagementBundle:SysUserActionLogs");
				$requestSerialized = $this->requestParamsSerializer($req);
				$session = $this->get('session');
				$user_logs = new SysUserActionLogs();
				$user_logs->setUsername($session->get('username'));
				$user_logs->setModule(__METHOD__);
				$user_logs->setMethod(__FUNCTION__);
				$user_logs->setAffectedId($user[0]->getId());
				$user_logs->setAffectedData(json_encode($requestSerialized));
				$datetime = new \Datetime();
				$user_logs->setActionstamp($datetime);
			
		        $em->persist($user_logs);
		        $em->persist($user[0]);
		        $em->flush();	
			}
		} catch ( Exception $e ) {
			$logger = $this->get('logger');
			$msg = 'Exception fn "deleteUser" internal server error : '.json_encode($e);
			$logger->error($msg);	
			return new JsonResponse(array('success' => false, 'status' => 500, 'msg' => $msg));
		}
				
		//$retVal = array("success" => true, "data" => new UserAccountSerializer($accounts), "total_count" => 1);
		$retVal = array("success" => true);
		
		$response = new JsonResponse($retVal);
		return $response;
	}

	/**
	 * @Route("/delete-user", name="_delete_user")
	 */
	public function deleteUser(Request $req)
	{
		if ( $this->isAdministrator()['not'] ) {
			return $this-isAdministrator()['response'];
		}
		
		$username = $req->request->get('username');
		
		// temporary
		// for static super admin user (Administrator)
		if ( $username == "Administrator" )
		{
			return $this->response400("Please contact the administrator.");
		}		
		
		if ( !empty($username) ) 
			try
			{
				$em = $this->getDoctrine()->getManager();
				$repo = $em->getRepository("AdminUserManagementBundle:UserAccount")->findByUsername($username);
				if ( empty($repo) )
				{
					return $this->response400("Username not found.");
				}
				$SysUserActionLogs = $em->getRepository("AdminUserManagementBundle:SysUserActionLogs");
				$requestSerialized = $this->requestParamsSerializer($req);
				$session = $this->get('session');
				$user_logs = new SysUserActionLogs();
				$user_logs->setUsername($session->get('username'));
				$user_logs->setModule(__METHOD__);
				$user_logs->setMethod(__FUNCTION__);
				$user_logs->setAffectedId($repo[0]->getId());
				$user_logs->setAffectedData(json_encode($requestSerialized));
				$datetime = new \Datetime();
				$user_logs->setActionstamp($datetime);
				
				$em->remove($repo[0]);
				$em->persist($user_logs);
				$em->flush();
			}
			catch ( Exception $e )
			{
				$logger = $this->get('logger');
				$logger->error('Exception fn "deleteUser" internal server error : '.json_encode($e));	
			}
		else
		{
			return $this->response400("Username is required.");
		}
		
		return new JsonResponse(array('success' => true));
	}
	
	/**
	 * @Route("/load-user", name="_load_user")
	 */
	public function loadUser(Request $request)
	{
		if ( $this->isAdministrator()['not'] )
			return $this->isAdministrator()['response'];
		$rquery = $request->query;
		$limit = pg_escape_string($rquery->get('limit'));
		$start = pg_escape_string($rquery->get('start'));
		$json_sort = pg_escape_string($rquery->get('sort'));
		$offset= pg_escape_string($rquery->get('page')) * $limit - $limit;
		
		try
		{
			$em = $this->getDoctrine()->getManager();
			$totalCount = 0;
			if ( !$this->isSuperUser() )
			{
				$accounts = $em->getRepository("AdminUserManagementBundle:UserAccount")->findAllButAdministrator($start, $limit, $json_sort);
				$totalCount--;
			}
			else
			{
				if ( ! (empty($json_sort)) )
				{
					$sort = json_decode($json_sort);
					$sortArr = array($this->serializeProperty($sort[0]->property) => $sort[0]->direction);
				}
				else
					$sortArr = array();
				$accounts = $em->getRepository("AdminUserManagementBundle:UserAccount")->findBy(array(), $sortArr, $limit, $offset);
			}
			$allUser = $em->getRepository("AdminUserManagementBundle:UserAccount")->findAll();
			$totalCount += count($allUser);
			
			$retVal = array("data" => new UserAccountSerializer($accounts), "totalCount" => $totalCount);
			
			$response = new JsonResponse($retVal);
			return $response;
		}
		catch( Exception $e)
		{
			return new JsonResponse(array('success' => false, 'msg' => json_encode($e)));
		}
	}

	function response400($msg)
	{
		return new JsonResponse(array('success' => false, 'status' => 400, "msg" => $msg ));
	}

	function isValidForm($req)
	{
		$invalid_arr = array('not' => true, 'msg' => "");
		$getFieldValidity = $this->isFieldValid('username', pg_escape_string($req->request->get("username")), $req);
		if ( !$getFieldValidity['success'] )
		{
			$invalid_arr['msg'] = $getFieldValidity['msg'];
			return $invalid_arr;
		}
		
		$getFieldValidity = $this->isFieldValid('password', pg_escape_string($req->request->get("password")), $req);
		if ( !$getFieldValidity['success'] )
		{
			$invalid_arr['msg'] = $getFieldValidity['msg'];
			return $invalid_arr;
		}
		
		$getFieldValidity = $this->isFieldValid('email', pg_escape_string($req->request->get("email")), $req);
		if ( !$getFieldValidity['success'] )
		{
			$invalid_arr['msg'] = $getFieldValidity['msg'];
			return $invalid_arr;
		}
		
		$invalid_arr['not'] = false;
		return $invalid_arr;
	}
	
	function isFieldValid($field, $input, $req)
	{
		$default_return = array('success' => false, 'msg' => "");
		$input = pg_escape_string($input);
		
		if ( $field == 'username' ) {
			if ( empty($input) )
			{
				
				return $default_return;				
			}
			// regex = only allow alphanumeric underscore
			else if ( preg_match("/[^,;a-zA-Z0-9_\.]|[,;]$/s",  $input) )
			{
				return $default_return;
			}
			
			try
			{
				$em = $this->getDoctrine()->getManager();
				$repo = $em->getRepository("AdminUserManagementBundle:UserAccount")->findByUsername($input);
				if ( $req->request->get('oldUsername') != $input && !empty($repo) )
				{
					$default_return['msg'] = "Username already exists.";
					return $default_return;
				}
			}
			catch(Exception $e)
	        {
	        	$logger = $this->get('logger');
			    $logger->info('I just got the logger');
			    $logger->error('Exception fn "isFieldValid" internal server error : '.json_encode($e));
				
				$default_return['msg'] = "Internal Server Error: ".json_encode($e);
				return $default_return;
	        }
			
		}
		else if ( $field == 'password' )
		{
			//it means its update and password is "as is"
			$hasUsername = $req->request->get('oldUsername');
			if ( empty($input) && !(empty($hasUsername)) )
			{
				
				$default_return['success'] = true;
				return $default_return;
			}
		}
		else if ( $field == 'email' )
		{
			if ( !filter_var($input, FILTER_VALIDATE_EMAIL) )
			{
				$default_return['msg'] = "Invalid E-mail format.";
				return $default_return;
			}
			
			try
			{
				$em = $this->getDoctrine()->getManager();
				$repo = $em->getRepository("AdminUserManagementBundle:UserAccount")->findByEmailCaseInsensitive($input);
				if ( $req->request->get('oldEmail') != $input && !empty($repo) )
				{
					$email = $repo[0]->getEmail();
					if ( $email == $input )
					{
						$default_return['msg'] = "E-mail is not available.";
						return $default_return;
					}
				}
			}
			catch(Exception $e)
	        {
	        	$logger = $this->get('logger');
			    $logger->info('I just got the logger');
			    $logger->error('Exception fn "isFieldValid" internal server error : '.json_encode($e));
				
				$default_return['msg'] = "Internal Server Error: ".json_encode($e);
				return $default_return;
	        }
		}
		
		$default_return['success'] = true;
		return $default_return;
	}

	function getSideBarDataPerRole()
	{
		$session = $this->get('session');
		//$role = $session->get('role');
		$data = array(array('name' => "Report", 'path' => "_report"));
		/*if ( $session->get('role') == 'admin' )
			array_push($data, array('name' => "Maintenance", 'path' => "_maintenance"));
		if ( $session->get('username') == 'Administrator' )
		{
			array_push($data, array('name' => "User", 'path' => "_user"));
			array_push($data, array('name' => "Email Recipients", 'path' => "_email_recipients"));
		}*/
		if ( $session->get('role') == 'admin' )
		{
			array_push($data, array('name' => "Maintenance", 'path' => "_maintenance"));
			array_push($data, array('name' => "User", 'path' => "_user"));
			array_push($data, array('name' => "Email Recipients", 'path' => "_email_recipients"));
			//array_push($data, array('name' => "Logs", 'path' => "_logs"));
		}
		
		/*return $this->render(
			'/base.html.twig',
			array('modules' => $data)
		);*/
		return $data;
	}
	
	function isAdministrator()
	{
		$session = $this->get("session");
		//if ( $session->get("username") != "Administrator" )
		if ( $session->get("role") == "admin" )
		{
			return array('not' => false);
		}
		
		return array('not' => true,
				'response' => new JsonResponse(array('success' => false, 'status' => 403))
				);
	}
	
	function isSuperUser() {
		$session = $this->get('session');
		if ( $session->get('username') == 'Administrator' )
			return true;
		
		return false;
	}
	function requestParamsSerializer($req) {
		 //[username] => ty [password] => ty [confirm_password] => ty [user_role] => admin [email] => saer@weao.co [active] => true
		 $arr = array();
		 foreach($req->request as $paramKey => $paramVal) {
		 	if ( $paramKey == 'password' ) {
		 		$paramVal = $this->getAsteriskPass($paramVal);
		 		array_push($arr, array($paramKey => $paramVal));
		 	} else if( $paramKey == 'confirm_password' )
		 		;
			else {
				array_push($arr, array($paramKey => $paramVal));
			}
		 	
		 }
		 return $arr;
	}
	function getAsteriskPass($pw) {
		$len = strlen($pw);
		$asterisk_arr = array();
		while( $len-- )
		{
			array_push($asterisk_arr, '*');
		}
		
		//return implode('', $asterisk_arr);
		return strlen($pw);
	}
	
	function serializeProperty($prop) {
		if ( $prop == 'user_role' )
			return 'userRole';
		return $prop;
	}	
}

