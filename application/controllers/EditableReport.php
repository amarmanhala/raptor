<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Allows editing of certain reports (e.g. property inspections) inside Raptor job details.
 *
 * @author   Alison Morris <itglobal3@dcfm.com.au>
 *
 * @category Controllers
 *
 * @license  N/A
 *
 * @link
 */
class EditableReport extends MY_Controller
{
    public function __construct()
    {
        $this->jobdocs_dir = 'D:\Apache Software Foundation\htdocs\infomaniacDocs\jobdocs\\';
        $this->tablenames = array(
            0 => 'se_master',
            1 => 'se_trade',
            2 => 'se_trade_works',
            3 => 'se_works',
            4 => 'se_works_subworks',
            5 => 'se_subworks',
            6 => 'se_master_keyword',
            7 => 'se_master_swms',
            80 => 'se_customer_trade',
            90 => 'se_customer_works',
            100 => 'se_customer_subworks',
            110 => 'se_wording',
            120 => 'se_master_document',
            200 => 'gmap_route',
            300 => 'example',
            400 => 'ins_scope',
            500 => 'document',
        
            600 => 'rep_reporttype',
            610 => 'rep_area_type',
            620 => 'rep_area_topic',
            630 => 'rep_area',
            640 => 'rep_areatype_areatopic',
            650 => 'rep_report_areatypetopic_value',
            660 => 'rep_area_value',
            670 => 'rep_asset',
            680 => 'se_billing_region',
            690 => 'se_billing_region_territory',
            700 => 'se_billingrates',
            710 => 'se_billing_region_territory1',
            720 => 'htmldoc_type',
            730 => 'licencetype',
            740 => 'insurancetype',
            750 => 'htmldoc_template',
            760 => 'compliancedocs',
            770 => 'asset_doctype',
            780 => 'asset_category',
        );
        
        $this->fieldnames = array(
            0 => array(0 => 'se_trade_id', 1 => 'se_works_id', 2 => 'se_subworks_id', 3 => 'se_quote_stdwords', 4 => 'se_quote_costs_id', 5 => 'se_billing_stdwords', 6 => 'se_billing_pricing', 7 => 'se_keywords', 8 => 'se_glcode_id', 9 => 'se_riskcategory_id', 10 => 'se_swms', 11 => 'se_msds', 12 => 'se_permit_id', 13 => 'se_itp_id', 100 => 'enabled'),
            200 => array(0 => 'name', 1 => 'directions_object'),
            300 => array(0 => 'name', 1 => 'field_two', 3 => 'userid'),
            400 => array(0=>'scope_description', 1=>'scope_approximate_size', 2=>'scope_labour_hours', 3=>'scope_area_name'),
            500 => array(0 => 'documentdesc', 1 => 'docnote'),
            600 => array(0 => 'name', 1 => 'reportFolder', 2 => 'filespec', 3 => 'sortorder'),
            610 => array(0 => 'name'),
            620 => array(0 => 'name'),
            630 => array(0 => 'report_type_id', 10 => 'area_type_id'),
            640 => array(0=>'areatype_id', 1=>'areatopic_id', 2=>'show_yn', 3=>'show_cdwb', 4=>'show_rating', 5=>'minrating', 6=>'maxrating', 7=>'topic_value_id', 8=>'show_scope', 9=>'show_photo_link', 10=>'show_asset_link', 11=>'sortorder'),
            650 => array(0=>'dirty', 1=> 'dirty_rating', 2=> 'damaged', 3=> 'damaged_rating', 4=> 'description', 5=> 'remedial_action', 6=> 'ynna', 7=> 'value', 8=> 'height', 9=> 'width', 10=> 'length', 11=> 'labour_hours', 12=> 'labour_cost', 13=> 'materials', 14=> 'material_cost', 15=> 'is_insurable'),
            660 => array(0 => 'area_description'),
            670 => array(0=>'category_id', 1=>'description', 2=>'asset_criticality', 3=>'desired_condition', 4=>'actual_condition', 5=>'remaining_life_expectancy',),
            680 => array(0 => 'name', 1 => 'isactive'),
            685 => array(0 => 'region_id', 1 => 'parent_territory'),
            690 => array(0 => 'region_id', 1 => 'name', 2 => 'base_rate', 3 => 'callout_rate', 4=> 'ot_uplift_rate', 5 => 'jobcount_band1_factor', 6 => 'jobcount_band2_factor', 7 => 'jobcount_band3_factor', 8 => 'jobcount_band4_factor', 9 => '15day_terms_uplift_rate', 10 => '30day_terms_uplift_rate', 11 => '60day_terms_uplift_rate', 12 => '90day_terms_uplift_rate', 13 => '15day_ft_uplift_rate', 14 => '30day_ft_uplift_rate', 15 => '60day_ft_uplift_rate', 16 => '90day_ft_uplift_rate', 17 => 'trade_id'),
            695 => array(0 => 'region_id', 1 => 'parent_territory'),
            700 => array(0 => 'name'),
            701 => array(0 => 'name', 1 => 'contractid'),
            702 => array(0 => 'name', 1 => 'contract_program_id'),
            703 => array(0 => 'name', 1 => 'contract_service_id'),
            704 => array(0 => 'name', 1 => 'labelid', 2 => 'contract_service_standard_id', 3 => 'summer_hours', 4 => 'winter_hours', 5 => 'budgeted_travel', 6 => 'price'),
        );
        log_message("debug", "In Editable Report");
        parent::__construct();
        log_message("debug", "In Editable Report");
        //  Load libraries
        $this->load->library('job/JobClass');
        $this->load->library('schedule/ScheduleClass');
        $this->load->library('document/DocumentClass');
        $this->load->library('purchaseorder/PurchaseOrderClass');
        $this->load->library('editablereport/EditableReportClass', null, "editRep");
        log_message("debug", "In Editable Report");
    }


