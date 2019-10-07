<?php 
/**
 * InsertPurchaseOrderChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for create Purchase order Record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractPurchaseOrderChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertPurchaseOrderChain Libraries Class
 *
 *  This is a PurchaseOrder Chain class for create Purchase order Record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          InsertPurchaseOrderChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertPurchaseOrderChain extends AbstractPurchaseOrderChain
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
     * 
    * @param Class $nextService - class for execute  
    */
    public function setSuccessor($nextService)
    {
        $this->successor=$nextService;
    }
 
    /**
    * This function use for create new Purchase Order
     * 
    * @param array $request - the request is array of required parameter for creating job Request
     * 
    * @return array
    */
    public function handleRequest($request)
    { 
        $logClass= new LogClass('jobtracker', 'InsertPurchaseOrderChain');
         
       	$insertPOData = $request['purchaseOrderData'];
        
        $this->db->insert('purchaseorders', $insertPOData);
        $request["poref"] = $this->db->insert_id();
        $request['purchaseOrderData']["poref"] = $request["poref"];
        
        if( isset($request['etpJobData'])){
            $request['etpJobData']['ownerporef'] =  $request['poref']; 
        }
        
        if( isset($request['documentData'])){
            $request['documentData']['documentdesc'] = 'Job Request ' .$request['poref'];
            //$request['documentData']['xrefid'] = $request['poref'];
            $request['documentData']['docname'] ='Jrq_' .$request['poref'].'.pdf';
        }
        
        if( isset($request['requiredJobDocuments'])){
            foreach ($request['requiredJobDocuments'] as $key => $value) {
                $request['requiredJobDocuments'][$key]['poref'] = $request['poref'];
            }
         
        }
        
        if( isset($request['emailData'])){
            foreach ($request['emailData'] as $key => $value) {
                $message = $request['emailData'][$key]['message'];
                $message =str_replace('<poref>', $request['poref'], $message);
                //$message =str_replace('poref', $request['poref'], $message);
                $request['emailData'][$key]['message'] = $message;
            }
         
        }
        
        
        $logClass->log('Create PO Query : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;
     

    }
	
}


/* End of file InsertPurchaseOrderChain.php */