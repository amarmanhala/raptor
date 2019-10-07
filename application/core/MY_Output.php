<?php 
/**
 * MY_Output Class
 *
 * This is a MY_Output
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 * MY_Output Class
 *
 * This is a MY_Output
 *
 * @package		Raptor
 * @subpackage          Output
 * @category            Output
 * @filesource          MY_Output.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 

class MY_Output extends CI_Output {

    /**
     * nocache
     */
    function nocache()
    {
        $this->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        $this->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age = 0');
        $this->set_header('Cache-Control: post-check= 0, pre-check= 0', FALSE);
        $this->set_header('Pragma: no-cache');
    }

}

/* End of File */