<?php
session_start();
$basepath=$_SESSION["basepath"];
$clientpath=$_SESSION["clientpath"];
include "$clientpath/ii/iinc.php";

include_once "$basepath/crmDBAccess.php";

include_once "jtpdf.php";
include_once "reportQuery.cls.php";
require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/excelReport.class.php");
//require "C:\\Program Files (x86)\\Apache Software Foundation\\Apache2.2\\htdocs\\fpdf\\fpdf.php";


error_reporting(0);
//ini_set('display_errors','On');
//ini_set('display_startup_errors','on');

/** handle action mode **/
$mode = $aS->paramsA['mode'];
logit("sitereport mode " .$mode);


function logIt($string){

	  $file = 'C:/temp/reportdebug.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
	  fclose($fh);
}

switch($mode){
	
	case "link":
		logit("Link Report");
		$jobid = $aS->paramsA['jobid'];
		$linkreportid = $aS->paramsA['linkreportid'];
		logit("jobid: $jobid reportid: $linkreportid");
		#$q = format("Update jobs set rep_parent_siterepid = {0}, jobtype='report' where jobid = {1}",$linkreportid,$jobid);
		#logit($q);
		
		$jobA['jobtype'] = "report";
		$jobA['rep_parent_siterepid'] = $linkreportid;
		pau("jobs",$jobA,"jobid",$jobid);
		
		$testid = singlevalue("jobs","rep_parent_siterepid","jobid",$jobid);
		logit("testid: $testid");
		if($testid == $linkreportid){
			echo "SUCCESS";
		} else {
			echo "FAIL";
		}
		break;
		
	case "generate":
		
		$jobid = $aS->paramsA['jobid'];
		$reportid = $aS->paramsA['reportid'];

		logit("jobid:$jobid");
		logit("reportid:$reportid");
		
		$q = "SELECT name,reportFolder,filespec FROM rep_reporttype rt INNER JOIN ins_sitereport ir ON ir.report_type_id=rt.id WHERE ins_sitereport_id=$reportid";
		logit($q);
	  	$da = iqasra($q);
  	  	$subfolder = $da["reportFolder"];
		$reporttype = $da["name"];
		
		logit("Reporttype: $reporttype");

		switch($reporttype){
				
			
		case "jobReport":
			JobReport($aS,$jobid);
		break;
		
		case "Maintenance Check":
			logit("Site Report - $reporttype");	
			ConditionReport($aS,$jobid,$reportid,"Maintenance Report");
		
		break;

		case "SiteReport":
			logit("Site Report - $reporttype");	
			switch ($reporttype)
			{
				case "Air Services Property Inspection":
				  logit("Site Report - $reporttype");	
				break;	
				default:	
				  SiteReport($aS,$reportid,$jobid);
 			}
   		break;

		case "LeakReport":
			LeakReport($aS,$jobid,$reportid);
   		break;
	
		Case "Claim Central":
			ClaimCentralReport($aS,$jobid,$reportid);
		break;	
		case "Air Services":
			AirServicesReport($aS,$jobid,$reportid);
	    break;
	
		case "PropertyInspectionReport":
			PropertyInspectionReport($aS,$jobid,$reportid);
		break;

		case "ErgonPropertyInspectionReport":
			ErgonPropertyInspectionReport($aS,$jobid,$reportid);
		break;
		
		case "DTZCUBInspectionReport":
			DTZPropertyInspectionReport($aS,$jobid,$reportid);
		break;

		case "OfficeAssetsReport":
			OfficeAssetsReport($aS,$jobid,$reportid);
		break;

		case "AssetInspectionReport":
			PropertyInspectionReport($aS,$jobid,$reportid);
		break;
				
		default:
			logit("$reporttype not implemented for SiteReport.generate");
		break;
	}
	break;

	case "approve":
		$reportid = $aS->paramsA['reportid'];
		$jobid = $aS->paramsA['jobid'];

		$q = "SELECT rt.* FROM ins_sitereport IR INNER JOIN rep_reporttype rt ON rt.id = ir.report_type_id WHERE ins_sitereport_id = $reportid";
		
		$reporttype = iqasrf($q,"reportFolder");
		
		$doc['documentdesc']='Report';
		$doc['doctype'] = $reporttype;
		$doc['docformat'] = 'pdf';
		$doc['dateadded'] = date("Y-m-d");
		$doc['userid'] = $_SESSION['userid'];
		$doc['xrefid'] = $jobid;
		$doc['xreftable'] = "jobs";
		$doc['docname'] = $jobid."_".$reportid.".pdf";
		$doc['approved'] = "1";
		$doc['origin'] = "JT";
		$doc['docfolder'] = "Reports";
		
		$docid = pai("document",$doc);
		
		$rep["is_approved"] = "on";
		pau("ins_sitereport",$rep,"ins_sitereport_id",$reportid);
		
		logit("reporttype: $reporttype");
		
		$sourcepath = getReportFile($jobid,$reportid);
		
		$targetpath = $_SERVER['DOCUMENT_ROOT']."\\infomaniacDocs\\jobdocs\\".$docid.".pdf";
		logit("docid $docid");
		logit("sourcepath: ".$sourcepath);
		logit("targetpath: ".$targetpath);
		
		if(is_readable($sourcepath)) {
			copy($sourcepath,$targetpath);
			
			$jn['jobid'] = $jobid;
			$jn['date'] = date('Y-m-d');
			$jn['userid'] = $_SESSION['userid'];
			$jn['notes'] = "Report id: $reportid approved for client portal. Docid: $docid";
			$jn['notetype'] = "Internal";
			$jn['ntype'] = "Report";
			pai("jobnote",$jn);
			
		    return "TRUE";
		} else {
			logit("Could not copy file");
		}
		
		break;
	case "saveimagedescription":
		$desc = $aS->paramsA['desc'];
		$docid = $aS->paramsA['docid'];
		logit("descripion: $desc");
		logit("docid: $docid");
		$doc["documentdesc"] = $desc;
		pau("document",$doc,"documentid",$docid);

		break;
	case "saveimageall":
		$desc = $aS->paramsA['desc'];
		$docid = $aS->paramsA['docid'];
		$caption = $aS->paramsA['caption'];
		logit("descripion: $desc");
		logit("docid: $docid");
		$doc["documentdesc"] = $caption;
		$doc["docnote"] = $desc;
		pau("document",$doc,"documentid",$docid);

		break;
	case "email":
		logit("Email report");

		$jobid = $aS->paramsA['jobid'];
		$reportid = $aS->paramsA['reportid'];

		$reporttype = singlevalue("ins_sitereport","report_type","ins_sitereport_id",$reportid);

		//todo customsie emal for job report...
		switch($reporttype){
			case "Job Report":
				$subject = "Job Report for DCFM Job $jobid";
		        $message = "Please find job report attached for DCFM Job $jobid";
		        $file = getReportFile($jobid,$reportid);

			break;
			case "Inspection":
				$subject = "Inspection Report for DCFM Job $jobid";
		        $message = "Please find inspection report attached for DCFM Job $jobid";
		        $file = getReportFile($jobid,$reportid);

			break;
			case "Insurance":
				$subject = "Insurance Report for DCFM Job $jobid";
		        $message = "Please find insurance report attached for DCFM Job $jobid";
		        $file = getReportFile($jobid,$reportid);

			break;
			case "AirServices":
	
				$subject = "Insurance Report for DCFM Job $jobid";
		        $message = "Please find insurance report attached for DCFM Job $jobid";
		        $file = getReportFile($jobid,$reportid);
			
			break;
			default:
				$subject = "Job Report for DCFM Job $jobid";
		        $message = "Please find job report attached for DCFM Job $jobid";
		        $file = getReportFile($jobid,$reportid);
				break;
		}

		$recipients = "ankitchen@gmail.com";
		$att[0]["dname"]= $file;
		$att[0]["fname"]= $file;
		$att[0]["relpath"] = "../../infomaniacDocs/reports";

		$rx = emailReport($aS, $subject, $message, $recipients, $att);
		return $rx;
		break;

	case "preview":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		$reporttype = getReportType($reportid);
		
		logit("Sitereport.preview");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");
		logit("report type: $reporttype");

			logit("Site Report - $reporttype");	
			switch ($reporttype)
			{
				case "Air Services Property Inspection":
				  logit("Excel Site Report - $reporttype");
				  
				  //error_reporting('ALL');
				  //ini_set('display_errors','On');
	 			  $report = new excelReport(AppType::JobTracker,$_SESSION['userid']);
	 			  $documentid = $report->run($reportid);
				  logit("report result: $documentid");
				  echo "Report added as document $documentid";
				  
				  error_reporting(0);	
				break;	
				
				case "Maintenance Check":
					logit("Site Report - $reporttype");	
					ConditionReport($aS,$jobid,$reportid,"Maintenance Report");
		
					break;
				
				
				
				
				default:	
				  error_reporting(0);	
	
				  SiteReport($aS,$reportid,$jobid);
				  $file = getReportFile($jobid,$reportid);
				  
 			}
		
		

		
		break;
	case "jobReport":
		$jobid = trim($aS->paramsA['jobid']);
		$reportid = trim($aS->paramsA['reportid']);
		
		logIt("Reportid: $reportid");
		logIt("Jobid: $jobid");

		JobReport($aS,$jobid);
		break;

	case "SiteReport":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Site Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");

		SiteReport($aS,$reportid,$jobid);
			
	   break;

	case "LeakReport":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Leak Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");

		LeakReport($aS,$jobid,$reportid);
			
	   break;
	
	case "AirServices":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Air Services Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");

		AirServicesReport($aS,$jobid,$reportid);
			
	   break;
	
	case "PropertyInspectionReport":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Property Inspection Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");


		$filename = PropertyInspectionReport($aS,$jobid,$reportid);
		
		break;
	
	case "ErgonPropertyInspectionReport":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Property Inspection Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");


		ErgonPropertyInspectionReport($aS,$jobid,$reportid);
		break;

	case "OfficeAssetsReport":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Property Inspection Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");

		OfficeAssetsReport($aS,$jobid,$reportid);
		
	break;
			
	case "DTZCUBInspectionReport":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("DTZ/CUB Inspection Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");
		DTZPropertyInspectionReport($aS,$jobid,$reportid);
	break;

			
			
	case "AssetInspection":
		$reportid = trim($aS->paramsA['reportid']);
		$jobid = trim($aS->paramsA['jobid']);
		logit("Property Inspection Report");
		logit("Reportid: $reportid");
		logit("Jobid: $jobid");


		OfficeAssetsReport($aS,$jobid,$reportid);

	break;

	default:
		logit("mode $mode not implemented.");	
	
}
	function getReportType($reportid){
		
		$q = "SELECT name FROM rep_reporttype rt INNER JOIN ins_sitereport ir ON ir.report_type_id=rt.id WHERE ins_sitereport_id=$reportid";
		logit($q);
		$tf = kda("name");
		$da = iqa($q,$tf);
		
		foreach($da as $i=>$row){
			$reporttype = $row['name'];
			logit("reporttype: $reporttype");
			return $reporttype;			
		}

		return "";


	}
	function getReportFile($jobid,$reportid){
	logit("Enter getReportFile $jobid $reportid");
	  $folder = "../../InfomaniacDocs/reports";
	  $reportfile = "";
	  if ($reportid==""){
	  	$subfolder = "JobReports";
	  	$reportfile = $folder. "\\". $subfolder."\\".$jobid.".pdf";
	  	return $reportfile;	
 
	  }
 	
	  $q = "SELECT reportFolder,filespec FROM rep_reporttype rt INNER JOIN ins_sitereport ir ON ir.report_type_id=rt.id WHERE ins_sitereport_id=$reportid";
	  $tf = kda("reportFolder,filespec");
	  $da = iqa($q,$tf);
	  foreach ($da as $i=>$row)
	  {
  	  $subfolder = $row["reportFolder"];
	  $filespec = $row["filespec"];
	  }
	  logit($q);
	  logit("folder: $subfolder");
	  logit("filespec: $filespec");
	  
	 
	  switch($filespec){
	  	case "%jobid_%reportid":
	    	$reportfile = $folder. "\\". $subfolder."\\".$jobid."_".$reportid.".pdf";
	    	break;
	  	case "%jobid":
	    	$reportfile = $folder. "\\". $subfolder."\\".$jobid.".pdf";
	    	break;
		default:
	    	$reportfile = $folder. "\\". $subfolder."\\".$jobid.".pdf";
	  }
	  
	  return $reportfile;
	}
	
	function getLeakReportFile($jobid,$reportid)
	{
	  $folder = "../../InfomaniacDocs/reports/Leakreports/";
 	  $reportfile = $folder.$jobid."_".$reportid.".pdf";
	  return $reportfile;
	}

	function JobReport($aS,$jobid)
	{
	  $reportfile = getReportFile($jobid,"");
	  $docfolder = "../../InfomaniacDocs/jobdocs/";

	  if (file_exists($reportfile))
	    unlink($reportfile);

	  logit("Jobreport file: $reportfile");

	  $pdf = new JTPDF();
	  $pdf->pageBanner = "Job Report";


	  $pdf->AliasNbPages();
	  $pdf->AddPage();
	  $pdf->SetFont('Arial','',12);

	  logit("99");

	  $h = 10;
	  $width = 200;
	  $firstline = 40;

	  $job = iqatr('jobs','jobid',$jobid);
	  $custid = $job['customerid'];
	  logit("Custid: $custid");

	  $clientname = singlevalue('customer','companyname','customerid',$custid);
	  logit("Jobs:".count($job));
	  logit("Client: ".$clientname);

	  $workorder = $job['custordref'];
	  $fmname = $job['sitefm'];
	  $siteAddress = $job['siteline2'] ."||".$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	  $jobDescription = $job['jobdescription'];
	  $dueDate = $job['duedate'];
	  $completeDate = $job["jcompletedate"];
	  $siteContact = $job["sitecontact"];
	  $attendDate = $job["jrespdate"];

	$firstline = 40;
		logit("158");
	  $pdf->DrawField(0,$pdf->getY_custom($firstline,0),$h,"Client Name",0.3,'B',':',$clientname,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,1),$h,"Client Work Order",0.3,'B',':',$workorder,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,2),$h,"DCFM Job No",0.3,'B',':',$jobid,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,3),$h,"Facility Manager",0.3,'B',':',$fmname,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,4),$h,"Site Address",0.3,'B',':',$siteAddress,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,6),$h,"Due Date",0.3,'B',':',$dueDate,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,7),$h,"Attend Date",0.3,'B',':',$attendDate,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,8),$h,"Completion Date",0.3,'B',':',$completeDate,'',$width);
	  $pdf->DrawField(0,$pdf->getY($firstline,9),$h,"Site Contact",0.3,'B',':',$siteContact,'',$width);


	  $liney = 95;
	  $pdf->Line(10,$liney,195,$liney);

	  $pdf->DrawField(0,96,$h,"Job Description",0.3,'BU',':','','',$width);
	  $pdf->Ln(5);
	  
	 // $header =  array("Job Description");
	 //$Columns[] = array("180","L");
	  //$Data[] = array($jobDescription);
	  
	  //$pdf->DrawTable($header, $Columns, $Data);
	  
	  $desclines = $pdf->NbLines($width, $jobDescription);
	  logit("528: $desclines");
	  
	  $maxlines=27;
	  //Forget about page breaks here. Just switching off the banner.
	  if ($desclines >50000){
	  	   $s=str_replace("\r", '', $jobDescription);
    	   $nb=strlen($s);
		   $linecount=0;
		   $i=0;
		   $startpos=0;
		   $pagecount = 0;
		    while($i<$nb)
    		{
        		$c=$s[$i];
            	$i++;
        		if($c=="\n")
        		{
 				  $linecount++;
				  if ($linecount==$maxlines){
				  	$currenty = $pdf->getY();
				  	logit("maxlines reached $linecount sp:$startpos  i:$i pagecount: $pagecount  currenty: $currenty");
				
				    $pdf->MultiCell(180,6,substr($s, $startpos,$i),0,"L");
					
					logit(substr($s, $startpos,$i));
					
				    $linecount=0;
					$startpos = $i;
					$pdf->AddPage();
					$pdf->drawHeaderLines = false;
					$pagecount++;
					
					if ($pagecount>0) {
						$pdf->setY($firstline)+ 20;
						$maxlines = 35;
					}	 
					//$pdf->Ln($pagecount*20 + 5);
				  }
				}
          
            }
			$pdf->MultiCell(180,6,substr($s, $startpos,$i),0,"L");
	  } else {
	  	$pdf->drawHeaderLines = false;
	  	$pdf->MultiCell(180,5,$jobDescription,0,"L");
	  }	

	  $pdf->Ln(5);
	  $pdf->drawHeaderLines = true;
	 //$pdf->AddPage();

	//========================================================================================
	// PICTURES
	//========================================================================================
	$pdf->AddJobPhotos(50,$jobid);
	$pdf->AddPreworksPhotos(50,$jobid);

