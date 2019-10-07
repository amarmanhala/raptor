<?php /**
 * Abstract Invoice chain Libraries Class
 *
 * This is a Abstract Invoice chain class for Invoice chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Invoice chain Libraries Class
 *
 * This is a Abstract Invoice chain class for Invoice chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            invoice/chain
 * @filesource          AbstractInvoiceChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractInvoiceChain extends MY_Model {

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

/* End of file AbstractInvoiceChain.php */