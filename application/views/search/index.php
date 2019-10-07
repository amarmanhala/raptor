<!-- Default box -->
<div class="box" id="SearchCtrl" ng-app="app" ng-controller="SearchCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
        <button  class="btn bg-teal btn-sm pull-right " id ="btn-opensearch" ng-show="showsearchresult" ng-click="showSearchForm()">Open Search Page</button>
    </div> 
 
        <div class="box-body " ng-hide="showsearchresult">
            <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
        <form id ="fullsearch_form" id ="fullsearch_form" action='' method ="post" class="form-horizontal"  >
            <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label">DCFM Job ID</label>
                <div class="col-sm-5 col-md-3">  
                    <input type ="text" name ="jobid" id ="jobid" class="form-control" placeholder="Job ID" ng-model="filterOptions.jobid"  /> 
                </div> 
            </div>
            <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1'; ?> </label>
                <div class="col-sm-5 col-md-3" >
                    <input type="text" name="custordref" id="custordref" class="form-control" ng-model="filterOptions.custordref" />
                </div>
            </div>
            <div class="form-group" <?php if (isset($ContactRules["use_jobid_as_custordref2_in_client_portal"]) && $ContactRules["use_jobid_as_custordref2_in_client_portal"] == "1"){ echo 'style="display:none;"'; }?>>
                <label for="input" class="col-sm-3 col-md-2 control-label"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 1'; ?></label>
                <div class="col-sm-5 col-md-3" >
                    <input type="text" name="custordref2" id="custordref2" class="form-control" ng-model="filterOptions.custordref2"  />
                </div>
            </div>
            <div class="form-group" <?php if (isset($ContactRules["hide_custordref3_in_client_portal"]) && $ContactRules["hide_custordref3_in_client_portal"] == "1"){ echo 'style="display:none;"'; }?>>
                <label for="input" class="col-sm-3 col-md-2 control-label"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3'; ?> </label>
                <div class="col-sm-5 col-md-3" >
                    <input type="text" name="custordref3" id="custordref3" class="form-control" ng-model="filterOptions.custordref3"  />
                </div>
            </div>
            <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label">Street Address</label>
                <div class="col-sm-8 col-md-5">  
                    <input type ="text" name ="siteline2" id ="siteline2" class="form-control" placeholder="Street Address "  ng-model="filterOptions.siteaddress" />
                 </div> 
            </div>
             <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label">Suburb</label>
                <div class="col-sm-6 col-md-4">  
                    <div class= "has-feedback">
                            
                        <input type="text" ng-model="filterOptions.suburb" id="suburb" name="suburb" placeholder="search.." uib-typeahead="city as city.displaytext for city in getCityPostCode($viewValue, 'city')"    typeahead-on-select="onCitySelect($item, $model, $label)" typeahead-loading="loadingCity"   class="form-control"   />
                        <span class="form-control-feedback" ><i ng-show="loadingCity" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCity" ></i></span>
                    </div>
                </div> 
            </div>
            <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label">State</label>
                <div class="col-sm-4 col-md-3">  
                    <select class= "form-control selectpicker"  multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" name = "state[]" id = "state"   ng-model= "filterOptions.state">
                         <?php foreach($states as $key=>$value) { ?>
                            <option value="<?php echo $value['abbreviation'];?>"><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                    </select>
                </div> 
            </div>
            <div class="form-group">
                <label for="leaddate" class="col-sm-3 col-md-2 control-label">Date Logged</label>
                <div class="col-sm-4 col-md-2">
                     
                    <div class="input-group">
                        <input type ="text" name ="fromleaddate" id ="fromleaddate" class="form-control datepicker" placeholder="Date In"  ng-model= "filterOptions.fromleaddate" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                     </div>
                </div>
                <label for="leaddate" class="col-sm-1 col-md-1  control-label">To</label>
                <div class="col-sm-4 col-md-2"> 
                    <div class="input-group">
                        <input type ="text" name ="toleaddate" id ="toleaddate" class="form-control datepicker" placeholder="Date In"  ng-model= "filterOptions.toleaddate" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label">Date Due</label>
                <div class="col-sm-4 col-md-2">  
                    <div class="input-group">
                        <input type ="text" name ="fromduedate" id ="fromduedate" class="form-control datepicker" placeholder="Date Due" ng-model= "filterOptions.fromduedate" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div> 
                <label for="leaddate" class="col-sm-1 col-md-1  control-label">To</label>
                <div class="col-sm-4 col-md-2"> 
                    <div class="input-group">
                        <input type ="text" name ="toduedate" id ="toduedate" class="form-control datepicker" placeholder="Date Due"  ng-model= "filterOptions.toduedate" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="portaldesc" class="col-sm-3 col-md-2 control-label">Job Stage</label>
                <div class="col-sm-8 col-md-5"> 
                    <select class= "form-control selectpicker"  multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" name = "state[]" id = "state"   ng-model= "filterOptions.jobstages">
                        <?php foreach($jobstages as $key=>$value) { ?>
                            <option value="<?php echo $value['portaldesc'];?>"><?php echo $value['portaldesc'];?></option> 
                        <?php } ?>
                    </select>
                </div> 
            </div>
            <div class="form-group">
                <label for="jobdescription" class="col-sm-3 col-md-2 control-label">Description</label>
                <div class="col-sm-9 col-md-8">  
                    <input type ="text" name ="jobdescription" id ="jobdescription" class="form-control" placeholder="Job Description" ng-model= "filterOptions.jobdescription" />
                </div> 
            </div>
            <div class="form-group">
                <label for="input" class="col-sm-3 col-md-2 control-label">&nbsp;</label>
                <div class="col-sm-9">  
                    <button type ="button" class="btn btn-primary" ng-click="searchJob()">Search</button>
                     <button type ="button" class="btn btn-default" id ="reset-search"  ng-click="resetSearch()">Reset</button>
                </div> 
            </div>
        </form>
        </div><!-- /.box-body -->
        <div class="box-body" id ="search-result-page" ng-show="showsearchresult">
            <div>
                <div ui-grid ="jobs" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class="grid"></div>
            </div>
        </div><!-- /.box-body -->
         <!-- Loading (remove the following to stop the loading)-->
        <div class= "overlay" style = "display:none">
              <i class= "fa fa-refresh fa-spin"></i>
        </div>
</div><!-- /.box -->
 