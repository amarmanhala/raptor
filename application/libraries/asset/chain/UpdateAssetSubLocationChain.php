<?php 
/**
 * UpdateAssetSubLocationChain Libraries Class
 *
 * This is a Asset Chain Class use for update Asset
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('AbstractAssetChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * UpdateAssetSubLocationChain Libraries Class
 *
 * This is a Asset Chain Class use for update Asset
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset/chain
 * @filesource          UpdateAssetSubLocationChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
  
class UpdateAssetSubLocationChain extends AbstractAssetChain{
 
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
    * This function use for update Asset
     * 
    * @param array $request - the request is array of required parameter for update Asset
     * 
    * @return array
    */
    public function handleRequest($request)
    {
            
        $logClass= new LogClass('jobtracker', 'UpdateAssetSubLocationChain');
 
        $params = $request['params'];
        $assetData = $request['updateSubLocationData']; 
         
        $this->db->where('asset_sublocation_id', $params['asset_sublocation_id']);
        $this->db->update('asset_sublocation', $assetData); 
        
        $logClass->log('Update Asset Location Query : '. $this->db->last_query());
       
       
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest ($request);
        }

        //it should be at the last part of chain
        $this -> returnValue = $request;

    }
}


/* End of file UpdateAssetSubLocationChain.php */