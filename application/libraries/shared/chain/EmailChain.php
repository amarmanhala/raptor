<?php 
/**
 * Email Chain  Chain Libraries Class
 *
 * This is a Email Chain class for create a new record in maillog table
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('AbstractSharedChain.php');
require_once( __DIR__.'/../../LogClass.php');
//include_once ($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/maillog.class.php");
/**
 * Email  Chain Libraries Class
 *
 * This is a Email Chain class for create a new record in maillog table
 *
 * @package		Raptor
 * @subpackage          Libraries
 * @category            shared/chain
 * @filesource          EmailChain.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */

class EmailChain extends AbstractSharedChain
{
    private $successor;

     /**
    * @desc This function set Successor for next Service class which one execute after the handleRequest
    * @param Class $nextService - class for execute  
    *  
    */
    public function setSuccessor($nextService)
    {
        $this->successor= $nextService;
    }

     /**
    * @desc This function use for new record maillog request
    * @param array $request - the request is array of required parameter for creating Job Note
    * @return integer -  
    */
    public function handleRequest($request)
    { 
        
        $emailParams = $request['emailData'];
        $userdata = $request['userData'];
        
        //$mail = new maillog(AppType::JobTracker, $userdata['email']);
 
        $origin = "ClientPortal";
        foreach ($emailParams as $key => $value) {
            
            
            $recipsA = array();
            $ccsA = array();
            $docsA = array();
 
            if (isset($value['recipient'])) {
                if (is_array($value['recipient'])) {
                    $recipsA= $value['recipient'];
                }
                else{
                    $recipsA[] = $value['recipient'];
                }
            }

            if (isset($value['cc'])) {
                if (is_array($value['cc'])) {
                    $ccsA= $value['cc'];
                }
                else{
                    $ccsA[] = $value['cc'];
                }
            }


            $subject = $value['subject'];
            $message = $value['message'];
            if (isset($value['docsA'])) {
                $docsA= $value['docsA'];
            }
            $replyto = 'dcfm@dcfm.com.au';
            if (isset($value['replyto'])) {
                $replyto = $value['replyto'];
            }
            $sender = 'dcfm@dcfm.com.au';
            if (isset($value['sender'])) {
                $sender = $value['sender'];
            }
 
           
            if(implode(';', $recipsA)!=''){
                $mailLogData =array(
                    'datein'        => date("Y-m-d H:i:s"),
                    'userid'        => $userdata['email'],
                    'recip'         => implode(';', $recipsA),
                    'ccs'           => implode(';', $ccsA),
                    'customerid'    => $userdata['customerid'],  
                    'origin'        => $origin,
                    'subject'       => $subject,
                    'message'       => $message, 
                    'docs'          => serialize($docsA),
                    'replyto'       => $replyto,
                    'sender'        => $sender,
                    'xrefid'        => 0
                );
          
                //ANK Prevent blank subject line emails
                if($subject != '' && $mailLogData['recip'] != '') {
                    $this->db->insert('maillog', $mailLogData);
                    $request["maillogid"] = $this->db->insert_id();
                }
            }
            
         
//            $mid = $mail->queueMail($recipsA, $ccsA, $userdata['customerid'], $origin, $subject, $message, $docsA, $replyto);
//                    
//            $request["maillogid"] =  $mid;
        }
        
        
 
        if ($this->successor != NULL)
        {
            $this->successor->handleRequest($request);
        }

          //it should be at the last part of chain
        $this -> returnValue = $request;

    }

}

/* End of file EmailChain.php */