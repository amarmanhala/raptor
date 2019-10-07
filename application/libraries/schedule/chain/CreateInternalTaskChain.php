<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('AbstractScheduleChain.php');
require_once( __DIR__.'/../../LogClass.php');
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/jrq.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/blTechAllocate.class.php");

/**
 * Project: ETP Falcon
 * Package: CI
 * Subpackage: Libraries\Job\Chain
 * File: CreateInternalTaskChain.php
 * Description: It creates the Internal Diary Data for the task
 * Created by : 
 *
 */

class CreateInternalTaskChain extends AbstractScheduleChain
{
    private $successor;

     
    /**
    * @desc This function set Successor for next Service class which one execute after the handleRequest
    * @param Class $nextService - class for execute  
    */
    public function setSuccessor($nextService)
    {
        $this->successor=$nextService;
    }
 
    /**
    * @desc 
    * @param array $request - the request is array of required parameter for creating schedule job
    * @return bool -  success or failure
    */
    public function handleRequest($request)
    {
        
        $logClass= new LogClass('jobtracker', 'CreateInternalTaskChain');
        
        $userdata = $request['userData']; 
         
        //$jrq = new jrq(AppType::JobTracker, $userdata['etp_email']);
        //$blTechAllocate = new blTechAllocate(AppType::JobTracker, $userdata['etp_email']);
         
        $diaryData = $request['diaryData'];
        $poData = $request['poData'];
        foreach ($diaryData as $row) {
            
            $this->db->insert('diary', $row);
              
            $logClass->log('Create Internal diary Task : '. $this->db->last_query());
            
            $apptid = $this->db->insert_id();
            $poData['apptid'] = $apptid;
             
            $this->db->insert('purchaseorders', $poData);
            $poref = $this->db->insert_id();
            $logClass->log('Create Internal diary Task PO: '. $this->db->last_query());
            
            //Get the rules for customerId
            $this->db->select("rcd.*");
            $this->db->from('rule_categories AS rc');
            $this->db->join('rules_categories_docs rcd', 'rcd.cat_id = rc.id', 'left');
            $this->db->where('rc.customerid', $row['customerid']);
            $rules = $this->db->get()->result_array();
            
            $logClass->log('Get Customer Require Docs: '. $this->db->last_query());
 

            if( count( $rules ) > 0 )
            {
                //print_r($rules);
                foreach ($rules as $rule)
                {
                    $jobDocRequired = array(   
                        "documentid"            => $rule['documentid'],
                        "jobid"                 => $row['jobid'],
                        "required_start"        => $rule['required_start'],
                        "required_complete"     => $rule['required_complete'],
                        "poref"                 => $poref,
                        "from_category_rule"    => $rule['cat_id'],
                        "dont_send"             => $rule['dont_send']
                    );
                    $this->db->insert('job_document_required', $jobDocRequired);

                    $logClass->log('create job_document_required : '. $this->db->last_query());
                
                }
            }
            
            //$jrq->CreateJRQ($poData);
            
            //$blTechAllocate->new_po($apptid);
            
        }
			
			
	 

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
        //it should be at the last part of chain
        $this -> returnValue = $request;
	 
		
    }
	
}


/* End of file CreateJobDocumentRequiredChain.php */