<?php 
/**
 * PurchaseOrder PDF Libraries Class
 *
 * This is a PurchaseOrder PDF class for Generate PurchaseOrder PDF 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( __DIR__.'/../../third_party/tcpdf/tcpdf.php');
require_once( __DIR__.'/../../third_party/tcpdf/config/tcpdf_config.php');

/**
 * PurchaseOrder PDF Libraries Class
 *
 * This is a PurchaseOrder PDF class for Generate PurchaseOrder PDF 
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder
 * @filesource          PurchaseOrderPDF.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class PurchaseOrderPDF extends TCPDF
{
    
    /**
    * PurchaseOrder PDF Title
    * 
    * @var string
    */
    private $jobRequestTitle;
    
    /**
    * poref 
    * 
    * @var integer
    */
    private $poref;
    
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
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
       
        parent::__construct();
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
     * It sets the Pdf Title
     * 
     * @param string $jobRequestTitle - Job Request PDF Title 
     */
    public function setJobRequestTitle($jobRequestTitle) {
        $this->jobRequestTitle = $jobRequestTitle;
    }
   
    /**
     * It sets the poref
     * 
     * @param integer $poref - poref
     */
    public function setPOREF($poref) {
        $this->poref = $poref;
    }
   
    /**
     * Set Custom PDF page Header
     * 
     */
    public function Header() {
        
        // Set font
        
        $height = (int)((int)$this->headerImage['height'])* 0.264583333;
        $width = (int)((int)$this->headerImage['width'])* 0.264583333;
         //Logo 
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->headerImage['image'], 0, 0, $width, $height, $this->headerImage['imagetype'], '', 'M', false, 300, 'C', false, false, 0, false, false, false);
        $this->SetY($height);
//        // Set font
//        $this->SetFont('helvetica', 'N', 15);
//        // Title
//        $this->Ln(2);
//        $this->SetTextColor(255, 0, 0);
//        $this->Cell(0,7, $this->jobRequestTitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
//        $this->Ln(5);
        $top= $this->GetY();
        $this->Line(0, $top,210, $top);	
        
       
        
           
    }

    /**
     * Set Custom PDF page Footer
     * 
     */
    public function Footer() {
        
        $top= $this->GetY();
        $this->Line(0, $top+10,210, $top+10);	
        
         
        $height = (int)((int)$this->footerImage['height'])* 0.264583333;
        $width = (int)((int)$this->footerImage['width'])* 0.264583333;
       //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->footerImage['image'], 0, $top+10, $width, $height, $this->footerImage['imagetype'], '', 'M', false, 300, 'C', false, false, 0, false, false, false);
    
     } 
}