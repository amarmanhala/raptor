<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php'); 
require_once( __DIR__.'/../job/JobClass.php');
 
/**
 * Project: ETP Falcon
 * Package: CI
 * Subpackage: Libraries\Schedule
 * File: ScheduleClass.php
 * Description: This is a Schedule class for Schedule Opration 
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */

class ScheduleClass extends MY_Model
{

    private $LogClass;
    private $jobClass;
    private $sharedClass;
    
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('falcon', 'ScheduleClass');
        $this->jobClass = new JobClass();
        $this->sharedClass = new SharedClass();
        
    }
    /**
    * @desc This function use for get schedules
   
    * @return bool -  success or failure
    */
    public function getSchedules($customerid, $startdate, $enddate, $sitestate = NULL, $technicians = NULL, $contractid = NULL, $labelids = NULL) {
         
        $sql = "SELECT apptid,dte,d.userid,d.start,d.duration,d.inprogress,d.completed,a.sitestate,a.sitesuburb,d.jobid,j.custordref,j.contractid 
                FROM diary d 
                INNER JOIN jobs j ON d.jobid=j.jobid 
                INNER JOIN addresslabel a ON j.labelid=a.labelid 
                WHERE j.customerid= :customerid AND d.dte >= :fromdate 
                and d.dte <= :todate  
                AND a.sitestate in (:selectedstates) AND 
                d.userid in (:selectedtechnicians) AND j.contractid  = : contractid";
        
        $this->db->select("apptid,dte,d.userid,d.start,d.end, d.duration,d.inprogress,d.completed,a.sitestate,a.sitesuburb,d.jobid,j.custordref,j.contractid,j.duedate");
        $this->db->from('diary d');
        $this->db->join('jobs j', 'd.jobid=j.jobid', 'INNER');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->where('j.customerid', $customerid);
        $this->db->where('d.dte>=', $startdate);
        $this->db->where('d.dte<=', $enddate);
        if ($sitestate!= NULL) {
            if(is_array($sitestate)){
                $this->db->where_in('a.sitestate', $sitestate); 
            }
            else{
                $this->db->where('a.sitestate', $sitestate); 
            }
            
        }
        if ($labelids!= NULL) {
            if(is_array($labelids)){
                $this->db->where_in('a.labelid', $labelids); 
            }
            else{
                $this->db->where('a.labelid', $labelids); 
            }
            
        }
        
        if ($technicians!= NULL) {
            if(is_array($technicians)){
                $this->db->where_in('d.userid', $technicians); 
            }
            else{
                $this->db->where('d.userid', $technicians); 
            }
            
        }
        
        if ($contractid!= NULL) {
            if(is_array($contractid)){
                $this->db->where_in('j.contractid', $contractid); 
            }
            else{
                $this->db->where('j.contractid', $contractid); 
            }
            
        }
         
        
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get Schedules Data Query : '. $this->db->last_query());
        return $data;
    }
    
    
   
    
    /**
    * @desc This function Get a technicians from contact with given parameters
    * @param integer $customerid - customer id 
    * @return array 
    */
    public function getTechnicians($customerid, $startdate = NULL, $enddate = NULL, $sitestate = NULL, $technicians = NULL, $contractid = NULL) {

        $this->db->select("d.userid");
        $this->db->from('diary d');
        $this->db->join('jobs j', 'd.jobid=j.jobid', 'INNER');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->where('j.customerid', $customerid);
        if($startdate != NULL){
            $this->db->where('d.dte>=', $startdate);
        }
        if($enddate != NULL){
            $this->db->where('d.dte<=', $enddate);
        }
        if ($sitestate!= NULL) {
            if(is_array($sitestate)){
                $this->db->where_in('a.sitestate', $sitestate); 
            }
            else{
                $this->db->where('a.sitestate', $sitestate); 
            }
            
        }
        
        if ($technicians!= NULL) {
            if(is_array($technicians)){
                $this->db->where_in('d.userid', $technicians); 
            }
            else{
                $this->db->where('d.userid', $technicians); 
            }
            
        }
        
        if ($contractid!= NULL) {
            if(is_array($contractid)){
                $this->db->where_in('j.contractid', $contractid); 
            }
            else{
                $this->db->where('j.contractid', $contractid); 
            }
            
        }
        $this->db->group_by('d.userid');
         

        return $this->db->get()->result_array();
    }
    
     
    /**
    * @desc This function Get a contact Schedule List for selected date and all
    * @param integer $contactid - selected contact
    * @return array 
    */
    public function getTimeSheet($customerid, $userid, $date = NULL) {

        
     
        
        $this->db->select("apptid,dte,d.userid,d.start,d.duration,d.inprogress,d.completed,a.sitestate,a.sitesuburb,d.jobid,j.custordref,j.contractid");
        
        $this->db->from('diary d');
        $this->db->join('jobs j', 'd.jobid=j.jobid', 'INNER');
        $this->db->join('addresslabel a', 'j.labelid=a.labelid', 'INNER');
        $this->db->where('j.customerid', $customerid);
        $this->db->where('d.userid', $userid);
        if ($date!= NULL) {
            $this->db->where('d.dte', $date);
        }
        $this->db->order_by('d.start asc');
       
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get Tech TimeSheet Data Query : '. $this->db->last_query());
        return $data;
    }
   
     
}


/* End of file ScheduleClass.php */