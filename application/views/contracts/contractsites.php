<div class= "row">
    <div class= "col-md-12">
        <!-- Default box -->
        <div class= "box"  id = "ContractSitesCtrl" ng-controller= "ContractSitesCtrl">
            <div class= "box-header with-border">
              <h3 class= "box-title text-blue">Sites</h3>
                <div class= "pull-right">
                    <input type="hidden" id="edit_site" name="edit_site" value="<?php echo $EDIT_CONTRACT_SITE ? '1':'0';?>">
                    <input type="hidden" id="delete_site" name="delete_site" value="<?php echo $DELETE_CONTRACT_SITE ? '1':'0';?>">
                    <?php if($ADD_CONTRACT_SITE) { ?>
                     &nbsp;<button type="button" class="btn  btn-primary" ng-click="addSite();" title="Add a new site"><i class="fa fa-plus"></i></button> 
                   <?php } ?>
                </div>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                    <div class= "col-sm-2 col-md-1"  >
                        <button type = "button"  class= "btn btn-info" title = "Show on Map" ng-click="showOnMap()"><i class= "fa fa-map-marker" title = "Show on Map"></i></button>
                    </div>
                    <div class= "col-sm-5 col-md-3" >
                        <select class= "form-control selectpicker" name = "suburb" id = "suburb" ng-change = "changeFilters()" ng-model= "addressFilter.suburb" >
                            <option value = ''>All Suburb</option>
                            <?php foreach($contractsitesuburb as $val) { ?>
                                <option value="<?php echo $val['sitesuburb'];?>"><?php echo $val['sitesuburb'];?> (<?php echo $val['count'];?>)</option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-5 col-md-3" >
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
                                    <?php if($EXPORT_CONTRACT_SITE) { ?>
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
                   <div ui-grid = "addressGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridautoheight"></div>
               </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" ng-show="overlay">
               <i class= "fa fa-refresh fa-spin"></i>
           </div>
           <!-- end loading -->
    
           
            <div class="modal fade" id ="sitesModal" tabindex="-1" role ="dialog" aria-labelledby="sitesModalLabel" data-backdrop="static" data-keyboard ="false">
                <div class="modal-dialog modal-lg" role ="document">
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
                            <div class="row">
                                
                                <div class= "col-sm-3" >
                                    <label for="input">State</label>
                                    <select class= "form-control" name = "state" id = "state"   >
                                        <option value = ''>All State</option>
                                        <?php foreach($states as $val) { ?>
                                            <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class= "col-sm-3" >
                                    <label for="input">Sites Ref</label>
                                    <select class= "form-control" name = "selectlabelid" id = "selectlabelid"   >
                                        <option value = ''>Select</option>
                                      
                                    </select>
                                </div>
                                <div class= "col-sm-6" >
                                    <label for="input">Sites Search</label>
                                    <div class= "has-feedback">
                                        <input type="text" ng-model="sitesearch" id="sitesearch" name="sitesearch" placeholder="search Site.." uib-typeahead="sites as sites.address for sites in getSites($viewValue)"    typeahead-on-select="onSiteSelect($item, $model, $label)" typeahead-loading="loadingSites"  typeahead-editable="false"   class="form-control"   />
                                       <span class="form-control-feedback" ><i ng-show="loadingSites" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingSites" ></i></span>
                                   </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class= "col-sm-9" >
                                    <label for="input">Site</label>
                                    
                                    <input type="hidden"   name="sitesuburb" id="sitesuburb" class="form-control" />
                                    <input type="hidden"   name="labelid" id="labelid" class="form-control" />
                                    <input type="text" readonly="readonly" name="site" id="site" class="form-control" />
                                </div>
                                <div class= "col-sm-3" >
                                    <label for="input">Area (m2)</label>
                                     <input type="text" readonly="readonly" name="floorarea_sqm" id="floorarea_sqm" class="form-control" />
                                </div>
                            </div>
                            <div class="row">
                                <div class= "col-sm-3" >
                                    <label for="input">Latitude</label>
                                    <input type="text" readonly="readonly" name="latitude" id="latitude" class="form-control" />
                                </div>
                                <div class= "col-sm-3" >
                                    <label for="input">Longitude</label>
                                    <input type="text" readonly="readonly" name="longitude" id="longitude" class="form-control" />
                                </div>
                                <div class= "col-sm-6" >
                                    <label for="input">&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-default" id="getgps" onclick="getGPS();"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Searching...</span><span style="display:block;">Get GPS</span></button>
                                        <?php if($ADDRESS_SAVE_GPS) { ?>
                                        <button type="button" class= "btn btn-info" title = "Save GPS" ng-click="updateSiteLatLong();" >Save GPS</button> 
                                        <?php } ?>
                                        <button type="button" class="btn btn-link" id="showmap" ng-click="showMap();"><i class= "fa fa-map-marker" title = "Show on Map" style="font-size: 26px;"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class= "col-sm-12" >
                                    <label for="input">Address Group</label>
                                    <select class= "form-control selectpicker" ng-model="sitegroupids" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" name = "groupid[]" id = "groupid"   >
                                        <?php foreach($addressgroups as $val) { ?>
                                            <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                           </div>
                        </div>
                          <div class="modal-footer">
                              <div class="form-group">
                                    <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                                    <div class="col-sm-9">
                                        <input type ="hidden" name ="contractid" id ="contractid" value =""/> 
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
           
            <div class= "modal fade" id = "addressesModel" tabindex= "-1" role = "dialog" aria-labelledby = "addressesModalLabel" data-backdrop= "static" data-keyboard = "FALSE">
                <div class= "modal-dialog modal-lg" role = "document" >
                    <div class= "modal-content">
                        <div class= "modal-header">
                            <button type = "button" class= "close" ng-click="closeModal()"><span aria-hidden= "TRUE">&times;</span></button>
                            <h4 class= "modal-title" id = "exampleModalLabel"><?php echo $contract['name'];?></h4>
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
           
        </div><!-- /.box -->	
    </div>
</div>