<?php /**
 * Abstract Admin chain Libraries Class
 *
 * This is a Abstract Admin chain class for Admin chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Admin chain Libraries Class
 *
 * This is a Abstract Admin chain class for Admin chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Admin/chain
 * @filesource          AbstractAdminChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractAdminChain extends MY_Model {

    /**
     * return value from chain process
     * 
     * @var array Store return data array
     */
    public $returnValue = array();

    /**
     * It handles the request from chain class
     * 
     * @param array $request
     */
    abstract public function handleRequest($request);

    /**
     * It sets the next part of chain
     * 
     * @param class $nextService
     */
    abstract public function setSuccessor($nextService);

}

/* End of file AbstractAdminChain.php */