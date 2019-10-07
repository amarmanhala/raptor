<?php 
/**
 * Log Class
 *
 *  Description of Log
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Log Class
 *
 *  Description of Log
 *
 * @package		Tiger
 * @subpackage          Libraries
 * @category            General
 * @filesource          LogClass.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
 
class LogClass  {
    
    /**
    * Logged user id 
      * 
    * @var string
    *  
    */
    private $userId = '';
    
    /**
    * app Type
    * 
    * @var string
    *  
    */
    private $appType = '';
    
    /**
    * caller class Name 
    * 
    * @var string
    *  
    */
    private $callerName = '';
    
    /**
    * unique ID
      * 
    * @var string
    *  
    */
    private $uniqueId = '';
    
    /**
    * Class constructor
    *
    * @param string $appType
    * @param string $callerName
    * @param string $userId
    * @return void
    */
    function __construct($appType = "jobtracker", $callerName = '', $userId = '')
    {
          
 
        $this -> callerName = $callerName;
        $this -> appType = $appType;
        $this -> userId = $userId;
        $this -> uniqueId = uniqid();
    }   
    
    /**
    * Get Log Path Suffix
    *
    * @return string
    */
    private function getLogPathSuffix()
    {
        $suffix = "jobtracker";
         
        return $suffix;    
    }
    
    /**
    * this function for generate log file
    *
    * @param string $content
    * @return string
    */
    public function log($content) {

        try {

            $logPath =  $_SERVER['DOCUMENT_ROOT'] .'/raptor/logs';
			 
			
            if ($logPath == null || $logPath == '')
                return;

            //create a log directory, if it doesn't exist
            if (!file_exists($logPath)) {
                //echo $logPath;
                mkdir($logPath, 0777);
            }
            
            $suffix = $this->getLogPathSuffix();
            $logPath = $logPath . DIRECTORY_SEPARATOR .$suffix.DIRECTORY_SEPARATOR;//format("{0}{1}{2}{3}", $logPath, DIRECTORY_SEPARATOR, $suffix, DIRECTORY_SEPARATOR);

            //suffix. create a log directory, if it doesn't exist
            if (!file_exists($logPath)) {
                //echo $logPath;
                mkdir($logPath, 0777);
            }
            

            $currentDateTime = date('Y-m-d H:i:s');

            $fileName = $logPath . date('Y-m-d') . ".log";
            $contentStr = '';

            if (!file_exists($fileName)) {
                //create a log file
                $file = fopen($fileName, "wb");
                fwrite($file, $currentDateTime . " - Log File Created.\r\n");
                fclose($file);
            }

            if (!is_array($content) && !trim($content) == '') {
                $contentStr =PHP_EOL .$currentDateTime.' - '. $this->appType .' - '. $this->callerName.' - '.$this->userId.' - ('.$this -> uniqueId.') '.PHP_EOL;// format("{0} - {1} - {2} - {3} - ({4})", $currentDateTime, $this -> appType, $this -> callerName, $this -> userId, $this -> uniqueId).PHP_EOL;
            }

            if (is_array($content)) {

                $contentStr = $contentStr . " - " . "Params\r\n";

                foreach ($content as $x => $x_value) {
                    if (is_array($x_value)) {
                         $contentStr = $contentStr . "\r\n";
                        $contentStr = $contentStr . " - " .$x.  "Params\r\n";

                        foreach ($x_value as $x1 => $x_value1) {
                            if (is_array($x_value1)) {
                                   $contentStr = $contentStr . "\r\n";
                                   $contentStr = $contentStr . " - " .$x.  "Params\r\n";

                                   foreach ($x_value1 as $x2 => $x_value2) {
                                       $contentStr = $contentStr . "\t\t" . $x2 . " => " . $x_value2;

                                       $contentStr = $contentStr . "\r\n";
                                   }

                            }
                            else{
                                $contentStr = $contentStr . "\t\t" . $x1 . " => " . $x_value1;

                                $contentStr = $contentStr . "\r\n";
                            }
                            
                        }

                    } else {
                        $contentStr = $contentStr . "\t\t" . $x . " => " . $x_value;
                    }
                     
                    $contentStr = $contentStr . "\r\n";
                }

            } else {
                if (!trim($content) == '') {
                    $contentStr = $contentStr.' - '.$content.PHP_EOL;//sprintf("%1 - %2 \r\n", $contentStr, $content);// format("{0} - {1} \r\n", $contentStr, $content);
                }
            }

            file_put_contents($fileName, $contentStr, FILE_APPEND);

        } catch(Exception $ex) {
            //echo $ex;
            //nothing to do
        }

    }
}
