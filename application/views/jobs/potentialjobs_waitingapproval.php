<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title  text-blue">Jobs Waiting Approval</h3>
            </div>
             <div class="box-header  with-border">
                 <div class="row">
                    <div  class="col-sm-4 col-md-6">
                        <button id="approvewaitingapprovalbtn"  class="btn btn-info approvebtn" data-targettableid="waitingapprovaltbl">Approve</button>
                        <button id="declinebtn" class="btn btn-info" data-targettableid="waitingapprovaltbl">Decline</button>
                    </div>
                    <div  class="col-sm-8 col-md-6"> 
                       <div class="input-group input-group">
                            <input type="text" class="form-control" placeholder="Search By : Site Address/Suburb/State" ng-change="changeText('waitingapproval')" ng-model="waitingapprovalfilterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters('waitingapproval')"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid('waitingapproval')"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success" title = "Export To Excel" ng-click= "exportToExcel('waitingapproval')"><i class="fa fa-file-excel-o"></i></button>
                            </span>
                       </div>

                   </div>
                </div>
            </div>
            <div class="box-body">
               <div id="waitingapprovaltbl">
                   <div ui-grid ="waitingApprovalJobs" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class="gridwithselect1"></div>
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
  