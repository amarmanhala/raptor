<div class="row" id="userSecurityCtrl"  ng-controller="portalSettingsCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">Portal Settings</h3>
                <div class="pull-right">
                    <input type="hidden" id="edit_portalsettings" name="edit_portalsettings" value="<?php echo $edit_portalsettings ? '1':'0';?>">
                    <div class="input-group input-group">
                        <input type="text" class="form-control" placeholder="Search ....." ng-change="changeText()" ng-model="filterOptions.filtertext" id="filtertext" name="filtertext" aria-invalid="false">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                            <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshPortalSettingsGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                            <?php if($export_portalsettings) { ?>
                            <button type="button" class="btn btn-success"  ng-click="exportPortalSettings()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                            <?php } ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="box-body">
               <div id="portalsettingsgrid">
                   <div ui-grid ="portalSettingsGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" ng-show="overlay">
                  <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!--end loading -->
        </div>	
    </div>
</div>