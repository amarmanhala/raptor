<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


require_once __DIR__.'/../../helpers/custom_helper.php';
require_once __DIR__.'/../LogClass.php';
require_once __DIR__.'/../shared/SharedClass.php';
/**
 * EditableReport Libraries Class
 *
 * This is a Quote class for Quote Opration 
 *
 * @category   EditableReport
 * @package    Raptor
 * @subpackage Libraries
 * @filesource EditableReportClass.php
 * @author     Alison Morris <itglobal3@dcfm.com.au>
 * 
 */

require_once "masterfunctionsreturn.php";
require_once "jtpdf.php";
require_once "reportQuery.cls.php";
require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/excelReport.class.php");
class ajaxPostBackClass extends MY_Model 
{
    /**
     * Log class 
     * 
     * @var class
     */
    private $LogClass;
    
    /**
     * Shared class 
     * 
     * @var class
     */
    private $sharedClass;
    
    public function __construct()
    {
        parent::__construct();
        //$this->LogClass= new LogClass('jobtracker', 'ajaxPostBack');
        $this->sharedClass = new SharedClass();
        $this->masterfunctions = new masterfunctionsreturn();
    }
    
    public function logIt($string){

            $file = 'C:/temp/reportdebug.log';
            $fh = fopen($file, 'a');
            fwrite($fh, $string."\n");
            fclose($fh);
  }
    
    public function ajaxPost($params) {
        //$data = $this->switchReport($params, 'PropertyInspectionReport', 'PropertyInspectionReport');
        $data = $this->switchReport($params, $params['mode'], 'reporttype');
        return $data;
    }
    
