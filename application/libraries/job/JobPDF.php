<?php 
/**
 * Job PDF Libraries Class
 *
 * This is a Job PDF class for Generate Job PDF 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( __DIR__.'/../../third_party/tcpdf/tcpdf.php');
require_once( __DIR__.'/../../third_party/tcpdf/config/tcpdf_config.php');

/**
 * Job PDF Libraries Class
 *
 * This is a Job PDF class for Generate Job PDF 
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Job
 * @filesource          JobPDF.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class JobPDF extends TCPDF
{
    /**
    * Job PDF Title
    * 
    * @var string
    */
    private $jobtitle;
    
    /**
    * Job Id 
    * 
    * @var integer
    */
    private $jobid;

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
     * @param string $jobtitle - Job PDF Title 
     */
    public function setjobTitle($jobtitle) {
        $this->jobtitle = $jobtitle;
    }
    
    /**
     * It sets the Pdf Job Id
     * 
     * @param integer $jobid - Job ID
     */
    public function setjobid($jobid) {
        $this->jobid = $jobid;
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
        
        $height = (int)((int)$this->headerImage['height'])* 0.264583333;
        $width = (int)((int)$this->headerImage['width'])* 0.264583333;
         //Logo 
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->headerImage['image'], 0, 0, $width, $height, $this->headerImage['imagetype'], '', 'M', false, 300, 'C', false, false, 0, false, false, false);
        $this->SetY($height+5);
      
      
        // Set font
        $this->SetFont('helvetica', 'N', 15);
        // Title
        $this->Ln(2);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0,7, $this->jobtitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $top= $this->GetY();
        $this->Line(0, $top,210, $top);	
        
       
        
//        $this->SetTextColor(0, 0, 0);
//        $this->SetFont('helvetica', '', 8);
//        $border = array('LTRB' => array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
//        
//        $this->Image($this->logoImage['image'], 128, 17, 67, 25, $this->logoImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
//        $this->MultiCell(80, 32, $this->companyDetails, $border, 'L', false, 1, 15, 17, true, 0, true, true, 0, 'T', false);
    }

    /**
     * Set Custom PDF page Footer
     * 
     */
    public function Footer() {
           	
//        $this->SetY(-10);
//        $top= $this->GetY();
//        $this->Line(0, $top,210, $top);
//        // Set font
//        $this->SetFont('helvetica', 'I', 8);
//        // Page number
//        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    
        $top= $this->GetY();
        $this->Line(0, $top+10,210, $top+10);	
        
         
        $height = (int)((int)$this->footerImage['height'])* 0.264583333;
        $width = (int)((int)$this->footerImage['width'])* 0.264583333;
       //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->footerImage['image'], 0, $top+10, $width, $height, $this->footerImage['imagetype'], '', 'M', false, 300, 'C', false, false, 0, false, false, false);
    
        
    }
   
}