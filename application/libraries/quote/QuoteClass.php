<?php 
/**
 * Quote Libraries Class
 *
 * This is a Quote class for Quote Opration 
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(__DIR__.'/../../helpers/custom_helper.php');
require_once( __DIR__.'/../LogClass.php');
require_once( __DIR__.'/../shared/SharedClass.php');

/**
 * Quote Libraries Class
 *
 * This is a Quote class for Quote Opration 
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            Quote
 * @filesource          QuoteClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class QuoteClass extends MY_Model{

   /**
    * Log class 
    * 
    * @var class
    */
    private $LogClass;
    
    /**
    * Shared class 
    * 
    * @var class
    */
    private $sharedClass;
    
    /**
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
        parent::__construct();
        $this->LogClass= new LogClass('jobtracker', 'QuoteClass');
        $this->sharedClass = new SharedClass();
        
    }
    
}


/* End of file QuoteClass.php */