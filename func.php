<?php
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
function getStartAndEndDate1($year, $week)
{
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-m-d', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-m-d', $time);
    return $return;
}

function getStartAndEndDate($year, $week) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret[0] = $dto->format('Y-m-d');
  $dto->modify('+6 days');
  $ret[1] = $dto->format('Y-m-d');
  return $ret;
}

echo '<pre>';
/*$s = getWeeksOfMonth(1, 2016);
foreach($s as $v) {
    echo $v;
    print_r(getStartAndEndDate(2016, $v));
}
echo '-----------------------------------------------------<br>';
$s = getWeeksOfMonth(2, 2016);
foreach($s as $v) {
    echo $v;
    print_r(getStartAndEndDate(2016, $v));
}
echo '-----------------------------------------------------<br>';
$s = getWeeksOfMonth(3, 2016);
foreach($s as $v) {
    echo $v;
    print_r(getStartAndEndDate(2016, $v));
}
echo '-----------------------------------------------------<br>';
$s = getWeeksOfMonth(4, 2016);
foreach($s as $v) {
    echo $v;
    print_r(getStartAndEndDate(2016, $v));
}
echo '-----------------------------------------------------<br>';*/



function getMonthsInRange($startDate, $endDate) {
    $months = array();
    while (strtotime($startDate) <= strtotime($endDate)) {
        $months[] = array('year' => date('Y', strtotime($startDate)), 'month' => date('m', strtotime($startDate)));
        //$startDate = date('Y-m-d', strtotime($startDate.'+ 1 month'));
        $startDate = date('Y-m-d', strtotime($startDate.'+ 1 day'));
    }
    return $months;
}

$filterMonths = getMonthsInRange('2015-12-28', '2016-11-30');
$tMonths = array();
foreach($filterMonths as $mth) {  
    $tMonths[] = $mth['month']; 
}
$tMonths = array_unique($tMonths);
print_r($tMonths);


 ?>