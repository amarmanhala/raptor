<!-- Default box -->
<div class= "box" ng-app="app" id = "CustomerSummaryCtrl"  ng-controller= "CustomerSummaryCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Customer Contacts</h3>
    </div>
    <div class= "box-header  with-border"> 
        <div class="row">
            <div class= "col-sm-9 col-md-6">
     
                <div class="input-group">
                    <div class= "has-feedback">
                        <input type="text" ng-model="filterOptions.company" id="company" name="company" placeholder="search Customer.." uib-typeahead="customer as customer.companyname for customer in getCustomer($viewValue)"  typeahead-editable="false"    typeahead-on-select="onCustomerSelect($item, $model, $label)" typeahead-loading="loadingCustomer"   class="form-control" ng-change="changeCustomerText()" />
                       <span class="form-control-feedback" ><i ng-show="loadingCustomer" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCustomer" ></i></span>
                    </div>
                    <div class="input-group-addon">
                        <a href="javascript:void(0);" ng-click="addCustomerModule()" ><i  class="fa fa-plus"></i></a>
                    </div>
                </div><!-- /.input group -->
                
            </div>
            <div class= "col-sm-3 col-md-6 text-right">
                <button type="button" id="btndeactivated" class="btn btn-danger" title="Disable access to portal"><i class="fa fa-ban"></i></button>
                <button type="button" id="btnactivated" class="btn btn-success"  title="Enable access to portal"><i class="fa fa-play-circle-o"></i></button>
            </div>
        </div>
    </div>

    <div class= "box-body">

        <div id="contactGrid">
            <div ui-grid = "contactGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->
 
