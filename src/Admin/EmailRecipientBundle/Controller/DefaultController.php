<?php
/**
* DefaultController - Controller class for Email Recipients Module
*
*
* @package 
* @subpackage class
* @author tyro.tan@concentrix.com
*/

namespace Admin\EmailRecipientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Admin\UserManagementBundle\Entity\SysUserActionLogs;
use Admin\EmailRecipientBundle\Entity\EmailRecipient;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="_email_recipients")
	 * @Template()
	 */
    public function indexAction()
    {
    	if ( !$this->isAuthorized() )
			return $this->redirect($this->generateUrl('_login'));
		
		$session = $this->get('session');
		
        return $this->render('AdminEmailRecipientBundle:Default:index.html.twig',
	        array('modules' => $this->getSideBarDataPerRole(),
					  'username' => $session->get('username')
			)
		);
    }
	
	/**
	 * @Route("/load-email", name="_load_email")
	 */
	public function loadEmail(Request $req)
	{
    	if ( !$this->isAuthorized() )
			return $this->redirect($this->generateUrl('_login'));
		$rquery = $req->query;
		$limit = pg_escape_string($rquery->get('limit'));
		$start = pg_escape_string($rquery->get('start'));
		$page = pg_escape_string($rquery->get('page'));
		$offset = $limit * $page - $limit;
		
		if ( $json_sort = $rquery->get('sort') )
		{
			$sort = json_decode(pg_escape_string($json_sort));
			$array_of_sort = array($sort[0]->property => $sort[0]->direction);
		}
		else
			$array_of_sort = array('active' => 'DESC', 'email' => 'ASC');
	
		
		
		$repo = $this->getDoctrine()->getRepository("AdminEmailRecipientBundle:EmailRecipient")
				->findBy(array(),
						 $array_of_sort,
						 $limit,
						 $offset);
		$repo_all =  $this->getDoctrine()->getRepository("AdminEmailRecipientBundle:EmailRecipient")
				->findAll();
		$retVal = array('data' => array(), 'totalCount' => count($repo_all));
		foreach( $repo as $item) {
			array_push($retVal['data'], array(
					'id' => $item->getId(),
					'email' => $item->getEmail(),
					'active' => $item->getActive()
				)
			);
		}
		return new JsonResponse($retVal);
	}	
	
	/**
	 * @Route("/save-recipient", name="_save_recipient")
	 */
	public function saveRecipient(Request $req)
	{
    	if ( !$this->isAuthorized() )
			return $this->redirect($this->generateUrl('_login'));
		
		$request = $req->request;
		
		$active = $request->get('active');
		if ( !empty($active) )
		{
			$active_lower = strtolower($request->get('active'));
			if ( $active_lower != 'false' && $active_lower != 'true' )
			{
				$retVal = array('success' => false, 'msg' => "Invalid active field.");
				return new JsonResponse($retVal);
			}
		}
		else
		{
			$retVal = array('success' => false, 'msg' => "Active field is required.");
			return new JsonResponse($retVal);
		}
		
		$mail = $this->validEmail($request);
		if ( !$mail['success'] )
			return new JsonResponse($mail);
			
		try
		{
		$em = $this->getDoctrine()->getManager();
		$insert = new EmailRecipient();
		$insert->setEmail($request->get('email'));
		$insert->setActive($active);
		$dateTime = new \Datetime();
		$insert->setCreatedAt($dateTime);
		
		$requestSerialized = $this->requestParamsSerializer($req);
		$session = $this->get('session');
		$user_logs = new SysUserActionLogs();
		$user_logs->setUsername($session->get('username'));
		$user_logs->setModule(__METHOD__);
		$user_logs->setMethod(__FUNCTION__);
		$user_logs->setAffectedData(json_encode($requestSerialized));
		$user_logs->setActionstamp($dateTime);
		
		$em->persist($insert);
		$em->persist($user_logs);
		$em->flush();
		}
		catch( Exception $e)
		{
			return new JsonResponse(array('success' => false, 'msg' => json_encode($e)));
		}
		
		
		return new JsonResponse(array('success' => true));		
	}
	
	/**
	 * @Route("/update-recipient", name="_update_recipient")
	 */
	public function updateRecipient(Request $req)
	{
    	if ( !$this->isAuthorized() )
			return $this->redirect($this->generateUrl('_login'));
		
		$request = $req->request;
		
		$active = $request->get('active');
		if ( !empty($active) )
		{
			$active_lower = strtolower($request->get('active'));
			if ( $active_lower != 'false' && $active_lower != 'true' )
			{
				$retVal = array('success' => false, 'msg' => "Invalid active field.");
				return new JsonResponse($retVal);
			}
		}
		else
		{
			$retVal = array('success' => false, 'msg' => "Active field is required.");
			return new JsonResponse($retVal);
		}
		
		$mail = $this->validEmail($request, 0);
		if ( !$mail['success'] )
			return new JsonResponse($mail);
		
		if ( !is_integer(+($request->get('id'))) || !filter_var($request->get('oldEmail'), FILTER_VALIDATE_EMAIL) )
		{
			return new JsonResponse(array('success' => false, 'status' => 500, 'msg' => "Internal Server Error."));
		}
		
		try
		{
			$em = $this->getDoctrine()->getManager();
			$repo = $em->getRepository("AdminEmailRecipientBundle:EmailRecipient")->find($request->get('id'));
			if ( !$repo ) {
				return new JsonResponse(array('success' => false, 'status' => 500, 'msg' => "Fatal error : id not found"));
			}
			$repo->setActive($request->get('active'));
			$repo->setEmail($request->get('email'));
			
			$session = $this->get('session');
			$requestSerialized = $this->requestParamsSerializer($req);
			$user_logs = new SysUserActionLogs();
			$user_logs->setUsername($session->get('username'));
			$user_logs->setModule(__METHOD__);
			$user_logs->setMethod(__FUNCTION__);
			$user_logs->setAffectedData(json_encode($requestSerialized));
			$datetime = new \Datetime();
			$user_logs->setActionstamp($datetime);
			$user_logs->setAffectedId($req->request->get('id'));
			
			$em->persist($user_logs);
			$em->persist($repo);
			$em->flush();
		}
		catch ( Exception $e )
		{
			$logger = $this->get('logger');
			$msg = 'Exception fn "updateRecipient" internal server error : '.json_encode($e);
			$logger->error($msg);
			return new JsonResponse(array('success' => false, 'status' => 500, 'msg' => $msg));
		}
		
		return new JsonResponse(array('success' => true));
	}
	
	/**
	 * @Route("/delete-recipient", name="_delete_recipient")
	 */
	public function deleteRecipient(Request $req)
	{
    	if ( !$this->isAuthorized() )
			return $this->redirect($this->generateUrl('_login'));
		
		$id = $req->request->get('id');
				
		if ( empty($id) || !is_integer(+$id) ) 
		{
			$retVal = array('success' => false, 'msg' => "Invalid input.");
			return new JsonResponse($retVal);
		}
		
		try 
		{
			$em = $this->getDoctrine()->getManager();
			$repo = $em->getRepository("AdminEmailRecipientBundle:EmailRecipient")->findById($id);
			
			if ( empty($repo) ) {
				throw $this->createNotFoundException(
                    'No recipient found for id '.$id 
                );
			}
			
			$session = $this->get('session');
			$requestSerialized = $this->requestParamsSerializer($req);
			$user_logs = new SysUserActionLogs();
			$user_logs->setUsername($session->get('username'));
			$user_logs->setModule(__METHOD__);
			$user_logs->setMethod(__FUNCTION__);
			$user_logs->setAffectedData(json_encode($requestSerialized));
			$datetime = new \Datetime();
			$user_logs->setActionstamp($datetime);
			$user_logs->setAffectedId($id);			
			
			$em->persist($user_logs);
			$em->remove($repo[0]);
			$em->flush();
		}
		catch(Exception $e)
		{
			return new JsonResponse(array('success' => false,
				'status' => 500,
				'msg' => "Internal Server Error."));
		}
		return new JsonResponse(array('success' => true));		
	}
	
	function validEmail($request, $isAdd = 1) {
		$email = $request->get('email');
		$retArr = array('success' => false, 'status' => 403, 'msg' => "");
		
		if ( empty( $email ) )
		{
			$retArr['msg'] = "Email is required.";
			return $retArr;
		}
		
		// invalid email format
		else if ( !filter_var($email, FILTER_VALIDATE_EMAIL) )
		{
			$retArr['msg'] = "Invalid E-mail format.";
			return $retArr;
		}
		
		try
		{
			$em = $this->getDoctrine()->getManager();
			$query = $em->createQuery("Select LOWER(ue.email) FROM Admin\EmailRecipientBundle\Entity\EmailRecipient
				ue WHERE LOWER(ue.email) = LOWER('".pg_escape_string($email)."')");
				
			$result = $query->getArrayResult();				
			if ( !empty($result) && $isAdd ) {
				$retArr['msg'] = "E-mail is not available.";
				return $retArr;
			}
		}
		catch ( Exception $e)
		{
			$retArr['msg'] = "Internal server error.";
			$retArr['status'] = 500;
			return $retArr;
		}
		
		$retArr['success'] = true;
		return $retArr;
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
		
		return $data;
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
	
	function isAuthorized() {
		$session = $this->get('session');
			//if ( $session->get('username') == "Administrator" )
			if ( $session->get('role') == "admin" )
				return true;
			
		return false;
	}
}
