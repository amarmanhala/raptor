<?php 
/**
 * auth Controller Class
 *
 * This is a auth controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller Class
 *
 * This is a auth controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            auth
 * @filesource          auth.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Auth extends CI_Controller {

    protected $layouts = 'login_layout';


    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->helper('language');
        $this->load->library('shared/SharedClass');
        $this->config->load('raptor_config');
    }
 
    /**
     * redirect if needed, otherwise display the user list
     */
    function index()
    {

        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        else
        {
            if($this->session->userdata('raptor_israptoradmin')==1)
            {
                redirect('admin');
            }
            else{
                redirect('dashboard');
            }
            
        }
    }

    /**
     * log the user in
     */
    function adminlogin($originurl = NULL)
    { 
        if ($this->ion_auth->logged_in())
        {
            //redirect('dashboard');
        }
        if ($this->ion_auth->login_remembered_user())
        {
            redirect('admin');
        }
        $this->data['title'] = "Login";
        $this->data['remember_me'] =get_cookie($this->config->item('identity_cookie_name', 'ion_auth'));
    
        
        //validate form input
        $this->form_validation->set_rules('email', 'email', 'valid_email|required');
        $this->form_validation->set_rules('password', 'Password', 'required');


        if ($this->form_validation->run() == true)
        {
            //redirect('dashboard');
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember_me');
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
            {
                //if the login is successful
                //redirect them back to the home page
                
                $loggeduser = $this->ion_auth->user()->row(); 
                if($loggeduser->israptoradmin == 1){
                    $this->session->set_flashdata('message', $this->ion_auth->messages()); 
                    redirect('admin');
                }
                else{
                    $logout = $this->ion_auth->logout();
                    //redirect them back to the login page
                    $this->session->set_flashdata('error', "unable to login.");
                    redirect('auth/adminlogin'); //use redirects instead of loading views for compatibility with MY_Controller libraries
                }
                
            }
            else
            {
                //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('auth/adminlogin'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {

            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->data['error'] = (validation_errors()) ? validation_errors() : '';

            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Login')
                ->set_layout($this->layouts)
                ->set('page_title', 'Login')
                ->build('auth/login', $this->data);

        }
    }
    
    
    /**
     * log the user in
     */
    function login($originurl = NULL)
    { 
        if ($this->ion_auth->logged_in())
        {
            //redirect('dashboard');
        }
        if ($this->ion_auth->login_remembered_user())
        {
            if($originurl != NULL && $originurl != ''){
                redirect(urldecode(urldecode($originurl)));
            }
            else{
                redirect('dashboard');
            }
        }
        $this->load->library('admin/AdminClass');
        $this->data['title'] = "Login";
        $this->data['remember_me'] = get_cookie($this->config->item('identity_cookie_name', 'ion_auth'));
        $this->data['site_module'] = $this->adminclass->getSiteModule();
        
        //validate form input
        $this->form_validation->set_rules('email', 'email', 'valid_email|required');
        $this->form_validation->set_rules('password', 'Password', 'required');


        if ($this->form_validation->run() == true)
        {
            //redirect('dashboard');
            //check to see if the user is logging in
            //check for "remember me"
            $password = $this->input->post('password');
            $remember = (bool) $this->input->post('remember_me');
            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
            {
                $raptor_welcome_password = $this->config->item('raptor_welcome_password');
             
                //if the login is successful
                //redirect them back to the home page
                $loggeduser = $this->ion_auth->user()->row(); 
                $this->session->set_flashdata('message', $this->ion_auth->messages()); 
                if($loggeduser->israptoradmin == 1){
                    
                    redirect('admin');
                }
                else{ 
                    $this->session->set_flashdata('showannouncement', TRUE);
                    
                    $ContactRules= $this->sharedclass->getCustomerRules($loggeduser->customerid, $this->session->userdata('raptor_role'));
                    $allow_simple_password = FALSE;
                    if (isset($ContactRules["allow_simple_password"]) && $ContactRules["allow_simple_password"] == 1){
                        $allow_simple_password = TRUE;
                    }
                    if($raptor_welcome_password == $password && $allow_simple_password == FALSE){
                       
                        
                        $this->session->set_flashdata('message', "Please update your password.");
                        redirect('settings/changepassword');
                    }
                    else{
                        if($originurl != NULL && $originurl != ''){
                            redirect(urldecode(urldecode($originurl)));
                        }
                        else{
                            redirect('dashboard');
                        }
                    }
                }

            }
            else
            {
                //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect('auth/login'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {

            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->data['error'] = (validation_errors()) ? validation_errors() : '';

            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Login')
                ->set_layout($this->layouts)
                ->set('page_title', 'Login')
                ->build('auth/login', $this->data);

        }
    }

     
    /**
     * log the user out
     */
    function logout()
    {
        $this->data['title'] = "Logout";

        //log the user out
        $logout = $this->ion_auth->logout();
        $this->session->unset_userdata('dcfm_c_l');
        //redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());


        redirect('auth/login', 'refresh');
    }
 
    /**
     * forgot password
     */
    function forgotpassword()
    {  
        //setting validation rules by checking wheather identity is username or email
        $this->form_validation->set_rules('email', "Email", 'required|valid_email');	

        if ($this->form_validation->run() == false)
        {

                $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Forgot Password ')
        ->set_layout($this->layouts)
        ->set('page_title', 'Forgot Password')
        ->build('auth/forgot_password');
        }
        else
        {
            // get identity from username or email
             $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();

            if(empty($identity)) {
                $this->ion_auth->set_message('forgot_password_email_not_found');

                $this->session->set_flashdata('error', $this->ion_auth->messages());
                redirect("auth/forgotpassword", 'refresh');
            }

            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten)
            {
                //if there were no errors
                $this->session->set_flashdata('success', $this->ion_auth->messages());
                redirect("auth/forgotpassword", 'refresh'); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect("auth/forgotpassword", 'refresh');
            }

        }
    }

    
    /**
     * reset password - final step for forgotten password
     * @param string $code
     */
    public function resetpassword($code=null)
    {

        if (!$code || $code==null)
        {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user)
        {
            //if the code is valid then display the password reset form
            $this->form_validation->set_rules('email', "Email", 'required|valid_email');	
            $this->form_validation->set_rules('password', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|callback_valid_pass');
            $this->form_validation->set_rules('confirm_password', "Confirm Password", 'required|matches[password]');

            if ($this->form_validation->run() == false)
            {
                //display the form

                //set the flash data error message if there is one
                $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;
                $this->data['user_id'] = $user->id;

                $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Reset Password ')
                    ->set_layout($this->layouts)
                    ->set('page_title', 'Reset Password')
                    ->build('auth/reset_password', $this->data);
            }
            else
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
                {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    show_error($this->lang->line('error_csrf'));

                }
                else
                {
                    if ($user->email != $this->input->post('email'))
                    {

                        $this->session->set_flashdata('error', "email id is not match with reset url");
                        redirect('auth/resetpassword/' . $code, 'refresh');

                    }
                    else
                    {


                        // finally change the password
                        $identity = $user->{$this->config->item('identity', 'ion_auth')};
                        $change = $this->ion_auth->reset_password($identity, $this->input->post('password'));

                        if ($change)
                        {
                            //if the password was successfully changed
                            $this->session->set_flashdata('success', $this->ion_auth->messages());
                            redirect('auth/login');
                        }
                        else
                        {
                            $this->session->set_flashdata('error', $this->ion_auth->errors());
                            redirect('auth/resetpassword/' . $code, 'refresh');
                        }
                    }
                }
            }
        }
        else
        {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('error', $this->ion_auth->errors());
            redirect("auth/forgotpassword", 'refresh');
        }
    }
 
    /**
     * validate password
     * 
     * @param boolean $candidate
     * @return boolean
     */
    function valid_pass($candidate) 
    {
        $r1='/[A-Z]/';  //Uppercase
        $r2='/[a-z]/';  //lowercase
        $r3='/[!@#$%&*()^,._;:-]/';  // whatever you mean by 'special char'
        $r4='/[0-9]/';  //numbers
	 
        if(preg_match_all($r1, $candidate, $o)<1)
        {
                $this->form_validation->set_message('valid_pass', 'Password must contain at least 1 uppercase characters(A-Z).');
                return false;
        }
        else if(preg_match_all($r2, $candidate, $o)<1)
        {
                $this->form_validation->set_message('valid_pass', 'Password must contain at least 1 lowercase characters(a-z).');
                return false;
        }
        else if(preg_match_all($r3, $candidate, $o)<1)
        {
                $this->form_validation->set_message('valid_pass', 'Password must contain at least 1 special characters(!@#$%&*()^,._;:-).');
                return false;
        }
        else if(preg_match_all($r4, $candidate, $o)<1)
        {
                $this->form_validation->set_message('valid_pass', 'Password must contain at least 1 number(0-9).');
                return false;
        }
         
        else
        {
            return TRUE;
        }
    }
	
    /**
     * check_email
     * @param string $email
     * @return boolean
    */
    public function check_email($email)
    {

        if (empty($email)){
            return FALSE;
        }
        if($this->ion_auth->email_check($email)){
            $this->form_validation->set_message('check_email', 'Email ID Already Register with us');
            return false;
        }
        else
        {
            return TRUE;
        }

    }
  
    /**
     * create a new user
     * 
     * @return void
     */
    function register()
    {
        $this->data['title'] = "Create User";
        $tables = $this->config->item('tables','ion_auth');

        //validate form input
        $this->form_validation->set_rules('firstname', "Name", 'required|trim');
        $this->form_validation->set_rules('email', "Email", 'required|valid_email|callback_check_email');	
        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|callback_valid_pass');
        $this->form_validation->set_rules('confirm_password', "", 'required|matches[password]');
        $this->form_validation->set_rules('agree', "", 'required');
        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->input->post('email'));
            $email   = strtolower($this->input->post('email'));
            $password = $this->input->post('password');

            $additional_data = array(
                    'firstname' => $this->input->post('firstname'),
                    'email' => $this->input->post('email')
            );
            if ($this->ion_auth->register($username, $password, $email, $additional_data)){

                $this->session->set_flashdata('success', $this->ion_auth->messages());
                redirect("auth/register", 'refresh');
            }else {
                $this->session->set_flashdata('error', $this->ion_auth->messages());
                redirect("auth/register", 'refresh'); 
            }

        }
        else{
            $this->template->title(trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE) .' | Register ')
                ->set_layout($this->layouts)
                ->set('page_title', 'Register')
                ->build('auth/register', $this->data);
        }

    }

 
    /**
     * activate the user
     * @param type $id
     * @param type $code
     */
    function activate($id, $code=false)
    {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        }else if ($this->ion_auth->is_admin())
        {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgotpassword", 'refresh');
        }
    }
	
    /**
     * 
     * @return array
     */
    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key  = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    /**
     * 
     * @return boolean
     */
    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
                return TRUE;
        }
        else
        {
                return FALSE;
        }
    }
 
    //for Password encryption
    function pass_encrypt()
    {
        $password=trim($this->input->get_post('password'));
        $secret=trim($this->input->get_post('secret'));

        if($secret!='$2y$08$lQK1VCzjYPxxbzMY5JA1lO0mDBWJX2HxSv1ZS')
        {
            $data=   array('error'=>'true',
                            'message'=>"secret Code not match");
            echo json_encode($data);
            return;

        }
        if($password=="")
        {
            $data=   array('error'=>'true',
                            'message'=>"password not match");
            echo json_encode($data);
            return;

        }

        $data=   array('error'=>'false',
                    'password'=>$password,
                'hashedpassword'=>$this->ion_auth->hash_password($password));
        echo json_encode($data);


    }    
          
}
