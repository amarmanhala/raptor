<div class="box box-solid" style=" height: 100%;">
    <div id="marketingPanel" class="box-body" style=" height: 100%;">
        <div id="marketingFirst" >
            <p><img src="<?php echo $this->config->item('HOST_LOGO');?>"></p>
             <ul>
                <li><i class="fa fa-fw fa-phone" style="font-size: 18px;"></i>&nbsp;<?php echo $this->config->item('HOST_PHONE');?></li>
                <li><i class="fa fa-fw fa-envelope" style="font-size: 18px;"></i>&nbsp;<a href="mailto:<?php echo $this->config->item('HOST_EMAIL');?>" target="_top"><?php echo $this->config->item('HOST_EMAIL');?></a></li>
                <li><i class="fa fa-fw fa-users" style="font-size: 18px;"></i>&nbsp;<a href="javascript:void(0);" onclick="showOrgChart(<?php echo $this->config->item('HOST_CUSTOMERID');?>)"><?php echo $this->config->item('HOST_CONTACTS');?></a></li>
            </ul>
             
        </div>
        <div id="marketingSecond" >
            <h5 >Notifications:</h5>
            <ul>
                <li>
                    <a href="<?php echo site_url('jobs');?>">
                        <small class="label pull-left unapprovejobs" style="background-color:#ec0404;color:#fefefe;font-size: 13px;"><?php echo isset($menucounter['unapprovejobs']) ? $menucounter['unapprovejobs'] : 0;?></small>
                    </a>&nbsp;Jobs for Approval</li>
                <li>
                    <a href="<?php echo site_url('quotes');?>">
                        <small class="label pull-left unapprovequotes" style="background-color:#ec0404;color:#fefefe;font-size: 13px;"><?php echo isset($menucounter['unapprovequotes']) ? $menucounter['unapprovequotes'] : 0;?></small>
                    </a>&nbsp;Quotes for Approval</li>
                <li>
                    <a href="<?php echo site_url('jobs');?>">
                        <small class="label pull-left wiatingvariationjobs" style="background-color:#ec0404;color:#fefefe;font-size: 13px;"><?php echo isset($menucounter['wiatingvariationjobs']) ? $menucounter['wiatingvariationjobs'] : 0;?></small>
                    </a>&nbsp;Variations for Approval</li>
                <li>
                    <a href="<?php echo site_url('statements');?>">
                        <small class="label pull-left unapproveinvoices" style="background-color:#ec0404;color:#fefefe;font-size: 13px;"><?php echo isset($menucounter['unapproveinvoices']) ? $menucounter['unapproveinvoices'] : 0;?></small>
                    </a>&nbsp;Invoices for Approval</li>
            </ul>
            
            <?php if(isset($budgetWidgetCaption)) { ?>
            <h5 ><?php echo $budgetWidgetCaption['caption'];?></h5>
            <ul>
                <li>
                    <div class="form-horizontal">
                    <div class="form-group">
                      <div class="col-sm-12">
                          <select id="widget_glcode" name="widget_glcode">
                            <option value="">Gl Code</option>
                            <?php foreach($glcodes as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['glcode'];?></option> 
                            <?php } ?>
                        </select>
                      </div>
                    </div>
                </div>
                </li>
                <li>
                    <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php 
                                $current_month = date('M', time());
                                $current_date = date('Y-m-d', time());
                                $next_month = date('M',strtotime('+ 1 month'));
                                $next_date = date('Y-m-d',strtotime('+ 1 month'));
                            ?>
                            <input type="radio" name="widget_month" value="<?php echo $current_date;?>" checked="checked" />&nbsp;<?php echo strtoupper($current_month);?>&nbsp;
                            <input type="radio" name="widget_month" value="<?php echo $next_date;?>" />&nbsp;<?php echo strtoupper($next_month);?>&nbsp;
                            <input type="radio" name="widget_month" value="wait" />&nbsp;WAIT
                            <input type="hidden" id="widget_check" />
                            <input type="hidden" id="widget_current_date" value="<?php echo $current_date;?>" />
                            <input type="hidden" id="widget_next_date" value="<?php echo $next_date;?>" />
                            <input type="hidden" id="widget_jobid" value="" />
                        </div>
                    </div>
                </div>
                </li>
                
                <li>
                    <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="chart" id ="budgetWidgetChart" ></div>
                        </div>
                    </div>
                </div>
                </li>
                
                <li>
                    <div class="form-horizontal">
                    <div class="form-group">
       
                        <div class="col-sm-6">
                            Left:&nbsp;&nbsp;<span id="widgetBudgetLeftFirst"></span> 
                        </div>
                        <div class="col-sm-6">
                            <span id="widgetBudgetLeftSecond"></span> 
                        </div>
                    </div>
                </div>
                </li>
            </ul>
            <?php } ?>
            
            <h5>Messages:</h5>
            <table class="table table-bordered"></table>
            
        </div>
        <div id="marketingThird" style="min-height:8%;height:auto;border:1px groove;padding:5px;"></div>
    </div>
</div>