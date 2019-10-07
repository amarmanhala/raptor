<?php 
/**
 * Schedule Libraries Class
 *
 * This is a Schedule class for Schedule Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  
require_once( __DIR__.'/../job/JobClass.php');
/**
 * Schedule Libraries Class
 *
 * This is a Schedule class for Schedule Opration 
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Schedule
 * @filesource          ScheduleClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
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
        $this->LogClass= new LogClass('jobtracker', 'ScheduleClass');
        $this->jobClass = new JobClass();
        $this->sharedClass = new SharedClass();
        
    }
    /**
    * @desc This function use for get schedules
    * @param array $scheduleParams - the scheduleParams is array of startdate, enddate, ids and apptid
    * @return bool -  success or failure
    */
    public function getSchedules($scheduleParams) {
         
        $this->db->select("d.apptid, d.dte, d.contactid, d.activity_id, d.start, d.end, d.duration, j.ownerjobid, d.jobid, j.jobnumber,  d.status, d.islocked,
                             j.jobdescription, po.poref, j.siteline1, j.isinternal, j.supplierid, j.siteline2, j.sitesuburb, j.sitestate, j.sitepostcode, j.sitecontact, j.sitephone, j.statusid,
                             po.completebydate, po.completebytime, con.firstname, c.companyname");
        $this->db->from('etp_diary d');
        $this->db->where('d.dte>=', $scheduleParams['startdate']);
        $this->db->where('d.dte<', $scheduleParams['enddate']);
        $this->db->where('con.customerid', $scheduleParams['customerid']);
        $this->db->where('d.isdeleted', '0');
        if (count($scheduleParams['ids'])>0) {
           $this->db->where_in('d.contactid', $scheduleParams['ids']);
        }
        if ($scheduleParams['apptid']!= NULL) {
            $this->db->where('d.apptid', $scheduleParams['apptid']);  
        }
        if (isset($scheduleParams['jobid']) && $scheduleParams['jobid']!= NULL && $scheduleParams['jobid']!=0) {
          $this->db->where('d.jobid='.$scheduleParams['jobid'].' or j.isinternal=1');  
        }
        $this->db->join('etp_job j', 'd.jobid = j.jobid', 'inner');
        $this->db->join('customer c', 'j.customerid =c.customerid', 'inner');
        $this->db->join('purchaseorders po', 'j.ownerporef = po.poref', 'left');
        $this->db->join('contact con', 'd.contactid =con.contactid', 'inner');
        
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get Schedules Data Query : '. $this->db->last_query());
        return $data;
    }
    
    /**
    * @desc This function use for create schedule
    * @param array $scheduleParams - the scheduleParams is array of jobid, dbsdate, duration, dbstime, $dbetime and contactid(loggedUser)
    * @return integer -  insert id
    */
    public function createSchedule($scheduleParams) {
        
        //1 - load multiple models
        require_once('chain/CreateScheduleChain.php');
        require_once( __DIR__.'/../job/chain/UpdateJobChain.php');
        require_once('chain/CreatePurchaseOrderChain.php');
        require_once('chain/CreateJobDocumentRequiredChain.php');
        
        //2 - initialize instances
        $CreateScheduleChain = new CreateScheduleChain();
        $UpdateJobChain = new UpdateJobChain();
        $purchaseOrderChain = new CreatePurchaseOrderChain();
        $jobDocumentRequiredChain = new CreateJobDocumentRequiredChain();
  

        //3 - get the parts connected
        $CreateScheduleChain->setSuccessor($UpdateJobChain);
        $UpdateJobChain->setSuccessor($purchaseOrderChain);
        $purchaseOrderChain->setSuccessor($jobDocumentRequiredChain);
		
        
         //4 - start the process
        $loggedUser = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $jobData = $this->jobClass->getJobById($scheduleParams['jobid']);
        
        $etp_activity = $this->getActivity('ATTEND');
        $activity_id = 0;
        if (count($etp_activity) > 0) {
            $activity_id = $etp_activity[0]['id'];
        }
        	
        //Create Schedule Data
        $scheduleData = array(
            'dte'               => $scheduleParams['dbsdate'],
            'contactid'         => $scheduleParams['technician'],
            'duration'          => $scheduleParams['duration'],
            'start'             => $scheduleParams['dbstime'],
            'end'               => $scheduleParams['dbetime'],
            'customerid'        => $jobData['customerid'],
            'notes'             => $jobData['jobdescription'],
            'ownerjobid'        => $jobData['ownerjobid'],
            'jobid'             => $scheduleParams['jobid'],
            'createdby'         => $scheduleParams['logged_contactid'],
            'datecreated'       => date('Y-m-d H:i:s',time()),
            'labelid'           => $jobData['labelid'],
            'origin'            => 'etp',
            'activity_id'       => $activity_id,
            'last_updated'      => date('Y-m-d H:i:s',time()),
            'latitude_decimal'  => (double)$jobData['latitude_decimal'],
            'longitude_decimal' => (double)$jobData['longitude_decimal']
        );

        if($scheduleParams['restartjob'] == 1){
            
            $updateJobParams = array( 
                'statusid' => 30
            );
        }
        else {
            
            $queryParams = array(
               'jobid'      => $scheduleParams['jobid'],
               'isdeleted'  => 0
            );

            $checkDiaryData = $this->getDairyData($queryParams);

            if (count($checkDiaryData) == 0) {
                $updateJobParams = array( 
                     'statusid' => 20
                );

            }
            else{
                $updateJobParams = array(
                    'statusid'  => $jobData['statusid']
                );
            }
        }
        
         
        
        $purchaseOrderData = array(
            'supplierid'        => $jobData['supplierid'],
            'statusid'          => 20,
            'podate'            => $scheduleParams['dbsdate'],
            'attendbydate'      => $jobData['attendby'],
            'completebydate'    => $jobData['completeby'],
            'jobid'             => $jobData['ownerjobid'],
            'accepted'          => 'on',
            'invoiceref'        => $jobData['custordref'],
            'safety'            => 'on',
            'acceptdate'        => $jobData['acceptdate'],
            'apptid'            => -1,
            'polimit'           => $jobData['polimit']
        );
		
        
        $request = array(
            'scheduleParams'    => $scheduleParams,
            'userData'          => $loggedUser,
            'jobData'           => $jobData,
            'scheduleData'      => $scheduleData,
            'updateJobParams'   => $updateJobParams,
            'purchaseOrderData' => $purchaseOrderData 
        );
 
        $CreateScheduleChain->handleRequest($request);
								
        ///5 - get inserted id values
        $returnValue = $jobDocumentRequiredChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for inviteAppointment
    * @param array $scheduleParams - the scheduleParams is array of apptid, dbsdate, duration, dbstime, $dbetime and contactid(loggedUser)
    * @return integer -  insert id
    */
    public function inviteAppointment($scheduleParams) {
        
        //1 - load multiple models
        require_once('chain/CreateScheduleChain.php');
         
        //2 - initialize instances
        $CreateScheduleChain = new CreateScheduleChain();
        
        //3 - get the parts connected
          
        //4 - start the process
        $this->LogClass->log('Invite Appointment : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $diaryData = $this->getSchedule($scheduleParams['apptid']);
   
        
        $this->db->select("*");
        $this->db->where("apptid", $scheduleParams['apptid']);
        $this->db->from("etp_diary");
        $query = $this->db->get();
      
        $scheduleData = $query->row_array();
        $scheduleData['contactid'] = $scheduleParams['technicianid'];
        $scheduleData['invited_apptid'] = $scheduleParams['apptid'];
        $scheduleData['dte'] = $scheduleParams['dte'];
        
        unset($scheduleData['apptid']);
        
         
        //create request array for Chain Process
        $request = array(
            'scheduleParams' => $scheduleParams, 
            'diaryData'      => $diaryData,
            'scheduleData'   => $scheduleData
        );
        
      
        $CreateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $CreateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for start Appointment
    * @param array $scheduleParams - the $scheduleParams is array of apptid,contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function startAppointment($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/UpdateScheduleChain.php');
        require_once( __DIR__.'/../job/chain/UpdateJobChain.php');
        require_once( __DIR__.'/../job/chain/PurchaseOrderUpdateChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');
         

         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();
        $UpdateJobChain = new UpdateJobChain();
        $PurchaseOrderUpdateChain = new PurchaseOrderUpdateChain();
        $JobNoteChain = new JobNoteChain();
    

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor($UpdateJobChain);
        $UpdateJobChain->setSuccessor($PurchaseOrderUpdateChain); 
        $PurchaseOrderUpdateChain->setSuccessor($JobNoteChain);
        
        
        //4 - start the process
        $this->LogClass->log('start Appointment : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $diaryData = $this->getSchedule($scheduleParams['apptid']);
        $jobData = $this->jobClass->getJobById($diaryData[0]['jobid']);
       
        $nowdate = date('Y-m-d', time());
        $nowtime = date('H:i:s', time());
        $sdate = $nowdate.' '.$nowtime;
        $duration = $this->sharedClass->etp_settings('default_task_duration', $jobData['supplierid']);
        if(!$duration) {
            $duration = '60';
        }
        $default_task_duration = '+'.$duration.'minutes';

        $time = strtotime($sdate.$default_task_duration);
        $dbetime = date('H:i', $time);
        //Update Diary Data
        $updateScheduleData =  array(
            'dte'           =>$nowdate,
            'start'         =>$nowtime,
            'end'           =>$dbetime,
            'status'        =>1,
            'last_updated'  =>$nowdate.' '.$nowtime
        );
        
        //update etp_job data array
        if($jobData['statusid'] != 20){
            //update etp_job data array
            $updateJobParams = array(
                'statusid'  => $this->jobClass->getJobStatusID('INPROGRESS'),
                'startdate' => $nowdate.' '.$nowtime 
            );
        }
        else{
            $updateJobParams = array(
                'statusid'  => $jobData['statusid']
            );
        }
        
        //update po data array
        $updatePO = array(
            'statusid'      => 40,
            'responsedate'  => $nowdate.' '.$nowtime
        );
        $jobParams = array(
            'poref' => $diaryData[0]['ownerporef']
        );
        
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'etp',
            'jobid'      => $diaryData[0]['ownerjobid'],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Task started by external technician '.$diaryData[0]['companyname'].' '.$diaryData[0]['firstname'].' on po: '.$diaryData[0]['ownerporef'],
            'ntype'      => 'etp update',
            'notetype'   => 'external',
            'userid'     => $loggedUserData['etp_email'],
            'contactid'  => $loggedUserData['contactid'],
            'supplierid' => $loggedUserData['customerid']
        );
      
        
         
        //create request array for Chain Process
        $request = array(
            'scheduleParams'    => $scheduleParams, 
            'diaryData'         => $diaryData,
            'jobData'           => $jobData,
            'jobParams'         => $jobParams,
            'updateScheduleData'=> $updateScheduleData,
            'updateJobParams'   => $updateJobParams,
            'updatePO'          => $updatePO,
            'jobNoteData'       => $jobNoteData 
        );
        
      
        $UpdateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    
    /**
    * @desc This function use for Close Appointment
    * @param array $scheduleParams - the $scheduleParams is array of apptid,contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function closeAppointment($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/UpdateScheduleChain.php');
        require_once('chain/CreateScheduleChain.php');
        require_once( __DIR__.'/../job/chain/UpdateJobChain.php');
        require_once( __DIR__.'/../job/chain/PurchaseOrderUpdateChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');
         

         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();
        $CreateScheduleChain = new CreateScheduleChain();
        $UpdateJobChain = new UpdateJobChain();
        $PurchaseOrderUpdateChain = new PurchaseOrderUpdateChain();
        $JobNoteChain = new JobNoteChain();
    

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor($UpdateJobChain);
        $UpdateJobChain->setSuccessor($PurchaseOrderUpdateChain); 
        $PurchaseOrderUpdateChain->setSuccessor($JobNoteChain);
        
        
        //4 - start the process
        $this->LogClass->log('Close Appointment : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $diaryData = $this->getSchedule($scheduleParams['apptid']);
        $jobData = $this->jobClass->getJobById($diaryData[0]['jobid']);
       
        $nowdate = $scheduleParams['nowdate'];
        $nowtime = $scheduleParams['nowtime'];
          
        $scheduleData = array();
        
        if ($scheduleParams['task'] == 'close') {
            $updateScheduleData = array(
                'end' => $nowtime,
                'status' => 2,
                'completedBy' => $loggedUserData['contactid'],
                'last_updated' => $nowdate.' '.$nowtime,
                'completedDate' => $nowdate.' '.$nowtime,
                'completedOrigin' => "etp"
            );

            $updateJobParams = array(
                'statusid' => 30
            );

            $updatePO = array(
                'statusid' => 40
            );
        }

        if ($scheduleParams['task'] == 'reallocate') {
            $updateScheduleData = array(
                //'end' => $nowtime,
                'status' => 2,
                'completedBy' => $loggedUserData['contactid'],
                'last_updated' => $nowdate.' '.$nowtime,
                'completedDate' => $nowdate.' '.$nowtime,
                'completedOrigin' => "etp"
             );

            $updateJobParams = array(
                'holddate' => $nowdate.' '.$nowtime,
                'statusid' => 20
            );

            $updatePO = array(
                'statusid' => 45
            );
            
            $this->db->select("*");
            $this->db->where("apptid", $scheduleParams['apptid']);
            $this->db->from("etp_diary");
            $query = $this->db->get();

            $scheduleData = $query->row_array();
            $scheduleData['contactid'] = $scheduleParams['technicianid'];
            $scheduleData['invited_apptid'] = $scheduleParams['apptid'];
            $scheduleData['status'] = 0;
            if ($scheduleParams['allocatestart'] != '' && $scheduleParams['allocateend'] != '') {
                $scheduleData['start'] = $scheduleParams['allocatestart'];
                $scheduleData['end'] = $scheduleParams['allocateend'];
            }
            unset($scheduleData['apptid']);
            
            $PurchaseOrderUpdateChain->setSuccessor($CreateScheduleChain);
            $CreateScheduleChain->setSuccessor($JobNoteChain);
        }

        if ($scheduleParams['task'] == 'complete') {
            $updateScheduleData = array(
                'end' => $nowtime,
                'status' => 2,
                'completedBy' => $loggedUserData['contactid'],
                'last_updated' => $nowdate.' '.$nowtime,
                'completedDate' => $nowdate.' '.$nowtime,
                'completedOrigin' => "etp"
             );

            $updateJobParams = array(
                'finishdate' => $nowdate.' '.$nowtime,
                'statusid'   => 40,
                'isclosed'   => 1
            );

            $updatePO = array(
                'completedate'  => $nowdate.' '.$nowtime,
                'statusid'      => 60,
                'completed'     => 'on'
              );
        }
          
        $jobParams = array(
            'poref' => $diaryData[0]['ownerporef']
        );
        
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'etp',
            'jobid'      => $diaryData[0]['ownerjobid'],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => $scheduleParams['notes'],
            'ntype'      => "complete",
            'notetype'   => 'completion',
            'userid'     => $loggedUserData['etp_email'],
            'contactid'  => $loggedUserData['contactid'],
            'supplierid' => $loggedUserData['customerid']
        );
      
        
                               
         
        //create request array for Chain Process
        $request = array(
            'scheduleParams'    => $scheduleParams, 
            'diaryData'         => $diaryData,
            'jobData'           => $jobData,
            'jobParams'         => $jobParams,
            'updateScheduleData'=> $updateScheduleData,
            'updateJobParams'   => $updateJobParams,
            'updatePO'          => $updatePO,
            'scheduleData'      => $scheduleData,
            'jobNoteData'       => $jobNoteData 
        );
        
      
        $UpdateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for start Appointment
    * @param array $scheduleParams - the $scheduleParams is array of apptid,contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function pauseAppointment($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/UpdateScheduleChain.php');
        require_once('chain/CreateScheduleChain.php');
        require_once( __DIR__.'/../job/chain/PurchaseOrderUpdateChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');
         

         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();
        $CreateScheduleChain = new CreateScheduleChain();
        $PurchaseOrderUpdateChain = new PurchaseOrderUpdateChain();
        $JobNoteChain = new JobNoteChain();
    

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor($CreateScheduleChain);
        $CreateScheduleChain->setSuccessor($PurchaseOrderUpdateChain); 
        $PurchaseOrderUpdateChain->setSuccessor($JobNoteChain);
        
        
        //4 - start the process
        $this->LogClass->log('start Appointment : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $diaryData = $this->getSchedule($scheduleParams['apptid']);
        $jobData = $this->jobClass->getJobById($diaryData[0]['jobid']);
       
      
        $nowdate = date('Y-m-d', time());
        $nowtime = date('H:i:s', time());
        //Update Diary Data
        $updateScheduleData = array(
            'end'             => $nowtime,
            'status'          => 2,
            'completedBy'     => $this->data['loggeduser']->contactid,
            'last_updated'    => $nowdate.' '.$nowtime,
            'completedDate'   => $nowdate.' '.$nowtime,
            'completedOrigin' => "etp"
        );
        
        
        $this->db->select("*");
        $this->db->where("apptid", $scheduleParams['apptid']);
        $this->db->from("etp_diary");
        $query = $this->db->get();
      
        $scheduleData = $query->row_array();
        $sdate = $nowdate.' '.$nowtime;
        $duration = $this->sharedClass->etp_settings('default_task_duration', $diaryData[0]['supplierid']);
        if(!$duration) {
            $duration = '60';
        }
        $default_task_duration = '+'.$duration.'minutes';

        $ntime = strtotime($sdate.$default_task_duration);
        $scheduleData['end'] = date('H:i', $ntime);

        $scheduleData['start'] = date('H:i', time());
        unset($scheduleData['apptid']);
        $scheduleData['status'] = 0;
       
        //update po data array
        $updatePO = array(
            'statusid'      => 45,
            'responsedate'  => $nowdate.' '.$nowtime
        );
        
        $jobParams = array(
            'poref' => $diaryData[0]['ownerporef']
        );
        
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'etp',
            'jobid'      => $diaryData[0]['ownerjobid'],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => 'Task paused by external technician '.$diaryData[0]['companyname'].' '.$diaryData[0]['firstname'].' on po: '.$diaryData[0]['ownerporef'],
            'ntype'      => 'etp update',
            'notetype'   => 'external',
            'userid'     => $loggedUserData['etp_email'],
            'contactid'  => $loggedUserData['contactid'],
            'supplierid' => $loggedUserData['customerid']
        );
         
        //create request array for Chain Process
        $request = array(
            'scheduleParams'    => $scheduleParams, 
            'diaryData'         => $diaryData,
            'jobData'           => $jobData,
            'jobParams'         => $jobParams,
            'updateScheduleData'=> $updateScheduleData,
            'scheduleData'      => $scheduleData,
            'updatePO'          => $updatePO,
            'jobNoteData'       => $jobNoteData 
        );
        
      
        $UpdateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for lock Appointment
    * @param array $scheduleParams - the $scheduleParams is array of apptid,contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function lockAppointment($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/UpdateScheduleChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');
         

         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();
        $JobNoteChain = new JobNoteChain();
    

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor($JobNoteChain);
        
        //4 - start the process
        $this->LogClass->log('lock Appointment : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['contactid']);
        $diaryData = $this->getSchedule($scheduleParams['apptid']);


        if ($diaryData[0]['islocked'] == 0) {
            $islock = 1;
        } else {
            $islock = 0;
        }

        $lastupdated = date('Y-m-d H:i:s', time());
        $lastupdated1 = date(RAPTOR_DISPLAY_DATEFORMAT.' '.RAPTOR_DISPLAY_TIMEFORMAT, time());
        
        $updateScheduleData = array(
            'islocked'     => $islock,
            'lockreason'   => $scheduleParams['reason'],
            'last_updated' => $lastupdated
        );
        
        if ($islock == 1) {
            $notes = 'Task ('.$scheduleParams['apptid'].') locked by '.$loggedUserData['firstname'].' for '.$diaryData[0]['firstname'].' on '.$lastupdated1.'. Reason: '.$scheduleParams['reason'];
        } else {
            $notes = 'Task ('.$scheduleParams['apptid'].') unlocked by '.$loggedUserData['firstname'].' for '.$diaryData[0]['firstname'].' on '.$lastupdated1;
        }
        
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'etp',
            'jobid'      => $diaryData[0]['ownerjobid'],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => $notes,
            'ntype'      => 'etp update',
            'notetype'   => 'external',
            'userid'     => $loggedUserData['etp_email'],
            'contactid'  => $loggedUserData['contactid'],
            'supplierid' => $loggedUserData['customerid']
        );
         
        //create request array for Chain Process
        $request = array(
            'scheduleParams'    => $scheduleParams, 
            'diaryData'         => $diaryData,
            'updateScheduleData'=> $updateScheduleData,
            'jobNoteData'       => $jobNoteData 
        );
        
      
        $UpdateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $JobNoteChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for update schedule
    * @param array $scheduleParams - the scheduleParams is array of dte, start, end, apptid
    * $scheduleParams = array(
    *                 dte =>2014-02-25,
    *                 start =>15:30:00,
    *                 end =>16:30:00,
    *                 apptid =>25
    *               );
    * @return boolean -  true or false
    */
    public function updateSchedule($scheduleParams) {
        //1 - load multiple models
       
        require_once('chain/UpdateScheduleChain.php');
        
         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor(NULL);
        
         //4 - start the process
        $diff = timeDiffTimeFormat($scheduleParams['dte'].' '.$scheduleParams['start'], $scheduleParams['dte'].' '.$scheduleParams['end']);
        $duration = $diff[0];
        if ($diff[1]>0) {
            $duration = $diff[0].'.'.$diff[1];
        }
 
        $updateScheduleData = array(
            'dte'          => $scheduleParams['dte'],
            'start'        => $scheduleParams['start'],
            'end'          => $scheduleParams['end'],
            'duration'     => $duration,
            'last_updated' => date('Y-m-d H:i:s')
        );
            
         
        $request = array(
            'scheduleParams'     => $scheduleParams,
            'updateScheduleData' => $updateScheduleData
        );
 
        $UpdateScheduleChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for update activity
    * @param array $scheduleParams - the scheduleParams is array of dte, start, end, apptid
    * $scheduleParams = array(
    *                 dte =>2014-02-25,
    *                 start =>15:30:00,
    *                 end =>16:30:00,
    *                 apptid =>25
    *               );
    * @return boolean -  true or false
    */
    public function updateActivity($scheduleParams) {
        //1 - load multiple models
       
        require_once('chain/UpdateScheduleChain.php');
        
         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor(NULL);
        
         //4 - start the process
        $updateScheduleData = array(
            'activity_id'  => $scheduleParams['activityid'],
            'last_updated' => date('Y-m-d H:i:s')
        );
            
         
        $request = array(
            'scheduleParams'     => $scheduleParams,
            'updateScheduleData' => $updateScheduleData
        );
 
        $UpdateScheduleChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function Get a technicians from contact with given parameters
    * @param integer $ownercustomerid - Logged Contact customerid
    * @param integer $supplierid - Selected supplier id
    * @return array 
    */
    public function getTechnicians($ownercustomerid, $supplierid = NULL) {

        $this->db->select(array("c.contactid, c.firstname, TRIM(CONCAT(c.firstname,' ',c.surname)) as contactname"));
        $this->db->from('contact c');
        $this->db->join('customer s', 'c.customerid =s.customerid', 'INNER');
        $this->db->where('s.ownercustomerid', $ownercustomerid); 
        if($supplierid != NULL && $supplierid != '' && $supplierid != 0){
            $this->db->where('c.customerid', $supplierid);
        }
        $this->db->where('c.etp_onschedule', '1');
        $this->db->where('c.active', '1');
        $this->db->order_by('contactname', 'asc');
        return $this->db->get()->result_array();
    }
    
    /**
    * @desc This function Get a unbilled time total with given parameters
    * @param integer $supplierid - supplierid  
    * @return float 
    */
    public function getUnbilledTimeTotal($supplierid) { 

        $this->db->select(array("IFNULL(SUM(duration*billrate*wud),0) as total"));
        $this->db->from('etp_diary d' );
        $this->db->join('etp_job j', 'd.jobid =j.jobid', 'INNER');
        $this->db->where('j.quoterqd', '0');
        $this->db->where('d.isbillable', '1');
        $this->db->where('d.invoiceid', '0');
        $this->db->where('d.status', '2');
        $this->db->where('supplierid', $supplierid);
        $data = $this->db->get()->row();

        if ($data) {
            return (float)$data->total;
        }
        else{
            return 0;
        }
    }

    
    /**
    * @desc This function Get a technicians from contact with given parameters
    * @param integer $customerid - customer id 
    * @return array 
    */
    public function checkTaskOverlap($params) {

        $sql= "SELECT * FROM etp_diary WHERE isdeleted=0 AND contactid=".$params['contactid']." AND dte='".$params['sdate']."' AND apptid!=".$params['apptid']." AND ((start BETWEEN '".$params['stime']."' and '".$params['etime']."') OR (end BETWEEN '".$params['stime']."' and '".$params['etime']."'))";
        return $this->getDataFromSQLQuery($sql);
    }
    
    
    /**
    * @desc This function Get a Schedule Detail for selected appid
    * @param integer $apptid - apptid from etp_diary
    * @return array 
    */
    public function getSchedule($apptid) {

        $argsvalid=TRUE;
	$exceptionmsg = "";
	
	if (! isset($apptid) ){
            $argsvalid = FALSE;
            $exceptionmsg = "apptid value must be supplied.";
	}
	
	if(! $argsvalid){
            throw new exception($exceptionmsg);
	}
        
        $this->db->select("d.*,j.jobnumber,j.isinternal,  j.ownerjobid, j.ownerporef, j.jobdescription, j.siteline1, j.siteline2, j.siteline3,
            j.sitesuburb, j.sitestate, j.sitepostcode, j.sitecontact, j.sitephone, j.supplierid, con.firstname, c.companyname, j.statusid");
        $this->db->from('etp_diary d');
        $this->db->where('d.apptid =', $apptid);
        $this->db->join('etp_job j', 'd.jobid = j.jobid', 'inner');
        $this->db->join('customer c', 'j.customerid =c.customerid', 'inner');
        $this->db->join('contact con', 'd.contactid =con.contactid', 'inner');
        
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get ETP Diary Data Query : '. $this->db->last_query());
        return $data;
    }

    /**
    * @desc This function Get a contact list for logged customer and not use for selected appid
    * @param integer $supplierid - logged user customerid
    * @param integer $thiscontactid - contact id from etp_diary for selected appid
    * @param integer $apptid - apptid from etp_diary
    * @return array 
    */
    public function getInvites($supplierid, $thiscontactid, $apptid) {
        
    
        if( !isset( $supplierid ) )
            throw new Exception("customerId doesn't exist");

        if( !isset( $thiscontactid ) )
            throw new Exception("contactid doesn't exist");

        if( !isset( $apptid ) )
            throw new Exception("apptid doesn't exist");
        
        $sql = "SELECT contactid,firstname "
                . " FROM contact"
                . " WHERE etp_onschedule =1"
                . " AND customerid = $supplierid"
                . " AND contactid != $thiscontactid AND"
                . " contactid NOT IN (SELECT contactid FROM etp_diary WHERE invited_apptid = $apptid)"
                . " ORDER BY firstname";

        $query = $this->db->query($sql);
        return $query->result_array();        
    }
    
    /**
    * @desc This function Get a contact list for logged customer and not use for selected appid
    * @param integer $contactid - contactid
    * @param date $date - date
    * @param time $ecstime - mintime
    * @param time $ecetime - maxtime
    * @return array 
    */
    public function getAdjustAppointment($contactid, $date, $ecstime, $ecetime) {
        
    
        if( !isset( $contactid ) )
            throw new Exception("contactid doesn't exist");

        if( !isset( $date ) )
            throw new Exception("date doesn't exist");

        if( !isset( $ecstime ) )
            throw new Exception("ecstime doesn't exist");
        
        if( !isset( $ecetime ) )
            throw new Exception("ecetime doesn't exist");
        
       $sql = "select * from etp_diary where contactid=$contactid and dte='".$date."' and status!=2 and isdeleted=0 and "
                                . "((('".$ecstime."'<start and '".$ecetime."'<=end) and ('".$ecetime."'>start)) or "
                                . "('".$ecstime."'<start and '".$ecetime."'>=end) or "
                                . "('".$ecstime."'>start and '".$ecetime."'<=end) or "
                                . "(('".$ecstime."'>start and '".$ecetime."'>=end) and ('".$ecstime."'<end))) order by start limit 1";


        $query = $this->db->query($sql);
        return $query->result_array();        
    }
    
     
     
    
    /**
    * @desc This function Get a contact list for logged customer and not use for selected appid
    * @param array $queryParams - parameters for select query
    * @return array 
    */
    public function getDairyData($queryParams) {
        
        $this->db->select('*');
        $this->db->where($queryParams);
        $this->db->from('etp_diary');
        $data = $this->db->get()->result_array();
        return $data;
    }

    /**
    * @desc This function Get a contact Schedule List for selected date and all
    * @param integer $contactid - selected contact
    * @return array 
    */
    public function getTimeSheet($contactid, $date = NULL) {

        if( !isset( $contactid ) ){
            throw new Exception("contactid doesn't exist");
        }
        
        $this->db->select("d.apptid, d.dte, d.start, d.duration, d.jobid,j.jobnumber, j.sitesuburb, d.notes, c.firstname");
        
        $this->db->from('etp_diary d');
        $this->db->join('etp_job j', 'd.jobid =j.jobid', 'left');
        $this->db->join('contact c', 'c.contactid =d.contactid', 'left');
        $this->db->where('d.contactid', $contactid);
        if ($date!= NULL) {
            $this->db->where('d.dte', $date);
        }
        $this->db->order_by('d.start asc');
       
        $data = $this->db->get()->result_array();
        
        $this->LogClass->log('Get Contact TimeSheet Data Query : '. $this->db->last_query());
        return $data;
    }
   
    
   
    /**
    * @desc This function Get a activityid with given parameters
    * @param text $activity - the name of activity for getting activity id
    * @return result array - array(array(id =>1235, code =>'state'))
    */
    public function getActivity($activity = NULL) {

         $this->db->select('*');
         if ($activity!= NULL) {
             $this->db->where('LCASE(activity)', strtolower($activity));
         }
         $this->db->where('is_active', 1);
         $this->db->from('etp_activity');
         $this->db->order_by('sortorder', 'asc');
         $query = $this->db->get();

         return $query->result_array();
    }
    
    /**
    * @desc This function Get schedule colors data with given parameters
    * @param int $supplierid - the name of supplierid for getting colors data
     * @param string $field - default NULL
    * @return array
    */
    public function getScheduleItems($supplierid, $field = NULL) {
           
        $this->db->select('*');
        $this->db->where('supplierid', $supplierid);
        if ($field != NULL) {
            $this->db->where($field);
        }
        $this->db->where('isactive', 1);
        $this->db->from('etp_schedulecolour');
        $data = $this->db->get()->result_array();
        if (count($data) == 0) {
            $this->db->select('*');
            if ($field != NULL) {
              $this->db->where($field);
            }
            $this->db->where('supplierid', 0);
            $this->db->where('isactive', 1);
            $this->db->from('etp_schedulecolour');
            $data = $this->db->get()->result_array();
        }
        
        $this->LogClass->log('Get Schedules Data Query : '. $this->db->last_query());
        return $data;
    }

    
    public function formatAppointment($data) {

        foreach ($data as $key => $value) {
            
            $data[$key]['scheduledate']=$data[$key]['dte'];
            $location = array();
            if (trim($value['siteline1']) != '') {
                array_push($location, $value['siteline1']);
            }
            if (trim($value['siteline2']) != '') {
                array_push($location, $value['siteline2']);
            }

            $data[$key]['address1'] = implode(", ", $location);
            $data[$key]['dte'] = format_date($value['dte'], 'D d-M-Y');
            $data[$key]['start'] = format_time($value['start'], 'H:i');

            $data[$key]['invitedata'] = $this->scheduleclass->getInvites($data[$key]['supplierid'], $data[$key]['contactid'], $data[$key]['apptid']);
        }
        return $data;
    }
    
    public function getDefaultScheduleLayout($contactid) {
        
        $schedulelayout = array(
            'timeslot'      => '00:30:00',
            'view'          => 'month',
            'singletech'    => '',
            'multitech'     => array()
        );
        
        $schedulelayoutData = $this->sharedClass->getSettings($contactid, 'ScheduleLayout');
        
        if (is_array($schedulelayoutData)) {
           if ($schedulelayoutData['data']!='') {
               $schedulelayout = $schedulelayout = json_decode($schedulelayoutData['data'], true);
           }
        }  
        return $schedulelayout;
    }
    
    /**
    * @desc This function adjst schedule colors for all task status
    * @param array $schedulecolors
    * @param array $value
    * @param array $getstatus 
    * @return array
    */
    public function getSechdulecolor($schedulecolors, $value, $getstatus) {

        $jobcolor = '';

        foreach ($schedulecolors as $value1) {

            //task notified
            if (count($getstatus) > 0) {
                if ($value['statusid'] == $getstatus[0]['id']) {
                    if ($value1['jobnotified'] == 1 && $value1['taskcomplete'] == 0) {
                        $jobcolor = $value1['colour'];
                        break;
                    }
                }
            }

            // task in progress
            if ($value['status'] == 1 && $value1['taskinprogress'] == 1) {
                $jobcolor = $value1['colour'];
                break;
            }

            //task complete for notify 1
            if ($value['status'] == 2 && count($getstatus) > 0 && $value['statusid'] == $getstatus[0]['id'] && $value1['jobnotified'] == 1 && $value1['taskcomplete'] == 1) {
                $jobcolor = $value1['colour'];
                break;
            }

            //task complete for notify 0
            if ($value['status'] == 2 && $value1['jobnotified'] == 0 && $value1['taskcomplete'] == 1) {
                $jobcolor = $value1['colour'];
                break;
            }

            //task due
            if ($value['completebydate'] == date('Y-m-d', time()) && $value1['duetoday'] == 1) {
                $jobcolor = $value1['colour'];
                break;
            }

             //task overdue
            if ($value['completebydate']<date('Y-m-d', time()) && $value['status'] == 0 && $value1['overdue'] == 1) {
                $jobcolor = $value1['colour'];
                break;
            }

            //task activity
            if ($value1['activityid']  != 0 && ($value['activity_id'] == $value1['activityid'])) {
                $jobcolor = $value1['colour'];
                break;
            }

            //unpaid
            if ($value['activity_id']  != 0 && $value1['unpaid'] != 0) {
                $sql = "select id from etp_activity where not_payable=1 && id='".$value['activity_id']."'";
                $query = $this->db->query($sql);
                if ($query->num_rows() > 0) {
                    $jobcolor = $value1['colour'];
                }
            }

            //nonbillable
            if ($value['activity_id']  != 0 && $value1['nonbillable'] != 0) {
                $sql = "select id from etp_activity where is_billable=0 && id='".$value['activity_id']."'";
                $query = $this->db->query($sql);
                if ($query->num_rows() > 0) {
                    $jobcolor = $value1['colour'];
                }
            }
        }
        return $jobcolor;
    }
    
    /**
    * @desc This function use for bump schedule
    * @param array $scheduleParams - the scheduleParams is array of date, task_contactid, nextdate, contactid(loggedUser)
    * $jobParams = array(
    *                 date =>2014-02-25,
    *                 task_contactid =>25,
    *                 nextdate =>2014-02-25,
    *                 contactid =>25
    *               );
    * @return boolean -  true or false
    */
    public function bumpSchedule($scheduleParams) {
        //1 - load multiple models
       
        require_once('chain/UpdateScheduleChain.php');
        
         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor(NULL);
        
         //4 - start the process

        $updateScheduleData = array(
            'dte'          => $scheduleParams['nextdate'],
            'last_updated' => date('Y-m-d H:i:s')
        );
        
        $customWhere = array(
            'dte' => $scheduleParams['date'],
            'contactid' => $scheduleParams['task_contactid'],
            'status' => '0',
            'islocked' => '0'
        );
         
        $request = array(
            'scheduleParams'     => $scheduleParams,
            'updateScheduleData' => $updateScheduleData,
            'customWhere'        => $customWhere
        );
 
        $UpdateScheduleChain->handleRequest($request);

        ///5 - get inserted id values
        $returnValue = $UpdateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for create internal task
    * @param array $scheduleParams - the $scheduleParams is array of apptid, contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function createInternalTask($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/CreateScheduleChain.php');
        require_once( __DIR__.'/../job/chain/UpdateJobChain.php');

         //2 - initialize instances
        $CreateScheduleChain = new CreateScheduleChain();
        $UpdateJobChain = new UpdateJobChain();

        //3 - get the parts connected
        $CreateScheduleChain->setSuccessor($UpdateJobChain);
        
        //4 - start the process
        $this->LogClass->log('create Internal Task : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $jobData = $this->jobClass->getJobById($scheduleParams['jobid']);
       
        $date = to_mysql_date($scheduleParams['date'], RAPTOR_DISPLAY_DATEFORMAT, "Y-m-d");
        $task_duration = $scheduleParams['duration']*60;
        $task_duration = '+'.$task_duration.'minutes';
        $time = strtotime($date.' '.$scheduleParams['starttime'].$task_duration);
        $endtime = date('H:i', $time);
        $newstartdate = $date.'T'.$scheduleParams['starttime'];
        $newenddate = date('Y-m-d', $time).'T'.date('H:i', $time);
        
        //Create Internal Task Diary Data
        $createScheduleData =  array(
            'contactid'     => $scheduleParams['technician'],
            'activity_id'   => $scheduleParams['activity'],
            'jobid'         => $scheduleParams['jobid'],
            'dte'           => $date,
            'start'         => $scheduleParams['starttime'],
            'duration'      => $scheduleParams['duration'],
            'notes'         => $scheduleParams['description'],
            'end'           => $endtime,
            'customerid'    => $loggedUserData['customerid'],
            'createdby'     => $loggedUserData['contactid'],
            'datecreated'   => date('Y-m-d H:i:s', time()),
            'isbillable'    => '0',
            'origin'        => 'etp',
            'last_updated'  => date('Y-m-d H:i:s', time())
        );
        
        $queryParams = array(
            'jobid'     => $scheduleParams['jobid'],
            'isdeleted' => 0
        );
        
        $diaryData = $this->getDairyData($queryParams);
        
        //update etp_job data array
        if (count($diaryData) == 0) {
            $updateJobParams = array( 
                'statusid' => 20
            );
        } else {
           $updateJobParams = array( 
                'statusid' => $jobData['statusid']
            ); 
        }

        //create request array for Chain Process
        $request = array(
            'scheduleParams'  => $scheduleParams, 
            'jobData'         => $jobData,
            'scheduleData'    => $createScheduleData,
            'updateJobParams' => $updateJobParams
        );
        
      
        $CreateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $CreateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function use for delete task
    * @param array $scheduleParams - the $scheduleParams is array of apptid,contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function deleteSchedule($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/UpdateScheduleChain.php');
        require_once( __DIR__.'/../job/chain/UpdateJobChain.php');
        require_once( __DIR__.'/../shared/chain/JobNoteChain.php');
         

         //2 - initialize instances
        $UpdateScheduleChain = new UpdateScheduleChain();
        $UpdateJobChain = new UpdateJobChain();
        $JobNoteChain = new JobNoteChain();
    

        //3 - get the parts connected
        $UpdateScheduleChain->setSuccessor($UpdateJobChain);
        $UpdateJobChain->setSuccessor($JobNoteChain);
        
        //4 - start the process
        $this->LogClass->log('delete Schedule : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $diaryData = $this->getSchedule($scheduleParams['apptid']);
        $jobData = $this->jobClass->getJobById($diaryData[0]['jobid']);
        
       
        $lastupdated1 = date(RAPTOR_DISPLAY_DATEFORMAT.' '.RAPTOR_DISPLAY_TIMEFORMAT, time());
        
        //Update Diary Data
        $updateScheduleData =  array(
            'isdeleted' => 1
        );
        
        $queryParams = array(
            'jobid' => $diaryData[0]['jobid'],
            'isdeleted' => 0
        );
        
        $checkDiaryData = $this->getDairyData($queryParams);

        if (count($checkDiaryData) == 1) {
            if ($diaryData[0]['isinternal'] == 0) {
                //update etp_job data array
                $updateJobParams = array( 
                    'statusid' => 10
                );
            }
            else {
                //update etp_job data array
                $updateJobParams = array( 
                    'statusid' => 100
                );
            }
         
        }
        else{
            $updateJobParams = array(
                'statusid'  => $jobData['statusid']
            );
        }
        
         $notes = 'Task '.$diaryData[0]['apptid'].' deleted by '.$loggedUserData['firstname'].' for Technician '.$diaryData[0]['firstname'].' on '.$lastupdated1;
 
        //Create job notes data array
        $jobNoteData = array(
            'origin'     => 'etp',
            'jobid'      => $diaryData[0]['ownerjobid'],
            'pdate'      => date('Y-m-d H:i:s'),
            'date'       => date('Y-m-d'),
            'notes'      => $notes,
            'ntype'      => 'etp update',
            'notetype'   => 'external',
            'userid'     => $loggedUserData['etp_email'],
            'contactid'  => $loggedUserData['contactid'],
            'supplierid' => $loggedUserData['customerid']
        );
      
        
         
        //create request array for Chain Process
        $request = array(
            'scheduleParams'    => $scheduleParams, 
            'diaryData'         => $diaryData,
            'jobData'           => $jobData,
            'updateScheduleData'=> $updateScheduleData,
            'updateJobParams'   => $updateJobParams,
            'jobNoteData'       => $jobNoteData 
        );
        
      
        $UpdateScheduleChain->handleRequest($request);

        //5 - get inserted id values
        $returnValue = $UpdateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
    
    /**
    * @desc This function Get a labour data for chart
    * @param integer $ownerjobid - owner jobid
    * @return array 
    */
    public function getExtLabourData($ownerjobid) {
        $this->db->select('SUM(duration) as duration, activity');
        $this->db->where('ownerjobid', $ownerjobid);
        $this->db->join('etp_activity a', 'a.id=d.activity_id', 'inner');
        $this->db->from('etp_diary d');
        $this->db->group_by('a.activity');
        $data = $this->db->get()->result_array();
        return $data;
    }
	
	
	
        public function getLabourData($jobid, $contactid='') {
        $this->db->select('SUM(duration)*60 as duration, lm_te_activity as activity');
        $this->db->where('timer_started', 0);
        $this->db->where('lm_te_activity is NOT NULL',NULL,FALSE);
        if($contactid!='') {
            $this->db->where('d.contactid', $contactid);
        }
        $this->db->like('userid','fma');
		
        $this->db->join('lm_timeentry te', 'te.lm_te_apptid=d.apptid', 'inner');
        $this->db->from('diary d');
        $this->db->group_by('te.lm_te_activity');
        $data = $this->db->get()->result_array();
        return $data;
    }
    /**
    * @desc This function use for create FMA Task
    * @param array $scheduleParams - the $scheduleParams is array of apptid, contactid(LoggedUser) 
     * @return bool -  success or failure
    */
    public function createFMATask($scheduleParams)
    {
        //1 - load multiple models
        require_once('chain/CreateScheduleChain.php');

         //2 - initialize instances
        $CreateScheduleChain = new CreateScheduleChain();

        //3 - get the parts connected
        
        //4 - start the process
        $this->LogClass->log('FMA Dashboard Signup Task : ');
        $this->LogClass->log($scheduleParams);
        $loggedUserData = $this->sharedClass->getLoggedUser($scheduleParams['logged_contactid']);
        $diaryData = $scheduleParams['diaryData'];
        
        $startEndTime = array(
            array(
                'start'     => '10:00:00',
                'end'       => '11:00:00',
                'notes'     => $diaryData['notes1']
            ),
            array(
                'start'     => '11:00:00',
                'end'       => '12:00:00',
                'notes'     => $diaryData['notes2']
            ),
            array(
                'start'     => '12:00:00',
                'end'       => '13:00:00',
                'notes'     => $diaryData['notes3']
            )
        );
        
        //Create Internal Task Diary Data
        
        foreach($startEndTime as $value) {
            $createScheduleData =  array(
                'contactid'     => $diaryData['contactid'],
                'jobid'         => $diaryData['jobid'],
                'dte'           => $diaryData['date'],
                'start'         => $value['start'],
                'duration'      => 1,
                'notes'         => $value['notes'],
                'end'           => $value['end'],
                'labelid'       => $diaryData['labelid'],
                'customerid'    => $diaryData['ownercustomerid'],
                'createdby'     => $loggedUserData['contactid'],
                'datecreated'   => date('Y-m-d H:i:s', time()),
                'origin'        => 'FMA',
                'last_updated'  => date('Y-m-d H:i:s', time())
            );

            //create request array for Chain Process
            $request = array(
                'scheduleParams'  => $scheduleParams, 
                'scheduleData'    => $createScheduleData
            );


            $CreateScheduleChain->handleRequest($request);
        }

        //5 - get inserted id values
        $returnValue = $CreateScheduleChain -> returnValue;

        //6 - return the result object
        return $returnValue ;
    }
}


/* End of file ScheduleClass.php */