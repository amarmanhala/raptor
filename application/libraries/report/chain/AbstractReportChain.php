<?php /**
 * Abstract Report chain Libraries Class
 *
 * This is a Abstract Report chain class for Report chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Report chain Libraries Class
 *
 * This is a Abstract Report chain class for Report chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Report/chain
 * @filesource          AbstractReportChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractReportChain extends MY_Model 
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

/* End of file AbstractReportChain.php */