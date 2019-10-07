<?php

function get_profile_images($dir, $path, $photoid) {
    
    if ($photoid =="" || $photoid == NULL) {
        return base_url("assets/img/user2-160x160.jpg");
    }
    
    $imagepath =$path.$photoid;
    $dirpath = $dir.$photoid;

    if (!file_exists($dirpath)) {
        return base_url("assets/img/user2-160x160.jpg");
    }
    return $imagepath;
}
function aklog($string){
  $file = 'C:/temp/raptor.log';
	  $fh = fopen($file, 'a');
	  
	  if (is_object($string)){
	  	$string = print_r($string,1);
	  }
  	  if (is_array($string)){
	  	$string = print_r($string,1);
	  }
	  
	  fwrite($fh, $string."\n");
	  fclose($fh);
} 
function get_logo_images($dir, $path, $customerid) {
    
    $CI = & get_instance();
    $CI->load->database();
    $db = $CI->db;
    
    $imagepath = base_url("assets/img/DCFM2011.png");
    $db->select("documentid, docformat");
    $db->from('branding b');
    $db->join('brandtype bt', 'bt.id = b.brandtypeid', 'inner');
    $db->join('brand_location bl', 'bl.id=b.brandlocationid', 'inner');
    $db->where('bt.code', 'P');
    $db->where('bl.code', 'L');
    $db->where('b.isdeleted', 0);
    $db->where('b.isactive', 1);
    $db->where('b.customerid', $customerid);
    $brandingImage = $db->get()->row_array();
 
    if (count($brandingImage)>0) {
 
         $imagepath =$path.$brandingImage['documentid'].'.'.$brandingImage['docformat'];
         $dirpath = $dir.$brandingImage['documentid'].'.'.$brandingImage['docformat'];
 
        if (!file_exists($dirpath)) {
            return base_url("assets/img/DCFM2011.png");
        }
    }
    else{
        return base_url("assets/img/DCFM2011.png");
    }
    
    return $imagepath;
}


function valid_pass($candidate) 
{
   $r1='/[A-Z]/';  //Uppercase
   $r2='/[a-z]/';  //lowercase
   $r3='/[!@#$%&*()^,._;:-]/';  // whatever you mean by 'special char'
   $r4='/[0-9]/';  //numbers

   $CI =& get_instance();

        if(preg_match_all($r1, $candidate, $o)<1)
        {
                $CI->form_validation->set_message('valid_pass', 'Password must contain at least 1 uppercase characters(A-Z).');
                return false;
        }
        else if(preg_match_all($r2, $candidate, $o)<1)
        {
                $CI->form_validation->set_message('valid_pass', 'Password must contain at least 1 lowercase characters(a-z).');
                return false;
        }
        /*else if(preg_match_all($r3, $candidate, $o)<1)
        {
                $CI->form_validation->set_message('valid_pass', 'Password must contain at least 1 special characters(!@#$%&*()^,._;:-).');
                return false;
        }
        else if(preg_match_all($r4, $candidate, $o)<1)
        {
                $CI->form_validation->set_message('valid_pass', 'Password must contain at least 1 number(0-9).');
                return false;
        }*/
        else
        {
                return TRUE;
        }
}

function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'jhkjKUYUHKJNKSDUHJVLKFSmsfk680808987KJN11';
    $secret_iv = 'KJHKJHKJAVH7YIU89l3rku98ssppuy8YHJg78JHguu';

    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}

function date_format_select(){
	$formats = array('d/m/Y' => date('d/m/Y'),
                        'm/d/Y' => date('m/d/Y'),
                        'Y/m/d' => date('Y/m/d'),
                        'F j, Y' => date('F j, Y'),
                        'm.d.Y' => date('m.d.Y'),
                        'd-m-Y' => date('d-m-Y'),
                        'D M j Y' => date('D M j Y'),
                    );
	return $formats;
}
function time_format_select(){
	$formats = array('H:i:s' => date('H:i:s'),
                        'g:i A' => date('g:i A')
                    );
	return $formats;
}
function javascript_date_formats($date_format = "d/m/Y"){
		
    $Javascriptformats = array(
        'd/m/Y'=>'dd/mm/yyyy',
        'm/d/Y'=>'mm/dd/yyyy',
        'Y/m/d'=>'yyyy/mm/dd',
        'F j, Y'=>'MM dd, yyyy',
        'm.d.Y'=>'mm.dd.yyyy',
        'd-m-Y'=>'dd-mm-yyyy',
        'D M j Y'=>'D M dd yyyy'
    );
    if ($date_format != "") {
        return $Javascriptformats[$date_format];
    } else {
        return 'dd-mm-yyyy';
    }
}
 