    public function index($job_id = null, $report_id = null, $areavalue_guid = null, $readonly = null)
    {
        $this->data['cssToLoad'] = array(
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/uigrid/ui-grid-stable.min.css'),
            base_url('plugins/bootstrap-editable/css/bootstrap-editable.css'),
            base_url('plugins/jQuery-File-Upload/css/jquery.fileupload.css')
         );

        $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('plugins/bootstrap-editable/js/bootstrap-editable.js'),
            base_url('plugins/jQuery-File-Upload/js/vendor/jquery.ui.widget.js'), 
            base_url('plugins/jQuery-File-Upload/js/jquery.iframe-transport.js.js'),
            base_url('plugins/jQuery-File-Upload/js/jquery.fileupload.js'),
            base_url('plugins/jQuery-File-Upload/js/jquery.fileupload-audio.js'),
            base_url('plugins/jQuery-File-Upload/js/jquery.fileupload-image.js'),
            base_url('plugins/jQuery-File-Upload/js/jquery.fileupload-process.js'),
            base_url('plugins/jQuery-File-Upload/js/jquery.fileupload-validate.js'),
            base_url('plugins/jQuery-File-Upload/js/jquery.fileupload-video.js'),
            base_url('assets/js/editablereport/init_x-editable.js'),
            base_url('assets/js/editablereport/editablereport.index.js')                    
        );
        
        $customerid = $this->session->userdata('raptor_customerid');
        
        $this->data['job_id']  = !empty($job_id) ? $job_id : 0;

        $this->data['job_id_readonly'] = FALSE;
        $this->data['report_id_readonly'] = FALSE;
        if($readonly == 1) {
            if(trim($this->data['job_id']) == '' || trim($this->data['job_id'] == 0)) {
                $this->data['job_id_readonly'] = FALSE;
            } else {
                $this->data['job_id_readonly'] = TRUE;
            }

            $this->data['report_id'] = $report_id;
            if(trim($this->data['report_id']) == '' || trim($this->data['report_id'] == 0)) {
                $this->data['report_id_readonly'] = FALSE;
            } else {
                $this->data['report_id_readonly'] = TRUE;
            }
        }

        //$this->data['jobids']  = $this->editRep->get_jobid_list();
        if($this->data['job_id_readonly']) {
            $this->data['jobids']  = $this->documentclass->getJobs($customerid, $this->data['job_id']);
        } else {
            $this->data['jobids']  = $this->documentclass->getJobs($customerid);
        }
        if($this->data['job_id'] == 0 || $this->data['job_id'] == NULL){
            $this->data['reports'] = array();
        }
        else{
            $this->data['reports'] = $this->editRep->get_report_list($this->data['job_id']);
        }
        

        $this->data['shareUrl'] = ''; 
        // || $report_id == '' || $report_id == ''
        if($job_id == '' || $job_id == 0 || $job_id == NULL) {
            
        } else {
            $report = $this->editRep->get_report($job_id, $this->data['reports'][0]['id']);
            $encrypt_documentid = encrypt_decrypt('encrypt', $report['documentid']);
            //http://localhost/raptor/document/downloadreport/177685
            $this->data['shareUrl'] = 'Report download link: '.site_url('document/downloadreport/'.$encrypt_documentid); 
        }

