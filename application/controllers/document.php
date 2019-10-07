<?php 
/**
 * Document Controller Class
 *
 * This is a Documents controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Documents Controller Class
 *
 * This is a Documents controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Document
 * @filesource          Document.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Document extends CI_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
         //  Load libraries
        
        $this->config->load('raptor_config');
        $this->load->library('document/DocumentClass');
    }

    /**
    * This function use for show Documents
    * 
    * @return void 
    */
    public function index()
    {
        show_404();
    } 
    
    /**
    * This function use for download report documents
    * 
    * @param integer $documentid 
    * @return void 
    */
    public function downloadReport($documentid) {
        
        $documentid = encrypt_decrypt('decrypt', $documentid);
        
        //Load Selected document data
        $data = $this->documentclass->getDocumentById($documentid);
        if (count($data) > 0) {
          
            $docname = $data['docname'];
            $path = $this->config->item('document_dir').$docname;
            if (file_exists($path)) {
                $content = file_get_contents($path);
                $docname = $data['docname'];
                
                //Load download helper 
                $this->load->helper('download');
        
                force_download($docname, $content);
            }
            else{
                echo "document doesn't exist.";
                //throw new Exception("document doesn't exist. Documentid: " . $documentid);
            }
        
        }
        else{
            echo "document doesn't exist.";
            //throw new Exception("document doesn't exist. Documentid: " . $documentid);
        }
    }
}