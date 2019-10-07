<?php /**
 * Abstract Quote chain Libraries Class
 *
 * This is a Abstract Quote chain class for Quote chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Quote chain Libraries Class
 *
 * This is a Abstract Quote chain class for Quote chain system
 *
 * @package		RAPTOR
 * @subpackage          Libraries
 * @category            Quote/chain
 * @filesource          AbstractQuoteChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractQuoteChain extends MY_Model {

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

/* End of file AbstractQuoteChain.php */