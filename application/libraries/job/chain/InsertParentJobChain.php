<?php 
/**
 * InsertParentJobChain Libraries Class
 *
 * This is a Job Chain Class use for create new job
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractJobChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertParentJobChain Libraries Class
 *
 * This is a Job Chain Class use for create new job
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            job/chain
 * @filesource          InsertParentJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertParentJobChain extends AbstractJobChain
{
   /**
    * next chain class 
      * 
    * @var class
    *  
    */
    private $successor;
 
    /**
    * This function set Successor for next Service class which one execute after the handleRequest
     * 
    * @param Class $nextService - class for execute  
    */
    public function setSuccessor($nextService)
    {
        $this->successor = $nextService;
    }
 
    /**
    * This function use for create new job
     * 
    * @param array $request - the request is array of required parameter for creating job
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertParentJobChain');
        $userData = $request['userData'];
        $jobData = $request['jobData'];
      
        $ContactRules = $request['ContactRules'];
       
        
        if (isset($ContactRules["use_contract_parent_job"]) && $ContactRules["use_contract_parent_job"] == 1){
  
            if(!$jobData['parentid'])
            {
                if(isset($ContactRules["auto_create_parent_job"]) && $ContactRules["auto_create_parent_job"] == 1)
                {
                    $ja = array();

                    $ja["customerid"]=$userData['customerid'];
                    $ja['duedate']=date('Y-m-t',time());
                    $ja["duetime"] = "17:00";
                    $ja["jdaysbuffer"] = date('t',time())-date('d',time());
                    $ja["origin"]= "raptor";
                    
                    if(isset($ContactRules["auto_parent_job_description"])){
                        $ja['jobdescription'] = $ContactRules["auto_parent_job_description"];
                    }
                    $ja["userid"]=$userData['email'];
                    $ja["jobstage"]='client_notified';
                    if(isset($ContactRules["auto_parent_job_custordref"])) {
                        $ja['custordref'] = $ContactRules["auto_parent_job_custordref"];
                    }
                    $ja["notexceed"] = 0;
                    $ja["dcfmbufferv"] = 0;
                    $ja["jrespbuffer"] = $ja["jdaysbuffer"] ;
                    $ja["contactid"] = $userData['contactid'];
                    
                    if(isset($ContactRules["auto_parent_job_labelid"]))
                    {
                        $ja["labelid"] = $ContactRules["auto_parent_job_labelid"];
                        
                        $this->db->select('a.*, c.phone, c.mobile, c.email, c.firstname');
                        $this->db->from('addresslabel a');
                        $this->db->join('contact c', 'c.contactid = a.contactid', 'left');
                        $this->db->where('a.labelid', $ja["labelid"]);

                        $addresslabeldetails1 = $this->db->get()->row_array();
                        

                        if(count($addresslabeldetails1)>0){
                            $ja["siteline1"] = $addresslabeldetails1["siteline1"] ;
                            $ja["siteline2"] = $addresslabeldetails1["siteline2"] ;
                            $ja["sitesuburb"] = $addresslabeldetails1["siteline1"] ;
                            $ja["sitestate"] = $addresslabeldetails1["sitestate"] ;
                            $ja["sitepostcode"] = $addresslabeldetails1["sitepostcode"] ;
                            $ja["sitecontact"] = $addresslabeldetails1["sitecontact"] ;
                            $ja["sitephone"] = $addresslabeldetails1["sitephone"] ;
                            $ja["siteemail"] = $addresslabeldetails1["siteemail"] ;
                            $ja["sitecontactid"] = $addresslabeldetails1["sitecontactid"] ;
                            
                            $ja["contactid"] = $addresslabeldetails1["contactid"] ;
                            $ja["sitecontactid"] = $addresslabeldetails1["sitecontactid"] ;
                            $ja["sitefm"] = $addresslabeldetails1["firstname"];
                            $ja["sitefmph"] =  $addresslabeldetails1["mobile"] !="" ? $addresslabeldetails1["mobile"] :  $addresslabeldetails1["phone"];
                            $ja["sitefmemail"] = $addresslabeldetails1["email"];
                            

                        }
                    }
                    
                    $this->db->insert('jobs', $ja);
         
                    $parentjobid = $this->db->insert_id(); 
                    $request["parentid"] = $parentjobid;
                    $con_parentjob_data = array();
                    $con_parentjob_data["customerid"] = $userData['customerid'];
                    $con_parentjob_data["contactid"] = isset($ja['contactid']) ? $ja['contactid'] : $userData['contactid'];
                    $con_parentjob_data["parentjobid"] = $parentjobid;
                    //$con_parentjob_data["dcfmbufferv"] = 0;
                    $con_parentjob_data["monthofyear"] = date('m',time());
                    $con_parentjob_data["year"] = date('Y',time());
                    $con_parentjob_data["status"] = '1';
                    
                    $this->db->insert('con_parentjob_id', $con_parentjob_data); 
 
                    if (isset($request["jobid"]) && $request["jobid"] > 0){
                        
                        $update_data =  array('parentid'=>$parentjobid);
                        
                        $this->db->where('jobid', $request["jobid"]);
                        $this->db->update('jobs', $update_data);  
                         
                    }
                }
            }
            
        }
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertParentJobChain.php */