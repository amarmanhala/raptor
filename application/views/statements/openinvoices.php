<div class="row" id="openInvoicesCtrl"  ng-controller="openInvoicesCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title  text-blue">Open Invoices</h3>
            </div>
             <div class="box-header  with-border">
                 <div class="row">
                    <div class="col-sm-4 col-md-8">
                        <button id="currentstatementbtn" data-customerid="<?php echo $this->session->userdata('raptor_customerid'); ?>" data-ajaxurl="<?php echo $this->config->item('client_portal').'contractor/ajxswitch.php'; ?>" data-pdfurl="<?php echo base_url().'../infomaniacDocs/arstatements/statement_'.$this->session->userdata('raptor_customerid').'.pdf'; ?>" class="btn btn-info" data-targettableid="openinvoicestbl">Current Statement</button>
                        <button id="emailinvoicesbtn" class="btn btn-info" data-targettableid="openinvoicestbl">Email Invoices</button>
                    </div>
                    <div class="col-sm-8 col-md-4"> 
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
               <div id="openinvoicestbl">
                   <div ui-grid ="openInvoices" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class="gridwithselect1"></div>
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
  