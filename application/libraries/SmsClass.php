<?php
/**
 * Sms Library Class
 *
 * This is a Sms class for sending Sms
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Bcrypt Library Class
 *
 * This is a Sms class for sending Sms
 *
 * @package		Tiger
 * @subpackage          Library
 * @category            General
 * @filesource          SmsClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class SmsClass  {
    
    /**
    * $ci;
    *
    * @var $ci;
    */
    var $ci;
    
    /**
    * Class constructor
    *
    * @return  void
    */
    function __construct()
    {
          $this->ci = & get_instance();  
          $this->ci->load->config('raptor_config');
    }   
    
    /**
    * send sms according to provide settings
    *
    * @param string $destination destination mobile number
    * @param string $source  source mobile number
    * @param string $text sms message
    * @param string $ref 
    * @return  array
    */
    public function sendSMS($destination, $source, $text, $ref) {
            
        $username = $this->ci->config->item('service_username');
        $password = $this->ci->config->item('service_password');
        //$destination = '0400000000'; //Multiple numbers can be entered, separated by a comma
        //$source   = 'MyCompany';
        //$text = 'This is our test message.';
        //$ref = 'abc123';

        $content =  'username = '.rawurlencode($username).
                    '&password = '.rawurlencode($password).
                    '&to= '.rawurlencode($destination).
                    '&from= '.rawurlencode($source).
                    '&message = '.rawurlencode($text).
                    '&ref= '.rawurlencode($ref);

        $ch = curl_init($this->ci->config->item('sms_service_url'));
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec ($ch);
        curl_close ($ch);

        $response_lines = explode("\n", $output);
        $response = '';
        $status = '';
         foreach ( $response_lines as $data_line) {
            $message_data = '';
            $message_data = explode(':', $data_line);
            $status = $message_data[0];
            if ($message_data[0] == "OK") {
                $response =  "The message to ".$message_data[1]." was successful, with reference ".$message_data[2]."\n";
            } elseif ( $message_data[0] == "BAD" ) {
                $response =  "The message to ".$message_data[1]." was NOT successful. Reason: ".$message_data[2]."\n";
            } elseif ( $message_data[0] == "ERROR" ) {
                $response = "There was an error with this request. Reason: ".$message_data[1]."\n";
            }
        }
        return array('status' => $status, 'response' => $response);
    }
}
