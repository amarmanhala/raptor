<?php 
/**
 * GeneratePDFChain Libraries Class
 *
 *  This is a Invoice Chain class for generate Invoice PDF
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractJobChain.php');
require_once( __DIR__.'/../../LogClass.php');
require_once( __DIR__.'/../JobPDF.php');
require_once( __DIR__.'/../../shared/SharedClass.php');

/**
 * GeneratePDFChain Libraries Class
 *
 *  This is a GeneratePDFChain Chain class for generate Invoice PDF
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Invoice/chain
 * @filesource          GeneratePDFChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class GeneratePDFChain extends AbstractJobChain
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
        $pdf = new JobPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  
        //3 - get the parts connected
        $pdfParams = $request['pdfParams'];
    
        $jobData = $request['jobData'];
        $documentData = $request['documentData'];
        $documentData = $documentData['data'];
        $imagesData = $request['imagesData'];
        $notesData = $request['notesData'];
        $notesData = $notesData['data'];
        $userData =  $request['userData'];
        $printOptions = explode(",", $pdfParams['print_options']);
        
        //contact rules
        $ContactRules = $sharedClass->getCustomerRules($userData['customerid'], $userData['role']);
        
        if (count($jobData)==0) {
            return false;
        }
 
        $headerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $footerImage = array('image'=> K_PATH_IMAGES.'banner.jpg','imagetype'=>'jpg');
        $logoImage = array('image'=>K_PATH_IMAGES.'logo.png', 'imagetype'=>'PNG');
       
        
        $brandingImage = $sharedClass->getBrandingImage('H', 'R');
        if (count($brandingImage)>0) {
            $dirpath = $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $headerImage = array('image'=> $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'],'imagetype'=> $brandingImage['docformat']);
            }
        }
        
        $brandingImage = $sharedClass->getBrandingImage('F', 'R');
        if (count($brandingImage)>0) {
            $dirpath = $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'];
                            
            if (file_exists($dirpath)) {
               $footerImage = array('image'=> $this->config->item('branding_dir').$brandingImage['documentid'].'.'.$brandingImage['docformat'],'imagetype'=> $brandingImage['docformat']);
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
      
        //set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle('JOB - ' .$jobData["jobid"]);
        $pdf->SetSubject('JOB');
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $Headerheight = (int)((int)$headerImage['height'])* 0.264583333; 
        $footerheight = (int)((int)$footerImage['height'])* 0.264583333; 
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, $Headerheight+20, PDF_MARGIN_RIGHT,$footerheight+10);
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
        
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('helvetica', '', 8);
        $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
        //Company Info 
        $address =  nl2br($sharedClass->getSysValue("dochead"));
        $owner= nl2br($sharedClass->getSysValue("tradingname"));
        $abn= nl2br($sharedClass->getSysValue("abn"));

        $CompanyDetail = '<table>
                        <tr><td style ="width:5px;height:3px;font-size:3px;">&nbsp;</td><td style ="height:3px;font-size:3px;width:250px;"></td></tr>
                        <tr><td style ="width:5px;">&nbsp;</td><td ><b>'. $owner.'</b>';
                        $CompanyDetail .='<br>ABN : '.$abn;
                        $CompanyDetail .='<br>'.$address;
        
        $CompanyDetail .='</td></tr>
                            </table>';
        
        $pdf->setHeaderImage($headerImage);
        $pdf->setfooterImage($footerImage);
        $pdf->setLogoImage($logoImage);
        $pdf->setjobTitle('JOB - ' .$jobData["jobid"]);
        $pdf->setjobid($jobData["jobid"]);
        $pdf->setCompanyDetail($CompanyDetail);
        
        // add a page
        $pdf->AddPage();
        
        //Job Detail Section
        if(is_array($printOptions) && (array_search('jl', $printOptions) > -1)) {
            $pdf->Ln(4);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(31,73,125); // Grey
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(0, 7, 'Job Detail', 0, false, 'L', true, '', 0, false, 'M', 'M');

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(3.8);

            $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));

            //Job Data formating for display
            $jobData['sitecontact'] = '';
            if($jobData['sitecontact'] != '') {
                $jobData['sitecontact'] = $jobData['sitecontact'];
                if($jobData['sitephone'] != '') {
                    $jobData['sitecontact'] = $jobData['sitecontact'].' - ('.$jobData['sitephone'].')';
                }
            }

            $jobData['notexceed'] = format_amount($jobData['notexceed']);
            $jobData['pdate'] = format_datetime($jobData['pdate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);
            $jobData['duedate'] = format_date($jobData['duedate'], RAPTOR_DISPLAY_DATEFORMAT);
            $jobData['duetime'] = format_time($jobData['duetime'], RAPTOR_DISPLAY_TIMEFORMAT);
            $jobData['jcompletedate'] = format_datetime($jobData['jcompletedate'], RAPTOR_DISPLAY_DATEFORMAT, RAPTOR_DISPLAY_TIMEFORMAT);

            $jobData['custordref1_label'] = isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"] : 'Order Ref 1';
            $jobData['custordref2_label'] = isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"] : 'Order Ref 2';
            $jobData['custordref3_label'] = isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"] : 'Order Ref 3';

            $jobData['custordref3_access'] = isset($ContactRules["hide_custordref3_in_client_portal"]) ? $ContactRules["hide_custordref3_in_client_portal"] : 0;

            //Job Data
            $jobDetail = '<table cellpadding="2" style="border:solid 1px black;">';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;height:3px;font-size:3px;">&nbsp;</td><td style ="height:3px;font-size:3px;width:476px;"></td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Job Id</td><td>'. $jobData['jobid'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Job Status</td><td>'. $jobData['portaldesc'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Site</td><td>'. $jobData['siteline1'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Site Address</td><td>'. $jobData['site'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Territory</td><td>'. $jobData['territory'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">'.$jobData['custordref1_label'].'</td><td>'. $jobData['custordref'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">'.$jobData['custordref2_label'].'</td><td>'. $jobData['custordref2'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">'.$jobData['custordref3_label'].'</td><td>'. $jobData['custordref3'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Priority</td><td>'. $jobData['priority'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">$ Limit</td><td>'. $jobData['notexceed'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Quoted Required?</td><td>'. $jobData['quoted'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Entry Date</td><td>'. $jobData['pdate'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Due Date</td><td>'. $jobData['duedate'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Completion Date</td><td>'. $jobData['jcompletedate'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Site FM</td><td>'. $jobData['sitefm'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Site Contact</td><td>'. $jobData['sitecontact'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;">Job Description</td><td>'. $jobData['jobdescription'].'</td></tr>';
            $jobDetail .= '<tr><td style ="width:10px;">&nbsp;</td><td style ="width:150px;height:3px;font-size:3px;">&nbsp;</td><td style ="height:3px;font-size:3px;width:476px;"></td></tr>';

            $jobDetail .='</table>';

            // output the HTML content

            $pdf->writeHTML($jobDetail, true, false, true, false, '');
        }
        
        
        //Job Note Section
        if(is_array($printOptions) && (array_search('jn', $printOptions) > -1)) {
            $pdf->Ln(7);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(31,73,125); // Grey
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(0, 7, 'Job Notes', 0, false, 'L', true, '', 0, false, 'M', 'M');

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(3.8);
            $color1 = "#FFFFFF";
            $color2 = "#F4F9FF";
            $html = '<table style ="border:1px solid black;" cellspacing="0" cellpadding="4">
                <thead>
                <tr nobr="true">
                    <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;">Job Note Id</th>

                    <th style ="border:1px solid black;font-weight:bold;width:356px;background-color:#CCCCCC;">Notes</th>
                    <th style ="border:1px solid black;font-weight:bold;width:100px;background-color:#CCCCCC;">Note Type</th>
                    <th style ="border:1px solid black;font-weight:bold;width:80px;background-color:#CCCCCC;">Date</th>
                </tr></thead><tbody>';

            $count = 0;
            foreach ($notesData as $row) {
                $count = $count+1;
                  $row_color = ($count % 2) ? $color1 : $color2;
                $html .= '<tr nobr="true" bgcolor="'. $row_color.'">
                            <td style ="border:1px solid black;width:100px;">'.$row['jobnoteid'].'</td>
                            <td style ="border:1px solid black;width:356px;">' . $row['notes'] . '</td>
                            <td style ="border:1px solid black;width:100px;">' . $row['notetype'] . '</td>
                            <td style ="border:1px solid black;width:80px;">' . format_date($row['date'], RAPTOR_DISPLAY_DATEFORMAT) . '</td>
                        </tr>';
            }

            /*for ($i= $count;$i<=4;$i++) {
                 $row_color = ($i % 2) ?  $color2:$color1;
                  $html .= '<tr bgcolor="'. $row_color.'">
                            <td style ="border:1px solid black;width:100px;">&nbsp;</td>
                            <td style ="border:1px solid black;width:356px;">&nbsp;</td>
                            <td style ="border:1px solid black;width:100px;">&nbsp;</td>
                            <td style ="border:1px solid black;width:80px;">&nbsp;</td>
                        </tr>';

            }*/
            $html .= '</tbody></table>';

            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
        }
       
        
        
        //job documents
        if(is_array($printOptions) && (array_search('jd', $printOptions) > -1)) {
            $pdf->Ln(7);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(31,73,125); // Grey
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(0, 7, 'Job Docs', 0, false, 'L', true, '', 0, false, 'M', 'M');

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(3.8);
            $color1 = "#FFFFFF";
            $color2 = "#F4F9FF";
            $html = '<table style ="border:1px solid black;" cellspacing="0" cellpadding="4">
                <thead>
                <tr nobr="true">
                    <th style ="border:1px solid black;font-weight:bold;width:338px;background-color:#CCCCCC;">Document Name</th>

                    <th style ="border:1px solid black;font-weight:bold;width:218px;background-color:#CCCCCC;">Docfolder</th>
                    <th style ="border:1px solid black;font-weight:bold;width:80px;background-color:#CCCCCC;">Date Added</th>
                </tr></thead><tbody>';

            $count = 0;
            foreach ($documentData as $row) {
                $count = $count+1;
                  $row_color = ($count % 2) ? $color1 : $color2;
                $html .= '<tr nobr="true" bgcolor="'. $row_color.'">
                            <td style ="border:1px solid black;width:338px;">'.$row['docname'].'</td>
                            <td style ="border:1px solid black;width:218px;">' . $row['docfolder'] . '</td>
                            <td style ="border:1px solid black;width:80px;">' . format_date($row['dateadded'], RAPTOR_DISPLAY_DATEFORMAT) . '</td>
                        </tr>';
            }

            /*for ($i= $count;$i<=4;$i++) {
                 $row_color = ($i % 2) ?  $color2:$color1;
                  $html .= '<tr bgcolor="'. $row_color.'">
                            <td style ="border:1px solid black;width:338px;">&nbsp;</td>
                            <td style ="border:1px solid black;width:218px;">&nbsp;</td>
                            <td style ="border:1px solid black;width:80px;">&nbsp;</td>
                        </tr>';

            }*/
            $html .= '</tbody></table>';

            // output the HTML content
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //images
        if(is_array($printOptions) && (array_search('im', $printOptions) > -1)) {
            $pdf->Ln(7);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->SetFillColor(31,73,125); // Grey
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(0, 7, 'Images', 0, false, 'L', true, '', 0, false, 'M', 'M');

            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->Ln(3.8);

            $html = '<table cellspacing="0" cellpadding="10">';
            foreach ($imagesData as $k => $value) {
                 
                
                $imagepath = $this->config->item('document_path').$value['documentid'].'_thumb.'.$value['docformat'];
                $dirpath = $this->config->item('document_dir').$value['documentid'].'_thumb.'.$value['docformat'];
                if (file_exists($dirpath)) {
                   $html .= '<tr nobr="true"><td style ="border-bottom:1px solid gray;width:150px;"><img src="'.$imagepath.'" /></td>'
                           . '<td style ="border-bottom:1px solid gray;width:486px;vertical-align:middle;" valign="middle">Id: '.$value['documentid'].' '.format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT).'<br />'.$value['docname'].'<br />'.$value['documentdesc'].'</td></tr>';
               }
               else{
                    $imagepath = $this->config->item('document_path').$value['documentid'].'.'.$value['docformat'];
                    $dirpath = $this->config->item('document_dir').$value['documentid'].'.'.$value['docformat'];
                    if (file_exists($dirpath)) {
                       $html .= '<tr nobr="true"><td style ="border-bottom:1px solid gray;width:150px;"><img src="'.$imagepath.'" /></td>'
                               . '<td style ="border-bottom:1px solid gray;width:486px;vertical-align:middle;" valign="middle">Id: '.$value['documentid'].' '.format_date($value['dateadded'], RAPTOR_DISPLAY_DATEFORMAT).'<br />'.$value['docname'].'<br />'.$value['documentdesc'].'</td></tr>';
                   }
               }
            }  

            $html .= '</table>';

            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        //echo $html;
        //exit;
        
        $filelocation =  $this->config->item('document_dir');
        if(!$filelocation){
            $filelocation = $_SERVER['DOCUMENT_ROOT'].'/infomaniacDocs/jobdocs';
 
        }
        $open = false;
        if(isset($pdfParams['open'])){
            $open = (bool)$pdfParams['open'];
        }
        
        //5 - get inserted id values
        $file_name = 'job_' . $jobData["jobid"] . '.pdf';
        
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
        
        
        $logClass->log('Create Job PDF');
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;
     

    }
	
}


/* End of file GeneratePDFChain.php */