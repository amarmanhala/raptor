<?php
/**
 * Project: Raptor
 * Package: CI
 * Subpackage: Models
 * File: techmap_model.php
 * Description: This is a tech map  model class
 * Created by : Prakash Saud <saud.prakash@gmail.com>
 *
 */

class Techmap_model extends MY_Model{
	 
    function __construct() {
            parent::__construct();
    }
    
    /**
	 * @desc This function getJobs will return list of jobs array from the jobs table with conditions matching for customerid, contactid, subordinateemails, fromdate, todate
	 * @param  $customerid, $contactid, $subordinateemails, $state, $fromDate, $toDate, $adhoc, $contract used to filter job lists
	 * @return array 
	 */
	public function getJobs($customerId = NULL,$contactId = NULL,$subordinateEmails = NUll,$state = NULL, $fromDate = NULL, $toDate = NULL, $adhoc = NULL, $contract = NULL){
				
		$this->db->select('j.jobid,jrespdate AS attenddate,jcompletedate AS completiondate,duedate,duetime,getJobStatus(jobid) AS STATUS,
		j.siteline2 AS address,j.siteline1,j.sitesuburb AS suburb,j.sitestate AS state,j.sitepostcode AS postcode,j.latitude_decimal,j.longitude_decimal,
		j.userid, j.custordref, j.jobdescription');
		$this->db->from('jobs j');
		
		if($subordinateEmails!=NULL){
                    $this->db->where(array('j.customerid' => $customerId, 'j.contactid' => $contactId));
                    $this->db->or_where_in('j.sitefmemail', $subordinateEmails);

                }elseif($contactId!=NULL){
			$this->db->where(array('j.customerid' => $customerId, 'j.sitecontactid' => $contactId));
		}else{
                    $this->db->where('j.customerid', $customerId);
		}
		//get jobs only if  lat and long is not empty
		$this->db->where('j.latitude_decimal is NOT NULL', NULL, FALSE);
		$this->db->where('j.latitude_decimal !=', 0);
		// if state is selected
		if(!empty($state)){
                    $this->db->where('j.sitestate', $state);
		}
		// if adhoc selected
		if(!empty($adhoc) && empty($contract)){
                    $where = " IFNULL ((IFNULL(j.recurring,'') = 'on' OR j.contractid>0),0) = '0' ";
                    $this->db->where($where);
		}
		//if contract option selected
		if(!empty($contract) && empty($adhoc)){
                    $where = " IFNULL ((IFNULL(j.recurring,'') = 'on' OR j.contractid>0),0) = '1' ";
                    $this->db->where($where);
		}
		// if from date selected
		if(!empty($fromDate)){
                    /* converted date from dd/mm/yy to yy-mm-dd format */
                    $fromDate = substr($fromDate,6,4).'-'.substr($fromDate,3,2).'-'.substr($fromDate,0,2);
                    $this->db->where('j.leaddate >=', $fromDate);
		}
		// if to date selected
		if(!empty($toDate)){
                    /* converted date from dd/mm/yy to yy-mm-dd format */
                    $toDate = substr($toDate,6,4).'-'.substr($toDate,3,2).'-'.substr($toDate,0,2);
                    $this->db->where('j.duedate <=', $toDate);
		}
		$query = $this->db->get();
		return $query->result_array();
		
		
	}
	 
	/**
	 * @desc This function getUserLatestDairyEntry gives a user latest dairy entry having inprogress status on or completed on with lat, long not null
	 * @param  $userId,$checkCompleted 
	 * @return array 
	 */
	 
	public function getUserLatestDairyEntry($userId,$checkCompleted = NULL){
            
            $this->db->select('dte,START,END,inprogress,completed,jobid,lm_te_activity_id,latitude_decimal,longitude_decimal');
            $this->db->from('diary d');
            $this->db->join('lm_timeentry te', 'te.lm_te_apptid = d.apptid', 'inner');
            $this->db->where('userid', $userId);
            $this->db->where('inprogress', 'on');
            if($checkCompleted != NULL){
                    $this->db->or_where('completed', 'on');
            }
            // Filter if dairy lat long empty NULL or 0 
            $this->db->where('latitude_decimal is NOT NULL', NULL, FALSE);
            $this->db->where('latitude_decimal !=', 0);
            $this->db->order_by("dte", "desc"); 
            $this->db->order_by("START", "desc"); 
            $this->db->limit(1);
            $query = $this->db->get();
            return $query->row_array();
	 	
	}
	/**
	 * @desc This function getCurrentTechStatus returns status as (Working, Travelling, Onsite,Offline) of user
	 * @param  $userid - parameter is the id of user for which we need to get status.
	 * @return string
	 */
	public function getCurrentTechStatus($userId){
		$row =  $this->getUserLatestDairyEntry($userId);
		/* Check the return data by function for status with the inprogress field  */
		if($row['inprogress'] == 'on' && $row['lm_te_activity_id'] == NULL){
			$status = 'Working';
		}elseif($row['inprogress'] == 'on' && ($row['lm_te_activity_id'] >= 4 && $row['lm_te_activity_id'] <= 29) ){
			 $status = 'Travelling';
		}else{
			$jobid = $row['jobid'];
			$this->db->select("isTechOnsite('$userId','$jobid') as status");
			$query = $this->db->get();
            $row =  $query->row_array();
		    if($row['status'] == 1){
			   $status = 'Onsite';
		    }else{
				$this->db->select("getOnlineStatus('$userId') as status");
			    $query = $this->db->get();
				$row =  $query->row_array();
				$status = $row['status'];
			}
		}
		//return tech status
		return $status;
        		
	}
	/**
	 * @desc This function getUserLatestLogin gives a user latest login detail from table dbversion_users_login_history as row array
	 * @param  $userId - parameter is the id of user for which we will get login detail.
	 * @return array row 
	 */
	public function getUserLatestLogin($userId){
            $this->db->select('accessdate,latitude_decimal,longitude_decimal');
            $this->db->from('dbversion_users_login_history');
            $this->db->where('userid',$userId);
            $this->db->order_by("accessdate", "desc"); 
            $this->db->limit(1);
            $query = $this->db->get();
            //return tech latest login data as array
            return $query->row_array();
		
		
	}
	/**
	 * @desc This function getEstimatedArrival gives estimated arrival time of tech to latest working site or completed site
	 * @param  $userid 
	 * @return datetime 
	 */
	public function getEstimatedArrival($userId){
            // get the tech latest login location from dbversion_users_login_history table
            $techLocationRow = $this->getUserLatestLogin($userId);
            // get user latest dairy login location
            $techJobRow =  $this->getUserLatestDairyEntry($userId,$checkCompleted = 'yes');
            /* get travel time between the latest login and latest diary login
               if the diary latest login  has a valid latitude and longitude calculate
               using google distance matrix api
            */
            if(!empty($techJobRow) && !empty($techJobRow['latitude_decimal'])){
                    $date = new DateTime();
                    $departureTimeInSeconds  = $date->getTimestamp();
                    $departureTimeInSeconds = $departureTimeInSeconds + 60;
                    $distanceMatrixApi = $this->config->item('google_maps_distance_matrix_api');
                    // pass latitude, longitude and departure time to google distance matrix api
                    $distanceMatrixApi .= $techLocationRow['latitude_decimal'].",".$techLocationRow['longitude_decimal']."&destinations=".
                $techJobRow['latitude_decimal'].",".$techJobRow['longitude_decimal']."&departure_time=".$departureTimeInSeconds.
                "&traffic_model=best_guess&key=".$this->config->item('google_api_key');
                    $json = file_get_contents($distanceMatrixApi);
                    $response = json_decode($json, TRUE);
                    /* Check if the google api return valid response, if its not valid,
                     * there is issue in the diary lat lon or tech latest login location lat long
                    */
                    if(isset($response['rows'][0]['elements'][0]['duration']['value'])){
                            $travelTimeInSeconds = $response['rows'][0]['elements'][0]['duration']['value'];
                        // add the travel time to the current time, which will give site arrival time in seconds
                        $date = new DateTime();
                        $currentTimeInSeconds  = $date->getTimestamp();
                        $timeToArriveSite = $travelTimeInSeconds + $currentTimeInSeconds;
                        $date = new DateTime("@$timeToArriveSite");
                        return  $date->format('H:i:s');
                    }else{
                            return NULL;
                    }


            }else{
                    // return  Null on empty job diary entry
                    return NULL;
            }
		

	}
	
	
	/**
	 * @desc This function getJobsTecList gives distinct tech list for selected jobs with the status, estimated arrival time, status and jobs list
	 * @param  $customerid , $contactid , $subordinateemails 
	 * @return array 
	*/
	
	public function getJobsTecList($jobs = NULL, $customerid = NULL, $contactid = NULL, $subordinateemails = NUll){
		$userIds = array_column($jobs, 'userid');
		$jobIds = array_column($jobs, 'jobid');
		$userIds = array_unique($userIds);
		$userIds = array_filter($userIds);
		$techs = array();
		foreach($userIds as $userId){
			$techStatus = $this->getCurrentTechStatus($userId);
			$arrivalTime = $this->getEstimatedArrival($userId);
			
			$this->db->select("s.firstname, s.surname, s.mobile, s.*,CONCAT((s.firstname),(' '),(s.surname),(' '),('('),(s.mobile),(')')) AS NAME");
			$this->db->from('customer s');
		    $this->db->join('users u', 's.customerid = u.email', 'inner');
			$this->db->where('u.userid', $userId);
			$query = $this->db->get();
			$techNameRow = $query->row_array();
			$techLocationRow = $this->getUserLatestLogin($userId);
			$techJobs = array();
			foreach($jobs as $job){
				if($job['userid'] == $userId){
					$this->db->select('dte,start');
					$this->db->from('diary');
					$this->db->where('jobid',$job['jobid']);
					$query = $this->db->get();
                    $diaryRow = $query->row_array();
					if(empty($diaryRow)){
						$techJobs[] = array('siteline2'=>$job['address'],
									'sitesuburb'=>$job['suburb'],
									'jobid'=>$job['jobid'],
									'dte'=>'',
									'start'=>''
								  );
					}else{
						$techJobs[] = array('siteline2'=>$job['address'],
										'sitesuburb'=>$job['suburb'],
										'jobid'=>$job['jobid'],
										'dte'=>$diaryRow['dte'],
										'start'=>$diaryRow['start']
									  );
					}
					
					
				}
			}
			/* Create array and assign tech detail */
			$techs[] = array(
				            'techstatus'=>$techStatus,
							'arrivaltime'=>$arrivalTime,
							'userid'=>$userId,
							'NAME'=>$techNameRow['NAME'],
							'accessdate'=>$techLocationRow['accessdate'],
							'latitude_decimal'=>$techLocationRow['latitude_decimal'],
							'longitude_decimal'=>$techLocationRow['longitude_decimal'],
							'jobs'=>$techJobs
							
						);
			
		}
		return $techs;
		
	}
		 
    
}
/* End of file Techmap_model.php*/
/* Location: ./application/models/Techmap_model.php */