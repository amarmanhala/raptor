<!-- Default box -->
<div class= "box" id = "AssetCtrl" ng-app= "app" ng-controller= "AssetCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
        <div class="pull-right">
             <input type="hidden" id="edit_asset" name="edit_asset" value="<?php echo $EDIT_ASSET ? '1':'0';?>">
             <button ng-click="ScheduleService()" title="Schedule Service" class="btn btn-info" type="button"><i title="Schedule Service" class="fa fa-calendar-plus-o"></i></button>
            <?php  if(isset($ADD_ASSET) && $ADD_ASSET){ ?>
            <a class="btn btn-primary" href="<?php echo site_url() ?>asset/add" title="Add Assets"><i class= "fa fa-plus"></i></a>
          <?php  } ?>
            
        </div>
    
    </div>
    <div class= "box-header  with-border">
        
       <div class="row">
            <div class= "col-sm-6 col-md-2">
                <label class= "control-label">Site Addresses</label>
                <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.labelid">
                    <option value="">All</option>
                    <?php foreach ($sites as $key => $value) {
                        echo '<option value="'.$value['labelid'].'">'.$value['site'].'</option>';
                    }?>
                </select>
            </div>
            <div class= "col-sm-6 col-md-2">
                <label class= "control-label">Contract</label>
                <select class= "form-control selectpicker"    data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.contractid">
                    <option value="">All</option>
                    <?php foreach($contracts as $key=>$value) { ?>
                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                    <?php } ?>
                </select>
            </div>
                <div class= "col-sm-6 col-md-2" >
                    <label class= "control-label">State</label>
                    <select id="state" name="state" class="form-control selectpicker" ng-change = "changeFilters()" ng-model= "filterOptions.state">
                            <option value="">All</option>
                            <?php foreach($states as $key=>$value) {   ?>
                            <option value="<?php echo $value['abbreviation'];?>" <?php echo $selected;?>><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2" >
                    <label class= "control-label">Category</label>
                    <select id="categoryid" name="categoryid" class="form-control selectpicker" ng-change="changeFilters()"  ng-model="filterOptions.categoryid">
                            <option value="">All</option>
                     <?php foreach($categories as $key=>$value) {  ?>
                                <option value="<?php echo $value['asset_category_id'];?>"  ><?php echo $value['category_name'];?></option> 
                  <?php } ?>
                        </select>
                </div>
                <div  class="col-sm-12 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control" placeholder="Site Address/Suburb/State/Location/Category/Manufacturer/Service Tag" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                                
                            </span>
                    </div>
                    
                </div>
               
            </div>    
      
        
    </div>
      
    <div class= "box-body">
        <div id="myassetstatus"></div>   
         <?php 
 		if($this->session->flashdata('success')) 
 		{
         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
                }
                if($this->session->flashdata('error')) 
 		{
         	echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	
                }
	?>
        <div>
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" style = "display:none">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
    
    <div class="modal fade" id="ScheduledServiceModal" tabindex="-1" role="dialog" aria-labelledby="ScheduledServiceModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Schedule Service</h4>
                        </div>
                        <form name="addScheduleServiceForm" id="addScheduleServiceForm" class="form-horizontal" autocomplete="off" novalidate>
                            <div class="modal-header">
                                <div class="row">
                                    <div class= "col-xs-8 col-sm-6 col-md-4">
                                        <select class= "form-control selectpicker"  ng-change = "changeAssetServiceFilters()" ng-model= "assetcategory">
                                            <option value = ''>Category</option>
                                            <?php foreach($categories as $val) { ?>
                                                <option value="<?php echo $val['asset_category_id'];?>"><?php echo $val['category_name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                     <div class= "col-xs-4 col-sm-6 col-md-8 text-right">
                                        <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearAssetServiceFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                        <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeAssetServiceFilters()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>

                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-body">
                                <center  ng-show="ScheduleServiceoverlay" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                             
                                <div class="row" ng-hide="ScheduleServiceoverlay">
                                    <div class="col-sm-12">
                                        <div id="assetAvailableGrid" style="max-height: 150px;overflow: auto">
                                            <div ui-grid = "assetAvailableGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class= "gridautoheight"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <div class="row">
                                            <div class= "col-sm-3">
                                                <label class= "control-label">{{selectedassetupper.length}} asset selected</label>
                                            </div>
                                            <div class= "col-sm-3">
                                                <select class= "form-control" id="servicetypeid">
                                                    <option value="">Select Service Type</option>
                                                    <?php foreach($conServiceType as $key=>$value) { ?>
                                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class= "col-sm-3">
                                                <select class= "form-control" id="checklistid">
                                                    <option value="">Select Checklist</option>
                                                    <?php foreach($checklists as $key=>$value) { ?>
                                                    <option value="<?php echo $value['checklistid'];?>"><?php echo $value['checklist'];?></option> 
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class= "col-sm-2">
                                                <select class= "form-control" id="activityid">
                                                    <option value="">Select Activity</option>
                                                    <?php foreach($assetActivities as $key=>$value) { ?>
                                                    <option value="<?php echo $value['asset_activity_id'];?>"><?php echo $value['activity_name'];?></option> 
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class= "col-sm-1">
                                                 <button ng-click="addSelectedAsset()" title="add selected asset" class="btn btn-primary" type="button"><i title="add selected asset" class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12" style="margin-top: 15px;">
                                        <div id="assetSelectedGrid" style="max-height: 150px;overflow: auto">
                                            <div ui-grid = "assetSelectedGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  class= "gridautoheight"></div>
                                        </div>
                                         
                                    </div>
                                    <div class="col-sm-12"  style="margin-top: 15px;">
                                        <div class="row">
                                            <div class= "col-sm-6">
                                                <select class= "form-control" id="jobid" name="jobid">
                                                    <option value="">Select Job</option>
                                                    <?php foreach($joblists as $key=>$value) { ?>
                                                    <option value="<?php echo $value['jobid'];?>"><?php echo $value['job'];?></option> 
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class= "col-sm-6">
                                                <label class="radio-inline"><input type="radio" value="selected" name="allocateto" id="rdbselected" >Allocate to Selected Job</label>
                                                <label class="radio-inline"><input type="radio" value="new" name="allocateto" id="rdbnew" >Log new Job</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="status"></div>
                                </div>
                                
                            </div>
                            
                            <div class="modal-footer"> 
                                <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary" ng-disabled="ScheduledSiteoverlay" data-loading-text="<i class='fa fa-spinner fa-spin'></i>&nbsp;Saving...">Allocate</button>
                                <button type ="button" id="modalclose" name ="modalclose" class="btn btn-default" ng-disabled="ScheduledSiteoverlay"  data-loading-text="Close" data-dismiss="modal" aria-label="Close" >Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
</div><!-- /.box -->  
 
