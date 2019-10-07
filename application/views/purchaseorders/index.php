<div ng-app="app" id = "PurchaseOrdersCtrl"  ng-controller= "PurchaseOrdersCtrl">
<!-- Default box -->
<div class= "box" >
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Purchase Orders</h3>
        <div class= "pull-right">
              <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
        <input type="hidden" name="custordref2_label" id="custordref2_label" value="<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>"/>
            <input type="hidden" id="edit_purchaseorder" name="edit_purchaseorder" value="<?php echo $EDIT_PURCHASE_ORDER ? '1':'0';?>">
            <input type="hidden" id="delete_purchaseorder" name="delete_purchaseorder" value="<?php echo $DELETE_PURCHASE_ORDER ? '1':'0';?>">
            <?php if($ADD_PURCHASE_ORDER) { ?>
             &nbsp;<button type="button" class="btn  btn-primary" ng-click="addPurchaseOrder();" title="Add New Purchase Order"><i class="fa fa-plus"></i></button> 
            <?php } ?>
        </div>
    </div>
    <div class= "box-header  with-border" id="filterform"> 
            <div class= "row">
                <div class="col-sm-6 col-md-2" style="padding-right: 0px">
                    <label class= "control-label">From Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" ng-change = "changeFilter()" ng-model= "filterOptions.fromdate">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-2"  style="padding-right: 0px">
                    <label class= "control-label">To Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" ng-change = "changeFilter()" ng-model= "filterOptions.todate">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                 
                <div class= "col-sm-6 col-md-2"  style="padding-right: 0px">
                    <label class= "control-label">GL Code</label>
                    <select class= "form-control selectpicker" name = "glcode" id = "glcode" ng-model= "filterOptions.glcode" ng-change="changeFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($glcodes as $key => $value) { ?>
                            <option value = "<?php echo $value['accountname'];?>" ><?php echo $value['accountname'];?></option> 
                        <?php } ?>

                    </select>
                </div>
                
                <div class= "col-sm-6 col-md-2"  style="padding-right: 0px">
                    <label class= "control-label">Status</label>
                    <select name = "status" id = "status" class= "form-control selectpicker" ng-model= "filterOptions.status" ng-change="changeFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($status as $key => $value) { ?>
                            <option value = "<?php echo $value['id'];?>" ><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div  class="col-sm-12 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control"  ng-change = "changeFilter()" placeholder= "Search PO Number" ng-model="filterOptions.filtertext"  aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($EXPORT_PURCHASE_ORDER) { ?>
                                    <button type = "button" class= "btn btn-success" ng-click="exportToExcel()" title = "Export To Excel"><i class= "fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                    </div>
                    
                </div>
            </div>
           <div class="row" style="margin-top: 15px;">
                <div  class="col-sm-4">
                     <?php if($EDIT_PURCHASE_ORDER) { ?>
                   <button type ="button" name ="btncancelpo" id ="btncancelpo" class="btn btn-danger" title ="Cancel PO"><span class="glyphicon glyphicon-ban-circle" title ="Cancel PO"></span></button>
                   <button type ="button" name ="btnlockpo" id ="btnlockpo" class="btn btn-warning" title ="Lock PO"><span class="glyphicon glyphicon-lock" title ="Lock PO"></span></button>
                   <?php } ?>
                   <button type ="button" name ="btnchartpo" id ="btnchartpo" class="btn btn-success" title ="Chart PO"><span class="glyphicon glyphicon-barcode" title ="Chart PO"></span></button>
                   <button type ="button" name ="btnreportpo" id ="btnreportpo" class="btn btn-primary" title ="Report PO"><span class="glyphicon glyphicon-print" title ="Report PO"></span></button>
                   <button type ="button" name ="btnrecalculate" id ="btnrecalculate" class="btn btn-default"  title ="Recalculate Totals"><span class="fa fa-retweet" title ="Recalculate Totals" style="font-size: 16px;"></span></button>
                </div>
               <div  class="col-sm-8">
                   <div class= "row">
                        <div class="col-sm-6" > 
                            <label class="control-label">Total remaining exc. WIP:&nbsp;<small id="totlremainexc" class="label"><?php echo format_amount(0); ?></small></label>
                        </div>
                        <div class="col-sm-6"> 
                            <label class="control-label">Total remaining inc. WIP:&nbsp;<small id="totlremaininc" class="label"><?php echo format_amount(0); ?></small></label>
                        </div>
                   </div>
               </div>

           </div> 
 
    </div>

    <div class= "box-body">
        <div id="purchaseorderstatus"></div> 
    <?php 
           if($this->session->flashdata('success')) 
           {
               echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
           }
       ?>
        <div id="contactGrid">
            <input type="hidden" id="selectedponumber" name="selectedponumber" value="">
            <div ui-grid = "purchaseorderGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns   ui-grid-selection class= "gridautoheight"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
    <?php $this->load->view('purchaseorders/customerpoaddeditmodal');?>
</div><!-- /.box -->	

    <div class= "box unallocatedjobs" style="display: none;">
        <div class= "box-header with-border">
            <h3 class= "box-title text-blue">Un-Allocated Jobs to Purchase order</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class= "box-body">
            <div class= "row">
                <div class="col-xs-9 col-sm-6 col-md-3" > 
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="loggeddate" name="loggeddate" readonly="readonly" placeholder="Logged Date" ng-change = "changePoFilter()" ng-model= "loggeddate">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div  class="col-xs-3 col-sm-6 col-md-9 "> 
                    <div class="text-right">
                        <button type ="button" name ="btnaddjob" id ="btnaddjob" class="btn btn-primary" title ="Add Job To PO"><span class="fa fa-plus" title ="Add Job To PO"></span></button>
                    </div>
                    
                </div>
            </div>
            <hr>
            <div ui-grid = "unAllocatedJobsGrid"  ui-grid-auto-resize ui-grid-resize-columns   ui-grid-selection class= "gridautoheight"></div>

        </div> <!--/.box-body 
          Loading (remove the following to stop the loading) -->
         <div class= "overlay" ng-show="overlay">
              <i class= "fa fa-refresh fa-spin"></i>
        </div>
<!--         end loading  -->
    </div>
<!-- /.box -->
    <div class= "box allocatedjobs" style="display: none;">
        <div class= "box-header with-border">
            <h3 class= "box-title text-blue">Allocated Jobs to Purchase order : <span class="pon"></span></h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class= "box-body"> 
            <div class= "row">
                <div class="col-xs-4 col-sm-3 col-md-2" > 
                    <label class="control-label">WIP:&nbsp;<small class="label label-danger"><?php echo RAPTOR_CURRENCY_SYMBOL; ?>&nbsp;{{allocatedjobsummery.WIP}}</small></label>
                </div>
                <div class="col-xs-4 col-sm-3 col-md-2" > 
                    <label class="control-label">Invoiced:&nbsp;<small class="label label-danger"><?php echo RAPTOR_CURRENCY_SYMBOL; ?>&nbsp;{{allocatedjobsummery.Invoiced}}</small></label>
                </div>
                <div class="col-xs-4 col-sm-3 col-md-2" > 
                    <label class="control-label">Total:&nbsp;<small class="label label-danger"><?php echo RAPTOR_CURRENCY_SYMBOL; ?>&nbsp;{{allocatedjobsummery.Total}}</small></label>
                </div>
                <div class="col-xs-4 col-sm-3 col-md-2" > 
                    <label class="control-label">POValue:&nbsp;<small class="label label-danger">{{allocatedjobsummery.POValue}}</small></label>
                </div>
                <div class="col-xs-4 col-sm-3 col-md-2" > 
                    <label class="control-label">Remaining:&nbsp;<small class="label label-danger">{{allocatedjobsummery.Remaining}}</small></label>
                </div>
                <div  class="col-xs-4 col-sm-3 col-md-2"> 
                    <div class="text-right">
                        <button type ="button" name ="btnremovejob" id ="btnremovejob" class="btn btn-danger" title ="Remove Job To PO"><span class="fa fa-remove" title ="Remove Job To PO"></span></button>
                    </div>
                    
                </div>
            </div>
            <hr>
        
            <div ui-grid = "allocatedJobsGrid"   ui-grid-auto-resize ui-grid-resize-columns   ui-grid-selection class= "gridautoheight"></div>

        </div> <!--/.box-body 
          Loading (remove the following to stop the loading)-->
         <div class= "overlay" ng-show="overlay">
              <i class= "fa fa-refresh fa-spin"></i>
        </div>
<!--         end loading  -->
    </div>
<!-- /.box -->
 
</div>
<div class="modal fade" id="poChartModal"   role="dialog" aria-labelledby="poChartModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog  modal-lg" role="document" >
    <div class="modal-content">
 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Purchase Order</h4> 
      </div>
        
      <div class="modal-body"> 
                <div class= "row">
                    <div class="col-sm-6 col-md-3" style="padding-right: 0px">
                        <label class= "control-label">From Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker chartfilter" id="chartfromdate" name="chartfromdate" readonly="readonly" placeholder="From" >
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3"  style="padding-right: 0px">
                        <label class= "control-label">To Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control datepicker chartfilter" id="charttodate" name="charttodate" readonly="readonly" placeholder="To" >
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>

                    <div class= "col-sm-6 col-md-3"  style="padding-right: 0px">
                        <label class= "control-label">GL Code</label>
                        <select class= "form-control selectpicker chartfilter"  id="glcode" name="glcode" >
                            <option value = ''>All</option>
                            <?php foreach ($glcodes as $key => $value) { ?>
                                <option value = "<?php echo $value['accountname'];?>" ><?php echo $value['accountname'];?></option> 
                            <?php } ?>

                        </select>
                    </div>
                
                
                <div  class="col-sm-6 col-md-3">
                    <label class="control-label">&nbsp;</label>
                    <div class="text-right">
                        <button type="button" class="btn btn-warning btnClearChartFilter" title = "Clear Filter" ><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                        <button type="button" class="btn btn-default btn-refresh btnRefreshChartFilter" title = "Refresh Data"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 loading-div text-center" style="display:none" >
                    <img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" />
                </div>
                <div class="col-md-12 chartdiv">
                    <div class="box box-solid" style="overflow-x: auto;overflow-y: auto;">
                     
                        <div class="chart" id = "barChart"></div>
                    </div>
                </div> 
                <div class="col-md-12 chartdiv">
                    <div class="box box-solid" style="overflow-x: auto;overflow-y: auto;">
                        <div class= "chart" id="lineChart" ></div><!-- /.box-body -->
                    </div>
                </div>  
            </div>
           
        </div>    
       <div class="modal-footer">
          <button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
      </div>
    
    </div>
  </div>
</div>