        // If specific report not selected show first report
        $this->data['report_id'] = !empty($report_id) ? $report_id : (count($this->data['reports'])>0 ?$this->data['reports'][0]['id'] : 0);

        $this->data['areas'] = $this->editRep->get_area_list($this->data['report_id']);

        // If specific area value not selected show the first area instead.
        $this->data['areavalue_guid'] = empty($areavalue_guid) ? $this->data['areas'][0]['guid'] : $areavalue_guid;

        $this->data['title'] = 'Admin - Edit Report';

        // url for find, reset & export button
        $this->data['filter']['url'] = 'admin/areatypeareatopic/';
        $this->data['filter']['exportfilename'] = 'ExportedFileName/';

        $this->editRep->get_report_area($this->data);
        $this->editRep->get_report_area_topic($this->data);

        $this->editRep->writeXEditableValues($this->data);

        $this->template->title(trim(RAPTOR_APP_TITLE.' '.RAPTOR_APP_SUBTITLE).' | Edit Job Report')
            ->set_layout($this->layout)
            ->set('page_title', 'Job ' . $job_id. ' - Edit Report')
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Jobs', site_url('jobs'))
            ->set_breadcrumb('Job Detail', '')
            ->set_breadcrumb('Edit Report', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('editablereport/editreportindex', $this->data);

        $this->logClass->log(print_r($this->data, 1));
    }