function date_format_preg_match($date_format="d-m-Y"){
    $date_preg_match = array('d/m/Y'=>"/(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})/",
                            'm/d/Y'=>"/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\/([0-9]{4})/",
                            'Y/m/d'=>"/^[0-9]{4}/(0[1-9]|1[0-2])/(0[1-9]|[1-2][0-9]|3[0-1])$/",
                            'F j, Y'=>"/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) (0[1-9]|[1-2][0-9]|3[0-1]), [0-9]{4}$/",
                            'm.d.Y'=>"/^(0[1-9]|1[0-2]).(0[1-9]|[1-2][0-9]|3[0-1]).[0-9]{4}$/",
                            'd-m-Y'=>"/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",
                            'D M j Y'=>"/^(Sun|Mon|Tue|wed|Thu|Fri|sat) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) (0[1-9]|[1-2][0-9]|3[0-1]) [0-9]{4}$/",
			);
    return $date_preg_match[$date_format];
	
}
function format_datetime($date, $date_format = RAPTOR_DISPLAY_DATEFORMAT, $time_format = RAPTOR_DISPLAY_TIMEFORMAT){

    if ($date != '' && $date != '0000-00-00 00:00:00' && $date != null) {
        return date($date_format . ' ' . $time_format, strtotime($date));
    } else {
        return '';
    }
}
function format_date($date, $date_format = RAPTOR_DISPLAY_DATEFORMAT){
    if ($date != '' && $date != '0000-00-00 00:00:00' && $date != null) {
        return date_format(  date_create($date), $date_format); 
        //return date($date_format, strtotime($date));
    } else {
        return '';
    }
}
function format_time($date, $time_format = RAPTOR_DISPLAY_TIMEFORMAT){
    if ($date != '' && $date != '0000-00-00 00:00:00') {
        return date($time_format, strtotime($date));
    } else {
        return '';
    }
}
function to_mysql_date($date = '', $date_format = RAPTOR_DISPLAY_DATEFORMAT, $my_sql_format="Y-m-d"){
    if($date != '')
    {
       return date_format(date_create_from_format($date_format, $date), $my_sql_format);
    }
    else{
        return '';
    }
}

function format_amount($amount = 0, $show_currency_symbol = RAPTOR_SHOW_CURRENCY_SYMBOL, $symbol = RAPTOR_CURRENCY_SYMBOL, $currency_decimal = RAPTOR_CURRENCY_DECIMAL, $currency_symbol_pos = RAPTOR_CURRENCY_SYMBOL_POSITION) {
    $formatted_amt = number_format($amount, $currency_decimal, '.', '');
    if ($show_currency_symbol==1) {
        if ($currency_symbol_pos== "Right") {
            $formatted_amt = (isset($symbol)) ? $formatted_amt.' '.$symbol : $formatted_amt;
        }
        else {
            $formatted_amt = (isset($symbol)) ? $symbol.' '.$formatted_amt : $formatted_amt;
        }
    }
    return $formatted_amt;
}
 
function timeDiff($starttime, $endtime) {
    
    $timespent = strtotime( $endtime)-strtotime($starttime);
    $days = floor($timespent / (60 * 60 * 24)); 
    $remainder = $timespent % (60 * 60 * 24);
    $hours = floor($remainder / (60 * 60));
    $remainder = $remainder % (60 * 60);
    $minutes = floor($remainder / 60);
    $seconds = $remainder % 60;
    $TimeInterval = '';
    if($hours < 0) $hours=0;
    if($hours != 0)
    {
        $TimeInterval = ($hours == 1) ? $hours.' hour' : $hours.' hours';
    }
    if ($minutes < 0) {
        $minutes = 0;
    }
    if ($seconds < 0) {
        $seconds = 0;
    }
    $TimeInterval = $minutes.' minutes '. $seconds.' seconds ';
    

    return $TimeInterval;
}
function limitWords($text, $limit) {
    $word_arr = explode(" ", $text);

    if (count($word_arr) > $limit) {
        $words = implode(" ", array_slice($word_arr , 0, $limit) ) . ' ...';
        return $words;
    }

    return $text;
}

