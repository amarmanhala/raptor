<!-- Default box -->
<div class= "box" id = "AssetHistoryCtrl" ng-controller= "AssetHistoryCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">Activities/Notes/History</h3>
        <div class="pull-right">
            <button type="button" class="btn btn-primary" name="add_asset_note_history" id="add_asset_note_history" title="Asset Add Notes/History"><i class= "fa fa-plus"></i></button>
         </div>
    </div>
    <div class= "box-body">
          
        <div>
            <div ui-grid = "assetHistoryOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "gridautoheight1"></div>
        </div> 
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" style = "display:none">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
     <!-- Modal -->
    <div class="modal fade" id="assetdetailhistorymodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
     <form id="assetdetailhistoryform" name="assetdetailhistoryform" class="form-horizontal" method="post" autocomplete="off">
      <div class="modal-header">
        <button id="dhclose" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Notes/History</h4>
      </div>
      <div class="modal-body">

		<div class="form-group">
		    <label for="input" class="col-sm-3 control-label">Job:</label>
		    <div class="col-sm-5">
                        <div class= "has-feedback">
                            <input type="text" ng-model="jobid" id="jobid" name="jobid" placeholder="search .." uib-typeahead="jobid as jobid.jobid for jobid in getJobs($viewValue)"  typeahead-editable="false"    typeahead-on-select="onJobSelect($item, $model, $label)" typeahead-loading="loadingJob"   class="form-control"   />
                           <span class="form-control-feedback" ><i ng-show="loadingJob" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingJob" ></i></span>
                        </div>
                       
		      <input type="hidden" id="hidjobid" name="hidjobid" value="" />
		      <span class="help-block with-errors"></span>
		    </div>
		</div>
		
		<div class="form-group">
		    <label for="input" class="col-sm-3 control-label">PO Ref:</label>
		    <div class="col-sm-5">
                        <div class= "has-feedback">
                            <input type="text" ng-model="poref" id="poref" name="poref" placeholder="search .." uib-typeahead="poref as poref.poref for poref in getPOs($viewValue)"  typeahead-editable="false"    typeahead-on-select="onPOSelect($item, $model, $label)" typeahead-loading="loadingPO"   class="form-control"   />
                           <span class="form-control-feedback" ><i ng-show="loadingPO" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingJob" ></i></span>
                        </div>
		        <input type="hidden" id="hidporef" name="hidporef" value="" />
		      <span class="help-block with-errors"></span>
		    </div>
		</div>
		
		<div class="form-group">
		    <label for="input" class="col-sm-3 control-label">Activity Date:</label>
		    <div class="col-sm-4">
		       <div class="input-group">
                           <input type="text" class="form-control datepicker" id="activity_date" name="activity_date" placeholder="Date" value="" readonly=""/>
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
               </div>	
		      <span class="help-block with-errors"></span>
		    </div>
		</div>
		
		<div class="form-group">
		    <label for="input" class="col-sm-3 control-label">Activity Category:</label>
		    <div class="col-sm-8">
		      <select class="form-control select2" id="activity_category" name="activity_category">
		      	<option value="">-Select-</option>
		      	<?php
			      	foreach($asset_activity as $value):
			      		echo '<option value="'.$value['asset_activity_id'].'">'.$value['activity_name'].'</option>';
			      	endforeach;
		      	?>
		      </select>
		      <span class="help-block with-errors"></span>
		    </div>
		</div>
		
		<div class="form-group">
		    <label for="input" class="col-sm-3 control-label">Description:</label>
		    <div class="col-sm-9">
		      <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="" />
		      <span class="help-block with-errors"></span>
		    </div>
		</div>
		<div class="form-group">
		     <div class="col-sm-12  e-status">
                     </div>
		</div>
		
        
      </div>
      <div class="modal-footer">
          <input type="hidden" name="assetid" id="assetid" value="">
       
        <button id="dhsavebtn" type="submit" class="btn btn-primary" data-loading-text="Saving...">Save</button>
         <button id="dhclosebtn" type="button" class="btn btn-default" data-dismiss="modal" data-loading-text="Close">Close</button>
      </div>
      <input type="hidden" name="dhassethistoryid" id="dhassethistoryid" value="" />
      </form>
    </div>
  </div>
</div>

</div><!-- /.box -->  
 
