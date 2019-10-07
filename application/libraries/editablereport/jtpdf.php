<?php
//include_once "$clientpath/fpdf/fpdf.php";
require_once APPPATH."/third_party/fpdf/fpdf.php"; 
require_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/imageEditor.class.php");

error_reporting(E_ERROR | E_WARNING | E_PARSE);

abstract class ReportType{
	const JobReport = "JobReport";
	const Insurance = "Insurance";
	const Inspection = "Inspection";
	const LeakReport ="LeakReport";
	const AirServices = "AirServices";
}
abstract class PageOrientation{
	const Portrait = "P";
	const Landscape = "L";
}

class JTPDF extends FPDF
{
public $pageOrientation = PageOrientation::Portrait;
public $pageBanner;
public $rootdocfolder = "../../InfomaniacDocs/jobdocs/";
public $bannerwidth;
public $drawHeaderLines;
public $caption1;
public $caption2;
public $drawcaption = false;
public $ytablestart = 0;
public $masterfunctions;
public $suppressSectionCaption = false;
public $tagcaption = "Tagged";
public $showquote = false;


        public function __construct()
        {
            parent::FPDF();
            $this->rootdocfolder = $_SERVER['DOCUMENT_ROOT']."InfomaniacDocs/jobdocs/";
            $this->masterfunctions = new masterfunctionsreturn();
        }
        
	function logIt($string){

	  $file = 'C:/temp/reportdebug.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
	  fclose($fh);
}


