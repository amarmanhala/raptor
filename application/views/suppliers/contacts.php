<div class= "row" id = "ContactCtrl"  ng-controller= "ContactCtrl">
    <div class= "col-md-12">
        <!-- Default box -->
        <div class= "box" >
            <div class= "box-header with-border">
              <h3 class= "box-title text-blue">Contacts</h3>
                <div class= "pull-right">
                    <input type="hidden" id="allow_etp_login" name="allow_etp_login" value="<?php echo $ALLOW_ETP_LOGIN ? '1':'0';?>">
                    <input type="hidden" id="edit_contact" name="edit_contact" value="<?php echo $EDIT_SUPPLIER_CONTACT ? '1':'0';?>">
                    <input type="hidden" id="delete_contact" name="delete_contact" value="<?php echo $DELETE_SUPPLIER_CONTACT ? '1':'0';?>">
                    <?php if($ADD_SUPPLIER_CONTACT) { ?>
                     &nbsp;<button type="button" class="btn  btn-primary" ng-click="addContact();" title="Add New Contact"><i class="fa fa-plus"></i></button> 
                   <?php } ?>
                </div>
            </div>
            <div class= "box-header  with-border">
                <div class= "row">
                    <div class= "col-sm-6 col-md-2">
                        <label class= "control-label">State</label>
                        <select class= "form-control selectpicker" name = "state" id = "state" ng-model= "filterOptions.state" ng-change="changeContactFilter()">
                            <option value = ''>All</option>
                            <?php foreach ($states as $key => $value) { ?>
                                <option value = "<?php echo $value['abbreviation'];?>" <?php if (set_value('shipstate') == $value['abbreviation']) echo "selected";?>><?php echo $value['abbreviation'];?></option> 
                            <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-6 col-md-3">
                        <label class= "control-label">Trade</label>
                        <select id = "tradeid" name = "tradeid[]" class= "form-control selectpicker" data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" ng-model= "filterOptions.tradeid" ng-change="changeContactFilter()">
                            <option value = ''>All</option>
                            <?php foreach ($se_trades as $key => $value) {?>
                                <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_trade_name'] ;?></option> 
                            <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-12 col-md-5 col-md-offset-2">
                        <label class="control-label">&nbsp;</label>
                        <div class="input-group input-group">
                            <input type="text" class="form-control" id = "externalfiltercomp" ng-change = "changeContactFilter()" placeholder= "Search..." ng-model="filterOptions.filtertext"  aria-invalid="false">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearContactFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeContactFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($EXPORT_SUPPLIER_CONTACT) { ?>
                                    <button type = "button" class= "btn btn-success" ng-click="exportToExcel()" title = "Export To Excel"><i class= "fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class= "box-body">
                <div>
                    <div ui-grid = "contactGrids" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
                </div>
            </div><!-- /.box-body -->
             <!-- Loading (remove the following to stop the loading)-->
             <div class= "overlay" ng-show="overlay">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div><!-- /.box -->	
        
        <?php $this->load->view('suppliers/suppliercontactmodal');?>
    </div>
</div>