<?php 
/**
 * Report PDF Libraries Class
 *
 * This is a Report PDF class for Generate Report PDF 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( __DIR__.'/../../third_party/tcpdf/tcpdf.php');
require_once( __DIR__.'/../../third_party/tcpdf/config/tcpdf_config.php');

/**
 * Report PDF Libraries Class
 *
 * This is a Report PDF class for Generate Report PDF 
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Report
 * @filesource          ReportPDF.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class ReportPDF extends TCPDF
{
    /**
    * Report PDF Title
    * 
    * @var string
    */
    private $reporttitle;
    
    /**
    * Report Id 
    * 
    * @var integer
    */
    private $reportid;

    /**
    * array of image with location and type
    * 
    * @var array
    */
    private $headerImage; 
    
    /**
    * array of image with location and type
    * 
    * @var array
    */
    private $footerImage;  
    
     /**
    * Company Detail
    * 
    * @var string
    */
    private $companyDetails;
    
    /**
    * Logo Image array of image with location and type
    * 
    * @var array
    */
    private $logoImage; 
    
    /**
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
       
        parent::__construct();
    }

    /**
     * It sets the Pdf Title
     * 
     * @param string $reporttitle - Report PDF Title 
     */
    public function setreportTitle($reporttitle) {
        $this->reporttitle = $reporttitle;
    }
    
    /**
     * It sets the Pdf Report Id
     * 
     * @param integer $reportid - Report ID
     */
    public function setreportid($reportid) {
        $this->reportid = $reportid;
    }
    
    
   
    /**
     * It sets the pdf logo Image
     * 
     * @param array $logoImage - logo Image Array
    */
    public function setLogoImage($logoImage) {
        $this->logoImage = $logoImage;
    }
    
    /**
     * It sets the pdf company Details
     * 
     * @param string $companyDetails 
    */
    public function setCompanyDetail($companyDetails) {
        $this->companyDetails = $companyDetails;
    }
    
     /**
     * It sets the Header Image
     * 
     * @param array $headerImage - header Image Array
     */
    public function setHeaderImage($headerImage) {
        $this->headerImage = $headerImage;
    }
    
    /**
     * It sets the footer Image
     * 
     * @param array $footerImage - footer Image Array
     */
    public function setfooterImage($footerImage) {
        $this->footerImage = $footerImage;
    }
    
    /**
     * Set Custom PDF page header
     * 
     */
    public function Header() {
        
        $this->logoImage = array('image'=>K_PATH_IMAGES.'logo.png', 'imagetype'=>'PNG');
         //Logo 
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->headerImage['image'], 0, 0, 210, 30, $this->headerImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
        $this->SetY(35);
        // Set font
        $this->SetFont('helvetica', 'N', 15);
        // Title
        $this->Ln(2);
        //$this->SetTextColor(255, 0, 0);
        //$this->Cell(0,7, $this->reporttitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        //$this->Ln(5);
        //$top= $this->GetY();
        //$this->Line(0, $top,210, $top);	
        
       
    }

    /**
     * Set Custom PDF page Footer
     * 
     */
    public function Footer() {
           	
        $this->SetY(-30);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
        // Page number
        $this->Cell(0, 15, 'Page: '.$this->getAliasNumPage(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    $this->SetY(-10);
        $top= $this->GetY();
        $this->Line(0, $top-10, 210, $top-10);	
       //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->footerImage['image'], 0, $top-10, 210, 20, $this->footerImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
 
        
    }
   
}