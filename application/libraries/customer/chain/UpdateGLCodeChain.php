<?php 
/**
 * UpdateGLCodeChain Libraries Class
 *
 * This is a UpdateGLCodeChain class for update customer
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateGLCodeChain Libraries Class
 *
 * This is a UpdateGLCodeChain class for update customer
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          UpdateGLCodeChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class UpdateGLCodeChain extends AbstractCustomerChain{
 
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
        $this->successor= $nextService;
    }
 
    /**
    * This function use for update customer
     * 
    * @param array $request - the request is array of required parameter for update customer
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateGLCodeChain');
         
        $params = $request['params'];

        $updateData = $request['updateGLCodeData']; 
      
        $this->db->where('id', $params['glcodeid']);
        $this->db->update('customer_glchart', $updateData); 
        
        $logClass->log('Update customer_glcode Query : '. $this->db->last_query());
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
        
         //it should be at the last part of chain
        $this -> returnValue = $request;
    }
}


/* End of file UpdateGLCodeChain.php */