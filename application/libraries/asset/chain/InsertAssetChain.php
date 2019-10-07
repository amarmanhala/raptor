<?php 
/**
 * InsertAssetChain Libraries Class
 *
 * This is a Asset Chain Class use for create new Asset
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractAssetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * InsertAssetChain Libraries Class
 *
 * This is a Asset Chain Class use for create new Asset
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset/chain
 * @filesource          InsertAssetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class InsertAssetChain extends AbstractAssetChain
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
        $this->successor = $nextService;
    }
 
    /**
    * This function use for create new Asset
     * 
    * @param array $request - the request is array of required parameter for creating Asset
    * @return array
    */
    public function handleRequest($request)
    {
          
        $logClass= new LogClass('jobtracker', 'InsertAssetChain');
        $params = $request['params'];
        $assetData = $request['assetData']; 
       
        $this->db->insert('asset', $assetData);
        $id =  $this->db->insert_id();
        
        $request["assetid"] = $id;
        $logClass->log('Insert asset Query : '. $this->db->last_query());
        
        
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }
         //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file InsertAssetChain.php */