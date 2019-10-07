<div class="row">
    <div class="col-md-12">
        <div class="box" id="JobReportCtrl" ng-controller="JobReportCtrl">
            <div class="box-header  with-border">
                <div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <button type="button" class="btn btn-default" id="editableReports" data-url=<?php echo base_url('EditableReport/index/' . $this->data['job']['jobid']); ?>>Go to Editable Reports</button>
                    </div>
                </div>
            </div>
            <div class="box-body">                
                <div>
                    <div ui-grid ="jobReportGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
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