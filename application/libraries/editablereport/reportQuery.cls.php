<?php
class ReportQuery {
    
    public $masterfunctions;

        public function __construct()
        {
            $this->masterfunctions = new masterfunctionsreturn();
        }
        
	function qlog($string){
		
	  $file = 'C:/temp/reportdebug.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
	  fclose($fh);
}
	function getScopeAreas($reportid){
	   $this->qlog("Enter getScopeAreas: $reportid");	
	   $q = "SELECT DISTINCT scope_area_name FROM INS_SCOPE WHERE ins_sitereport_id=$reportid";

	   $this->qlog($q);	
	   $tf = $this->kda("scope_area_name");
	   $da = $this->masterfunctions->iqa($q,$tf);
	   
	   return $da;		
	
	}	
	function getAreas($reportid){
	   $this->qlog("Enter getAreas: $reportid");	
	   $q = "SELECT b.id,b.name  FROM rep_area a INNER JOIN rep_area_type b ON a.area_type_id=b.id 
	   INNER JOIN ins_sitereport c ON a.report_type_id=c.report_type_id
	   WHERE c.ins_sitereport_id=$reportid order by a.sortorder";

	   $this->qlog($q);	
	   $tf = $this->kda("id,name");
	   $da = $this->masterfunctions->iqa($q,$tf);
	   
	   return $da;	
	}
	
	function getAreaGuids($reportid){
	   $this->qlog("Enter getAreaGuids: $reportid");	
	   $q = "SELECT b.id,b.name  FROM rep_area a INNER JOIN rep_area_type b ON a.area_type_id=b.id 
	   INNER JOIN ins_sitereport c ON a.report_type_id=c.report_type_id
	   WHERE c.ins_sitereport_id=$reportid order by a.sortorder";

	   $q="SELECT  b.areatype_id, b.guid, b.area_description as name,sortorder
		FROM ins_sitereport a
		LEFT JOIN rep_area_value b ON (a.ins_sitereport_id=b.report_id)
		INNER JOIN rep_area c ON c.area_type_id=b.areatype_id
		WHERE a.ins_sitereport_id=$reportid AND isdeleted=0 ORDER BY sortorder,AREA_description;";

		$q="SELECT e.guid, e.area_description AS name,sortorder 
		FROM rep_report_areatypetopic_value a
		INNER JOIN rep_area_type b ON a.area_type_id=b.id
		INNER JOIN rep_area d ON a.area_type_id = d.area_type_id
		INNER JOIN rep_area_value e ON e.guid=areavalue_guid
		WHERE a.report_id=$reportid
		GROUP BY areavalue_guid
		ORDER BY d.sortorder,e.area_description"	;



	   $this->qlog($q);	
	   $tf = $this->kda("guid,name");
	   $da = $this->masterfunctions->iqa($q,$tf);
	   
	   return $da;	
	}
	function getComments($reportid){
	  $this->qlog("Enter getComments: $reportid");	
		
	  $q = "SELECT c.name AS topic,a.description as comments FROM rep_report_areatypetopic_value a
	  INNER JOIN rep_area_type b ON a.area_type_id=b.id
	  INNER JOIN rep_area_topic c ON a.area_topic_id=c.id
	  WHERE report_id=$reportid AND b.name = 'General Comments'";
	  
	  $this->qlog($q);	
	  $tf = $this->kda("topic,comments");
	  $da = $this->masterfunctions->iqa($q,$tf);
		
	  return $da;	
		
	}
	
	function getAreaTopicValue($reportid,$area,$topic){
	   $this->qlog("Enter AreaTopicValue: $reportid $area $topic");	
		
		$q = "SELECT a.description FROM rep_report_areatypetopic_value a
		INNER JOIN rep_area_type b ON a.area_type_id=b.id
		INNER JOIN rep_area_topic c ON a.area_topic_id=c.id
		WHERE report_id=$reportid AND b.name = '$area' AND c.name = '$topic'";
		
		$this->qlog($q);	
	    $tf = $this->kda("description");
	    $da = $this->masterfunctions->iqa($q,$tf);
		
	    return $da;	
		
		
		
	}
	function getAreaRatings($reportid) {
	  $this->qlog("Enter getAreaRatings: $reportid");	
	  
	  $q = "SELECT a.report_id,b.name AS area_name,AVG(a.dirty_rating) AS av_dirty_rating, AVG(a.damaged_rating) AS av_damaged_rating FROM rep_report_areatypetopic_value a
			INNER JOIN rep_area_type b ON a.area_type_id=b.id
			INNER JOIN rep_area d on a.area_type_id = d.area_type_id
			WHERE a.isdeleted=0 AND report_id=$reportid	AND a.dirty_rating>0	
			GROUP BY area_name 
			ORDER BY d.sortorder";	
		
		$this->qlog($q);
		
		$tf = $this->kda("area_name,av_dirty_rating,av_damaged_rating");
		
		$da = $this->masterfunctions->iqa($q,$tf);
		
		return $da;
		
	}

