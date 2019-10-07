<?php 
/**
 * Abstract Shared chain Libraries Class
 *
 * This is a Abstract Shared chain class for chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Abstract Shared chain Libraries Class
 *
 * This is a Abstract Shared chain class for chain system
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            shared/chain
 * @filesource          AbstractSharedChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
abstract class AbstractSharedChain extends MY_Model {

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

/* End of file AbstractSharedChain.php */