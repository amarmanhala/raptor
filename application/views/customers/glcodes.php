<!-- Default box -->
<div  id = "GlCodesCtrl"  ng-app="app" ng-controller= "GlCodesCtrl">
    <div class= "box" >
        <div class="box-header with-border">
            <h3 class="box-title text-blue">GL Codes</h3>
            <div class="pull-right text-right">
                <input type="hidden" id="edit_glcode" name="edit_glcode" value="<?php echo $EDIT_GLCODE ? '1':'0';?>">
                 <?php if($ADD_GLCODE) { ?>
                    &nbsp;<button type="button" class="btn  btn-primary" ng-click="addGlCode();" title="Add"><i class="fa fa-plus"></i></button>
                <?php } ?>
                    <?php  if($IMPORT_GLCODE) { ?>
                        <button type="button" class="btn  btn-info" id="btn_import" title="Import Excel" ng-click="importGlCode();" ><i class="fa fa-upload"></i></button>
                        <br><a href="javascript:void(0)"  ng-click="exportImportTemplate()">Import Template</a>&nbsp;
                    <?php }?> 
            </div>

        </div>
        <div class= "box-header  with-border form-horizontal">
               <div class="row">
                    <div class= "col-sm-5 col-md-3">
                        <label class= "control-label">Site Addresses</label>
                        <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.labelid">
                            <option value="">All</option>
                            <?php foreach ($sites as $key => $value) {
                                echo '<option value="'.$value['labelid'].'">'.$value['site'].'</option>';
                            }?>
                        </select>
                    </div>
                     
                    <div class= "col-sm-3 col-md-2">
                        <label class= "control-label">State</label>
                        <select class= "form-control selectpicker"  multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" name = "state[]" id = "state" ng-change = "changeFilters()" ng-model= "filterOptions.state">
                         <?php foreach($states as $key=>$value) { ?>
                            <option value="<?php echo $value['abbreviation'];?>"><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                   <div class= "col-sm-4 col-md-2">
                        <label class= "control-label">Job Type</label>
                        <select class= "form-control selectpicker"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.jobtypeid">
                            <option value="">All</option>
                            <?php foreach($jobtypes as $key=>$value) { ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                   <div class= "col-sm-6 col-md-2">
                        <label class= "control-label">Budget Category</label>
                        <select class= "form-control selectpicker"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"  ng-change = "changeFilters()" ng-model= "filterOptions.budget_categoryid">
                            <option value="">All</option>
                        <?php foreach($budgetcategories as $key=>$value) { ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                   <div class= "col-sm-6 col-md-3">
                        <label class= "control-label">Budget Item</label>
                        <select class= "form-control selectpicker"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" ng-change = "changeFilters()" ng-model= "filterOptions.budget_itemid">
                            <option value="">All</option>
                        <?php foreach($budgetitems as $key=>$value) { ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-4 col-md-3">
                        <label class= "control-label">Trades</label>
                        <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.se_tradeid">
                            <option value="">All</option>
                            <?php foreach ($trades as $key => $value) {
                                echo '<option value="'.$value['id'].'">'.$value['se_trade_name'].'</option>';
                            }?>
                        </select>
                    </div>
                   <div class= "col-sm-4 col-md-3">
                        <label class= "control-label">Works</label>
                        <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.se_worksid">
                            <option value="">All</option>
                            <?php foreach ($works as $key => $value) {
                                echo '<option value="'.$value['id'].'">'.$value['se_works_name'].'</option>';
                            }?>
                        </select>
                    </div>
                   <div class= "col-sm-4 col-md-3">
                        <label class= "control-label">Sub Works</label>
                        <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.se_subworksid">
                            <option value="">All</option>
                            <?php foreach ($subworks as $key => $value) {
                                echo '<option value="'.$value['id'].'">'.$value['se_subworks_name'].'</option>';
                            }?>
                        </select>
                    </div>
                    <div class= "col-sm-4 col-md-3">
                        <label class= "control-label">Account Type</label>
                        <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.accounttype">
                            <option value="">All</option>
                            <?php foreach ($accounttypes as $key => $value) {
                                echo '<option value="'.$value['code'].'">'.$value['name'].'</option>';
                            }?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class= "col-sm-2 col-md-1" style="padding-right: 1px;">
                        <label class="control-label">&nbsp;</label>
                        <div> 
                            <div class="checkbox icheck" style="display: inline;">
                                <label><input class="" type="checkbox"  value="1" id="assets" name="assets" checked="checked" >&nbsp;Assets</label>
                            </div>
                        </div>
                    </div>
                   <div class= "col-sm-5 col-md-3">
                        <div class="showasset">
                            <label class="control-label">Asset Category</label>
                            <div> 
                                <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.asset_categoryid">
                                    <option value="">All</option>
                                    <?php foreach ($assetcategories as $key => $value) {
                                        echo '<option value="'.$value['asset_category_id'].'">'.$value['category_name'].'</option>';
                                    }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class= "col-sm-5 col-md-3">
                        <div class="showasset">
                            <label class="control-label">Search Assets</label>
                            <div> 
                                <input type="text" class="form-control" placeholder="Search Assets" ng-change="changeText()" ng-model="filterOptions.filtertext" id="filtertext" name="filtertext" aria-invalid="false">
                            </div>
                        </div>
                    </div>
                    <div  class="col-sm-12 col-md-5 text-right">
                        <label class="control-label">&nbsp;</label>
                        <div class="input-group input-group">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                 <?php if($EXPORT_GLCODE){?>
                                    <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                                <?php } ?>
                                
                            </span>
                        </div> 
                       
                    </div>
                      
                </div>    
 
        </div>

        <div class= "box-body">
            <div id="myglcodestatus"></div>   
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
    
    <div class="modal fade" id ="glcodeModal" tabindex="-1" role ="dialog" aria-labelledby="glcodeModalLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add GL-Code</h4>
            </div>
        <form name ="glcodeform" id ="glcodeform" class="form-horizontal" method ="post"  >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Account Type</label>
                        <div class="col-sm-4">
                            <select class= "form-control"  id="accounttype" name="accounttype">
                                <option value="">All Type</option>
                                <?php foreach ($accounttypes as $key => $value) {
                                    echo '<option value="'.$value['code'].'">'.$value['name'].'</option>';
                                }?>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">GL Account</label>
                        <div class="col-sm-5" >
                            <input type="text" name="accountcode" id="accountcode" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Account Name</label>
                        <div class="col-sm-9" >
                            <input type="text" name="accountname" id="accountname" class="form-control" value="" />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Site Address</label>
                        <div class="col-sm-9" >
                            <div class= "has-feedback">
                                <input type="text" ng-model="address11" id="address" name="address" placeholder="All Sites" uib-typeahead="sites as sites.site for sites in getCustomerSite($viewValue)"  typeahead-editable="false"  typeahead-on-select="onCustomerSiteSelect($item, $model, $label)" typeahead-loading="loadingSite"   class="form-control" ng-change="changeSiteAddressText()" />
                                <span class="form-control-feedback"><i ng-show="loadingSite" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingSite" ></i></span>
                            </div>
                            <input type="hidden" name="labelid" id="labelid" class="form-control" value="0" />
                        </div>
                    </div>
                    <div class="form-group showasset" >
                        <label for="input" class="col-sm-3 control-label">Asset Category</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" id= "asset_categoryid" name= "asset_categoryid">
                                <option value="0">All Asset Category</option>
                                <?php foreach ($assetcategories as $key => $value) {
                                    echo '<option value="'.$value['asset_category_id'].'">'.$value['category_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group showasset" >
                        <label for="input" class="col-sm-3 control-label">Asset</label>
                        <div class="col-sm-9" >
                            <div class= "has-feedback">
                                <input type="text" ng-model="asset11" id="asset" name="asset" placeholder="All Assets" uib-typeahead="assets as assets.asset for assets in getAssets($viewValue)"  typeahead-editable="false"  typeahead-on-select="onAssetSelect($item, $model, $label)" typeahead-loading="loadingAsset"   class="form-control" ng-change="changeAssetText()" />
                                <span class="form-control-feedback"><i ng-show="loadingAsset" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingAsset" ></i></span>
                            </div> 
                            <input type="hidden" name="assetid" id="assetid" class="form-control" value="0" />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Job Type</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" id= "jobtypeid" name="jobtypeid">
                                <option value="0">All Job Type</option>
                                <?php foreach($jobtypes as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Budget Category</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"  id= "budget_categoryid" name="budget_categoryid">
                                <option value="0">All</option>
                            <?php foreach($budgetcategories as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Budget Item</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" id= "budget_itemid" name="budget_itemid">
                                <option value="0">All Item</option>
                            <?php foreach($budgetitems as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Trade</label>
                        <div class="col-sm-7" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   id= "se_tradeid" name="se_tradeid">
                                <option value="0">All Trade</option>
                                <?php foreach ($trades as $key => $value) {
                                    echo '<option value="'.$value['id'].'">'.$value['se_trade_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Works</label>
                        <div class="col-sm-7" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"  id= "se_worksid" name="se_worksid">
                                <option value="">All</option>
                                <?php foreach ($works as $key => $value) {
                                    //echo '<option value="'.$value['id'].'">'.$value['se_works_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Sub Works</label>
                        <div class="col-sm-7" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   id= "se_subworksid" name="se_subworksid">
                                <option value="">All</option>
                                <?php foreach ($subworks as $key => $value) {
                                    //echo '<option value="'.$value['id'].'">'.$value['se_subworks_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>



                </div>
            </div>
                <div class="modal-footer">
                    <div class="form-group">
                          <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                          <div class="col-sm-9">
                              <input type ="hidden" name ="glcodeid" id ="glcodeid" value =""/> 
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
    
    
    <div class="modal fade" id ="importglcodeModal" tabindex="-1" role ="dialog" aria-labelledby="importglcodeModalLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import GL-Codes</h4>
            </div>
        <form name ="importglcodeform" id ="importglcodeform" class="form-horizontal" method ="post" enctype="multipart/form-data" action="<?php echo site_url('customers/importglcodeexcel') ?>" >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                     <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Account Type</label>
                        <div class="col-sm-4">
                            <select class= "form-control"  id="accounttype" name="accounttype">
                                <option value="">All Type</option>
                                <?php foreach ($accounttypes as $key => $value) {
                                    echo '<option value="'.$value['code'].'">'.$value['name'].'</option>';
                                }?>
                            </select>
                        </div>

                    </div>
                     
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Site Address</label>
                        <div class="col-sm-9" >
                            <div class= "has-feedback">
                                <input type="text" ng-model="address11" id="address" name="address" placeholder="All Sites" uib-typeahead="sites as sites.site for sites in getCustomerSite($viewValue)"  typeahead-editable="false"  typeahead-on-select="onCustomerSiteSelect($item, $model, $label)" typeahead-loading="loadingSite"   class="form-control" ng-change="changeSiteAddressText()" />
                                <span class="form-control-feedback"><i ng-show="loadingSite" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingSite" ></i></span>
                            </div>
                            <input type="hidden" name="labelid" id="labelid" class="form-control" value="0" />
                        </div>
                    </div>
                    <div class="form-group showasset" >
                        <label for="input" class="col-sm-3 control-label">Asset Category</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" id= "asset_categoryid" name= "asset_categoryid">
                                <option value="0">All Asset Category</option>
                                <?php foreach ($assetcategories as $key => $value) {
                                    echo '<option value="'.$value['asset_category_id'].'">'.$value['category_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group showasset" >
                        <label for="input" class="col-sm-3 control-label">Asset</label>
                        <div class="col-sm-9" >
                            <div class= "has-feedback">
                                <input type="text" ng-model="asset11" id="asset" name="asset" placeholder="All Assets" uib-typeahead="assets as assets.client_asset_id for assets in getAssets($viewValue)"  typeahead-editable="false"  typeahead-on-select="onAssetSelect($item, $model, $label)" typeahead-loading="loadingAsset"   class="form-control" ng-change="changeAssetText()" />
                                <span class="form-control-feedback"><i ng-show="loadingAsset" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingAsset" ></i></span>
                            </div> 
                            <input type="hidden" name="assetid" id="assetid" class="form-control" value="0" />
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Job Type</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" id= "jobtypeid" name="jobtypeid">
                                <option value="0">All Job Type</option>
                                <?php foreach($jobtypes as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Budget Category</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"  id= "budget_categoryid" name="budget_categoryid">
                                <option value="0">All</option>
                            <?php foreach($budgetcategories as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Budget Item</label>
                        <div class="col-sm-5" >
                            <select class= "form-control"  data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" id= "budget_itemid" name="budget_itemid">
                                <option value="0">All Item</option>
                            <?php foreach($budgetitems as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                            <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Trade</label>
                        <div class="col-sm-7" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   id= "se_tradeid" name="se_tradeid">
                                <option value="0">All Trade</option>
                                <?php foreach ($trades as $key => $value) {
                                    echo '<option value="'.$value['id'].'">'.$value['se_trade_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Works</label>
                        <div class="col-sm-7" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"  id= "se_worksid" name="se_worksid">
                                <option value="">All</option>
                                <?php foreach ($works as $key => $value) {
                                    //echo '<option value="'.$value['id'].'">'.$value['se_works_name'].'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-3 control-label">Sub Works</label>
                        <div class="col-sm-7" >
                            <select class= "form-control"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   id= "se_subworksid" name="se_subworksid">
                                <option value="">All</option>
                                <?php foreach ($subworks as $key => $value) {
                                    //echo '<option value="'.$value['id'].'">'.$value['se_subworks_name'].'</option>';
                                }?>
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

