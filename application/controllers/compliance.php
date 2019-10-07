<?php 
/**
 * Compliance Controller Class
 *
 * This is a Compliance controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Compliance Controller Class
 *
 * This is a Compliance controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Compliance
 * @filesource          Compliance.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Compliance extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
        
        $this->load->library('compliance/ComplianceClass');
    }

    /**
    * This function use for show My Compliances
    * 
    * @return void 
    */
    public function index()
    {
        
        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['trades'] = $this->sharedclass->getTrades();
        $this->data['states'] = $this->sharedclass->getStates(1);
        $captionData = $this->complianceclass->getComplianceCaption($customerid);
        $formatedCaptionData = array();
        foreach ($captionData as $key => $value) {
           
            $captionData[$key]['name'] = str_replace(' ', '_', strtolower($value['caption']));
            $captionData[$key]['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $captionData[$key]['name']);
            $formatedCaptionData[$captionData[$key]['name']] =  $captionData[$key];
        }
        $this->data['captionData'] = $formatedCaptionData;
        
        
        $this->data['cssToLoad'] = array( 
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'), 
            base_url('plugins/uigrid/ui-grid-stable.min.css') 
         );


        $this->data['jsToLoad'] = array( 
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/uigrid/angular.min.js'), 
            base_url('plugins/uigrid/ui-grid-stable.min.js'), 
            base_url('assets/js/compliance/compliance.index.js') 
        );

          $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | My Compliance')
                ->set_layout($this->layout)
                ->set('page_title', 'My Compliance')
                ->set('page_sub_title', '')
                ->set_breadcrumb('My Compliance', '')
                ->set_partial('page_header', 'shared/page_header')
                ->set_partial('header', 'shared/header')
                ->set_partial('navigation', 'shared/navigation')
                ->set_partial('footer', 'shared/footer')
                ->build('compliance/index', $this->data);
 

    }
    
    /**
    * This function use for load Compliance Data in uigrid
    * 
    * @return json 
    */
    public function loadCompliances() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;
        try
        { 
            $message = '';
            $isSuccess = TRUE; 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                //default settings for uigrid
                $page = 1;
                $size = 25;
                $order = 'asc';
                $field = 'firstname';
                $params = array();
                $filter = '';
                //intialize uigrid request params
                if ($this->input->get('page')) {
                    $page = $this->input->get('page');
                    $size = $this->input->get('size');
                    $field = $this->input->get('field') != '' ? $this->input->get('field') : $field;
                    $order = $this->input->get('order') != '' ? $this->input->get('order') : $order;
                }
                
                if (trim($this->input->get('filterText')) != '') {
                    $filter = $this->input->get('filterText');
                }
             
                if ($this->input->get('state') != NULL) {
                    $params['c.state'] = $this->input->get('state');
                }
                
                if ($this->input->get('trade') != NULL) {
                    $params['cu.industrysector'] = $this->input->get('trade');
                }
                 
                //intialize start page for uigrid
                $start = ($page - 1) * $size;
                
                //get document data
                $customerid = $this->session->userdata('raptor_customerid');
                $captionData = $this->complianceclass->getComplianceCaption($customerid);
                $formatedCaptionData = array();
                foreach ($captionData as $key => $value) {

                    $captionData[$key]['name'] = str_replace(' ', '_', strtolower($value['caption']));
                    $captionData[$key]['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $captionData[$key]['name']);
                    $formatedCaptionData[$captionData[$key]['name']] =  $captionData[$key];
                }
                
                
                $complianceDate = $this->complianceclass->getComplianceData($customerid, $size, $start, $field, $order, $filter, $params);
                
                $trows = $complianceDate['trows'];
                $data = $complianceDate['data'];
                
                //format data for uigrid
                foreach ($data as $key => $value) {
                   
                    foreach ($formatedCaptionData as $key2 => $value2) {
                        $ccData = $this->complianceclass->getComplianceCaptionData($customerid, $value['contactid'], $value2['caption']);
                        if(count($ccData)>0){
                            $data[$key][$key2.'_documentid'] = $ccData['documentid'];
                            $data[$key][$key2.'_docname'] = $ccData['docname'];
                            $data[$key][$key2.'_number'] = $ccData['number'];
                            $data[$key][$key2.'_sdate'] = format_date($ccData['startdate']);
                            $data[$key][$key2.'_edate'] = format_date($ccData['expirydate']);

                            $data[$key][$key2.'_has_startdate'] = (bool)$ccData['has_startdate'];
                            $data[$key][$key2.'_has_number'] = (bool)$ccData['has_number'];
                            $data[$key][$key2.'_has_expiry'] = (bool)$ccData['has_expiry'];
                            $data[$key][$key2.'_has_doclink'] = (bool)$ccData['has_doclink'];
                        }
                        else{
                            $data[$key][$key2.'_documentid'] = '';
                            $data[$key][$key2.'_docname'] = '';
                            $data[$key][$key2.'_number'] = '';
                            $data[$key][$key2.'_sdate'] = '';
                            $data[$key][$key2.'_edate'] = '';

                            $data[$key][$key2.'_has_startdate'] = TRUE;
                            $data[$key][$key2.'_has_number'] = TRUE;
                            $data[$key][$key2.'_has_expiry'] = TRUE;
                            $data[$key][$key2.'_has_doclink'] = false;
                        }
                    }
                     
                }
                $success -> setData($data);
                $success -> setTotal($trows);
                
            }
        }
        catch( Exception $e ) {
            
            $success = SuccessClass::initialize(FALSE);
            $message = $e->getMessage();
            $message = $message . " - " . $e->getTraceAsString(); 
             //log the exception
            $this->logClass->log("exception : ", $message);
            $code = SuccessClass::$CODE_EXCEPTION_OCCURED;
        }

        //set the variables
        $success -> setMessage($message);
        $success -> setCode($code);
        $success -> setData($data);
        
        //convert response array to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
    }

    /**
    * This function use for export Compliance data for excel
    * 
    * @return json 
    */
    public function exportExcel() {
        
        
         
        $params = array();
        $order = 'asc';
        $field = 'firstname';
        $filter = '';
        if (trim($this->input->get('filterText')) != '') {
            $filter = $this->input->get('filterText');
        }

        if ($this->input->get('state') != NULL) {
            $params['c.state'] = $this->input->get('state');
        }

        if ($this->input->get('trade') != NULL) {
            $params['cu.industrysector'] = $this->input->get('trade');
        }
         
        $customerid = $this->session->userdata('raptor_customerid');
        $captionData = $this->complianceclass->getComplianceCaption($customerid);
        $formatedCaptionData = array();
        foreach ($captionData as $key => $value) {

            $captionData[$key]['name'] = str_replace(' ', '_', strtolower($value['caption']));
            $captionData[$key]['name'] = preg_replace('/[^A-Za-z0-9\-]/', '', $captionData[$key]['name']);
            $formatedCaptionData[$captionData[$key]['name']] =  $captionData[$key];
        }
        
        
        $complianceDate = $this->complianceclass->getComplianceData($customerid, NULL, 0, $field, $order, $filter, $params);
        $trows = $complianceDate['trows'];
        $data = $complianceDate['data'];
        foreach ($data as $key => $value) {
                   
            foreach ($formatedCaptionData as $key2 => $value2) {
                $ccData = $this->complianceclass->getComplianceCaptionData($customerid, $value['contactid'], $value2['caption']);
                if(count($ccData)>0){
                    $data[$key][$key2.'_documentid'] = $ccData['documentid'];
                    $data[$key][$key2.'_docname'] = $ccData['docname'];
                    $data[$key][$key2.'_number'] = $ccData['number'];
                    $data[$key][$key2.'_sdate'] = format_date($ccData['startdate']);
                    $data[$key][$key2.'_edate'] = format_date($ccData['expirydate']);

                    $data[$key][$key2.'_has_startdate'] = (bool)$ccData['has_startdate'];
                    $data[$key][$key2.'_has_number'] = (bool)$ccData['has_number'];
                    $data[$key][$key2.'_has_expiry'] = (bool)$ccData['has_expiry'];
                    $data[$key][$key2.'_has_doclink'] = (bool)$ccData['has_doclink'];
                }
                else{
                    $data[$key][$key2.'_documentid'] = '';
                    $data[$key][$key2.'_docname'] = '';
                    $data[$key][$key2.'_number'] = '';
                    $data[$key][$key2.'_sdate'] = '';
                    $data[$key][$key2.'_edate'] = '';

                    $data[$key][$key2.'_has_startdate'] = TRUE;
                    $data[$key][$key2.'_has_number'] = TRUE;
                    $data[$key][$key2.'_has_expiry'] = TRUE;
                    $data[$key][$key2.'_has_doclink'] = false;
                }
            }

        }
        $this->load->library('excel');
   
         //set excel configurations
        $heading = array('Name', 'Compnay', 'Suburb', 'State', 'Trade', 'Type');
        $this->excel->getDefaultStyle()->getAlignment()->setWrapText(TRUE);
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10); 
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $style = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 
            )
        );

        $this->excel->getDefaultStyle()->applyFromArray($style);
        
        $this->excel->getProperties()->setCreator("DCFM")
			     ->setLastModifiedBy("DCFM")
			     ->setTitle("My Compliance")
			     ->setSubject("DCFM Client Portal :: My Compliance")
		   	     ->setDescription("DCFM Client Portal :: My Compliance")
			      ->setKeywords('My Compliance')
		      	     ->setCategory('My Compliance');
        $this->excel->getActiveSheet()->setTitle('My Compliance');
        
        //Loop Heading
        $rowNo = 1;
        $colH1 = 'G';
        $this->excel->getActiveSheet()->setCellValue('A1', 'Contractor Details');
        $this->excel->getActiveSheet()->mergeCells("A1:F1");
        $colH = 'G';
        
        foreach ($formatedCaptionData as $key => $value) {  
            $t = 0;
            if((bool)$value['has_number']){
                $t++;
                $heading[] = 'Number';
                $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(13); 
                $colH++;
            }
            
            if((bool)$value['has_startdate']){
                $t++;
                $heading[] = 'Start Date';
                $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(15); 
                $colH++;
            }
            
            if((bool)$value['has_expiry']){
                $t++;
                $heading[] = 'Expiry Date';
                $this->excel->getActiveSheet()->getColumnDimension($colH)->setWidth(15);
                $colH++; 
            }
            
            if($t > 0){
                
                $this->excel->getActiveSheet()->setCellValue($colH1.$rowNo, $value['caption']);
                
                $colH2 = $colH1;
                for($i = 1; $i< $t; $i++){
                    $colH2++;
                }
                
                $this->excel->getActiveSheet()->mergeCells($colH1.$rowNo.":".$colH2.$rowNo);
                
                $colH1 = $colH;
            }
            
        }
       
        $rowNo++;
        $colH = 'A';
        foreach($heading as $h){
            $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $h);
            $colH++;    
        }
 
        //Loop Result
        $rowNo++;
        foreach($data as $row1){ 
            $this->excel->getActiveSheet()->setCellValue('A'.$rowNo, $row1['firstname']);
            $this->excel->getActiveSheet()->setCellValue('B'.$rowNo, $row1['company']);
            $this->excel->getActiveSheet()->setCellValue('C'.$rowNo, $row1['suburb']);
            $this->excel->getActiveSheet()->setCellValue('D'.$rowNo, $row1['state']);
            $this->excel->getActiveSheet()->setCellValue('E'.$rowNo, $row1['trade']);
            $this->excel->getActiveSheet()->setCellValue('F'.$rowNo, $row1['type']);
            
            $colH = 'G';
            foreach ($formatedCaptionData as $key2 => $value) {  
                if((bool)$value['has_number']) {
                    $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1[$key2.'_number']);
                    $colH++;
                }
                if((bool)$value['has_startdate']) {
                    $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1[$key2.'_sdate']);
                    $colH++;
                }
                if((bool)$value['has_expiry']) {
                    $this->excel->getActiveSheet()->setCellValue($colH.$rowNo, $row1[$key2.'_edate']);
                    $colH++;
                }
            } 
            $rowNo++;
        }
        
        $colH1--;
         
        //Freeze pane
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.'1')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'CCCCCC')
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                )
            )
        );
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.'2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->freezePane('A3');
        //Cell Style
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

       
        $this->excel->getActiveSheet()->getStyle('A1:'.$colH1.($trows+2))->applyFromArray($styleArray);
        //Save as an Excel BIFF (xls) file
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel,'Excel5');
        
        // Load the file helper and write the file to your server
        $dir = "./temp";
        if(!is_dir($dir))
        {
                mkdir($dir, 0755, true);
        }

        $file_name="My_Compliance.xls";
 
        $objWriter->save($dir.'/'.$file_name);
         
        //$this->excel->Exportexcel("My Compliance", $dir, $file_name, $heading, $dataArray);
        $this->load->helper('download');
        $filedata = file_get_contents(base_url()."temp/".$file_name);
        force_download('My_Compliance.xls', $filedata);
         
    }

    
}