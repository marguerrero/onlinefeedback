<?php

namespace Admin\LogsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\JsonResponse;
use Admin\LogsBundle\DependencyInjection\SysUserActionLogsSerializer;
use Admin\LogsBundle\Entity\SysUserActionLogs;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="_logs")
	 * @Template()
	 */
    public function indexAction()
    {
    	return $this->redirect($this->generateUrl('_login'));
		
        //return $this->render('AdminLogsBundle:Default:index.html.twig', array('modules' => $this->getSideBarDataPerRole()));
    }
	
	/**
	 * @Route("/load-logs", name="_load_logs")
	 */
	/*public function loadLogs()
	{
		if ( !$this->isAuthorized() ) {
			$this->redirect($this->generateUrl('_login'));
		}
		try
		{
			$em = $this->getDoctrine()->getManager();
			$repo = $em->getRepository("AdminLogsBundle:SysUserActionLogs")->findAll();
			$repoSerialized = new SysUserActionLogsSerializer($repo);
			
			$retval = array('success' => true, 'data' => $repoSerialized->jsonSerialize());
			return new JsonResponse($retval);
		}
		catch( Exception $e )
		{
			return new JsonResponse(array('success' => false, 'msg' => json_encode($e)));	
		}
	}*/
	
	/*function getSideBarDataPerRole()
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
		}* /
		if ( $session->get('role') == 'admin' )
		{
			array_push($data, array('name' => "Maintenance", 'path' => "_maintenance"));
			array_push($data, array('name' => "User", 'path' => "_user"));
			array_push($data, array('name' => "Email Recipients", 'path' => "_email_recipients"));
			//array_push($data, array('name' => "Logs", 'path' => "_logs"));
		}
		
		return $data;
	}*/
	
	/*function isAuthorized()
	{
		$session = $this->get('session');
			//if ( $session->get('username') == "Administrator" )
			if ( $session->get('role') == "admin" )
				return true;
			
		return false;
	}*/
}
