<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * AbstractScheduleChain Class
 *
 * @package     ETP
 * @subpackage  Schedule
 */
abstract class AbstractScheduleChain extends MY_Model {

    public $returnValue = array();

    /**
     * It handles the request from chain class
     */
    abstract public function handleRequest($request);

    /**
     * It sets the next part of chain
     */
    abstract public function setSuccessor($nextService);

}

/* End of file AbstractScheduleChain.php */