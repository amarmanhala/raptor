<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 * ======================================= 
 */  
require_once APPPATH."/third_party/PHPExcel.php"; 
 
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
    
    
    public function Exportexcel($title, $dir_path, $file_name, $heading, $data_array) 
    {
       
        $this->getProperties()->setCreator("DCFM")
			     ->setLastModifiedBy("DCFM")
			     ->setTitle($title)
			     ->setSubject("DCFM Client Portal :: ". $title)
		   	     ->setDescription("DCFM Client Portal :: ".$title)
			      ->setKeywords($title)
		      	     ->setCategory($title);
            $this->getActiveSheet()->setTitle($title);
            //Loop Heading
             $rowNumberH = 1;
            $colH = 'A';
            $colH1 = $colH;
            foreach($heading as $h){
                $colH1 = $colH;
                $this->getActiveSheet()->setCellValue($colH.$rowNumberH, $h);
                $colH++;    
            }


            //Loop Result
            $totn=count($data_array);
            $maxrow=$totn+1;
            $row = 2;
            $no = 1;

            foreach($data_array as $row1)
            { 
                $colH = 'A';
                foreach($row1 as $h){
                   $this->getActiveSheet()->setCellValue($colH.$row, $h);
                    $colH++; 
                }

                $row++;
                $no++; 

            }
         //Freeze pane
            $this->getActiveSheet()->freezePane('A2');
            //Cell Style
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );

             $colH1--;
            $this->getActiveSheet()->getStyle('A1:'.$colH1.$maxrow)->applyFromArray($styleArray);
            //Save as an Excel BIFF (xls) file
            $objWriter = PHPExcel_IOFactory::createWriter($this,'Excel5');

            // Load the file helper and write the file to your server
            $objWriter->save($dir_path.'/'.$file_name);
            return true;
             
    }
         
}