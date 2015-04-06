<?php

namespace Admin\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPExcel;
use PHPExcel_IOFactory;

use Admin\MaintenanceBundle\DependencyInjection\ConcessionaireSerializer;

class DefaultController extends Controller
{
    
    private $report = array();
    
    //private $value = array();
    
    /**
     * @Route("/", name="_report")
     * @Template()
     */
    public function indexAction()
    {   
        if ( $this->is403() )
            return $this->redirect($this->generateUrl('_login'));
    
    $session = $this->get('session');
    $base_url = $this->generateUrl("_report", array(), true);
    $base_url = str_replace('/report', '', $base_url);
    $base_url = str_replace('/app_dev.php', '', $base_url);
    $base_url = str_replace('/app.php', '', $base_url);
    
    return $this->render('AdminReportBundle:Default:index.html.twig', 
      array('modules' => $this->getSideBarDataPerRole(),
            'report_url' => $base_url.'/reports/',
            'username' => $session->get('username')
      )
    );
    }
    
    
    /**
     * @Route("/generate", name="_generate_report")
     * 
     */
    public function generateExcelReportAction(Request $request)
    {
      if ( $this->is403() )
            return $this->redirect($this->generateUrl('_login'));
    
    $selects = pg_escape_string($request->request->get("selects"));
    $selectValidation = $this->isValidSelects($selects);
    if ( !$selectValidation['isValid'] ) {
      $retval['success'] = true;
      $retval['status'] = 'failure';
            $retval['data'] = $selectValidation['msg'];
    
            $response = new Response(json_encode($retval));
            $response->headers->set('Content-Type', 'application/json');
      
      return $response;
    }
    
    // $selects is assumed as valid
    $selects_id = $selectValidation['data'];
    $conc_name = $selects;
            
    try
    {
          $date_from = date('Y-m-d',strtotime(pg_escape_string($request->request->get('report_article_datefrom'))));
          $date_to = date('Y-m-d',strtotime(pg_escape_string($request->request->get('report_article_dateto'))));
          if ( !$this->validateDashedDate($date_from) || !$this->validateDashedDate($date_to) )
        throw $this->createAccessDeniedException(
                "Please check input date."
                );
      }
    catch ( \Exception $e )
    {
      $data = array("success"=>false, "data"=>"Please check input date.", 'exception' => json_encode($e));
            die(json_encode($data));
    }
        
        if($date_from == '1970-01-01' || $date_to == '1970-01-01')
            return new JsonResponse(array('success'=>true, 'status'=>'failure', 'data'=>'Please select a date to generate a report'));
        
        if($date_from > $date_to)
            return new JsonResponse(array('success'=>true, 'status'=>'failure', 'data'=>'(Date From should not be greater than Date To)'));
        
        
        $filename = $date_from.'-'.$date_to;
       
        $em = $this->getDoctrine()->getManager()->getConnection();
       
        if(empty($date_from) || empty($date_to))
        {
            $data = array("success"=>false, "data"=>"Please check input date.");
            die(json_encode($data));
        }
        //$sql = 'SELECT category_name, id FROM category ORDER BY id';
        $sql = 'SELECT c.category_name, c.id, concessionaire.description FROM category c
        LEFT JOIN concessionaire
        ON c.idconcessionaire = concessionaire.idconcessionaire
        WHERE concessionaire.idconcessionaire = \''.$selects_id.'\' ORDER BY c.id';
                        
        $stmt = $em->prepare($sql);
        
        $stmt->execute();
        
        $data = $stmt->fetchAll();
        
        $count = count($data);
        
        $alpha = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U',
            'V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM'
            ,'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD'
            ,'BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU'
            ,'BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL',
            'CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ'
            );
        $alphaIndex = 2; # C
        
        foreach ($data as $key => $category)
        {      
            $sql = 'SELECT description, id FROM questions WHERE c_id ='.$category['id'];
            $stmt = $em->prepare($sql);
            $stmt->execute();
            $questions = $stmt->fetchAll();
            $questions_count = count($questions);
            
            if($questions_count==0)
                continue;
  
            $this->report['headers'][$category['category_name']] = array();   
            
            foreach ($questions as $key => $question)
            {
                $this->report['headers'][$category['category_name']]['datasub'][$alpha[$alphaIndex]] = $question['description'];
                $alphaIndex++; 
            }  
           
           $this->report['headers'][$category['category_name']]['count'] = count($this->report['headers'][$category['category_name']]['datasub']);
        }
        
    
        unset($sql);
        
