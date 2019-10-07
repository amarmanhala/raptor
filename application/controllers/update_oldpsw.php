<?php 
/**
 * update_oldpsw Controller Class
 *
 * This is a MY model class for update old password to ion auth encrypted password
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * update_oldpsw Controller Class
 *
 * This is a MY model class for update old password to ion auth encrypted password
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            update_oldpsw
 * @filesource          update_oldpsw.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class update_oldpsw extends CI_Controller {

    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
       

    }

    /**
    * This function use for update password
    * 
    * @return void 
    */
    public function index()
    {

        $this->db->select('*'); 
        $this->db->from('contact');
        $contactsdata = $this->db->get()->result_array();
      
        $totalr=0;
        foreach ($contactsdata as $key => $value) {

            $identity = $value['contactid'];
            $password='password';
            if($value['password']!=""){
                $password=$value['password'];
            }
            $change = $this->ion_auth->update_password($identity, $password);

            if ($change)
            {
                $totalr=$totalr+1;
            }
        }
        echo "Total ". $totalr ." contact psw updated";

    }
     
}