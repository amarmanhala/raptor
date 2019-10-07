<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <div class="row">
                    <div class= "col-sm-6 col-md-6" style="padding-right: 0px;margin-bottom: 15px;">
                        <label class= "control-label">Customer:</label>
                        <div class= "has-feedback">
                             <input type="text" ng-model="filterOptions.customer" id="customer" name="customer" placeholder="search company.." uib-typeahead="customer as customer.companyname for customer in getCustomer($viewValue)"    typeahead-on-select="onCustomerSelect($item, $model, $label,'auditlog')" typeahead-loading="loadingCustomer"   class="form-control" ng-change="changeCustomerText('auditlog')" />
                            <span class="form-control-feedback" ><i ng-show="loadingCustomer" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCustomer" ></i></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-md-2" style="padding-right: 0px;">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" ng-change = "changeAuditLogFilters()" ng-model= "filterOptions.fromdate">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-2" style="padding-right: 0px;">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" ng-change = "changeAuditLogFilters()" ng-model= "filterOptions.todate">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class= "col-sm-6 col-md-4" style="padding-right: 0px;">
                            <select id="contacts" name="rulename" class="form-control" ng-change = "changeAuditLogFilters()" ng-model= "filterOptions.rulename">
                                <option value="">Setting</option>
                                <?php foreach($customer_rulenames as $key=>$value) {  ?>
                                <option value="<?php echo $value['rulename'];?>"><?php echo $value['caption'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    <div class="col-md-4 text-right"> 
                        <div class="input-group input-group">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning btn-sm" title = "Clear Filter" ng-click= "clearAuditLogFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default  btn-sm btn-refresh" title = "Refresh Data" ng-click= "refreshAuditLogGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success  btn-sm" title = "Export To Excel" ng-click= "exportToExcel()"><i class="fa fa-file-excel-o"></i></button>
                              
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
               <div id="auditLogGrid">
                   <div ui-grid ="portalAuditLogGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
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