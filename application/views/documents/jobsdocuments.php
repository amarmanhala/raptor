<!-- Default box -->
<div  id = "JobsDocumentCtrl" ng-app="app" ng-controller= "JobsDocumentCtrl">
    <div class= "box" >
        <div class="box-header with-border">
            <h3 class="box-title text-blue">Jobs Document Library</h3>
            <div class="pull-right">
               
            </div>

        </div>
        <div class= "box-header  with-border formhorizontal">
                <div class="row">
                    <div class= "col-sm-4 col-md-2" style="padding-right: 5px;">
                        <label class= "control-label">Document Type</label>
                        <select class= "form-control selectpicker"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.documenttype">
                        <?php foreach ($docType as $key => $value) {
                                echo '<option value="'.$value['doctype'].'">'.$value['doctype'].' ('.$value['count'].')</option>';
                            }?>
                        </select>
                    </div>
                    
                    <div class= "col-sm-4 col-md-2" style="padding-right: 5px;">
                        <label class= "control-label">Site</label>
                        <select class= "form-control selectpicker"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.site">
                        <?php foreach ($sites as $key => $value) {
                                echo '<option value="'.$value['labelid'].'">'.$value['site'].'</option>';
                            }?>
                        </select>
                    </div>
                   
                    <div class= "col-sm-4 col-md-2" style="padding-right: 5px;">
                        <label class="control-label">Suburb</label>
                        <div class= "has-feedback">
                            
                            <input type="text" ng-model="filterOptions.suburb" id="suburb" name="suburb" placeholder="search.." uib-typeahead="city as city.displaytext for city in getCityPostCode($viewValue, 'city')"    typeahead-on-select="onCitySelect($item, $model, $label)" typeahead-loading="loadingCity"   class="form-control" ng-change="changeSuburbText()" />
                            <span class="form-control-feedback" ><i ng-show="loadingCity" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCity" ></i></span>
                        </div>
                    </div>
                    <div class= "col-sm-4 col-md-1" style="padding-right: 5px;">
                        <label class= "control-label">State</label>
                        <select class= "form-control selectpicker"  multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" name = "state[]" id = "state" ng-change = "changeFilters()" ng-model= "filterOptions.state">
                         <?php foreach($states as $key=>$value) { ?>
                            <option value="<?php echo $value['abbreviation'];?>"><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-4 col-md-2" style="padding-right: 5px;">
                        <label class= "control-label">Month Added</label>
                        <select class= "form-control selectpicker" multiple data-live-search= "TRUE"  title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.monthyear">
                            <?php foreach ($monthDocCount as $key => $value) {
                            echo '<option value="'.$value['month_added'].'">'.$value['month_added'].' ('.$value['count'].')</option>';
                            }?>
                        </select>
                    </div>
                    <div  class="col-sm-8 col-md-3">
                        <label class="control-label">&nbsp;</label>
                        <div class="input-group input-group">

                                <input type="text" class="form-control" placeholder="Documentid/Doc Name/Description/Jobid/Custom Ref/Suburb/State" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                                <span class="input-group-btn">

                                    <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                    <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                    <?php if($EXPORT_DOC){?>
                                        <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                                    <?php } ?>
                                </span>
                        </div>
                    </div>
                      
                </div>    
 
        </div>

        <div class= "box-body">
             <div>
                <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
            </div>
        </div><!-- /.box-body -->
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay" ng-show="overlay" >
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->
    </div><!-- /.box -->
</div>
