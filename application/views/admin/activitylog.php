 
<div class= "box" id = "activityLogCtrl" ng-app= "app" ng-controller= "activityLogCtrl">
    <div class="box-header  with-border">
        <h3 class="box-title text-blue">Activity Log</h3>
    </div>
    <div class="box-header  with-border">
        <div class="row">
            <div class= "col-md-2" style="padding-right: 0px;">
                <div class= "has-feedback">
                     <input type="text" ng-model="filterOptions.company" id="company" name="company" placeholder="search company.." uib-typeahead="customer as customer.companyname for customer in getCustomer($viewValue)"    typeahead-on-select="onCustomerSelect($item, $model, $label)" typeahead-loading="loadingCustomer"   class="form-control" ng-change="changeCustomerText()" />
                    <span class="form-control-feedback" ><i ng-show="loadingCustomer" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCustomer" ></i></span>
                </div>
            </div>
            <div class= "col-md-2" style="padding-right: 0px;">
                <div class= "has-feedback">
                     <input type="text" ng-model="filterOptions.contact" id="contact" name="contact" placeholder="search Contact.." uib-typeahead="contact as contact.firstname for contact in getContact($viewValue)"    typeahead-on-select="onContactSelect($item, $model, $label)" typeahead-loading="loadingContact"   class="form-control" ng-change="changeContactText()" />
                    <span class="form-control-feedback" ><i ng-show="loadingContact" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingContact" ></i></span>
                </div>
                 
            </div>
            <div class="col-md-2" style="padding-right: 0px;">
                <div class="input-group">
                    <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" ng-change = "changeFilters()" ng-model= "filterOptions.fromdate">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-2" style="padding-right: 0px;">
                <div class="input-group">
                    <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" ng-change = "changeFilters()" ng-model= "filterOptions.todate">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>

            
            <div class= "col-md-2" style="padding-right: 0px;">
                <select id="success" name="success" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.success">
                    <option value="1">Success</option>
                    <option value="0">Fail</option>
                </select>
            </div>
            <div class="col-md-2 text-right"> 
                <div class="input-group input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-warning btn-sm" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                        <button type="button" class="btn btn-default  btn-sm btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                        <button type="button" class="btn btn-success  btn-sm" title = "Export To Excel" ng-click= "exportToExcel()"><i class="fa fa-file-excel-o"></i></button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
       <div id="auditLogGrid">
           <div ui-grid ="activityLogGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
        </div>
    </div>
    <!-- Loading (remove the following to stop the loading)-->
    <div class="overlay" ng-show="overlay">
          <i class="fa fa-refresh fa-spin"></i>
    </div>
    <!--end loading -->
</div>	
    