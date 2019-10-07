 
<!-- Default box -->
<div class= "box" ng-app="app"  id = "ContractedHoursCtrl" ng-controller= "ContractedHoursCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Contracted Hours</h3>
        <div class= "pull-right">
            <input type="hidden" id="edit_contract" name="edit_contract" value="<?php echo $EDIT_CONTRACT ? '1':'0';?>">
            <?php if($EDIT_CONTRACT) { ?>
             &nbsp;<button type="button" class="btn  btn-primary" ng-click="addContractedHours();" title="Add a new Contracted Hours"><i class="fa fa-plus"></i></button> 
           <?php } ?>
        </div>
    </div> 
    <div class= "box-body"> 
       <div id="ContractedHoursGrid">
           <div ui-grid = "ContractedHoursGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class= "gridautoheight"></div>
       </div>
    </div><!-- /.box-body -->
    <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" ng-show="overlay">
       <i class= "fa fa-refresh fa-spin"></i>
   </div>
   <!-- end loading -->
   
   
    <div class="modal fade" id="contractHoursModal" tabindex="-1" role="dialog" aria-labelledby="contractHoursModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
      <div class="modal-content">

          <form name="contractHoursForm" id="contractHoursForm" class="form-horizontal"   autocomplete="off" novalidate>

          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"  ><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" >Add Contracted Hours Template</h4>
          </div>

          <div class="modal-body">
               <div class="status">
                   <div class="alert alert-danger" style="display:none;" id="contactModalErrorMsg"></div>
                  <div class="alert alert-success" style="display:none;" id="contactModalSuccessMsg"></div>
              </div>

                 <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Name</label>
                      <div class= "col-sm-6">  
                           <input type = "text" id="name" name = "name" class= "form-control" value="" placeholder= "Name" />
                      </div> 
                      
                  </div>
                    <div class= "form-group">
                        <label for= "input" class= "col-sm-2">&nbsp;</label>
                        <div class= "col-sm-3">  
                            From
                        </div>
                        <div class="col-sm-3">
                           To
                        </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Sun</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="sun_from" name ="sun_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="sun_to" name ="sun_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Mon</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="mon_from" name ="mon_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="mon_to" name ="mon_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Tue</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="tue_from" name ="tue_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="tue_to" name ="tue_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Wed</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="wed_from" name ="wed_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="wed_to" name ="wed_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Thu</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="thu_from" name ="thu_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="thu_to" name ="thu_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Fti</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="fri_from" name ="fri_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="fri_to" name ="fri_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
                  <div class= "form-group">
                      <label for= "input" class= "col-sm-2 control-label">Sat</label>
                       <div class= "col-sm-3">
                            <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="sat_from" name ="sat_from" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            </div>
                       </div>
                       <div class= "col-sm-3">
                           <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input class="form-control timepicker" type="text" id ="sat_to" name ="sat_to" readonly="readonly" value ="00:00 AM">
                                <div class="input-group-addon">
                                  <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                           </div>
                       </div>
                  </div>
              <div class="form-group">
                  <label class="col-sm-2 control-label">Sort</label>
                  <div class="col-sm-3">
                      <input class="form-control" type="text" id ="sortorder" name ="sortorder"   >
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-sm-2 control-label">Active</label>
                  <div class="col-sm-9">
                     <div class="checkbox">
                       <label>
                           <input type="checkbox" name="isactive" value="1">
                       </label>
                     </div>
                  </div>
              </div>

          </div>
          <div class="modal-footer">  
                <input type ="hidden" name ="contractedhoursid" id ="contractedhoursid" value =""/>

              <input type ="hidden" name ="mode" id ="mode" value =""/> 
              <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary" data-loading-text="Saving...." ><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
              <button type ="button" id="cancel" name ="btnCancel" class="btn btn-default" data-loading-text="Cancel" data-dismiss="modal" aria-label="Close" >Cancel</button>
          </div>

          </form>
      </div>
    </div>
    </div>


</div><!-- /.box -->	
   