<?php 
/**
 * Invoice PDF Libraries Class
 *
 * This is a Invoice PDF class for Generate Invoice PDF 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( __DIR__.'/../../third_party/tcpdf/tcpdf.php');
require_once( __DIR__.'/../../third_party/tcpdf/config/tcpdf_config.php');

/**
 * Invoice PDF Libraries Class
 *
 * This is a Invoice PDF class for Generate Invoice PDF 
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Invoice
 * @filesource          InvoicePDF.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InvoicePDF extends TCPDF
{
    
    
    
    /**
    * Invoice PDF Title
    * 
    * @var string
    */
    private $invoicetitle = 'TAX INVOICE';
    
    
    /**
    * Invoice Number
    * 
    * @var string
    */
    private $invoiceno; 
    
     /**
    * Company Detail
    * 
    * @var string
    */
    private $companyDetails;
    
    /**
    * Invoice Details
    * 
    * @var string
    */
    private $invoiceDetails;
    /**
    * Customer Details
    * 
    * @var string
    */
    private $customerDetails;
    
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
     * It sets the Pdf Invoice No
     * 
     * @param string $invoiceno - Invoice no
     */
    public function setinvoiceno($invoiceno) {
        $this->invoiceno = $invoiceno;
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
     * It sets the Pdf invoice Details
     * 
     * @param string $invoiceDetails
     */
    public function setInvoiceDetail($invoiceDetails) {
        $this->invoiceDetails = $invoiceDetails;
    }
    
    
    /**
     * It sets the Pdf customer Details
     * 
     * @param string $customerDetails
     */
    public function setCustomerDetail($customerDetails) {
        $this->customerDetails = $customerDetails;
    }
    
     /**
     * Set Custom PDF page header
     * 
     */
    public function Header() {
        
        $this->logoImage = array('image'=>K_PATH_IMAGES.'logo.png', 'imagetype'=>'PNG');
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        $this->Ln(2);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0,7, $this->invoicetitle, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $top= $this->GetY();
        $this->Line(0, $top,210, $top);	
        
        //Banner 
        //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
        //$this->Image($this->headerImage['image'], 0, $top, 210, 20, $this->headerImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
       
         
        
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 8);
        $border = array('LTRB' => array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs = array())
        $this->Image($this->logoImage['image'], 15, 17, 67, 25, $this->logoImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
        
        //MultiCell ($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
        $this->MultiCell(0, 35, $this->invoiceDetails, $border, 'L', false, 1, 115, 17, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(80, 32, $this->companyDetails, $border, 'L', false, 1, 15, 45, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(0, 23, $this->customerDetails, $border, 'L', false, 1, 115, 54, true, 0, true, true, 0, 'T', false);
  
        
           
    }

    /**
     * Set Custom PDF page Footer
     * 
     */
    public function Footer() {
        
        
//            $top= $this->GetY();
//            $this->Line(0, $top-10,210, $top-10);	
//           //Image ($file, $x='', $y='', $w=0, $h=0, $type ='', $link ='', $align='', $resize =false, $dpi=300, $palign='', $ismask =false, $imgmask =false, $border=0, $fitbox=false, $hidden=false, $fitonpage =false, $alt =false, $altimgs = array())
//            $this->Image($this->footerImage['image'], 0, $top-10, 210, 20, $this->footerImage['imagetype'], '', '', false, 300, '', false, false, 0, false, false, false);
            
            // Position at 10 mm from bottom
           	
            $this->SetY(-10);
            $top= $this->GetY();
            $this->Line(0, $top,210, $top);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
     } 
}