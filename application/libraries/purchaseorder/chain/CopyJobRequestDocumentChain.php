<?php 
/**
 * CopyJobRequestDocumentChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for create a Copy job request Document in docs folder
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * CopyJobRequestDocumentChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for create a Copy job request Document in docs folder
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          CopyJobRequestDocumentChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class CopyJobRequestDocumentChain extends AbstractPurchaseOrderChain
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
    * @param Class $nextService - class for execute  
    *  
    */
    
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }

    /**
    * This function use for create document for job request
     * 
    * @param array $request - the request is array of required parameter for creating document
    * @return integer -  
    */
    
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'CopyJobRequestDocumentChain');
         
        $purchaseOrderData = $request['purchaseOrderData'];
        if (isset($request["documentid"])) {
            $document_root = $_SERVER['DOCUMENT_ROOT'];
            $rest = substr($document_root, -1);
            if (ctype_alpha($rest)) {
                $document_root = $document_root.'/';
            }
            
            //Store Upload File location from config
            $targetdir = $this->config->item('document_dir');
            if(!$targetdir){
                $targetdir = $document_root.'infomaniacDocs/jobdocs';
            }
            //Check dir exist or not
            if (!is_dir($targetdir)) {
                //Create Dir if not exist
                mkdir($targetdir, 0755, TRUE);
            }
            
            
            $filelocation =  $this->config->item('jobrequest_dir');
            if(!$filelocation){
                $filelocation = $document_root.'infomaniacDocs/jrq';
 
            }
            $source_path = $filelocation.'/' . 'jrq_'.$purchaseOrderData["poref"].'.pdf';
            $newfile = $targetdir.'/' . $request["documentid"].'.pdf';
          
            @copy($source_path, $newfile);
            
            if(isset($request['emailData']) && isset($request['emailData'][1])){
                $doc = array();
                $doc['fname'] = $request["documentid"].'.pdf';
                $doc['dname'] = $request["documentid"].'.pdf';
                $doc['relpath'] = $targetdir;
                if(isset($request['emailData'][1]['docsA'])){
                    $request['emailData'][1]['docsA'][] = $doc; 
                }
                else{
                    $request['emailData'][1]['docsA'] = $doc; 
                }
                
            }
           
           
            
           
            
        }
          
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file CopyJobRequestDocumentChain.php */