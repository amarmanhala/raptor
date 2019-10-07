<?php /**
 * Abstract PurchaseOrder chain Libraries Class
 *
 * This is a Abstract PurchaseOrder chain class for PurchaseOrder chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract PurchaseOrder chain Libraries Class
 *
 * This is a Abstract PurchaseOrder chain class for PurchaseOrder chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            PurchaseOrder/chain
 * @filesource          AbstractPurchaseOrderChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractPurchaseOrderChain extends MY_Model {

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

/* End of file AbstractPurchaseOrderChain.php */