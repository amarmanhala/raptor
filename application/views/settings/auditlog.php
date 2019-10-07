<div class="row" id="auditLogCtrl"  ng-controller="auditLogCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title">Audit Log</h3>
            </div>
            <div class="box-header  with-border">
                <div class="row">
                    <div class="col-sm-6 col-md-2" style="padding-right: 0px;">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" ng-change = "changeFilters()" ng-model= "filterOptions.fromdate">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2" style="padding-right: 0px;">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" ng-change = "changeFilters()" ng-model= "filterOptions.todate">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class= "col-sm-4 col-md-2" style="padding-right: 0px;">
                            <select id="contacts" name="contacts" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.contact">
                                <option value="">Contacts</option>
                                <?php foreach($contacts as $key=>$value) {  ?>
                                <option value="<?php echo $value['contactid'];?>"><?php echo $value['firstname'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                        <div class= "col-sm-4 col-md-2" style="padding-right: 0px;">
                            <select id="contacts" name="role" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.role">
                                <option value="">Role</option>
                                <?php foreach($role as $key=>$value) {  ?>
                                <option value="<?php echo $value['role'];?>"><?php echo $value['role'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                        <div class= "col-sm-4 col-md-2" style="padding-right: 0px;">
                            <select id="contacts" name="function" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.function">
                                <option value="">Function</option>
                                <?php foreach($functions as $key=>$value) {  ?>
                                <option value="<?php echo $value['functionname'];?>"><?php echo $value['functionname'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    <div class="col-md-2 text-right"> 
                        <div class="input-group input-group">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning btn-sm" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default  btn-sm btn-refresh" title = "Refresh Data" ng-click= "refreshAuditLogGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($export_contactsecurity_auditlog) { ?>
                                <button type="button" class="btn btn-success  btn-sm" title = "Export To Excel" ng-click= "exportToExcel()"><i class="fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
               <div id="auditLogGrid">
                   <div ui-grid ="auditLogGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" ng-show="overlay">
                  <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!--end loading -->
        </div>	
    </div>
</div>