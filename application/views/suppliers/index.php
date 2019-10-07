<!-- Default box -->
<div class= "box" ng-app="app" id = "SuppliersCtrl"  ng-controller= "SuppliersCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">My Suppliers</h3>
        <div class= "pull-right">
            
            <input type="hidden" id="allow_etp_login" name="allow_etp_login" value="<?php echo $ALLOW_ETP_LOGIN ? '1':'0';?>">
            <input type="hidden" id="edit_supplier" name="edit_supplier" value="<?php echo $EDIT_SUPPLIER ? '1':'0';?>">
            <input type="hidden" id="delete_supplier" name="delete_supplier" value="<?php echo $DELETE_SUPPLIER ? '1':'0';?>">
            <?php if($ADD_SUPPLIER) { ?>
            <a class= "btn btn-primary btn-sm" href= "<?php echo site_url('suppliers/add');?>" title = "Add New Supplire"><i class= "fa  fa-plus"></i></a> 
            <?php } ?>
        </div>
    </div>
    <div class= "box-header  with-border"> 
            <div class= "row">
                
                <div class= "col-sm-4 col-md-2">
                    <label class= "control-label">Type</label>
                    <select class= "form-control selectpicker" name = "typeid" id = "typeid" ng-model= "filterOptions.typeid" ng-change="changeContactFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($suppliertypes as $key => $value) { ?>
                            <option value = "<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>

                    </select>
                </div>
                <div class= "col-sm-4 col-md-2">
                    <label class= "control-label">State</label>
                    <select class= "form-control selectpicker" name = "state" id = "state" ng-model= "filterOptions.state" ng-change="changeContactFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($states as $key => $value) { ?>
                            <option value = "<?php echo $value['abbreviation'];?>" <?php if (set_value('shipstate') == $value['abbreviation']) echo "selected";?>><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>

                    </select>
                </div>
                <div class= "col-sm-4 col-md-2">
                <label class= "control-label">Trade</label>
                
                    <select id = "tradeids" name = "tradeids[]" class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" ng-model= "filterOptions.tradeids" ng-change="changeContactFilter()">

                        <?php foreach ($se_trades as $key => $value) {?>
                            <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_trade_name'] ;?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-4 col-md-2">
                    <label class= "control-label">Status</label>
                    <select name = "status" id = "status" class= "form-control selectpicker" ng-model= "filterOptions.status" ng-change="changeContactFilter()">
                        <option value = ''>All</option>
                        <option value = '1'>Active</option>
                        <option value = '0'>Inactive</option> 
                    </select>
                </div>
                <div  class="col-sm-12 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control" id = "externalfiltercomp" ng-change = "changeContactFilter()" placeholder= "Search..." ng-model="filterOptions.filtertext"  aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearContactFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeContactFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($EXPORT_SUPPLIERS) { ?>
                                    <button type = "button" class= "btn btn-success" ng-click="exportToExcel()" title = "Export To Excel"><i class= "fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                    </div>
                    
                </div>
                 

            </div>
 
    </div>

    <div class= "box-body">
        <div id="mysupplierstatus"></div> 
    <?php 
           if($this->session->flashdata('success')) 
           {
               echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
           }
       ?>
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
