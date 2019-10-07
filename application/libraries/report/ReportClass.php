<?php 
/**
 * Report Libraries Class
 *
 * This is a Report class for Report Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');  
require_once( __DIR__.'/../customer/CustomerClass.php');  

/**
 * Report Libraries Class
 *
 * This is a Invoice class for Report Opration  
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Report
 * @filesource          ReportClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 class ReportClass extends MY_Model
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
    
    
    /**
    * customer class 
    * 
    * @var class
    */
    private $customerClass;

    
    /**
    * Class constructor
    *
    * @return  void
    */
    
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'ReportClass');
        $this->sharedClass = new SharedClass();
        $this->customerClass = new CustomerClass();
    }
    
    /**
    * This function use for getting Reports
    * @param integer $size - for getting data limited
    * @param integer $start - its require when you use $size param
    * @param string $field - it is use for sort
    * @param string $order - it is use for sorting order ASC/DESC
    * @return array 
    */
    public function getReports($size, $start, $field, $order) {
        
		$sql = 'SELECT r.name,description,rf.`route_url` AS familyroute,r.route_url AS reportroute '
                . 'FROM report r '
                . 'INNER JOIN reportfamily rf ON r.familyid=rf.`id` '
                . 'WHERE rf.`isactive`=1 AND r.isactive=1 AND inclientportal=1 ORDER BY r.sortorder, r.name';
        
        $this->db->select("r.id");
        $this->db->from('report r');
        $this->db->join('reportfamily rf', 'r.familyid=rf.id', 'left');
        $this->db->where('rf.isactive', 1);
        $this->db->where('r.isactive', 1);
        $this->db->where('r.inclientportal', 1);
        
        
        
        $trows = $this->db->count_all_results();
            
        $this->db->select("r.id, r.name, description, rf.route_url AS familyroute, r.route_url AS reportroute, rf.name AS familyname");
        $this->db->from('report r');
        $this->db->join('reportfamily rf', 'r.familyid=rf.id', 'left');
        $this->db->where('rf.isactive', 1);
        $this->db->where('r.isactive', 1);
        $this->db->where('r.inclientportal', 1);
        
        if ($size != NULL) {
            $this->db->limit($size, $start);
        }
        if ($field != '') {
            $this->db->order_by($field, $order);
        } else {
            $this->db->order_by('r.sortorder, r.name');
        }

        $data = array(
            'trows' => $trows, 
            'data' => $this->db->get()->result_array()
        );		
        
        $this->LogClass->log('Get Reports Data Query : '. $this->db->last_query());
        
        return $data;
        
    }
      
    
    public function createPDF($params) {
        
        //1 - load multiple models
       require_once('ReportPDF.php');
 
       //2 - initialize instances
        $pdf = new ReportPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        //3 - get the parts connected
        $loggedUserData = $this->sharedClass->getLoggedUser($params['logged_contactid']);
        $Customerdata = $this->db->where('customerid', $loggedUserData['customerid'])->get('customer')->row_array();
        
         //4 - start the process
       
        //set document information
         $headerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $footerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $logoImage = array('image'=>K_PATH_IMAGES.'logo.png', 'imagetype'=>'PNG');
       
        
        $brandingImage = $this->sharedClass->getBrandingImage('H', 'R');
        if (count($brandingImage)>0) {
            $dirpath = $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'];
            
			//aklog($dirpath);
			                
            if (file_exists($dirpath)) {
               $headerImage = array('image'=> $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'],'imagetype'=> $brandingImage['docformat']);
            }
        }
        
        $brandingImage = $this->sharedClass->getBrandingImage('F', 'R');
        if (count($brandingImage)>0) {
            $dirpath = $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $footerImage = array('image'=> $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'],'imagetype'=> $brandingImage['docformat']);
            }
        }
      
        //set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('My Reports');
        $pdf->SetSubject('Reports');
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT,PDF_MARGIN_BOTTOM+30);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        


        // ---------------------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 8);
        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
   
        
        $pdf->setHeaderImage($headerImage);
        $pdf->setfooterImage($footerImage);
        $pdf->setLogoImage($logoImage);
        
        // add a page
        $pdf->AddPage();
         
       //Extension Summary
        $report = '';
        if($params['report'] == 'extensionsummary') {
            $report = 'Extension Summary';
        } else if($params['report'] == 'ontimeattendance') {
            $report = 'On Time Attendance';
        } else if($params['report'] == 'jobcounts') {
            $report = 'Job Counts';
        } else if($params['report'] == 'salehistory') {
            $report = 'Sale History';
        } else if($params['report'] == 'quoteconversion') {
            $report = 'Quote Conversion';
        } else if($params['report'] == 'workcompletion') {
            $report = 'Work Completion';
        } else if($params['report'] == 'jobextensions') {
            $report = 'Job Extensions';
        } else if($params['report'] == 'ontimecompletion') {
            $report = 'On Time Completion';
        } else if($params['report'] == 'reconciliation') {
            $report = 'Job Counts/Invoice Date Reconciliation';
        } else if($params['report'] == 'extensiondetail') {
            $report = 'Extension Detail';
        }
        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 7, $report, 0, false, 'C', false, '', 0, false, 'M', 'M');
        $pdf->SetFont('helvetica', '', 8);
        
        if($params['report'] == 'extensionsummary') {
            $this->extensionSummaryReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'ontimeattendance') {
            $this->onTimeAttendanceReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'jobcounts') {
            $this->jobCountsReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'salehistory') {
            $this->saleHistoryReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'quoteconversion') {
            $this->quoteConversionReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'workcompletion') {
            $this->workCompletionReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'jobextensions') {
            $this->jobExtensionsReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'ontimecompletion') {
            $this->onTimeCompletionReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'reconciliation') {
            $this->invoiceReconciliationReport($pdf, $Customerdata, $params, $loggedUserData);
        }
        if($params['report'] == 'extensiondetail') {
            $this->extensionDetailReport($pdf, $Customerdata, $params, $loggedUserData);
        }
    }

    /**
     * 
     * @param date $customerid
     * @param date $fromdate
    * @param date $todate
     * @return array 
     */
    public function getExtensionJobNotes($customerid, $fromdate, $todate) {
        $this->db->select('jn.notes, j.sitefm');
        $this->db->from('jobnote jn');  
        $this->db->join('jobs j', 'j.jobid=jn.jobid', 'inner');  
        
        $this->db->where('j.customerid', $customerid);
        $this->db->where('jcompletedate >=', $fromdate);
        $this->db->where('jcompletedate <=', $todate);
        $this->db->where('jn.ntype', 'extend');
        $this->db->where('notetype', 'client');
        $this->db->where('j.jobstage', 'client_notified');
            
            
//        if($loggedUserData['role'] == 'site contact') {
//            $this->db->where('a.sitecontactid', $loggedUserData['contactid']); 
//        }
//        elseif ($loggedUserData['role'] == 'sitefm') {
//            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
//            $this->db->where(" (a.contactid=".$loggedUserData['contactid']." or j.sitefmemail='".$loggedUserData['email']."' or j.contactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))");
//        }

       
        
        $data = $this->db->get()->result_array();
        
        return $data;
    }
    
    public function getExtendReason($note){ 
        
        $ipos1 = strpos($note,"Reason chosen for extension:",0); 
        $ipos2 = strpos($note,"Job No");
        $reason = substr($note, $ipos1+28,$ipos2-$ipos1-28); 
        $reason = str_replace("<br>", "", $reason); 
        return $reason; 
        
    }
    
      /**
    * This function Get Extension Counts
     * 
    * @param integer $customerid selected Customer Id
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getExtensionCounts($customerid, $fromdate, $todate) {
       
        $sql = "SELECT extcount,COUNT(extcount) as jobs "
                . " FROM (SELECT j.jobid,COUNT(*) AS extcount "
                . " FROM jobnote jn INNER JOIN jobs j ON j.jobid=jn.jobid "
                . " WHERE  ntype = 'extend' AND notetype = 'client'  "
                . " AND j.customerid=$customerid AND jcompletedate >= '$fromdate' AND jcompletedate <= '$todate' "
                . " and j.jobstage = 'client_notified' GROUP BY jobid) AS c GROUP BY extcount";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
	
    /**
    * This function Get Extension Report by month
     * 
    * @param integer $customerid selected Customer Id
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getExtensionByMonth($customerid, $fromdate, $todate) {
       
        $sql = "SELECT m, Y, SUM(EXTENDED) AS extended, SUM(TOTALJOBS-EXTENDED) AS notextended, TOTALJOBS as totaljobs FROM"
            . " (SELECT MONTH(jcompletedate) AS m, YEAR(jcompletedate) AS Y, COUNT(*) AS EXTENDED, 0 AS TOTALJOBS"
            . " FROM jobnote jn"
            . " INNER JOIN jobs j ON j.jobid=jn.jobid WHERE ntype = 'extend' AND notetype = 'client' AND"
            . " j.customerid= ".$customerid." AND jcompletedate >= '".$fromdate."' AND jcompletedate <= '".$todate."' AND"
            . " j.jobstage = 'client_notified' GROUP BY MONTH(jcompletedate),YEAR(jcompletedate) UNION"
            . " SELECT MONTH(jcompletedate) AS m, YEAR(jcompletedate) AS Y, 0 AS EXTENDED, COUNT(*) AS TOTALJOBS"
            . " FROM jobs j WHERE  j.customerid = ".$customerid.""
            . " AND jcompletedate >= '".$fromdate."' AND jcompletedate <= '".$todate."' AND j.jobstage = 'client_notified'"
            . " GROUP BY MONTH(jcompletedate),YEAR(jcompletedate) ) AS DATA GROUP BY m, Y ORDER BY Y, m";
        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        $year = '';
        $extensionByMonth = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['Y']) {
                $year = $value['Y'];
                $i++;
                $extensionByMonth[$i][] = $value;
            } else {
                $extensionByMonth[$i][] = $value;
            }
        }
        return $extensionByMonth;
    }
    
    /**
    * This function generate Extension summary report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function extensionSummaryReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        
        
        
        $extentionJobNoteData= $this->getExtensionJobNotes($loggedUserData['customerid'], $params['fromdate'], $params['todate']);
        
        $extentionReasons = array();
        $extentionFMs = array();
        foreach ($extentionJobNoteData as $key => $value) {
      
            $reason = $this->getExtendReason($value['notes']);
            $reason_key = str_replace(' ', '_', strtolower($reason));
            $reason_key = preg_replace('/[^A-Za-z0-9\-]/', '', $reason_key);
            if(isset($extentionReasons[$reason_key])){
                $extentionReasons[$reason_key]['count'] = (int)$extentionReasons[$reason_key]['count'] + 1;
            }
            else{
                $extentionReasons[$reason_key] = array('name' =>$reason, 'count' =>1);
            }
            $extentionFMs[] = $value['sitefm'];
        }
        
        $extentionFMs = array_unique($extentionFMs);
      
        $pdf->Ln(4);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 7, '(Based on job completion Date)'.PHP_EOL.' Extension Causes Summary ('.count($extentionReasons).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->SetFont('helvetica', '', 9);
        $color1 = "#FFFFFF";
        $color2 = "#F4F9FF";
        $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                <thead>
                <tr nobr="true">
                    <th style ="border:1px solid black;font-weight:bold;width:540px;background-color:#CCCCCC;text-align:center;">Reason</th>
                    <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Count</th>
                </tr></thead><tbody>';

        $count = 0;
        $tcount = 0;
        foreach ($extentionReasons as $key => $value) {
            $tcount = $tcount + (int)$value['count'];
            $count = $count+1;
            $row_color = ($count % 2) ? $color1 : $color2;
            $html .= '<tr nobr="true" bgcolor="'. $row_color.'">
                    <td style ="border:1px solid black;width:540px;text-align:center;">' . $value['name'] . '</td>
                    <td style ="border:1px solid black;width:100px;text-align:right;">' . $value['count'] . '</td>
            </tr>';
        }
        $html .= '</tbody><tfoot>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:540px;background-color:#CCCCCC;text-align:right;">Total</th>
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:right;">'.$tcount.'</th>
                    </tr></tfoot></table>';

        
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        foreach ($extentionFMs as $key2 => $value2) {
            
            $extentionFMReason = array();
            
            foreach ($extentionJobNoteData as $key => $value) {
                if($value['sitefm'] == $value2){
                    $reason = $this->getExtendReason($value['notes']);
                    $reason_key = str_replace(' ', '_', strtolower($reason));
                    $reason_key = preg_replace('/[^A-Za-z0-9\-]/', '', $reason_key);
                    if(isset($extentionFMReason[$reason_key])){
                        $extentionFMReason[$reason_key]['count'] = (int)$extentionFMReason[$reason_key]['count'] + 1;
                    }
                    else{
                        $extentionFMReason[$reason_key] = array('name' =>$reason, 'count' =>1);
                    }
                }
            }
            

            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'Extension Causes ('.$value2.') ('.count($extentionFMReason).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            $color1 = "#FFFFFF";
            $color2 = "#F4F9FF";
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:540px;background-color:#CCCCCC;text-align:center;">Reason</th>
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Count</th>
                    </tr></thead><tbody>';

            $count = 0;
            $tcount = 0;
            foreach ($extentionFMReason as $key => $value) {
                $tcount = $tcount + (int)$value['count'];
                $count = $count+1;
                $row_color = ($count % 2) ? $color1 : $color2;
                $html .= '<tr nobr="true" bgcolor="'. $row_color.'">
                        <td style ="border:1px solid black;width:540px;text-align:center;">' . $value['name'] . '</td>
                        <td style ="border:1px solid black;width:100px;text-align:right;">' . $value['count'] . '</td>
                </tr>';
            }
            $html .= '</tbody><tfoot>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:540px;background-color:#CCCCCC;text-align:right;">Total</th>
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:right;">'.$tcount.'</th>
                    </tr></tfoot></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            
        }
        
        
        $extentionCounts= $this->getExtensionCounts($loggedUserData['customerid'], $params['fromdate'], $params['todate']);
      
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 7,  'Extension Counts Per Job ('.count($extentionCounts).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->SetFont('helvetica', '', 9);
        $color1 = "#FFFFFF";
        $color2 = "#F4F9FF";
        $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                <thead>
                <tr nobr="true">
                    <th style ="border:1px solid black;font-weight:bold;width:540px;background-color:#CCCCCC;text-align:center;">Extensions Per Job</th>
                    <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Jobs</th>
                </tr></thead><tbody>';

        $count = 0;
        foreach ($extentionCounts as $key => $value) {
            
            $count = $count+1;
            $row_color = ($count % 2) ? $color1 : $color2;
            $html .= '<tr nobr="true" bgcolor="'. $row_color.'">
                    <td style ="border:1px solid black;width:540px;text-align:center;">' . $value['extcount'] . '</td>
                    <td style ="border:1px solid black;width:100px;text-align:right;">' . $value['jobs'] . '</td>
            </tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
		
        $extensionByMonth = $this->reportclass->getExtensionByMonth($loggedUserData['customerid'], $params['fromdate'], $params['todate']);
        
        $chartReportImages = $params['rp'];
        
        foreach($extensionByMonth as $key=>$value) {
            
            $months = array();
            $extended = array();
            $notextended = array();
            
            $thtdwidth = 540/count($value);
            foreach($value as $key1=>$value1) {
                array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:' . $thtdwidth . 'px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $value1['m'], 10)).'</th>');
                array_push($extended, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['extended'].'</td>');
                array_push($notextended, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['notextended'].'</td>');
            }
           
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'No of Job Extended (2 rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Extended Jobs</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Extended</td>'
                    .implode('', $extended)
                    .'</tr>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Not Extended</td>'
                    .implode('', $notextended)
                    .'</tr>';
            
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
            foreach($chartReportImages as $chartIndex) {
                if($chartIndex['key'] == $key) {
                    foreach($chartIndex['values'] as $chartValue) {
                        if(file_exists("./temp/".$chartValue['file'])) {
                            $y = $pdf->GetY();
                            $pdf->SetY($y+5);
                            $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                            $y = $pdf->GetY();
                            $pdf->SetY($y+120);
                        }
                    }
                }
            }
        }
        
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get On Time Attendance Report
     * 
    * @param integer $customerid selected Customer Id
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getOnTimeAttendance($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $ccondxgroup = ' and j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $ccondxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $ccondxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
       
        $sql = "select count(jobid), month(jrespdate) as m, year(jrespdate) as y,"
            . " sum(if(DATEDIFF(jrespdate,responseduedate)<=0,1,0)) as ontime,"
            . " sum(if(DATEDIFF(jrespdate,responseduedate)>0,1,0)) as late,"
            . " ROUND(SUM(IF(DATEDIFF(jrespdate,responseduedate)<=0,1,0))/ SUM(IF(jrespdate>0,1,0))*100,2) AS otp"
            . " from jobs j where leaddate>='".$fromdate."' and leaddate<='".$todate."'"
            . " $ccondxgroup"
            . " group by month(jrespdate), year(jrespdate) having y>2012 order by year(jrespdate), month(jrespdate)";

        
        
        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        $year = '';
        $onTimeAttendance = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['y']) {
                $year = $value['y'];
                $i++;
                $onTimeAttendance[$i][] = $value;
            } else {
                $onTimeAttendance[$i][] = $value;
            }
        }
        return $onTimeAttendance;
    }
    
    /**
    * This function generate On Time Attendance report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function onTimeAttendanceReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $onTimeAttendance = $this->getOnTimeAttendance($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        
        $chartReportImages = $params['rp'];
        
        $months = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:45px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $i, 10)).'</th>');
        }
        
        $attendendYearly = array();
        $attendendLateYearly = array();
        $attendendLatePercentageYearly = array();
        
        $attendedTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $attendendLateTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $attendendLatePercentageTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach($onTimeAttendance as $key=>$value) {
            
            $attendend = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            
            $attendendLate = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            $attendendPercentage = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            
            $year = '';
            foreach($value as $key1=>$value1) {
                $index = (int)$value1['m']-1;
                if($value1['otp'] == 0) {
                    $value1['otp'] = ''; 
                }
                if($value1['ontime'] == 0) {
                    $value1['ontime'] = ''; 
                }
                else{ 
                    $value1['otp'] = round(((float)$value1['ontime']/((float)$value1['ontime']+(float)$value1['late']))*100, 2);
               
                }
                if($value1['late'] == 0) {
                    $value1['late'] = ''; 
                }
                $attendend[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['ontime'].'</td>';
                $attendendLate[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['late'].'</td>';
                $attendendPercentage[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['otp'].'</td>';
                $year = $value1['y'];
                
                $attendedTotal[$index] = (int)$attendedTotal[$index]+$value1['ontime'];
                $attendendLateTotal[$index] = (int)$attendendLateTotal[$index]+$value1['late'];
                $attendendLatePercentageTotal[$index] = (double)$attendendLatePercentageTotal[$index]+$value1['otp'];
            }
            
            array_unshift($attendend, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
            array_unshift($attendendLate, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
            array_unshift($attendendPercentage, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
         
            array_push($attendendYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $attendend) . '</tr>');
            array_push($attendendLateYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $attendendLate) . '</tr>');
            array_push($attendendLatePercentageYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $attendendPercentage) . '</tr>');
        }

        if(count($onTimeAttendance) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'Jobs On Time ('.count($attendendYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= implode('', $attendendYearly);
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
            foreach($attendedTotal as $value) {
                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.$value.'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'Overdue Jobs ('.count($attendendLateYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            

            $html .= implode('', $attendendLateYearly);
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
            foreach($attendendLateTotal as $value) {
                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.$value.'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  '% On Time ('.count($attendendLatePercentageYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            

            $html .= implode('', $attendendLatePercentageYearly);
//            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
//            foreach($attendendLatePercentageTotal as $value) {
//                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.format_amount($value, FALSE).'</td>';
//            }
//            $html .= '</tr>';
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        foreach($chartReportImages as $chartIndex) {

            foreach($chartIndex['values'] as $chartValue) {
                if(file_exists("./temp/".$chartValue['file'])) {
                    $y = $pdf->GetY();
                    $pdf->SetY($y+5);
                    $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                    $y = $pdf->GetY();
                    $pdf->SetY($y+120);
                }
            }
        }
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get Job Counts Report
     * 
    * @param integer $contactid logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getJobCounts($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $condxgroup = ' and j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $condxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $condxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
        $portalFmJoinx = "left outer join addresslabel as al on j.labelid=al.labelid"
                . " left outer join contact as ac on al.contactid=ac.contactid"
                . " left outer join contact as jac on j.contactid=jac.contactid";
        
        $sql = "select distinct month(leaddate) as m, year(leaddate) as y, count(jobid) as jobcount from jobs as j "
                . $portalFmJoinx . " where leaddate > '2008-01-01' ".$condxgroup." group by month(leaddate), year(leaddate) order by year(leaddate), month(leaddate)";
       

        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        $year = '';
        $jobCounts = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['y']) {
                $year = $value['y'];
                $i++;
                $jobCounts[$i][] = $value;
            } else {
                $jobCounts[$i][] = $value;
            }
        }
        return $jobCounts;
    }
    
    /**
    * This function generate Job Counts report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function jobCountsReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $jobCounts = $this->getJobCounts($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        
        $chartReportImages = $params['rp'];
        
        
        $months = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:45px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $i, 10)).'</th>');
        }
        
        $jobCountsYearly = array();
        $jobCountsTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach($jobCounts as $key=>$value) {
            
            $counts = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            
            $year = '';
            foreach($value as $key1=>$value1) {
                $index = (int)$value1['m']-1;
                if($value1['jobcount'] == 0) {
                    $value1['jobcount'] = ''; 
                }
                $counts[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['jobcount'].'</td>';
                $year = $value1['y'];
                $jobCountsTotal[$index] = (int)$jobCountsTotal[$index]+$value1['jobcount'];
            }
            
            array_unshift($counts, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
            array_push($jobCountsYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $counts) . '</tr>');
        }

        if(count($jobCounts) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  '('.count($jobCountsYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= implode('', $jobCountsYearly);
            
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
            foreach($jobCountsTotal as $value) {
                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.$value.'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        foreach($chartReportImages as $chartIndex) {

            foreach($chartIndex['values'] as $chartValue) {
                if(file_exists("./temp/".$chartValue['file'])) {
                    $y = $pdf->GetY();
                    $pdf->SetY($y+5);
                    $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                    $y = $pdf->GetY();
                    $pdf->SetY($y+120);
                }
            }
        }   
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get Job Sale History Report Data
     * 
    * @param integer $contactid logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getSaleHistory($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $condxgroup = ' j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $condxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $condxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }

        $sql = "SELECT MONTH(i.invoicedate) AS m, YEAR(i.invoicedate) AS y, SUM(i.netval) AS netval FROM invoice i INNER JOIN jobs as j on i.jobid=j.jobid WHERE"
                . $condxgroup . " AND i.isreversed = 0 AND i.INVDOCTYPE = 'INVOICE' GROUP BY MONTH(i.invoicedate), YEAR(i.invoicedate) order by year(i.invoicedate), month(i.invoicedate)";
      

        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        $year = '';
        $saleHistory = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['y']) {
                $year = $value['y'];
                $i++;
                $saleHistory[$i][] = $value;
            } else {
                $saleHistory[$i][] = $value;
            }
        }
        return $saleHistory;
    }
    
    /**
    * This function generate Sale History report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function saleHistoryReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $saleHistory = $this->getSaleHistory($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        
        $chartReportImages = $params['rp'];
        
        $months = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:45px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $i, 10)).'</th>');
        }
        
        $saleHistoryYearly = array();
        $saleHistoryTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach($saleHistory as $key=>$value) {
            
            $counts = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            
            $year = '';
            foreach($value as $key1=>$value1) {
                $index = (int)$value1['m']-1;
                if($value1['netval'] == 0) {
                    $value1['netval'] = ''; 
                }
                $counts[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['netval'].'</td>';
                $year = $value1['y'];
                $saleHistoryTotal[$index] = format_amount((double)$saleHistoryTotal[$index]+(double)$value1['netval'], $show_currency_symbol = FALSE);
            }
            
            array_unshift($counts, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
            array_push($saleHistoryYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $counts) . '</tr>');
        }

        if(count($saleHistory) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  '('.count($saleHistoryYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 8);
            
            
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= implode('', $saleHistoryYearly);
            
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
            foreach($saleHistoryTotal as $value) {
                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.format_amount($value, FALSE).'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        foreach($chartReportImages as $chartIndex) {

            foreach($chartIndex['values'] as $chartValue) {
                if(file_exists("./temp/".$chartValue['file'])) {
                    $y = $pdf->GetY();
                    $pdf->SetY($y+5);
                    $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                    $y = $pdf->GetY();
                    $pdf->SetY($y+120);
                }
            }
        }   
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get Quote Conversion Report Data
     * 
    * @param integer $contactid logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getQuoteConversion($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $condxgroup = ' and j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $condxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $condxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
         $portalFmJoinx = "left outer join addresslabel as al on j.labelid=al.labelid"
                . " left outer join contact as ac on al.contactid=ac.contactid"
                . " left outer join contact as jac on j.contactid=jac.contactid";
         
        $sql = "select distinct month(leaddate) as m, year(leaddate) as y, sum(if(quotestatus='accepted',1,0)) as accepted,"
                . " sum(if(quotestatus='declined',1,0)) as declined, sum(if(quotestatus like '%submission%',1,0)) as submission,"
                . " sum(if(quotestatus not like '%submission%' and quotestatus!='accepted' and quotestatus!='declined',1,0)) as waiting from jobs as j "
                . $portalFmJoinx . " where leaddate>='".$fromdate."' and leaddate<='".$todate."' and quoterqd='on' $condxgroup"
                . " group by month(leaddate), year(leaddate) order by year(leaddate), month(leaddate)";

        $query = $this->db->query($sql);
        $data = $query->result_array();

        $year = '';
        $quoteConversion = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['y']) {
                $year = $value['y'];
                $i++;
                $quoteConversion[$i][] = $value;
            } else {
                $quoteConversion[$i][] = $value;
            }
        }
        return $quoteConversion;

        //return $data;
    }
    
    /**
    * This function generate Quote Conversion report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function quoteConversionReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $quoteConversion = $this->getQuoteConversion($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        $chartReportImages = $params['rp'];
        
        foreach($quoteConversion as $key=>$value) {
            
            $months = array();
            $accepted = array();
            $declined = array();
            $tosubmit = array();
            $waiting = array();
            
            $thtdwidth = 540/count($value);
            $firstMonth = 0;
            $lastMonth = 0;
            $year = 0;
            foreach($value as $key1=>$value1) {
                if($key1 == 0) {
                   $firstMonth =  $value1['m'];
                }
                $lastMonth = $value1['m'];
                $year = $value1['y'];
                
                if($value1['accepted'] == 0) {
                    $value1['accepted'] = '';
                }
                if($value1['declined'] == 0) {
                    $value1['declined'] = '';
                }
                if($value1['submission'] == 0) {
                    $value1['submission'] = '';
                }
                if($value1['waiting'] == 0) {
                    $value1['waiting'] = '';
                }
                    
                array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:' . $thtdwidth . 'px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $value1['m'], 10)).'</th>');
                array_push($accepted, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['accepted'].'</td>');
                array_push($declined, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['declined'].'</td>');
                array_push($tosubmit, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['submission'].'</td>');
                array_push($waiting, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['waiting'].'</td>');
            }
           
            $d = new DateTime('2016-'.$lastMonth.'-23');
            $lastDayOfMonth = $d->format('t');
            
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7, format_date($year.'-'.$firstMonth.'-01', 'd M Y').' - '.format_date($year.'-'.$lastMonth.'-'.$lastDayOfMonth, 'd M Y').' (4 rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Quote Requests Logged</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Accepted</td>'
                    .implode('', $accepted)
                    .'</tr>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Declined</td>'
                    .implode('', $declined)
                    .'</tr>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">To be submitted</td>'
                    .implode('', $tosubmit)
                    .'</tr>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Waiting Decision</td>'
                    .implode('', $waiting)
                    .'</tr>';
            
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
            foreach($chartReportImages as $chartIndex) {
                if($chartIndex['key'] == $key) {
                    foreach($chartIndex['values'] as $chartValue) {
                        if(file_exists("./temp/".$chartValue['file'])) {
                            $y = $pdf->GetY();
                            $pdf->SetY($y+5);
                            $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                            $y = $pdf->GetY();
                            $pdf->SetY($y+120);
                        }
                    }
                }
            }
        } 

        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get Work Completion Report Data
     * 
    * @param integer $contactid logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getWorkCompletion($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $condxgroup = ' and j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $condxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $condxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
        $sql = "select distinct customerid, count(jobid) as tj, sum(if(datediff(jcompletedate,duedate)<0,1,0)) as early, sum(if(datediff(jcompletedate,duedate)=0,1,0)) as ontime,"
                . " sum(if(datediff(jcompletedate,duedate)=1,1,0)) as 1day, sum(if(datediff(jcompletedate,duedate)=2,1,0)) as 2days, sum(if(datediff(jcompletedate,duedate)=3,1,0)) as 3days,"
                . " sum(if(datediff(jcompletedate,duedate)>3 and datediff(jcompletedate,duedate)<=7,1,0)) as 4_7days,"
                . " sum(if(datediff(jcompletedate,duedate)>7 and datediff(jcompletedate,duedate)<=14,1,0)) as 8_14days,"
                . " sum(if(datediff(jcompletedate,duedate)>14 and datediff(jcompletedate,duedate)<=30,1,0)) as 15_30days,"
                . " sum(if(datediff(jcompletedate,duedate)>30 and datediff(jcompletedate,duedate)<=60,1,0)) as 31_60days,"
                . " sum(if(datediff(jcompletedate,duedate)>60 ,1,0)) as g60days from jobs j"
                . " where leaddate>='".$fromdate."'  and leaddate<'".$todate."' and jcompletedate>0  and duedate>0 ".$condxgroup." group by customerid";

        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    /**
    * This function generate Work Compleition report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function workCompletionReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $workCompletion = $this->getWorkCompletion($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        
        $chartReportImages = $params['rp'];

        if(count($workCompletion) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,   $Customerdata['companyname'], 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->Cell(0, 7,   format_date($params['fromdate'], 'd M Y').' - '.format_date($params['todate'], 'd M Y'), 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 8);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">No. Of Work Orders Requested</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed Before Target Date</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed on Time</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed > 1 day</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed > 2 days</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed > 3 days</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed 4-7 days</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed 8-15 days</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed 16-30 days</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed 31-60 days</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:60px;background-color:#CCCCCC;text-align:center;">Completed > 60 days</th>'
                    .'</tr></thead><tbody>';
              
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['tj'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['early'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['ontime'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['1day'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['2days'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['3days'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['4_7days'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['8_14days'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['15_30days'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['31_60days'].'</td>';
            $html .= '<td style ="font-weight:bold;border:1px solid black;width:60px;text-align:right;">'.$workCompletion['g60days'].'</td>';
            
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        foreach($chartReportImages as $chartIndex) {

            foreach($chartIndex['values'] as $chartValue) {
                if(file_exists("./temp/".$chartValue['file'])) {
                    $y = $pdf->GetY();
                    $pdf->SetY($y+5);
                    $pdf->Image("./temp/".$chartValue['file'], 30, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                    $y = $pdf->GetY();
                    $pdf->SetY($y+120);
                }
            }
        }   
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get Job Extensions Report
     * 
    * @param integer $contactid - logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getJobExtension($contactid, $fromdate, $todate) {
        
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        
       $condxgroup = ' and customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $condxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $condxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
        $sql = "select distinct customerid, count(jobid) totaljobs, month(leaddate) as m, year(leaddate) as y,"
                . " sum(if(extenduntil is not null,1,0)) as extended, sum(if(extenduntil is null,1,0)) as notextended"
                . " from jobs j where leaddate>='".$fromdate."' and leaddate<='".$todate."' $condxgroup "
                . " group by month(leaddate), year(leaddate) order by year(leaddate), month(leaddate)";
        
        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        $year = '';
        $jobExtensions = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['y']) {
                $year = $value['y'];
                $i++;
                $jobExtensions[$i][] = $value;
            } else {
                $jobExtensions[$i][] = $value;
            }
        }
        return $jobExtensions;
    }
    
    /**
    * This function generate Job Extensions report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function jobExtensionsReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $jobExtension = $this->getJobExtension($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        $chartReportImages = $params['rp'];
        
        foreach($jobExtension as $key=>$value) {
            
            $months = array();
            $extended = array();
            $notextended = array();
            
            $thtdwidth = 540/count($value);
            $firstMonth = 0;
            $lastMonth = 0;
            $year = 0;
            foreach($value as $key1=>$value1) {
                if($key1 == 0) {
                   $firstMonth =  $value1['m'];
                }
                $lastMonth = $value1['m'];
                $year = $value1['y'];
                array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:' . $thtdwidth . 'px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $value1['m'], 10)).'</th>');
                array_push($extended, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['extended'].'</td>');
                array_push($notextended, '<td style ="border:1px solid black;width:' . $thtdwidth . 'px;text-align:center;">'.$value1['notextended'].'</td>');
            }
           
            $d = new DateTime('2016-'.$lastMonth.'-23');
            $lastDayOfMonth = $d->format('t');
            
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  $Customerdata['companyname'], 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->Cell(0, 7, format_date($year.'-'.$firstMonth.'-01', 'd M Y').' - '.format_date($year.'-'.$lastMonth.'-'.$lastDayOfMonth, 'd M Y'), 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(6);
            $pdf->Cell(0, 7,  'No of Job Extended (2 rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Extended Jobs</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Extended</td>'
                    .implode('', $extended)
                    .'</tr>';
            
            $html .= '<tr nobr="true" bgcolor="#FFFFFF">'
                    .'<td style ="border:1px solid black;width:100px;text-align:center;">Not Extended</td>'
                    .implode('', $notextended)
                    .'</tr>';
            
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
            foreach($chartReportImages as $chartIndex) {
                if($chartIndex['key'] == $key) {
                    foreach($chartIndex['values'] as $chartValue) {
                        if(file_exists("./temp/".$chartValue['file'])) {
                            $y = $pdf->GetY();
                            $pdf->SetY($y+5);
                            $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                            $y = $pdf->GetY();
                            $pdf->SetY($y+120);
                        }
                    }
                }
            }
        }   
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get On Time Completion Report
     * 
    * @param integer $contactid - logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getOnTimeCompletion($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $ccondxgroup = ' and j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $ccondxgroup .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $ccondxgroup .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
       
        $sql = "select count(jobid), month(jcompletedate) as m, year(jcompletedate) as y,"
                . " sum(if(DATEDIFF(jcompletedate,duedate)<=0,1,0)) as ontime,"
                . " sum(if(DATEDIFF(jcompletedate,duedate)>0,1,0)) as late,"
                . " ROUND(SUM(IF(DATEDIFF(jcompletedate,duedate)<=0,1,0))/ SUM(IF(jcompletedate>0,1,0))*100,2) AS otp"
                . " from jobs j where jcompletedate>='".$fromdate."'  and jcompletedate<='".$todate."'"
                . " $ccondxgroup"
                . " group by month(jcompletedate), year(jcompletedate) order by year(jcompletedate), month(jcompletedate)";
 
        $query = $this->db->query($sql);
        $data = $query->result_array();
        
        $year = '';
        $onTimeAttendance = array();
        $i = -1;
        foreach($data as $key=>$value) {
            if($year != $value['y']) {
                $year = $value['y'];
                $i++;
                $onTimeAttendance[$i][] = $value;
            } else {
                $onTimeAttendance[$i][] = $value;
            }
        }
        return $onTimeAttendance;
    }
    
    /**
    * This function generate On Time Completion report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function onTimeCompletionReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $onTimeCompletion = $this->getOnTimeCompletion($loggedUserData['contactid'], $params['fromdate'], $params['todate']);
        
        $chartReportImages = $params['rp'];
        
        $months = array();
        for($i = 1;$i <= 12;$i++) {
            array_push($months, '<th style ="border:1px solid black;font-weight:bold;width:45px;background-color:#CCCCCC;text-align:center;">'.date('M', mktime(0, 0, 0, $i, 10)).'</th>');
        }
        
        $attendendYearly = array();
        $attendendLateYearly = array();
        $attendendLatePercentageYearly = array();
        
        $attendedTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $attendendLateTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $attendendLatePercentageTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        foreach($onTimeCompletion as $key=>$value) {
            
            $attendend = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            
            $attendendLate = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            $attendendPercentage = array(
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>',
                '<td style ="border:1px solid black;width:45px;text-align:center;">&nbsp;</td>'
            );
            
            $year = '';
            foreach($value as $key1=>$value1) {
                $index = (int)$value1['m']-1;
                if($value1['otp'] == 0) {
                    $value1['otp'] = ''; 
                }
                
               
                if($value1['ontime'] == 0) {
                    $value1['ontime'] = ''; 
                }
                else{
                    $value1['otp'] = round(((float)$value1['ontime']/((float)$value1['ontime']+(float)$value1['late']))*100, 2);
                
                }
                if($value1['late'] == 0) {
                    $value1['late'] = ''; 
                }
                
               
                
                
                $attendend[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['ontime'].'</td>';
                $attendendLate[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['late'].'</td>';
                $attendendPercentage[$index] = '<td style ="border:1px solid black;width:45px;text-align:right;">'.$value1['otp'].'</td>';
                $year = $value1['y'];
                
                $attendedTotal[$index] = (int)$attendedTotal[$index]+$value1['ontime'];
                $attendendLateTotal[$index] = (int)$attendendLateTotal[$index]+$value1['late'];
                $attendendLatePercentageTotal[$index] = (double)$attendendLatePercentageTotal[$index]+$value1['otp'];
            }
            
            array_unshift($attendend, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
            array_unshift($attendendLate, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
            array_unshift($attendendPercentage, '<td style ="border:1px solid black;width:100px;text-align:center;">'.$year.'</td>'); 
         
            array_push($attendendYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $attendend) . '</tr>');
            array_push($attendendLateYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $attendendLate) . '</tr>');
            array_push($attendendLatePercentageYearly, '<tr nobr="true" bgcolor="#FFFFFF">' . implode('', $attendendPercentage) . '</tr>');
        }

        if(count($onTimeCompletion) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'Jobs On Time ('.count($attendendYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= implode('', $attendendYearly);
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
            foreach($attendedTotal as $value) {
                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.$value.'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
             $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'Overdue Jobs ('.count($attendendLateYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            

            $html .= implode('', $attendendLateYearly);
            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
            foreach($attendendLateTotal as $value) {
                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.$value.'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');

            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  '% On Time ('.count($attendendLatePercentageYearly).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 9);
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">&nbsp;</th>'
                        .implode('', $months)
                    .'</tr></thead><tbody>';
            
            $html .= implode('', $attendendLatePercentageYearly);
//            $html .= '<tr nobr="true"><td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;">Total</td>';
//            foreach($attendendLatePercentageTotal as $value) {
//                 $html .= '<td style ="font-weight:bold;border:1px solid black;width:45px;background-color:#CCCCCC;text-align:right;">'.format_amount($value, FALSE).'</td>';
//            }
//            $html .= '</tr>';
            $html .= '</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        foreach($chartReportImages as $chartIndex) {
            foreach($chartIndex['values'] as $chartValue) {
                if(file_exists("./temp/".$chartValue['file'])) {
                    $y = $pdf->GetY();
                    $pdf->SetY($y+5);
                    $pdf->Image("./temp/".$chartValue['file'], 0, $y, 0, 0, $chartValue['ext'], '', '', false, 300, '', false, false, 0, false, false, false);
                    $y = $pdf->GetY();
                    $pdf->SetY($y+120);
                }
            }
        }
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function generate invoice reconciliation report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function invoiceReconciliationReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $invoicedJobs = $this->getInvoicedJobs($loggedUserData['contactid'], $params['fromdate'], $params['todate']);

        if(count($invoicedJobs) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  'Invoiced Jobs ('. format_date($params['fromdate'], 'd M Y').' : '.format_date($params['todate'], 'd M Y').') ('.count($invoicedJobs).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 8);

            $invoiceTotal = array(0, 0, 0, 0, 0);
            $totalRows = array();
            foreach($invoicedJobs as $key=>$value) {

                $quoterqd = '';
                if($value['quoterqd'] == 'on') {
                    $quoterqd = 'Yes';
                }
                
                $str = '<tr><td style ="border:1px solid black;width:90px;text-align:center;">'.$quoterqd.'</td>
                    <td style ="border:1px solid black;width:100px;text-align:left;">'.$value['jobstage'].'</td>
                    <td style ="border:1px solid black;width:90px;text-align:right;">'.$value['jcount'].'</td>
                    <td style ="border:1px solid black;width:90px;text-align:right;">'.format_amount($value['est'], FALSE).'</td>
                    <td style ="border:1px solid black;width:90px;text-align:right;">'.format_amount($value['ival'], FALSE).'</td>
                    <td style ="border:1px solid black;width:90px;text-align:right;">'.format_amount($value['iperiod'], FALSE).'</td>
                    <td style ="border:1px solid black;width:90px;text-align:right;">'.format_amount($value['xperiod'], FALSE).'</td></tr>';
                
                    array_push($totalRows, $str);
                
                $invoiceTotal[0] = $invoiceTotal[0]+$value['jcount'];
                $invoiceTotal[1] = format_amount((double)$invoiceTotal[1]+(double)$value['est'], FALSE);
                $invoiceTotal[2] = format_amount((double)$invoiceTotal[2]+(double)$value['ival'], FALSE);
                $invoiceTotal[3] = format_amount((double)$invoiceTotal[3]+(double)$value['iperiod'], FALSE);
                $invoiceTotal[4] = format_amount((double)$invoiceTotal[4]+(double)$value['xperiod'], FALSE);
            }
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:90px;background-color:#CCCCCC;text-align:center;">Quoted</th>'
                        .'<th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;text-align:center;">Job Stage</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:90px;background-color:#CCCCCC;text-align:center;"># Jobs</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:90px;background-color:#CCCCCC;text-align:center;">$ Est</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:90px;background-color:#CCCCCC;text-align:center;">$ Total Inv</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:90px;background-color:#CCCCCC;text-align:center;">$ Inv This Period</th>'
                    .'<th style ="border:1px solid black;font-weight:bold;width:90px;background-color:#CCCCCC;text-align:center;">$ Inv Other Periods</th>'
                    .'</tr></thead><tbody>';
            
 
            $html .= implode('', $totalRows);
            $html .= '<tr nobr="true">'
                    . '<td style ="font-weight:bold;border:1px solid black;width:90px;background-color:#CCCCCC;text-align:right;">Total</td>'
                    . '<td style ="font-weight:bold;border:1px solid black;width:100px;background-color:#CCCCCC;text-align:right;"></td>';
            foreach($invoiceTotal as $value) {
                $html .= '<td style ="font-weight:bold;border:1px solid black;width:90px;background-color:#CCCCCC;text-align:right;">'.$value.'</td>';
            }
            $html .= '</tr>';
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
        }
           
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function Get Invoiced Jobs Data
     * 
    * @param integer $contactid logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getInvoicedJobs($contactid, $fromdate, $todate) {
       
       $loggedUserData = $this->sharedClass->getLoggedUser($contactid);

        $ccondx = ' and j.customerid='.$loggedUserData['customerid'];
        if($loggedUserData['role'] == 'site contact') {
            $ccondx .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $ccondx .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }
        
        $portalFmJoinx = " left outer join addresslabel as al on j.labelid=al.labelid"
                . " left outer join contact as ac on al.contactid=ac.contactid"
                . " left outer join contact as jac on j.contactid=jac.contactid";
        
        $icondx = "and i.invoiceno is not null AND inv.invdoctype='INVOICE' and inv.isreversed=0";

        $sql = "select distinct quoterqd, jobstage, count(distinct(j.jobid)) as jcount, sum(estimatedsell) as est,"
                . " sum(i.qty*i.price) as ival, sum(if(i.invoicedate>='".$fromdate."' and i.invoicedate<='".$todate."',"
                . " i.qty*i.price,0)) as iperiod, sum(if(i.invoicedate<'".$fromdate."' or i.invoicedate>'".$todate."',"
                . " i.qty*i.price,0)) as xperiod from jobs as j"
                . " left outer join invoicelines as i on j.jobid=i.jobid"
                . " left outer join invoice as inv on i.invoiceno=inv.invoiceno"
                . " " . $portalFmJoinx . ""
                . " where leaddate>='".$fromdate."' and leaddate<='".$todate."' $icondx $ccondx";
      
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /**
    * This function Get Extension Detail Report Data
     * 
    * @param integer $contactid logged user contactid
     * @param date $fromdate
    * @param date $todate
    * @return array - 
    */
   public function getExtensionDetail($contactid, $fromdate, $todate) {
       
        $loggedUserData = $this->sharedClass->getLoggedUser($contactid);
        
        $roleclause = '';
        if($loggedUserData['role'] == 'site contact') {
            $roleclause .= ' and (j.sitecontactemail = "'.$loggedUserData['customerid'].'" OR j.sitecontactid = '.$loggedUserData['contactid'];
        } elseif ($loggedUserData['role'] == 'sitefm') {
            $subordinate_emails = $this->customerClass->getSubordinateEmails($loggedUserData['email']);
            $roleclause .= " and (j.sitefmemail='".$loggedUserData['email']."' or j.sitecontactid=".$loggedUserData['contactid']." or FIND_IN_SET(j.sitefmemail, '".$this->db->escape_str($subordinate_emails)."'))";
        }

        $sql = "SELECT j.jobid, j.custordref, j.leaddate, j.sitesuburb, j.duedate, j.extenduntil, jn.notes, j.sitefm"
                . " FROM jobnote jn"
                . " INNER JOIN jobs j ON j.jobid=jn.jobid "
                . "WHERE ntype = 'extend' AND notetype = 'client' AND j.customerid= ".$loggedUserData['customerid'].""
                . " AND jcompletedate >= '".$fromdate."' AND jcompletedate <= '".$todate."' AND j.jobstage = 'client_notified'"
                . " $roleclause ORDER BY jobid";
      

        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /**
    * This function generate extension detail report
    * @param object $pdf - pdf library instance
    * @param array $Customerdata - customer data 
    * @param array $params- request parameters
    * @param array $loggedUserData- logged user
    * @return pdf - 
    */
    public function extensionDetailReport($pdf, $Customerdata, $params, $loggedUserData) {
        
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Printed :'. date('d/m/Y H:i'), 0, false, 'R', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Customer   :' . $Customerdata['companyname'], 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
        $pdf->Cell(0, 7, 'Date Range :' . format_date($params['fromdate'], RAPTOR_DISPLAY_DATEFORMAT).' to '. format_date($params['todate'], RAPTOR_DISPLAY_DATEFORMAT), 0, false, 'L', false, '', 0, false, 'M', 'M');
        $pdf->Ln(4);
	
        $extensionDetail = $this->getExtensionDetail($loggedUserData['contactid'], $params['fromdate'], $params['todate']);

        if(count($extensionDetail) > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7,  $Customerdata['companyname'], 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(5);
            $pdf->Cell(0, 7,  format_date($params['fromdate'], 'd M Y').' - '.format_date($params['todate'], 'd M Y'), 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(7);
            $pdf->Cell(0, 7,  'Based on Job Completion Dates', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->Cell(0, 7,  'Extension Detail ('.count($extensionDetail).' rows)', 0, false, 'C', false, '', 0, false, 'M', 'M');
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', '', 8);

            $totalRows = array();
            foreach($extensionDetail as $key=>$value) {
                
                $jobInfo = '<b>Work Order:</b><br />'.$value['custordref'].'<br />';
                $jobInfo .= '<b>Requested:</b><br />'.$value['leaddate'].'<br />';
                $jobInfo .= '<b>Due Date:</b><br />'.$value['duedate'].'<br />';
                $jobInfo .= '<b>Extension:</b><br />'.$value['extenduntil'].'<br />';
                
                $str = '<tr><td style ="border:1px solid black;width:130px;text-align:center;">'.$jobInfo.'</td>
                    <td style ="border:1px solid black;width:510px;text-align:center;">'.$value['notes'].'</td></tr>';
                
                    array_push($totalRows, $str);
            }
            
            $html = '<table style ="border:1px solid black;margin-left:100px;" cellspacing="0" cellpadding="4">
                    <thead>
                    <tr nobr="true">
                        <th style ="border:1px solid black;font-weight:bold;width:130px;background-color:#CCCCCC;text-align:center;">Job Info</th>'
                        .'<th style ="border:1px solid black;font-weight:bold;width:510px;background-color:#CCCCCC;text-align:center;">Notes</th>'
                    .'</tr></thead><tbody>';
            
 
            $html .= implode('', $totalRows);
            $html .= '</tbody></table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
        }
          
        //5 - get inserted id values
        $file_name = 'My_Reports.pdf';
  
        ob_end_clean();
        $pdf->Output($file_name, 'I');
    }
    
    /**
    * This function use for get Reports for Editable Reports
    * @param integer $jobid - jobid
    * 
    * @return array
    */
    public function getJobReports($jobid) {
 
        $sql = "SELECT ins_sitereport_id,CONCAT(ins_sitereport_Id,' - ',report_type,' (',inspection_date,')') AS report"
                . " FROM ins_sitereport WHERE jobid=:selectedjobid"
                . " ORDER BY ins_sitereport_id";

        $this->db->select(array("ins_sitereport_id, CONCAT(ins_sitereport_Id,' - ',report_type,' (',inspection_date,')') AS report"));
        $this->db->from('ins_sitereport');
        $this->db->where('jobid', $jobid);
        $this->db->order_by('ins_sitereport_id');
         
        return $this->db->get()->result_array();
    }
    
}


/* End of file ReportClass.php */