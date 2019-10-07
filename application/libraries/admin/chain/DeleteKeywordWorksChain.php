<?php 
/**
 * Delete Keyword Works Libraries Class
 *
 * This is a Keyword Works class for delete Keyword Works
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractAdminChain.php');
require_once( __DIR__.'/../../LogClass.php');
/**
 * Delete Keyword Works Libraries Class
 *
 * This is a Keyword Works class for delete Keyword Works
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            Admin/chain
 * @filesource          DeleteKeywordWorksChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class DeleteKeywordWorksChain extends AbstractAdminChain
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
    * This function use for delete Keyword Works
      * 
    * @param array $request - the request is array of required parameter for delete Keyword Works
      * 
    * @return integer -  
    */
    public function handleRequest($request)
    {
        $logClass= new LogClass('jobtracker', 'DeleteKeywordWorksChain');
         
        $params = $request['params'];
 
        $this->db->where('id', $params['id']);
        $this->db->delete('se_keyword_subworks'); 
        
        $logClass->log('Delete Keyword Works Query : '. $this->db->last_query());
         
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }


    }

}

/* End of file DeleteKeywordWorksChain.php */