function limitTexts($text, $limit) {
    if (strlen($text) > $limit) {
        $text = substr($text , 0, $limit) . ' ...';
        return $text;
    }
    return $text;
}

function getPeriods($format ='d/m/Y') {
    $periods =  array();
    
    $firstDateFormat = str_replace('d', '01', $format);
    $lastDateFormat = str_replace('d', 't', $format);
    
    $firstDateMonthFormat = str_replace('m', '01', $firstDateFormat);
    $lastDateMonthFormat = str_replace('m', '12', $lastDateFormat);
    
    $firstFiscalDateMonthFormat = str_replace('m', '07', $firstDateFormat);
    $lastFiscalDateMonthFormat = str_replace('m', '06', $lastDateFormat);
    
    //Current Month
    $currentMonthDate = time();
    $periods['current_month'] = array(
        'title'     => 'Current Month',
        'fromdate'  => date($firstDateFormat, $currentMonthDate),
        'todate'    => date($format, $currentMonthDate)
    ); 
    
    //Prior Month
    $priorMonthDate = strtotime('-1 month', time());
    $periods['prior_month'] = array(
        'title'     => 'Prior Month',
        'fromdate'  => date($firstDateFormat, $priorMonthDate),
        'todate'    => date($lastDateFormat, $priorMonthDate)
    );
    
     //Prior2 Month
    $prior2MonthDate = strtotime('-2 month', time());
    $periods['2_months_prior'] = array(
        'title'     => '2 Months Prior',
        'fromdate'  => date($firstDateFormat, $prior2MonthDate),
        'todate'    => date($lastDateFormat, $prior2MonthDate)
    );
    
    //Prior3 Month
    $prior3MonthDate = strtotime('-3 month', time());
    $periods['3_months_prior'] = array(
        'title'     => '3 Months Prior',
        'fromdate'  => date($firstDateFormat, $prior3MonthDate),
        'todate'    => date($lastDateFormat, $prior3MonthDate)
    );
    
    
    //Calendar Year  
    $periods['calendar_year'] = array(
        'title'     => 'Calendar Year',
        'fromdate'  => date($firstDateMonthFormat, $currentMonthDate),
        'todate'    => date($format, $currentMonthDate)
    );
    
    
    //Prior Calendar Year
    $priorCalendarYearDate = strtotime('-1 Year', time());
    $periods['prior_calendar_year'] = array(
        'title'     => 'Prior Calendar Year',
        'fromdate'  => date($firstDateMonthFormat, $priorCalendarYearDate),
        'todate'    => date($lastDateMonthFormat, $priorCalendarYearDate)
    );
    
    //Fiscal Year  
    if((int)date('m')>=7){
        $startdateYear = time();
        $enddateYear = strtotime('+1 Year', time());
    }
    else{
        $startdateYear = strtotime('-1 Year', time());
        $enddateYear = time();
    }
    $periods['fiscal_year'] = array(
        'title'     => 'Fiscal Year',
        'fromdate'  => date($firstFiscalDateMonthFormat, $startdateYear),
        'todate'    => date($lastFiscalDateMonthFormat, $enddateYear)
    );
    
    
    //Prior Fiscal Year
    if((int)date('m')>=7){
        $startdateYear = strtotime('-1 Year', time());
        $enddateYear = time();
    }
    else{
        $startdateYear = strtotime('-2 Year', time());
        $enddateYear = strtotime('-1 Year', time());
    }
    $periods['prior_fiscal_year'] = array(
        'title'     => 'Prior Fiscal Year',
        'fromdate'  => date($firstFiscalDateMonthFormat, $startdateYear),
        'todate'    => date($lastFiscalDateMonthFormat, $enddateYear)
    );
    
    //Rolling 12 Months (LM)  
    $startYear = strtotime('-1 Year', time());
    $endYear = strtotime('-1 month', time());
    $periods['rolling_12_months_lm'] = array(
        'title'     => 'Rolling 12 Months (LM)',
        'fromdate'  => date($firstDateFormat, $startYear),
        'todate'    => date($lastDateFormat, $endYear)
    );
    
    //Rolling 12 Months (TM)
    $startYear = strtotime('-11 month', time());
    $endYear = time();
    $periods['rolling_12_months_tm'] = array(
        'title'     => 'Rolling 12 Months (TM)',
        'fromdate'  => date($firstDateFormat, $startYear),
        'todate'    => date($lastDateFormat, $endYear)
    );
    return $periods; 
}

