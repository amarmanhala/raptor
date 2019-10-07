<div class="row" id="finalisedInvoicesCtrl"  ng-controller="finalisedInvoicesCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title  text-blue">Finalised Invoices</h3>
            </div>
             <div class="box-header  with-border">
                 <div class="row">
                    <div  class="col-sm-12 col-md-8">
                        <button id="finalisedapproveinvoicesbtn" class="btn btn-success" data-targettableid="finalisedinvoicestbl">Finalise</button> 
                        <?php if ((isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1")){  ?>
                            <button id="finalisedapproveinvoicesbtn1" class="btn btn-success" data-targettableid="finalisedinvoicestbl">Approve</button> 
                        <?php } ?>
                            <button id="finalisedinvoicebtn" class="btn btn-primary editinvoicebtn" data-targettableid="finalisedinvoicestbl">Edit Invoice</button>    
                            
                
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
               <div id="finalisedinvoicestbl">
                   <div ui-grid ="finalizedInvoices" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class="gridwithselect1"></div>
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
 

 