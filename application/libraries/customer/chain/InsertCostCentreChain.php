<?php 
/**
 * InsertCostCentreChain Libraries Class
 *
 * This is a Cost Centre Chain class for create a new record
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractCustomerChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertCostCentreChain Libraries Class
 *
 * This is a Cost Centre Chain class for create a new record
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Customer/chain
 * @filesource          InsertCostCentreChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertCostCentreChain extends AbstractCustomerChain
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
        $logClass= new LogClass('jobtracker', 'InsertCostCentreChain');
        
        $insertGLCodeData = $request['insertCostCentreData'];
        
        $this->db->insert('customer_costcentre', $insertGLCodeData);
        $id =  $this->db->insert_id();
        $request["costcentreid"] = $id;

        $logClass->log('Insert CostCentre Query : '. $this->db->last_query());

        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

         //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file InsertCostCentreChain.php */