    public function switchReport($params, $mode, $reporttype) {
            $aS = 1;
            switch($mode){

            case "link":
                    $this->logit("Link Report");
                    $jobid = $aS->paramsA['jobid'];
                    $linkreportid = $aS->paramsA['linkreportid'];
                    $this->logit("jobid: $jobid reportid: $linkreportid");
                    #$q = format("Update jobs set rep_parent_siterepid = {0}, jobtype='report' where jobid = {1}",$linkreportid,$jobid);
                    #$this->logit($q);

                    $jobA['jobtype'] = "report";
                    $jobA['rep_parent_siterepid'] = $linkreportid;
                    pau("jobs",$jobA,"jobid",$jobid);

                    $testid = $this->singlevalue("jobs","rep_parent_siterepid","jobid",$jobid);
                    $this->logit("testid: $testid");
                    if($testid == $linkreportid){
                            echo "SUCCESS";
                    } else {
                            echo "FAIL";
                    }
                    break;

            case "generate":

                    //$jobid = $aS->paramsA['jobid'];
                    //$reportid = $aS->paramsA['reportid'];
                    
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    

                    $this->logit("jobid:$jobid");
                    $this->logit("reportid:$reportid");

                    $q = "SELECT name,reportFolder,filespec FROM rep_reporttype rt INNER JOIN ins_sitereport ir ON ir.report_type_id=rt.id WHERE ins_sitereport_id=$reportid";
                    $this->logit($q);
                    $da = $this->masterfunctions->iqasra($q);
                    $subfolder = $da["reportFolder"];
                    $reporttype = $da["name"];
     
                    $this->logit("Reporttype: $reporttype");
                    
                    //$reporttype = 'OfficeAssetsReport';

                    switch($reporttype){


                    case "jobReport":
                            return $this->JobReport($aS,$jobid);
                    break;

                    case "Maintenance Check":
                            $this->logit("Site Report - $reporttype");	
                            return $this->ConditionReport($aS,$jobid,$reportid,"Maintenance Report");

                    break;

                    case "SiteReport":
                            $this->logit("Site Report - $reporttype");	
                            switch ($reporttype)
                            {
                                    case "Air Services Property Inspection":
                                      $this->logit("Site Report - $reporttype");	
                                    break;	
                                    default:	
                                      return $this->SiteReport($aS,$reportid,$jobid);
                            }
                    break;

                    case "LeakReport":
                            return $this->LeakReport($aS,$jobid,$reportid);
                    break;

                    Case "Claim Central":
                            return $this->ClaimCentralReport($aS,$jobid,$reportid);
                    break;	
                    case "Air Services":
                            return $this->AirServicesReport($aS,$jobid,$reportid);
                break;

                    case "PropertyInspectionReport":
                        return $this->PropertyInspectionReport($aS,$jobid,$reportid);
                    break;

                    case "ErgonPropertyInspectionReport":
                            return $this->ErgonPropertyInspectionReport($aS,$jobid,$reportid);
                    break;

                    case "DTZCUBInspectionReport":
                            return $this->DTZPropertyInspectionReport($aS,$jobid,$reportid);
                    break;

                    case "OfficeAssetsReport":
                            return $this->OfficeAssetsReport($aS,$jobid,$reportid);
                    break;

                    case "AssetInspectionReport":
                            return $this->PropertyInspectionReport($aS,$jobid,$reportid);
                    break;
					
					case "Walkthrough":
						    return $this->WalkthroughReport($aS,$jobid,$reportid,"Site Walk-through Report",false);
							break;
							
					case "DIBP Roof Inspection":
						    return $this->WalkthroughReport($aS,$jobid,$reportid,"Roof Inspection Report",false);
							break;
		
		break;		

                    default:
                            $this->logit("$reporttype not implemented for SiteReport.generate");
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

                    //$docid = pai("document",$doc);
                    $this->db->insert('document', $doc);
                    $docid =  $this->db->insert_id();

                    $rep["is_approved"] = "on";
                    pau("ins_sitereport",$rep,"ins_sitereport_id",$reportid);

                    $this->logit("reporttype: $reporttype");

                    $sourcepath = $this->getReportFile($jobid,$reportid);

                    $targetpath = $_SERVER['DOCUMENT_ROOT']."\\infomaniacDocs\\jobdocs\\".$docid.".pdf";
                    $this->logit("docid $docid");
                    $this->logit("sourcepath: ".$sourcepath);
                    $this->logit("targetpath: ".$targetpath);

                    if(is_readable($sourcepath)) {
                            copy($sourcepath,$targetpath);

                            $jn['jobid'] = $jobid;
                            $jn['date'] = date('Y-m-d');
                            $jn['userid'] = $_SESSION['userid'];
                            $jn['notes'] = "Report id: $reportid approved for client portal. Docid: $docid";
                            $jn['notetype'] = "Internal";
                            $jn['ntype'] = "Report";
                            //pai("jobnote",$jn);
                            $this->db->insert('jobnote', $jn);

                        return "TRUE";
                    } else {
                            $this->logit("Could not copy file");
                    }

                    break;
            case "saveimagedescription":
                    $desc = $aS->paramsA['desc'];
                    $docid = $aS->paramsA['docid'];
                    $this->logit("descripion: $desc");
                    $this->logit("docid: $docid");
                    $doc["documentdesc"] = $desc;
                    pau("document",$doc,"documentid",$docid);

                    break;
            case "saveimageall":
                    $desc = $aS->paramsA['desc'];
                    $docid = $aS->paramsA['docid'];
                    $caption = $aS->paramsA['caption'];
                    $this->logit("descripion: $desc");
                    $this->logit("docid: $docid");
                    $doc["documentdesc"] = $caption;
                    $doc["docnote"] = $desc;
                    pau("document",$doc,"documentid",$docid);

                    break;
            case "email":
                    $this->logit("Email report");

                    $jobid = $aS->paramsA['jobid'];
                    $reportid = $aS->paramsA['reportid'];

                    $reporttype = $this->singlevalue("ins_sitereport","report_type","ins_sitereport_id",$reportid);

                    //todo customsie emal for job report...
                    switch($reporttype){
                            case "Job Report":
                                    $subject = "Job Report for DCFM Job $jobid";
                            $message = "Please find job report attached for DCFM Job $jobid";
                            $file = $this->getReportFile($jobid,$reportid);

                            break;
                            case "Inspection":
                                    $subject = "Inspection Report for DCFM Job $jobid";
                            $message = "Please find inspection report attached for DCFM Job $jobid";
                            $file = $this->getReportFile($jobid,$reportid);

                            break;
                            case "Insurance":
                                    $subject = "Insurance Report for DCFM Job $jobid";
                            $message = "Please find insurance report attached for DCFM Job $jobid";
                            $file = $this->getReportFile($jobid,$reportid);

                            break;
                            case "AirServices":

                                    $subject = "Insurance Report for DCFM Job $jobid";
                            $message = "Please find insurance report attached for DCFM Job $jobid";
                            $file = $this->getReportFile($jobid,$reportid);

                            break;
                            default:
                                    $subject = "Job Report for DCFM Job $jobid";
                            $message = "Please find job report attached for DCFM Job $jobid";
                            $file = $this->getReportFile($jobid,$reportid);
                                    break;
                    }

                    $recipients = "ankitchen@gmail.com";
                    $att[0]["dname"]= $file;
                    $att[0]["fname"]= $file;
                    $att[0]["relpath"] = "../../infomaniacDocs/reports";

                    $rx = $this->emailReport($aS, $subject, $message, $recipients, $att);
                    return $rx;
                    break;

            case "preview":
                    $reportid = trim($aS->paramsA['reportid']);
                    $jobid = trim($aS->paramsA['jobid']);
                    $reporttype = $this->getReportType($reportid);

                    $this->logit("Sitereport.preview");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
                    $this->logit("report type: $reporttype");

                            $this->logit("Site Report - $reporttype");	
                            switch ($reporttype)
                            {
                                    case "Air Services Property Inspection":
                                      $this->logit("Excel Site Report - $reporttype");

                                      //error_reporting('ALL');
                                      //ini_set('display_errors','On');
                                      $report = new excelReport(AppType::JobTracker,$_SESSION['userid']);
                                      $documentid = $report->run($reportid);
                                      $this->logit("report result: $documentid");
                                      echo "Report added as document $documentid";

                                      error_reporting(0);	
                                    break;	

                                    case "Maintenance Check":
                                            $this->logit("Site Report - $reporttype");	
                                            $this->ConditionReport($aS,$jobid,$reportid,"Maintenance Report");

                                            break;




                                    default:	
                                      error_reporting(0);	

                                      $this->SiteReport($aS,$reportid,$jobid);
                                      $file = $this->getReportFile($jobid,$reportid);

                            }




                    break;
            case "jobReport":
                    //$jobid = trim($aS->paramsA['jobid']);
                    //$reportid = trim($aS->paramsA['reportid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];

                    $this->logIt("Reportid: $reportid");
                    $this->logIt("Jobid: $jobid");

                    $this->JobReport($aS,$jobid);
                    break;

            case "SiteReport":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Site Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
                    
                    
                    $this->SiteReport($aS,$reportid,$jobid);

               break;

            case "LeakReport":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Leak Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
                    
                    
                    $this->LeakReport($aS,$jobid,$reportid);

               break;

            case "AirServices":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Air Services Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
                    
                    
                    $this->AirServicesReport($aS,$jobid,$reportid);

               break;

            case "PropertyInspectionReport":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Property Inspection Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
    
                    $aS = 1;
                    $filename = $this->PropertyInspectionReport($aS,$jobid,$reportid);
                    return $filename;
                    break;

            case "ErgonPropertyInspectionReport":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Property Inspection Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");

                    $aS = 1;
                    $this->ErgonPropertyInspectionReport($aS,$jobid,$reportid);
                    break;

            case "OfficeAssetsReport":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Property Inspection Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
                    
                    //$aS = 1;
                    $this->OfficeAssetsReport($aS,$jobid,$reportid);

            break;

            case "DTZCUBInspectionReport":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("DTZ/CUB Inspection Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");
                    
                    //$aS = 1;
                    $this->DTZPropertyInspectionReport($aS,$jobid,$reportid);
            break;



            case "AssetInspection":
                    //$reportid = trim($aS->paramsA['reportid']);
                    //$jobid = trim($aS->paramsA['jobid']);
                    $jobid = $params['jobid'];
                    $reportid = $params['reportid'];
                    
                    $this->logit("Property Inspection Report");
                    $this->logit("Reportid: $reportid");
                    $this->logit("Jobid: $jobid");

                    //$aS = 1;
                    $this->OfficeAssetsReport($aS,$jobid,$reportid);

            break;

            default:
                    $this->logit("mode $mode not implemented.");	

    }
    }
    
    public function getReportType($reportid) {

            $q = "SELECT name FROM rep_reporttype rt INNER JOIN ins_sitereport ir ON ir.report_type_id=rt.id WHERE ins_sitereport_id=$reportid";
            $this->logit($q);
            $tf = $this->masterfunctions->kda("name");
            $da = $this->masterfunctions->iqa($q,$tf);

            foreach($da as $i=>$row){
                    $reporttype = $row['name'];
                    $this->logit("reporttype: $reporttype");
                    return $reporttype;			
            }

            return "";


    }
    
    public function getReportFile($jobid,$reportid){
    $this->logit("Enter getReportFile $jobid $reportid");
      //$folder = "../../InfomaniacDocs/reports";
      $folder = $this->config->item('job_report_dir');
      $reportfile = "";
      if ($reportid==""){
            $subfolder = "JobReports";
            //$reportfile = $folder. "\\". $subfolder."\\".$jobid.".pdf";
            $reportfile = $folder.$subfolder."/".$jobid.".pdf";
            return $reportfile;	

      }

      $q = "SELECT reportFolder,filespec FROM rep_reporttype rt INNER JOIN ins_sitereport ir ON ir.report_type_id=rt.id WHERE ins_sitereport_id=$reportid";
      //$tf = $this->masterfunctions->kda("reportFolder,filespec");
      //$da = $this->masterfunctions->iqa($q,$tf);
      $da = $this->db->query($q);
      $da = $da->result_array();
      foreach ($da as $i=>$row)
      {
      $subfolder = $row["reportFolder"];
      $filespec = $row["filespec"];
      }
      $this->logit($q);
      $this->logit("folder: $subfolder");
      $this->logit("filespec: $filespec");


      switch($filespec){
            case "%jobid_%reportid":
            $reportfile = $this->config->item('document_dir').$jobid."_".$reportid.".pdf";
            //$reportfile = $folder. "\\". $subfolder."\\".$jobid."_".$reportid.".pdf";
            break;
            case "%jobid":
            //$reportfile = $folder. "\\". $subfolder."\\".$jobid.".pdf";
            $reportfile = $folder.$subfolder."/".$jobid.".pdf";
            break;
            default:
            //$reportfile = $folder. "\\". $subfolder."\\".$jobid.".pdf";
            $reportfile = $folder.$subfolder."/".$jobid.".pdf";
      }

      return $reportfile;
    }

    public function getLeakReportFile($jobid,$reportid)
    {
      //$folder = "../../InfomaniacDocs/reports/Leakreports/";
      $folder = $this->config->item('job_report_dir').'Leakreports/';
      $reportfile = $folder.$jobid."_".$reportid.".pdf";
      return $reportfile;
    }

    public function JobReport($aS,$jobid)
    {
      $reportfile = $this->getReportFile($jobid,"");
      //$docfolder = "../../InfomaniacDocs/jobdocs/";
       $docFolder = $this->config->item('document_dir');

      if (file_exists($reportfile))
        unlink($reportfile);

      $this->logit("Jobreport file: $reportfile");

      $pdf = new JTPDF();
      $pdf->pageBanner = "Job Report";


      $pdf->AliasNbPages();
      $pdf->AddPage();
      $pdf->SetFont('Arial','',12);

      $this->logit("99");

      $h = 10;
      $width = 200;
      $firstline = 40;

      $job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
      $custid = $job['customerid'];
      $this->logit("Custid: $custid");

      $clientname = $this->singlevalue('customer','companyname','customerid',$custid);
      $this->logit("Jobs:".count($job));
      $this->logit("Client: ".$clientname);

      $workorder = $job['custordref'];
      $fmname = $job['sitefm'];
      $siteAddress = $job['siteline2'] ."||".$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
      $jobDescription = $job['jobdescription'];
      $dueDate = $job['duedate'];
      $completeDate = $job["jcompletedate"];
      $siteContact = $job["sitecontact"];
      $attendDate = $job["jrespdate"];

    $firstline = 40;
            $this->logit("158");
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
      $this->logit("528: $desclines");

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
                                    $this->logit("maxlines reached $linecount sp:$startpos  i:$i pagecount: $pagecount  currenty: $currenty");

                                $pdf->MultiCell(180,6,substr($s, $startpos,$i),0,"L");

                                    $this->logit(substr($s, $startpos,$i));

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

$this->logit("Output: $reportfile");
    $pdf->Output($reportfile,'F');
$this->logit("After Output");

    $q = "SELECT COUNT(*) as reportcount from ins_sitereport where jobid = $jobid and report_type='Job Report'";
$this->logit($q);
    $tf = $this->masterfunctions->kda("reportcount");
    $darep = $this->masterfunctions->iqasra($q,$tf);

$this->logit("count: ".$darep['reportcount']);

    if ($darep["reportcount"]==0){

    $reporttype = $this->singlevalue("rep_reporttype","id","name","Job Report");

    $rep["inspection_date"]=$attendDate;
    $rep["report_type"]="Job Report";
    $rep["notes"] = "Job Report";
    $rep["jobid"] = $jobid;
    $rep["inspection_date"] = date("Y-m-d");
    $rep["create_date"] = date("Y-m-d H:i:s");
    $rep["modify_date"] = date("Y-m-d H:i:s");
    $rep["create_user"] = $_SESSION["userid"];
    $rep["modify_user"] = $_SESSION["userid"];
    $rep["report_type_id"] = $reporttype;

    //$reportid = pai("ins_sitereport",$rep);
    $this->db->insert('ins_sitereport', $rep);
    $reportid =  $this->db->insert_id();
    

    }
            return $reportfile;


    }



    public function LeakReport($aS,$jobid,$reportid){
    $reportfile = $this->getLeakReportFile($jobid,$reportid);

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter LeakReport");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("Leakreport file: $reportfile");

    $pdf = new JTPDF();
    $pdf->reportCaption = "Leak Detection Report";

    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $h = 10;
    $width = 200;
    $firstline = 40;

    $report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
    $job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
    $custid = $job['customerid'];
    $this->logit("Custid: $custid");

    $clientname = $this->singlevalue('customer','companyname','customerid',$custid);
    $this->logit("Jobs:".count($job));
    $this->logit("Client: ".$clientname);


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

            $reportfile = $this->getReportFile($jobid,$reportid);
        $docfolder = "../../InfomaniacDocs/jobdocs/";
        $firstline = 40;

    }
    public function DTZPropertyInspectionReport($aS,$jobid,$reportid){
    $reportfile = $this->getReportFile($jobid,$reportid);

    //$docFolder = "../../InfomaniacDocs/jobdocs/";
    $docFolder = $this->config->item('document_dir');

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter DTZ Property inspection Report");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("DTZ Property inspection report file: $reportfile");

    $pdf = new JTPDF();
    $pdf->pageBanner = "Property Inspection Checklist";
    $inspectiondate = $this->singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
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
    $this->logit("Do output");

    $pdf->Output($reportfile,'F');
    $this->logit("After Output to $reportfile");
    return $reportfile;


    }

    public function OfficeAssetsReport($aS,$jobid,$reportid){
    $reportfile = $this->getReportFile($jobid,$reportid);

    //$docFolder = "../../InfomaniacDocs/jobdocs/";
    $docFolder = $this->config->item('document_dir');

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter Office Assets Report");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("Office Assets report file: $reportfile");

    $pdf = new JTPDF();
    $pdf->pageBanner = "Office Asset Inspection Report";
    $inspectiondate = $this->singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
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
    $this->logit("Do output");

    $pdf->Output($reportfile,'F');
    $this->logit("After Output to $reportfile");
    return $reportfile;


    }


    public function PropertyInspectionReport($aS,$jobid,$reportid){
  
    $reportfile = $this->getReportFile($jobid,$reportid);
    //$docFolder = "../../InfomaniacDocs/jobdocs/";
    $docFolder = $this->config->item('document_dir');

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter Property Inspection Report");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("Property Inspection report file: $reportfile");

    $pdf = new JTPDF();
    $pdf->pageBanner = "Residential Inspection Report";
    $inspectiondate = $this->singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);

    //$table,$field,$idfield,$idvalue
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
    $pdf->InspectionAreaResults($jobid,$reportid,false, false);

    #General Site Photos
    #$ypos,$sql,$title
    #$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);
    $ypos = 45;
    #$pdf->AddJobPhotos($ypos,$jobid); 

    #Before and After Photos
    #$pdf->AddBeforeAfterPhotos($pdf,$firstline,$jobid,"BEFORE AND AFTER SITE PHOTOS",$docFolder);
    $this->logit("Do output");

    $pdf->Output($reportfile,'F');
    $this->logit("After Output to $reportfile");
    return $reportfile;

    }
    public function ErgonPropertyInspectionReport($aS,$jobid,$reportid){
    $reportfile = $this->getReportFile($jobid,$reportid);
    //$docFolder = "../../InfomaniacDocs/jobdocs/";
    $docFolder = $this->config->item('document_dir');

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter Property Inspection Report");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("Ergon Property Inspection report file: $reportfile");

    $pdf = new JTPDF();
    $inspectiondate = $this->singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
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
    $this->logit("Do output");

    $pdf->Output($reportfile,'F');
    $this->logit("After Output to $reportfile");
    return $reportfile;

    }
    public function ClaimCentralReport($aS,$jobid,$reportid){

    $reportfile = $this->getReportFile($jobid,$reportid);
    $docFolder = "../../InfomaniacDocs/jobdocs/";

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter claim Central Report");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("claim Central report file: $reportfile");

    $pdf = new JTPDF();
    $pdf->pageBanner = "Insurance Makesafe Report";
    $inspectiondate = $this->singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
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
    $this->logit("After Output to $reportfile");
    return $reportfile;					




    }
    public function AirServicesReport($aS,$jobid,$reportid){
    $reportfile = $this->getReportFile($jobid,$reportid);

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter AirServicesReport");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("AirServices report file: $reportfile");

    $pdf = new JTPDF();
    $pdf->pageBanner = "Air Services Report";
    $pdf->pageOrientation = PageOrientation::Landscape;
    $pdf->AliasNbPages();
    $pdf->newPage("L");
    $pdf->SetFont('Arial','',12);

    $h = 10;
    $width = 200;
    $firstline = 40;

    $report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
    $job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
    $custid = $job['customerid'];
    $this->logit("Custid: $custid");

    $clientname = $this->singlevalue('customer','companyname','customerid',$custid);
    $this->logit("Jobs:".count($job));
    $this->logit("Client: ".$clientname);


    $workorder = $job['custordref'];
    $fmname = $job['sitefm'];
    $siteAddress = $job['siteline1'] ."||".$job['siteline2'] ."||".$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
    $jobDescription = $job['jobdescription'];
    $inspectionDate = $report['inspection_date'].' '.$report['inspection_time'];
    $siteGPS = "(".$job['latitude_decimal']." , ".$job['longitude_decimal'].")";

    $userid = $report['create_user'];
    $supplierid = $this->singlevalue('users','email','userid',$userid);
    $techname = $this->singlevalue("customer","companyname","customerid",$supplierid);
    $tech = $userid." ($techname)";
    $this->logit("Workorder:".$workorder);

    $pdf->DrawField(0,$pdf->getY($firstline,0),$h,"Client Name",0.3,'B',':',$clientname,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,1),$h,"Client Work Order",0.3,'B',':',$workorder,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,2),$h,"DCFM Job No",0.3,'B',':',$jobid,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,3),$h,"Facility Manager",0.3,'B',':',$fmname,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,4),$h,"Site Address",0.3,'B',':',$siteAddress,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,6),$h,"GPS Location",0.3,'B',':',$siteGPS,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,7),$h,"Inspection Date",0.3,'B',':',$inspectionDate,'',$width);
    $pdf->DrawField(0,$pdf->getY($firstline,8),$h,"Inspected By",0.3,'B',':',$tech,'',$width);
    $this->logit("After fields");
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
    //// $this->logit("Photos: $q");
    //$pdf->AddPhotos($pdf,$firstline,$q,"GENERAL SITE PHOTOS",$docfolder);

    //$pdf->AddPhotos($pdf,$firstline,$q,"COMPLETION PHOTOS",$docfolder);

    $pdf->Output($reportfile,'F');
    $this->logit("After Output to $reportfile");
    return $reportfile;

    }

    public function ConditionReport($aS,$jobid,$reportid,$title){
    $reportfile = $this->getReportFile($jobid,$reportid);

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter ConditionReport");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("Conditionreport file: $reportfile");
    $this->logit("Title: $title");

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
    $tf = $this->masterfunctions->kda("companyname,report_type,clientref,site_address,inspection_date,create_user,siteline2,sitesuburb,sitestate");
    $da = $this->masterfunctions->iqa($q,$tf);

    $this->logit($q);
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
    $this->logit($qu);

    $tfu = $this->masterfunctions->kda("NAME");
    $dau = $this->masterfunctions->iqa($qu,$tfu);

    $this->logit(print_r($dau,1));
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


    $tf = $this->masterfunctions->kda("name,caption,condition_value");

    $da = $this->masterfunctions->iqa($q,$tf);

    $currentarea = "";
    $data = array();
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

	
	function WalkthroughReport($aS,$jobid,$reportid, $caption,$showquote){
	$reportfile = $this->getReportFile($jobid,$reportid);
	$docFolder = "../../InfomaniacDocs/jobdocs/";
		
	if (file_exists($reportfile))
	  unlink($reportfile);

	$this->logit("enter Walkthrough Inspection Report");
	$this->logit("reportid $reportid");
	$this->logit("jobid: $jobid");
	$this->logit("Walkthrough Inspection report file: $reportfile");

	$pdf = new JTPDF();
	$pdf->pageBanner = $caption;
	$inspectiondate = $this->masterfunctions->singlevalue("ins_sitereport","inspection_date","ins_sitereport_id",$reportid);
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

	$pdf->WalkthroughReportCoverPage($jobid,$reportid);

	#individual area results
	$pdf->suppressSectionCaption = true;
    $pdf->tagcaption = "Y/N/NA";	
	$pdf->InspectionAreaResultsByGuid($jobid,$reportid,false,true);

	$ypos = 45;
		
	#Before and After Photos
	$this->logit("Do output");
			
	$pdf->Output($reportfile,'F');
	$this->logit("After Output to $reportfile");
	return $reportfile;
		
	}

    public function SiteReport($aS,$reportid,$jobid) {
    $reportfile = $this->getReportFile($jobid,$reportid);

    if (file_exists($reportfile))
      unlink($reportfile);

    $this->logit("enter SiteReport");
    $this->logit("reportid $reportid");
    $this->logit("jobid: $jobid");
    $this->logit("Sitereport file: $reportfile");

    $pdf = new JTPDF();
    $pdf->reportCaption = "Site Inspection Report";


    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial','',12);

    $this->logit("180");

    $h = 10;
    $width = 200;
    $firstline = 40;

    $report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
    $job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
    $custid = $job['customerid'];
    $this->logit("Custid: $custid");

    $clientname = $this->singlevalue('customer','companyname','customerid',$custid);
    $this->logit("Jobs:".count($job));
    $this->logit("Client: ".$clientname);


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
    
    $Works = array();
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
    $this->logit($q);
    $tf = $this->masterfunctions->kda("SCOPE_AREA_NAME,SCOPE_HEIGHT,SCOPE_WIDTH,SCOPE_LENGTH,SCOPE_DESCRIPTION,SCOPE_LABOUR_COST,SCOPE_MATERIAL_COST");
    $da=$this->masterfunctions->iqa($q,$tf);
    
    $scopeData = array();
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

$q = "SELECT d.documentid,d.documentdesc,d.docformat FROM document d INNER JOIN ins_sitereport r ON d.eagle_guid = r.insurance_guid WHERE r.jobid=$jobid ORDER BY d.documentid";
    $tf = $this->masterfunctions->kda("documentid,documentdesc,docformat");
    $dadoc = $this->masterfunctions->iqa($q,$tf);

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
            $this->logit("Try $imagefile");

            $xpos = 95;
            $documentid = '';
            $scale = 1.0;
            $notes = "";

            try{
            $pdf->DrawImage($imagefile,$xpos,$ypos,$caption,$notes,$documentid,$scale);
            }
            catch (Exception $ex)
            {
                    $this->logit($ex->getMessage());
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

public function emailReport($aS, $subject, $message, $recipients, $attachments){
$this->logit('Enter emailReport function');

$ra = explode(";",$recipients);
$mx = '';


foreach($ra as $recip){
 $this->logit('sending to '.$recip);
 $this->logit("attachments count ".count($attachments));
 $this->logit("sending:");
 $this->logit($message);
 $msglen = strlen($message);
 $this->logit("msglen: $msglen");

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

public function singlevalue($table,$field,$whereid,$value) {
    $query = $this->db->query("select $field from $table where $whereid='".$value."'");
    $data = $query->row_array();
    return $data[$field];
}

}    




?>
