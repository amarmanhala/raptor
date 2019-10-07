<div class="row">
    <div class="col-md-12">
        <div class="box" id="JobNoteCtrl" ng-controller="JobNoteCtrl">
            <div class="box-header  with-border">
<!--               <h3 class="box-title">Job Notes</h3>-->
                <button id ="addjobnotebtn" class="btn btn-primary">Add Note</button>
                 <button type ="button"  class="btn btn-default pull-right btn-sm btn-refresh" title ="Refresh Data" ng-click ="refreshJobNote()"><i class="fa fa-refresh" title ="Refresh Data"></i></button>
            </div>
            <div class="box-body">
                <div>
                    <div ui-grid ="jobNotesGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
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



<div class="modal fade" id ="jobNoteModal" tabindex="-1" role ="dialog" aria-labelledby="jobNoteModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
        <form name ="jobnote_form" id ="jobnote_form" class="form-horizontal" method ="post">
      <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id ="jobNoteModalLabel">Add Job Note</h4>
      </div>
      <div class="modal-body">
         <div class="form-group">
              <div class="col-sm-12">
                  <textarea class="form-control" id ="notes" name ="notes" rows="5"></textarea>
               </div>
        </div> 
          <div class="form-group">
               
              <div class="col-sm-12">
                  <div id ="status"></div> 
               </div>
        </div> 
     <div class="form-group">
              <div class="col-sm-12 text-right">
                  <button type ="submit" name ="modalsave" id ="modalsave" class="btn btn-primary" data-loading-text ="Saving...">Save Note</button>
                  &nbsp;&nbsp;<button type ="button" name ="cancel" id ="cancel" class="btn btn-default">Cancel</button>
               </div>
        </div>    
      </div>
            <input  type ="hidden" id ="jobid" name="jobid" value ="<?php echo $job['jobid'];?>" />
  </form>
    </div>
  </div>
</div>

