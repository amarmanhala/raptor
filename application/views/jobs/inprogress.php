<div class="row"  id="inprogressJobsCtrl" ng-controller="inprogressJobsCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">In Progress</h3>
                 
            </div>
            <div class= "box-header  with-border"> 
                <div class="row form-horizontal">
                    <div class= "col-sm-12 col-md-5" > 
                         <?php if (isset($ContactRules["direct_allocate"]) && $ContactRules["direct_allocate"] == "1"){?>
                        <select id="supplierid" name="supplierid" class="form-control"  data-live-search= "TRUE" title = "All Suppliers" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.supplierid">
                            <option value="">All Suppliers</option> 
                            <option value="O">DCFM</option> 
                            <?php foreach($suppliers as $key=>$value) {  ?>
                            <option value="<?php echo $value['customerid'];?>"><?php echo $value['companyname'];?></option> 
                            <?php } ?>
                        </select>
                         <?php } ?>
                    </div>
                    <div class= "col-sm-4 col-md-2" > 
                        <select id="suburb" name="suburb" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.suburb">
                            <option value="">All Suburbs</option>
                        <?php foreach($inprogressJobSuburb as $key=>$value) {  ?>
                            <option value="<?php echo $value['suburb'];?>"  ><?php echo $value['suburb'];?> (<?php echo $value['count'];?>)</option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-8 col-md-5"> 
                        <div class="input-group input-group">
                            <input type="text" class="form-control" placeholder="Search By: Job ID/<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>/Site Address/Suburb/State" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <?php if(isset($ContactRules['EDIT_JOB_GLCODE']) && $ContactRules['EDIT_JOB_GLCODE'] == 1) { ?>
                <div class="row form-horizontal" style="margin-top: 10px;">
                    <div  class="col-sm-12 col-md-4"> 
                    <div class="input-group input-group-sm">
                        <select id="in_glcode" name="in_glcode" class="form-control">
                            <option value="">Select GL Code</option>
                            <?php foreach($glcodes as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['glcode'];?></option> 
                            <?php } ?>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" id="in_updateglcode" class="btn btn-default btn-flat" data-loading-text ="Saving...">Update</button>
                        </span>
                    </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="box-body">
                <div id="inprogressjobstbl">
                    <div ui-grid = "gridInProgress" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns ui-grid-selection class= "gridwithselect1"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" style = "display:none">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>	
    </div>
</div>
 

 