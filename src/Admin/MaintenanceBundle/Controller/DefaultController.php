<?php
/**
* DefaultController - Controller class for Maintenance Module
*
*
* @package 
* @subpackage class
* @author reymar.guerrero@concentrix.com
*/
namespace Admin\MaintenanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Admin\MaintenanceBundle\Entity\Questions;
use Admin\MaintenanceBundle\Entity\Category;
use Symfony\Component\HttpFoundation\JsonResponse;
use Admin\MaintenanceBundle\Entity\Concessionaire;
use Admin\UserManagementBundle\Entity\SysUserActionLogs;
use Admin\MaintenanceBundle\DependencyInjection\ConcessionaireSerializer;
use Feedback\SurveyFormBundle\Entity\Options;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="_maintenance")
     * @Template()
     */
    public function indexAction()
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
        
      $session = $this->get('session');
      
          return $this->render('AdminMaintenanceBundle:Default:index.html.twig',
            array('modules' => $this->getSideBarDataPerRole(),
            'username' => $session->get('username')
        )
      );
    }

     /**
     * @Route("/load-option", name="_load_option")
     * 
     */
    public function loadOption(Request $request){
      $err_msg = "";
      $msg_info = "";
      $data = array();
      try{
        $o_id = $request->request->get('o_id');
        $o = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:Options')->find($o_id);
        $data = array(
              'option_id' => $o_id,
              'option_desc' => $o->getOptionDesc()
            );
        $msg_info = "Successfully loaded";
      } catch (\Exception $e){
        $err_msg = $e->getMessage();
      }
      $retval = array(
          'data' => $data,
          'success' => true,
          'status' => 'success'
        );
      $response = new Response(json_encode($retval));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
    /**
     * @Route("/delete-option", name="_delete_option")
     * 
     */
    public function deleteOption(Request $request){
      $err_msg = "";
      $msg_info = "";

      $o_id = $request->request->get('id');
      

      try{
        $o = ($o_id) ? $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:Options')->find($o_id) : new Options; 
        $em = $this->getDoctrine()->getManager();
        $em->remove($o);
        $em->flush();

        $msg_info = "Successfully deleted";
      } catch(\Exception $e){
        $err_msg = $e->getMessage();
      }
      $retval = array(
          'msg' => ($msg_info) ? $msg_info : $err_msg,
          'status' => ($msg_info) ? 'success' : 'failure',
          'success' => true,

        );
      $response = new Response(json_encode($retval));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
    /**
     * @Route("/save-option", name="_save_option")
     * 
     */
    public function saveOption(Request $request){
      $err_msg = "";
      $msg_info = "";

      $o_id = $request->request->get('option_id');
      $q_id = $request->request->get('question_id');
      $option = strip_tags($request->request->get('option_desc'));

      try{
        //-- validate if already existing
        $o_repo = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:Options');
        $query = $o_repo->createQueryBuilder('p')
            ->where('LOWER(p.optionDesc) = :description AND p.qId = :q_id ' )
            ->setParameter('description', strtolower($option))
            ->setParameter('q_id', $q_id)
            ->getQuery();
                
        $option_dup = count($query->getResult());
        if($option_dup > 0)
          throw new \Exception("Option already exists", 1);
        
        $o = ($o_id) ? $o_repo->find($o_id) : new Options; 
        $o->setQId($q_id);
        $o->setOptionDesc($option);

        $em = $this->getDoctrine()->getManager();
        $em->persist($o);
        $em->flush();

        $msg_info = "Successfully saved";
      } catch(\Exception $e){
        $err_msg = $e->getMessage();
      }
      $retval = array(
          'msg' => ($msg_info) ? $msg_info : $err_msg,
          'status' => ($msg_info) ? 'success' : 'failure',
          'success' => true,

        );
      $response = new Response(json_encode($retval));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }

    /**
     * @Route("/option-list", name="_option_list")
     * 
     */
    public function optionList(Request $request)
    {

      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
      $q_id = $request->query->get('q_id') ;
      if ( empty($q_id) ) {$q_id = -1; } // defaults to ..
        $retval = array();
        $data = array();
        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        
        $retval['success'] = true;
        $retval['status'] = 'failure';
        $retval['data'] = $data;
        $retval['totalCount'] = 0;
        
      try
      {
            $em = $this->getDoctrine()->getManager();
            $option_list = $em->getRepository('FeedbackSurveyFormBundle:Options')
                             ->findAllOrderByComments($start, $limit, $q_id);
            
                             
            $count = $em->getRepository('FeedbackSurveyFormBundle:Options')->getCommentCount($q_id);
    
            foreach ($option_list as $key => $category) 
            {
                $temp['id'] = $category->getId();
                $temp['order'] = $key + 1;
                $temp['description'] = $category->getOptionDesc();
                $data[] = $temp;
            }
            
            if(true)
            {
                $retval['success'] = true;
                $retval['status'] = 'success';
                $retval['data'] = $data;
                $retval['totalCount'] = $count;
            }
            
            $response = new Response(json_encode($retval));
            $response->headers->set('Content-Type', 'application/json');
           
            return $response;
      }
      catch ( Exception $e )
      {
        $response = new Response(json_encode(array('error' => json_encode($e))));
            $response->headers->set('Content-Type', 'application/json');
      }
    
    }

    /**
     * @Route("/category-list", name="_category_list")
     * 
     */     
    public function categoryList(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
      $cid = $request->query->get('c_id') ;
      if ( empty($cid) ) {$cid = -1; } // defaults to ..
        $retval = array();
        $data = array();
        $start = $request->query->get('start');
        $limit = $request->query->get('limit');
        
        $retval['success'] = true;
        $retval['status'] = 'failure';
        $retval['data'] = $data;
        $retval['totalCount'] = 0;
        
      try
      {
            $em = $this->getDoctrine()->getManager();
            $category_list = $em->getRepository('AdminMaintenanceBundle:Category')
                             ->findAllOrderByComments($start, $limit, $cid);
                             
            $count = $em->getRepository('AdminMaintenanceBundle:Category')->getCommentCount($cid);
    
            foreach ($category_list as $key => $category) 
            {
                $temp['id'] = $category->getId();
                $temp['category'] = $category->getCategoryName();
                $data[] = $temp;
            }
            
            if(true)
            {
                $retval['success'] = true;
                $retval['status'] = 'success';
                $retval['data'] = $data;
                $retval['totalCount'] = $count;
            }
            
            $response = new Response(json_encode($retval));
            $response->headers->set('Content-Type', 'application/json');
           
            return $response;
      }
      catch ( Exception $e )
      {
        $response = new Response(json_encode(array('error' => json_encode($e))));
            $response->headers->set('Content-Type', 'application/json');
      }
    }

     /**
     * @Route("/save-category", name="_save_category")
     * 
     */
    public function saveCategory(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
        $cat_id = $request->request->get('category_id');
    $idconcessionaire = $request->request->get('idconcessionaire');
        $category_name = trim(strip_tags($request->request->get('category_name')));
        $retval = array();
        $category_repo = $this->getDoctrine()->getRepository('AdminMaintenanceBundle:Category');
       
        try 
        {
            $em = $this->getDoctrine()->getManager();
            $datetime = new \Datetime();
            $category = new Category();
    
            $category->setCategoryName($category_name);
            
            $query = $category_repo->createQueryBuilder('p')
                     ->where('LOWER(p.categoryName) = :category_name')
                     ->setParameter('category_name', strtolower($category_name))
                     ->getQuery();
                     
            $category_dup_count = count($query->getResult());
     
            if($category_dup_count > 0)
            {
                $retval['success'] = true;
                $retval['status'] = 'failure';
                $retval['msg'] = 'Category already exist';
                
                $response = new Response(json_encode($retval));
                $response->headers->set('Content-Type', 'application/json');
               
                return $response;
            }
             
            if($cat_id)
            {
                $category = $category_repo->find($cat_id);
                $category->setCategoryName($category_name);
                
            }
            else
            {
                $category->setCreated($datetime);
            }
           
        if ( $idconcessionaire )
          $category->setIdconcessionaire($idconcessionaire);
            $em->persist($category);
            $em->flush();    
           
            $retval['success'] = true;
            $retval['status'] = 'success';
            $retval['msg'] = 'Category Saved';            
        }
        catch (Exception $e)
        {
            die("DB Insert ERROR (Save Category): ".$e);
            return "DB Insert ERROR (Save Category): ".$e;
        }

        $response = new Response(json_encode($retval));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

     /**
     * @Route("/delete-category", name="_delete_category")
     * 
     */
    public function deleteCategory(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
        $cat_id = $request->request->get('id');
        
        try
        {
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AdminMaintenanceBundle:Category')->findOneById($cat_id);
            
            if(!$category)
            {
                throw $this->createNotFoundException(
                    'No category found for id '.$cat_id 
                );
            }
      
      $questions_cat = $em->getRepository('AdminMaintenanceBundle:Questions')->findBy(array('c' => $cat_id));
      if ( empty($questions_cat) )
      {
        $em->remove($category);
              $em->flush(); 
      }
      else
        throw $this->createNotFoundException('Fatal: cannot delete Category with id: '.$cat_id);
        }
        catch(Exception $e)
        {
            return new JsonResponse(array('data' => "DB Deletion ERROR (Delete Category)$e"));
        }
        
        return new JsonResponse(array('data' => 'Category Succesfully Deleted'));
        
    }    
    
      /**
     * @Route("/save-question", name="_save_question")
     * 
     */
    public function saveQuestion(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
        $description = trim(strip_tags($request->request->get('question_name')));
        $q_id = $request->request->get('q_id');
        $q_id = ($q_id == 'false') ? "0" : $q_id;
        $cat_id = $request->request->get('cat_id');
        $rating = $request->request->get('type');
        $optional = $request->request->get('optional', 0);
        $grouping = $request->request->get('grouping', "");
        try
        {
            $em = $this->getDoctrine()->getManager();
            $datetime = new \Datetime();
            $question = new Questions();
            
            $question_repo = $this->getDoctrine()->getRepository('AdminMaintenanceBundle:Questions');
            
            $cat_repo = $this->getDoctrine()->getRepository('AdminMaintenanceBundle:Category');
            $cat_obj = $cat_repo->findOneById($cat_id);
            
            $query = $question_repo->createQueryBuilder('p')
            ->where('LOWER(p.description) = :description AND p.c = :cat_id AND p.type = :type AND p.id <> :q_id' )
            ->setParameter('description', strtolower($description))
            ->setParameter('cat_id', $cat_obj)
            ->setParameter('type', $rating)
            ->setParameter('q_id', $q_id)
            ->getQuery();
                
            $question_dup_count = count($query->getResult());
            
            if($question_dup_count > 0)
            {
                $retval['success'] = true;
                $retval['status'] = 'failure';
                $retval['msg'] = 'Question already exist';
                
                $response = new Response(json_encode($retval));
                $response->headers->set('Content-Type', 'application/json');
               
                return $response;
            } 
            //-- Check if the category already have grouped/separated question
            if($grouping && ($grouping != 'none'))
            {
              $query = $question_repo->createQueryBuilder('p')
                ->where('p.grouping = :grouping AND p.c = :cat_id AND p.id <> :q_id' )
                ->setParameter('grouping', $grouping)
                ->setParameter('cat_id', $cat_obj)
                ->setParameter('q_id', $q_id)
                ->getQuery();
              $checker = count($query->getResult());
              if($checker > 0)
              {
                $retval['success'] = true;
                $retval['status'] = 'failure';
                $retval['msg'] = "Category already have ".ucfirst($grouping)." question.";
                
                $response = new Response(json_encode($retval));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
              }
            }
            
            $question->setC($cat_obj);
            $question->setDescription($description);
            $question->setType($rating);
            $question->setOptional($optional);
            $question->setGrouping($grouping);
            $user_logs = new SysUserActionLogs();
      
            if(($q_id !== 'false') AND ($q_id != 0))
            {
                $question_repo = $this->getDoctrine()->getRepository('AdminMaintenanceBundle:Questions');
                $question = $question_repo->find($q_id);
                $question->setDescription($description);
                $question->setType($rating);
                $question->setOptional($optional);
                $question->setGrouping($grouping);
                $user_logs->setAffectedId($q_id);
                $user_logs->setMethod(__FUNCTION__.' - edit');
            }
            else
            {
                $user_logs->setMethod(__FUNCTION__);
                $question->setCreated($datetime);
            }
      
      $requestSerialized = $this->requestParamsSerializer($request);
      $session = $this->get('session');
      $user_logs->setUsername($session->get('username'));
      $user_logs->setModule(__METHOD__);
      $user_logs->setAffectedData(json_encode($requestSerialized));
      $user_logs->setActionstamp($datetime);
      
            $em->persist($user_logs);
            $em->persist($question);
            $em->flush();
    
            $retval = array();
            $retval['success'] = true;
            $retval['status'] = 'success';
            $retval['msg'] = 'Question Saved';
        }
        catch(Exception $e)
        {
            die("DB Insert ERROR (Save Question): ".$e);
            return "DB Insert ERROR (Save Question): ".$e;
        }
        
        $response = new Response(json_encode($retval));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    /**
     * @Route("/edit-question", name="_edit_question")
     * 
     */
     public function editQuestionAction(Request $request)
     {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
         $question_id = $request->request->get('q_id');
         $retval = array();
         
         $em = $this->getDoctrine()->getManager();
         $query = $em->getRepository('AdminMaintenanceBundle:Questions');
         $question = $query->findOneById($question_id);
         
         $retval[] = $question->getId();
         $retval[] = $question->getDescription();
         $retval[] = $question->getType();
         $retval[] = $question->getOptional();
         $retval[] = $question->getGrouping();
         
         return new JsonResponse(array('success' => true, 'status' => 'success', 'data' => $retval));  
         
     }
    
     /**
     * @Route("/load-question", name="_load_question")
     * 
     */
    public function loadQuestions(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
        $category_id = $request->query->get('c_id');
        $start = $request->query->get('start');
        $limit = $request->query->get('limit');

        $retval = array();
        $data = array();
         
         /*$em->getRepository('AdminMaintenanceBundle:Questions')->findOneById($qid)
         $question_count = $em->getRepository('AdminMaintenanceBundle:Questions')
                         ->getQuestionCount($category_id);*/
                         
        $em = $this->getDoctrine()->getManager();
        $question_list = $em->getRepository('AdminMaintenanceBundle:Questions')
                         ->findAllQuestionsWithLimit($category_id, $start,  $limit);
         
         $question_count = $em->getRepository('AdminMaintenanceBundle:Questions')
                         ->getQuestionCount($category_id);
                         
         foreach ($question_list as $key => $question) 
        {
            // $temp['id'] = $question->getId();
            // $temp['description'] = $question->getDescription();
            // $data[] = $temp;
          $data[] = array(
            'id' => $question->getId(),
            'description' => $question->getDescription(),
            'type' => $question->getType()
          );
        }
         
        if(true)
        {
            $retval['success'] = true;
            $retval['status'] = 'success';
            $retval['data'] = $data;
            $retval['totalCount'] = $question_count;
        }
        
        return new JsonResponse($retval);
    }
     /**
     * @Route("/delete-question", name="_delete_question")
     * 
     */
    public function deleteQuestion(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
      
        $qid = $request->request->get('id'); 
        
        try
        {
            $em = $this->getDoctrine()->getManager();
            $question_repo = $em->getRepository('AdminMaintenanceBundle:Questions')->findOneById($qid);
            
            if(!$question_repo)
            {
                throw $this->createNotFoundException(
                    'No question found for id '.$qid
                );
            }
      $requestSerialized = $this->requestParamsSerializer($request);
      $session = $this->get('session');
      $user_logs = new SysUserActionLogs();
      $user_logs->setUsername($session->get('username'));
      $user_logs->setModule(__METHOD__);
      $user_logs->setMethod(__FUNCTION__);
      $user_logs->setAffectedData(json_encode($requestSerialized));
      $user_logs->setActionstamp(new \DateTime());
      $user_logs->setAffectedId($qid);
            
            $em->persist($user_logs);
            $em->remove($question_repo);
            $em->flush();
        }
        catch(Exception $e)
        {
            return new JsonResponse(array('data' => "DB Deletion Error (Delete Question) $e"));  
        }
        
        
        return new JsonResponse(array('data' => 'Question Successfully Deleted')) ; 
    }

   /**
   * @Route("/generate-survey-url", name="_generate_survey_url")
   * 
   */
    public function generateSurveyUrl(Request $request)
    {
      $id = $request->request->get('id');
      $crypt_key = $this->container->getParameter('crypt_key');;
      $encryption = $this->get('encryption');

      $encrypt_id = $encryption->encrypt($id, $crypt_key);
      $url = $this->generateUrl(
            "_specific_survey_type", 
            array('survey_id' => $encrypt_id),
            true
          );
      $retval = array(
        'data' => array('conc_url' => $url),
        'success' => true
      );
      $response = new Response(json_encode($retval));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }


   /**
   * @Route("/load_conc", name="_load_conc")
   * 
   */
  public function loadConc(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
    $rquery = $request->query;
    
    $limit = $rquery->get('limit');
    $page = $rquery->get('page');
    $offset = $page * $limit - 15;
      
    $description = $this->getDoctrine()
        ->getRepository('AdminMaintenanceBundle:Concessionaire')
        //->findAll();
        ->findBy(array(),array(), $limit, $offset);
      
    $concessionaires = $this->getDoctrine()
        ->getRepository('AdminMaintenanceBundle:Concessionaire')
        ->findAll();
    $totalCount = count($concessionaires);

      if (!$description) {
          throw $this->createNotFoundException(
              'No Category found for Concessionaire'
          );
      }
    //print_r($response);

    $item = new ConcessionaireSerializer($description);

    //echo json_encode(array('data'=>$item));
    $retval = array('data'=>$item->jsonSerialize(), 'totalCount' => $totalCount);
    
    $response = new Response(json_encode($retval));
        $response->headers->set('Content-Type', 'application/json');

    //die("died ".$description[0]->getDescription());
      
      
      /*$data = array(array("concessionaire" => "test Name") );
    $retval['success'] = true;
    $retval['status'] = 'success';
    $retval['data'] = $data;
    $retval['totalCount'] = 1;
        
        $response = new Response(json_encode($retval));
        $response->headers->set('Content-Type', 'application/json');*/
    return $response;
    }
  
   /**
     * @Route("/save-concessionaire", name="_save_concessionaire")
     * 
     */
    public function saveConcessionaire(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
      
        $concessionaire_name = trim(strip_tags($request->request->get('conc_add_name')));
    $conc_id = $request->request->get('conc_add_conc_id-inputEl');
    // no session security?
        try
        {
          // regex = only allow alphanumeric underscore hyphen space
          if ( preg_match("/[^,;a-zA-Z0-9_-\s]|[,;]$/s", $concessionaire_name) ) {
          $retval['success'] = true;
                  $retval['status'] = 'failure';
                  $retval['msg'] = 'Please provide a valid Concessionaire name; underscore alphanumeric word only';
                  $response = new Response(json_encode($retval));
          return $response;
          }
            $em = $this->getDoctrine()->getManager();
            $datetime = new \Datetime();
            $conc = new Concessionaire();
            
            $conc_repo = $this->getDoctrine()->getRepository('AdminMaintenanceBundle:Concessionaire');
            $query = $conc_repo->createQueryBuilder('p')
            ->where('LOWER(p.description) = :description')
            ->setParameter('description', strtolower($concessionaire_name))
            ->getQuery();
                
            $conc_dup_count = count($query->getResult());
            
            if($conc_dup_count > 0)
            {
                $retval['success'] = true;
                $retval['status'] = 'failure';
                $retval['msg'] = 'Concessionaire already exist';
                
                $response = new Response(json_encode($retval));
                $response->headers->set('Content-Type', 'application/json');
               
                return $response;
            }

      if ( $conc_id ) 
      {
        $conc = $conc_repo->find($conc_id);
        if (!$conc)
        {
          $retval['success'] = true;
                  $retval['status'] = 'failure';
                  $retval['msg'] = 'No concessionaire ID ';
                  $response = new Response(json_encode($retval));
          return $response;
        }
      }
            $conc->setDescription($concessionaire_name);
            $conc->setCreatedAt($datetime);

            $em->persist($conc);
            $em->flush();
    
            $retval = array();
            $retval['success'] = true;
            $retval['status'] = 'success';
            $retval['msg'] = 'Concessionaire Saved';
        }
        catch(Exception $e)
        {
            die("DB Insert ERROR (Save Concessionaire): ".$e);
            return "DB Insert ERROR (Save Concessionaire): ".$e;
        }
        
        $response = new Response(json_encode($retval));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

  /**
   * @Route("/delete-concessionaire", name="_delete_concessionaire")
   */
  public function deleteConcessionaire(Request $request)
    {
      if ( $this->isMaintenanceAuthorized()['not'] )
      return $this->redirect($this->generateUrl('_login'));
    
        $conc_id = $request->request->get('id');
        try
        {
            $cm = $this->getDoctrine()->getManager();
            $conc = $cm->getRepository('AdminMaintenanceBundle:Concessionaire')->findOneByIdconcessionaire($conc_id);
            
            if( empty($conc) || !is_integer(+$conc_id) )
            {
                throw $this->createNotFoundException(
                    'No concessionaire found for id '.$conc_id 
                );
            }
            
            //ensure the to be deleted concessionaire does not have child Category
            $cat_conc = $cm->getRepository('AdminMaintenanceBundle:Category')->findOneByIdconcessionaire($conc_id);
      if ( empty($cat_conc )) {
        $cm->remove($conc);
              $cm->flush();
              return new JsonResponse(array('data' => 'Concessionaire Succesfully Deleted'));
      }
      throw $this->createNotFoundException(
                'Fatal: Cannot delete concessionaire '.$conc_id 
            );
        }
        catch(Exception $e)
        {
            return new JsonResponse(array('data' => "DB Deletion ERROR (Delete Concessionaire)$e"));
        }
        
        return new JsonResponse(array('data' => 'Concessionaire Succesfully Deleted'));
    }

  function isMaintenanceAuthorized()
  {
    $session = $this->get("session");
    if ( $session->get("role") == "admin" )
      return array('not' => false);
    
    //403 unauthorized access
    return array('not' => true,
           'response' => new JsonResponse(array('success' => false, 'status' => 403))
          );
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
  
  /**
   *@Route("/regex-test", name="_regex_test")
   */
   public function regexTest() {
    
    // only alphanumeric dash dot underscore and space..
    // refer to sample data for test cases
    $regex = "/[^\\.a-zA-Z0-9_-\s]|[\\.]$/s";
    $regex = "/[^\\.a-zA-Z0-9_-\s]|[\\.]$/s";
    $good_arr_string = array(
      '1' => "1",
      '1' => "TheConcessioniare",
      '2' => "The Concessioniare ",
      '3' => " The Concess ioniare",
      '4' => "The_Concessioniare",
      '5' => "The_ Concessioniare",
      '6' => "The-",
      '7' => "The.Concessioniare",
      '8' => "The. Concessioniare",
      '9' => "The._Concessioniare",
      '10' => "The._-Concessioniare",
      '11' => "The..Concessioniare"
    );
    
    $index_err = 1;
    
    foreach($good_arr_string as $gas) {
      if ( preg_match($regex, $gas) )
      {
        die("error on good strings at err $index_err\n");
      }
      $index_err++;
    }
    
    $index_err = 1;
    $bad_arr_string = array(
      '1' => "",
      '1' => "  ",
      '1' => "The_@Concessioniare",
      '2' => "The)Concessioniare",
      '3' => "The~Concessioniare",
      '4' => "The-once&ssioniare",
      '5' => "The,Concessioniare",
      
      '6' => "The;Concessioniare",
      '7' => " TheConcessioniare/",
      '8' => "'The,Concessioniare",
      '9' => "The<html Concessioniare",
    );

    foreach($bad_arr_string as $bas) {
      if ( !preg_match($regex, $bas) )
        die("error on bad strings at err $index_err\n");
      $index_err++;
    }
    
    die("ok\n");
   }
  
}
