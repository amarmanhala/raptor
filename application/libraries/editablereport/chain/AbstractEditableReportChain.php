<?php /**
 * Abstract Job chain Libraries Class
 *
 * This is a Abstract Job chain class for Job chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Job chain Libraries Class
 *
 * This is a Abstract Job chain class for Job chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Job/chain
 * @filesource          AbstractJobChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractJobChain extends MY_Model 
{

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

/* End of file AbstractJobChain.php */