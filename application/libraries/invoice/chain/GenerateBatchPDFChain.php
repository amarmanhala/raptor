<?php 
/**
 * GenerateBatchPDFChain Libraries Class
 *
 *  This is a Invoice Chain class for generate Batch PDF
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractInvoiceChain.php');
require_once( __DIR__.'/../../LogClass.php');
require_once( __DIR__.'/../InvoiceBatchPDF.php');
require_once( __DIR__.'/../../shared/SharedClass.php');

/**
 * GenerateBatchPDFChain Libraries Class
 *
 *  This is a Invoice Chain class for generate Batch PDF
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Invoice/chain
 * @filesource          GenerateBatchPDFChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class GenerateBatchPDFChain extends AbstractInvoiceChain
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
        $this->successor=$nextService;
    }
 
    /**
    * This function use for Generate Batch PDF
     * 
    * @param array $request - the request is array of required parameter for creating Batch PDF
    * @return array
    */
    public function handleRequest($request)
    { 
        $logClass= new LogClass('jobtracker', 'GenerateBatchPDFChain');
        $sharedClass = new SharedClass();
       	
        //2 - initialize instances
        $pdf = new InvoiceBatchPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
        
        //3 - get the parts connected
        $params = $request['params'];
         
        $batchData = $request['batchData']; 
        $batchInvoiceData = $request['batchInvoiceData']; 
         
       
        if (count($batchData)==0) {
            return false;
        }
        
        $headerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $footerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $logoImage = array('image'=>K_PATH_IMAGES.'logo.png', 'imagetype'=>'PNG');
       
        
        $brandingImage = $sharedClass->getBrandingImage('H', 'BI');
        if (count($brandingImage)>0) {
            $dirpath = $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $headerImage = array('image'=> $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'],'imagetype'=> $brandingImage['docformat']);
            }
        }
        
        $brandingImage = $sharedClass->getBrandingImage('F', 'BI');
        if (count($brandingImage)>0) {
            $dirpath = $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $footerImage = array('image'=> $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'],'imagetype'=> $brandingImage['docformat']);
            }
        }
        
        
        //set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('Batch Invoice Manifest');
        $pdf->SetSubject('Batch Invoice Manifest');
         
          // set header and footer fonts
         // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT,PDF_MARGIN_BOTTOM+20);
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

        $pdf->setHeaderImage($headerImage);
        $pdf->setfooterImage($footerImage);
        $pdf->setinvoiceTitle('Batch Invoice Manifest');
        
        // ---------------------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 8);
 
        // add a page
        $pdf->AddPage();
        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
        
        //Contractor Info 
        $customer = $this->db->where('customerid', $batchData['customerid'])->get('customer')->row_array(); 
        
        $customerAddress1 = $customer['mail1'];
        $customerAddress2 = $customer['mailsuburb'] .' '.$customer['state'].' '.$customer['postcode'];
        if(trim($customerAddress1) == '') {
            $customerAddress1 = $customerAddress2;
            $customerAddress2 = '';
        }
        $CompanyDetail = '<table  cellspacing="0" cellpadding="0" >
            <tr><td style ="width:5px;height:6px;font-size:3px;">&nbsp;</td><td style ="height:6px;font-size:3px;width:250px;"></td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:80px;font-size:12px;">&nbsp;&nbsp;Customer:</td><td style="width:260px;font-size:12px;">'. $customer["tradingname"].'</td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:80px;font-size:12px;">&nbsp;&nbsp;Address:</td><td style="width:260px;font-size:12px;">'. $customerAddress1 .'</td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:80px;font-size:12px;">&nbsp;&nbsp;</td><td style="width:260px;font-size:12px;">'.$customerAddress2 .'</td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:80px;font-size:12px;">&nbsp;&nbsp;Recipients:</td><td style="width:260px;font-size:12px;">'. $batchData['recipients'].'</td></tr>
            </table>';
       
        $y = $pdf->GetY();
        $pdf->MultiCell(100, 25, $CompanyDetail, $border, 'L', false, 1, 15, $y, true, 0, true, true, 0, 'T', false);
        
        //PO and Job Details 
        $invoiceDetails = '<table cellspacing="0" cellpadding="0">
            <tr><td style="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style="width:90px;height:6px;font-size:3px;"></td><td style="height:6px;font-size:3px;width:180px;"></td></tr>          
            <tr><td style="width:3px;">&nbsp;</td><td style="width:120px;font-size:12px;">&nbsp;&nbsp;Customer Batch ID:</td><td style="width:160px;font-size:12px;">'. $batchData['custbatchid'].'</td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:120px;font-size:12px;">&nbsp;&nbsp;Batch Date:</td><td style="width:160px;font-size:12px;">'. format_date($batchData['batchdate'], RAPTOR_DISPLAY_DATEFORMAT).'</td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:120px;font-size:12px;">&nbsp;&nbsp;No. Invoices:</td><td style="width:160px;font-size:12px;">'. $batchData['invoicecount'].'</td></tr>
            <tr><td style="width:3px;">&nbsp;</td><td style="width:120px;font-size:12px;">&nbsp;&nbsp;Total Value:</td><td style="width:160px;font-size:12px;">$'. $batchData['totalvalue'].'</td></tr>
        </table>';
        
        $pdf->MultiCell(0, 25, $invoiceDetails, $border, 'L', false, 1, 120, $y, true, 0, true, true, 0, 'T', false);
         
        
        $pdf->Ln(7);
        $pdf->SetFont('helvetica', '', 9);
        $html = '<table style="border:1px solid black;" cellspacing="0" cellpadding="4">
            <thead>
            <tr>
                <th style="border:1px solid black;font-weight:bold;width:100px;">Invoice No</th>
                <th style="border:1px solid black;font-weight:bold;width:100px;">Invoice Date</th>
                <th style="border:1px solid black;font-weight:bold;width:137px;">Cost Centre</th>
                <th style="border:1px solid black;font-weight:bold;width:150px;">GL Code</th>
                <th style="border:1px solid black;font-weight:bold;width:150px;text-align:right;">Amount</th>
            </tr></thead><tbody>';
        $count = 0;
     
        foreach ($batchInvoiceData as $row) {
             $count=$count+1;
            $html .= '<tr>
                        <td style="border:1px solid black;width:100px;">' . $row['invoiceno'] . '</td>
                        <td style="border:1px solid black;width:100px;">' . format_date($row['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT) . '</td>
                        <td style="border:1px solid black;width:137px;">' . $row['costcentre'] . '</td>
                        <td style="border:1px solid black;width:150px;">' . $row['glcode'] . '</td>
                        <td style="border:1px solid black;width:150px;text-align:right;">' . format_amount($row['amount']). '</td>
                    </tr>';
        }
        
        for ($i=$count;$i<=10;$i++) {
              $html .= '<tr>
                        <td style="border:1px solid black;width:100px;">&nbsp;</td>
                        <td style="border:1px solid black;width:100px;">&nbsp;</td>
                        <td style="border:1px solid black;width:137px;">&nbsp;</td>
                        <td style="border:1px solid black;width:150px;">&nbsp;</td>
                        <td style="border:1px solid black;width:150px;text-align:right;">&nbsp;</td>
                    </tr>';
             
        }
        $html .= '</tbody></table>';
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
      
        
        $filelocation =  $this->config->item('invoicedir');
        if(!$filelocation){
            $filelocation = $_SERVER['DOCUMENT_ROOT'].'/infomaniacDocs/invoices';
 
        }
        $open = false;
        if(isset($params['open'])){
            $open = (bool)$params['open'];
        }
      
        //5 - get inserted id values
        $file_name = 'batch_invoice_' . $batchData["id"] . '.pdf';
        
        $fileNL = $filelocation . "/" . $file_name; //Linux
 
        if (!is_dir($filelocation)) {
            mkdir($filelocation, 0755, true);
        }
        
        //6 - return the result object
        //Close and output PDF document
        $pdf->Output($fileNL, 'F');
        
        if ($open) {
            ob_end_clean();
            $pdf->Output($file_name, 'I');
        }
        
        
        $logClass->log('Create Batch Invoice PDF');
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;
     

    }
	
}


/* End of file GenerateBatchPDFChain.php */