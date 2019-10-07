<div class="row" id="finalapprovalInvoicesCtrl"  ng-controller="finalapprovalInvoicesCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                 <?php if ((isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1")) { ?>
                <h3 class="box-title text-blue">Invoices for Final Approval</h3>
            <?php  }else{ ?>
                <h3 class="box-title text-blue">Invoices for Approval</h3>
            <?php } ?> 
            </div>
             <div class="box-header  with-border">
                 <div class="row">
                    <div  class="col-sm-12 col-md-8" style="padding-right: 0px;">
                        <button id="finalapprovalqueryinvoicebtn" class="btn btn-warning queryinvoicebtn" data-targettableid="finalapprovalinvoicestbl">Query Invoice</button>
                        <button id="finalapprovaleditinvoicebtn" class="btn btn-primary editinvoicebtn" data-targettableid="finalapprovalinvoicestbl">Edit Invoice</button>
                        <button id="finalapproveinvoicesbtn" class="btn btn-success finalapproveinvoicesbtn" data-targettableid="finalapprovalinvoicestbl">Approve Invoices</button>
                        <?php if($create_batchinvoice) { ?>
                        <button id="finalapprovalbatchinvoicesbtn" class="btn btn-info batchinvoicesbtn" data-targettableid="finalapprovalinvoicestbl">Batch Invoices</button>
                        <?php } ?>
                        <button id="finalapprovalcheckbudgetssbtn" class="btn btn-default checkbudgetbtn" data-targettableid="finalapprovalinvoicestbl">Check Budgets</button>
                    </div>
                    <div  class="col-sm-12 col-md-4"> 
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
               <div id="finalapprovalinvoicestbl">
                   <div ui-grid ="finalapprovalInvoices" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class="gridwithselect1"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" style ="display:none">
                  <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!--end loading -->
        </div>	
    </div>
</div>


 