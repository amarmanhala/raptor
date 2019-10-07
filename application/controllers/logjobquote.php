<?php 
/**
 * logjobquote Controller Class
 *
 * This is a logjobquote controller class  
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * logjobquote Controller Class
 *
 * This is a logjobquote controller class  
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            logjobquote
 * @filesource          logjobquote.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class LogJobQuote extends MY_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
                
        //  Load libraries 
        $this->load->library('job/JobClass');
        $this->load->library('purchaseorder/PurchaseOrderClass');
        
        
        $this->load->helper('dynamicform_helper');
    }
    
     /**
    * This function use for show Report
    * 
    * @return void 
    */
    
    
    public function index()
    {
         
        
        $ContactRules=$this->data['ContactRules'];

        $customerid = $this->session->userdata('raptor_customerid');
        
        $customerData = $this->customerclass->getCustomerById($customerid); 
        $jobLeadData = array();
        if($this->input->get('jobleadid')){
            $jobLeadData = $this->jobclass->getLeadJobById($this->input->get('jobleadid'));
        }
        
        $fields = array();
        $rules = array();
        $states = $this->sharedclass->getStates(1);
        $state_array = array();
        foreach($states as $key=>$value) { 
           $state_array[$value['abbreviation']]=$value['abbreviation'];
        }
        $priority = $this->customerclass->getCustomerPriority($customerid);
        $this->data['priority'] = $priority;
        $use_site_ref_as_custordref =isset($ContactRules["use_site_ref_as_custordref_in_client_portal"]) ? $ContactRules["use_site_ref_as_custordref_in_client_portal"]:0;
        $use_site_ref_as_custordref2 =isset($ContactRules["use_site_ref_as_custordref2_in_client_portal"]) ? $ContactRules["use_site_ref_as_custordref2_in_client_portal"]:0;
        $fields[]=  array(array('name' => 'use_site_ref_as_custordref', 
                            'type'       => 'hidden',
                            'label'      => '',
                            'value'      => $use_site_ref_as_custordref,
                            'other'      =>'',
                            'placeholder' => '',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2'     =>'',
                            'options'    =>array(),
        ));
            
        $fields[]=  array(array('name' => 'use_site_ref_as_custordref2', 
                            'type'       => 'hidden',
                            'label'      => '',
                            'value'      => $use_site_ref_as_custordref2,
                            'other'      =>'',
                            'placeholder' => '',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2'     =>'',
                            'options'    =>array(),
                           ));    
        
  
            
            $get_fm_from_site =isset($ContactRules["get_fm_from_site_in_client_portal"]) ? $ContactRules["get_fm_from_site_in_client_portal"]: (($this->session->userdata('raptor_role')=='sitefm'  || $this->session->userdata('raptor_role')=='master') ? 0:1);
            
            $sitedetails = array();  
            $cna = array();
           
            if($get_fm_from_site)
            {
                $siteAddresses = $this->customerclass->getAddressByParams(array('sitecontactid'=>$this->session->userdata('raptor_contactid')));
                if(count($siteAddresses)>0){
                    $sitedetails = $siteAddresses[0];
                    $cna = $this->customerclass->getContactById($sitedetails['contactid']);
                }
                
            }
            else {
                $siteAddresses = $this->customerclass->getAddressByParams(array('contactid'=>$this->session->userdata('raptor_contactid')));
                if(count($siteAddresses)>0){
                    $sitedetails = $siteAddresses[0];
                }
                $cna = $this->customerclass->getContactById($this->session->userdata('raptor_contactid'));
            }
             
           if($this->session->userdata('raptor_role') != 'site contact'){ 
                $sitedetails = array();  
                $cna = array();
           }
            
            if (!isset($ContactRules["auto_create_custordref_in_client_portal"]) || (isset($ContactRules["auto_create_custordref_in_client_portal"]) && !$ContactRules["auto_create_custordref_in_client_portal"] == "1")){
                $ref1 =isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';
                $custordref_not_mandatory =isset($ContactRules["custordref_not_mandatory"]) ? $ContactRules["custordref_not_mandatory"]:0;
                $required="";
                $required1="";
                if(!$custordref_not_mandatory){
                    $rules[]= array('field'  => 'custordref', 
                      'label'  => $ref1,
                      'rules'  => 'required|trim');
                     $required="required";
                      $required1='required="required"';
                }
                if($use_site_ref_as_custordref){
                     $required1 .= " readonly ";
                }
               
              
                if (isset($ContactRules["custordref1_from_customerpo"]) && $ContactRules["custordref1_from_customerpo"] == "1"){
                    
                    $poData = $this->purchaseorderclass->getCustomerPOs($customerid, 'OPEN');
                    $this->data['poData'] = $poData;
                    $po_array = array();
                    foreach($poData as $key=>$value) { 
                        $po_array[$value['ponumber']]=$value['ponumber'];
                    }
                    $po_array['other'] = 'other';
                    $salectedPO = set_value('custordref', $use_site_ref_as_custordref == 1 ? 'other':'');
                    
                    $fields[]= array(
                        array('name'     => 'custordref', 
                            'type'       => 'select',
                            'label'      => $ref1,
                            'value'      => $salectedPO,
                            'other'      => $required1,
                            'placeholder'=> $ref1,
                            'divclass'   => 'col-sm-3', 
                            'fieldclass' => $required,
                            'label2'     => '',
                            'options'    => $po_array,
                           ),
                        array('name'       => 'custordrefother', 
                                'type'       => 'text',
                                'label'      => '',
                                'value'      => set_value('custordrefother', $use_site_ref_as_custordref == 1 ? (isset($sitedetails["siteref"]) ? $sitedetails["siteref"]:''):''),
                                'other'      => $salectedPO != 'other' ? 'style="display:none;"':'',
                                'placeholder' => $ref1,
                                'divclass'   =>'col-sm-3',
                                'labelclass'   =>'col-sm-1 custom',
                                'fieldclass' =>"",
                                'label2'     =>'',
                                'options'    =>array(),
                            ),
                         array('name'       => 'btnviewposummary', 
                                'type'       => 'button',
                                'label'      => '',
                                'value'      => 'View PO Summmary',
                                'other'      => '',
                                'placeholder' => '',
                                'divclass'   =>'col-sm-3',
                                'labelclass'   =>'',
                                'fieldclass' =>"btn-info",
                                'label2'     =>'',
                                'options'    =>array(),
                            )
                        );
                 }
                 else{
                     $fields[] = array( array('name'       => 'custordref', 
                                'type'       => 'text',
                                'label'      => $ref1,
                                'value'      => set_value('custordref', $use_site_ref_as_custordref == 1 ? (isset($sitedetails["siteref"]) ? $sitedetails["siteref"]:''):''),
                                'other'      => $required1,
                                'placeholder' => $ref1,
                                'divclass'   =>'col-sm-3',
                                'fieldclass' =>$required,
                                'label2'     =>'',
                                'options'    =>array(),
                            ));
                 }
                
            }
            
            if (!isset($ContactRules["auto_create_custordref2_in_client_portal"]) || (isset($ContactRules["auto_create_custordref2_in_client_portal"]) && !$ContactRules["auto_create_custordref2_in_client_portal"] == "1")){
                $ordref2 =isset($ContactRules["default_custordref2"]) ? $ContactRules["default_custordref2"]:'';
                $ref2 =isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';
                
                $use_jobid_as_custordref2 =isset($ContactRules["use_jobid_as_custordref2_in_client_portal"]) ? $ContactRules["use_jobid_as_custordref2_in_client_portal"]:0;
                
                $fields[] = array(array('name'         => 'custordref2', 
                                'type'       => $use_jobid_as_custordref2 == 1 ? 'hidden':'text',
                                'label'      => $ref2,
                                'value'      => set_value('custordref2', $use_site_ref_as_custordref2 == 1 ? (isset($sitedetails["siteref"]) ? $sitedetails["siteref"]:$ordref2):$ordref2),
                                'other'       => '',
                                'placeholder' => $ref2,
                                'divclass'   =>'col-sm-3',
                                'fieldclass' =>'',
                                'label2' =>'',
                                'options' =>array(),
                               ));
            }
            if (!isset($ContactRules["hide_custordref3_in_client_portal"]) || (isset($ContactRules["hide_custordref3_in_client_portal"]) && !$ContactRules["hide_custordref3_in_client_portal"] == "1")){
                $ref3 =isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';
                $fields[] = array(array('name'        => 'custordref3', 
                                'type'       => 'text',
                                'label'      => $ref3,
                                'value'      => set_value('custordref3', ''),
                                'other'      =>'',
                                'placeholder' => $ref3,
                                'divclass'   =>'col-sm-3',
                                'fieldclass' =>'',
                                'label2' =>'',
                                'options' =>array(),
                               ));
            }
            if (!isset($ContactRules["hide_job_priority_in_client_portal"]) || (isset($ContactRules["hide_job_priority_in_client_portal"]) && !$ContactRules["hide_job_priority_in_client_portal"] == "1")){
         
                $priority_array = array();
                foreach($priority as $key=>$value) { 
                    $priority_array[$value['days_offset']] = $value['display_description'];
                }
                $fields[] = array(array('name'        => 'priority', 
                            'type'       => 'select',
                            'label'      => 'Priority',
                            'value'      => set_value('priority', '-1'),
                            'other'      =>'required="required"',
                            'placeholder' => 'Priority',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>'select2 required',
                            'label2' =>'',
                            'options' =>$priority_array,
                           ));
                $rules[] = array('field'  => 'priority', 
                            'label'  => 'Priority', 
                            'rules'  => 'required|trim');
		 				
            }

            
            if (isset($ContactRules["show_custom_allocation"]) && $ContactRules["show_custom_allocation"] == "1"){
      
			
			  $allocate_array = array("1000" =>"DCFM","1001" => "ACME Supplier", "1002" => "ABC Locksmiths");
			  $allocate_array = $this->customerclass->getSupplierAllocations($customerid);
			 
			  $fields[] = array(array('name'        => 'allocateto', 
                            'type'       => 'select',
                            'label'      => 'Allocate To',
                            'value'      => set_value('allocateto', '-1'),
                            'other'      =>'required="required"',
                            'placeholder' => 'Allocate',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>'select2 required',
                            'label2' =>'',
                            'options' =>$allocate_array,
                           ));
            }
            if (!isset($ContactRules["hide_attend_date_time_in_client_portal"]) || (isset($ContactRules["hide_attend_date_time_in_client_portal"]) && !$ContactRules["hide_attend_date_time_in_client_portal"] == "1")){
                    $fields[]=  array(array('name'        => 'attenddate', 
                                'type'       => 'date',
                                'label'      => 'Attendance Date',
                                'value'      => set_value('attenddate'),
                                'other'      => '',
                                'placeholder' => 'Attendance Date',
                                'divclass'   =>'col-sm-3',
                                'fieldclass' =>'',
                                'label2' =>'',
                                'options' =>array()
                               ));
                    $fields[]= array( array('name'        => 'attendtime', 
                                'type'       => 'time',
                                'label'      => 'Attendance Time',
                                'value'      => set_value('attendtime'),
                                'other'      =>'',
                                'placeholder' => 'Attendance Time',
                                'divclass'   =>'col-sm-3',
                                'fieldclass' =>'',
                                'label2' =>'',
                                'options' =>array()
                               ));
            }
            
              $fields[]=  array(array('name' => 'responsedatelock', 
                                'type'       => 'hidden',
                                'label'      => 'Response on this date',
                                'value'      => set_value('responsedatelock', 'false'),
                                'other'      =>'',
                                'placeholder' => 'Response on this date',
                                'divclass'   =>'',
                                'fieldclass' =>'',
                                'label2'     =>'',
                                'options'    =>array(),
                               ));
          
            
            if (!isset($ContactRules["auto_create_custlimit_in_client_portal"]) || (isset($ContactRules["auto_create_custlimit_in_client_portal"]) && !$ContactRules["auto_create_custlimit_in_client_portal"] == "1")){		
                $joblimit = $this->customerclass->getCustomerJobLimit($customerid);
                $joblimit_array = array();
                foreach($joblimit as $key=>$value) { 
                    $joblimit_array[$value['joblimit']]='$'. $value['joblimit'];
                }
                
                if ((isset($ContactRules["hide_job_limit_in_client_portal"]) && $ContactRules["hide_job_limit_in_client_portal"] == "1")){
                     $fields[]=  array(array('name'        => 'notexceed', 
                            'type'       => 'hidden',
                            'label'      => '$ Job Limit *',
                            'value'      => set_value('notexceed', $customerData['supplier_threshold']),
                            'other'      =>'',
                            'placeholder' => '$ Job Limit *',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>$joblimit_array,
                           ));
            
                }
                else{
                    if(trim($this->input->post('quoterqd')) == 'on'){
                        $fields[]=  array(array('name'        => 'notexceed', 
                            'type'       => 'select',
                            'label'      => '$ Job Limit *',
                            'value'      => set_value('notexceed', $customerData['supplier_threshold']),
                            'other'      =>'',
                            'placeholder' => '$ Job Limit *',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>'select2 ',
                            'label2' =>'',
                            'options' =>$joblimit_array,
                           ));
                    }
                    else{
                     $fields[]=  array(array('name'        => 'notexceed', 
                            'type'       => 'select',
                            'label'      => '$ Job Limit *',
                            'value'      => set_value('notexceed', $customerData['supplier_threshold']),
                            'other'      =>'required="required"',
                            'placeholder' => '$ Job Limit *',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>'select2 required',
                            'label2' =>'',
                            'options' =>$joblimit_array,
                           ));
                    $rules[] = array('field'  => 'notexceed', 
                            'label'  => 'Job Limit', 
                            'rules'  => 'required|trim');
                    }
                }
               
            }

            if (isset($ContactRules["show_glcode_in_client_portal"]) && $ContactRules["show_glcode_in_client_portal"] == "1"){
      
                   $glcodes = $this->customerclass->getCustomerGLChart($customerid, 'E');
                    $glcode_array = array();
                    foreach($glcodes as $key=>$value) { 
                        $glcode_array[$value['id']]= $value['glcode'];
                    }
                    
                    $otherAttr = '';
                    $fieldClassAttr = 'select2';
                    if(isset($ContactRules["GL_CODE_MANDATORY"]) && $ContactRules["GL_CODE_MANDATORY"] == 1) {
                        $otherAttr = 'required="required"';
                        $fieldClassAttr = 'select2 required';
                    }
                    
                    $fields[] = array(array('name'        => 'custglchartid', 
                      'type'        => 'select',
                      'label'       => 'GL Code',
                      'value'       => set_value('custglchartid', '-1'),
                      'other'       => $otherAttr,
                      'placeholder' => 'GL Code',
                      'divclass'    => 'col-sm-4',
                      'fieldclass'  => $fieldClassAttr,
                      'label2'      => '',
                      'options'     => $glcode_array,
                     ));
                    
                    if(isset($ContactRules["GL_CODE_MANDATORY"]) && $ContactRules["GL_CODE_MANDATORY"] == 1) {
                        $rules[] = array('field'  => 'custglchartid', 
                                'label'  => 'GL Code', 
                                'rules'  => 'required|trim') ; 
                    }
            }
            
            if (!isset($ContactRules["hide_quote_request_in_client_portal"]) || (isset($ContactRules["hide_quote_request_in_client_portal"]) && !$ContactRules["hide_quote_request_in_client_portal"] == "1")){
                    $fields[]=  array(array('name'        => 'quoterqd', 
                                        'type'       => 'checkbox',
                                        'label'      => '',
                                        'value'      => 'on',
                                        'other'      =>'',
                                        'placeholder' => '',
                                        'divclass'   =>'',
                                        'fieldclass' =>'minimal',
                                        'label2' =>'Do You Require A Quote?',
                                        'options' =>array(),
                                       )); 
 
            }  
                  
            $fields[]=  array(array('name'        => 'customerid', 
                       'type'       => 'hidden',
                       'label'      => 'customerid',
                       'value'      => $this->session->userdata('raptor_customerid'),
                       'other'      =>'',
                       'placeholder' => 'customerid',
                       'divclass'   =>'',
                       'fieldclass' =>'',
                       'label2' =>'',
                       'options' =>array(),
                      ));
            $fields[]=  array(array('name'        => 'jobid', 
                       'type'       => 'hidden',
                       'label'      => '',
                       'value'      => set_value('jobid','0'),
                       'other'      =>'',
                       'placeholder' => '',
                       'divclass'   =>'',
                       'fieldclass' =>'',
                       'label2' =>'',
                       'options' =>array(),
                      ));
          
                 $fields[]=  array(array('name'        => 'site', 
                            'type'       => 'hidden',
                            'label'      => 'site',
                            'value'      => set_value('site'),
                            'other'      =>'',
                            'placeholder' => 'site',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
         
                if (!isset($ContactRules["hide_site_lookup_in_client_portal"]) || (isset($ContactRules["hide_site_lookup_in_client_portal"]) && !$ContactRules["hide_site_lookup_in_client_portal"] == "1")){
                    $fields[]=  array(array('name'        => 'sitelookup', 
                                'type'       => 'textboxwithsearchbtn',
                                'label'      => 'Site Lookup ',
                                'value'      => set_value('sitelookup',isset($jobLeadData['sitesuburb']) ? $jobLeadData['sitesuburb'] .' ' . $jobLeadData['sitestate'] : (trim((isset($sitedetails["sitesuburb"]) ? $sitedetails["sitesuburb"]:'').' '. (isset($sitedetails["sitestate"]) ? $sitedetails["sitestate"]:'')))),
                                'other'      =>'',
                                'placeholder' => 'Site Lookup ',
                                'divclass'   =>'col-sm-8',
                                'fieldclass' =>'',
                                'label2' =>'',
                                'options' =>array(),
                               )); 
                    $fields[]=  array(array('name'        => 'sitelookuphidden', 
                            'type'       => 'hidden',
                            'label'      => 'Site Lookup',
                            'value'      => set_value('sitelookuphidden'),
                            'other'      =>'',
                            'placeholder' => 'Site Lookup',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
                }
                
               
              
            
                $fields[]=  array(array('name'        => 'siteline1', 
                            'type'       => 'text',
                            'label'      => 'Company Name *',
                            'value'      => set_value('siteline1', isset($jobLeadData['siteline1']) ? $jobLeadData['siteline1'] : (isset($sitedetails["siteline1"]) ? $sitedetails["siteline1"]:'')),
                            'other'      =>'required="required"',
                            'placeholder' => 'Company Name *',
                            'divclass'   =>'col-sm-6',
                            'fieldclass' =>'required',
                            'label2' =>'',
                            'options' =>array(),
                           )); 
                $rules[] = array('field'  => 'siteline1', 
                                'label'  => 'Company Name', 
                                'rules'  => 'required|trim');
                
                $fields[]=  array(array('name'        => 'labelid', 
                            'type'       => 'hidden',
                            'label'      => '',
                            'value'      => set_value('labelid', isset($jobLeadData['labelid']) ? $jobLeadData['labelid'] : (isset($sitedetails["labelid"]) ? $sitedetails["labelid"]:0)),
                            'other'      =>'',
                            'placeholder' => 'labelid',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
                
                $fields[]=  array(array('name'        => 'sitesuburb1', 
                            'type'       => 'hidden',
                            'label'      => '',
                            'value'      => set_value('sitesuburb1',isset($jobLeadData['sitesuburb']) ? $jobLeadData['sitesuburb'] : (isset($sitedetails["sitesuburb"]) ? $sitedetails["sitesuburb"]:'')),
                            'other'      =>'data-suburb="suburb"',
                            'placeholder' => '',
                            'divclass'   =>'',
                            'fieldclass' =>'updatesuburb',
                            'label2' =>'',
                            'options' =>array(),
                           ));
                
                
                $fields[]=  array(array('name'        => 'siteline2', 
                            'type'       => 'text',
                            'label'      => 'Street Address ',
                            'value'      => set_value('siteline2', isset($jobLeadData['siteline2']) ? $jobLeadData['siteline2'] : (isset($sitedetails["siteline2"]) ? $sitedetails["siteline2"]:'')),
                            'other'      =>'',
                            'placeholder' => 'Street Address ',
                            'divclass'   =>'col-sm-6',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
                $fields[]= array( array('name'        => 'sitesuburb', 
                            'type'       => 'text',
                            'label'      => 'Suburb *',
                            'value'      => set_value('sitesuburb', isset($jobLeadData['sitesuburb']) ? $jobLeadData['sitesuburb'] : (isset($sitedetails["sitesuburb"]) ? $sitedetails["sitesuburb"]:'')),
                            'other'      =>'required="required" data-postcode="sitepostcode" data-state="sitestate" data-suburb="sitesuburb1"',
                            'placeholder' => 'Suburb *',
                            'divclass'   =>'col-sm-2',
                            'fieldclass' =>'required suburbtypeahead',
                            'label2' =>'',
                            'options' =>array(),
                           ),
                         array('name'        => 'sitestate', 
                            'type'       => 'select',
                            'label'      => 'State',
                            'value'      => set_value('sitestate', isset($jobLeadData['sitestate']) ? $jobLeadData['sitestate'] : (isset($sitedetails["sitestate"]) ? $sitedetails["sitestate"]:'')),
                            'other'      =>'',
                            'placeholder' => 'State',
                            'divclass'   =>'col-sm-2 ',
                            'labelclass'   =>'col-sm-1 custom',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>$state_array,
                           ),
                        array('name'        => 'sitepostcode', 
                            'type'       => 'text',
                            'label'      => 'Postcode',
                            'value'      => set_value('sitepostcode', isset($jobLeadData['sitepostcode']) ? $jobLeadData['sitepostcode'] : (isset($sitedetails["sitepostcode"]) ? $sitedetails["sitepostcode"]:'')),
                            'other'      =>'',
                            'placeholder' => 'Postcode',
                            'divclass'   =>'col-sm-2',
                            'labelclass'   =>'col-sm-1 custom',
                            'fieldclass' =>'postcodetypeahead',
                            'label2' =>'',
                            'options' =>array(),
                           )
                );
                $rules[]= array('field'  => 'sitesuburb', 
                                'label'  => 'Suburb', 
                                'rules'  => 'required|trim');
                $rules[] = array('field'  => 'sitestate', 
                            'label'  => 'State', 
                            'rules'  => 'required|trim');
                
                
                
                $default_site_contact = isset($ContactRules["default_site_contact_in_client_portal"]) ? $ContactRules["default_site_contact_in_client_portal"]:0;
                $cna1=$this->customerclass->getContactById($default_site_contact);
                
                $emptysitecontact = isset($ContactRules["sitecontact_empty_in_client_portal"]) ? $ContactRules["sitecontact_empty_in_client_portal"]:0;
                $sitecontact_mandatory = isset($ContactRules["site_contact_mandatory_in_client_portal"]) ? $ContactRules["site_contact_mandatory_in_client_portal"]:0;
                $required="";
                $required1="";
                if($sitecontact_mandatory){
                    $rules[]= array('field'  => 'sitecontact', 
                      'label'  => 'FM Contact',
                      'rules'  => 'required|trim');
                     $required="required";
                     $required1='required="required"';
                }
                $fields[]=  array(array('name'        => 'emptysitecontact', 
                                        'type'       => 'hidden',
                                        'label'      => 'emptysitecontact',
                                        'value'      => set_value('emptysitecontact',$emptysitecontact),
                                        'other'      =>'',
                                        'placeholder' => '',
                                        'divclass'   =>'',
                                        'fieldclass' =>'',
                                        'label2' =>'',
                                        'options' =>array(),
                           ));
                
                $sitecontactid = (isset($cna1["contactid"]) ? $cna1["contactid"]: (isset($sitedetails["sitecontactid"]) ? $sitedetails["sitecontactid"]:''));
                if(count($jobLeadData)>0){
                    $sitecontactid = $this->customerclass->getFindContactId($this->session->userdata('raptor_customerid'),(isset($jobLeadData['siteemail']) ? $jobLeadData['siteemail']:''), $jobLeadData['sitecontact'], $jobLeadData['sitephone']);
                }
                
                $fields[]=  array(array('name'        => 'sitecontactid', 
                            'type'       => 'hidden',
                            'label'      => 'contactid',
                            'value'      => set_value('sitecontactid', $sitecontactid),
                            'other'      =>'',
                            'placeholder' => '',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
                $ph1=isset($cna1["mobile"]) && $cna1["mobile"]!="" ? $cna1["mobile"]: (isset($cna1["phone"]) ? $cna1["phone"]:'');
                $ph=  isset($sitedetails["sitephone"]) ? $sitedetails["sitephone"]:'';
                $fields[]=  array(array(
                            'name'        => 'sitecontact', 
                            'type'       => 'text',
                            'label'      => 'Site Contact',
                            'value'      => set_value('sitecontact', isset($jobLeadData['sitecontact']) ? $jobLeadData['sitecontact'] : ($emptysitecontact==1 ? '' : (isset($cna1["firstname"]) ? $cna1["firstname"]: (isset($sitedetails["sitecontact"]) ? $sitedetails["sitecontact"]:'')))),
                            'other'      => $required1,
                            'placeholder' => 'Site Contact',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>$required,
                            'label2' =>'',
                            'options' =>array(),
                           ),
                            array('name'        => 'sitephone', 
                                'type'       => 'phone',
                                'label'      => 'Phone',
                                'value'      => set_value('sitephone', isset($jobLeadData['sitephone']) ? $jobLeadData['sitephone'] : ($ph1!="" ? $ph1: $ph)),
                                //'other'      =>'pattern="[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask=\'"mask": "99 9999 9999"\' data-mask ',
                                'placeholder' => '',//'xx xxxx xxxx'
                                'divclass'   =>'col-sm-3',
                                'labelclass' =>'col-sm-1 custom',
                                'fieldclass' =>'',
                                'label2' =>'',
                                'options' =>array(),
                               ));
            
                $fields[]=  array(array('name'        => 'siteemail', 
                            'type'       => 'email',
                            'label'      => 'Site Email *',
                            'value'      => set_value('siteemail', isset($jobLeadData['siteemail']) ? $jobLeadData['siteemail'] : ($emptysitecontact==1 ? '' : (isset($cna1["email"]) ? $cna1["email"]: (isset($sitedetails["siteemail"]) ? $sitedetails["siteemail"]:'')))),
                            'other'      =>'',
                            'placeholder' => 'Site Email',
                            'divclass'   =>'col-sm-6',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));  
                
                $fmcontactid = isset($cna["contactid"]) ? $cna["contactid"]: $this->session->userdata('raptor_contactid');
                if(count($jobLeadData)>0){
                    $fmcontactid = $this->customerclass->getFindContactId($this->session->userdata('raptor_customerid'), $jobLeadData['sitefmemail'], $jobLeadData['sitefm'], $jobLeadData['sitefmph']);
                }
                $fields[]=  array(array('name'        => 'contactid', 
                            'type'       => 'hidden',
                            'label'      => 'contactid',
                            'value'      => set_value('contactid', $fmcontactid),
                            'other'      =>'',
                            'placeholder' => '',
                            'divclass'   =>'',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
                $ph=isset($cna["mobile"]) && $cna["mobile"]!="" ? $cna["mobile"]: isset($cna["phone"]) ? $cna["phone"]:'';
		$fields[]=  array(array('name'        => 'sitefm', 
                            'type'       => 'text',
                            'label'      => 'Site FM',
                            'value'      => set_value('sitefm', isset($jobLeadData['sitefm']) ? $jobLeadData['sitefm'] : (isset($cna["firstname"]) ? $cna["firstname"] : '')),
                            'other'      =>'',
                            'placeholder' => 'Site FM',
                            'divclass'   =>'col-sm-4',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ),
                            array('name'        => 'sitefmph', 
                            'type'       => 'phone',
                            'label'      => 'FM Phone',
                            'value'      => set_value('sitefmph',isset($jobLeadData['sitefmph']) ? $jobLeadData['sitefmph'] : ($ph)),
                            //'other'      =>'pattern="[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask=\'"mask": "99 9999 9999"\' data-mask ',
                            'placeholder' => '',//'xx xxxx xxxx'
                            'divclass'   =>'col-sm-3',
                            'labelclass'   =>'col-sm-1 custom',
                            'fieldclass' =>'',
                            'label2' =>'',
                            'options' =>array(),
                           ));
               
		 
		 
		 $fields[]=  array(array('name'        => 'sitefmemail', 
                            'type'       => 'email',
                            'label'      => 'FM Email *',
                            'value'      => set_value('sitefmemail',isset($jobLeadData['sitefmemail']) ? $jobLeadData['sitefmemail'] : (isset($cna["email"]) ? $cna["email"]:'')),
                            'other'      =>'required="required"',
                            'placeholder' => 'FM Email',
                            'divclass'   =>'col-sm-6',
                            'fieldclass' =>'required',
                            'label2' =>'',
                            'options' =>array(),
                           ));  
                $rules[] = array(
                    'field'  => 'sitefmemail', 
                    'label'  => 'FM Email', 
                    'rules'  => 'required|trim|valid_email'
                );
                 
                $fields[]=  array(array('name'        => 'jobdescription', 
                            'type'       => 'textarea',
                            'label'      => 'Job Description * ',
                            'value'      => set_value('jobdescription',isset($jobLeadData['jobleaddescription']) ? $jobLeadData['jobleaddescription'] :''),
                            'other'      =>'required="required"',
                            'placeholder' => 'Job Description * ',
                            'divclass'   =>'col-sm-8',
                            'fieldclass' =>'required',
                            'label2' =>'',
                            'options' =>array(),
                           ));
		 
                $rules[] = array('field'  => 'jobdescription', 
                                'label'  => 'Job Description', 
                                'rules'  => 'required|trim') ; 
            
            $this->data['fields']=$fields;
            $this->data['rules'] =$rules;
            
              
            $this->data['cssToLoad'] = array(
                base_url('plugins/select2/select2.min.css'),
                base_url('plugins/datepicker/datepicker3.css'),
                base_url('plugins/timepicker/bootstrap-timepicker.min.css')
             );
            
            $this->data['jsToLoad'] = array(
                 base_url('plugins/input-mask/jquery.inputmask.js'),
                 base_url('plugins/bootstrap-ajax-typeahead/src/bootstrap-typeahead.js'),
                 base_url('plugins/jquery-validator/jquery.validate.min.js'),
                 base_url('plugins/jquery-validator/validation.js'),
                 base_url('plugins/datepicker/bootstrap-datepicker.js'),
                 base_url('plugins/timepicker/bootstrap-timepicker.min.js'),
                 base_url('plugins/select2/select2.full.min.js'),
                 base_url('assets/js/jobs/jobs.logjobquote.js')
                
            );
   
            $this->form_validation->set_rules($this->data['rules']);
            if($this->form_validation->run() == false)
	    {
                $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Log Job/Quote')
                            ->set_layout($this->layout)
                            ->set('page_title', 'Log Job/Quote')
                            ->set('page_sub_title', '')
                            ->set_breadcrumb('Log Job/Quote', '')
                            ->set_partial('page_header', 'shared/page_header')
                            ->set_partial('header', 'shared/header')
                            ->set_partial('navigation', 'shared/navigation')
                            ->set_partial('footer', 'shared/footer')
                            ->build('jobs/logjobquote', $this->data);
            }
            else{
                
                
                $insertdata = array(
                    'custordref'            => trim($this->input->post('custordref')),
                    'custordref2'           => trim($this->input->post('custordref2')),
                    'custordref3'           => trim($this->input->post('custordref3')),
                    'customerid'            => $this->session->userdata('raptor_customerid'), //trim($this->input->post('customerid')),
                    'labelid'               => trim($this->input->post('labelid')),
                    'siteline1'             => trim($this->input->post('siteline1')),
                    'siteline2'             => trim($this->input->post('siteline2')),
                    'sitesuburb'            => trim($this->input->post('sitesuburb')),
                    'sitestate'             => trim($this->input->post('sitestate')),
                    'sitepostcode'          => trim($this->input->post('sitepostcode')),
                    'territory'             => trim($this->input->post('territory')),
                    //'country'               => trim($this->input->post('country')),
                    'sitecontactid'         => trim($this->input->post('sitecontactid')),
                    'sitecontact'           => trim($this->input->post('sitecontact')),
                    'sitephone'             => trim($this->input->post('sitephone')),
                    'siteemail'             => trim($this->input->post('siteemail')),
                    'contactid'             => trim($this->input->post('contactid')),
                    'sitefm'                => trim($this->input->post('sitefm')),
                    'sitefmph'              => trim($this->input->post('sitefmph')),
                    'sitefmemail'           => trim($this->input->post('sitefmemail')),
                    'notexceed'             => trim($this->input->post('notexceed')),
                    'priority'              => trim($this->input->post('priority')), 
                    'jobdescription'        => trim($this->input->post('jobdescription')),
                    'quoterqd'              => trim($this->input->post('quoterqd')),
                    'responsedatelock'      => trim($this->input->post('responsedatelock')),
                    'custglchartid'         => (int)trim($this->input->post('custglchartid'))
                );
                if (isset($ContactRules["custordref1_from_customerpo"]) && $ContactRules["custordref1_from_customerpo"] == "1"){
                    if( trim($this->input->post('custordref')) == 'other'){
                        $insertdata["custordref"] = trim($this->input->post('custordrefother'));
                    }
                }
                $insertdata['custordrefLabel'] = isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1';
                $insertdata['custordrefLabel2'] = isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2';
                $insertdata['custordrefLabel3'] = isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3';
            
                
                //get the addresslabel
                $addresslabeldetails=$this->customerclass->getAddressById(trim($this->input->post('labelid')));
                
                $custpriorityrow = $this->customerclass->getCustomerPriorityDetail($customerid, trim($this->input->post('priority')));
                
				
                $days_offset = (int)$custpriorityrow["days_offset"];
            
                $insertdata["priority"] = $custpriorityrow["display_description"];

                $leaddate =date('Y-m-d',time());
                $insertdata["leaddate"] =date('Y-m-d',time());// $leaddate->format('Y-m-d');
                $duedate = date('Y-m-d',time());
		 
                $day = date('l', Time());
                $weekend = 0;
                if($day == 'Friday'){
                    $weekend = 2;
                }
                if($day == 'Saturday'){
                    $weekend = 1;
                }
                
                
                $isscheduled = false;
                if ($custpriorityrow["display_description"] == "P5 - Scheduled"){
                    $isscheduled = true;
                    if(trim($this->input->post('attenddate')) != "")
                    {
                        $duedate =to_mysql_date(trim($this->input->post('attenddate')), RAPTOR_DISPLAY_DATEFORMAT) ;
                    }
                    else{
                        $duedate = date('Y-m-d', strtotime("+".($days_offset+$weekend)." day", time()));
                    }
                    $duetime =trim($this->input->post('attendtime'));
                     
                } else {
                    
                    $duedate = date('Y-m-d', strtotime("+".($days_offset+$weekend)." day", time()));
                    $duetime = date('H:i:s', strtotime("+". (int)$custpriorityrow["response_buffer_hours"]." hours", time()));
                }
 
               

                $mustattend = $insertdata["responsedatelock"]; 
                if($insertdata["responsedatelock"] == 'false'){
                    $insertdata["responsedatelock"] = '';
                }
                if ($mustattend == "true" || $isscheduled){
                    if($mustattend == "true")
                    {
                        $insertdata["responsedatelock"] = "on";	
                    }
                } 
                
                $day = date('l', strtotime($duedate));
                if($day == 'Saturday'){
                    $duedate = date('Y-m-d', strtotime("+2 day", strtotime($duedate)));
 
                }
                if($day == 'Sunday'){
                    $duedate = date('Y-m-d', strtotime("+1 day", strtotime($duedate)));
                }
                
                $insertdata["duedate"] = $duedate;
                $insertdata["duetime"] = $duetime;
                $insertdata["responseduedate"] = $duedate;
                $insertdata['responseduetime'] = $duetime;
                
                $internalduedate =  strtotime("-".$custpriorityrow["buffer_days"]." day", time());
                if($internalduedate < time()){
                    $internalduedate = time();
                }
                
                $insertdata["jrespintdate"] = date('Y-m-d',$internalduedate);
                $insertdata["internalduedate"] = date('Y-m-d',$internalduedate);
                
                $insertdata["jdaysbuffer"] = $custpriorityrow["buffer_days"];
                $insertdata["jrespbuffer"] = $custpriorityrow["response_buffer_hours"];
 	
                $workorder_increment_val=isset($addresslabeldetails['workorder_increment_val'])? $addresslabeldetails['workorder_increment_val'] : 0;
                $workorder_increment_val++;
                // auto write custordref2 is set in customer_rule table
                if (isset($ContactRules["auto_create_custordref2_in_client_portal"]) && $ContactRules["auto_create_custordref2_in_client_portal"] == "1"){
                    
                    $insertdata["custordref2"] = (isset($addresslabeldetails['siteref'])? $addresslabeldetails['siteref'] : '') . "_". $workorder_increment_val;
                }

                //auto write notexceed if set in customer_rule table
                if ((isset($ContactRules["auto_create_custlimit_in_client_portal"]) && $ContactRules["auto_create_custlimit_in_client_portal"] == "1")){		
                    $insertdata["notexceed"] = $customerData['supplier_threshold'];
                }
		 
		if (!is_numeric($insertdata["notexceed"])) {
                    $insertdata['notexceed'] = (float)$this->sharedclass->getSysValue('default_notexceed');
		}
                //Add standard job text
                if ((isset($ContactRules["add_custom_job_description"]) && $ContactRules["add_custom_job_description"] != "")){	
                    $insertdata['jobdescription'] = $insertdata['jobdescription']."\n \n".$ContactRules["add_custom_job_description"];
                }
		 
		//Add Key Date Field
                if ((isset($ContactRules["keydatefield"]) && $ContactRules["keydatefield"] != "")){	
                    $insertdata['keydate'] = $ContactRules["keydatefield"];
                }
		  
                if($this->session->userdata('raptor_role') == 'site contact') {
                    $insertdata["jobstage"] = 'portal_await_approval';
                }
                // if a user is a site contact & customer has a show_portal_job_approval_tab tag ON, assign jobstage as 'portal_await_approval'
                if($this->session->userdata('raptor_role') == 'site contact' && (isset($ContactRules["show_job_approval_tab_in_client_portal"]) && $ContactRules["show_job_approval_tab_in_client_portal"] == "1"))
                {   
                    $insertdata["jobstage"] = 'portal_await_approval';
                
		}
                else {
                    $insertdata["jobstage"]="portal";
                }
               
                
                if (isset($insertdata["quoterqd"]) && $insertdata["quoterqd"] =='on'){
                
                    $insertdata["quotestatus"] = "pending_submission";
                    
                    $qduedate = date('Y-m-d',strtotime($leaddate) + (24*3600*$days_offset)); 
                    $insertdata["qduedate"] = $qduedate;
                    $insertdata["qduetime"] = "17:00";
                    $insertdata["qdrbuffer"] = "0";
                    $insertdata["qduebuffer"] = $days_offset;
                    $insertdata["qrespintdate"] = $leaddate;
                    $insertdata["qrespduedate"] = $leaddate;
                    $insertdata["qdueintdate"] = $leaddate;
                    $insertdata["qrespduetime"] = "17:00";
		 
                }
              
               
                if ((isset($ContactRules["hide_job_priority_in_client_portal"]) && $ContactRules["hide_job_priority_in_client_portal"] == "1")){
                    $insertdata["priority"] = "TBA";
                    $newdate = date('Y-m-d', strtotime("+5 day", time()));
                    $insertdata["responseduedate"] = $newdate;
                    $insertdata["duedate"] =  $newdate;
                    $insertdata["duetime"] = "17:00";
            	}
                
                #No photo rules
                $insertdata['nophoto_check'] = isset($addresslabeldetails['photos_not_reqd'])? $addresslabeldetails['photos_not_reqd'] : 0;
                
                $insertdata["territory"] = $this->sharedclass->getLowestTerritory($insertdata["sitesuburb"], $insertdata["sitestate"], $insertdata["sitepostcode"]);
                
                $insertdata["userid"] = $this->session->userdata('raptor_email');
                $insertdata["origin"] = "CP";
                $insertdata["parentid"] = 0;
                $insertdata["dcfmbufferv"] = 250;
                if(trim($this->input->post('quoterqd')) == 'on'){
                    $insertdata["notexceed"] = 0;
                    $insertdata["dcfmbufferv"] = 0;
                }
                $insertdata["internalqlimit"] = $insertdata["notexceed"] - $insertdata["dcfmbufferv"];
                if ((isset($ContactRules["direct_allocate"]) && $ContactRules["direct_allocate"] == "1")){
                    $insertdata["jobstage"] = 'portal_await_approval';
                }
                
                
                $request = array(
                    'jobData'           => $insertdata, 
                    'logged_contactid'  => $this->data['loggeduser']->contactid
                );
                $response = $this->jobclass->createJob($request);
                $jobid= $response['jobid'];
                
                
                //update incremented workorder_increment_val in addresslabel table
                // auto write custordref2 is set in customer_rule table
                if (isset($ContactRules["auto_create_custordref2_in_client_portal"]) && $ContactRules["auto_create_custordref2_in_client_portal"] == "1"){
                    
                    //update incremented workorder_increment_val in addresslabel table
                    $request = array(
                        'updateData'       => array('workorder_increment_val'=>$workorder_increment_val),
                        'labelid'          => $insertdata["labelid"],  
                        'logged_contactid' => $this->data['loggeduser']->contactid
                    );
                    $this->customerclass->updateAddress($request);
                
                }
                if($this->input->get('tempjobid')){
                    $this->load->library('asset/AssetClass');
                    
                    $this->assetclass->updateAssetServiceJobId($jobid, $this->input->get('tempjobid'));   
                }
                
                if($this->input->get('jobleadid') && count($jobLeadData)>0){
                    
                    $updateData = array(
                        'approvaldate'  => date('Y-m-d H:i:s'),
                        'approvedby'    => $this->data['loggeduser']->contactid,
                        'jobidcreated'  => $jobid
                    );
                   $request = array(
                        'joblead_id'        => $this->input->get('jobleadid'),
                        'updateData'        => $updateData,
                        'logged_contactid'  => $this->data['loggeduser']->contactid
                    );
                    $this->jobclass->updateLeadJob($request);   
                    
                }
                
                //send notification
                $email = $custpriorityrow['emailnotification'];
                $smsphone = $custpriorityrow['smsnotification'];
                $jobData = $this->jobclass->getJobById($jobid);
                
                
                if (isset($ContactRules["custordref1_from_customerpo"]) && $ContactRules["custordref1_from_customerpo"] == "1"){
                    if( trim($this->input->post('custordref')) != 'other'){
                        $insertCustomerPOJobData = array();
                        $ponumber = trim($this->input->post('custordref'));
                        $amount = $insertdata["notexceed"];
                        if(trim($this->input->post('quoterqd')) == 'on'){
                            $amount = 0;
                        }
                        
                        $insertCustomerPOJobData[] = array(
                            'customerid'    => $customerid,
                            'jobid'    => $jobid,
                            'ponumber'    => $ponumber
                        );
                   
                        $request = array(
                            'insertCustomerPOJobData'        => $insertCustomerPOJobData,
                            'logged_contactid'      => $this->data['loggeduser']->contactid
                        );

                        $this->purchaseorderclass->insertCustomerPurchaseOrderJob($request);
                
                        $poData= $this->purchaseorderclass->getCustomerPOByPONumber($ponumber);

                        $contactData = array(
                            'amount_used' => $poData['amount_used'] + $amount
                        );

                        $request = array(
                            'customer_po_id'        => $poData['id'],
                            'updateCustomerPOData'  => $contactData,
                            'logged_contactid'      => $this->data['loggeduser']->contactid
                        );

                        $this->purchaseorderclass->updateCustomerPurchaseOrder($request);
                
                    }
                }

                if($email != '') {
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    
                    $subject = 'Urgent Client Portal Job  '.$jobData['jobid'].': '.$jobData['companyname'].' ('.$jobData['sitesuburb'].' '.$jobData['sitestate'].') <br/><br/>';
                    $message = '<b>Job ID:</b> &nbsp;&nbsp;'.$jobData['jobid'].' <br />';
                    $message .= '<b>Customer:</b> &nbsp;&nbsp;'.$jobData['companyname'].'  <br />';
                    $message .= '<b>Address:</b> &nbsp;&nbsp;'.$jobData['siteline2'].'   <br />';
                    $message .= '<b>Suburb:</b> &nbsp;&nbsp;'.$jobData['sitesuburb'].' '.$jobData['sitestate'].'   <br />';
                    $message .= '<b>Priority:</b> &nbsp;&nbsp;'.$jobData['priority'].'   <br />';
                    $message .= '<b>Attend By:</b> '.format_date($jobData['responseduedate'], RAPTOR_DISPLAY_DATEFORMAT).' '.format_time($jobData['responseduetime'], RAPTOR_DISPLAY_TIMEFORMAT).' <br />';
                    $message .= '<b>Site FM:</b> &nbsp;&nbsp;'.$jobData['sitefm'].' '.$jobData['sitefmph'].'   <br />';
                    $message .= '<b>Site Contact:</b> &nbsp;&nbsp;'.$jobData['sitecontact'].' '.$jobData['sitephone'].'   <br />';
                    $message .= '<b>Description:</b> &nbsp;&nbsp;'.$jobData['jobdescription'].'';
                    @mail($email, $subject, $message, $headers);
                }
                
                if($smsphone != '') {
                    $text = 'Urgent job '.$jobData['jobid'].' logged via Client Portal by '.$jobData['companyname'].' in '.$jobData['sitesuburb'].' '.$jobData['sitestate'];
                    $source = 'Client Portal';
                    $ref = 'Raptor';

                    $this->load->library('SmsClass');
                    $response = $this->smsclass->sendSMS($smsphone, $source, $text, $ref);
                    
                }
                include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/con_jobrule.class.php");
                $jobrule = new con_jobrule(AppType::JobTracker,$this->session->userdata('raptor_email'));
                $jobrule->executeContractRules($jobid);
                redirect('jobs/jobdetail/'.$jobid);
        }
          
    }
    
    /**
    * This function use for site search for selected customerid
    * 
    * @return json 
    */
    public function loadSiteSearch() {
        
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $query = trim($this->input->get('search'));
            $custBasedA = array(7045,7089);

            $customerid =$this->session->userdata('raptor_customerid');
            $contactemail =$this->session->userdata('raptor_email');
            if($this->session->userdata('raptor_role') == 'master')
            {
                $contactemail="";
            }
            if(in_array($customerid, $custBasedA)) $contactemail="";
                
            $log_allsites = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'LOG_ALL_SITES');
            if($log_allsites) {
                $contactemail = '';
            }
            
            if( !isset($customerid) || $customerid == '' || $customerid == NULL)
                $message = 'customerid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                 
                $data = $this->customerclass->getCustomerSiteAddress($customerid, $query, $contactemail, FALSE, '', TRUE);
                
                //format data
                foreach ($data as $key => $value) {
                    //$data[$key]['site'] = $this->customerclass->getFormattedSiteAddress($value['labelid']);
                    $data[$key]['siteaddress'] = str_replace('<br>', ', ', $data[$key]['address']); 
                }
                 
                $success->setData($data);
            }
        }
        catch( Exception $e )
        {
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));

    }
    
    /**
     * load sitelookup
     * 
     * @param string $q
     */  
    public function loadSiteLookup() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $custBasedA = array(7045,7089);

            $customerid =$this->session->userdata('raptor_customerid');
            $contactemail =$this->session->userdata('raptor_email');
            if($this->session->userdata('raptor_role') == 'master')
            {
                $contactemail = '';
            }
            if(in_array($customerid, $custBasedA)) {
                $contactemail = '';
            }
            
            $log_allsites = $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'LOG_ALL_SITES');
            if($log_allsites) {
                $contactemail = '';
            }
           
                
            if( !isset($customerid) || $customerid == '' || $customerid == NULL)
                $message = 'customerid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $ContactRules = $this->data['ContactRules'];
                $show_custom_attributes = isset($ContactRules["show_custom_attributes_in_address_list"]) ? $ContactRules["show_custom_attributes_in_address_list"]:0;
                
                $sql_w="";
                if(isset($ContactRules['get_fm_from_site_in_client_portal']) && $ContactRules['get_fm_from_site_in_client_portal']){

                    $fmemail=$this->session->userdata('raptor_email');

                     $subordinate_emails = $this->customerclass->getSubordinateEmails($fmemail);
//                    if($show_custom_attributes)
//                    {
//                        $sql_w =  " and FIND_IN_SET(d.email, '".$this->db->escape_str($subordinate_emails)."') or d.email='".$this->db->escape_str($fmemail)."' ";
//                    }
//                    else{
//                        $sql_w =  " and FIND_IN_SET(con.email, '".$this->db->escape_str($subordinate_emails)."') or con.email='".$this->db->escape_str($fmemail)."'";
//                    }
                    $sql_w =  " and FIND_IN_SET(con.email, '".$this->db->escape_str($subordinate_emails)."') or con.email='".$this->db->escape_str($fmemail)."'";

                }
                 
                $data = $this->customerclass->getCustomerSiteAddress($customerid, '', $contactemail, $show_custom_attributes, $sql_w, TRUE);
                
                //format data
                foreach ($data as $key => $value) {
                    //$data[$key]['site'] = $this->customerclass->getFormattedSiteAddress($value['labelid']);
                    $data[$key]['siteaddress'] = str_replace('<br>', ', ', $data[$key]['address']); 
                }
                 
                $success->setData($data);
            }
        }
        catch( Exception $e )
        {
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
                

    }
  public function loadAllocate() {

        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        { 
            //get request
            $customerid =$this->session->userdata('raptor_customerid');
                
            if( !isset($customerid) || $customerid == '' || $customerid == NULL)
                $message = 'customerid cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);
           

            if( $isSuccess )
            {
                $ContactRules = $this->data['ContactRules'];
                $show_custom_allocation = isset($ContactRules["show_custom_allocation"]) ? $ContactRules["show_custom_allocation"]:0;
                
                if($show_custom_allocation){
                    $data = $this->customerclass->getCustomerSiteAddress($customerid);
                
                    //format data
                    foreach ($data as $key => $value) {
                    //$data[$key]['site'] = $this->customerclass->getSupplierAllocations($customerid);
                    //$data[$key]['siteaddress'] = str_replace('<br>', ', ', $data[$key]['site']); 
                    }

                    $success->setData($data);
                }
            }
        }
        catch( Exception $e )
        {
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
  
        //convert result data to json and set output
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($success));
 
    }
}