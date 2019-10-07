<div class="row" id="historyInvoicesCtrl"  ng-controller="historyInvoicesCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title  text-blue">Invoice History</h3>
            </div>
             <div class="box-header  with-border">
                 <div class="row">
                     
                    <div  class="col-sm-12 col-md-4 col-md-offset-8"> 
                       <div class="input-group input-group">

                               <input type="text" class="form-control" placeholder="Search By : Site Address/Suburb/State/Invoice No/Gl Code" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                               <span class="input-group-btn">
                                    <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                    <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                    <button type="button" class="btn btn-success" title = "Export To Excel" ng-click= "exportToExcel()"><i class="fa fa-file-excel-o"></i></button>
                               </span>
                       </div>

                   </div>
                </div>
            </div>
            <div class="box-body">
               <div >
                   <div ui-grid ="historyInvoices" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
                </div>
            </div>
<!--             Loading (remove the following to stop the loading)-->
            <div class="overlay" style ="display:none">
                  <i class="fa fa-refresh fa-spin"></i>
            </div>
<!--             end loading -->
        </div>	
    </div>
</div>
 
 