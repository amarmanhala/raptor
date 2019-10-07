<?php 
/**
 * GeneratePDFChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for generate PO/ Job Request PDF
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
require_once( __DIR__.'/../PurchaseOrderPDF.php');
require_once( __DIR__.'/../../shared/SharedClass.php'); 
/**
 * CreatePurchaseOrderChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for generate PO/ Job Request PDF
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          GeneratePDFChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class GeneratePDFChain extends AbstractPurchaseOrderChain
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
        $logClass= new LogClass('jobtracker', 'GeneratePDFChain');
        $sharedClass = new SharedClass(); 
       	
         //2 - initialize instances
        $pdf = new PurchaseOrderPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
        //3 - get the parts connected
        $poParams = $request['params'];
        $userData =  $request['userData'];
        $purchaseOrderData = $request['purchaseOrderData'];
        $jobData = $request['jobData'];
        if (count($purchaseOrderData)==0) {
            return false;
        }
        $ContactRules = $sharedClass->getCustomerRules($userData['customerid'], $userData['role']);
        $headerImage = array('image'=> K_PATH_IMAGES.'banner.png','imagetype'=>'png');
        $footerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        
   
        $brandImage = $sharedClass->getBrandingImageByCustomerid($jobData['customerid'],'H','JRQ');
        if ($brandImage) {
            $dirpath = $this->config->item('branding_dir').$brandImage['documentid'].'.'.$brandImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $headerImage = array('image'=> $this->config->item('branding_dir').$brandImage['documentid'].'.'.$brandImage['docformat'],'imagetype'=> $brandImage['docformat']);
            }
        }
        
        $brandImage = $sharedClass->getBrandingImage($jobData['customerid'],'F','JRQ');
        if ($brandImage) {
            $dirpath = $this->config->item('branding_dir').$brandImage['documentid'].'.'.$brandImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $footerImage = array('image'=> $this->config->item('branding_dir').$brandImage['documentid'].'.'.$brandImage['docformat'],'imagetype'=> $brandImage['docformat']);
            }
        }
         
        $size = getimagesize($headerImage['image']);
        if((int)$size[0]>800){
            $size[1] = ((int)$size[1]/(int)$size[0])*800;
            $size[0] = 800;
        }
        $headerImage['width']= $size[0];
        $headerImage['height']= $size[1]; 
        
        $size = getimagesize($footerImage['image']);
        if((int)$size[0]>800){
            $size[1] = ((int)$size[1]/(int)$size[0])*800;
            $size[0] = 800;
        }
        $footerImage['width']= $size[0];
        $footerImage['height']= $size[1];
      
        //4 - start the process
       
        //set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('JOB REQUEST - ' .$purchaseOrderData["poref"]);
        $pdf->SetSubject('JOB REQUEST');
         
        $pdf->setHeaderImage($headerImage);
        $pdf->setfooterImage($footerImage);
        $pdf->setJobRequestTitle("JOB REQUEST");
        $pdf->setPOREF($purchaseOrderData["poref"]);
 
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        
        $Headerheight = (int)((int)$headerImage['height'])* 0.264583333; 
        $footerheight = (int)((int)$footerImage['height'])* 0.264583333; 
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, $Headerheight+10, PDF_MARGIN_RIGHT,$footerheight+10);
        $pdf->SetHeaderMargin($Headerheight+10);
        $pdf->SetFooterMargin($footerheight+10);

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
 
        // add a page
        $pdf->AddPage();
        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $NoLeftborder = array('TRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
        //Contractor Info 
        $customer = $this->db->where('customerid', $purchaseOrderData["supplierid"])->get('customer')->row_array();
        //$contact = $this->db->where('contactid', $purchaseOrderData["contactid"])->get('contact')->row_array();
        $CompanyDetail = '<table>
                                <tr><td style ="width:5px;height:6px;font-size:3px;">&nbsp;</td><td style ="height:6px;font-size:3px;width:250px;"></td></tr>
                                <tr><td style ="width:5px;">&nbsp;</td><td ><b>'. $customer["companyname"].'</b>';
         
//        if (isset($contact['firstname'])&& $contact['firstname']!= '')
//        { 
//            $CompanyDetail .='<br>'.$contact['firstname'] . ' ' .$contact['surname']; 
//        } 
        //$CompanyDetail .='<br>ABN :'.$customer['abn'];    
        if (isset($customer['mail1'])&& $customer['mail1']!= '')
        { 
            $CompanyDetail .='<br>'.$customer['mail1']; 
        }

        if (isset($customer['mail2'])&& $customer['mail2']!= '')
        { 
           $CompanyDetail .='<br>'.$customer['mail2']; 
        }
        $CompanyDetail .='<br>'.$customer['mailsuburb'] .' '.$customer['state'].' '.$customer['postcode']; 
        if (isset($customer['phone'])&& $customer['phone']!= '')
        { 
           $CompanyDetail .='<br>P: '.$customer['phone']; 
        }
        if (isset($customer['email'])&& $customer['email']!= '')
        { 
           $CompanyDetail .='<br>E: <a href="mailto:'.$customer['email'] .'">'.$customer['email'] .'</a>'; 
        }
        if (isset($customer['url'])&& $customer['url']!= '')
        { 
            //$CompanyDetail .='<br>W: <a target ="_blank" href="'.$customer['url'] .'">'.$customer['url'] .'</a>';  
        }

        
        $CompanyDetail .='</td></tr>
                            </table>';
       
        $y = $pdf->GetY();
        //MultiCell ($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
        $pdf->MultiCell(100, 28, $CompanyDetail, $border, 'L', false, 1, 15, $y, true, 0, true, true, 0, 'T', false);
        $jobData['custordref1_label'] = isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1';
        $jobData['custordref2_label'] = isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2';
        $jobData['custordref3_label'] = isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3';
        $jobData['custordref3_access'] = isset($ContactRules["hide_custordref3_in_client_portal"]) ? $ContactRules["hide_custordref3_in_client_portal"] : 0;
        
        //PO and Job Details 
        $invoiceDetails = '<table>
                                <tr><td style ="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style ="width:140px;height:6px;font-size:3px;"></td><td style ="height:6px;font-size:3px;width:90px;"></td></tr>          
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">PO Date:&nbsp;&nbsp;</td><td style ="width:90px;">'. format_date($purchaseOrderData['podate'], RAPTOR_DISPLAY_DATEFORMAT).'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">PO REF:&nbsp;&nbsp;</td><td style ="width:90px;">'. $purchaseOrderData["poref"].'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">Job ID:&nbsp;&nbsp;</td><td style ="width:90px;">'. $jobData["jobid"].'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">'. $jobData["custordref1_label"].':&nbsp;&nbsp;</td><td style ="width:90px;">'. $jobData["custordref"].'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">'. $jobData["custordref2_label"].':&nbsp;&nbsp;</td><td style ="width:90px;">'. $jobData["custordref2"].'</td></tr>';
        if($jobData['custordref3_access']==0){
            $invoiceDetails .= '<tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">'. $jobData["custordref3_label"].':&nbsp;&nbsp;</td><td style ="width:90px;">'. $jobData["custordref3"].'</td></tr>';
        }
        $invoiceDetails .= '</table>';
        
        
        $pdf->MultiCell(0, 28, $invoiceDetails, $border, 'L', false, 1, 120, $y, true, 0, true, true, 0, 'T', false);
        
        
        //Customer Section
        $pdf->Ln(7);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(31,73,125); // Grey
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0,7, 'Customer', 0, false, 'L', true, '', 0, false, 'M', 'M');
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        
        $top = $pdf->GetY();
     
      
        $CustomerDetails = '<table>
                                <tr><td style ="width:5px;height:6px;font-size:3px;">&nbsp;</td><td style ="height:6px;font-size:3px;width:250px;"></td></tr>
                                <tr><td style ="width:5px;">&nbsp;</td><td >'. $jobData["site"].'</td></tr>
                            </table>';
        $pdf->MultiCell(0, 22, $CustomerDetails, $border, 'L', false, 1, 15, $top, true, 0, true, true, 0, 'T', false);
       
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        //Site COntact Details 
        $siteContactDetails = '<table>
                                <tr><td style ="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style ="width:140px;height:6px;font-size:3px;"></td><td style ="height:6px;font-size:3px;width:90px;"></td></tr>          
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">Site Contact:&nbsp;&nbsp;</td><td style ="width:90px;">'. $jobData["sitecontact"].'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:140px;text-align:right;font-weight:bold;">Phone:&nbsp;&nbsp;</td><td style ="width:90px;">'. $jobData["sitephone"].'</td></tr>
                          </table>';
        
        $pdf->MultiCell(0, 22, $siteContactDetails, $NoLeftborder, 'L', false, 1, 120, $top, true, 0, true, true, 0, 'T', false);
   
        $pdf->Ln(7);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(31,73,125); // Grey
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(0,7, 'IMPORTANT !!! Please Read the following terms', 0, false, 'L', true, '', 0, false, 'M', 'M');
        
        $pdf->Ln(3.5);
         
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $top = $pdf->GetY();
        $jr_TERMS = '';
//        $globalSettings = $this->db->where('setting_name', 'JRQ_TERMS')->get('tig_setting_global')->row_array();
//        $jr_TERMS = isset($globalSettings['setting_value']) ? $globalSettings['setting_value'] : '';
//        $jr_TERMS = nl2br($jr_TERMS);
        $jr_TERMS = str_replace('<polimit>', format_amount($purchaseOrderData["polimit"]), $jr_TERMS);
        $jr_TERMS = str_replace('<attendbydate>', format_datetime($purchaseOrderData['attendbydate'].' ' .$purchaseOrderData['attendbytime'], 'D M j Y', RAPTOR_DISPLAY_TIMEFORMAT), $jr_TERMS);
        $jr_TERMS = str_replace('<completebydate>', format_datetime($purchaseOrderData['completebydate'].' ' .$purchaseOrderData['completebytime'], 'D M j Y', RAPTOR_DISPLAY_TIMEFORMAT), $jr_TERMS);
        foreach ($purchaseOrderData as $key=> $value) {
            $jr_TERMS = str_replace('<'.$key.'>', $value, $jr_TERMS);
        }
        foreach ($jobData as $key=> $value) {
            $jr_TERMS = str_replace('<'.$key.'>', $value, $jr_TERMS);
        }
      
        $importantDetails = '<table>
                                <tr><td style ="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style ="width:340px;height:6px;font-size:3px;"></td><td style ="height:6px;font-size:3px;width:290px;font-weight:bold;"></td></tr>          
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:340px;text-align:right;font-weight:bold;height:18px;">Works Must not exceed:&nbsp;&nbsp;</td><td style ="width:290px;height:18px;color:red;font-weight:bold;">'. format_amount($purchaseOrderData["polimit"]).'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:340px;text-align:right;font-weight:bold;height:18px;">Must Attend site by:&nbsp;&nbsp;</td><td style ="width:290px;height:18px;color:red;font-weight:bold;">'. format_datetime($purchaseOrderData['attendbydate'].' ' .$purchaseOrderData['attendbytime'], 'D M j Y', RAPTOR_DISPLAY_TIMEFORMAT).'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:340px;text-align:right;font-weight:bold;height:18px;">Must Complete works by:&nbsp;&nbsp;</td><td style ="width:290px;height:18px;color:red;font-weight:bold;">'. format_datetime($purchaseOrderData['completebydate'].' ' .$purchaseOrderData['completebytime'], 'D M j Y', RAPTOR_DISPLAY_TIMEFORMAT).'</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:630px;text-align:left;font-size:11px;" colspan="2">'. $jr_TERMS.'</td></tr>
                                <tr><td style ="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style ="width:340px;height:6px;font-size:3px;"></td><td style ="height:6px;font-size:3px;width:290px;font-weight:bold;"></td></tr>
                          </table>';
        $pdf->MultiCell(0, 16, $importantDetails, $border, 'L', false, 1, 15, $top, true, 0, true, true, 0, 'T', false);
       
       
        $pdf->Ln(7);
       //Scope Section
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(31,73,125); // Grey
        $pdf->SetTextColor(255, 255, 255);
         $pdf->Cell(0,7, 'SCOPE  OF WORKS', 0, false, 'L', true, '', 0, false, 'M', 'M');
        $pdf->Ln(3.5);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        
        $y= $pdf->GetY();
        
        $jobdescription = '<table>
                                <tr><td style ="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style ="width:630px;height:6px;font-size:3px;"></td></tr> 
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:630px;text-align:left;font-size:14px;">Job Description:</td></tr>
                                <tr><td style ="width:3px;">&nbsp;</td><td style ="width:630px;text-align:left;font-size:11px;">'. nl2br($jobData['jobdescription']).'</td></tr>
                                <tr><td style ="width:3px;height:6px;font-size:3px;">&nbsp;</td><td style ="width:630px;height:6px;font-size:3px;"></td></tr>
                          </table>';
        $pdf->MultiCell(0, (280-$footerheight)-$y, $jobdescription, $border, 'L', false, 1, 15, '', true, 0, true, true, 0, 'T', false);
    
          
        $filelocation =  $this->config->item('jobrequest_dir');
        if(!$filelocation){
            $filelocation = $_SERVER['DOCUMENT_ROOT'].'/infomaniacDocs/jrq';
 
        }
        $open = false;
        if(isset($poParams['open'])){
            $open = (bool)$poParams['open'];
        }
        
        //5 - get inserted id values
        $file_name = 'jrq_' . $purchaseOrderData["poref"] . '.pdf';
        
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
        
        
        $logClass->log('Create Job Request');
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;
     

    }
	
}


/* End of file GeneratePDFChain.php */