	function logIt2($string){

	  $file = 'C:/temp/reportdebug2.log';
	  $fh = fopen($file, 'a');
	  fwrite($fh, $string."\n");
	  fclose($fh);
	  $fh = null;
}

function newPage($orientation)	{
	if ($orientation=="L"){
	   $this->pageOrientation = PageOrientation::Landscape;
	} else {
	   $this->pageOrientation = PageOrientation::Portrait;
	}
	
	
	$this->AddPage("L");
	return;
	
	#$this->pageOrientation = $orientation;
	if ($orientation == PageOrientation::Landscape){
		$this->AddPage("L");
	} else {
		$this-AddPage("P");
	}
}

// Page header
function Header()
{
    // Logo
    //$logo = "../../dcfm07/images/dcfmlogo.jpg";
    $logo = $_SERVER['DOCUMENT_ROOT'].'/raptor/assets/img/logo.png';
	
	if ($this->pageOrientation == PageOrientation::Portrait){
 		$this->bannerwidth = 198;
		$logox = 158;
 	} else {
 		$this->bannerwidth = 287;
		$logox = 247;
 	}
 
    $this->logIt("bannerwidth: $this->bannerwidth  logox:$logox");
   # $bannerwidth=198;
	
    $this->Image($logo,$logox,10,40);
    // Arial bold 15
    $this->SetFont('Arial','',15);
    // Move to the right
    $this->Cell(1);
    // Title
    //$this->Cell(0,10,'DCFM Australia Pty Ltd',1,0,'L');
	
	$this->Text(10,20,'DCFM Australia Pty Ltd');
    $this->SetFont('');
	$this->SetFontSize('10');
	
 	$this->Text(10,25,'ABN: 69 122 487 078');
    
    $this->SetFont('Arial','',15);
 	
	if(! isset($this->drawHeaderLines)) $this->drawHeaderLines = true;
		

 		
    // Line break
    $this->Ln(20);
	#$this->SetDrawColor(128,0,0);
	$this->SetDrawColor(102,118,255);
	
	if ($this->drawHeaderLines){
	  $this->Line(10,30,$this->bannerwidth,30);
	  $this->SetTextColor(0,0,0);
	  $this->Text(10,36,$this->pageBanner);
	  $this->Line(10,39,$this->bannerwidth,39);
	}
	$this->SetDrawColor(0,0,0);
	
}

/*
function getY($firstline,$i)
{
	$spacing = 8;
	return $firstline + $i * $spacing;
	
}
*/

function getY_Custom($firstline,$i)
{
	$spacing = 8;
	return $firstline + $i * $spacing;
	
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
	$this->SetRightMargin(10);
	
	$footertext = $this->footercaption;
	
    // Page number
	$this->SetX(10);
    $this->Cell(0,10,$footertext.'  Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function AddJobPhotos($ypos,$jobid){
	#$this->newPage("P");
	$this->pageOrientation = PageOrientation::Portrait;
	
	$this->logit("JOB PHOTOS $jobid");
	$q="SELECT documentid,documentdesc,docformat,docnote,dateadded FROM document d WHERE xreftable = 'jobs' AND  d.doctype IN ('Site Photo General','Sketch - General') AND xrefid = $jobid";
	$this->logit("Photos: $q");
	$title = "GENERAL SITE PHOTOS";
	$this->AddPhotos($ypos,$q,$title);

  	$q="SELECT documentid,documentdesc,docformat,docnote,dateadded FROM document d WHERE xreftable = 'jobs' AND  d.doctype LIKE '%Completion Photos' AND xrefid = $jobid";
	$this->logit("Photos: $q");
	$title = "COMPLETION PHOTOS";
	$this->AddPhotos($ypos,$q,$title);
}
function AddPreworksPhotos($ypos,$jobid){
	$this->logit("PREWORKS PHOTOS $jobid");
	
	$q="SELECT documentid,documentdesc,docformat,docnote,dateadded FROM document d WHERE xreftable = 'jobs' AND  d.doctype IN ('Inspection Photo Pre-Works') AND xrefid = $jobid";
	$this->logit("Photos: $q");
	$title = "PRE-WORKS PHOTOS";
	$this->pageOrientation = PageOrientation::Portrait;
	$this->AddPhotos($ypos,$q,$title);
}

function AddConditionPhotos($ypos,$jobid){
	$this->logit("Add Condition Photos $jobid");
		
	$q="SELECT * FROM document d INNER JOIN rep_report_section_value rrsv ON d.area_guid = rrsv.guid WHERE docfolder LIKE '%photo%' AND xrefid=$jobid AND sitereport_guid IS NOT NULL";
	
	$this->logit("Photos: $q");
	$title = "INSPECTION PHOTOS";
	$this->pageOrientation = PageOrientation::Portrait;
	$this->AddPhotos($ypos,$q,$title);
	
}


function AddAreaPhotos($ypos,$jobid,$areaid,$rowcount){
	  $this->logit("Enter AddAreaPhotos");
	  $docfolder = $this->rootdocfolder;
	  $this->SetFont('Arial','',10);
 	
	  $guidmatch = TRUE;
	  if($guidmatch)
	  {
 	  	$sql = "SELECT documentid,documentdesc,doctype,docformat,docnote FROM document d 
			INNER JOIN rep_area_value rav ON d.scope_guid=rav.areatype_guid
			WHERE xreftable = 'jobs' AND  (doctype LIKE '%pre-work%' OR doctype LIKE '%post-work%') 
			AND areatype_id=$areaid AND xrefid = $jobid and d.isdeleted=0";
 	
	   } else {
 		$sql ="SELECT documentid,documentdesc,doctype,docformat,docnote FROM document d 
 			WHERE xreftable = 'jobs' AND  (doctype LIKE '%pre-work%' OR doctype LIKE '%post-work%') 
 			AND area_id=$areaid AND xrefid = $jobid and d.isdeleted=0";
	   }
	   
	  $sql .= " ORDER BY d.documentid";
	   	
 	  $this->logit($sql);	
		
	  $tf = $this->masterfunctions->kda("documentid,documentdesc,doctype,docformat,docnote");
	  $dadoc = $this->masterfunctions->iqa($sql,$tf);
      if (count($dadoc)==0) return;
  	$this->logit("Adding page");
      $this->AddPage("L");
	  $this->Ln(10);
	
	 //$ypos = 100;
	  $ypos = 0;
	  //$yposimage = 60 + $rowcount * 6.75;
	  //$yposimage = $rowcount * 6.75;
	  $yposimage = 50;
	  $yspacing = 60;
	  $this->logit("image ypos $yposimage");
	  
	  foreach($dadoc as $i=>$row)
	  {
	    $doc = $row['documentid'];
	    $fmt = $row['docformat'];
	    $image = $docfolder."$doc.$fmt";
	  //$this->logit("image: $image");
	    $caption = strtoupper($row['documentdesc']);
	    $notes = $row['docnote'];
		$beforeafter = $row['doctype'];
		
	    $images[] = array($image,$caption,$notes,$doc,$beforeafter);
	  }
	  
	  foreach ($images as $image)
	  {
		$imagefile = $image[0];
		$caption = $image[1];
		$note = $image[2];
		$documentid = $image[3];
		$beforeafter = $image[4];
		
		$this->logit("image1: ".$imagefile);
		$this->logit("caption: ".$caption);
		$this->logit("notes: ".$note);
		$this->logit("documentid: ".$documentid);
		$this->logit("beforeafter: ".$beforeafter);
		$this->logit("yposimage: ".$yposimage);
		#$this->Line(10,$yposline,200,$yposline);
		
		$scale = 1;
		$xpos = 10;
		
		try {
		  $this->SetLeftMargin(10);
		  $date = $this->masterfunctions->singlevalue("document","dateadded","documentid",$documentid);
		  
		 if ($yposimage>150){
		 	$this->logit("Adding page >150");
	 		$this->newPage("L");
  		  	$yposimage=50;	 
	     }
		 
		  #$this->SetRightMargin(120);
		  $imagecount++;
		  $this->DrawImage($imagefile,$xpos,$yposimage,"","",$docid,$scale);
	
		  //$xpos = 85;
		  $xpos = 120;
		  
		  //$this->text($xpos,$yposimage+2,$caption);
		  //$this->text($xpos,$yposimage+2,$caption);
		  
		  $this->SetXY($xpos, $yposimage);
		  
		  //$this->multicell(300,6, "!!!! \n\n aaa /n/n <br>" . $caption . "!!!end", 1);
		  //$this->multicell(300,6, $caption, 1);
		  $doc = "Image ID: $documentid";
		  $this->multicell(160,5, $caption . "\nDate: " .$date . "\n" .$note. "\n" .$doc, "");
		  
		  
		  //$this->text($xpos,$yposimage+10,"Date: ".$date);
		  //$this->text($xpos,$yposimage+18,$note);

	  	  $yposimage = $yposimage + $yspacing;		
		  
		}
				catch (Exception $ex)
		{
			$this->logit("exception ".$ex->getMessage());
		}
		
	    if ($ypos>250)
		{
			//$this->AddPage();
			#this->AddPage("L");
		    $ypos = $this->getY_Custom($firstline,1);
		}  
	  }	
}
function AddAreaGuidPhotos($ypos,$jobid,$areaguid,$rowcount){
	  $this->logit("Enter AddAreaGuidPhotos");
	  $docfolder = $this->rootdocfolder;
	  $this->SetFont('Arial','',10);
 
	  $sql = "SELECT documentid,documentdesc,doctype,docformat,docnote FROM 
	  		rep_area_value rav INNER JOIN document d ON rav.areatype_guid=d.scope_guid
			WHERE rav.guid='$areaguid' AND xrefid= $jobid";
	   	
 	  $this->logit2($sql);	
		
	  $tf = $this->masterfunctions->kda("documentid,documentdesc,doctype,docformat,docnote");
	  $dadoc = $this->masterfunctions->iqa($sql,$tf);
	  $photocnt = count($dadoc);
	  $this->logit("$photocnt photos.");
	  
      if ($photocnt==0) return;
  
      $this->AddPage("L");
	  $this->Ln(10);
	
	 //$ypos = 100;
	  $ypos = 0;
	  //$yposimage = 60 + $rowcount * 6.75;
	  //$yposimage = $rowcount * 6.75;
	  $yposimage = 50;
	  $yspacing = 60;
	  $this->logit("image ypos $yposimage");
	  
	  foreach($dadoc as $i=>$row)
	  {
	    $doc = $row['documentid'];
	    $fmt = $row['docformat'];
	    $image = $docfolder."$doc.$fmt";
	  //$this->logit("image: $image");
	    $caption = strtoupper($row['documentdesc']);
	    $notes = $row['docnote'];
		$beforeafter = $row['doctype'];
		
	    $images[] = array($image,$caption,$notes,$doc,$beforeafter);
	  }
	  
	  foreach ($images as $image)
	  {
		$imagefile = $image[0];
		$caption = $image[1];
		$note = $image[2];
		$documentid = $image[3];
		$beforeafter = $image[4];
		
		$this->logit("image1: ".$imagefile);
		$this->logit("caption: ".$caption);
		$this->logit("notes: ".$note);
		$this->logit("documentid: ".$documentid);
		$this->logit("beforeafter: ".$beforeafter);
		$this->logit("yposimage: ".$yposimage);
		
		#$this->Line(10,$yposline,200,$yposline);
		
		$scale = 1;
		$xpos = 10;
		
		try {
		  $this->SetLeftMargin(10);
		  $date = $this->masterfunctions->singlevalue("document","dateadded","documentid",$documentid);
		  
		 if ($yposimage>150){
	 		$this->newPage("L");
  		  	$yposimage=50;	 
	     }
		 
		  #$this->SetRightMargin(120);
		  $imagecount++;
		  $this->DrawImage($imagefile,$xpos,$yposimage,"","",$documentid,$scale);
	
		  //$xpos = 85;
		  $xpos = 120;
		  
		  //$this->text($xpos,$yposimage+2,$caption);
		  //$this->text($xpos,$yposimage+2,$caption);
		  
		  $this->SetXY($xpos, $yposimage);
		  
		  //$this->multicell(300,6, "!!!! \n\n aaa /n/n <br>" . $caption . "!!!end", 1);
		  //$this->multicell(300,6, $caption, 1);
		  $doc = "Image ID: $documentid";
		  $this->multicell(160,5, $caption . "\nDate: " .$date . "\n" .$note. "\n" .$doc, "");
		  
		  
		  //$this->text($xpos,$yposimage+10,"Date: ".$date);
		  //$this->text($xpos,$yposimage+18,$note);

	  	  $yposimage = $yposimage + $yspacing;		
		  
		}
				catch (Exception $ex)
		{
			$this->logit("exception ".$ex->getMessage());
		}
		
	    if ($ypos>250)
		{
			//$this->AddPage();
			$this->AddPage("L");
		    $ypos = $this->getY_Custom($firstline,1);
		}  
	  }	
}
	function AddBeforeAfterPhotos($pdf,$firstline,$jobid,$title,$docfolder,$area=null){
	  $this->logit("Enter AddBeforeAndAfterPhotos");
	  $sql ="SELECT documentid,documentdesc,doctype,docformat,docnote,s.scope_guid FROM document d 
			INNER JOIN ins_scope s ON d.scope_guid = s.scope_guid 
		    WHERE xreftable = 'jobs' AND  
	  		(doctype LIKE '%pre-work%' OR doctype  LIKE '%post-work%') 
	  		AND xrefid = $jobid  
	  		AND s.scope_area_name = '$area'
	  		ORDER BY s.scope_server_id ASC, doctype DESC";
	 
	  $this->logit($sql);	
	  
	  if ($title=="") $title = "BEFORE & AFTER PHOTOS";					
	  
	  $tf = $this->masterfunctions->kda("documentid,documentdesc,doctype,docformat,docnote,scope_id");
	  $dadoc = $this->masterfunctions->iqa($sql,$tf);
      if (count($dadoc)==0) return;

      if ($this->getY_Custom()>0){
	    //$this->AddPage();
		$this->AddPage("L");
	  }
	  
      $imagespacing = 80;
	  $ypos = $this->getY_Custom($firstline,1);
	  $this->DrawField(0,$ypos,$h,$title,0.3,'BU',':','','',$width);

	  $ypos = $this->getY_Custom($firstline,2);
	  $imagespacing = 100;
	  
	  $yposline = $ypos;
	  $yposdate = $ypos+5;
	  $yposimage = $ypos+6;

	  foreach($dadoc as $i=>$row)
	  {
	    $doc = $row['documentid'];
	    $fmt = $row['docformat'];
	    $image = $docfolder."$doc.$fmt";
	  //$this->logit("image: $image");
	    $caption = $row['documentdesc'];
	    $notes = $row['docnote'];
	    $scope_id = $row['scope_id'];
		$beforeafter = $row['doctype'];
		
	    $images[] = array($image,$caption,$notes,$doc,$scope_id,$beforeafter);
	  }
//$this->logit("190");

		$header = array("B/A","Notes");
		$Columns[] = array("15","L");
		$Columns[] = array("65","L");

      foreach ($images as $image)
	  {
		$imagefile = $image[0];
		$caption = $image[1];
		$note = $image[2];
		$documentid = $image[3];
		$scope_id = $image[4];
		$beforeafter = $image[5];
		
		$this->logit("image1: ".$imagefile);
		$this->logit("caption: ".$caption);
		$this->logit("notes: ".$note);
		$this->logit("documentid: ".$documentid);
		$this->logit("scopeid: ".$scope_id);
		$this->logit("beforeafter: ".$beforeafter);
		#$this->logit("yposimage: ".$yposimage);
		
		
		$this->Line(10,$yposline,200,$yposline);
		
		$scale = 0.5;
		
		try {
		  if ($currentscopeid != $scope_id){
		  	$xpos = 95;
			$type = "Before";
			$docid = $documentid;
			  
		  }	else {
		  	$xpos = 150;
			$type = "After";
			$docid = "";
		    #$this->SetRightMargin(1);
			
		  }
		  
		  $date = $this->masterfunctions->singlevalue("document","dateadded","documentid",$documentid);
		  $this->text($xpos,$yposdate,"Date: ".$date);
		  
		  $this->SetRightMargin(120);
				
		  $this->DrawImage($imagefile,$xpos,$yposimage,"","",$docid,$scale);
		
		  if ($type=="Before") {
		  	$this->SetLeftMargin($xpos);
		  	$this->Ln(5);
		  	$this->SetX($xpos+50);
		 $this->logit("Before X $xpos"); 
		    $this->SetY($ypos+70);
		    $this->Cell(50,6,$caption,1,"L");
		    $this->Ln(1);
		   #$this->Text(10,$ypos+40,"Before: ".$note);
		  	$beforenotes = $note;		
		   # $this->Write(5,$note);
 		  } else {
 		  	$this->SetX($xpos);
			$this->logit("After X $xpos"); 
		  	$this->SetLeftMargin($xpos);
			
		  	$this->SetRightMargin(10);
		    $this->SetY($ypos+70);
			$this->Cell(50,6,$caption,1,"L");
		    #$this->Ln(7);
			
		    #$this->Write(5,$note);
		    #$this->SetRightMargin(180);
		    
		    #$this->Text(10,$ypos+60,"After: ".$note);
		  	#$this->SetRightMargin(10);
			
			$afternotes = $note;
			$this->SetLeftMargin(10);
			$this->SetRightMargin(120);
			$this->SetY($ypos+35);	
			$Data[] = array("Before",$beforenotes);
			$Data[] = array("After",$afternotes);
			
			$this->DrawTable($header, $Columns, $Data);
	

		  }
	
		  
		  
		  if ($type == "After"){
		    $ypos = $ypos + $imagespacing;
			$yposline = $ypos;
			$yposdate = $ypos+5;
	  		$yposimage = $ypos+6;
			unset($Data); 
			  }
		  $currentscopeid = $scope_id;
 		}
		catch (Exception $ex)
		{
			$this->logit("exception ".$ex->getMessage());
		}
		
	    if ($ypos>250)
		{
			//$this->AddPage();
			$this->AddPage("L");
			
		    $ypos = $this->getY_Custom($firstline,1);
		}
	  }
	}

function AddPhotos($ypos,$sql,$title){
	$this->logit("Enter AddPhotos $ypos,$title");
	$docfolder = $this->rootdocfolder;
	$this->logit("docfolder $docfolder");
	$conditionreport = false;
	
	
	if(strpos($sql, "rrsv")){
      $tf = $this->masterfunctions->kda("documentid,documentdesc,docformat,docnote,dateadded,section_description");
	  $conditionreport = true;
	} else {
     $tf = $this->masterfunctions->kda("documentid,documentdesc,docformat,docnote,dateadded");
	}
	
	$dadoc = $this->masterfunctions->iqa($sql,$tf);
    if (count($dadoc)==0) return;

	$this->AddPage();

    $imagespacing = 75;
	$this->DrawField(0,$ypos,$h,$title,0.3,'BU',':','','',$width);
	$ypostop = $ypos + 5;
	$ypos = $ypostop;

	foreach($dadoc as $i=>$row)
	{
	  $doc = $row['documentid'];
	  $fmt = $row['docformat'];
	  $image = $docfolder."$doc.$fmt";
	  $this->logit("image path: ".$image);
	  $caption = $row['documentdesc'];
	  $notes = $row['docnote'];
	  $date = $row['dateadded'];
	  
	  if ($conditionreport){
	  	$area = $row['section_description'];
	  } else {
	    $area = "";
	  }

	  $images[] = array($image,$caption,$notes,$doc,$date,$area);
	  
	  }
		$imagecount = count($images);
     	foreach ($images as $image)
	{
		$imagefile = $image[0];
		$caption = $image[1];
		$note = $image[2];
		$documentid = $image[3];
		$date = $image[4];
		$area = $image[5];
		
		$this->logit("image1: ".$imagefile);
		$this->logit("caption: ".$caption);
		$this->logit("notes: ".$note);
		$this->logit("date: ".$date);
		$this->logit("documentid: ".$documentid);
		$this->logit("yposimage: ".$ypos);
		$this->logit("area: ".$area);
		
		if (strlen($area)<2) {
		  $areatext = "";
		} else {
		  $areatext = "Area:".$area;	
		}
		
		$xpos = 95;
		$scale = 0.8;
		try {
		  $this->DrawImage($imagefile,$xpos,$ypos,$note,$caption,$documentid,$scale);
		  $this->Ln(10);
		 $this->SetX(10);
		  $this->multicell(160,5, $areatext . "\nDate: " .$date . "\nID:" .$doc, "");
 #$this->SetRightMargin(1);
		  $irec++;
		  $ypos = $ypos + $imagespacing;
		}
		catch (Exception $ex)
		{
		  $this->logit("exception ".$ex->getMessage());
		}
	    if ($ypos>240)
		{
			if ($imagecount>$irec){
			$this->AddPage();
			#$this->AddPage("L");
		    $ypos = $ypostop;
				
			}
		}

	}
}

function NotableItemsSummary($reportid){
	$this->logit("Notable items");
	
	$q = "SELECT content_id,typecode,style,rsct.caption,condition_value,v.sortorder,rrsv.section_description as name,isnotable FROM rep_report_inspection_value v
	INNER JOIN rep_section s ON s.id = v.section_id
	INNER JOIN rep_section_content_type rsct ON content_id=rsct.id
	INNER JOIN rep_report_section_value rrsv ON rrsv.guid= v.section_guid
	WHERE v.isdeleted=0 AND v.report_id = $reportid AND isnotable=1 ORDER BY s.sortorder,section_description,v.sortorder,rsct.sortorder";
	
	$tf = $this->masterfunctions->kda("name,caption,condition_value");
	
	$da = $this->masterfunctions->iqa($q,$tf);
	
	$this->logit(print_r($da,1));
	
        $data = array();
        
	foreach($da as $i=>$row){
	  $area = $row['name'];
	  $item = $row['caption'];
	  $value = $row['condition_value'];
	  
	  $data[] = array($area,$item,$value);
	}
		
	$header = array("Area Name","Item","Condition Notes");

	$columns[] = array("45","L","B");
	$columns[] = array("45","L","");
	$columns[] = array("97","L","");


	$this->ytablestart = 80;
 	$this->DrawGenericTable($header, $columns, $data);
	$this->ytablestart = 0;
 
	$this->Write(20,"NOTABLE ITEMS SUMMARY");
 	
	
}

function DrawCellData($caption,$value,$w1,$w2,$h,$j1 = NULL,$j2 = NULL){
	if (is_null($j1)) $j1 = "L";
	if (is_null($j2)) $j2 = "L";
	
	$this->Cell($w1,$h,$caption,1,0,$j1);
	$this->Cell($w2,$h,$value,1,1,$j2);
}

//Standard Field
function DrawField($xpos,$ypos,$h,$caption,$pctwidth,$captionstyle,$separator,$value,$valuestyle,$totalwidth)
{
	$fontsize = 10;
	$spacing = $fontsize/2;
		
	$this->SetX($xpos);
	$this->SetY($ypos);
	
	$this->SetFont('Arial',$captionstyle,$fontsize);
	$this->Write($h,$caption.$separator);
	
	$valx = $xpos + $pctwidth * $totalwidth;
	
	
	$lines = explode("||",$value);
	
	foreach($lines as $line)
	{
	  $this->SetX($valx);
	  $this->SetFont('Arial',$valuestyle,$fontsize);
	  $this->Write($h,$line);
	  $ypos=$ypos+$spacing;
	  $this->SetY($ypos);
	}

}

function DrawImage ($imagefile,$xpos,$ypos,$caption,$notes,$documentid=null,$scale=1)
{
	$this->SetY($ypos);
	$this->SetX(0);
	$this->logit("DrawImage i:$imagefile x:$xpos y:$ypos c:$caption n:$notes d:$documentid s:$scale");
	
	$rootdocpath = $GLOBALS['JT_HTDOCS']."//InfomaniacDocs";
	$this->logit("rootdocpath: $rootdocpath");
	$imagefile = str_replace("htdocsInfomaniacDocs", "htdocs/InfomaniacDocs", $imagefile);
	$this->logit("New file path: $imagefile");
	
	if (file_exists($imagefile)){
	  $filesize = filesize($imagefile) / 1024;
	  $targetfilesize = 200;
	  $minfilesize = 90;	
	  $this->logit("size:". $filesize);
	  $scale = 100;
	  $fscale = 100;
	  if ($filesize> $targetfilesize){
	  	$scale = $targetfilesize / $filesize * 100;
	  }	else {
	  	//look for usuzed file
	 	$ipos = strrpos($imagefile,".");
		$fileext = substr($imagefile,$ipos+1);
		$basefilename = substr($imagefile, 0,$ipos);
		$origfile = $basefilename."_original.".$fileext;
   $this->logit("origfile:". $origfile);
			if (file_exists($origfile)){
			$origfilesize = filesize($origfile) / 1024;
 $this->logit("origfilesize:". $origfilesize);
			if($origfilesize > $targetfilesize) {
				$fscale = $targetfilesize / $origfilesize * 100;
		}
	  }
	  
	  if ($filesize < $minfilesize && $origfilesize>$filesize ){
	  	$imged = new imageEditor(AppType::JobTracker,"");
	  	copy($origfile,$imagefile);
	  	$imged->edit(6,$fscale,$imagefile);
		$this->logit("using origfile");
		
	  } else {
	  	$imged = new imageEditor(AppType::JobTracker,"");
	  	$imged->edit(6,$scale,$imagefile);
		$this->logit("using currentfile");
	  }
	  
	   $this->logit("scale:". $scale);
	   $this->logit("fscale:". $fscale);
	}
	}
	$pos = strrpos($imagefile,'.');
	$this->logit("pos: $pos");
	
	$processimage = true;
	if(strpos(strtoupper($imagefile),".TIF") !== false)
	{
	 $processimage = false;	
	}
	if (! $processimage){
	$this->logit('Image file cannot be processed:'.$imagefile);
	return;	
	}
	
	
	if(!$pos)
		$this->logit('Image file has no extension and no type was specified: '.$imagefile);
	$type = substr($imagefile,$pos+1);
	$this->logit("file type:$type");		
	try{
		#$this->Image($imagefile,$xpos,$ypos,$scale*100,0,$type);
		$this->Image($imagefile,$xpos,$ypos,0,55,$type);
				$this->logit("Image added OK: $imagefile  $pos  $type");
	}
	catch (Exception $ex)
	{
			$this->logit($ex->getMessage());
	}
	$this->SetX(150);
	$this->SetY($ypos);
	//$this->Cell(0,$ypos,$caption,"1");
	//$this->MultiCell(80,6,$caption,1,"L");
	//$this->Ln(5);
	//$this->SetRightMargin(120);
	//$this->Cell(80,10,$notes,1,2,"L");
	//$this->Write(5,$notes);
	$this->SetRightMargin(1);
	
	if ($caption != ''){
	  $this->MultiCell(80,6,$caption,1,"L");
	  $this->Ln(1);
	  $this->SetRightMargin(120);
	}

	//$this->DrawImageScope($documentid);
	
	if ($notes != ''){
	  $this->Write(5,$notes);
	}

}
function DrawHeightImage($imagefile,$xpos,$ypos,$height,$caption,$notes,$documentid=null)
{
	$this->SetY($ypos);
	$this->SetX($xpos);
	$this->logit("DrawHeightImage i:$imagefile x:$xpos y:$ypos c:$caption n:$notes d:$documentid s:$scale");
	
	$pos = strrpos($imagefile,'.');
	$this->logit("pos: $pos");
	
	$processimage = true;
	if(strpos(strtoupper($imagefile),".TIF") !== false)
	{
	 $processimage = false;	
	}
	if (! $processimage){
	$this->logit('Image file cannot be processed:'.$imagefile);
	return;	
	}
	
	
	if(!$pos)
		$this->logit('Image file has no extension and no type was specified: '.$imagefile);
	$type = substr($imagefile,$pos+1);
	$this->logit("file type:$type");		
	try{
		$this->Image($imagefile,$xpos,$ypos,0,$height,$type);
		$this->logit("Image added OK: $imagefile  $xpos $ypos  $height $type");
	}
	catch (Exception $ex)
	{
			$this->logit($ex->getMessage());
	}

	$ypos = $ypos + $height + 2;
	$this->SetY($ypos);
	#$this->SetRightMargin(1);
	
	if($xpos<110){
	  	$rmargin = 185;
	} else {
	  	$rmargin = 10;
	}
	  
	if ($caption != ''){
	  #$this->MultiCell(80,6,$caption,1,"L");
	  $this->SetLeftMargin($xpos);
	  $this->Ln(1);

	  $this->SetRightMargin($rmargin);
	  $this->SetFont('Arial','B',10);
	  $this->SetX($xpos);
	  $this->Write(5,$caption);
	}

	
	if ($notes != ''){
	  $this->SetFont('Arial','',10);
	  $this->SetX($xpos);
	  $this->Sety($ypos+7);
	  $this->Write(5,$notes);
	}

}

function DrawImageScope($documentid){
	$this->logit("Enter DrawImageScope: $documentid");
	
	$sql = "SELECT scope_labour_hours,scope_labour_cost,scope_material_cost FROM ins_scope s INNER JOIN document d ON d.scope_guid=s.scope_guid WHERE documentid = $documentid";
	$this->logit($sql);
	$tf = $this->masterfunctions->kda("scope_labour_hours,scope_labour_cost,scope_material_cost");
	$da = $this->masterfunctions->iqa($sql,$tf);
 
    if (count($da) == 0){
    	return;
    }
	
	$header = array("Costs","Hours","$");

	$Columns[] = array("35","L");
	$Columns[] = array("20","C");
	$Columns[] = array("25","R");


	foreach ($da as $i=>$row)
	{
	  $hours = $row['scope_labour_hours'];
	  $labour = $row['scope_labour_cost'];
	  $materials = $row['scope_material_cost'];
	  
	  $this->logit("h:$hours l:$labour m:$materials");
	  
	  $total = str_replace("$","",$labour) + str_replace("$","",$materials) ;
	  
	  $this->logit("t: ".$total);
	  
	  if ($total == 0){
	  	return;
	  }
	  
	  $Data[] = array("Labour",$hours,$labour);
	  $Data[] = array("Materials"," - ",$materials);
	  $Data[] = array("Total","  ","$".number_format($total,2,".",","));
	}

	$this->Ln(1);
	if (count($da)> 0){
	  $this->logit("drawing table..");
	  $this->SetX(10);
	  $this->DrawTable($header, $Columns, $Data);
  	}	
  	
	
}



function GenerateWord()
{
    //Get a random word
    $nb=rand(3, 10);
    $w='';
    for($i=1;$i<=$nb;$i++)
        $w.=chr(rand(ord('a'), ord('z')));
    return $w;
}

function GenerateSentence()
{
    //Get a random sentence
    $nb=rand(1, 10);
    $s='';
    for($i=1;$i<=$nb;$i++)
        $s.=GenerateWord().' ';
    return substr($s, 0, -1);
}

function DrawTableHeader($header,$columns){
	
	if ($this->drawcaption){
	  $this->SetFillColor(255);
      $this->SetTextColor(0,0,0);
  	  $this->SetDrawColor(0,0,0);
	  $this->SetXY(10, 28);
		
	  $this->Cell( 187,10, $this->caption1,1,0,'C',true);
	  $this->Ln(10);
	  $this->Cell( 187,10, $this->caption2,1,0,'C',true);
	  $this->Ln(15);
	}
	
		
	$countColumns = count($columns);
	
	for($i=0; $i < $countColumns; $i++)
	{
		$rowColumn = $columns[$i];
		$rowHeader = $header[$i];
		
		$x = $this->GetX();		
		$y = $this->GetY();
   		
   		//$this->SetFillColor(176,13,191);
   		$this->SetFillColor(125,61,185);
    	$this->SetTextColor(255);
		$this->SetFont('','B');
		
		$this->Cell( $rowColumn[0],7, $rowHeader,1,0,'C',true);
	}

		$this->SetFont('','');
   		$this->SetTextColor(0);
		
 	    $this->SetXY(10, $y+7.4);

}
function DrawGenericTable($header, $columns, $data)
{
	$this->logit("DrawGenericTable");
	$this->logit(print_r($header,1));
	$this->logit(print_r($columns,1));
	$this->logit(print_r($data,1));
	
	$countHeader = count($header);
	$countColumns = count($columns);
	$countData = count($data);
	
    // Colors, line width and bold font
    $this->SetFillColor(176,13,191);
    $this->SetTextColor(255);
 	$this->SetDrawColor(102,118,255);
    $this->SetLineWidth(.2);

	$widthColumns = array();
	$alignColumns = array();
	
	
	for($i=0; $i < $countColumns; $i++)
	{
		$rowColumn = $columns[$i];
		$rowHeader = $header[$i];
		$widthRow = $rowColumn[0];
		$widthColumns[$i] = $widthRow;
		$alignColumns[$i] = $rowColumn[1];
		
		$x = $this->GetX();		
		$y = $this->GetY();

		//$this->Cell( $rowColumn[0],7, $rowHeader,1,0,'C',true);
	}
	
	if ($this->ytablestart != 0){
		$y = $y + $this->ytablestart;
		$this->setXY($x,$y);
		$this->logit("ytablestart: ".$this->ytablestart);
	}
	 
	$this->DrawTableHeader($header,$columns);
	$this->logit("X: $x");
	$this->logit("y: $y");
	
	$x = $this->GetX();		
	$y = $this->GetY();

	$this->logit("X: $x");
	$this->logit("y: $y");


	//set the cursor at the next row

	//$this->SetXY(10, $y+7.4);
	
	//$this->SetTextColor(0);
	
	$this->SetWidths($widthColumns);
	$this->SetAligns($alignColumns);
	

	//process all rows
    foreach($data as $row)
    {
	
		$contentRow = array();
		
		//get the columns
		for($i=0; $i < $countColumns; $i++)
		{
			$rowColumn = $columns[$i];
			
			$widthRow = $rowColumn[0];
			$textContent = $row[$i];
			
			$contentRow[$i] = $textContent;

		}
		
		$this->Row($contentRow,$header,$columns);
		
	}	
	
	
} 

	

function DrawTable($header, $columns, $data)
{
	$this->logit("Enter DrawTable");
	$countHeader = count($header);
	$countColumns = count($columns);
	$countData = count($data);

	#$this->logit2("Enter DrawTable, header: $countHeader columns:  $countColumns data: $countData");


	
    // Header
    //$w = array(40, 35, 40, 45);
    //$w = $columns;
	//$width=0;
	
	//$this->logit2("countHeader: " . $countHeader);
	//$this->logit2(implode($columns));
	//$this->logit2(implode($columns));
	
    // Colors, line width and bold font
    //$this->SetFillColor(176,13,191);
    //$this->SetTextColor(255);
   // $this->SetDrawColor(0,0,0);
    //$this->SetLineWidth(.3);
    //$this->SetFont('','B');
	
	$this->SetFillColor(211,211,211);
    $this->SetTextColor(0);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.1);
	
	
	$widthColumns = array();
	$alignColumns = array();
	
	
	for($i=0; $i < $countColumns; $i++)
	{
		$rowColumn = $columns[$i];
		$rowHeader = $header[$i];
		$widthRow = $rowColumn[0];
		$widthColumns[$i] = $widthRow;
		
		$align = "C";
		
		if( $i == 0 )
		{
			$align = "L";
		}
		else if( $i == 7 )
		{
			$align = "L";
		}
		else if( $i ==8)
		{
			$align = "L";
		}

		
		$alignColumns[$i] = $align;
		
		
		$x = $this->GetX();		
		$y = $this->GetY();

		#$this->logit2( "Length: $widthRow, header: $rowHeader ($x, $y)" );
		
		//$this->Cell($w[$i][0],7,$header[$i],1,0,'C',true);
		$this->Cell( $rowColumn[0],7, $rowHeader,1,0,'C',true);
		//$this->logit2($rowHeader);
	}
	
	$x = $this->GetX();		
	$y = $this->GetY();

	#$this->logit2("Finished Header Draw ($x, $y)");
	
	//set the cursor at the next row

	$this->SetXY(10, $y+7);
	
	$this->SetTextColor(0);
	
	
	/*
	$this->SetWidths(array(30, 50, 30, 40));
	for($i=0;$i<20;$i++)
		$this->Row(array("a","a","a","a"));

	*/

	$this->SetWidths($widthColumns);
	$this->SetAligns($alignColumns);
	
	

	//all rows
    foreach($data as $row)
    {
	
		$contentRow = array();
		
		//get the each row
	
		//get the columns
		for($i=0; $i < $countColumns; $i++)
		{
			$rowColumn = $columns[$i];
			$widthRow = $rowColumn[0];
			$textContent = $row[$i];
			
			$contentRow[$i] = $textContent;

			//$x = $this->GetX();
			//$y = $this->GetY();
			//$this->MultiCell($widthRow, 6, $textContent,'LRBT','L',0);
			
			//$x = $x + $widthRow;
			//$this->SetXY($x,$y);
		}
		
		$this->Row($contentRow);
		
		//$this->Ln();
		//$this->Ln();
		//return;
		

	}	
	
	//$this->MultiCell(60,6,'!!!!- text content',1,’L’,false);
	
	
	#$this->logit2("Finished Rows Draw ($x, $y)");
			$this->logit("Leave DrawTable");
	
} 







/******* *******/

var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data,$header = null,$columns = null)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    if($this->CheckPageBreak($h)){
    	if (isset($header)){
 			$this->Ln(15);
			$this->DrawTableHeader($header,$columns);
    	}
    }
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
		
		if ($y<47){
			$y=47;
			$this->SetXY($x, $y);
		
		}
        //Draw the border
        $this->Rect($x, $y, $w, $h);
        //Print the text
        if(isset($columns)){
          $rowcolumn = $columns[$i];	
          $rowfont = $rowcolumn[2];
		  $this->SetFont('',$rowfont);
        }
         
        $this->MultiCell($w, 5, $data[$i], 0, $a);
		
		$this->logit2("X: $x   y:$y  ".$data[$i]);
        //Put the position to the right of the cell
        $this->SetXY($x+$w, $y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
 
    if($this->GetY()+$h>$this->PageBreakTrigger)
	{
        $this->AddPage($this->CurOrientation);
		return true;
	} else {
		return false;
	}	

}

