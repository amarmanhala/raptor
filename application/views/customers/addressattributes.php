<!-- Default box -->
<div  id = "AddressAttributeCtrl"  ng-app="app" ng-controller= "AddressAttributeCtrl">
    <div class= "box" >
        <div class="box-header with-border">
            <h3 class="box-title text-blue">Address Attributes</h3>
            <div class="pull-right">
                <input type="hidden" id="edit_addressattributes" name="edit_addressattributes" value="<?php echo $EDIT_ADDRESS_ATTRIBUTE ? '1':'0';?>">
                <input type="hidden" id="delete_addressattributes" name="delete_addressattributes" value="<?php echo $DELETE_ADDRESS_ATTRIBUTE ? '1':'0';?>">
                <?php if($ADD_ADDRESS_ATTRIBUTE) { ?>
                 <button type="button" class="btn  btn-primary" id="createaddressattribute" title="Add"><i class="fa fa-plus"></i></button>
              <?php } ?>

            </div>

        </div>
        <div class= "box-header  with-border"> 
            <div class="row form-horizontal">
                 
                <div  class="col-sm-12 col-md-4 col-md-offset-8"> 
                    <div class="input-group input-group">
                        <input type="text" class="form-control" placeholder="Search By: Attribute Name/Caption" ng-change="changeText()" ng-model="filterOptions.filtertext" id="filtertext" name="filtertext" aria-invalid="false">
                        <span class="input-group-btn">
                            <!--<button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>-->                 
                            <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                            <?php if($EXPORT_ADDRESS_ATTRIBUTE){?>
                                <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>    
        </div>
        <div class= "box-body">
            <div id="myaddressattributestatus"></div>   
             <div>
                <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
            </div>
        </div><!-- /.box-body -->
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay" ng-show="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->
    </div><!-- /.box -->
     
</div>
<?php if($ADD_ADDRESS_ATTRIBUTE || $EDIT_ADDRESS_ATTRIBUTE) { ?>
<?php $this->load->view('customers/addressattribute_modal'); ?>
<?php } ?>

