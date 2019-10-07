<div class="row" id="batchHistoryCtrl"  ng-controller="batchHistoryCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">Batch History</h3>
            </div>
             
            <div class="box-header  with-border">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <button id="printbatchinvoice" class="btn btn-primary" data-targettableid="batchhistorytbl">Print Batch</button>
                        <button id="exportbatchinvoice" class="btn btn-success" data-targettableid="batchhistorytbl">Export Batch</button>
                        <button id="emailbatchinvoice" class="btn btn-warning" data-targettableid="batchhistorytbl">Email Batch</button>
                    </div>
                    <div class="col-sm-12 col-md-3 form-group"  style="padding-right: 0px;">
                        <label class="control-label col-xs-2">To:</label>
                        <div class="col-xs-10">
                            <input type="text" class="form-control" name="tobatchrecipient" id="tobatchrecipient" placeholder="Recipient" />
                        </div>
                        
                    </div>
                    <div  class="col-sm-12 col-md-4"> 
                       <div class="input-group input-group">

                               <input type="text" class="form-control" placeholder="Search By : Batch Id/Batch Date/Created By/Recipients" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
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
               <div id="batchhistorytbl">
                   <div ui-grid ="batchHistory" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class="gridwithselect1"></div>
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
  
 

 