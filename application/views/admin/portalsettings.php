<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">Portal Settings</h3>
                 
            </div>
            <div class="box-header  with-border">
                <div class="row">
                    <div class= "col-sm-9 col-md-6">
                        <label class= "control-label">Customer:</label>
                        <div class= "has-feedback">
                             <input type="text" ng-model="portalSettingsfilterOptions.customer" id="customer" name="customer" placeholder="search company.." uib-typeahead="customer as customer.companyname for customer in getCustomer($viewValue)"    typeahead-on-select="onCustomerSelect($item, $model, $label,'portalsetting')" typeahead-loading="loadingCustomer"   class="form-control" ng-change="changeCustomerText('portalsetting')" />
                            <span class="form-control-feedback" ><i ng-show="loadingCustomer" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCustomer" ></i></span>
                        </div>
                    </div>
                    <div class= "col-sm-3 col-md-6 text-right">
                        <label class= "control-label">&nbsp;</label>
                        <div >
                             
                            <div class="input-group input-group">
                                <input type="text" class="form-control" placeholder="Search ....." ng-change="changeText()" ng-model="portalSettingsfilterOptions.filtertext" id="filtertext" name="filtertext" aria-invalid="false">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                    <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshPortalSettingsGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>

                                    <button type="button" class="btn btn-success"  ng-click="exportPortalSettings()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>

                                </span>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
               <div id="portalsettingsgrid">
                   <div ui-grid ="portalSettingsGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
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