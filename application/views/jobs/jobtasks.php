<div class="row">
    <div class="col-md-12">
        <div class="box" id="JobTaskCtrl" ng-controller="JobTaskCtrl">
            <div class="box-header  with-border">
<!--               <h3 class="box-title">Job Notes</h3>-->
                <?php if($addtask_security) { ?>
                <button id ="addjobtask" class="btn btn-primary">Add Task</button>
                <?php } ?>
                 <button type ="button"  class="btn btn-default pull-right btn-sm btn-refresh" title ="Refresh Data" ng-click ="refreshJobTask()"><i class="fa fa-refresh" title ="Refresh Data"></i></button>
            </div>
            <div class="box-body">
                <div>
                    <div ui-grid ="jobTaskGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class="grid"></div>
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

<div class="modal fade" id ="addTaskModal" tabindex="-1" role ="dialog" aria-labelledby="addTaskModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
    <form name ="addtask_form" id ="addtask_form" role ="form" method ="post" action="<?php echo site_url();?>jobs/addtask" class="form-horizontal">
      <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addTaskTitle">Add Task</h4>
      </div>
      <div class="modal-body">
            
            
            <div class="form-group">
                <label class="col-sm-3" class="control-label">Task Description:</label>
                <div class="col-sm-9">
                    <textarea type ="text" id ="description" rows="5" name ="description" class="form-control" placeholder="Description"></textarea>
                </div>
            </div>
          <div class="form-group">
                <label class="col-sm-3" class="control-label">Allocated To:</label>
                <div class="col-sm-5">
                    <select class="form-control" name ="allocatedto" id ="allocatedto">
                             <option value ="">Select</option>
                             <?php foreach($taskallocatedto as $value) { ?>
                             <option value ="<?php echo $value;?>"><?php echo $value;?></option>
                             <?php } ?>
                         </select>
                </div>
           
            </div>
            <div class="form-group">
                <label class="col-sm-3" class="control-label">Follow-up Date:</label>
                <div class="col-sm-5">
                        <div class="input-group">
                            <input type="text" class="form-control datepicker" id="followupdate" name="followupdate" readonly="readonly">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                </div>
            </div>
 
         <div id ="status"></div>         
    
          <div class="form-group">
              <div class="col-sm-10 col-sm-offset-3">
                    <button type ="submit" id ="modalsave" class="btn btn-primary" data-loading-text="Saving..">Save</button>&nbsp;
                    <button type ="button" id ="cancel" class="btn btn-Default" data-loading-text="Cancel">Cancel</button>
              </div>
                
            </div>
      </div>
        <input  type ="hidden" name ="jobid" id ="jobid" value ="<?php echo $job['jobid'] ?>" />
     </form>
    </div>
  </div>
</div>