        /*$sql = "SELECT username, actionstamp FROM employee_answers 
                WHERE actionstamp::DATE >='".$date_from."'AND actionstamp::DATE <= '".$date_to."'
                AND q_id IN (SELECT id FROM questions WHERE c_id IN 
                (SELECT id FROM category) ORDER BY id ASC)
                GROUP BY username, actionstamp";*/
        $sql = "SELECT surveyor_by_category_of_questions.username, surveyor_by_category_of_questions.ea_stamp as actionstamp
        FROM (
          SELECT employee_answers_questions.ea_id, employee_answers_questions.q_description, employee_answers_questions.value,
            employee_answers_questions.username, employee_answers_questions.ea_stamp, employee_answers_questions.q_c_id
          FROM (SELECT ea.id as ea_id, ea.q_id, ea.username, ea.value, ea.actionstamp as ea_stamp, q.id as ques_id, q.description as q_description, q.type, q.c_id as q_c_id
            FROM employee_answers as ea
            LEFT JOIN questions as q
            ON q.id = ea.q_id) as employee_answers_questions
          RIGHT JOIN (
            SELECT c.idconcessionaire,
              q.c_id
            FROM category as c 
            LEFT JOIN questions as q
            ON q.c_id = c.id
            WHERE c.idconcessionaire = $selects_id) as category_of_questions
          ON category_of_questions.c_id = employee_answers_questions.q_c_id
          GROUP BY employee_answers_questions.ea_id, employee_answers_questions.q_description, employee_answers_questions.value,
            employee_answers_questions.username, employee_answers_questions.ea_stamp, employee_answers_questions.q_c_id
      
        ) as surveyor_by_category_of_questions
        WHERE surveyor_by_category_of_questions.ea_stamp::DATE  >= '$date_from' AND surveyor_by_category_of_questions.ea_stamp::DATE <= '$date_to'
        GROUP BY surveyor_by_category_of_questions.username, surveyor_by_category_of_questions.ea_stamp
        ORDER BY surveyor_by_category_of_questions.ea_stamp ASC";
        
        $stmt = $em->prepare($sql);
        $stmt->execute();
        $answers = $stmt->fetchAll();
        
        /*(print_r(array('this->report' => $this->report, 'answers' => $answers));
    die();*/
        //-- Get the question with grouped type
        $sql = "
          SELECT DISTINCT id FROM questions WHERE c_id IN (
          SELECT distinct b.id FROM concessionaire a
          LEFT JOIN category b ON b.idconcessionaire=a.idconcessionaire
          WHERE a.description='$conc_name'
              )AND grouping='grouped'
        ";
        $stmt = $em->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $grouped_id = ($result) ? ($result['id']) : "";

        //-- Get the question with separated type
        $sql = "
          SELECT DISTINCT id FROM questions WHERE c_id IN (
          SELECT distinct b.id FROM concessionaire a
          LEFT JOIN category b ON b.idconcessionaire=a.idconcessionaire
          WHERE a.description='$conc_name'
              )AND grouping='separated'
        ";
        $stmt = $em->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $separated_id = ($result) ? ($result['id']) : "";

        if(count($answers) === 0)
            return new JsonResponse(array('success'=>true, 'status'=>'failure', 'data'=>"No Data to Generate (".$date_from." TO " .$date_to.")"));
          
