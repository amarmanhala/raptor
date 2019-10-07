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
                        <button type = "button"  class= "btn btn-info" title = "Make Schedule" ><i class= "fa fa-calendar-plus-o" title = "Make Schedule"></i></button>
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
        </div>	
    </div>
</div>