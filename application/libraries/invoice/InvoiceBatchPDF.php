<?php 
/**
 * Invoice Batch PDF Libraries Class
 *
 * This is a Invoice PDF class for Generate Invoice PDF 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( __DIR__.'/../../third_party/tcpdf/tcpdf.php');
require_once( __DIR__.'/../../third_party/tcpdf/config/tcpdf_config.php');

/**
 * Invoice Batch PDF Libraries Class
 *
 * This is a Invoice Batch PDF class for Generate Invoice Batch PDF 
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Invoice
 * @filesource          InvoiceBatchPDF.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class InvoiceBatchPDF extends TCPDF
{

    
    /**
    * Invoice PDF Title
    * 
    * @var string
    */
    private $invoicetitle = 'TAX INVOICE';
   
    /**
    * Logo Image array of image with location and type
    * 
    * @var array
    */
    private $logoImage; 
    
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
     * @param string $invoicetitle - Invoice PDF Title 
     */
    public function setinvoiceTitle($invoicetitle) {
        $this->invoicetitle = $invoicetitle;
    }
    
    
    /**
     * Set Custom PDF page header
     * 
     */
    public function Header() {
        
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        $this->Ln(2);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0,7, $this->invoicetitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $top= $this->GetY();
        $this->Line(0, $top,210, $top);	
        //Logo 
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->headerImage['image'], 0, $top, 210, 20, $this->headerImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
    }

    /**
     * Set Custom PDF page Footer
     * 
     */
    public function Footer() {
        
        $top= $this->GetY();
        $this->Line(0, $top-10,210, $top-10);	
       //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        $this->Image($this->footerImage['image'], 0, $top-10, 210, 20, $this->footerImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
    }
    
    
}