function random_password( $length = 8 ) {
    $lower = "abcdefghijklmnopqrstuvwxyz";
    $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $number = "0123456789";
    $special = "!@#$%&_-";

    $password = substr( str_shuffle( $lower ), 0, 2 );
    $password = $password . substr( str_shuffle( $upper ), 0, 2 );
    $password = $password . substr( str_shuffle( $number ), 0, 2 );
    $password = $password . substr( str_shuffle( $special ), 0, 2 );
    return $password;
}


function checkDocumentImage($dirpath, $imagepath, $documentid, $format) {
    
    
    $original = $documentid.'.'.$format;
    $thumb = $documentid.'_thumb.'.$format;
    
    $image = array(
        'original'  => '',
        'thumb'     => ''
    );
   
    if (file_exists($dirpath.$thumb)) {
        $image['thumb'] = $imagepath.$thumb;
    }
    
    if (file_exists($dirpath.$original)) {
	$image['original'] = $imagepath.$original; 
    }
	 
    return $image;
}

 function getLocationfromAddress($address) {
        $url = "http://maps.google.com/maps/api/geocode/json?address=" . str_replace(' ', '+', $address) . "&sensor=false";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        return $response_a->status !== 'ZERO_RESULTS' ? $response_a->results[0]->geometry->location : null;
}


function sortArraybyString($key) {    
    return function ($a, $b) use ($key) {
        $ar = strnatcmp($a[$key], $b[$key]);
        return $ar;
    };
}
 
function getYouTubeVideoId($url)
{
    $video_id = false;
    $url = parse_url($url);
    if (strcasecmp($url['host'], 'youtu.be') === 0)
    {
        #### (dontcare)://youtu.be/<video id>
        $video_id = substr($url['path'], 1);
    }
    elseif (strcasecmp($url['host'], 'www.youtube.com') === 0)
    {
        if (isset($url['query']))
        {
            parse_str($url['query'], $url['query']);
            if (isset($url['query']['v']))
            {
                #### (dontcare)://www.youtube.com/(dontcare)?v=<video id>
                $video_id = $url['query']['v'];
            }
        }
        if ($video_id == false)
        {
            $url['path'] = explode('/', substr($url['path'], 1));
            if (in_array($url['path'][0], array('e', 'embed', 'v')))
            {
                #### (dontcare)://www.youtube.com/(whitelist)/<video id>
                $video_id = $url['path'][1];
            }
        }
    }
    return $video_id;
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function getWeeksOfMonth($month, $year) {
    $beg = (int) date('W', strtotime("first monday of $year-$month"));
    $end = (int) date('W', strtotime("last  monday of $year-$month"));
    
    $weeks = range($beg, $end);

    $start = 1;
    $end = date("t", strtotime("$year-$month-01"));
    $weeks = array();
    
    for($start = 1; $start <= $end; $start++)
    {
        $time = mktime(0, 0, 0, $month, $start, $year);
        if (date('N', $time) == 1)
        {
            $weeks[] =  date('W', $time);
        }
    }
    return $weeks;
}

function getStartAndEndDate($year, $week) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret[0] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret[1] = $dto->format('Y-m-d');
  return $ret;
}

function getMonthsInRange($startDate, $endDate) {
    $months = array();
    while (strtotime($startDate) <= strtotime($endDate)) {
        $months[] = array('year' => date('Y', strtotime($startDate)), 'month' => date('m', strtotime($startDate)));
        //$startDate = date('d M Y', strtotime($startDate.'+ 1 month'));
        $startDate = date('d M Y', strtotime($startDate.'+ 1 day'));
    }

    return $months;
}

function getDatesFromRange($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {

    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while( $current <= $last ) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
 