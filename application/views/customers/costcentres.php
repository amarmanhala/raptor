<!-- Default box -->
<div  id = "CostCenterCtrl"  ng-app="app" ng-controller= "CostCenterCtrl">
    <div class= "box" >
        <div class="box-header with-border">
            <h3 class="box-title text-blue">Cost Centres</h3>
            <div class="pull-right">
                <input type="hidden" id="edit_costcentre" name="edit_costcentre" value="<?php echo $EDIT_COSTCENTRE ? '1':'0';?>">
                <input type="hidden" id="delete_costcentre" name="delete_costcentre" value="<?php echo $DELETE_COSTCENTRE ? '1':'0';?>">
                <?php if($ADD_COSTCENTRE) { ?>
                &nbsp;<button type="button" class="btn  btn-primary" ng-click="addCostCenter();" title="Add"><i class="fa fa-plus"></i></button>
              <?php } ?>

                <!--<button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>-->                
                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                 <?php if($EXPORT_COSTCENTRE){?>
                    <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                <?php } ?>
                <?php  if($IMPORT_COSTCENTRE) { ?>
                    <button type="button" class="btn  btn-info" id="btn_import" title="Import Excel" ng-click="importCostCenter();" ><i class="fa fa-upload"></i></button>
                    <br> <a href="javascript:void(0)"  ng-click="exportImportTemplate()">Import Template</a>&nbsp;
                <?php }?> 
            </div>

        </div>
        <div class= "box-body">
            <div id="mycostcentrestatus"></div>   
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
    
    <div class="modal fade" id ="costcentreModal" tabindex="-1" role ="dialog" aria-labelledby="costcentreModalLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Cost Centre</h4>
            </div>
        <form name ="costcentreform" id ="costcentreform" class="form-horizontal" method ="post"  >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                  
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Cost Centre</label>
                        <div class="col-sm-5" >
                            <input type="text" name="costcentre" id="costcentre" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9" >
                            <input type="text" name="description" id="description" class="form-control" value="" />
                        </div>
                    </div>
                 
                </div>
            </div>
                <div class="modal-footer">
                    <div class="form-group">
                          <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                          <div class="col-sm-9">
                              <input type ="hidden" name ="costcentreid" id ="costcentreid" value =""/> 
                              <input type ="hidden" name ="mode" id ="mode" value =""/> 
                             <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                            &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                           </div>
                    </div> 

                  </div>     

               </form>
        </div>
      </div>
    </div>
    
    
    <div class="modal fade" id ="importcostcentreModal" tabindex="-1" role ="dialog" aria-labelledby="importcostcentreModalLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import Cost Centre</h4>
            </div>
        <form name ="importcostcentreform" id ="importcostcentreform" class="form-horizontal" method ="post" enctype="multipart/form-data" action="<?php echo site_url('customers/importcostcentreexcel') ?>" >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                     
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Upload Excel </label>
                        <div class="col-sm-9">
                            <input type="file" name="importfile" id="importfile" onchange="readExcelURL(this);" />
                        </div>
                    </div>
                    <div class="progress">
                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
                              <span class="sr-only">0% Complete (success)</span>
                            </div>
                    </div>


                     <div id="status"></div>  

                </div>
            </div>
                <div class="modal-footer">
                    <div class="form-group">
                          <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                          <div class="col-sm-9"> 
                             <button type ="submit" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Importing...">Import Excel</button>
                            &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Importing...">Cancel</button>
                           </div>
                    </div> 

                  </div>     

               </form>
        </div>
      </div>
    </div>
    
</div>

