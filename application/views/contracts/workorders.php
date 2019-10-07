<div class= "row"  id = "WorkOrdersCtrl" ng-controller= "WorkOrdersCtrl">
    <div class= "col-md-12">
        <!-- Default box -->
        <div class= "box" >
            <div class= "box-header with-border">
              <h3 class= "box-title text-blue">Work Order Rules</h3>
                <div class= "pull-right text-right">
                    <button type="button" class="btn  btn-primary" ng-click="addSiteOrder();" title="Create work order"><i class="fa fa-plus"></i></button> 
                     <button type="button" class="btn  btn-info" id="btn_import" title="Import Excel" ng-click="importSiteOrder();" ><i class="fa fa-upload"></i></button>
                    <br> <a href="javascript:void(0)"  ng-click="exportImportTemplate()">Import Template</a>&nbsp;
                </div>
            </div>
            <div class= "box-body" > 
                <div class= "form-horizontal">
                    <div class= "form-group">
                        <label for= "name" class= "col-sm-3 control-label">Word Order Methods</label>
                        <div class= "col-md-4 col-sm-6">
                            <select name = "workordermethodid" id = "workordermethodid" class= "form-control" onchange="updateContract()">
                                <option value = ''>-Select-</option>
                            <?php foreach ($workorderMethods as $key => $value) { ?>
                                  <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class= "box" >
            <div class= "box-header with-border">
                <h3 class= "box-title text-blue">Site Parent Job Orders</h3>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                    <div class= "col-sm-4 col-md-3" >
                        <select class= "form-control" name = "state" id = "state" ng-change = "changeSiteParentJobOrderFilters()" ng-model= "siteParentJobOrderFilter.state" >
                            <option value = ''>All State</option>
                        <?php foreach($states as $val) { ?>
                            <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-8 col-md-9">
                        <div class="input-group input-group">
                            <input type = "text" id = "externalfiltercomp" placeholder="Search........."  ng-change = "changeSiteParentJobOrderText()" class= "form-control" ng-model= "siteParentJobOrderFilter.filtertext" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearSiteParentJobOrderFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshSiteParentJobOrderGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class= "btn btn-success" title = "Export To Excel" ng-click="exportSiteParentJobOrderToExcel()"><i title="Export To Excel"  class= "fa fa-file-excel-o"></i></button> 
                            </span>
                        </div>
                    </div>
                </div>    
            </div>
            <div class= "box-body">
               
               <div id="siteParentJobOrderGrid">
                   <div ui-grid = "siteParentJobOrderGrid" ui-grid-edit ui-grid-cellNav ui-grid-row-edit ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridautoheight"></div>
               </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" ng-show="overlay">
               <i class= "fa fa-refresh fa-spin"></i>
           </div>
           <!-- end loading -->
        </div><!-- /.box -->	
        <div class= "box" >
            <div class= "box-header with-border">
                <h3 class= "box-title text-blue">Site Orders</h3>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                    <div class= "col-sm-12 col-md-4" >
                        <label class="radio-inline">
                            <input type="radio" name="filterby" value="all" ng-model="siteOrderFilter.filterby" ng-change = "changeSiteOrderFilters2()" >View All
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="filterby" value="site" ng-model="siteOrderFilter.filterby" ng-change = "changeSiteOrderFilters2()" >View Site
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="filterby" value="period" ng-model="siteOrderFilter.filterby" ng-change = "changeSiteOrderFilters2()" >View Period
                        </label>
                    </div> 
                    <div class= "col-sm-3 col-md-2">
                        <select class= "form-control" name = "month" id = "month" ng-change = "changeSiteOrderFilters1()" ng-model= "siteOrderFilter.month" >
                            <option value="">Select Month</option>
                            <?php for($i = 1; $i <= 12; $i++) { ?>
                            <option value="<?php echo $i;?>"><?php echo date('M', strtotime(date('Y-'.$i.'-1'))); ?></option>
                                <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-3 col-md-2">
                        <select class= "form-control" name = "year" id = "year" ng-change = "changeSiteOrderFilters1()" ng-model= "siteOrderFilter.year" >
                            <option value="">Select Year</option>
                            <?php for($i = 2016; $i <= (date('Y')+2); $i++) { ?>
                                <option value="<?php echo $i;?>"><?php echo $i;?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-3 col-md-4">
                        <div class="input-group input-group">
                            <select class= "form-control" name = "serviceid" id = "serviceid" ng-change = "changeSiteOrderFilters()" ng-model= "siteOrderFilter.serviceid" >
                                <option value="">All Service</option>
                                <?php foreach ($contractServices as $key => $value) { ?>
                                  <option value="<?php echo $value['id'];?>" ><?php echo $value['name'];?></option> 
                                <?php } ?>
                               
                            </select>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearSiteOrderFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshSiteOrderGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class= "btn btn-success" title = "Export To Excel" ng-click="exportSiteOrderToExcel()"><i title="Export To Excel"  class= "fa fa-file-excel-o"></i></button> 
                            </span>
                        </div>
                         
                    </div>
                </div>    
            </div>
            <div class= "box-body">
               <div id="siteOrderGrid">
                   <div ui-grid = "siteOrderGrid" ui-grid-edit ui-grid-cellNav ui-grid-row-edit ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class= "gridautoheight"></div>
               </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" ng-show="overlay1">
               <i class= "fa fa-refresh fa-spin"></i>
           </div>
           <!-- end loading -->
        </div><!-- /.box -->	
        
        <div class="modal fade" id ="CreateWorkOrderModal" tabindex="-1" role ="dialog" aria-labelledby="CreateWorkOrderModalLabel" data-backdrop="static" data-keyboard ="false">
            <div class="modal-dialog" role ="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Create work order</h4>
                  </div>
              <form name ="CreateWorkOrderform" id ="CreateWorkOrderform" class="form-horizontal" method ="post"  >

                  <div class="modal-body">
 
                        <div class="status"></div>
                        <div class="form-group">
                            <label for="input" class="col-sm-3 control-label">Service</label>
                            <div class="col-sm-5" >
                                <select class= "form-control" name = "serviceid" id = "serviceid"    >
                                    <option value="">Select Service</option>
                                <?php foreach ($contractServices as $key => $value) { ?>
                                  <option value="<?php echo $value['id'];?>" ><?php echo $value['name'];?></option> 
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                         
                        
                        <div class="form-group">
                            <label for="input" class="col-sm-3 control-label">Month</label>
                            <div class="col-sm-5" >
                                <select class= "form-control" name = "month" id = "month" >
                                    <option value="">Select Month</option>
                                    <?php for($i = 1; $i <= 12; $i++) { ?>
                                    <option value="<?php echo $i;?>"><?php echo date('M', strtotime(date('Y-'.$i.'-1'))); ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-3 control-label">Year</label>
                            <div class="col-sm-5" >
                                <select class= "form-control" name = "year" id = "year">
                                    <option value="">Select Year</option>
                                    <?php for($i = 2016; $i <= (date('Y')+2); $i++) { ?>
                                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                       
                  </div>
                      <div class="modal-footer">
                          <div class="form-group">
                                <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                                <div class="col-sm-9">
                                    <input type ="hidden" name ="contractid" id ="contractid" value ="<?php echo $contract['id'];?>"/> 
                                   <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                                  &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                                 </div>
                          </div> 

                        </div>     

                     </form>
              </div>
            </div>
        </div>
    
    
    <div class="modal fade" id ="importsiteorderModal" tabindex="-1" role ="dialog" aria-labelledby="importsiteorderModalLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import Site Order</h4>
            </div>
        <form name ="importsiteorderform" id ="importsiteorderform" class="form-horizontal" method ="post" enctype="multipart/form-data" action="<?php echo site_url('contracts/importsiteorderexcel') ?>" >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                     <div class="form-group">
                            <label for="input" class="col-sm-3 control-label">Service</label>
                            <div class="col-sm-5" >
                                <select class= "form-control" name = "serviceid" id = "serviceid"    >
                                    <option value="">Select Service</option>
                                <?php foreach ($contractServices as $key => $value) { ?>
                                  <option value="<?php echo $value['id'];?>" ><?php echo $value['name'];?></option> 
                                <?php } ?>
                                </select>
                            </div>
                    </div>
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
                                <input type ="hidden" name ="contractid" id ="contractid" value ="<?php echo $contract['id'];?>"/> 
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
</div>