<?php 
/**
 * Success Library Class
 *
 * This is a bridge class between controller and restful api
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Success Library Class
 *
 * This is a bridge class between controller and restful api
 *
 * @package		Tiger
 * @subpackage          Library
 * @category            General
 * @filesource          Success.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class SuccessClass
{
    /**
    * success status for response
    *
    * @var boolean
    **/
    public $success = false;
    
    /**
    * message for response
    *
    * @var string
    **/
    public $message = "";
    
    /**
    * code for response
    *
    * @var string
    **/
    public $code = 0;
    
    /**
    * data for response
    *
    * @var array
    **/
    public $data = array();
    
    /**
    * total count data for response
    *
    * @var integer
    **/
    public $total = 0;
    
    /**
    * datetime for response
    *
    * @var string
    **/
    public $datetime = "";
   
    /**
    * partial for response
    *
    * @var array
    **/
    public $partial = array();


    /**
    * Class constructor
    *
    * @return  void
    */
    private function __construct() {
    }

    /**
     * Use this function in order to initialize an instance. First param is mandatory while others are optional.
     * 
     * @param bool - $isSuccess
     * @param string - $message
     * @param integer - $code
     * @throws $exception
     * @return SuccessClass - an instance of class
     */
    public static function initialize( $isSuccess, $message = "", $code = 0 ) {

        if( !isset($isSuccess) )
            throw new exception("isSuccess object must be defined.");

        //initialize an instance within class
        $instance = new self();
        $instance -> setSuccess( $isSuccess );

        //if message is not null
        if( isset($message) )
            $instance -> setMessage( $message );

        //if code is not null
        if( isset($code) )
            $instance -> setCode( $code );

        $instance->setDatetime( strtotime(date('m/d/Y H:i:s', time())) );


        //return the instance to a caller
        return $instance;
    }

    /**
     * It returns the message variable
     *  
     * @return string - message value
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * It sets the message variable
     * 
     * @param string $message - message to be displayed
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * It sets the unix datetime
     * 
     * @param $datetime - datetime to be set
     */
    public function setDateTime($datetime)
    {
        $this->datetime = $datetime;
    }


    /**
     * It returns the error or success code
     *  
     * @return integer - code value
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * It sets the error or success code
     * 
     * @param $code - code to be sent
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
 	
    /**
     * It returns the total row count or data row count
     *  
     * @return integer - total value
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * It sets the total row count 
     * 
     * @param $total - total value
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

	

    /**
     * It returns the generic data variable
     *  
     * @return string - generic data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * It sets the generic data variable
     * 
     * @param $data - $data to be sent
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
    * It sets the generic Partial
    * 
    * @param $partial - $partial to be sent
    */
    public function setPartial($partial)
    {
        $this->partial = $partial;
    }	
	
    /**
     * It returns the success status of instance
     *  
     * @return does action successful
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * It sets the success status of instance
     * 
     * @param bool - $isSuccess
     */
    public function setSuccess($isSuccess)
    {
        $this->success = $isSuccess;

        if( $this-> success )
            $this->code = self::$CODE_SUCCESSFUL;
    }
 
    /**
    * Generic successful code
    *
    * @var integer
    **/
    public static $CODE_SUCCESSFUL = 200;
 
    /**
    * Custom Error Codes for required param is null
    *
    * @var integer
    **/
    public static $CODE_MANDATORY_PARAM_NULL = 300;
    
    /**
    * Custom Error Codes for userid or psw is null
    *
    * @var integer
    **/
    public static $CODE_USERID_PASS_NOT_AUTH = 350;
    
    /**
    * Custom Error Codes for token is not valid 
    *
    * @var integer
    **/
    public static $CODE_TOKEN_PASS_NOT_VALID = 351;
     
    /**
    * When an unexpected exception occurs
    *
    * @var integer
    **/
    public static $CODE_EXCEPTION_OCCURED = 900;

}
