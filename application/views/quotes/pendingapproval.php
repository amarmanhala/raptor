<div class="row" id="PendingApprovalQuotesCtrl"  ng-controller="PendingApprovalQuotesCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">Pending Approval</h3>
                
            </div>
            <div class= "box-header  with-border"> 
                <div class="row form-horizontal">
                    <div  class="col-sm-4 col-md-3 " style="padding-right: 0px;"> 
                    <?php if($this->session->userdata('raptor_role') != 'site contact'){ ?>
                            <?php if($canapprove == 1) {  ?>
                                <button id="approvebtn" class="btn btn-success">Approve</button>
                                <button id="declinebtn" class="btn btn-warning">Decline</button>
                            <?php } ?>
                            <button id="printquotebtn" class="btn btn-primary"><i class="fa fa-print"></i></button>
                    <?php } ?>
                    </div>
                    <div class= "col-sm-4 col-md-2" > 
                        <select id="suburb" name="suburb" class="form-control selectpicker" ng-change = "changeFilters()" ng-model= "filterOptions.suburb">
                            <option value="">All Suburbs</option>
                            <?php foreach($pendingApprovalQuoteSuburb as $key=>$value) {  ?>
                            <option value="<?php echo $value['suburb'];?>"  ><?php echo $value['suburb'];?> (<?php echo $value['count'];?>)</option> 
                            <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-4 col-md-2" > 
                         <select id="sitefm" name="sitefm" class="form-control selectpicker" ng-change = "changeFilters()" ng-model= "filterOptions.sitefm">
                            <option value="">All Site FM</option>
                        <?php foreach($pendingApprovalQuoteSiteFM as $key=>$value) {  ?>
                            <option value="<?php echo $value['sitefm'];?>"  ><?php echo $value['sitefm'];?> (<?php echo $value['count'];?>)</option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-12 col-md-5"> 
                        <div class="input-group input-group">
                            <input type="text" class="form-control" placeholder="Search By: Job ID/<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>/Site Address/Suburb/State" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                            </span>
                        </div>
                    </div>
                </div>    
            </div>
            <div class="box-body">
                <div id="pendingapprovaltbl">
                    <div ui-grid = "gridPendingApproval" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns  ui-grid-selection class="gridwithselect1"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" style = "display:none">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>	
    </div>
</div>
<?php $this->load->view('quotes/quotedeclinemodal');  
$this->load->view('quotes/quoteapprovalmodal');  ?>