function NbLines($w, $txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r", '', $txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
/******* *******/




function DrawTable_RETIRED($header, $columns, $data)
{
	$this->logit("Enter DrawTable: $header"." columns:  ".count($columns)." rows: ".count($data));
    // Colors, line width and bold font
    $this->SetFillColor(176,13,191);
    $this->SetTextColor(255);
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    //$w = array(40, 35, 40, 45);
    $w = $columns;
	$width=0;
    for($i=0;$i<count($header);$i++)
  
  
        $this->Cell($w[$i][0],7,$header[$i],1,0,'C',true);
		$width = $width + $w[$i][0];
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','I',8);
	
    $this->SetFont('');
    // Data
    $fill = false;
	$irow=0;
	$tableleftmargin = 10;
    foreach($data as $row)
    {
    	$irow++;
   		$leftmargin = $tableleftmargin;
		#$this->SetLeftmargin($tableleftmargin);
		
    	
    	for($icol=0;$icol<count($columns);$icol++)
		{
			$this->logit("draw row: $irow col: $icol value: ".$row[$icol]);
	
			//for ($jcol=0;$jcol<$icol;$jcol++){
			if ($icol>0) {
					// = $tableleftmargin;
				//}  else {
					$leftmargin = $leftmargin + $columns[$icol-1][0];
				//}
			}
			$this->logit("row: $irow col: $icol lmargin: $leftmargin  text: ".$row[$icol]);
			#if($icol ==count($columns)-1){
				$text = $row[$icol];
					
				#$this->SetLeftmargin($leftmargin);
				#$this->Write(7,$row[$icol]);
				#$this->Cell($columns[$icol][0],6,$text,'LR',0,$columns[$icol][1],$fill);
			#} else {
				$text = $row[$icol];
				#$this->SetLeftmargin($tableleftmargin);
		
				//andrew
				//$this->Cell($columns[$icol][0],6,'!!!!-' . $text,'LR',0,$columns[$icol][1],$fill);
				
				//$this->Cell($columns[$icol][0],6,'!!!!-' . $text,'LR',0,$columns[$icol][1],$fill);
				//$this->MultiCell( 70, 20, $text, 1);
				//$this->MultiCell( 20,6, $text, 1);
				//$this->Cell($columns[$icol][0],6,'!!!!qqq\\n\nr rrr\n-' . $text,'LR',0,$columns[$icol][1],$fill);
				//$this->MultiCell(5,6, $text,0);
				
				//$this->MultiCell($columns[$icol][0],6,’Insert your long string here’,1,’L’,false);
				
				
				//worked
				//$this->MultiCell($columns[$icol][0],6,'!!!!-' . $text,1,’L’,false);
				 

				 
$current_y = $this->GetY();
$current_x = $this->GetX();

$cell_width = $columns[$icol][0];
$this->MultiCell($cell_width, 6, $text,1,'T', false,'T');
//$this->MultiCell($cell_width,6,'!!!!-' . $text,1,’L’,false);


$this->SetXY($current_x + $cell_width, $current_y);

				 

				//$x = $this->GetX();
				//$y = $this->GetY();

				//$this->MultiCell($columns[$icol][0],6,'!!!!-' . $text,1,’L’,false);

				//$col1="PILOT REMARKS\n\n";
				//$this->MultiCell(189, 10, $col1, 1, 1);
				//$this->MultiCell($columns[$icol][0], 6, $text, 1, 1);
				//$y = $y + 6;
				//$x = $x + $columns[$icol][0];

				//$this->SetXY($x , $y);				 
				
				
				
			#}
			#Cell(w,h,text,border,ln,align,fill link)
			#Write(h,text)
		}
		
        $this->Ln();
        $fill = !$fill;
    }
    
   $this->Cell($width,0,'','T');
   $this->Ln();
} 
function GetAreaTopicValue($reportid,$area,$topic){
  $this->logit("Enter GetAreaTopicValue $reportid $area $topic");	
  $rq = new reportQuery();
  $da = $rq->getAreaTopicValue($reportid,$area,$topic);
  $this->logit(count($da));

  if (count($da)==1){
  	foreach($da as $i=>$row){
  		$result = $row['description'];
  		return $result;
  	}
  }	else {
  	return "";
  }
	
}
function InspectionAreaResultsByGuid($jobid,$reportid,$basic,$showtag){
   $this->logit("Enter InspectionAreaResultsByGuid: $reportid  basic:$basic showtag:$showtag");
 	
   $rq = new reportQuery();
   $daa = $rq->getAreaGuids($reportid);
   
   foreach($daa as $i=>$row){
   	 $areaguid = $row['guid'];
	 $areaname = $row['name'];    
	 $da = $rq->getAreaGuidResults($reportid,$areaguid);
	 
	 if (count($da) > 0) {
	 	if ($areaname != "General Comments"){
 
 	 if ($areaname !='Uncategorised'){
	 
	 	$this->newPage("L");
			
	 if ($basic) {
	 	if ($showtag){
	      $this->DrawBasicAreaResults($areaname, $counter, $da);
	 	} else {
			
	     $this->DrawBasicAreaResultsNoTag($areaname, $counter, $da);
	 	}
	   #$this->DrawBasicAreaResultsNoTag($areaname, $counter, $da);
 	 }	else {
 		  $this->DrawAreaResults($areaname, $counter, $da);
	 }
	 }
	 
	//$this->AddPage();
	//$this->AddPage("L");
	//$this->Ln(10);
	$photoareaguid = $this->getPhotoGuid($reportid,$areaname);
	 //$ypos = 100;
	// $ypos = 0;
	$this->logit2("Area: $areaname guid: $areaguid photoguid: $photoareaguid");
	 $this->AddAreaGuidPhotos($ypos,$jobid,$photoareaguid,count($da));
		}
	 }
   }	
	
}
function getPhotoGuid($reportid,$areaname) {
	$q = "SELECT guid FROM rep_area_value WHERE report_id=$reportid AND area_description = '$areaname'";
	$guid = $this->masterfunctions->iqasrf($q,"guid");
	return $guid;
}

function InspectionAreaResults($jobid,$reportid,$basic,$asset){
   $this->logit("Enter InspectionAreaResults: $reportid basic: $basic asset:$asset");
 	
   $rq = new reportQuery();
   $daa = $rq->getAreas($reportid);
   
   if (! isset($asset) ) $asset = FALSE;
   
   foreach($daa as $i=>$row){
   	 $areaid = $row['id'];
	 $areaname = $row['name'];    
	 $da = $rq->getAreaResults($reportid,$areaid);
	 
	 if (count($da) > 0) {
	 	if ($areaname != "General Comments"){
 	 $this->newPage("L");
     #$this->Ln(15);
	 if ($areaname !='Uncategorised'){
	 if ($basic) {
	   $this->DrawBasicAreaResults($areaname, $counter, $da);
 	 }	else {
		if ($asset) {
	      $this->DrawAssetCountAreaResults($areaname, $counter, $da);
		} else {
	      $this->DrawAreaResults($areaname, $counter, $da);
		}
	 }
	 }
	 
	//$this->AddPage();
	//$this->AddPage("L");
	//$this->Ln(10);
	
	 //$ypos = 100;
	// $ypos = 0;
	 $this->AddAreaPhotos($ypos,$jobid,$areaid,count($da));
		}
	 }
   }	
	
}
function AssetAreaResults($jobid,$reportid,$basic,$asset){
   $this->logit("Enter InspectionAreaResults: $reportid");
 	
   $rq = new reportQuery();
   $daa = $rq->getAreas($reportid);
   
   foreach($daa as $i=>$row){
   	 $areaid = $row['id'];
	 $areaname = $row['name'];    
	 $da = $rq->getAreaResults($reportid,$areaid);
	 
	 if (count($da) > 0) {
	 	if ($areaname != "General Comments"){
 	 $this->newPage("L");
     #$this->Ln(15);
	 if ($areaname !='Uncategorised'){
	 if ($basic) {
	   $this->DrawBasicAreaResults($areaname, $counter, $da);
 	 }	else {

	   $this->DrawAreaResults($areaname, $counter, $da);
	 }
	 }
	 
	//$this->AddPage();
	//$this->AddPage("L");
	//$this->Ln(10);
	
	 //$ypos = 100;
	// $ypos = 0;
	 $this->AddAreaPhotos($ypos,$jobid,$areaid,count($da));
		}
	 }
   }	
	
}
function AreaResultsAndPhotos($jobid,$reportid) {
   $this->logit("Enter AreaResultsandPhotos: $reportid");
 	
   $rq = new reportQuery();
   $daa = $rq->getScopeAreas($reportid);
   
   $this->SetMargins(10,10,200);
 
   foreach($daa as $i=>$row){
 	 $areaname = $row['scope_area_name'];    
	 $this->logit("area: $areaname");
	 
	 if (count($daa) > 0) {
	 $counter++;
 	 $this->newPage("L");
     $this->Ln(15);
	 $this->DrawAreaQandAwithPhoto($jobid,$reportid,$areaname);
	 
	 }
   }	
}
function DrawBasicAreaResultsNoTag($areaname,&$counter,$da) {
	$this->logit("Enter DrawBasicAreaResultsNoTag: $areaname $counter");
	$this->SetFontSize('10');
	#$this->Ln();
	
	
	$Columns[] = array("112","L");
	$Columns[] = array("15","C");
	$Columns[] = array("80","L");
	$Columns[] = array("70","L");
	
	$this->logit("Columns: ".sizeof($Columns));
	$this->logit("Rows: ".sizeof($da));
	
	$reccnt = 0;
        $Data = array();
	foreach ($da as $i=>$row)
	{
	  $topic = $row['topic_name'];
	  $rating = $row['dirty_rating'];
	  $description = $row['description'];
	  $remedial_action = $row['remedial_action'];

	  $ratingsum = $ratingsum + $rating;
	  
	  if($rating>0) $reccnt++;
	  
	  
		$this->logit("$topic,$rating,$description,$remedial_action");
	    $Data[] = array($topic,$rating,$description,$remedial_action);
	}
	
	if ($reccnt==0){
		$average=0;
	} else {
		$average = round($ratingsum / $reccnt,2);
	}

	if ($average == 0){
		$this->logit("No Rating Table not drawn");	
		return;
	}
	$counter++;
	
	$this->newPage("L");
    $this->Ln(15);
	
	$this->Ln();
	
	$sectiontitle = "Section ".$counter." - ".$areaname;
	$header = array($sectiontitle,"Rating","Description","Remedial Action");
	
	$this->logit("1722 sectiontitle: $sectiontitle");
	
	$this->DrawTable($header, $Columns, $Data);
	$this->Setx(10);
	$this->SetFont('Arial','IB',10);
 
	$this->Cell(112,10,"$areaname - Rating",1,0,"L");
	$this->Cell(15,10,$average,1,0,"C");
	$this->Ln(20);

	
}

function DrawAreaResults($areaname,&$counter,$da) {
	$this->logit2("Enter DrawAreaResults: $areaname $counter");
	$this->SetFontSize('10');

	$Columns[] = array("49","L");
	$Columns[] = array("17","C");
	$Columns[] = array("17","C");
	$Columns[] = array("17","C");
	$Columns[] = array("21","C");
	$Columns[] = array("21","C");
	$Columns[] = array("15","C");
	$Columns[] = array("70","L");
	$Columns[] = array("50","L");
	
	$this->logit2("Columns: ".sizeof($Columns));
	$this->logit2("Rows: ".sizeof($da));
	
	$reccnt = 0;
	foreach ($da as $i=>$row)
	{
	  $topic = $row['topic_name'];
	  $ynna = $row['ynna'];
	  $dirtyval = $row['dirty'];
	  $damageval = $row['damaged'];
	  $rating = $row['dirty_rating'];
	  $description = $row['description'];
	  $remedial_action = $row['remedial_action'];

	  $ratingsum = $ratingsum + $rating;
	  
	  if($rating>0) $reccnt++;
	  
	  $this->logit2("topic: $topic ynna: $ynna");
	  
	  switch ($ynna){
	  	case "1":
			$tagged = "N";   //Changed from  1= "X", 0=blank, -1= "N/A" on 26.5.2015 by ANK
			break;
		case "0":
			$tagged = "Y";
			break;
		case "-1":
			$tagged = "N/A";
			break;		
	  }
	  
	  #if($ynna == "1"){
	  #	$tagged = "X";
	  #} else {
	  #	$tagged = "";
	  #}
	  
	  if($dirtyval == "1"){
	  	$clean = "";
		$dirty = "X";  
	  }
	  else {
	  	$clean = "X";
		$dirty = "";  
  	  }

	  if($damageval == "1"){
	  	$undamaged = "";
		$damaged = "X";  
	  }
	  else {
	  	$undamaged = "X";
		$damaged = "";  
  	  }
		$this->logit("$topic,tag:$tagged,c:$clean,d:$dirty,u:$undamaged,d:$damaged,r:$rating,$description,$remedial_action");
	    $Data[] = array($topic,$tagged,$clean,$dirty,$undamaged,$damaged,$rating,$description,$remedial_action);
	}
	
	if ($reccnt==0){
		$average=0;
	} else {
		$average = round($ratingsum / $reccnt,2);
	}

	if ($average == 0) {
		$this->logit("0 Rating. Table not drawn");
		return;
	}

	$this->Ln();
	$sectiontitle = "Section ".$counter." - ".$areaname;
	if ($this->suppressSectionCaption) {
		$sectiontitle = $areaname;
	}
	$this->logit("1824 sectiontitle: $sectiontitle");
	

	
	$header = array($sectiontitle,$this->tagcaption,"Clean","Dirty","Undamaged","Damaged","Rating","Description","Remedial Action");

 	$counter++;
	#$this->newPage("L"); ANK 6.2.2015 Remove spurious page break.
    $this->Ln(15);


	$this->DrawTable($header, $Columns, $Data);
	$this->Setx(10);
	$this->SetFont('Arial','IB',10);
 
	$this->Cell(142,10,"$areaname - Rating",1,0,"L");
	$this->Cell(15,10,$average,1,0,"C");
	$this->Ln(20);

	$this->logit("Leave DrawAreResults");
}
function DrawAssetCountAreaResults($areaname,&$counter,$da) {
	$this->logit("Enter DrawAssetCountAreaResults: $areaname $counter");
	$this->SetFontSize('10');
	$sectiontitle = "Section ".$counter." - ".$areaname;
	
	$header = array($sectiontitle,"Tagged","Count","Description","Action");

	$Columns[] = array("85","L");
	$Columns[] = array("17","C");
	$Columns[] = array("15","C");
	$Columns[] = array("80","L");
	$Columns[] = array("80","L");
	
	$this->logit("Columns: ".sizeof($Columns));
	$this->logit("Rows: ".sizeof($da));
	
	$reccnt = 0;
	foreach ($da as $i=>$row)
	{
	  $topic = $row['topic_name'];
	  $ynna = $row['ynna'];
	  $rating = $row['dirty_rating'];
	  $description = $row['description'];
	  $remedial_action = $row['remedial_action'];

	  $ratingsum = $ratingsum + $rating;
	  
	  if($rating>0) $reccnt++;
	  
	  switch ($ynna){
	  	case "1":
			$tagged = "X";
			break;
		case "0":
			$tagged = "";
			break;
		case "-1":
			$tagged = "N/A";
			break;		
	  }
	  
	  if($ynna == "1"){
	  	$tagged = "X";
	  } else {
	  	$tagged = "";
	  }
	  
		$this->logit("$topic,$tagged,$rating,$description,$remedial_action");
	    $Data[] = array($topic,$tagged,$rating,$description,$remedial_action);
	}
	
	if ($reccnt==0){
		$average=0;
	} else {
		$average = round($ratingsum / $reccnt,2);
	}

	if ($average == 0){
		$this->logit("No Rating. Table not drawn");	
		#return;
	}
	$counter++;
	
	#No Rain$this>logit("New page");
	#$this->newPage("L");
    $this->Ln(15);
	
	$this->Ln();

	$this->DrawTable($header, $Columns, $Data);
	$this->Setx(10);
	$this->SetFont('Arial','IB',10);
 
	$this->Cell(102,10,"$areaname - Total",1,0,"L");
	$this->Cell(15,10,$ratingsum,1,0,"C");
	$this->Ln(20);

	
}
function DrawBasicAreaResults($areaname,&$counter,$da) {
	$this->logit("Enter DrawBasicAreaResults: $areaname $counter");
	$this->SetFontSize('10');
	
	$header = array($sectiontitle,"Tagged","Rating","Description","Remedial Action");

	$Columns[] = array("85","L");
	$Columns[] = array("17","C");
	$Columns[] = array("15","C");
	$Columns[] = array("80","L");
	$Columns[] = array("80","L");
	
	$this->logit("Columns: ".sizeof($Columns));
	$this->logit("Rows: ".sizeof($da));
	
	$reccnt = 0;
	foreach ($da as $i=>$row)
	{
	  $topic = $row['topic_name'];
	  $ynna = $row['ynna'];
	  $rating = $row['dirty_rating'];
	  $description = $row['description'];
	  $remedial_action = $row['remedial_action'];

	  $ratingsum = $ratingsum + $rating;
	  
	  if($rating>0) $reccnt++;
	  
	  switch ($ynna){
	  	case "1":
			$tagged = "X";
			break;
		case "0":
			$tagged = "";
			break;
		case "-1":
			$tagged = "N/A";
			break;		
	  }
	  
	  if($ynna == "1"){
	  	$tagged = "X";
	  } else {
	  	$tagged = "";
	  }
	  
		$this->logit("$topic,$tagged,$rating,$description,$remedial_action");
	    $Data[] = array($topic,$tagged,$rating,$description,$remedial_action);
	}
	
	if ($reccnt==0){
		$average=0;
	} else {
		$average = round($ratingsum / $reccnt,2);
	}

	if ($average == 0){
		$this->logit("No Rating Table not drawn");	
		return;
	}
	$counter++;
	
	$this->newPage("L");
    $this->Ln(15);
	
	$this->Ln();
	$sectiontitle = "Section ".$counter." - ".$areaname;
	$this->logit("Suppress caption: ".$this->suppressSectionCaption);
	if ($this->suppressSectionCaption) {
		$sectiontitle = $areaname;
	}
	
	$this->logit("1996 sectiontitle: $sectiontitle");

	$this->DrawTable($header, $Columns, $Data);
	$this->Setx(10);
	$this->SetFont('Arial','IB',10);
 
	$this->Cell(102,10,"$areaname - Rating",1,0,"L");
	$this->Cell(15,10,$average,1,0,"C");
	$this->Ln(20);

	
}

function getSiteAddress($jobid){
	$job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
	$siteAddress = $job['siteline2'] .' '.$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	
	return $siteAddress;
}
function MakeSafeReportCoverPage($jobid,$reportid){
	$this->logit("Enter MakeSafeReportCoverPage $jobid");
	$this->SetFillColor(192,192,192);
	$this->Ln(12);

	#$this->Text(10,36,"Residential Inspection Report");
	#$this->Ln(10);
	$this->SetFont('Arial','B',12);
	$this->Cell(188,10,"Make Safe Details",1,1,"C",1);

	$h = 10;
	$width = 200;
	$firstline = 40;
	
	
	$job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	$this->logit("Custid: $custid");

	$clientname = $this->masterfunctions->singlevalue('customer','companyname','customerid',$custid);
	$this->logit("Jobs:".count($job));
	$this->logit("Client: ".$clientname);

	
	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$fmphone = $job['sitefmph'];
	$siteAddress = $job['siteline2'] .' '.$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$siteContact = $job["sitecontact"];
	$sitecontactphone = $job["sitephone"];

	
	$rq = "SELECT * from ins_sitereport where ins_sitereport_id=$reportid";
	$this->logit($rq);
	$tfr = $this->masterfunctions->kda("inspection_date,inspection_time,create_user");
	#$report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$dar = $this->masterfunctions->iqa($rq,$tfr);
	foreach ($dar as $i=>$row){
	 $attendDate = $row['inspection_date'];
	 $attendTime = $row['inspection_time'];
	 		
	 $userid = $row['create_user'];
	}
	
	$uq = "SELECT companyname,phone FROM customer c INNER JOIN users u ON u.email=c.customerid WHERE u.userid = '$userid'";
	$this->logit($uq);
	$tf = $this->masterfunctions->kda('companyname,phone');
	
	$supplier = $this->masterfunctions->iqa($uq,$tf);
	
	foreach ($supplier as $i=>$row){
	  $tech = $row['companyname'];
	  $techphone = $row['phone'];
	}
	
	#$contact1 = $report['occupant1name'];
	#$contact2 = $report['occupant2name'];
	
	#$agency = $this->GetAreaTopicValue($reportid,"General Comments","Agency");
	$agency = "DCFM Australia Pty Ltd";
	#$propertytype = $this->GetAreaTopicValue($reportid,"General Comments","Property Type");

	$this->SetFont('Arial','',10);

	$this->DrawCellData("Client:",$clientname,40,148,8);
	$this->DrawCellData("Work Order #:",$workorder,40,148,8);
	$this->DrawCellData("DCFM Job No:",$jobid,40,148,8);
	$this->DrawCellData("Date of Inspection:",$attendDate.'       Arrival Time On Site: '.$attendTime,40,148,8);
	$this->DrawCellData("Onsite Contact:",$siteContact,40,148,8);
	$this->DrawCellData("Contact No:",$sitecontactphone,40,148,8);
	#$this->DrawCellData("Others at Inspection:",$contact2,40,148,8);
	
	$this->DrawCellData("Contractor:",$tech,40,148,8);
	$this->DrawCellData("Contractor Contact:",$techphone,40,148,8);
	
	$this->DrawCellData("Property Address:",$siteAddress,40,148,8);
	#$this->DrawCellData("Property Type:",$propertytype,40,80,8);
	
	$this->DrawCellData("","",40,148,151);
	$this->text(11,130,"Job Description");
	$this->SetMargins(51,145);
	$this->SetY(128);
	$this->SetFont('Arial','',8);
	$this->Write(4,$jobDescription);
	
	$this->SetFont('Arial','B',12);
	$this->Ln(5);
	
	
}
function AssetReportCoverPage($jobid,$reportid) {
	$this->logit("Enter AssetReportCoverPage $jobid");
	$this->SetFillColor(192,192,192);
	$this->Ln(12);

	#$this->Text(10,36,"Residential Inspection Report");
	#$this->Ln(10);
	$this->SetFont('Arial','B',12);
	$this->Cell(185,10,"Inspection Details",1,1,"C",1);

	$h = 10;
	$width = 200;
	$firstline = 40;
	
	
	$job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	$this->logit("Custid: $custid");

	$clientname = $this->masterfunctions->singlevalue('customer','companyname','customerid',$custid);
	$this->logit("Jobs:".count($job));
	$this->logit("Client: ".$clientname);

	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$fmphone = $job['sitefmph'];
	$siteAddress = $job['siteline2'] .' '.$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$siteContact = $job["sitecontact"];
	$sitecontactphone = $job["sitephone"];

	$rq = "SELECT * from ins_sitereport where ins_sitereport_id=$reportid";
	$this->logit($rq);
	$tfr = $this->masterfunctions->kda("inspection_date,create_user");
	#$report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$dar = $this->masterfunctions->iqa($rq,$tfr);
	foreach ($dar as $i=>$row){
	 $attendDate = $row['inspection_date'];
	 $userid = $row['create_user'];
	}
	
	$uq = "SELECT companyname,phone FROM customer c INNER JOIN users u ON u.email=c.customerid WHERE u.userid = '$userid'";
	$this->logit($uq);
	$tf = $this->masterfunctions->kda('companyname,phone');
	
	$supplier = $this->masterfunctions->iqa($uq,$tf);
	
	foreach ($supplier as $i=>$row){
	  $tech = $row['companyname'];
	  $techphone = $row['phone'];
	}
	
	$contact1 = $report['occupant1name'];
	$contact2 = $report['occupant2name'];
	
	#$agency = $this->GetAreaTopicValue($reportid,"General Comments","Agency");
	$agency = "DCFM Australia Pty Ltd";
	$propertytype = $this->GetAreaTopicValue($reportid,"General Comments","Property Type");

	$this->SetFont('Arial','',10);
	$x2 = 145;

	$this->DrawCellData("Client:",$clientname,40,$x2,8);
	$this->DrawCellData("Work Order #:",$workorder,40,$x2,8);
	$this->DrawCellData("DCFM Job No:",$jobid,40,$x2,8);
	$this->DrawCellData("Date of Inspection:",$attendDate,40,$x2,8);
	$this->DrawCellData("Onsite Client Rep:",$siteContact,40,$x2,8);
	$this->DrawCellData("Contact No:",$sitecontactphone,40,$x2,8);
	$this->DrawCellData("Others at Inspection:",$contact2,40,$x2,8);
	
	$this->DrawCellData("Inspector:",$tech,40,$x2,8);
	$this->DrawCellData("Inspector Contact:",$techphone,40,$x2,8);
	
	
	$this->DrawCellData("Agency:",$agency,40,$x2,8);
	$this->DrawCellData("Property Address:",$siteAddress,40,$x2,8);
	$this->DrawCellData("Property Type:",$propertytype,40,$x2,8);
	
	$this->SetFont('Arial','B',12);
	$this->Ln(5);
	
	//return;
	
	#Summary Section
	$this->Cell(100,7,"SUMMARY",1,1,"C",1);
	
	$this->SetFont('Arial','B',10);
	$this->Cell(50,7,"Area",1,0,"L");
	$this->Cell(15,7,"Rating",1,0,"C");
	$this->Cell(35,7,"Overall Rating",1,1,"C");
		
	$this->SetFont('Arial','',10);
	
	$rq = new reportQuery();
	$dar = $rq->getAreaRatings($reportid);
		
	foreach ($dar as $i=>$row){
	   $area = $row['area_name'];
	   $av_dirty_rating = $row['av_dirty_rating'];
	   $av_damaged_rating = $row['av_damaged_rating'];	

	   if ($area != "General Comments")	  { 
 	   $tot_dirty_rating = $tot_dirty_rating + $av_dirty_rating;
	   $tot_damaged_rating = $tot_damaged_rating + $av_damaged_rating;
	   }
	   
	   $this->logit($area." ".$av_dirty_rating." ".$tot_dirty_rating);
	   if ($area != "General Comments")	  { 
        $this->DrawCellData($area,round($av_dirty_rating,2),50,15,7,"L","C");
		$irec++;   
	   }	
	}
	
	if ($irec==0){
		$overall_dirty_rating = 0;
		$overall_damaged_rating = 0;
	} else {
		$overall_dirty_rating = $tot_dirty_rating / $irec;
		$overall_damaged_rating = $tot_damaged_rating / $irec;
	}
	
		   $this->logit("dirty: ".$overall_dirty_rating." ".$overall_damaged_rating);
	
	#Add blank rows up to 13 total
	$maxareas = 15;
	#for($i=count($dar);$i<$maxareas;$i++){
		for($i=$irec;$i<$maxareas;$i++){
		$this->DrawCellData("","",50,15,7);
	}

	#Draw outside grey box
	$ypos = 149+11;
	$this->SetY($ypos);
	$this->SetX(75);
	$this->Cell(35,112,"",1,1,"C",1);

	#Draw inside box with overall rating
	$this->SetY($ypos+7);
	$this->SetX(80);
	$this->SetFillColor(255,255,255);
	$this->SetFont('Arial','B',14);
	$this->Cell(25,97,round($overall_dirty_rating,2),1,1,"C",1);


	
}
function InspectionReportCoverPage($jobid,$reportid) {
	$this->logit("Enter InspectionReportCoverPage $jobid");
	$this->SetFillColor(192,192,192);
	$this->Ln(12);

	#$this->Text(10,36,"Residential Inspection Report");
	#$this->Ln(10);
	$this->SetFont('Arial','B',12);
	$this->Cell(185,10,"Inspection Details",1,1,"C",1);

	$h = 10;
	$width = 200;
	$firstline = 40;
	
	
	$job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	$this->logit("Custid: $custid");

	$clientname = $this->masterfunctions->singlevalue('customer','companyname','customerid',$custid);
	$this->logit("Jobs:".count($job));
	$this->logit("Client: ".$clientname);

	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$fmphone = $job['sitefmph'];
	$siteAddress = $job['siteline2'] .' '.$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$siteContact = $job["sitecontact"];
	$sitecontactphone = $job["sitephone"];

	$rq = "SELECT * from ins_sitereport where ins_sitereport_id=$reportid";
	$this->logit($rq);
	$tfr = $this->masterfunctions->kda("inspection_date,create_user");
	#$report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$dar = $this->masterfunctions->iqa($rq,$tfr);
	foreach ($dar as $i=>$row){
	 $attendDate = $row['inspection_date'];
	 $userid = $row['create_user'];
	}
	
	$uq = "SELECT companyname,phone FROM customer c INNER JOIN users u ON u.email=c.customerid WHERE u.userid = '$userid'";
	$this->logit($uq);
	$tf = $this->masterfunctions->kda('companyname,phone');
	
	$supplier = $this->masterfunctions->iqa($uq,$tf);
	
	foreach ($supplier as $i=>$row){
	  $tech = $row['companyname'];
	  $techphone = $row['phone'];
	}
	
	$contact1 = $report['occupant1name'];
	$contact2 = $report['occupant2name'];
	
	#$agency = $this->GetAreaTopicValue($reportid,"General Comments","Agency");
	$agency = "DCFM Australia Pty Ltd";
	$propertytype = $this->GetAreaTopicValue($reportid,"General Comments","Property Type");

	$this->SetFont('Arial','',10);
	$x2 = 145;

	$this->DrawCellData("Client:",$clientname,40,$x2,8);
	$this->DrawCellData("Work Order #:",$workorder,40,$x2,8);
	$this->DrawCellData("DCFM Job No:",$jobid,40,$x2,8);
	$this->DrawCellData("Date of Inspection:",$attendDate,40,$x2,8);
	$this->DrawCellData("Onsite Client Rep:",$siteContact,40,$x2,8);
	$this->DrawCellData("Contact No:",$sitecontactphone,40,$x2,8);
	$this->DrawCellData("Others at Inspection:",$contact2,40,$x2,8);
	
	$this->DrawCellData("Inspector:",$tech,40,$x2,8);
	$this->DrawCellData("Inspector Contact:",$techphone,40,$x2,8);
	
	
	$this->DrawCellData("Agency:",$agency,40,$x2,8);
	$this->DrawCellData("Property Address:",$siteAddress,40,$x2,8);
	$this->DrawCellData("Property Type:",$propertytype,40,$x2,8);
	
	$this->SetFont('Arial','B',12);
	$this->Ln(5);
	
	#Summary Section
	$this->Cell(100,7,"SUMMARY",1,1,"C",1);
	
	$this->SetFont('Arial','B',10);
	$this->Cell(50,7,"Area",1,0,"L");
	$this->Cell(15,7,"Rating",1,0,"C");
	$this->Cell(35,7,"Overall Rating",1,1,"C");
		
	$this->SetFont('Arial','',10);
	
	$rq = new reportQuery();
	$dar = $rq->getAreaRatings($reportid);
		
	foreach ($dar as $i=>$row){
	   $area = $row['area_name'];
	   $av_dirty_rating = $row['av_dirty_rating'];
	   $av_damaged_rating = $row['av_damaged_rating'];	

	   if ($area != "General Comments")	  { 
 	   $tot_dirty_rating = $tot_dirty_rating + $av_dirty_rating;
	   $tot_damaged_rating = $tot_damaged_rating + $av_damaged_rating;
	   }
	   
	   $this->logit($area." ".$av_dirty_rating." ".$tot_dirty_rating);
	   if ($area != "General Comments")	  { 
        $this->DrawCellData($area,round($av_dirty_rating,2),50,15,7,"L","C");
		$irec++;   
	   }	
	}
	
	if ($irec==0){
		$overall_dirty_rating = 0;
		$overall_damaged_rating = 0;
	} else {
		$overall_dirty_rating = $tot_dirty_rating / $irec;
		$overall_damaged_rating = $tot_damaged_rating / $irec;
	}
	
		   $this->logit("dirty: ".$overall_dirty_rating." ".$overall_damaged_rating);
	
	#Add blank rows up to 13 total
	$maxareas = 15;
	#for($i=count($dar);$i<$maxareas;$i++){
		for($i=$irec;$i<$maxareas;$i++){
		$this->DrawCellData("","",50,15,7);
	}

	#Draw outside grey box
	$ypos = 149+11;
	$this->SetY($ypos);
	$this->SetX(75);
	$this->Cell(35,112,"",1,1,"C",1);

	#Draw inside box with overall rating
	$this->SetY($ypos+7);
	$this->SetX(80);
	$this->SetFillColor(255,255,255);
	$this->SetFont('Arial','B',14);
	$this->Cell(25,97,round($overall_dirty_rating,2),1,1,"C",1);

	#Draw General Box
	$elecmeter = $this->GetAreaTopicValue($reportid,"General Comments","Electricity Meter");
	$gasmeter = $this->GetAreaTopicValue($reportid,"General Comments","Gas Meter");
	$smokealarms = $this->GetAreaTopicValue($reportid,"General Comments","Smoke Alarms Working");
	$reporttypeid = $this->masterfunctions->singlevalue("ins_sitereport","report_type_id","ins_sitereport_id",$reportid);
	$this->logit("reporttype:".$reporttypeid);
	$this->logit("elecmeter:".$elecmeter);
	$this->logit("reporttype:".$reporttypeid);
	
	$inspectiontype = $this->masterfunctions->singlevalue("rep_reporttype","name","id",$reporttypeid);
	$this->logit("inspectiontype:".$inspectiontype);
	$inspectiontype = str_replace("Property Inspection","",$inspectiontype);
	$x1=120;
	$w2 = 30;
	$this->SetY(153);
	$this->SetX($x1);
	$this->SetFont('Arial','',10);
	$this->DrawCellData("Inspection Type",$inspectiontype,45,$w2,7);
	$this->SetX($x1);
	$this->DrawCellData("Electricity Meter",$elecmeter,45,$w2,7);
	$this->SetX($x1);
	$this->DrawCellData("Gas Meter",$gasmeter,45,$w2,7);
	$this->SetX($x1);
	$this->DrawCellData("Smoke Alarms Working",$smokealarms,45,$w2,7);
	
	#Draw Notes field
	#$this->SetY(255);
	#$this->SetX(10);
	#$this->SetFont('Arial','',10);
	#$this->Cell(190,20,"Notes",1,1,"L",1);
	
	#Draw Ratings Key
	$yr=190;
	$this->SetY($yr);
	$this->SetX(135);
	$this->SetFont('Arial','',8);
	$kleft = 135;
	$w1 = 12;
	$w2=48;
	
	$this->Cell(60,10,"RATING KEY",1,2,"C",0);
	$this->DrawCellData("10 - 9","EXCELLENT",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("8 - 7","GOOD",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("6 - 4","ACCEPTABLE/AVERAGE",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("3 - 2","POOR",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("1","REQUIRES URGENT ATTENTION",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("0","DOES NOT APPLY TO THIS SITE",$w1,$w2,10,"C","C");
	
}
function WalkthroughReportCoverPage($jobid,$reportid) {
	$this->logit("Enter WalkthroughReportCoverPage $jobid");
	$this->SetFillColor(211,211,211);
	$this->Ln(12);

	$this->SetFont('Arial','B',11);
	$this->Cell(185,10,"Inspection Details",1,1,"C",1);

	$h = 10;
	$width = 210;
	$firstline = 40;
	
	
	$job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	$this->logit("Custid: $custid");

	$clientname = $this->masterfunctions->singlevalue('customer','companyname','customerid',$custid);
	$this->logit("Jobs:".count($job));
	$this->logit("Client: ".$clientname);

	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$fmphone = $job['sitefmph'];
	$siteAddress = $job['siteline2'] .' '.$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$siteContact = $job["sitecontact"];
	$sitecontactphone = $job["sitephone"];

	$rq = "SELECT * from ins_sitereport where ins_sitereport_id=$reportid";
	$this->logit($rq);
	$tfr = $this->masterfunctions->kda("inspection_date,create_user");
	#$report = iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$dar = $this->masterfunctions->iqa($rq,$tfr);
	foreach ($dar as $i=>$row){
	 $attendDate = $row['inspection_date'];
	 $userid = $row['create_user'];
	}
	
	$uq = "SELECT companyname,phone FROM customer c INNER JOIN users u ON u.email=c.customerid WHERE u.userid = '$userid'";
	$this->logit($uq);
	$tf = $this->masterfunctions->kda('companyname,phone');
	
	$supplier = $this->masterfunctions->iqa($uq,$tf);
	
	foreach ($supplier as $i=>$row){
	  $tech = $row['companyname'];
	  $techphone = $row['phone'];
	}
	
	$contact1 = $report['occupant1name'];
	$contact2 = $report['occupant2name'];
	
	#$agency = $this->GetAreaTopicValue($reportid,"General Comments","Agency");
	$agency = "DCFM Australia Pty Ltd";
	$propertytype = $this->GetAreaTopicValue($reportid,"General Comments","Property Type");

	$this->SetFont('Arial','',10);
	$x2 = 145;

	$this->DrawCellData("Client:",$clientname,40,$x2,8);
	$this->DrawCellData("Work Order #:",$workorder,40,$x2,8);
	$this->DrawCellData("DCFM Job No:",$jobid,40,$x2,8);
	$this->DrawCellData("Date of Inspection:",$attendDate,40,$x2,8);
	$this->DrawCellData("Onsite Client Rep:",$siteContact,40,$x2,8);
	$this->DrawCellData("Contact No:",$sitecontactphone,40,$x2,8);
	$this->DrawCellData("Others at Inspection:",$contact2,40,$x2,8);
	
	$this->DrawCellData("Inspector:",$tech,40,$x2,8);
	$this->DrawCellData("Inspector Contact:",$techphone,40,$x2,8);
	$this->DrawCellData("Property Address:",$siteAddress,40,$x2,8);
		
	$this->SetFont('Arial','B',12);
	$this->Ln(5);
	
	#Summary Section
	$this->Cell(100,7,"SUMMARY",1,1,"C",1);
	
	$this->SetFont('Arial','B',10);
	$this->Cell(50,7,"Area",1,0,"L");
	$this->Cell(15,7,"Rating",1,0,"C");
	$this->Cell(35,7,"Overall Rating",1,1,"C");
		
	$this->SetFont('Arial','',10);
	
	$rq = new reportQuery();
	$dar = $rq->getAreaRatingsByGuid($reportid);
		
	foreach ($dar as $i=>$row){
	   $area = $row['area_description'];
	   $av_dirty_rating = $row['av_dirty_rating'];
	   $av_damaged_rating = $row['av_damaged_rating'];	

	   if ($area != "General Comments")	  { 
 	   $tot_dirty_rating = $tot_dirty_rating + $av_dirty_rating;
	   $tot_damaged_rating = $tot_damaged_rating + $av_damaged_rating;
	   }
	   
	   $this->logit($area." ".$av_dirty_rating." ".$tot_dirty_rating);
	   if ($area != "General Comments")	  { 
        $this->DrawCellData($area,round($av_dirty_rating,2),50,15,7,"L","C");
		$irec++;   
	   }	
	}
	
	if ($irec==0){
		$overall_dirty_rating = 0;
		$overall_damaged_rating = 0;
	} else {
		$overall_dirty_rating = $tot_dirty_rating / $irec;
		$overall_damaged_rating = $tot_damaged_rating / $irec;
	}
	
		   $this->logit("dirty: ".$overall_dirty_rating." ".$overall_damaged_rating);
	
	#Add blank rows up to 13 total
	$maxareas = 16;
	#for($i=count($dar);$i<$maxareas;$i++){
		for($i=$irec;$i<$maxareas;$i++){
		$this->DrawCellData("","",50,15,7);
	}

	#Draw outside grey box
	//$ypos = 149+11;
	$ypos=149+2;
	$this->SetY($ypos);
	$this->SetX(75);
	$this->Cell(35,112,"",1,1,"C",1);

	#Draw inside box with overall rating
	$this->SetY($ypos+7);
	$this->SetX(80);
	$this->SetFillColor(255,255,255);
	$this->SetFont('Arial','B',14);
	$this->Cell(25,97,round($overall_dirty_rating,2),1,1,"C",1);

	
	#Draw Ratings Key
	//$yr=190;
	$yr=137;
	$this->SetY($yr);
	$this->SetX(135);
	$this->SetFont('Arial','',8);
	$kleft = 135;
	$w1 = 12;
	$w2=48;
	
	$this->Cell(60,10,"RATING KEY",1,2,"C",0);
	$this->DrawCellData("10 - 9","EXCELLENT",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("8 - 7","GOOD",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("6 - 4","ACCEPTABLE/AVERAGE",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("3 - 2","POOR",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("1","REQUIRES URGENT ATTENTION",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("0","DOES NOT APPLY TO THIS SITE",$w1,$w2,10,"C","C");
	
}
function ErgonInspectionReportCoverPage($jobid,$reportid) {
	$this->logit("Enter InspectionReportCoverPage $jobid");
	$this->SetFillColor(192,192,192);
	$this->Ln(12);

	$width = 188;
	$cwidth = 40;
	$dwidth = $width-$cwidth;
	
	#$this->Text(10,36,"Residential Inspection Report");
	#$this->Ln(10);
	$this->SetFont('Arial','B',12);
	$this->Cell($width,10,"Inspection Details",1,1,"C",1);

	$h = 10;
	$width = 200;
	$firstline = 40;
	
	
	$job = $this->masterfunctions->iqatr('jobs','jobid',$jobid);
	$custid = $job['customerid'];
	$this->logit("Custid: $custid");

	$clientname = $this->masterfunctions->singlevalue('customer','companyname','customerid',$custid);
	$this->logit("Jobs:".count($job));
	$this->logit("Client: ".$clientname);

	$workorder = $job['custordref'];
	$fmname = $job['sitefm'];
	$fmphone = $job['sitefmph'];
	$siteAddress = $job['siteline2'] .' '.$job['sitesuburb'].' '.$job['sitestate'].' '.$job['sitepostcode'];
	$jobDescription = $job['jobdescription'];
	$siteContact = $job["sitecontact"];
	$sitecontactphone = $job["sitephone"];

	$rq = "SELECT * from ins_sitereport where ins_sitereport_id=$reportid";
	$this->logit($rq);
	$tfr = $this->masterfunctions->kda("inspection_date,create_user");
	#$report = $this->masterfunctions->iqatr('ins_sitereport','ins_sitereport_id',$reportid);
	$dar = $this->masterfunctions->iqa($rq,$tfr);
	foreach ($dar as $i=>$row){
	 $attendDate = $row['inspection_date'];
	 $userid = $row['create_user'];
	}
	
	$uq = "SELECT companyname,phone FROM customer c INNER JOIN users u ON u.email=c.customerid WHERE u.userid = '$userid'";
	$this->logit($uq);
	$tf = $this->masterfunctions->kda('companyname,phone');
	
	$supplier = $this->masterfunctions->iqa($uq,$tf);
	
	foreach ($supplier as $i=>$row){
	  $tech = $row['companyname'];
	  $techphone = $row['phone'];
	}
	
	$contact1 = $report['occupant1name'];
	$contact2 = $report['occupant2name'];
	
	#$agency = $this->GetAreaTopicValue($reportid,"General Comments","Agency");
	$agency = "DCFM Australia Pty Ltd";
	$propertytype = $this->GetAreaTopicValue($reportid,"General Comments","Property Type");

	$this->SetFont('Arial','',10);

	$this->DrawCellData("Client:",$clientname,$cwidth,$dwidth,8);
	$this->DrawCellData("Work Order #:",$workorder,$cwidth,$dwidth,8);
	$this->DrawCellData("DCFM Job No:",$jobid,$cwidth,$dwidth,8);
	$this->DrawCellData("Date of Inspection:",$attendDate,$cwidth,$dwidth,8);
	$this->DrawCellData("Onsite Client Rep:",$siteContact,$cwidth,$dwidth,8);
	$this->DrawCellData("Contact No:",$sitecontactphone,$cwidth,$dwidth,8);
	$this->DrawCellData("Others at Inspection:",$contact2,$cwidth,$dwidth,8);
	
	$this->DrawCellData("Inspector:",$tech,$cwidth,$dwidth,8);
	$this->DrawCellData("Inspector Contact:",$techphone,$cwidth,$dwidth,8);
	
	
	$this->DrawCellData("Agency:",$agency,$cwidth,$dwidth,8);
	$this->DrawCellData("Property Address:",$siteAddress,$cwidth,$dwidth,8);
	$this->DrawCellData("Property Type:",$propertytype,$cwidth,$dwidth,8);
	
	$this->SetFont('Arial','B',12);
	$this->Ln(5);
	
	#Draw Ratings Key
	$this->SetY(160);
	$this->SetX(10);
	$this->SetFont('Arial','',8);
	$kleft = 10;
	$w1 = 12;
	$w2=51;
	
	$this->Cell($w1+$w2,10,"RATING KEY",1,2,"C",0);
	$this->DrawCellData("5","BEST POSSIBLE CONDITION",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("4","PEFORMING WELL",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("3","MEETS REQUIREMENTS",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("2","FAIR CONDITION",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("1","POOR CONDITION",$w1,$w2,10,"C","C");
	$this->SetX($kleft);
	$this->DrawCellData("0","DOES NOT APPLY TO THIS SITE",$w1,$w2,10,"C","C");
	
	
	$rq = new reportQuery();
	$dar = $rq->getAreaRatingsByGuid($reportid);
	
	$overall_dirty_rating = $this->GetOverallDirtyRating($dar);
		
	$this->SetFont('Arial','B',12);
	$this->SetY(240);
	
	$this->Cell($w1+$w2,12,"OVERALL RATING",1,2,"C",0);
	$this->Cell($w1+$w2,12,round($overall_dirty_rating,2),1,2,"C",0);
	$this->SetFont('Arial','',10);
	
	#$this->SetFillColor(255,255,255);
	#$this->SetFont('Arial','B',14);
	#$this->Cell(25,196,round($overall_dirty_rating,2),1,1,"C",1);
	
	
	
	$this->AddPage();
	#Summary Section
	$this->SetY(45);
	$this->SetFont('Arial','B',10);
	$this->Cell(188,7,"SUMMARY",1,1,"C",1);
	
	$this->Cell(173,7,"Area",1,0,"L");
	$this->Cell(15,7,"Rating",1,1,"C");
			
	$this->SetFont('Arial','',10);
	
	
	$prec=0;
	$sumpages=0;	
	foreach ($dar as $i=>$row){
	   $area = $row['area_description'];
	   $av_dirty_rating = $row['av_dirty_rating'];
	   $av_damaged_rating = $row['av_damaged_rating'];	

	   if ($area != "General Comments")	  { 
 	   $tot_dirty_rating = $tot_dirty_rating + $av_dirty_rating;
	   $tot_damaged_rating = $tot_damaged_rating + $av_damaged_rating;
	   }
	   
	   $this->logit($area." ".$av_dirty_rating." ".$tot_dirty_rating);
	   if ($area != "General Comments")	  { 
        $this->DrawCellData($area,round($av_dirty_rating,2),173,15,7,"L","C");
		$irec++; 
		$prec++;  
	   }
	   
	   if ($prec>30){
	   	$this->AddPage();
		$sumpages++;
		$page = $sumpages+1;   
		$prec=0;
		$this->SetY(45);
		$this->SetFont('Arial','B',10);
		$this->Cell(188,7,"SUMMARY (Page $page)",1,1,"C",1);
	
		$this->Cell(173,7,"Area",1,0,"L");
		$this->Cell(15,7,"Rating",1,1,"C");
        $this->SetFont('Arial','',10);
  
	   }	
	}
	
	if ($irec==0){
		$overall_dirty_rating = 0;
		$overall_damaged_rating = 0;
	} else {
		$overall_dirty_rating = $tot_dirty_rating / $irec;
		$overall_damaged_rating = $tot_damaged_rating / $irec;
	}
	
		   $this->logit("dirty: ".$overall_dirty_rating." ".$overall_damaged_rating);
	
	#Add blank rows up to 13 total
	$maxareas = 93;
	for($i=count($dar);$i<$maxareas;$i++){
	#	$this->DrawCellData("","",173,15,7);
	}


	
}
	function getOverallDirtyRating($dar){
				
	foreach ($dar as $i=>$row){
	   $area = $row['area_description'];
	   $av_dirty_rating = $row['av_dirty_rating'];

	   if ($area != "General Comments" && $av_dirty_rating>0 )	  { 
 	   $tot_dirty_rating = $tot_dirty_rating + $av_dirty_rating;
		   
	   }
	   
	   $this->logit($area." ".$av_dirty_rating." ".$tot_dirty_rating);
	   if ($area != "General Comments"  && $av_dirty_rating>0)	  { 
		$irec++; 
		$prec++;  
	   }
	   
	}
	
	if ($irec==0){
		$overall_dirty_rating = 0;
	} else {
		$overall_dirty_rating = $tot_dirty_rating / $irec;
	}
	
	$this->logit("dirty: ".$overall_dirty_rating);
	return $overall_dirty_rating;		
		
	}
	
	function Inspectioncomments($reportid){
		
	$this->AddPage();
	$this->Ln(10);
	
	$rq = new reportQuery();
	$da = $rq->getComments($reportid);
	
	if (isset($da)){
	  foreach ($da as $i=>$row){
		$ctype = strtolower($row['topic']);
		$this->logit("ctype: $ctype");
		if (strpos($ctype,'tenant') !== false){
			$tcomments = $row['comments'];
		}
		if (strpos($ctype,'inspector') !== false){
			$icomments = $row['comments'];
		}
	  }
	}
	$this->SetY(40);
	$this->SetFont('Arial','B',13);
	$this->Write(8,"Tenant's General Comments");
	$this->Ln(12);
	$this->SetFont('Arial','',9);
	$this->Write(6,$tcomments);
	$this->SetX(10);
	$this->SetY(40);
	$this->Cell(0,50,"",1,0,'L');
	#$this->Ln();

	$this->SetY(100);
	$this->SetFont('Arial','B',13);
	$this->Write(10,"Inspector's General Comments");
	$this->Ln(12);
	$this->SetFont('Arial','',9);
	$this->Write(6,$icomments);
	$this->SetX(10);
	$this->SetY(100);
		
	$this->Cell(0,160,"",1,0,'L');	
	
	}
	
	function DrawAreaQandAwithPhoto($jobid,$reportid,$area){
 	$this->logit("Enter DrawAreaQandAwithPhoto $reportid,$area");
 	$this->SetFont('Arial','IB',10);

	$rootdocfolder = $this->rootdocfolder;

	$qaq = "SELECT cl.name AS Question,s.scope_description AS Response FROM ins_scope s INNER JOIN rep_scope_checklist cl ON s.scope_category_id=cl.id 
			WHERE ins_sitereport_id=$reportid AND scope_area_name = '$area' ORDER BY cl.sortorder
	";
	$this->logit($qaq);		
	$tf = $this->masterfunctions->kda("Question,Response");		
	$da = $this->masterfunctions->iqa($qaq,$tf);
	
	$ypos = 47;
	$xpos = 10;
	$Arealabel = "Area: $area";
	$this->Text($xpos,$ypos,$Arealabel);
	
	
	foreach ($da as $i=>$row)
	{
	  $Question = $row['Question'];
	  $Response = $row['Response'];
	  $ypos = $ypos + 10;

	  $this->SetFont('Arial','IB',10);
	  $this->Text($xpos,$ypos,$Question);
	  $ypos = $ypos + 0;
	  
	  $this->SetFont('Arial','',10);
	  #$this->Text($xpos+10,$ypos,$Response);
	  $this->SetXY($xpos+7,$ypos);
	  $this->Write(8,$Response);
	  #$scopeData[] = array($Question,$Response);
 	  
	}
	

	$q = "SELECT d.documentid,d.documentdesc AS caption,d.docnote AS notes,docfolder,docformat FROM document d INNER JOIN ins_scope s ON d.scope_guid = s.scope_guid 
	WHERE ins_sitereport_id=$reportid AND scope_area_name = '$area'";
	$this->logIt($q);
	
	$tf = $this->masterfunctions->kda("documentid,caption,notes,docfolder,docformat");
	$da = $this->masterfunctions->iqa($q,$tf);
	
	 
	if (count($da)>0) {
		$ypos = $ypos+12;
		$this->logit("Before photos caption: $ypos");
				if ($ypos>100){
	 				$this->newPage("L");
  		  			$ypos= 50;
	     		}
	
		
		$this->Text($xpos,$ypos,"Before Photos:");
		$xposafter = 170;
		$this->Text($xposafter,$ypos,"After Photos:");
	} else {
		return;
	}
	
	$imageheight = 50;
	$xposbefore = 10;
	$yposbefore = $ypos+5;
	$yposafter = $ypos+5;
	$yposimagespace = 30;
	
	
	foreach ($da as $i=>$row){
		$documentid = $row['documentid'];
		$caption = $row['caption'];
		$note = $row['notes'];
		$docfolder = $row['docfolder'];		
	    $fmt = $row['docformat'];
	
			
		$imagefile = $rootdocfolder."$documentid.$fmt";
	
		switch ($docfolder){
			case "Inspection Photo Pre-Works":
				
				if ($yposbefore>110 && $precount<count($da)){
	 				$this->newPage("L");
  		  			$yposbefore= 50;
					$yposafter = 50;	 
	     		}
				
				$this->DrawHeightImage($imagefile,$xposbefore,$yposbefore,$imageheight,$caption,$note,$documentid);
				$yposbefore = $yposbefore + $imageheight + $yposimagespace;
				$precount++;
				
			

			break;
			case "Inspection Photo Post-Works":
				$this->DrawHeightImage($imagefile,$xposafter,$yposafter,$imageheight,$caption,$note,$documentid);
			
				$yposafter = $yposafter + $imageheight + $yposimagespace;
				
				if ($yposafter>150){
	 				$this->newPage("L");
  		  			$yposbefore=50;
					$yposafter = 50;	 
	     		}
			break;
			default:
					
		}	
	}	
}

 	function DrawQandA($reportid){
 	$this->logit("Enter DrawQandA");
 	$this->SetFont('Arial','IB',10);

	$qaq = "SELECT s.scope_area_name as Area,LEFT(cl.name,40) as Question,LEFT(s.scope_description,50) as Response,scope_width,scope_length,scope_labour_hours as labour,scope_approximate_size as size FROM ins_scope s INNER JOIN rep_scope_checklist cl ON s.scope_category_id=cl.id 
			WHERE ins_sitereport_id=$reportid ORDER BY cl.sortorder";
	$this->logit($qaq);		
	$tf = $this->masterfunctions->kda("Area,Question,Response,labour,size,scope_width,scope_length");		
	$da = $this->masterfunctions->iqa($qaq,$tf);
	$scopeData = array();
	foreach ($da as $i=>$row)
	{
	  $Area 	= $row['Area'];
	  $Question = $row['Question'];
	  $Response = $row['Response'];
	  $Labour   = $row['labour'];
	  $TotSize  = $row['size'];
	  $width 	= $row['scope_width'];
	  $length   = $row['scope_length']; 

	  if ($width !='' && $length != ''){
	  	$Size = "($width x $length) - $TotSize";
	  } else {
	  	$Size = $TotSize;
	  }

	  $scopeData[] = array($Area,$Question,$Response,$Size,$Labour);
	}
	
 	$header = array("Area","Question","Response","Approx. Size","Labour(h)");

	$Columns[] = array("40","L");
	$Columns[] = array("80","L");
 	$Columns[] = array("87","L");
	$Columns[] = array("50","C");
	$Columns[] = array("20","C");
		
 	$this->logit("QandA: ".count($da));
	
 	$this->DrawTable($header, $Columns, $scopeData);
	}
	
	function ConditionSummary($reportid){
	$this->logit("Enter Condition Summary");			
	#$this->SetY(60);
	
	#$rq = new reportQuery();
	#$da = $rq->getComments($reportid);
	
	$q = "SELECT rat.name,ratv.description,rav.area_description FROM rep_report_areatypetopic_value ratv 
		INNER JOIN rep_area_topic rat ON rat.id=ratv.`area_topic_id`
		INNER JOIN rep_area_value rav ON areavalue_guid=rav.`guid`
		WHERE ratv.report_id=$reportid AND rat.name IN ('Asset Criticality1SELECT * from projhistory','Desired Condition1','Remaining Useful Life1')";
	
	
	$q="SELECT description,asset_criticality,actual_condition,remaining_life_expectancy FROM rep_asset a INNER JOIN rep_area_value_asset rava ON a.id=rava.asset_id
		INNER JOIN rep_area_value rav ON areavalue_guid=rav.guid 
		WHERE report_id=$reportid
		ORDER BY sortorder";	
	$this->logit($q);
	$tf = $this->masterfunctions->kda("description,asset_criticality,actual_condition,remaining_life_expectancy");
	$da = $this->masterfunctions->iqa($q,$tf);
	
	$rec = count($da);
	$this->logit("Sumamry report $rec rows");
	if ($rec == 0) {
		$this->logit("No condition summary data");
		return;
	}
	
	foreach ($da as $i=>$row){
	   $asset = $row['description'];
	   $crit = $row['asset_criticality'];
	   $actcond = $row['actual_condition'];
	   $life = $row['remaining_life_expectancy'];
	   #$this->logit("Adding summary results: a:$area  t:$topic  v:$value");
	   #$results[$area][$topic] = $value;
	   $Data[] = Array($asset,$crit,$actcond,$life);
	
	}

	#foreach($results as $key=>$value){
	#	$this->logit("Asset Area: $key");
	#	$Data[] = Array($key,$value['Asset Criticality'],$value['Desired Condition'],$value['Remaining Useful Life']);
	#}
	
	$this->newPage("L");
	$this->Ln(10);
	
	$this->SetY(45);
	$this->SetFont('Arial','B',10);
	$this->Cell(275,7,"CONDITION SUMMARY",1,1,"C",1);
	
	
	
	
	#$this->Cell(83,7,"Area",1,0,"L");
	#$this->Cell(45,7,"Asset Criticality",1,1,"C");
	#$this->Cell(45,7,"Desired Condition",1,1,"C");
	#$this->Cell(45,7,"Remaining Useful Life",1,1,"C");
			
	$this->SetFont('Arial','',10);
	
	$header = array("Area","Asset Criticality","Desired Condition","Remaining Useful Life");
	$Columns[] = array("105","L");
	$Columns[] = array("60","C");
	$Columns[] = array("60","C");
	$Columns[] = array("50","C");
		
	$this->DrawTable($header, $Columns, $Data);
	

	
		
	}
}	//end class
?>	
	