<?php 
/**
 * Cron Controller Class
 *
 * This is a Cron controller class
 * 
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller Class
 *
 * This is a Cron controller class
 *
 * @package		Raptor
 * @subpackage          Controller
 * @category            Cron
 * @filesource          Cron.php
 * @author		Itgrid Munish <itgrid.munish@gmail.com>
 * 
 */
class Cron extends CI_Controller {

	 
    /**
    * Class constructor
    *
    * @return	void
    */
    function __construct()
    {
        parent::__construct();
        
        $this->load->library('budget/BudgetClass');
        
    }

    
    /**
     * evaluating threshold by cron
     */  
    public function evaluating_threshold() {

        require_once($_SERVER['DOCUMENT_ROOT'] . "/common/phoenix/bl/budgets.class.php");
        $BudgetCPortal = new budgets(AppType::JobTracker, 'itglobel');

        $budget_settings =$this->budgetclass->getBudgetSettings();
        foreach ($budget_settings as $key => $value) {

            $startmonth= (int)$value['startmonth'];

            if($startmonth==1){
                $fromdate=date('Y').'-01-01';
            }
            else{
                if(date('m') > $startmonth){
                    if($startmonth>9){
                        $fromdate=date('Y').'-'.$startmonth.'-01';
                    }
                    else{
                        $fromdate=date('Y').'-0'.$startmonth.'-01';
                    }
                }
                else {
                    if($startmonth>9){
                        $fromdate=(date('Y')-1).'-'.$startmonth.'-01';
                    }
                    else{
                        $fromdate=(date('Y')-1).'-0'.$startmonth.'-01';
                    }
                }
            }

            $fromdate=strtotime($fromdate);
            $todate=strtotime("+1 Years", $fromdate);
            $todate=strtotime("-1 Days", $todate);

            $budget_threshold =$this->budgetclass->getBudgetThreshold($value['customerid']);
            foreach ($budget_threshold as $key1 => $value1) {
                $evalmonths=$value1['evalmonths'];
                $evalfromdate=$fromdate;

                $evaltodate=strtotime("+". ($evalmonths) ." Months", $evalfromdate);
                $evaltodate=strtotime("-1 Days", $evaltodate);
                while ($evaltodate< time()) {
                    $evalfromdate=strtotime("+1 Days", $evaltodate);
                    $evaltodate=strtotime("+". ($evalmonths) ." Months", $evalfromdate);
                    $evaltodate=strtotime("-1 Days", $evaltodate);
                }
                $FromYearMonth=(int)date('Ym', $evalfromdate);
                $ToYearMonth=(int)date('Ym', $evaltodate);
                
                $laststage=101;
                if(isset($budget_threshold[$key1+1]['threshold_pct'])){
                    $laststage=(int)$budget_threshold[$key1+1]['threshold_pct'];
                } 

                $sqldata = $this->budgetclass->getSiteBudgets($value['customerid'], $FromYearMonth, $ToYearMonth, date('Y-m-d', $evalfromdate), date('Y-m-d', $evaltodate));

                foreach ($sqldata as $key2 => $value2) {

                    $evaluate_budget = array();
                    $evaluate_budget['budget_period']=(float)$value2['actualbudget'];
                    $evaluate_budget['budget_position']=(float)$value2['actual'];

                    $budget_pctspend=((float)$value2['actual']/(float)$value2['actualbudget'])*100;
                    $color='';
                    if($budget_pctspend>=$value1['threshold_pct'] && $budget_pctspend<$laststage){
                        $evaluate_budget['budget_colour']=$value1['colour'];
                        $color=$value1['colour'];
                        $jobupp=$this->budgetclass->getJobsBudgetposition($value['customerid'], $value2['labelid'],(int)$value1['threshold_pct'], $laststage);
                        if(count($jobupp)==0){
                            $BudgetCPortal->sendNotificationEmail($value['customerid'], $evalfromdate, $evaltodate, $value1, $value2['labelid'], $evaluate_budget);
                        }
                    }

                    //$BudgetCPortal->sendNotificationEmail($value['customerid'], $evalfromdate, $evaltodate, $value1, $value2['labelid'], $evaluate_budget);
                    $BudgetCPortal->updateJOBS($value2['labelid'], $value['customerid'],(float)$value2['actual'],(float)$value2['actualbudget'], $color);
                    
                }
            }
        }

    }
        
}