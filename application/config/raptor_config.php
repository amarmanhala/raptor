<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$document_root = $_SERVER['DOCUMENT_ROOT'];
$rest = substr($document_root, -1);
if (ctype_alpha($rest)) {
    $document_root = $document_root.'/';
}

$config['userphotos_dir'] = $document_root.'infomaniacDocs/userphotos/';
$config['userphotos_path'] = base_url().'../infomaniacDocs/userphotos/';

$config['branding_dir'] = $document_root.'etpdocs/branding/';
$config['branding_path'] = base_url().'../etpdocs/branding/';

$config['invoicedir'] = $document_root.'infomaniacDocs/invoices/';
$config['invoicepath'] = base_url().'../infomaniacDocs/invoices/';

$config['document_dir'] = $document_root.'infomaniacDocs/jobdocs/';
$config['document_path'] = base_url().'../infomaniacDocs/jobdocs/';

$config['job_report_dir'] = $document_root.'infomaniacDocs/reports/';
$config['job_report_path'] = base_url().'../infomaniacDocs/reports/';
 

$config['quote_dir'] = $document_root.'infomaniacDocs/quotes/';
$config['quote_path'] = base_url().'../infomaniacDocs/quotes/';

$config['variation_dir'] = $document_root.'infomaniacDocs/variation/';
$config['variation_path'] = base_url().'../infomaniacDocs/variation/';


$config['report_dir'] = $document_root.'infomaniacDocs/variation/';
$config['report_path'] = base_url().'../infomaniacDocs/variation/';

$config['jobrequest_dir'] = $document_root.'infomaniacDocs/jrq/';
$config['jobrequest_path'] = base_url().'../infomaniacDocs/jrq/';

$config['client_portal'] ='http://crm.dcfm.com.au/dcfm07/';// 'http://localhost/dcfm07/';

$config['sms_service_url'] = 'https://api.smsbroadcast.com.au/api-adv.php';
$config['service_username'] = 'username';
$config['service_password'] = 'password';


$config['raptor_support_email'] = 'dcfm@dcfm.com.au';
$config['raptor_welcome_timeout'] = 200; //minutes
$config['raptor_welcome_password'] = 'dcfm';

//Marketing config
$config['SHOW_MARKETING'] = FALSE;
$config['MARKETING_WIDTH'] = '15%';
$config['MARKETING_PAUSETIME'] = 4; //seconds

$config['HOST_LOGO'] = base_url().'assets/img/DCFM2011.png';
$config['HOST_PHONE'] = '1-300-00';
$config['HOST_EMAIL'] = 'dcfm@dcfm.com.au';
$config['HOST_CONTACTS'] = 'DCFM Contacts';
$config['HOST_CUSTOMERID'] = 7781;

$config['google_maps_distance_matrix_api'] = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=';
$config['google_api_key'] = 'AIzaSyDP76G4-ao3G1pYF8emsHuasbVQKWZy9ig';