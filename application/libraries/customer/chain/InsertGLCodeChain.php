<?php 
/**
 * InsertGLCodeChain Libraries Class
 *
 * This is a GL Code Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertGLCodeChain Libraries Class
 *
 * This is a GL Code Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertGLCodeChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertGLCodeChain extends AbstractCustomerChain
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
    *  
    */
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }

     /**
    * This function use for create address record
      * 
    * @param array $request - the request is array of required parameter for creating address
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'InsertGLCodeChain');
        
        $insertGLCodeData = $request['insertGLCodeData'];
        
        $this->db->insert('customer_glchart', $insertGLCodeData);
        $id =  $this->db->insert_id();
        $request["glcodeid"] = $id;

        $logClass->log('Insert GL Code Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertGLCodeChain.php */