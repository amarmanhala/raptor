<div class= "row">
    <div class= "col-md-12">
        <div class= "box"  id = "ContractScheduleCtrl" ng-controller= "ContractScheduleCtrl">
            <div class= "box-header  with-border">
                <h3 class= "box-title  text-blue">Schedules</h3>
                <div class= "pull-right">
                    <input type="hidden" id="edit_schedule" name="edit_schedule" value="<?php echo $EDIT_CONTRACT_SCHEDULE ? '1':'0';?>">
                    <input type="hidden" id="delete_schedule" name="delete_schedule" value="<?php echo $DELETE_CONTRACT_SCHEDULE ? '1':'0';?>">
                    <?php if($ADD_CONTRACT_SCHEDULE) { ?>
                     &nbsp;<button type="button" class="btn  btn-primary" ng-click="addSchedule();" title="Add a new site"><i class="fa fa-plus"></i></button> 
                   <?php } ?>
                </div>
            </div>
            <div class= "box-header  with-border" id="filterform">
                <div class="row">
                    <div class= "col-sm-2 col-md-1">
                        <?php if($MAKE_CONTRACT_SCHEDULE) { ?>
                        <button type = "button"  class= "btn btn-info" title = "Make Schedule" ng-click="makeSchedule()"><i class= "fa fa-calendar-plus-o" title = "Make Schedule"></i></button>
                        <?php } ?>
                    </div>
                    <div class="col-sm-5 col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" ng-change = "changeFilters()" ng-model= "filterOptions.fromdate">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" ng-change = "changeFilters()" ng-model= "filterOptions.todate">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div  class="col-sm-12 col-md-5">
                        <div class="input-group input-group">
                            <input type = "text"  placeholder="Search........."  ng-change = "changeText()" class= "form-control" ng-model= "filterOptions.filterOption" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                    <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                    <?php if($EXPORT_CONTRACT_SCHEDULE) { ?>
                                    <button type="button" class= "btn btn-success" title = "Export To Excel" ng-click="exportToExcel()"><i title="Export To Excel"  class= "fa fa-file-excel-o"></i></button> 
                                    <?php } ?>
                                </span>
                        </div>
                    </div>
                </div>    
            </div>
            <div class= "box-body">
                <div id="scheduleGrid">
                    <div ui-grid = "scheduleGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridwithselect"></div>
                </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay"  ng-show="overlay">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
             
            <div class="modal fade" id ="schedulesModal" tabindex="-1" role ="dialog" aria-labelledby="schedulesModalLabel" data-backdrop="static" data-keyboard ="false">
                <div class="modal-dialog modal-lg" role ="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add Site for</h4>
                        </div>
                        <form name ="scheduleform" id ="scheduleform" class="form-horizontal" method ="post"  >
                            <div class="modal-body">
                                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                                <div id ="schedulegriddiv" style ="display:none;"> 
                                    <div class="status"></div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-8">
                                            <input type ="text" class= "form-control"  name ="name" id ="name" value ="" placeholder="Name"/> 
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Service Type</label>
                                        <div class="col-sm-5">
                                            <select class= "form-control"  name="servicetypeid" id="servicetypeid">
                                                <option value="">Select</option>
                                                <?php foreach($servicetypes as $key=>$value) { ?>
                                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                                                <?php } ?>
                                            </select>
                                        </div>

                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subworksid" class="col-sm-2 control-label">Works</label>
                                        <div class="col-sm-3">
                                            <select class= "form-control"  id="subworksid" name="subworksid">
                                                <option value="0">Select</option>
                                                <?php foreach ($subworks as $key => $value) {
                                                    echo '<option value="'.$value['id'].'">'.$value['se_subworks_name'].'</option>';
                                                }?>
                                            </select>
                                        </div>

                                        <label for="seasonid" class="col-sm-1 control-label">Season</label>
                                        <div class="col-sm-3">
                                            <select class= "form-control"  id="seasonid" name="seasonid">
                                                <option value="0">Select</option>
                                                <?php foreach ($seasons as $key => $value) {
                                                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                                }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class= "form-group">
                                        <label for= "season_start_date" class= "col-sm-2 control-label">Start Date</label>
                                        <div class= "col-sm-3">
                                            <div class= "input-group">
                                                <input type = "text" class= "form-control datepicker" id = "season_start_date" name = "season_start_date" placeholder="Start Date" value = "" readonly=""/>
                                                <div class= "input-group-addon">
                                                    <i class= "fa fa-calendar"></i>
                                                </div>
                                            </div>
                                         </div>
                                        <label for= "season_end_date" class= "col-sm-1 control-label">Finish</label>
                                        <div class= "col-sm-3">
                                            <div class= "input-group">
                                                <input type = "text" class= "form-control datepicker" id = "season_end_date" name = "season_end_date" placeholder="End Date" value = ""  readonly="" />
                                                <div class= "input-group-addon">
                                                    <i class= "fa fa-calendar"></i>
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="frequency_count" class="col-sm-2 control-label">Frequency</label>
                                        <div class="col-sm-2">
                                            <input type="number" min="1" name="frequency_count" id="frequency_count" class="form-control allownumericwithoutdecimal" />
                                        </div>
                                        <label for="period" class="col-sm-1 control-label">Period</label>
                                        <div class="col-sm-2">
                                            <select class= "form-control" id="frequency_period" name="frequency_period">
                                                <option value="M">Month</option>
                                                <option value="W">Week</option>
                                                <option value="D">Day</option>
                                            </select>
                                        </div>
                                        <label for="visitsperyear" class="col-sm-1 control-label">Visits/year</label>
                                        <div class="col-sm-1">
                                            <input type="text" name="visitsperyear" id="visitsperyear" class="form-control allownumericwithoutdecimal" readonly="readonly" />
                                        </div>
                                    </div>
                                    <div class= "form-group">
                                        <label for= "firstjobdate" class= "col-sm-2 control-label">First Job</label>
                                        <div class= "col-sm-3">
                                            <div class= "input-group">
                                                <input type = "text" class= "form-control datepicker" id = "firstjobdate" name = "firstjobdate" placeholder="First Job Date" value = "" readonly=""/>
                                                <div class= "input-group-addon">
                                                    <i class= "fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="name" class="col-sm-1 control-label">Float</label>
                                        <div class="col-sm-3">
                                            <select class= "form-control"  id="maxfloat" name="maxfloat">
                                                <option value="0">Select</option>
                                                <?php
                                                for($i=1;$i<=31;$i++){
                                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for= "season_start_date" class= "col-sm-2 control-label">Allowable Days</label>
                                        <div class= "col-sm-10">
                                            <label class="checkbox-inline"><input type="checkbox" id="sun_ok" name="sun_ok" value="1">Sun</label>
                                            <label class="checkbox-inline"><input type="checkbox" id="mon_ok" name="mon_ok"  value="1">Mon</label>
                                            <label class="checkbox-inline"><input type="checkbox" id="tue_ok" name="tue_ok"  value="1">Tue</label>
                                            <label class="checkbox-inline"><input type="checkbox" id="wed_ok" name="wed_ok"  value="1">Wed</label>
                                            <label class="checkbox-inline"><input type="checkbox" id="thu_ok" name="thu_ok"  value="1">Thu</label>
                                            <label class="checkbox-inline"><input type="checkbox" id="fri_ok" name="fri_ok"  value="1">Fri</label>
                                            <label class="checkbox-inline"><input type="checkbox" id="sat_ok" name="sat_ok"  value="1">Sat</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for= "firstjobdate" class= "col-sm-2 control-label">Last Scheduled</label>
                                        <div class= "col-sm-3"> 
                                            <input type = "text" class= "form-control" id = "last_scheduled" name = "last_scheduled" placeholder="Last Scheduled" value = "" readonly=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Active</label>
                                        <div class="col-sm-10">
                                            <div class="checkbox">
                                                <label><input type="checkbox" id="isactive" name="isactive" value="1"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <input type ="hidden" name ="contractid" id ="contractid" value =""/> 
                                        <input type ="hidden" name ="scheduleid" id ="scheduleid" value =""/> 
                                        <input type ="hidden" name ="mode" id ="mode" value =""/> 
                                        <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                                        &nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                                    </div>
                                </div> 
                            </div>     
                        </form>
                    </div>
                </div>
            </div>
         
            <div class="modal fade" id ="UpdateContractschedulesModal" tabindex="-1" role ="dialog" aria-labelledby="UpdateContractschedulesModalLabel" data-backdrop="static" data-keyboard ="false">
                <div class="modal-dialog" role ="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Update Contract Schedule</h4>
                        </div>
                        <form name ="updatescheduleform" id ="updatescheduleform" class="form-horizontal" method ="post"  >

                            <div class="modal-body">
                                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                                <div id ="schedulegriddiv" style ="display:none;"> 
                                    <div class="status"></div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-3 control-label">Schedule</label>
                                        <div class="col-sm-9">
                                            <input type ="text" class= "form-control" readonly="readonly" name ="name" id ="name" value ="" placeholder="Schedule"/> 
                                        </div>

                                    </div>
                                    <div class= "form-group">
                                        <label for= "season_start_date" class= "col-sm-3 control-label">Start Date</label>
                                        <div class= "col-sm-3">
                                            <input type = "text" class= "form-control" id = "season_start_date" name = "season_start_date" placeholder="Start Date" value = "" readonly=""/>
                                        </div>
                                        <label for= "season_end_date" class= "col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Finish</label>
                                        <div class= "col-sm-3">
                                            <input type = "text" class= "form-control" id = "season_end_date" name = "season_end_date" placeholder="End Date" value = ""  readonly="" />
                                        </div>
                                    </div>
                                    <div class= "form-group">
                                        <label for= "firstjobdate" class= "col-sm-3 control-label">First Job</label>
                                        <div class= "col-sm-3">
                                            <input type = "text" class= "form-control" id = "firstjobdate" name = "firstjobdate" placeholder="First Job" value = "" readonly=""/>
                                        </div>
                                        <label for= "last_scheduled" class= "col-sm-2 control-label" style="padding-left: 0px; padding-right: 0px;">Last Scheduled</label>
                                        <div class= "col-sm-3">
                                            <input type = "text" class= "form-control" id = "last_scheduled" name = "last_scheduled" placeholder="Last Scheduled" value = ""  readonly="" />
                                        </div>
                                    </div>
                                    <div class= "form-group">
                                        <label for= "schedule_start_date" class= "col-sm-3 control-label">Schedule From</label>
                                        <div class= "col-sm-4">
                                            <div class= "input-group">
                                                <input type = "text" class= "form-control datepicker" id = "schedule_start_date" name = "schedule_start_date" placeholder="Schedule From Date" value = "" readonly=""/>
                                                <div class= "input-group-addon">
                                                    <i class= "fa fa-calendar"></i>
                                                </div>
                                            </div>
                                         </div>
                                    </div>
                                    <div class= "form-group">
                                        <label for= "schedule_end_date" class= "col-sm-3 control-label">Schedule To</label>
                                        <div class= "col-sm-4">
                                            <div class= "input-group">
                                                <input type = "text" class= "form-control datepicker" id = "schedule_end_date" name = "schedule_end_date" placeholder="Schedule To" value = ""  readonly="" />
                                                <div class= "input-group-addon">
                                                    <i class= "fa fa-calendar"></i>
                                                </div>
                                            </div>
                                         </div>
                                        <div class= "col-sm-3">
                                            <button type="button" class="btn btn-success btn-flat" id="btnwholeperiod">Whole Period</button>
                                        </div>
                                    </div>
                                    <div class= "form-group">
                                        <label for= "visits_created" class= "col-sm-3 control-label">Visits Created</label>
                                        <div class= "col-sm-2">
                                            <input type = "text" class= "form-control" id = "visits_created" name = "visits_created" placeholder="Visits" value = ""  readonly="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="form-group">
                                    <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <input type ="hidden" name ="frequency_count" id ="frequency_count" value =""/> 
                                        <input type ="hidden" name ="frequency_period" id ="frequency_period" value =""/> 
                                        <input type ="hidden" name ="contractid" id ="contractid" value =""/> 
                                        <input type ="hidden" name ="scheduleid" id ="scheduleid" value =""/> 
                                         <?php if($DELETE_CONTRACT_SCHEDULE) { ?>
                                        <button type ="button" name ="btndelete" id ="btndelete" class="btn btn-danger" data-loading-text ="Saving...">Delete Schedule</button>
                                      <?php } ?>
                                        
                                        &nbsp;<button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Create Schedule</button>
                                        &nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving..." data-dismiss="modal" aria-label="Close">Cancel</button>
                                    </div>
                                </div> 
                            </div>     
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="ScheduledSitesModal" tabindex="-1" role="dialog" aria-labelledby="ScheduledSitesModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Scheduled Sites</h4>
                        </div>
                        <form name="addEditSecurityForm" id="addEditSecurityForm" class="form-horizontal" autocomplete="off" novalidate>
                            <div class="modal-header">
                                <div class="row">
                                    <div class= "col-xs-8 col-sm-6 col-md-4">
                                        <select class= "form-control selectpicker"  ng-change = "changeScheduledSitesFilters()" ng-model= "ScheduledSitesFilter" >
                                            <option value = ''>All State</option>
                                            <?php foreach($states as $val) { ?>
                                                <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                     <div class= "col-xs-4 col-sm-6 col-md-8 text-right">
                                        <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearScheduledSitesFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                        <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeScheduledSitesFilters()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>

                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-body">
                                <center  ng-show="ScheduledSiteoverlay" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                             
                                <div class="row" ng-hide="ScheduledSiteoverlay">
                                    <div class= "col-sm-5">
                                        <div class= "box">
                                            <div class= "box-header  with-border">
                                                <h3 class= "box-title  text-blue">Available Sites</h3>
                                            </div>
                                            <div class= "box-body"  style="padding: 0px;">
                                                <div id="scheduleAvailableSitesGrid">
                                                    <div ui-grid = "scheduleAvailableSitesGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridautoheight1"></div>
                                                </div>
                                            </div><!-- /.box-body -->
                                           
                                        </div>
                                    </div>
                                    <div class= "col-sm-2">
                                         <div style="vertical-align:middle;" class="text-center">
                                            <table border="0" style="width:100%;">
                                                <tr><td>&nbsp;</td></tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" ng-click="manageScheduleSites('lhs');">>></button>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" ng-click="manageScheduleSites('rhs');"><<</button>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                    <div class= "col-sm-5">
                                        <div class= "box">
                                            <div class= "box-header  with-border">
                                                <h3 class= "box-title  text-blue">Selected Sites</h3>
                                            </div>
                                            <div class= "box-body" style="padding: 0px;">
                                                <div id="scheduleSelectedSitesGrid">
                                                    <div ui-grid = "scheduleSelectedSitesGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridautoheight1"></div>
                                                </div>
                                            </div><!-- /.box-body -->
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer"> 
                                <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary" ng-disabled="ScheduledSiteoverlay" data-loading-text="<i class='fa fa-spinner fa-spin'></i>&nbsp;Saving...">Save</button>
                                <button type ="button" id="modalclose" name ="modalclose" class="btn btn-default" ng-disabled="ScheduledSiteoverlay"  data-loading-text="Close" data-dismiss="modal" aria-label="Close" >Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>	
    </div>
</div>