logit("Output: $reportfile");
	$pdf->Output($reportfile,'F');
logit("After Output");

	$q = "SELECT COUNT(*) as reportcount from ins_sitereport where jobid = $jobid and report_type='Job Report'";
logit($q);
	$tf = kda("reportcount");
	$darep = iqasra($q,$tf);

logit("count: ".$darep['reportcount']);

	if ($darep["reportcount"]==0){
	
	$reporttype = singlevalue("rep_reporttype","id","name","Job Report");

	$rep["inspection_Date"]=$attendDate;
	$rep["report_type"]="Job Report";
	$rep["notes"] = "Job Report";
	$rep["jobid"] = $jobid;
	$rep["inspection_date"] = date("Y-m-d");
	$rep["create_date"] = date("Y-m-d H:i:s");
	$rep["modify_date"] = date("Y-m-d H:i:s");
	$rep["create_user"] = $_SESSION["userid"];
	$rep["modify_user"] = $_SESSION["userid"];
	$rep["report_type_id"] = $reporttype;

	$reportid = pai("ins_sitereport",$rep);
		
	}
		return $reportfile;


	}



	function LeakReport($aS,$jobid,$reportid){
	$reportfile = getLeakReportFile($jobid,$reportid);

	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter LeakReport");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("Leakreport file: $reportfile");

	$pdf = new JTPDF();
	$pdf->reportCaption = "Leak Detection Report";
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 200;
	$firstline = 40;

	$report = iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$job = iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	logit("Custid: $custid");

	$clientname = singlevalue('customer','companyname','customerid',$custid);
	logit("Jobs:".count($job));
	logit("Client: ".$clientname);


	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$siteAddress = $job['siteline2'] ."||".$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$inspectionDate = $report['inspection_Date'].' '.$report['inspection_time'];
	$incidentDate = $report['event_date'];
	$makeSafeReqd = $report['is_make_safe_reqd']='0' ? 'NO' : 'YES';
	$makeSafeActioned = $report['is_make_safe_actioned']='0' ? 'NO' : 'YES';
	$makeSafeActionedBy = $report['make_safe_contact'];
		
	$pdf->DrawField(0,$pdf->getY($firstline,0),$h,"Client Name",0.3,'B',':',$clientname,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,1),$h,"Client Work Order",0.3,'B',':',$workorder,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,2),$h,"DCFM Job No",0.3,'B',':',$jobid,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,3),$h,"Facility Manager",0.3,'B',':',$fmname,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,4),$h,"Site Address",0.3,'B',':',$siteAddress,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,6),$h,"Inspection Date",0.3,'B',':',$inspectionDate,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,7),$h,"Incident Date",0.3,'B',':',$incidentDate,'',$width);
		
		$reportfile = getReportFile($jobid,$reportid);
	    $docfolder = "../../InfomaniacDocs/jobdocs/";
	    $firstline = 40;
	
	}
	function DTZPropertyInspectionReport($aS,$jobid,$reportid){
	$reportfile = getReportFile($jobid,$reportid);
	
	$docFolder = "../../InfomaniacDocs/jobdocs/";
		
	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter DTZ Property inspection Report");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("DTZ Property inspection report file: $reportfile");

	$pdf = new JTPDF();
	$pdf->pageBanner = "Property Inspection Checklist";
	$inspectiondate = singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
	$siteaddress = $pdf->getSiteAddress($jobid);
	$pdf->footercaption = "Property: $siteaddress  Date: $inspectiondate  - ";
		
	#====================================================
	# Page 1: Inspection Details
	# Page 2: Comments
	# Page 3: Section Reports
	# Page 4: Before/After Photos
	#====================================================
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 200;
	$firstline = 40;

	$pdf->AssetReportCoverPage($jobid,$reportid);	
		
	#individual area results
	$pdf->InspectionAreaResults($jobid,$reportid,false,true);
	
	#General Site Photos
	#$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);
	$ypos = 45;
	#$pdf->AddJobPhotos($ypos,$jobid); 
		
	#Before and After Photos
	#$pdf->AddBeforeAfterPhotos($pdf,$firstline,$jobid,"BEFORE AND AFTER SITE PHOTOS",$docFolder);
	logit("Do output");
			
	$pdf->Output($reportfile,'F');
	logit("After Output to $reportfile");
	return $reportfile;

		
	}

	function OfficeAssetsReport($aS,$jobid,$reportid){
	$reportfile = getReportFile($jobid,$reportid);
	
	$docFolder = "../../InfomaniacDocs/jobdocs/";
		
	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter Office Assets Report");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("Office Assets report file: $reportfile");

	$pdf = new JTPDF();
	$pdf->pageBanner = "Office Asset Inspection Report";
	$inspectiondate = singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
	$siteaddress = $pdf->getSiteAddress($jobid);
	$pdf->footercaption = "Property: $siteaddress  Date: $inspectiondate  - ";
		
	#====================================================
	# Page 1: Inspection Details
	# Page 2: Comments
	# Page 3: Section Reports
	# Page 4: Before/After Photos
	#====================================================
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 200;
	$firstline = 40;

	$pdf->AssetReportCoverPage($jobid,$reportid);	
		
	#individual area results
	$pdf->InspectionAreaResults($jobid,$reportid,false,true);
	
	#General Site Photos
	#$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);
	$ypos = 45;
	#$pdf->AddJobPhotos($ypos,$jobid); 
		
	#Before and After Photos
	#$pdf->AddBeforeAfterPhotos($pdf,$firstline,$jobid,"BEFORE AND AFTER SITE PHOTOS",$docFolder);
	logit("Do output");
	
			
	$pdf->Output($reportfile,'F');
	logit("After Output to $reportfile");
	return $reportfile;

		
	}


	function PropertyInspectionReport($aS,$jobid,$reportid){
	$reportfile = getReportFile($jobid,$reportid);
	$docFolder = "../../InfomaniacDocs/jobdocs/";
		
	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter Property Inspection Report");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("Property Inspection report file: $reportfile");

	$pdf = new JTPDF();
	$pdf->pageBanner = "Residential Inspection Report";
	$inspectiondate = singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
	$siteaddress = $pdf->getSiteAddress($jobid);
	$pdf->footercaption = "Property: $siteaddress  Date: $inspectiondate  - ";
	
	#====================================================
	# Page 1: Inspection Details
	# Page 2: Comments
	# Page 3: Section Reports
	# Page 4: Before/After Photos
	#====================================================
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 200;
	$firstline = 40;

	$pdf->InspectionReportCoverPage($jobid,$reportid);

	#comments Page
	$pdf->InspectionComments($reportid);
	
	#individual area results
	$pdf->InspectionAreaResults($jobid,$reportid,false);
	
	#General Site Photos
	#$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);
	$ypos = 45;
	#$pdf->AddJobPhotos($ypos,$jobid); 
		
	#Before and After Photos
	#$pdf->AddBeforeAfterPhotos($pdf,$firstline,$jobid,"BEFORE AND AFTER SITE PHOTOS",$docFolder);
	logit("Do output");
			
	$pdf->Output($reportfile,'F');
	logit("After Output to $reportfile");
	return $reportfile;
		
	}
	function ErgonPropertyInspectionReport($aS,$jobid,$reportid){
	$reportfile = getReportFile($jobid,$reportid);
	$docFolder = "../../InfomaniacDocs/jobdocs/";
		
	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter Property Inspection Report");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("Ergon Property Inspection report file: $reportfile");

	$pdf = new JTPDF();
	$inspectiondate = singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
	$siteaddress = $pdf->getSiteAddress($jobid);
	$pdf->pageBanner = "Ergon Property Inspection Report - $siteaddress";
	$pdf->footercaption = "Property: $siteaddress  Date: $inspectiondate  - ";
	
	#====================================================
	# Page 1: Inspection Details
	# Page 2: Comments
	# Page 3: Section Reports
	# Page 4: Before/After Photos
	#====================================================
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 198;
	$firstline = 40;

	$pdf->ErgonInspectionReportCoverPage($jobid,$reportid);

	#comments Page
	$pdf->InspectionComments($reportid);
	
	#Asset data
	$pdf->ConditionSummary($reportid);
	
	#individual area results
	$pdf->InspectionAreaResultsByGuid($jobid,$reportid,TRUE,FALSE);
	
	#General Site Photos
	#$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);
	$ypos = 45;
	#$pdf->AddJobPhotos($ypos,$jobid); 
		
	#Before and After Photos
	#$pdf->AddBeforeAfterPhotos($pdf,$firstline,$jobid,"BEFORE AND AFTER SITE PHOTOS",$docFolder);
	logit("Do output");
			
	$pdf->Output($reportfile,'F');
	logit("After Output to $reportfile");
	return $reportfile;
		
	}
	function ClaimCentralReport($aS,$jobid,$reportid){
						
	$reportfile = getReportFile($jobid,$reportid);
	$docFolder = "../../InfomaniacDocs/jobdocs/";
		
	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter claim Central Report");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("claim Central report file: $reportfile");

	$pdf = new JTPDF();
	$pdf->pageBanner = "Insurance Makesafe Report";
	$inspectiondate = singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
	$siteaddress = $pdf->getSiteAddress($jobid);
	$pdf->footercaption = "Property: $siteaddress  Date: $inspectiondate  - ";
	
	#====================================================
	# Page 1: Inspection Details
	# Page 2: Comments
	# Page 3: Section Reports
	# Page 4: Before/After Photos
	#====================================================
	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 200;
	$firstline = 40;

	$pdf->MakeSafeReportCoverPage($jobid,$reportid);

		#individual area results
	$pdf->AreaResultsAndPhotos($jobid,$reportid);
	
	#General Site Photos
	#$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);
	#$ypos = 45;
	#$pdf->AddJobPhotos($ypos,$jobid); 
		
	#Before and After Photos
	#$pdf->AddBeforeAfterPhotos($pdf,$firstline,$jobid,"BEFORE AND AFTER SITE PHOTOS",$docFolder);
	
			
	$pdf->Output($reportfile,'F');
	logit("After Output to $reportfile");
	return $reportfile;					
					
				
			
		
	}
	function AirServicesReport($aS,$jobid,$reportid){
	$reportfile = getReportFile($jobid,$reportid);
	
	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter AirServicesReport");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("AirServices report file: $reportfile");

	$pdf = new JTPDF();
	$pdf->pageBanner = "Air Services Report";
	$pdf->pageOrientation = PageOrientation::Landscape;
	$pdf->AliasNbPages();
	$pdf->newPage("L");
	$pdf->SetFont('Arial','',12);
		
	$h = 10;
	$width = 200;
	$firstline = 40;

	$report = iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$job = iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	logit("Custid: $custid");

	$clientname = singlevalue('customer','companyname','customerid',$custid);
	logit("Jobs:".count($job));
	logit("Client: ".$clientname);


	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$siteAddress = $job['siteline1'] ."||".$job['siteline2'] ."||".$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$inspectionDate = $report['inspection_date'].' '.$report['inspection_time'];
	$siteGPS = "(".$job['latitude_decimal']." , ".$job['longitude_decimal'].")";
	
	$userid = $report['create_user'];
	$supplierid = singlevalue('users','email','userid',$userid);
	$techname = singlevalue("customer","companyname","customerid",$supplierid);
	$tech = $userid." ($techname)";
	logit("Workorder:".$workorder);
	
	$pdf->DrawField(0,$pdf->getY($firstline,0),$h,"Client Name",0.3,'B',':',$clientname,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,1),$h,"Client Work Order",0.3,'B',':',$workorder,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,2),$h,"DCFM Job No",0.3,'B',':',$jobid,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,3),$h,"Facility Manager",0.3,'B',':',$fmname,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,4),$h,"Site Address",0.3,'B',':',$siteAddress,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,6),$h,"GPS Location",0.3,'B',':',$siteGPS,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,7),$h,"Inspection Date",0.3,'B',':',$inspectionDate,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,8),$h,"Inspected By",0.3,'B',':',$tech,'',$width);
	logit("After fields");
	$ypos = $pdf->getY($firstline,9) + 2;
	
	$bannerwidth=198;
	$pdf->Line(10,$ypos,$pdf->bannerwidth,$ypos);
	
	$pdf->newPage("L");
	$pdf->Ln(13);
	$pdf->DrawQandA($reportid);
	
	//========================================================================================
	// PICTURES
	//========================================================================================
	$ypos = 45;
	$pdf->AddPreworksPhotos($ypos,$jobid); 
	//// logit("Photos: $q");
	//$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);

	//$pdf->AddPhotos($pdf,$firstline,$q,"COMPLETION PHOTOS",$docfolder);
	
	$pdf->Output($reportfile,'F');
	logit("After Output to $reportfile");
	return $reportfile;
	
	}
	
	function ConditionReport($aS,$jobid,$reportid,$title){
	$reportfile = getReportFile($jobid,$reportid);

	if (file_exists($reportfile))
	  unlink($reportfile);
		
	logit("enter ConditionReport");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("Conditionreport file: $reportfile");
	logit("Title: $title");

	$pdf = new JTPDF();
	
	$pdf->drawHeaderLines = false;	
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	$pdf->reportCaption = $title;

	$h = 10;
	$width = 200;
	$firstline = 60;	
		
		
	$q = "SELECT * from ins_sitereport r inner join jobs j on r.jobid=j.jobid inner join customer c on c.customerid=j.customerid where ins_sitereport_id = $reportid";
	$tf = kda("companyname,report_type,clientref,site_address,inspection_date,create_user,siteline2,sitesuburb,sitestate");
	$da = iqa($q,$tf);
	
	logit($q);
	foreach($da as $i=>$row){
		$clientname = $row['companyname'];
		$report_type = $row['report_type'];
		$clientref = $row['clientref'];
		$address = $row['siteline2']." ".$row['sitesuburb']." ".$row['sitestate'];
		$inspection_date = $row['inspection_date'];
		$userid = $row['create_user'];
	}
	
	$qu = "SELECT companyname AS NAME FROM
      customer c INNER JOIN users u ON u.email = c.customerid WHERE u.userid = '$userid'"; 
	logit($qu);
	  
	$tfu = kda("NAME");
	$dau = iqa($qu,$tfu);
	
	logit(print_r($dau,1));
	foreach($dau as $i=>$row){
	 $inspector = $row['NAME'];	
	}	
	
	
	$pdf->SetFillColor(255);
    $pdf->SetTextColor(0,0,0);
	$pdf->SetXY(10, 28);
	
	$caption = "MAINTENANCE CHECK REPORT - $address";
	$jobdetails = "Client: ".$clientname." Job: ".$jobid."  Inspector: $inspector  Date:". $inspection_date;
	$pdf->caption1 = $caption;
	$pdf->caption2 = $jobdetails;
	$pdf->drawcaption = true;
	
		
	$pdf->SetXY(10, 50);
	$pdf->NotableItemsSummary($reportid);
	$pdf->AddPage();
	
	//get report data
			
	$q = "SELECT s.name,content_id,typecode,style,rsct.caption,condition_value,v.sortorder FROM rep_report_inspection_value v
	INNER JOIN rep_section s ON s.id = v.section_id
	INNER JOIN rep_section_content_type rsct ON content_id=rsct.id
	WHERE isdeleted=0 AND report_id = $reportid ORDER BY s.sortorder,v.sortorder,rsct.sortorder";	
	
	$q = "SELECT content_id,typecode,style,rsct.caption,condition_value,v.sortorder,rrsv.section_description as name FROM rep_report_inspection_value v
	INNER JOIN rep_section s ON s.id = v.section_id
	INNER JOIN rep_section_content_type rsct ON content_id=rsct.id
	INNER JOIN rep_report_section_value rrsv ON rrsv.guid= v.section_guid
	WHERE v.isdeleted=0 AND v.report_id = $reportid ORDER BY s.sortorder,section_description,v.sortorder,rsct.sortorder";
	
		
	$tf = kda("name,caption,condition_value");
	
	$da = iqa($q,$tf);
	
	$currentarea = "";
	
	foreach($da as $i=>$row){
	  $area = $row['name'];
	  $item = $row['caption'];
	  $value = $row['condition_value'];
	  
	  if ($currentarea != $area){
	  	$currentarea = $area;
	    $data[] = array($area,"");
		$data[] = array("  ".$item,$value);
	  }
	  else {
		$data[] = array("  ".$item,$value);
	  }
		
	}
		
	$header = array("Area Name","Condition Notes");

	$columns[] = array("55","L","B");
	$columns[] = array("132","L","");

	$pdf->DrawGenericTable($header, $columns, $data);
	
		
	$ypos = 30;
	$pdf->AddConditionPhotos($ypos,$jobid);
	
		
	$pdf->Output($reportfile,'F');

	return $reportfile;
		
		
	}
	
	
	function SiteReport($aS,$reportid,$jobid) {
	$reportfile = getReportFile($jobid,$reportid);

	if (file_exists($reportfile))
	  unlink($reportfile);

	logit("enter SiteReport");
	logit("reportid $reportid");
	logit("jobid: $jobid");
	logit("Sitereport file: $reportfile");

	$pdf = new JTPDF();
	$pdf->reportCaption = "Site Inspection Report";


	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',12);

	logit("180");

	$h = 10;
	$width = 200;
	$firstline = 40;

	$report = iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$job = iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	logit("Custid: $custid");

	$clientname = singlevalue('customer','companyname','customerid',$custid);
	logit("Jobs:".count($job));
	logit("Client: ".$clientname);


	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$siteAddress = $job['siteline2'] ."||".$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$inspectionDate = $report['inspection_Date'].' '.$report['inspection_time'];
	$incidentDate = $report['event_date'];
	$makeSafeReqd = $report['is_make_safe_reqd']='0' ? 'NO' : 'YES';
	$makeSafeActioned = $report['is_make_safe_actioned']='0' ? 'NO' : 'YES';
	$makeSafeActionedBy = $report['make_safe_contact'];
	$siteContact1 = $report['occupant1Name'];
	$siteContact2 = $report['occupant2Name'];

	$BuildingHeight = $report['building_height'];
	$BuildingArea = $report['building_size'];
	$constructionType = $report['construction_type'];
	$RoofConstruction = $report['roof_covering'];

	if ($report['is_demolition_reqd'] == '1')
	  $Works[] = "Demolition Required";
	if ($report['is_asbestos_works'] == '1')
	  $Works[] = "Works involving asbestos";
	if ($report['is_struct_demo_reqd'] == '1')
	  $Works[] = "Structural Demolition required";
	if ($report['is_work_at_heights_reqd'] == '1')
	  $Works[] = "Working at heights or EWP required";
	if ($report['is_confied_space'] == '1')
	  $Works[] = "Works in confined space required";
	if ($report['is_wet_area_reqd'] == '1')
	  $Works[] = "Works involving wet area renovations/waterproofing";
	if ($report['is_timber_floor_reqd'] == '1')
	  $Works[] = "Works involving timber floor repairs/replacement";
	if ($report['is_works_over_limit'] == '1')
	  $Works[] = "Works over 20k";
	if ($report['is_customer_difficult'] == '1')
	  $Works[] = "Works involving difficult customer or customer with unrealistic expectation";
	if ($report['is_permit_reqd'] == '1')
	  $Works[] = "Works requiring Council approval/permits";
	if ($report['is__engineer_reqd'] == '1')
	  $Works[] = "Works requiring Engineers or other professionals";
	if ($report['is_high_exposure'] == '1')
	  $Works[] = "Works in high exposure areas (eg Town centre)";
	if ($report['is_relocation_reqd'] == '1')
	  $Works[] = "Works involving business interupption or loss of rent";
	if ($report['is_comm_insurance_claim'] == '1')
	  $Works[] = "Commercial insurance claim";

	$pdf->DrawField(0,$pdf->getY($firstline,0),$h,"Client Name",0.3,'B',':',$clientname,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,1),$h,"Client Work Order",0.3,'B',':',$workorder,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,2),$h,"DCFM Job No",0.3,'B',':',$jobid,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,3),$h,"Facility Manager",0.3,'B',':',$fmname,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,4),$h,"Site Address",0.3,'B',':',$siteAddress,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,6),$h,"Inspection Date",0.3,'B',':',$inspectionDate,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,7),$h,"Incident Date",0.3,'B',':',$incidentDate,'',$width);


	$liney = $pdf->getY($firstline,9);
	$pdf->Line(10,$liney,195,$liney);


	$pdf->DrawField(0,$pdf->getY($firstline,9),$h,"CLIENT ISSUE/REQUEST",0.3,'BU',':','','',$width);
	$pdf->Ln(5);
	$pdf->MultiCell(180,6,$jobDescription,0,"L");

	$pdf->DrawField(0,$pdf->getY($firstline,13),$h,"Make Safe Required",0.3,'B',':',$makeSafeReqd,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,14),$h,"Make Safe Actioned",0.3,'B',':',$makeSafeActioned,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,15),$h,"Make Safe Actioned By",0.3,'B',':',$makeSafeActionedBy,'',$width);

	$pdf->DrawField(0,$pdf->getY($firstline,16),$h,"Site Contact 1",0.3,'B',':',$siteContact1,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,17),$h,"Site Contact 2",0.3,'B',':',$siteContact2,'',$width);

	$pdf->Ln(5);
	$pdf->DrawField(0,$pdf->getY($firstline,19),$h,"BUILDING DETAILS",0.3,'BU',':','','',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,20),$h,"Construction Type",0.3,'B',':',$constructionType,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,21),$h,"Building Height (m)",0.3,'B',':',$BuildingHeight,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,22),$h,"Building Area (m2)",0.3,'B',':',$BuildingArea,'',$width);
	$pdf->DrawField(0,$pdf->getY($firstline,23),$h,"Roof Construction",0.3,'B',':',$RoofConstruction,'',$width);


	//========================================================================================
	// WORKS REQUIRED
	//========================================================================================
	$pdf->DrawField(0,$pdf->getY($firstline,25),$h,"WORKS REQUIRED",0.3,'BU',':','','',$width);
    $y = 25;

	foreach($Works as $work)
	{
	  $y++;
	  $pdf->DrawField(0,$pdf->getY($firstline,$y),$h," - ".$work,0.3,'',':','','',$width);
	}

	$pdf->AddPage();

	//========================================================================================
	// SCOPE OF WORKS
	//========================================================================================

	$pdf->DrawField(0,$pdf->getY($firstline,0),$h,"SCOPE OF WORKS",0.3,'BU',':','','',$width);

  	$scopeheader = array("Area Name","Size(m) HxWxL","Description","Labour $","Material $");

	$scopeColumns[] = array("45","L");
	$scopeColumns[] = array("35","L");
	$scopeColumns[] = array("60","L");
	$scopeColumns[] = array("25","R");
	$scopeColumns[] = array("25","R");


	$q = "SELECT SCOPE_AREA_NAME,SCOPE_HEIGHT,SCOPE_WIDTH,SCOPE_LENGTH,SCOPE_DESCRIPTION,SCOPE_LABOUR_COST,SCOPE_MATERIAL_COST FROM INS_SCOPE WHERE IS_SCOPE_DELETED=0 AND INS_SITEREPORT_ID = $reportid";
	logit($q);
	$tf = kda("SCOPE_AREA_NAME,SCOPE_HEIGHT,SCOPE_WIDTH,SCOPE_LENGTH,SCOPE_DESCRIPTION,SCOPE_LABOUR_COST,SCOPE_MATERIAL_COST");
	$da=iqa($q,$tf);

	foreach ($da as $i=>$row)
	{
	  $area = $row['SCOPE_AREA_NAME'];
	  $height = $row['SCOPE_HEIGHT'];
	  $width = $row['SCOPE_WIDTH'];
	  $length = $row['SCOPE_LENGTH'];
	  $size = "$height x $width x $length";
	  $description = $row['SCOPE_DESCRIPTION'];
	  $labour_cost = $row['SCOPE_LABOUR_COST'];
	  $material_cost = $row['SCOPE_MATERIAL_COST'];

	  $scopeData[] = array($area,$size,$description,$labour_cost,$material_cost);
	}

	$pdf->Ln(7);
	$pdf->DrawTable($scopeheader, $scopeColumns, $scopeData);

	//========================================================================================
	// PICTURES
	//========================================================================================

	$pdf->AddPage();

	//$photoY = count($scopeData) * 6 + 60;
    $imagespacing = 80;
	$ypos = $pdf->getY($firstline,1);
	$pdf->DrawField(0,$ypos,$h,"SITE PHOTOGRAPHS",0.3,'BU',':','','',$width);

    $q = "SELECT documentid,documentdesc,docformat FROM document d INNER JOIN ins_sitereport r ON d.eagle_guid = r.insurance_guid WHERE r.jobid=$jobid ORDER BY documentid";
	$tf = kda("documentid,documentdesc,docformat");
	$dadoc = iqa($q,$tf);

	#$docfolder = $aS->docPath["jobs"];
	$docFolder = "../../InfomaniacDocs/jobdocs/";

	foreach($dadoc as $i=>$row)
	{
	  $doc = $row['documentid'];
	  $fmt = $row['docformat'];
	  $image = $docFolder."$doc.$fmt";
	  $caption = $row['documentdesc'];
	  $images[] = array($image,$caption);
	}

	$ypos = $ypos + 10;
	if (isset($images)) {
     	foreach ($images as $image)
	{
		$imagefile = $image[0];
		$caption = $image[1];
		logit("Try $imagefile");
		
		$xpos = 95;
		$documentid = '';
		$scale = 1.0;
		$notes = "";
		
		try{
		$pdf->DrawImage($imagefile,$xpos,$ypos,$caption,$notes,$documentid,$scale);
		}
		catch (Exception $ex)
		{
			logit($ex->getMessage());
		}
		$ypos = $ypos + $imagespacing;
				echo $ypos;
		if ($ypos>250)
		{
			$pdf->AddPage();
		    $ypos = $pdf->getY($firstline,1);
		}

	}
 }


	$pdf->Output($reportfile,'F');

	return $reportfile;

}

function emailReport($aS, $subject, $message, $recipients, $attachments){
  logit('Enter emailReport function');

  $ra = explode(";",$recipients);
  $mx = '';


  foreach($ra as $recip){
     logit('sending to '.$recip);
     logit("attachments count ".count($attachments));
     logit("sending:");
     logit($message);
     $msglen = strlen($message);
     logit("msglen: $msglen");

     if($recip != ""){
        if($aS->mailItNow($recip,$subject,$message,$attachments)){
          $mx .= "<br>sent OK.. to $recip";
          $sentok = true;

        }else{

          $mx .= "<br>Not sent to $recip $this->mailFail";
        }
     }

    if($sentok){
      logit ("Send OK");
    }
  }
  return $mx;
}
