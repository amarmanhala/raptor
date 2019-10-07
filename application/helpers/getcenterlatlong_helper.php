<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function getCenterLatLong($jobs){
    $minlat = '';
    $maxlat = '';
    $minlong = '';
    $maxlong = '';
    if(!empty($jobs)){
        foreach($jobs as $job){
            if(empty($minlat)){
                if(!empty($job['latitude_decimal']) && ($job['latitude_decimal'] != 0) && ($job['latitude_decimal'] != '0.00000000000000')){
                    $minlat = $job['latitude_decimal'];
                }
                //echo 'minlat is '.$minlat.' and the labelid is:'.$job['jobid'].'</br>';
            }else if($job['latitude_decimal'] < $minlat  && ($job['latitude_decimal'] != 0) && ($job['latitude_decimal'] != '0.00000000000000')){
                $minlat = $job['latitude_decimal'];
                //echo 'minlat is '.$minlat.' and the labelid is:'.$job['jobid'].'</br>';
            }
            if(empty($maxlat)){
                if(!empty($job['latitude_decimal']) && ($job['latitude_decimal'] != 0) && ($job['latitude_decimal'] != '0.00000000000000')){
                    $maxlat = $job['latitude_decimal'];
                }

            //echo 'maxlat is '.$maxlat.' and the labelid is:'.$job['jobid'].'</br>';
            }else if($job['latitude_decimal'] > $maxlat && ($job['latitude_decimal'] != 0) && ($job['latitude_decimal'] != '0.00000000000000')){
                $maxlat = $job['latitude_decimal'];
                //echo 'maxlat is '.$maxlat.' and the labelid is:'.$job['jobid'].'</br>';
            }

            if(empty($minlong)){
                if(!empty($job['longitude_decimal']) && ($job['longitude_decimal'] != 0) && ($job['longitude_decimal'] != '0.00000000000000')){
                    $minlong = $job['longitude_decimal'];
                }
                //echo 'minlong is '.$minlong.' and the labelid is:'.$job['jobid'].'</br>';
            }else if($job['longitude_decimal'] < $minlong && ($job['longitude_decimal'] != 0) && ($job['longitude_decimal'] != '0.00000000000000')){
                $minlong = $job['longitude_decimal'];
                //echo 'minlong is '.$minlong.' and the labelid is:'.$job['jobid'].'</br>';
            }
            if(empty( $maxlong)){
                if(!empty($job['longitude_decimal']) && ($job['longitude_decimal'] != 0) && ($job['longitude_decimal'] != '0.00000000000000')){
                    $maxlong = $job['longitude_decimal'];
                }

                //echo 'maxlong is '.$maxlong.' and the labelid is:'.$job['jobid'].'</br>';
            }else if($job['longitude_decimal'] > $maxlong && ($job['longitude_decimal'] != 0) && ($job['longitude_decimal'] != '0.00000000000000')){
                $maxlong = $job['longitude_decimal'];
                //echo 'maxlong is '.$maxlong.' and the labelid is:'.$job['jobid'].'</br>';
            }
        }

        $centerlat =  ($maxlat  + $minlat) / 2 ;
        $centerlong = ($maxlong + $minlong) / 2 ; 
        $centerlatlong = array($centerlat, $centerlong);
        return $centerlatlong;
    }
    else{
        $centerlatlong = array("-33.7969235","150.9224326");
        return $centerlatlong;
    }
		
		
}
