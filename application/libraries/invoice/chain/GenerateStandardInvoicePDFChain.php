<?php 
/**
 * GenerateStandardInvoicePDFChain Libraries Class
 *
 *  This is a Invoice Chain class for generate Invoice PDF
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractInvoiceChain.php');
require_once( __DIR__.'/../../LogClass.php');
require_once( __DIR__.'/../InvoicePDF.php');
require_once( __DIR__.'/../../shared/SharedClass.php');

/**
 * GenerateStandardInvoicePDFChain Libraries Class
 *
 *  This is a GenerateStandardInvoicePDFChain Chain class for generate Invoice PDF
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Invoice/chain
 * @filesource          GenerateStandardInvoicePDFChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class GenerateStandardInvoicePDFChain extends AbstractInvoiceChain
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
    * This function use for Generate Job Request PDF
     * 
    * @param array $request - the request is array of required parameter for creating Job Request PDF
    * @return array
    */
    public function handleRequest($request)
    { 
        $logClass= new LogClass('jobtracker', 'GenerateStandardInvoicePDFChain');
        $sharedClass = new SharedClass();
       	
        //2 - initialize instances
        $pdf = new InvoicePDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
        
        //3 - get the parts connected
        $params = $request['params'];
        
        $loggedUserData = $request['userData'];
        $invoiceData = $request['invoiceData']; 
        $invoiceLineData = $request['invoiceLineData'];
      
        $invoiceOptions = $request['invoiceOptions'];
        
        $ContactRules = $sharedClass->getCustomerRules($loggedUserData['customerid'], $loggedUserData['role']);
       
        if (count($invoiceData)==0) {
            return false;
        }
        
        $headerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $footerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $logoImage = array('image'=>K_PATH_IMAGES.'logo.png', 'imagetype'=>'PNG');
        //set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('TAX INVOICE - ' .$invoiceData["invoiceno"]);
        $pdf->SetSubject('TAX INVOICE');
         
          // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 60, PDF_MARGIN_RIGHT);
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
  
        $deladd=  nl2br($sharedClass->getSysValue("dochead"));
        //$owner= nl2br($sharedClass->getSysValue("ownername"));
        $owner= nl2br($sharedClass->getSysValue("tradingname"));
        $abn= nl2br($sharedClass->getSysValue("abn"));
        
        //set default header data PDF_HEADER_LOGO
        $CompanyDetail = '<table>
                                <tr><td style="width:5px;height:6px;font-size:3px;">&nbsp;</td><td style="width:250px;height:6px;font-size:3px;"></td></tr>
                                <tr><td style="width:5px;">&nbsp;</td><td style="font-size:15px;font-weight:bold;width:250px">'.$owner.'</td></tr>
                                <tr><td style="width:5px;">&nbsp;</td><td style="width:250px;">A.B.N. '.$abn.'</td></tr>
                                <tr><td style="width:5px;">&nbsp;</td><td style="width:250px;">'.$deladd.'</td></tr>
                            </table>';
        $custordref1_label = isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]: 'Order Ref 1';
        $custordref2_label = isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]: 'Order Ref 2';
        $custordref3_label = isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]: 'Order Ref 3';
          
        $SiteAddress='';
        if(trim($invoiceData['siteline1'])!=""){
             $SiteAddress .=$invoiceData['siteline1'].'<br>';
        }
        if(trim($invoiceData['siteline2'])!=""){
             $SiteAddress .=$invoiceData['siteline2'].'<br>';
        }
        $SiteAddress .=$invoiceData['sitesuburb'].' '. $invoiceData['sitestate'].' '. $invoiceData['sitepostcode'];
        $InvoiceDetails = '<table  >
                                <tr><td style="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style="width:90px;height:6px;font-size:3px;"></td><td style="height:6px;font-size:3px;width:180px;"></td></tr>          
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;INVOICE NO.:</td><td style="width:180px;">'. $invoiceData["invoiceno"].'</td></tr>
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;INVOICE DATE:</td><td style="width:180px;">'. format_date($invoiceData['invoicedate'], RAPTOR_DISPLAY_DATEFORMAT).'</td></tr>
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;'.$custordref1_label.'</td><td style="width:180px;">'. $invoiceData["custordref"].'</td></tr>
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;'.$custordref2_label.'</td><td style="width:180px;">'. $invoiceData["custordref2"].'</td></tr>
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;'.$custordref3_label.'</td><td style="width:180px;">'. $invoiceData["custordref3"].'</td></tr>
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;DCFM Job No:</td><td style="width:180px;">'. $invoiceData["jobid"].'</td></tr>
                                <tr><td style="width:3px;">&nbsp;</td><td style="width:90px;">&nbsp;&nbsp;Site Address:</td><td style="width:180px;">'. $SiteAddress.'</td></tr>
                            </table>';
        
        $Customerdata = $this->db->where('customerid', $invoiceData['customerid'])->get('customer')->row_array();
        $CustomerDetails = '<table>
                                <tr><td style="width:5px;height:6px;font-size:3px;">&nbsp;</td><td style="width:50px;height:6px;font-size:3px;"></td><td style="height:6px;font-size:3px;"></td></tr>
                                <tr><td style="width:5px;">&nbsp;</td><td style="width:50px;">To:</td><td><b>'. $Customerdata["companyname"].'</b><br>'. $Customerdata['mail1'].'<br>'. $Customerdata['mailsuburb'].' '. $Customerdata['state'].' '. $Customerdata['postcode'].'</td></tr>
                                 <tr><td style="width:5px;">&nbsp;</td><td style="width:50px;">&nbsp;</td><td>&nbsp;</td></tr>
                                <tr><td style="width:5px;">&nbsp;</td><td style="width:50px;">ATTN:</td><td>'. $invoiceData['firstname'].' '.$invoiceData['surname'].'</td></tr>
                            </table>';
         
       
        
        $pdf->setLogoImage($logoImage);
        $pdf->setinvoiceTitle('TAX INVOICE');
        $pdf->setinvoiceno($invoiceData["invoiceno"]);
        $pdf->setCompanyDetail($CompanyDetail);
        $pdf->setInvoiceDetail($InvoiceDetails);
        $pdf->setCustomerDetail($CustomerDetails);
        
       

        // ---------------------------------------------------------
        // set font
        $pdf->SetFont('helvetica', '', 8);
 
        // add a page
        $pdf->AddPage();
 
        //Invoice Lines
        $invoiceItemDetails = '<table style="border:1px solid black;" cellspacing="0" cellpadding="4">
            <thead>
            <tr>
                <th style="border:1px solid black;font-weight:bold;width:563px;">Description</th>
                <th style="border:1px solid black;font-weight:bold;width:75px;text-align:right;">Amount</th>
            </tr></thead><tbody>';
        $count = 0;
       
        foreach ($invoiceLineData as $row) {
          
             $count=$count+1;
             $scx= ($count==1 ? "<b><u>SCOPE OF WORKS:</u></b><br><br>" : "");
             
            $invoiceItemDetails .= '<tr>
                        <td style="border:1px solid black;width:563px;">'. $scx . nl2br($row['chargedesc'])  . '</td>
                        <td style="border:1px solid black;width:75px;text-align:right;">' . format_amount($row['netval']). '</td>
                    </tr>';
        }
       
        $invoiceItemDetails .= '</tbody></table>';
        // output the HTML content
        $pdf->writeHTML($invoiceItemDetails, true, false, true, false, '');
        
        if($pdf->gety()<170)
        {
            $pdf->sety($pdf->GetY()-6.1);
            $height=(int)((160 - $pdf->GetY())*3.779);
            
            $html = '<table   cellspacing="0" cellpadding="4">
       <tr>
                <th style="border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;width:563px;height:'.$height.'px;">&nbsp;</th>
                <th style="border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;font-weight:bold;width:75px;text-align:right;height:'.$height.'px;">&nbsp;</th>
            </tr></table>';
            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
            
            $pdf->sety(170);
        }
        
        
        //Payment Info and SUBTOTAL/GST
        $thisterms = 	strtoupper($Customerdata['payterms']);
        if ($thisterms == "") {
            $thisterms = "30 DAYS";
        }
        $payment_terms = "TERMS ".$thisterms;
        
        $security_of_payment_text= isset($invoiceOptions['security_of_payment_text']) ? $invoiceOptions['security_of_payment_text'] :'';
        $security_of_payment_text=  nl2br($security_of_payment_text).'<br><br>'.$payment_terms;
        $html = '<table cellpadding="4">
            
            <tr>
                <th style="border:1px solid black;width:403px;">'.$security_of_payment_text.'</th>
                <th style="width:25px;">&nbsp;</th>
                <th style="width:200px;">
                <table  cellspacing="0" cellpadding="4">
            <tr>
                <th style="border:1px solid black;font-weight:bold;width:125px;">SUB TOTAL</th>
                <th style="border:1px solid black;font-weight:bold;width:75px;text-align:right;">'.format_amount($invoiceData['netval']).'</th>
            </tr><tr>
                <th style="border:1px solid black;font-weight:bold;width:125px;">GST</th>
                <th style="border:1px solid black;font-weight:bold;width:75px;text-align:right;">'.format_amount($invoiceData['taxval']).'</th>
            </tr>
            <tr>
                <th style="border:1px solid black;font-weight:bold;width:125px;">TOTAL</th>
                <th style="border:1px solid black;font-weight:bold;width:75px;text-align:right;">'.format_amount($invoiceData['invoiced']).'</th>
            </tr></table></th>
            </tr></table>';
        
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
         //Remittance Advice
        $RemittanceAdvice= $sharedClass->getSysValue("REMITTANCEADVICE");
        $html = '<table >
            <tr><th style="border:1px solid black;font-weight:bold;height:20px;text-align:center;background-color:#CCCCCC;">Remittance Advice</th></tr>
            <tr><th style="border:1px solid black;min-height:60px;">'.$RemittanceAdvice.'</th></tr>
            </table>';
        
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
        $file_name = 'invoice_' . $invoiceData["invoiceno"] . '.pdf';
        
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
        
        
        $logClass->log('Create Invoice PDF');
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;
     

    }
	
}


/* End of file GenerateStandardInvoicePDFChain.php */