        foreach ($answers as $ka => $answer) 
        {
           $sql = "SELECT q_id, value FROM employee_answers 
                   WHERE actionstamp ='".$answer['actionstamp']."'
                   AND username = '".$answer['username']."' ORDER BY id";
           
           $stmt = $em->prepare($sql);
           $stmt->execute();
           $values = $stmt->fetchAll();
            
          $g_index = "";
          $s_index = "";
          $g_arr = array();
          $s_arr = array();
          foreach ($values as $value_holder)
          {
            $current_q_id = $value_holder['q_id'];
            if($current_q_id == $grouped_id){
              $g_index = $value_holder['value'];
            }
            if($current_q_id == $separated_id){
              $s_index = $value_holder['value'];
            }
            $values_holder[] = $value_holder['value'];
          }
          
          $answer[] = $values_holder;

          unset($values_holder);

          if($g_index){
            $g_arr[$g_index] = $answer;
            $this->report['grouped'][$g_index][] = $answer;
          }
          if($s_index){
            $g_arr[$g_index] = $answer;
            $this->report['separated'][$s_index][] = $answer;
          }
          $this->report['data'][] = $answer;
        }
    /*print_r($this->report);
    die();*/
       // echo '<pre>';
       // print_r($this->report['grouped']);
       // die();
        if(!empty($this->report))
        {
            $summary_report_data = $this->getSummaryReport($date_from, $date_to, $selects_id, $conc_name);
            $file = $this->getReportData($filename, $summary_report_data, $conc_name);
            return new JsonResponse(array('success'=>true, 'status'=>'success', 'data'=>$file));
        }
       else
       {
           return new JsonResponse(array('success'=>true, 'status'=>'failure', 'data'=>'Error Encountered Generating File'));
       }        
             
    }
    
    public function getSummaryReport($date_from, $date_to, $selects_id, $conc_name)
    {
      if ( $this->is403() )
            return $this->redirect($this->generateUrl('_login'));
    
        $report_summary = array();
   
        $em = $this->getDoctrine()->getManager()->getConnection();
    /*this is where we want to change the query considereing relatinship to the selected concessionaire* /     
        $sql = "SELECT count(*) as total_recepients FROM
                (SELECT username, actionstamp FROM employee_answers WHERE actionstamp::DATE  >='".$date_from
               ."'AND actionstamp::DATE <='".$date_to."' GROUP BY username, actionstamp ORDER BY actionstamp ASC) count";*/
        $sql = "SELECT COUNT(*) as total_surveyor_by_category_of_questions
        FROM
          (SELECT surveyor_by_category_of_questions.username, surveyor_by_category_of_questions.ea_stamp
          FROM (
            SELECT employee_answers_questions.ea_id, employee_answers_questions.q_description, employee_answers_questions.value,
              employee_answers_questions.username, employee_answers_questions.ea_stamp, employee_answers_questions.q_c_id
            FROM (SELECT ea.id as ea_id, ea.q_id, ea.username, ea.value, ea.actionstamp as ea_stamp, q.id as ques_id, q.description as q_description, q.type, q.c_id as q_c_id
              FROM employee_answers as ea
              LEFT JOIN questions as q
              ON q.id = ea.q_id) as employee_answers_questions
            RIGHT JOIN (
              SELECT c.idconcessionaire,
                q.c_id
              FROM category as c 
              LEFT JOIN questions as q
              ON q.c_id = c.id
              WHERE c.idconcessionaire = ".$selects_id.") as category_of_questions
            ON category_of_questions.c_id = employee_answers_questions.q_c_id
            GROUP BY employee_answers_questions.ea_id, employee_answers_questions.q_description, employee_answers_questions.value,
              employee_answers_questions.username, employee_answers_questions.ea_stamp, employee_answers_questions.q_c_id
        
          ) as surveyor_by_category_of_questions
          WHERE surveyor_by_category_of_questions.ea_stamp::DATE  >= '".$date_from."' AND surveyor_by_category_of_questions.ea_stamp::DATE <= '".$date_to."'
          GROUP BY surveyor_by_category_of_questions.username, surveyor_by_category_of_questions.ea_stamp
          ORDER BY surveyor_by_category_of_questions.ea_stamp ASC
        ) as count";
        
        
        $stmt = $em->prepare($sql);
        $stmt->execute();
        $surveyers = $stmt->fetch();
        
        $report_summary['header_data'] = array('Date From:'=>$date_from, 'Date To:'=>$date_to, 'Total Recepients:'=>$surveyers['total_surveyor_by_category_of_questions']);
    
        //$sql = 'SELECT category_name, id FROM category ORDER BY id';
        $sql = 'SELECT c.category_name, c.id, concessionaire.description FROM category c
        LEFT JOIN concessionaire
        ON c.idconcessionaire = concessionaire.idconcessionaire
        WHERE concessionaire.idconcessionaire = \''.$selects_id.'\' ORDER BY c.id';
                        
        $stmt = $em->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll();
        
        foreach ($categories as $key => $category)
        {
            $sql = 'SELECT description, id, type FROM questions WHERE c_id ='.$category['id'];
            $stmt = $em->prepare($sql);
            $stmt->execute();
            $questions = $stmt->fetchAll();
            
            //-- Get the question with grouped type
            $sql = "
              SELECT DISTINCT id FROM questions WHERE c_id IN ('{$category['id']}') AND grouping='grouped'
            ";
            $stmt = $em->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            $grouped_id = ($result) ? ($result['id']) : "";

            //-- Get the question with separated type
            $sql = "
              SELECT DISTINCT id FROM questions WHERE c_id IN (SELECT id FROM category WHERE idconcessionaire IN('$selects_id')) AND grouping='separated'
            ";
            
            $stmt = $em->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            $separated_id = ($result) ? ($result['id']) : "";

            
            if(count($questions) == 0)
                continue;


            $category['category_name'] = $category['category_name']; 
            $report_summary['sub_headers'][$category['category_name']] = array();
            
            //-- Prepares additional tabs with separated questions
            if($separated_id){
                
                $separated_arr = array();
                $o_repo = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:Options');
                $o = $o_repo->findBy(array('qId' => $separated_id));
                $report_summary['extra_header'] = array();

                foreach ($o as $o_key => $o_value){
                    // $report_summary['extra_header'][$o_value->getOptionDesc()][] = array();
                    $separated_arr[] = $o_value->getOptionDesc();
                }

                //-- loads the categories
                foreach ($categories as $ck => $cv){
                    foreach($separated_arr as $sk => $sv){
                        $report_summary['extra_header'][$sv][$cv['category_name']] = array();
                    }
                }

                
                // $report_summary['extra_header'][$o_value->getOptionDesc()][$category['category_name']] = array();
                $sql = "SELECT distinct actionstamp FROM employee_answers WHERE q_id=$separated_id AND actionstamp::DATE between '$date_from' AND '$date_to'";
                $stmt = $em->prepare($sql);
                $stmt->execute();
                $actionstamps = $stmt->fetchAll();

                $a_repo = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:EmployeeAnswers');
                foreach ($actionstamps as $a_key => $a_value) {
                    $stamp = $a_value['actionstamp'];
                    $sql = "SELECT * FROM employee_answers WHERE actionstamp='$stamp'";
                    $stmt = $em->prepare($sql);
                    $stmt->execute();
                    $a = $stmt->fetchAll();
                    $temp = array();

                    //-- Check all answers per entry
                    $s_index = "";
                    foreach ($a as $k => $v) {
                      if($v['q_id'] == $separated_id){
                        $s_index = $v['value'];
                      }
                      $temp[] = $v;
                    }

                }

                //-- loads the questions per category
                foreach($questions as $qk => $qv){
                    foreach($separated_arr as $sk => $sv){
                        $report_summary['extra_header'][$sv][$cv['category_name']][$qv['description']] = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
                    }
                }


                
                //-- Rating identifier and counter
                foreach($questions as $qq => $qs){
                    if($qs['type'] != 'rating')
                        continue;
                    $q_idc = $qs['id'];
                    // foreach ($temp as $tk => $tv) {
                    //     if($q_idc == $tv['q_id']){
                    //         switch ($tv['value']) {
                    //             case 1:
                    //                 $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][$qq + 1] += 1;
                    //                 break;
                    //             case 2:
                    //                 $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][$qq + 1] += 1;
                    //                 break;
                    //             case 3:
                                    
                    //                 $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][3] += 1;
                    //                 break;
                    //             case 4:
                    //                 $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][4] += 1;
                    //                 break;
                    //             default:
                    //                 # code...
                    //                 break;
                    //         }
                    //         break;
                    //     }
                    // }
                }

                // //-- Uses the original logic with no 
                // foreach($questions as $qk=>$question)
                // {
                  
                //    for($j=1;$j<=4;$j++)
                //    {
                //        //this old line below doesnt adhere to the input date filter so it re turns everything since the beginning of time
                //        //$sql = "SELECT count(value) FROM employee_answers WHERE q_id IN (".$question['id'].") AND value IN(".$j.")";
                //        $sql = "SELECT count(value) FROM employee_answers WHERE q_id IN (".$question['id'].")
                //              AND value IN('".$j."')
                //              AND actionstamp::DATE  >= '$date_from'
                //              AND actionstamp::DATE  <= '$date_to'
                //             ";
                        
                //        $stmt = $em->prepare($sql);
                //        $stmt->execute();
                //        $answer_count = $stmt->fetch();
                       
                //        $report_summary['sub_headers'][$category['category_name']][$question['description']][] = $answer_count['count'];            
                //    }
                // }

            }
            //-- On Basic Summary Report, manipulates data with group questions
            if($grouped_id){
              $group_arr = "";
              $o_repo = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:Options');
              $o = $o_repo->findBy(array('qId' => $grouped_id));
              foreach ($o as $o_key => $o_value){
              	$group_arr[$o_value->getOptionDesc()] = array();
                $report_summary['sub_headers'][$category['category_name']][0][$o_value->getOptionDesc()] = array();
                foreach ($questions as $qk=>$question) {
                    $report_summary['sub_headers'][$category['category_name']][0][$o_value->getOptionDesc()][$question['description']] = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
                }
              }
             
              
              
              $sql = "SELECT distinct actionstamp FROM employee_answers WHERE q_id=$grouped_id AND actionstamp::DATE between '$date_from' AND '$date_to'";
              $stmt = $em->prepare($sql);
              $stmt->execute();
              $actionstamps = $stmt->fetchAll();

              $a_repo = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:EmployeeAnswers');
              
              foreach ($actionstamps as $a_key => $a_value) {
                $stamp = $a_value['actionstamp'];
                $sql = "SELECT * FROM employee_answers WHERE actionstamp='$stamp'";
                $stmt = $em->prepare($sql);
                $stmt->execute();
                $a = $stmt->fetchAll();
                $temp = array();

                //-- Check all answers per entry
                $g_index = "";
                foreach ($a as $k => $v) {
                  if($v['q_id'] == $grouped_id){
                    $g_index = $v['value'];
                  }
                  $temp[] = $v;
                 
                }

                //-- Rating identifier and counter
                foreach($questions as $qq => $qs){
                    if($qs['type'] != 'rating')
                        continue;
                    $q_idc = $qs['id'];
                    foreach ($temp as $tk => $tv) {
                        if($q_idc == $tv['q_id']){
                            switch ($tv['value']) {
                                case 1:
                                    $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][$qq + 1] += 1;
                                    break;
                                case 2:
                                    $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][$qq + 1] += 1;
                                    break;
                                case 3:
                                    
                                    $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][3] += 1;
                                    break;
                                case 4:
                                    $report_summary['sub_headers'][$category['category_name']][0][$g_index][$qs['description']][4] += 1;
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                            break;
                        }
                    }
                }
              }
              continue;
            }
            //-- Uses the original logic with no 
            foreach($questions as $qk=>$question)
            {
              
               for($j=1;$j<=4;$j++)
               {
                   //this old line below doesnt adhere to the input date filter so it re turns everything since the beginning of time
                   //$sql = "SELECT count(value) FROM employee_answers WHERE q_id IN (".$question['id'].") AND value IN(".$j.")";
                   $sql = "SELECT count(value) FROM employee_answers WHERE q_id IN (".$question['id'].")
                         AND value IN('".$j."')
                         AND actionstamp::DATE  >= '$date_from'
                         AND actionstamp::DATE  <= '$date_to'
                        ";
                    
                   $stmt = $em->prepare($sql);
                   $stmt->execute();
                   $answer_count = $stmt->fetch();
                   
                   $report_summary['sub_headers'][$category['category_name']][$question['description']][] = $answer_count['count'];            
               }
            }


        }
       
        return $report_summary;   
    }
    
    //-- Warning! This functions is so dirty!
    public function getReportData($filename, $summary_report_data, $conc_name)
    {
        // print '<pre>';
        // print_r($summary_report_data);
        // die();
    if ( $this->is403() )
            return $this->redirect($this->generateUrl('_login'));
    
       $file = "online_feedback_report";
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
       
       $phpExcelObject->getProperties()->setCreator("liuggio")
           ->setLastModifiedBy("Giulio De Donato")
           ->setTitle("Office 2005 XLSX Test Document")
           ->setSubject("Office 2005 XLSX Test Document")
           ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
           ->setKeywords("office 2005 openxml php")
           ->setCategory("Test result file");
           
       $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);              
       $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('X')->setAutoSize(true); 
       $phpExcelObject->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
       $phpExcelObject->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
                     
        /**
         * Extract subheaders to excel
         */             
                     
        $colStartHeader = 2;
        $rowStartHeader = 1;
        $rowStartSubHeader = 2;
        $alpha = array(
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U',
            'V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM'
            ,'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD'
            ,'BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU'
            ,'BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL',
            'CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ'
            );
       
       $phpExcelObject->getActiveSheet()
                    ->getStyle('A2:B2')
                    ->getFont()
                    ->getColor()
                    ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
       $phpExcelObject->getActiveSheet()
                    ->getStyle('A2:B2')
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 
      $phpExcelObject->getActiveSheet()
                    ->getStyle('A2:B2')
                    ->getFill()
                    ->applyFromArray(array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '333333')
                      )
                    );
            
       $phpExcelObject->getActiveSheet()->setCellValue('A2', 'Name');
       $phpExcelObject->getActiveSheet()->setCellValue('B2', 'Date');     
            
        foreach($this->report['headers'] as $h => $data)
        {  
            foreach ($data['datasub'] as $ds => $subh)
            {
                 $phpExcelObject->getActiveSheet()
                    ->getStyleByColumnAndRow($colStartHeader, $rowStartSubHeader)
                    ->getFont()
                    ->getColor()
                    ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
                 
                 $phpExcelObject->getActiveSheet()
                    ->getStyle($alpha[$colStartHeader].'2')
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                 
                 $phpExcelObject->getActiveSheet()
                    ->getStyleByColumnAndRow($colStartHeader, $rowStartSubHeader)
                    ->getFill()
                    ->applyFromArray(array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '333333')
                        )
                    );
                
                $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($colStartHeader, $rowStartSubHeader, $subh);
                $colStartHeader++;
         }
      
      }

        /**
         * Extract data to excel
         */
         
        $rowStartData = 3;
        $colStartData = 0;
      
        foreach ($this->report['data'] as $e => $emp_info)
        {
            
             $phpExcelObject->getActiveSheet()
                    ->getStyle($alpha[$colStartData].$rowStartData)
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                     
             $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($colStartData, $rowStartData, strtolower($emp_info['username']));
             $colStartData++;
             $phpExcelObject->getActiveSheet()
                    ->getStyle($alpha[$colStartData].$rowStartData)
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
             $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($colStartData, $rowStartData, $emp_info['actionstamp']);
             $colStartData++;
             
             foreach ($emp_info[0] as $emp_value)
             {
                 $phpExcelObject->getActiveSheet()
                    ->getStyle($alpha[$colStartData].$rowStartData)
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($colStartData, $rowStartData, $emp_value);
                $colStartData++;      
             }
             
            $rowStartData++;
            $colStartData = 0;   
        }
        
         /**
         * Extract headers to excel
         */   
        
        $phpExcelObject->getActiveSheet()
                       ->getStyle('A1:B1')
                       ->getFont()
                       ->getColor()
                       ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
        
        $phpExcelObject->getActiveSheet()
                       ->mergeCells('A1:B1')
                       ->getStyle('A1:B1')     
                       ->getFill()
                       ->applyFromArray(array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0066CC')
                                        )
                        );
                        
        $rowStartHeader = 1;
        foreach($this->report['headers'] as $headerName => $data)
        {
            $start = key($data['datasub']); # 1st key
            end($data['datasub']); # point to last key
            $end = key($data['datasub']); # last key
            
            $phpExcelObject->setActiveSheetIndex(0)
                       ->getStyle($start.'1')
                       ->getFont()
                       ->getColor()
                       ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
                                      
            $phpExcelObject->getActiveSheet()
                ->mergeCells($start.'1:'.$end.'1')
                ->getStyle($start.'1')
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
           $phpExcelObject
                ->getActiveSheet()
                ->getStyle($start.'1')     
                ->getFill()
                ->applyFromArray(array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0066CC')
                    )
                );
            $phpExcelObject->getActiveSheet()->setCellValue($start.'1', $conc_name." - ".$headerName);
        }

       
                      
           
       $phpExcelObject->getActiveSheet()->setTitle('Raw Data');
       // Set active sheet index to the first sheet, so Excel opens this as the first sheet
       $phpExcelObject->setActiveSheetIndex(0);
       
       // This is another report for all users feedback summary report
       $phpExcelObject->createSheet();
       $phpExcelObject->setActiveSheetIndex(1);
       $phpExcelObject->getActiveSheet()->setTitle('Summary Report');
       for($col = 'A'; $col !== 'G'; $col++)
       {
            $phpExcelObject->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
       }
       
       $phpExcelObject->getActiveSheet()
                       ->mergeCells('A2:B2')
                       ->getStyle('A2:B2')     
                       ->getFill()
                       ->applyFromArray(array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '0066CC')
                                        )
                        );
       $phpExcelObject->getActiveSheet()
                      ->getStyle('A2:B2')
                      ->getAlignment()
                      ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                      ->setWrapText(true);
                      
       $phpExcelObject->getActiveSheet()
                      ->getStyle('A2:B2')
                      ->getFont()
                      ->setBold(true)
                      ->getColor()
                      ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
                      
                      
         
       $phpExcelObject->getActiveSheet()
                       ->mergeCells('C8:F8')
                       ->getStyle('C8:F8')     
                       ->getFill()
                       ->applyFromArray(array(
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '53DBDB')
                                        )
                        );
       $phpExcelObject->getActiveSheet()
                      ->getStyle('C8:F8')
                      ->getAlignment()
                      ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                      ->setWrapText(true);
                      
       $phpExcelObject->getActiveSheet()
                      ->getStyle('C8:F8')
                      ->getFont()
                      ->setBold(true)
                      ->getColor()
                      ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
      $rating = 1;

      for($i = 2; $i<6; $i++)
      {
        $phpExcelObject->getActiveSheet()
                      ->getStyle($alpha[$i].'9')
                      ->getAlignment()
                      ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                      ->setWrapText(true);
        
        $phpExcelObject->getActiveSheet()->setCellValue($alpha[$i].'9', $rating . ' - '.($this->convertRatingToDescription($rating)));
        $rating++;
      }
      
      $col_start_data = 0;
      $row_start_data = 3;
      
      foreach($summary_report_data['header_data'] as $sk=>$report_top_header)
      { 

          for($col_start_data = 0; $col_start_data < 2; $col_start_data++)
          {
               if($col_start_data == 0)    
                   $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $sk);
               else
               {
                   $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $report_top_header);      
                   $phpExcelObject->getActiveSheet()->getStyle('B'.$row_start_data)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
               }
          }
          $col_start_data = 0;        
          $row_start_data++;  
      }
    
      $row_start_data = 9;
        foreach($summary_report_data['sub_headers'] as $shc=>$sub_headers_questions)
        {
            $row_start_data++;    
            $phpExcelObject->getActiveSheet()->mergeCells('A'.$row_start_data.':B'.$row_start_data)->getStyle('A2:B2')->getFill()
                           ->applyFromArray(array(
                          'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                          'color' => array('rgb' => '0066CC')
                           )
                          );
                          
           $phpExcelObject->getActiveSheet()->getStyle('A'.$row_start_data.':B'.$row_start_data)->getFill()
                         ->applyFromArray(array(
                          'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                          'color' => array('rgb' => '333333')
                           )
                          );
                          
         $phpExcelObject->getActiveSheet()
                     ->getStyle('A'.$row_start_data.':B'.$row_start_data)
                     ->getFont()
                     ->getColor()
                     ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
                                       
                          
          $phpExcelObject->getActiveSheet()->getStyle('A'.$row_start_data.':B'.$row_start_data)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setWrapText(true);
           
          $phpExcelObject->getActiveSheet()->setCellValue('A'.$row_start_data, $shc);
            foreach($sub_headers_questions as $sqk=>$question)
            {
                //-- Generates a report that have grouped question
                if($sqk === 0){
                    
                  foreach ($question as $g_area => $g_val) {
                    $row_start_data++;
                    $phpExcelObject->getActiveSheet()->setCellValue("A$row_start_data", "$g_area");
                    $phpExcelObject->getActiveSheet()
                                   ->mergeCells("A$row_start_data:F$row_start_data")
                                   ->getStyle("A$row_start_data:F$row_start_data")     
                                   ->getFill()
                                   ->applyFromArray(array(
                                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '92D050')
                                )
                      );
                    $phpExcelObject->getActiveSheet()->getStyle('A'.$row_start_data.':H'.$row_start_data)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setWrapText(true);
                    
                    foreach ($g_val as $gk => $gv) {
                        $row_start_data++;
                        $phpExcelObject->getActiveSheet()->mergeCells('A'.$row_start_data.':B'.$row_start_data)->getStyle('A2:B2')->getFill()
                                 ->applyFromArray(array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '0066CC')
                                 )
                                );  
                        $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $gk);
                        $col_start_data = 2;
                        foreach ($gv as $gkey => $gval) {
                            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, 
                             $row_start_data, $gval." "."(".number_format(($gval/$summary_report_data['header_data']['Total Recepients:'])*(100), 2,'.','')." %)");
                             $col_start_data++;  
                        }
                        $col_start_data = 0;
                    }
                  }
                  break;
                }
                //-- Generates the standard reporting
                else {
                  $row_start_data++;
                  $phpExcelObject->getActiveSheet()->mergeCells('A'.$row_start_data.':B'.$row_start_data)->getStyle('A2:B2')->getFill()
                             ->applyFromArray(array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '0066CC')
                             )
                            );  
                  $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $sqk);
                  $col_start_data = 2;
                  
                  foreach($question as $answer_value)
                  {  
                     $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, 
                     $row_start_data, $answer_value." "."(".number_format(($answer_value/$summary_report_data['header_data']['Total Recepients:'])*(100), 2,'.','')." %)");
                     $col_start_data++;          
                 }
                  
                  $col_start_data = 0;                
                }
            }
            $row_start_data++;                  
        }

       $phpExcelObject->getActiveSheet()->setCellValue('A2', 'Online Feedback Form - '.$conc_name);
       $phpExcelObject->getActiveSheet()->setCellValue('C8', 'Rating');

       //-- Additional tabs if separated question is set
       if($summary_report_data['extra_header']){
            
          $tabs = 1;
          foreach ($summary_report_data['extra_header'] as $key => $value) {
                $tabs++;
                $phpExcelObject->createSheet();
                $phpExcelObject->setActiveSheetIndex($tabs);
                $phpExcelObject->getActiveSheet()->setTitle($key);

                for($col = 'A'; $col !== 'G'; $col++)
                {
                    $phpExcelObject->getActiveSheet()
                        ->getColumnDimension($col)
                        ->setAutoSize(true);
                }

                $phpExcelObject->getActiveSheet()->setCellValue('A2', 'Online Feedback Form - '.$conc_name);
                $phpExcelObject->getActiveSheet()->setCellValue('C8', 'Rating');
                $phpExcelObject->getActiveSheet()
                               ->mergeCells('A2:B2')
                               ->getStyle('A2:B2')     
                               ->getFill()
                               ->applyFromArray(array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '0066CC')
                                                )
                                );
                $phpExcelObject->getActiveSheet()
                              ->getStyle('A2:B2')
                              ->getAlignment()
                              ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                              ->setWrapText(true);
                              
                $phpExcelObject->getActiveSheet()
                              ->getStyle('A2:B2')
                              ->getFont()
                              ->setBold(true)
                              ->getColor()
                              ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
                              
                              
                 
                $phpExcelObject->getActiveSheet()
                               ->mergeCells('C8:F8')
                               ->getStyle('C8:F8')     
                               ->getFill()
                               ->applyFromArray(array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '53DBDB')
                                                )
                                );
                $phpExcelObject->getActiveSheet()
                              ->getStyle('C8:F8')
                              ->getAlignment()
                              ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                              ->setWrapText(true);
                              
                $phpExcelObject->getActiveSheet()
                              ->getStyle('C8:F8')
                              ->getFont()
                              ->setBold(true)
                              ->getColor()
                              ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);
                $rating = 1;

                for($i = 2; $i<6; $i++)
                {
                $phpExcelObject->getActiveSheet()
                              ->getStyle($alpha[$i].'9')
                              ->getAlignment()
                              ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                              ->setWrapText(true);

                $phpExcelObject->getActiveSheet()->setCellValue($alpha[$i].'9', $rating . ' - '.($this->convertRatingToDescription($rating)));
                $rating++;
                }

                $col_start_data = 0;
                $row_start_data = 3;

                foreach($summary_report_data['header_data'] as $sk=>$report_top_header)
                { 

                  for($col_start_data = 0; $col_start_data < 2; $col_start_data++)
                  {
                       if($col_start_data == 0)    
                           $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $sk);
                       else
                       {
                           $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $report_top_header);      
                           $phpExcelObject->getActiveSheet()->getStyle('B'.$row_start_data)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                       }
                  }
                  $col_start_data = 0;
                  $row_start_data++;  
                }
              $row_start_data = 9;

              //-- Generates the standard report
              foreach ($value as $sq => $sqk) {
                    $row_start_data++;
                    $phpExcelObject->getActiveSheet()->mergeCells('A'.$row_start_data.':B'.$row_start_data)->getStyle('A2:B2')->getFill()
                             ->applyFromArray(array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '0066CC')
                             )
                            );  
                    $phpExcelObject->getActiveSheet()
                        ->getStyle('A'.$row_start_data.':B'.$row_start_data)
                        ->getAlignment()
                        ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                        ->setWrapText(true);

                    $phpExcelObject->getActiveSheet()->getStyle('A'.$row_start_data.':B'.$row_start_data)->getFill()
                        ->applyFromArray(array(
                            'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '333333')
                        )
                    );
                    $phpExcelObject->getActiveSheet()
                        ->getStyle('A'.$row_start_data.':B'.$row_start_data)
                        ->getFont()
                        ->getColor()
                        ->setARGB(\PHPExcel_Style_Color::COLOR_WHITE);

                    $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $sq);
                    $col_start_data = 2;

                    //-- Generates  the questions and results
                    foreach ($g_val as $gk => $gv) {
                        $row_start_data++;
                        $phpExcelObject->getActiveSheet()->mergeCells('A'.$row_start_data.':B'.$row_start_data)->getStyle('A2:B2')->getFill()
                                 ->applyFromArray(array(
                                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '0066CC')
                                 )
                                );  
                        $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, $row_start_data, $gk);
                        $col_start_data = 2;
                        foreach ($gv as $gkey => $gval) {
                            $phpExcelObject->getActiveSheet()->setCellValueByColumnAndRow($col_start_data, 
                             $row_start_data, $gval." "."(".number_format(($gval/$summary_report_data['header_data']['Total Recepients:'])*(100), 2,'.','')." %)");
                             $col_start_data++;  
                        }
                        $col_start_data = 0;
                    }
                    $col_start_data = 0;
              }
          }
       }

        // print '<pre>';
        // print_r($summary_report_data['extra_header']);
        // die();
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        
        $q = $this->getDoctrine()->getRepository('FeedbackSurveyFormBundle:Concessionaire')->findOneByDescription($conc_name);
        $file = $conc_name."_".$filename.".xls";
        $dir = getcwd();
        $writer->save($dir.'/reports/online_feeback_report-'.$conc_name."_".$filename.".xls");
        // $writer->save('/db/webuser/html/online_feedback/web/reports/online_feeback_report-'.$conc_name."_".$filename.".xls");
        
        return $file;  
    }



  /**
   * @Route("/load-report-concessionaire", name="_loadrc")
   */
  public function loadReportConcessionaire(Request $reqeust)
  {
    if ( $this->is403() )
            return $this->redirect($this->generateUrl('_login'));
      
    try
    {
      $em = $this->getDoctrine()->getManager();
      $conc_repo = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findAll();
      
      $concs = new ConcessionaireSerializer($conc_repo);
  
      //echo json_encode(array('data'=>$item));
      $retval = array('data'=>$concs->jsonSerialize(), 'totalCount' => count($conc_repo));
      
      $response = new Response(json_encode($retval));
          $response->headers->set('Content-Type', 'application/json');    
    }
    catch ( Exception $e )
    {
      $response = new Response(json_encode(array('error' => json_encode($e))));
          $response->headers->set('Content-Type', 'application/json');
    }
    return $response;   
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
  
  function isValidSelects($string)
  {
    // only aplphaNumberic hyphen dash...
    $isValid = true;
    $msg = "";
    $data = null;
    //if ( empty($string) || !is_string($string) || preg_match("/[^,;a-zA-Z0-9_-]|[,;]$/s", $string) ) {
    // if ( empty($string) || !is_string($string) || preg_match("/[^\\.a-zA-Z0-9_-\s]|[\\.]$/s", $string) ) {
    //  return array('isValid' => false, "msg" => "Invalid Characters on Input. $string.");
    // }
  
    try {
      $em = $this->getDoctrine()->getManager();
      $conc = $em->getRepository("AdminMaintenanceBundle:Concessionaire")->findByDescription($string);
      $data = $conc[0]->getIdconcessionaire();
      if ( empty($conc) )  {
        //throw $this->createNotFoundException(
                    $msg = implode(", ", $conc)." Invalid input. $string not found.";
          $data = null;
          $isValid = false;
                //);
      }
    } catch (Exception $e) {
       $msg = "Invalid input: ".$e;
    }
    
    return array('isValid' => $isValid, "msg" => $msg, "data" => $data);
  }
  
  function validateDashedDate($date)
  {
    $format = 'Y-m-d';
      $d = \DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
  }
  
  function is403()
  {
    $session = $this->get('session');
    if ( $session->get('role') == 'report' || $session->get('role') == 'admin' )
      return false;
    
    return true;
  }
  function convertRatingToDescription($number) {
    $number--;
    try
    {
      if ( $number > 3 || $number < 0 || !is_integer($number) )
        throw new \Exception("Invalid value: $number");
    }
    catch (\Exception $e)
    {
      echo 'Caught exception at convertRatingToDescription fn: '. $e->getMessage(). "\n";
    }
    
    $static = array('Highly Disagree', 'Disagree', 'Agree', 'Highly Agree');
    
    return $static[$number]; 
  }
}
