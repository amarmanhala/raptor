<?php /**
 * Abstract Budget chain Libraries Class
 *
 * This is a Abstract Budget chain class for Budget chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Budget chain Libraries Class
 *
 * This is a Abstract Budget chain class for Budget chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Budget/chain
 * @filesource          AbstractBudgetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractBudgetChain extends MY_Model 
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

/* End of file AbstractBudgetChain.php */