    public function uploadfile($upload_file, $job_id, $report_id, $area_value_guid)
    {
        set_time_limit(0);
        $sMessage = '';
        $bResult = true;

        $file_to_read = 'server\\php\\files\\'.html_entity_decode(urldecode($upload_file));
        $path_parts = pathinfo($file_to_read);

        $data = array('job_id' => $job_id, 'report_id' => $report_id, 'area_value_guid' => $area_value_guid, 'extension' => $path_parts['extension']);
        $documentid = $this->editRep->add_photo($data);
        $sMessage = "new photo documentid=$documentid";

            //echo "$sMessage<P>";
            if (!copy(str_replace('/', '\\', $_SERVER['DOCUMENT_ROOT']).'\itglobal\shared\\'.$file_to_read, $this->jobdocs_dir.$data['documentid'].'.'.$data['extension'])) {
                //echo "failed to copy file...\n";
                $bResult = false;
                $sMessage = 'Failed to copy file to report';
            }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('result' => $bResult, 'message' => $sMessage)));
    }

    public function delete($id)
    {
        $this->editRep->updateRecords(array('id' => $id), array('isdeleted' => 1), 'rep_report_areatypetopic_value');

        $bResult = true;
        $sMessage = '';
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('result' => $bResult, 'message' => $sMessage)));
    }

    public function editReport()
    {
        //Load Job Detail Data
        $customerid = $this->session->userdata('raptor_customerid');
        $data = $this->jobclass->getJobById($jobid);
        if (count($data) == 0) {
            show_404();
        }
        if ($data['customerid'] != $customerid) {
            show_404();
        }

        $this->data['cssToLoad'] = array(
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/uigrid/ui-grid-stable.min.css'),
         );

        $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/jobs/jobs.jobdetail.js'),
            base_url('assets/js/jobs/jobs.jobnote.js'),
            base_url('assets/js/jobs/jobs.jobdocuments.js'),
            base_url('assets/js/jobs/jobs.jobtask.js'),
            base_url('assets/js/jobs/jobs.jobreport.js'),
            base_url('assets/js/emaildialog.js'),
        );

        $contactid = $this->session->userdata('raptor_contactid');

        $ContactRules = $this->data['ContactRules'];
        $this->data['jobDocFolder'] = $this->documentclass->getDocFolder('doc');
        $this->data['jobImagesFolder'] = $this->documentclass->getDocFolder('images');
        $this->data['documentImages'] = $this->documentclass->documentImages($jobid, $contactid);
        $this->data['addtask_security'] = 1;
        $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_TASK');

        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
        $this->data['suppliers'] = $this->customerclass->getCustomerSuppliers($this->data['loggeduser']->customerid);

        $allocatedto = array();
        $defaultTaskAllocateUser = $this->sharedclass->getSettingValue('cp_default_task_allocate_user');
        if (trim($defaultTaskAllocateUser) != '') {
            array_push($allocatedto, $defaultTaskAllocateUser);
        }
        array_push($allocatedto, $this->session->userdata('raptor_email'));
        $recipients = isset($this->data['ContactRules']['cp_task_allocation']) ? trim($this->data['ContactRules']['cp_task_allocation']) : '';
        if ($recipients != '') {
            $allocatedto = array_merge($allocatedto, explode(';', $recipients));
        }

        $this->data['taskallocatedto'] = $allocatedto;

        $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($customerid, 'E');

        //Job Data formating for display
        if (count($data) > 0) {
            $location = array();
            if (trim($data['siteline2']) != '') {
                array_push($location, $data['siteline2']);
            }
            if (trim($data['sitesuburb']) != '') {
                array_push($location, $data['sitesuburb']);
            }
            if (trim($data['sitestate']) != '') {
                array_push($location, $data['sitestate']);
            }
            /*if (trim($data['sitepostcode']) != '') {
                array_push($location, $data['sitepostcode']);
            }*/

            $data['location'] = implode(', ', $location);

            $data['sitecontact'] = '';
            if ($data['sitecontact'] != '') {
                $data['sitecontact'] = $data['sitecontact'];
                if ($data['sitephone'] != '') {
                    $data['sitecontact'] = $data['sitecontact'].' - ('.$data['sitephone'].')';
                }
            }

            $data['notexceed'] = format_amount($data['notexceed']);
            $data['pdate'] = format_datetime($data['pdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['duedate'] = format_date($data['duedate'], RAPTOR_DISPLAY_DATEFORMAT);
            $data['duetime'] = format_time($data['duetime'], RAPTOR_DISPLAY_TIMEFORMAT);
            $data['jcompletedate'] = format_datetime($data['jcompletedate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);

            $data['vapprovaldate'] = format_datetime($data['vapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['qdateaccepted'] = format_datetime($data['qdateaccepted'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['jobapprovaldate'] = format_datetime($data['jobapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);

            $data['custordref1_label'] = isset($ContactRules['custordref1_label']) ? $ContactRules['custordref1_label'] : 'Order Ref 1';
            $data['custordref2_label'] = isset($ContactRules['custordref2_label']) ? $ContactRules['custordref2_label'] : 'Order Ref 2';
            $data['custordref3_label'] = isset($ContactRules['custordref3_label']) ? $ContactRules['custordref3_label'] : 'Order Ref 3';

            $data['custordref3_access'] = isset($ContactRules['hide_custordref3_in_client_portal']) ? $ContactRules['hide_custordref3_in_client_portal'] : 0;
        }

        $this->data['job'] = $data;
        $this->data['poData'] = $this->purchaseorderclass->getPurchaseOrderByJobid($jobid);
        $this->template->title(trim(RAPTOR_APP_TITLE.' '.RAPTOR_APP_SUBTITLE).' | Job Detail')
            ->set_layout($this->layout)
            ->set('page_title', 'Job Detail : JOB No '.$jobid)
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Jobs', site_url('jobs'))
            ->set_breadcrumb('Job Detail', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('jobs/jobdetail', $this->data);
    }

    public function jobdetail()
    {
        //Load Job Detail Data
        $customerid = $this->session->userdata('raptor_customerid');
        $data = $this->jobclass->getJobById($jobid);
        if (count($data) == 0) {
            show_404();
        }
        if ($data['customerid'] != $customerid) {
            show_404();
        }

        $this->data['cssToLoad'] = array(
            base_url('plugins/datepicker/datepicker3.css'),
            base_url('plugins/bootstrap-select/css/bootstrap-select.min.css'),
            base_url('plugins/uigrid/ui-grid-stable.min.css'),
         );

        $this->data['jsToLoad'] = array(
            base_url('plugins/datepicker/bootstrap-datepicker.js'),
            base_url('plugins/bootstrap-select/js/bootstrap-select.min.js'),
            base_url('plugins/jquery-validator/jquery.validate.min.js'),
            base_url('assets/js/jquery.form.js'),
            base_url('plugins/uigrid/angular.min.js'),
            base_url('plugins/uigrid/ui-grid-stable.min.js'),
            base_url('assets/js/jobs/jobs.jobdetail.js'),
            base_url('assets/js/jobs/jobs.jobnote.js'),
            base_url('assets/js/jobs/jobs.jobdocuments.js'),
            base_url('assets/js/jobs/jobs.jobtask.js'),
            base_url('assets/js/jobs/jobs.jobreport.js'),
            base_url('assets/js/emaildialog.js'),
        );

        $contactid = $this->session->userdata('raptor_contactid');

        $ContactRules = $this->data['ContactRules'];
        $this->data['jobDocFolder'] = $this->documentclass->getDocFolder('doc');
        $this->data['jobImagesFolder'] = $this->documentclass->getDocFolder('images');
        $this->data['documentImages'] = $this->documentclass->documentImages($jobid, $contactid);
        $this->data['addtask_security'] = 1;
        $this->sharedclass->getFunctionalSecurityAccess($this->session->userdata('raptor_contactid'), 'ADD_TASK');

        $this->data['technicians'] = $this->scheduleclass->getTechnicians($this->data['loggeduser']->customerid);
        $this->data['suppliers'] = $this->customerclass->getCustomerSuppliers($this->data['loggeduser']->customerid);

        $allocatedto = array();
        $defaultTaskAllocateUser = $this->sharedclass->getSettingValue('cp_default_task_allocate_user');
        if (trim($defaultTaskAllocateUser) != '') {
            array_push($allocatedto, $defaultTaskAllocateUser);
        }
        array_push($allocatedto, $this->session->userdata('raptor_email'));
        $recipients = isset($this->data['ContactRules']['cp_task_allocation']) ? trim($this->data['ContactRules']['cp_task_allocation']) : '';
        if ($recipients != '') {
            $allocatedto = array_merge($allocatedto, explode(';', $recipients));
        }

        $this->data['taskallocatedto'] = $allocatedto;

        $this->data['glcodes'] = $this->customerclass->getCustomerGLChart($customerid, 'E');

        //Job Data formating for display
        if (count($data) > 0) {
            $location = array();
            if (trim($data['siteline2']) != '') {
                array_push($location, $data['siteline2']);
            }
            if (trim($data['sitesuburb']) != '') {
                array_push($location, $data['sitesuburb']);
            }
            if (trim($data['sitestate']) != '') {
                array_push($location, $data['sitestate']);
            }
            /*if (trim($data['sitepostcode']) != '') {
                array_push($location, $data['sitepostcode']);
            }*/

            $data['location'] = implode(', ', $location);

            $data['sitecontact'] = '';
            if ($data['sitecontact'] != '') {
                $data['sitecontact'] = $data['sitecontact'];
                if ($data['sitephone'] != '') {
                    $data['sitecontact'] = $data['sitecontact'].' - ('.$data['sitephone'].')';
                }
            }

            $data['notexceed'] = format_amount($data['notexceed']);
            $data['pdate'] = format_datetime($data['pdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['duedate'] = format_date($data['duedate'], RAPTOR_DISPLAY_DATEFORMAT);
            $data['duetime'] = format_time($data['duetime'], RAPTOR_DISPLAY_TIMEFORMAT);
            $data['jcompletedate'] = format_datetime($data['jcompletedate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);

            $data['vapprovaldate'] = format_datetime($data['vapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['qdateaccepted'] = format_datetime($data['qdateaccepted'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $data['jobapprovaldate'] = format_datetime($data['jobapprovaldate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);

            $data['custordref1_label'] = isset($ContactRules['custordref1_label']) ? $ContactRules['custordref1_label'] : 'Order Ref 1';
            $data['custordref2_label'] = isset($ContactRules['custordref2_label']) ? $ContactRules['custordref2_label'] : 'Order Ref 2';
            $data['custordref3_label'] = isset($ContactRules['custordref3_label']) ? $ContactRules['custordref3_label'] : 'Order Ref 3';

            $data['custordref3_access'] = isset($ContactRules['hide_custordref3_in_client_portal']) ? $ContactRules['hide_custordref3_in_client_portal'] : 0;
        }

        $this->data['job'] = $data;
        $this->data['poData'] = $this->purchaseorderclass->getPurchaseOrderByJobid($jobid);
        $this->template->title(trim(RAPTOR_APP_TITLE.' '.RAPTOR_APP_SUBTITLE).' | Job Detail')
            ->set_layout($this->layout)
            ->set('page_title', 'Job Detail : JOB No '.$jobid)
            ->set('page_sub_title', '')
            ->set_breadcrumb('My Jobs', site_url('jobs'))
            ->set_breadcrumb('Job Detail', '')
            ->set_partial('page_header', 'shared/page_header')
            ->set_partial('header', 'shared/header')
            ->set_partial('navigation', 'shared/navigation')
            ->set_partial('footer', 'shared/footer')
            ->build('jobs/jobdetail', $this->data);
    }

    public function updateRelationshipEntity2($parent_table_key, $id, $child_table_key, $values, $table) 
    {
        // delete values associated with field
        $this->deleteRecords(array($parent_table_key => $id), $table);

        
        // iterate thru selected items insert into table
        foreach ($values as $key => $value){
            $this->insertRecord($table, array($parent_table_key => $id, $child_table_key => $value));
            //$this->insertRecord($table, array($parent_table_key => $id, $child_table_key => $value));
        } 
    }

    public function updateRecords2($data) {
        $this->db->where($data['conditions']);
        $this->db->update($data['table'], $data['fields']);
    }

    /*** X-editable functions ***/
    public function intselect($min = 1, $max = 10)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->editRep->get_int_select($min, $max)));        
    }
    
    public function yesno()
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('1' => 'yes', '0' => 'no')));        
    }

    public function yesno_reversed()
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('1' => 'no', '0' => 'yes')));
    }
    
    public function truefalse()
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('1' => 'true', '0' => 'false')));        
    }
    
    public function cleandirty()
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('1' => 'Dirty', '0' => 'Clean')));        
    }
    
    public function undamageddamaged()
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('1' => 'Damaged', '0' => 'UnDamaged')));        
    }


    
    public function insert_xeditable($tablenum, $id, $field_num)        
    {
        $tablename = $this->tablenames[$tablenum];
        $fieldname = $this->fieldnames[$tablenum][$field_num];
        
        switch ($tablenum) {
            case 400:// ins_scope table 
                //$this->updateOrInsertRecords(array('scope_server_id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                //$this->editRep->updateRecords(array('scope_server_id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
        }    
    }    
    
    public function update_xeditable($tablenum, $id, $field_num)        
    {
        $tablename = $this->tablenames[$tablenum];
        $fieldname = $this->fieldnames[$tablenum][$field_num];

        // TO DO further consolidate code to use fieldname from array

        switch ($tablenum) {
            case 0:
                // se_master table
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 1:
                // se_trade table
                $this->editRep->updateRecords(array('id' => $id), array('se_trade_name' => $this->input->post('value')), $tablename);
                break;
            case 2:
                // se_trade_works table
                $this->updateRelationshipEntity('se_trade_id', $id, 'se_works_id', $this->input->post('value'), $tablename);
                break;
            case 3:
                // se_works table
                $this->editRep->updateRecords(array('id' => $id), array('se_works_name' => $this->input->post('value')), $tablename);
                break;
            case 4:
                // se_works_subworks table
                $this->updateRelationshipEntity('se_works_id', $id, 'se_subworks_id', $this->input->post('value'), $tablename);
                break;
            case 5:
                // se_subworks table
                $this->editRep->updateRecords(array('id' => $id), array('se_subworks_name' => $this->input->post('value')), $tablename);
                break;
            case 6:
                // se_master_keyword table
                $keyword_ids = $this->editRep->getKeywordIds($this->input->post('value'));
                $this->updateRelationshipEntity('se_master_id', $id, 'se_keyword_id', $keyword_ids, $tablename);
                break;
            case 7:
                // se_master_swms table
                $this->updateRelationshipEntity('se_master_id', $id, 'documentid', $this->input->post('value'), $tablename);
                break;
            case 80:
                // se_supplier_works table
                $this->updateRelationshipEntity('customerid', $id, 'se_trade_id', $this->input->post('value'), $tablename);
                break;
            case 90:
                // se_supplier_works table
                $this->updateRelationshipEntity('customerid', $id, 'se_works_id', $this->input->post('value'), $tablename);
                break;
            case 100:
                // se_supplier_subworks table
                $this->updateRelationshipEntity('customerid', $id, 'se_subworks_id', $this->input->post('value'), $tablename);
                break;
            case 110:
                // se_wording table
                $this->editRep->updateRecords(array('id' => $id), array('wording' => $this->input->post('value')), $tablename);
                break;
            case 120:
                // se_master_document table
                // delete values associated with field
                //$this->deleteRecords(array($parent_table_key => $id), $table);

                // iterate thru selected items insert into table

                //$this->updateRelationshipEntity('master_id', $id, 'se_document_id', $this->input->post('value'), $tablename);
                break;

                // updateRelationshipEntity($parent_table_key, $id, $child_table_key, $values, $table)
            
            case 200: // gmap_route table
            case 300:// example table    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 400:// ins_scope table 
                $this->editRep->updateRecords(array('scope_server_id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 500:// document table    
                $this->editRep->updateRecords(array('documentid' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 600:// rep_reporttype table    
            case 610:// rep_area_type table      
            case 620:// rep_area_topic table    
            case 650:// rep_report_areatypetopic_value 
            case 660:// rep_area_value    
            case 670:// rep_asset
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 630:
                // rep_area table
                $this->updateRelationshipEntity2('report_type_id', $id, 'area_type_id', $this->input->post('value'), 'rep_area');
                break;
            case 680:
                // se_billing_region    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 685:
                // se_billing_region_territory 
                
                 $data = $this->editRep->getRecord('region_id, parent_territory', array('id'=>$id), $tablename);
                 foreach($data as $key=>$value) {
                     if($key == $fieldname) {
                        $data[$key] = trim($this->input->post('value')); 
                     }
                 }
                 $data = $this->editRep->getRecord('id, region_id, parent_territory', array('id !='=>$id, 'region_id'=>$data['region_id'], 'parent_territory'=>$data['parent_territory']), $tablename);
                 if(count($data)>0) {
                     echo json_encode(array('error'=>'true'));
                 } else {
                    $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);    
                 }
                break;
            case 690:
                // se_billingrates    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;        
            case 695:
                // se_billing_region   //was 710 
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 640:
                // rep_areatype_areatopic table
                switch ($field_num) {
                    case 2:     
                    case 3: 
                    case 4:     
                    case 5:
                    case 6:  
                    case 8:
                    case 9:
                    case 10:    
                    case 11:     
                        $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                        break;
                    }
                break;
            case 704:
                // con_addresslabel_service_standard
                switch ($field_num) {
                    case 2: // contract_service_standard_id     
                        //$this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                        $this->updateOrInsertXEditRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                        break;
                    }
             case 720:
                // htmldoc_type    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
             case 730:
                // licencetype    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
             case 740:
                // insurancetype    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 750:
                // htmldoc_type    
                $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 760:
                // compliancedocs    
                $this->editRep->updateRecords(array('cdid' => $id), array($fieldname => $this->input->post('value')), $tablename);
                break;
            case 770:
                // asset_doctype  
                 $data = $this->editRep->getRecord('asset_doctype', array('id'=>$id), $tablename);
                 foreach($data as $key=>$value) {
                     if($key == $fieldname) {
                        $data[$key] = trim($this->input->post('value')); 
                     }
                 }
                 $data = $this->editRep->getRecord('id', array('id !='=>$id, 'asset_doctype'=>$data['asset_doctype']), $tablename);
                 if(count($data)>0) {
                     echo json_encode(array('error'=>'true'));
                 } else {
                    $this->editRep->updateRecords(array('id' => $id), array($fieldname => $this->input->post('value')), $tablename);    
                 } 
                break;
            case 780:
                // asset_category  
                 $data = $this->editRep->getRecord('category_name', array('asset_category_id'=>$id), $tablename);
                 foreach($data as $key=>$value) {
                     if($key == $fieldname) {
                        $data[$key] = trim($this->input->post('value')); 
                     }
                 }
                 $data = $this->editRep->getRecord('asset_category_id', array('asset_category_id !='=>$id, 'category_name'=>$data['category_name']), $tablename);
                 if(count($data)>0) {
                     echo json_encode(array('error'=>'true'));
                 } else {
                    $this->editRep->updateRecords(array('asset_category_id' => $id), array($fieldname => $this->input->post('value')), $tablename);    
                 } 
                break;          

        } 
    }
    
    public function updateOrInsertXEditRecords($condition_fields_values, $update_fields_values, $table) 
    {
        // if id was alpha record doesn't exit yet
        if(!is_numeric($condition_fields_values['id'])){
            
            //$update_fields_values = array_merge($update_fields_values, array('labelid'=>63471));
            
            //$insert_fields_values = array('labelid'=>63471, 'contract_service_standard_id'=> 2);
            //$id = $this->editRep->insertRecord(array('table'=>$table, 'fields'=>$insert_fields_values));
            $id = $this->editRep->insertRecord(array('table'=>'con_addresslabel_service_standard', 'fields'=>array('labelid'=>63471, 'contract_service_standard_id'=> 2)));
        } else {
            // record exists so update
            $this->editRep->updateRecords($condition_fields_values, $update_fields_values, $table);
        }
    }
    
    //TO DO revisit strategy of deleting all records then inserting again
    
    public function get_userid_xedit($json_format, $ids)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->editRep->get_userid_xedit($json_format, $ids)));        
    }
    
    public function get_regionid_xedit($json_format, $ids)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->editRep->get_regionid_xedit($json_format, $ids)));        
    }
    
    public function get_parentterritory_xedit($json_format, $ids)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->editRep->get_parentterritory_xedit($json_format, $ids)));        
    }
    
    public function get_tradeid_xedit($json_format, $ids)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->editRep->get_tradeid_xedit($json_format, $ids)));        
    }

    public function get_htmltypeid_xedit($json_format, $ids)
    {
        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($this->editRep->get_htmltypeid_xedit($json_format, $ids)));        
    }
    /*** END X-editable functions ***/
        
    public function actionimage($id, $grouptype, $groupid, $actiontype)
    {
        $sMessage = '';
        $bResult = true;

        switch ($grouptype) {
            // Send Top
            case 0:
                $this->editRep->propertyreport_actionimage($id, $groupid, $actiontype);
                break;
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('result' => $bResult, 'message' => $sMessage)));
    }

    public function photodelete($id)
    {
        //updateRecords($condition_fields_values, $update_fields_values, $table)
        $this->editRep->updateRecords(array('documentid' => $id), array('isdeleted' => 1), 'document');

        $bResult = true;
        $sMessage = '';

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode(array('result'=>$bResult, 'message'=>$sMessage)));

    }
    
     /**
    * @desc This function use for shareReport
    * 
    * @return json
    */
    public function shareReport() {
            
        //check ajax request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
         
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $jobid = $this->input->post('modaljobid');
            $reportid = $this->input->post('modalreportid');
            if( !isset($jobid) || !isset($reportid) )
                $message = 'jobid or report cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $email = $this->input->post('email');
                $notes = $this->input->post('notes');
                
                $jobData = $this->jobclass->getJobById($jobid); 
                
                $request = array(
                    'jobid'            => $jobid, 
                    'reportid'         => $reportid, 
                    'email'            => $email,
                    'notes'            => $notes,
                    'jobdata'          => $jobData, 
                    'logged_contactid' => $this->data['loggeduser']->contactid
                );

                $this->editRep->shareReport($request);
                $message = 'Email sent.';
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
    * @desc This function use for shareReport
    * 
    * @return json
    */
    public function previewReport($jobid='', $reportid='', $areaid='') {

        $this->load->library('editablereport/ajaxPostBackClass');
        
        $params = array(
            'jobid' => $jobid,
            'reportid' => $reportid,
            'mode' => 'generate'//,
            //'areaid' => $areaid
        );
        
        $reportfile = $this->ajaxpostbackclass->ajaxPost($params);
        
        if (file_exists($reportfile)) {
            $info = pathinfo($reportfile);
            $file_name =  basename($reportfile,'.'.$info['extension']);
            $this->load->helper('file');
            $mimetype = get_mime_by_extension($info['basename']);
            
            //$finfo  = new finfo(FILEINFO_MIME);
            header('Content-Description: File Transfer');
            header('Content-Disposition: inline; filename="'.$file_name.'"');
            //header('Content-Type: '.$finfo->file($reportfile));
            header('Content-Type: '.$mimetype);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($reportfile));
            readfile($reportfile);
    
            /*$content = file_get_contents($reportfile);
            $docname = '';

            //Load download helper 
            $this->load->helper('download');

            force_download($docname, $content);*/
        }
        else{
            echo "document doesn't exist.";
            //throw new Exception("document doesn't exist. Documentid: " . $documentid);
        }
        
    }
    
     /**
    * @desc This function use for shareReport
    * 
    * @return json
    */
    public function saveReport() {
        
        
        
        //check ajax request
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
         
        //define variables
        $isSuccess = FALSE;
        $message = "";
        $data = array();
        $code = SuccessClass::$CODE_SUCCESSFUL;

        try
        {
            $jobid = $this->input->post('jobid');
            $reportid = $this->input->post('reportid');
            if( !isset($jobid) || !isset($reportid) )
                $message = 'jobid or report cannot be null.';

            $isSuccess = ( $message == "" ); 

            //initialize an instance from success class
            $success = SuccessClass::initialize($isSuccess);

            if( $isSuccess )
            {
                $this->load->library('editablereport/ajaxPostBackClass');

                $params = array(
                    'jobid' => $jobid,
                    'reportid' => $reportid,
                    'mode' => 'generate'//,
                    //'areaid' => $areaid
                );

                $reportfile = $this->ajaxpostbackclass->ajaxPost($params);
                $message = 'Report saved.';
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

/* End of file EditableReport.php */
/* Location: ./application/controllers/EditableReport.php */
