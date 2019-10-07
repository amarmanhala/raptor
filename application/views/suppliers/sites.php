<div class= "row">
    <div class= "col-md-12">
        <!-- Default box -->
        <div class= "box"  id = "SupplierSitesCtrl" ng-controller= "SupplierSitesCtrl">
            <div class= "box-header with-border">
              <h3 class= "box-title text-blue">Sites</h3>
                <div class= "pull-right">
                    <input type="hidden" id="edit_site" name="edit_site" value="<?php echo $EDIT_SUPPLIER_SITE ? '1':'0';?>">
                    <input type="hidden" id="delete_site" name="delete_site" value="<?php echo $DELETE_SUPPLIER_SITE ? '1':'0';?>">
                    <?php if($ADD_SUPPLIER_SITE) { ?>
                     &nbsp;<button type="button" class="btn  btn-primary" ng-click="addSite();" title="Add a new site"><i class="fa fa-plus"></i></button> 
                   <?php } ?>
                </div>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                    <div class= "col-sm-6 col-md-4"  >
                        <button type = "button"  class= "btn btn-info" title = "Show on Map" ng-click="showOnMap()"><i class= "fa fa-map-marker" title = "Show on Map"></i>&nbsp;&nbsp;Show on Map</button>
                    </div>
                    <div class= "col-sm-6 col-md-3" >
                        <select class= "form-control selectpicker" name = "state" id = "state" ng-change = "changeFilters()" ng-model= "addressFilter.state" >
                            <option value = ''>All State</option>
                            <?php foreach($states as $val) { ?>
                                <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-12 col-md-5">
                        <div class="input-group input-group">
                            <input type = "text" id = "externalfiltercomp" placeholder="Search........."  ng-change = "changeText()" class= "form-control" ng-model= "addressFilter.filtertext" />
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                    <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshAddressGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                    <?php if($EXPORT_SUPPLIER_SITE) { ?>
                                    <button type="button" class= "btn btn-success" title = "Export To Excel" ng-click="exportToExcel()"><i title="Export To Excel"  class= "fa fa-file-excel-o"></i></button> 
                                    <?php } ?>
                                </span>
                        </div>
                    </div>
                </div>    
            </div>
            <div class= "box-body">
                <?php if($this->session->flashdata('success')){
                   echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
               }  ?>
               <div id="addressGrid">
                   <div ui-grid = "addressGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridwithselect"></div>
               </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" ng-show="overlay">
               <i class= "fa fa-refresh fa-spin"></i>
           </div>
           <!-- end loading -->
    
            <div class= "modal fade" id = "addressesModel" tabindex= "-1" role = "dialog" aria-labelledby = "addressesModalLabel" data-backdrop= "static" data-keyboard = "FALSE">
                <div class= "modal-dialog modal-lg" role = "document" >
                    <div class= "modal-content">
                        <div class= "modal-header">
                            <button type = "button" class= "close" ng-click="closeModal()"><span aria-hidden= "TRUE">&times;</span></button>
                            <h4 class= "modal-title" id = "exampleModalLabel"><?php echo $supplier['companyname'];?></h4>
                        </div>
                        <div class= "modal-body">
                            <div id="address-map" style="height: 450px;border:1px solid #d2d6de;"></div>    
                        </div>
                        <div class= "modal-footer">
                            <button type = "button" class= "btn btn-default" ng-click="closeModal()">Close</button>
                        </div>
                           <!-- Loading (remove the following to stop the loading)-->
                        <div class= "overlay map-overlay">
                              <i class= "fa fa-refresh fa-spin"></i>
                        </div>
                        <!-- end loading -->

                    </div>
                </div>
            </div>
           
            <div class="modal fade" id ="sitesModal" tabindex="-1" role ="dialog" aria-labelledby="sitesModalLabel" data-backdrop="static" data-keyboard ="false">
                <div class="modal-dialog" role ="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title">Add Site for</h4>
                      </div>
                  <form name ="siteform" id ="siteform" class="form-horizontal" method ="post"  >

                      <div class="modal-body">
                        <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                        <div id ="sitegriddiv" style ="display:none;"> 
                            <div class="status"></div>

                            <div class="form-group">
                                <label for="input" class="col-sm-3 control-label">State</label>
                                <div class="col-sm-5" >
                                    <select class= "form-control" name = "state" id = "state"   >
                                        <option value = ''>All State</option>
                                        <?php foreach($states as $val) { ?>
                                            <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="input" class="col-sm-3 control-label">Sites</label>
                                <div class="col-sm-9" >
                                    <div class="input-group">
                                        <select class= "form-control" name = "labelid" id = "labelid"   >
                                            <option value = ''>Select</option>
                                           
                                        </select>
                                        <div class="input-group-addon">
                                            <a href="<?php echo site_url("customers/addaddress?from=suppliers/edit/".  $supplier['customerid'] ."#sites");?>"    title="Add Address" ><i class="fa fa-plus" title="Add Address"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Active</label>
                                <div class="col-sm-6">
                                   <div class="checkbox">
                                     <label>
                                         <input type="checkbox" name="isactive" value="1">
                                     </label>
                                   </div>
                                </div>
                            </div>
                           </div>
                        </div>
                          <div class="modal-footer">
                              <div class="form-group">
                                    <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <input type ="hidden" name ="supplierid" id ="supplierid" value =""/> 
                                        <input type ="hidden" name ="siteid" id ="siteid" value =""/> 
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
    
           
        </div><!-- /.box -->	
    </div>
</div>