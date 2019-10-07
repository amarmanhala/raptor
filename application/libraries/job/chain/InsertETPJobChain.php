<?php 
/**
 * InsertETPJobChain Libraries Class
 *
 * This is a Job Chain Class use for create new ETP job
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractJobChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertETPJobChain Libraries Class
 *
 * This is a Job Chain Class use for create new ETP job
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            job/chain
 * @filesource          InsertETPJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class InsertETPJobChain extends AbstractJobChain
{
 
    
    private $successor;

     
    /**
    * @desc This function set Successor for next Service class which one execute after the handleRequest
    * @param Class $nextService - class for execute  
    */
    public function setSuccessor($nextService)
    {
        $this->successor = $nextService;
    }
 
    /**
    * @desc This function use for create new entry for extend job
    * @param array $request - the request is array of required parameter for extending job
    * @return bool -  success or failure
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertETPJobChain');
        
        $etpJobdata = $request['etpJobData'];
         
        $this->db->insert('etp_job', $etpJobdata);
        $id = $this->db->insert_id();
        
        $request["etp_jobid"] = $id;
        $logClass->log('Insert etp_job Query : '. $this->db->last_query());
        
        //For Update Job current count in etp_setting
        $etp_setting = $this->db->where('supplierid', $etpJobdata['supplierid'])->where('setting_name', 'job_currentcount')->get('etp_setting')->row();
        if ($etp_setting) {
            $update_data =  array('setting_value'=>((int)$etp_setting->setting_value+1));
            $this->db->where('id', $etp_setting->id);
            $this->db->update('etp_setting', $update_data); 
        }
        else{
            $update_data =  array(
                'supplierid'       => $etpJobdata['supplierid'],
                'setting_name'     => 'job_currentcount',
                'setting_value'    => 1,
                'setting_typeid'   => 2
            );
            $this->db->insert('etp_setting', $update_data);
        }
        $logClass->log('Update Etp_setting Query : '. $this->db->last_query());
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertETPJobChain.php */