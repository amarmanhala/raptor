<?php /**
 * Abstract Asset chain Libraries Class
 *
 * This is a Abstract Asset chain class for Asset chain system
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *Abstract Asset chain Libraries Class
 *
 * This is a Abstract Asset chain class for Asset chain system
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Asset/chain
 * @filesource          AbstractAssetChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
abstract class AbstractAssetChain extends MY_Model 
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

/* End of file AbstractAssetChain.php */