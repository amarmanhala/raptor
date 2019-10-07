<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Dummy include value, to demonstrate the parsing power of phpDocumentor
 */
include_once 'sample3.php';

/** 
 * Short description of file
 *  
 * LONG_DESCRIPTION_MULTILINE This file demonstrates the rich information that can be included in
 * in-code documentation through DocBlocks and tags.
 * @author DEVELOPER FULL NAME AND EMAIL ADDRESS<cellog@php.net>
 * @package CI 
 */
class SampleController extends CI_Controller {

    /**
     * A sample private variable, this can be hidden with the --parseprivate
     * option
     * @access private
     * @var integer|string
     */
    var $firstvar = 6;

    /**
    * A sample function docblock
    * @global string document the fact that this function uses $_myvar
    * @staticvar integer $staticvar this is actually what is returned
    * @param string $param1 name to declare
    * @param string $param2 value of the name
    * @return integer 
    */
    function firstFunc($param1, $param2 = 'optional')
    {
        static $staticvar = 7;
        global $_myvar;
        return $staticvar;
    }
    
    /**
     * Calls parent constructor, then increments {@link $firstvar}
     */
    function babyclass()
    {
        parent::myclass();
        $this->firstvar++;
    }
    
    /**
     * This always returns a myclass
     * @param ignored $paramie 
     * @return myclass 
     */
    function parentfunc($paramie)
    {
        return new myclass;
    }    


}
?>