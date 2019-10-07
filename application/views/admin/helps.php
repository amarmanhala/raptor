<!-- Default box -->
<div class= "box" ng-app="app" id = "HelpsCtrl"  ng-controller= "HelpsCtrl">
    <div class= "box-header with-border">
        <h3 class= "box-title text-blue">Manage Help</h3>
    </div>
    <div class= "box-header  with-border"> 
        <div class="row">
            <div class= "col-sm-9 col-md-6" style="padding-right: 0px;">
                <label class="radio-inline">
                    <input type="radio" name="status" value="active" ng-model="filterOptions.status" ng-change = "refreshFilter()" >Active Only
                </label>
                <label class="radio-inline">
                    <input type="radio" name="status" value="all" ng-model="filterOptions.status" ng-change = "refreshFilter()" >All
                </label>
            </div>
            <div  class="col-sm-12 col-md-6 text-right">
                <a class="btn  btn-primary" href="<?php echo site_url('admin/helps/addhelp'); ?>" title="Add"><i class="fa fa-plus"></i></a>
                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
            </div>
        </div> 
    </div>
    <div class= "box-body">
        <div id="contactGrid">
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->