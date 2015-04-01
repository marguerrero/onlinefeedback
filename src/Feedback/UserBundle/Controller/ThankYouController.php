<?php
/**
* DefaultController - Controller for User login
*
*
* @package Symfony2 Controller
* @subpackage class
* @author reymar.guerrero@concentrix.com
*/
namespace Feedback\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ThankYouController extends Controller
{
    /**
     * @Route("/thank_you", name="_thank_you")
     * @Template("FeedbackUserBundle:Default:thank_you.html.twig")
     */
    public function indexAction()
    {
        $session = $this->get('session');
        
        $session->clear();
        return array();
    }

    
}
