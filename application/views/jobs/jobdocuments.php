<div class="row">
    <div class="col-md-12">
        <div class="box" id="JobDocumentsCtrl" ng-controller="JobDocumentsCtrl">
            <div class="box-header  with-border">
                <button id ="adddoc" class="btn btn-primary">Upload Document</button>
                <button type ="button"  class="btn btn-default pull-right btn-sm btn-refresh" title ="Refresh Data" ng-click ="refreshJobDocument()"><i class="fa fa-refresh" title ="Refresh Data"></i></button>
            </div>
            <div class="box-body">
                <div>
                    <div ui-grid ="jobDocumentsGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" ng-show="overlay">
                  <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>
    </div>
</div>