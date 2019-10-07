<?php 
/**
 * Quote PDF Libraries Class
 *
 * This is a Quote PDF class for Generate Quote PDF 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once( __DIR__.'/../../third_party/tcpdf/tcpdf.php');
require_once( __DIR__.'/../../third_party/tcpdf/config/tcpdf_config.php');

/**
 * Quote PDF Libraries Class
 *
 * This is a Quote PDF class for Generate Quote PDF 
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Quote
 * @filesource          QuotePDF.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class QuotePDF extends TCPDF
{
   
    /**
    * Quote Ref Id 
    * 
    * @var integer
    */
    private $rfqno;
    
     /**
    * Quote Number 
    * 
    * @var String
    */
    private $quoteno;
    
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
     * It sets the Pdf rfq Number
     * 
     * @param string $quoteno - rfq Number
     */
    public function setQuoteno($quoteno) {
        $this->$quoteno = $quoteno;
    }
    
    /**
     * It sets the Pdf rfq Number
     * 
     * @param integer $rfqno - rfq Number
     */
    public function setRfqno($rfqno) {
        $this->rfqno = $rfqno;
    }
   
   /**
     * Set Custom PDF page Header
     * 
     */
    public function Header() {
        
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        $this->Ln(2);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0,7, 'QUOTE', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $top= $this->GetY();
        $this->Line(15, $top,195, $top);	
     
           
    }

    /**
     * Set Custom PDF page Footer
     * 
     */
    public function Footer() {
            // Position at 15 mm from bottom
            $this->SetY(-7);
            
            // Set font
            $this->SetFont('helvetica', 'I', 8);
           
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica', '', 8);
            //$this->setCellHeightRatio(1.10);
            $border = array('LTRB' => array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
            $top= $this->GetY();
            $this->Line(0, $top-2,240, $top-2);	
             
           
            //MultiCell ($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false)
            // Quote number
            $this->MultiCell(0, 7, 'Quote '.$this->quoteno, 0, 'L', false, 1, 15, $top, true, 0, true, true, 0, 'T', false);
             // Page number
            $this->MultiCell(0, 7, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, 'C', false, 1, 15, $top, true, 0, true, true, 0, 'T', false);
             // Current Date
            $this->MultiCell(0, 7, date('d.m.Y'), 0, 'R', false, 1, 15, $top, true, 0, true, true, 0, 'T', false);
  
     
     } 
}