<?php 
/**
 * Delete Asset Chain Libraries Class
 *
 * This is a Asset Chain class for delete in Asset
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAssetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Asset Chain Libraries Class
 *
 * This is a Asset Chain class for delete in Asset
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset/chain
 * @filesource          DeleteAssetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteAssetChain extends AbstractAssetChain
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
    * This function use for delete Asset
      * 
    * @param array $request - the request is array of required parameter for delete Asset
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteAssetChain');
         
        $params = $request['params'];
       
        $this->db->where('assetid', $params['assetid']);
        $this->db->delete('asset'); 
        
        $logClass->log('Delete Asset Query : '. $this->db->last_query());
      
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;
    }

}

/* End of file DeleteAssetChain.php */