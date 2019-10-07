<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Project: Raptor
 * Package: CI
 * Subpackage: Libraries
 * File: Invoice_pdf.php
 * Description: This is a Invoice PDF class for Create invoices
 * Created by : Itgrid Munish <itgrid.munish@gmail.com>
 *
 */
require_once APPPATH."/third_party/tcpdf/tcpdf.php"; 
require_once APPPATH."/third_party/tcpdf/config/tcpdf_config.php";
class Invoice_pdf extends TCPDF
{
     
    
    
    private $CompanyDetails="";
    private $InvoiceDetails="";
    private $CustomerDetails="";
    function __construct()
    {
       
        parent::__construct();
    }
   
    public function setCompanyDetail($param) {
        $this->CompanyDetails=$param;
    }
    public function setInvoiceDetail($param) {
        $this->InvoiceDetails=$param;
    }
    
     public function setCustomerDetail($param) {
        $this->CustomerDetails=$param;
    }
    //Page header
    public function Header() {
        
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        $this->Ln(7);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0,15, 'TAX INVOICE', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln();
        
         $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', '', 8);
        //$this->setCellHeightRatio(1.10);
        $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        
        //Image ($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs = array())
        $this->Image(K_PATH_IMAGES.'logo.png', 15, 20, 67, 25, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
        
        //MultiCell ($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
        $this->MultiCell(0, 35, $this->InvoiceDetails, $border, 'L', false, 1, 115, 20, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(80, 32, $this->CompanyDetails, $border, 'L', false, 1, 15, 48, true, 0, true, true, 0, 'T', false);
        $this->MultiCell(0, 23, $this->CustomerDetails, $border, 'L', false, 1, 115, 57, true, 0, true, true, 0, 'T', false);
  
        
            
    }

	// Page footer
    public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
    
}