	function getAreaRatingsByGuid($reportid) {
	  $this->qlog("Enter getAreaRatings: $reportid");	
	  
	  $q = "SELECT a.report_id,AVG(dirty_rating) AS av_dirty_rating,AVG(damaged_rating) AS av_damaged_rating, e.area_description AS area_description,b.name as area_name,areavalue_guid ,d.sortorder FROM rep_report_areatypetopic_value a
		INNER JOIN rep_area_type b ON a.area_type_id=b.id
		INNER JOIN rep_area d ON a.area_type_id = d.area_type_id
		INNER JOIN rep_area_value e ON e.guid=areavalue_guid
		WHERE a.report_id=$reportid and e.isdeleted=0
		GROUP BY areavalue_guid
		HAVING AVG(dirty_rating) != 0
		ORDER BY d.sortorder,e.area_description			
			";	
		
		$this->qlog($q);
		
		$tf = $this->kda("area_name,area_description,av_dirty_rating,av_damaged_rating,areavalue_guid");
		
		$da = $this->masterfunctions->iqa($q,$tf);
		
		return $da;
		
	}
	function getAreaResults($reportid,$areaid){
		$this->qlog("Enter getAreaResults: ".$reportid."  Area:".$areaid);	
		
		$q = "SELECT b.name as area_name,c.name as topic_name,a.ynna,a.dirty,a.damaged,a.dirty_rating,a.description,a.remedial_action FROM rep_report_areatypetopic_value a
		INNER JOIN rep_area_type b ON a.area_type_id=b.id
		INNER JOIN rep_area_topic c ON a.area_topic_id=c.id
		INNER JOIn rep_areatype_areatopic d on d.areatype_id=b.id and d.areatopic_id = c.id
		INNER JOIN ins_sitereport e ON e.report_type_id = d.reporttype_id AND e.ins_sitereport_id=a.report_id
		WHERE a.isdeleted=0 AND e.ins_sitereport_id=$reportid AND b.id=$areaid ORDER BY d.sortorder";

		
		$this->qlog($q);
		
		$tf = $this->kda("area_name,topic_name,ynna,dirty,dirty_rating,damaged,damaged_rating,description,remedial_action");
		
		$da = $this->masterfunctions->iqa($q,$tf);
		
		return $da;
		
	}	
	
	
	function getAreaGuidResults($reportid,$guid){
		$this->qlog("Enter getAreaGuidResults: ".$reportid."  Area:".$guid);	
		
		
			$q = "SELECT b.name AS area_name,c.name AS topic_name,a.ynna, a.dirty,a.damaged,a.dirty_rating,a.description,a.remedial_action FROM rep_report_areatypetopic_value a
		INNER JOIN rep_area_type b ON a.area_type_id=b.id
		INNER JOIN rep_area_topic c ON a.area_topic_id=c.id
		WHERE report_id=$reportid AND a.areavalue_guid='$guid'";

		
		$this->qlog($q);
		
		$tf = $this->kda("area_name,topic_name,ynna,dirty,dirty_rating,damaged,damaged_rating,description,remedial_action");
		
		$da = $this->masterfunctions->iqa($q,$tf);
		
		return $da;
		
	}
        
        function kda($raw){
            $da=explode(",",$raw);
            $ka=$this->key2val($da);
            return $ka;
           }

           function key2val($raw,$lcase=null){
            if(is_array($raw)){
                   $fv=array_values($raw);
                    foreach($fv as $v){
                     if($lcase=="lcase") $v=strtolower($v);
                     $tfields[$v]=$v;
                    }
                    return $tfields;
            }
           }
	
	
	
}

?>