<div class= "row">
    <div class= "col-md-12">
        <!-- Default box -->
        <div class= "box"  id = "ContractTechniciansCtrl" ng-controller= "ContractTechniciansCtrl">
            <div class= "box-header with-border">
              <h3 class= "box-title text-blue">Technicians</h3>
                <div class= "pull-right">
                      <button type="button" class="btn  btn-primary" ng-click="addTechnician();" title="Add a new Technician"><i class="fa fa-plus"></i></button> 
                
                </div>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                   
                    <div  class="col-sm-12 col-md-5 col-md-offset-7">
                        <div class="input-group input-group">
                            <input type = "text" id = "externalfiltercomp" placeholder="Search........."  ng-change = "changeText()" class= "form-control" ng-model= "filterOptions.filtertext" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class= "btn btn-success" title = "Export To Excel" ng-click="exportToExcel()"><i title="Export To Excel"  class= "fa fa-file-excel-o"></i></button> 
                            </span>
                        </div>
                    </div>
                </div>    
            </div>
            <div class= "box-body">
                
               <div id="technicianGrid">
                   <div ui-grid = "technicianGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  class= "gridautoheight"></div>
               </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" ng-show="overlay">
               <i class= "fa fa-refresh fa-spin"></i>
           </div>
           <!-- end loading -->
     
            <div class="modal fade" id="contractTechnicianModal" tabindex="-1" role="dialog" aria-labelledby="contractTechnicianModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
              <div class="modal-content">

                  <form name="contractTechnicianForm" id="contractTechnicianForm" class="form-horizontal"   autocomplete="off" novalidate>

                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"  ><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" >Add Technician</h4>
                  </div>

                  <div class="modal-body">
                       <div class="status">
                           <div class="alert alert-danger" style="display:none;" id="contactModalErrorMsg"></div>
                          <div class="alert alert-success" style="display:none;" id="contactModalSuccessMsg"></div>
                      </div>

                         <div class= "form-group">
                              <label for= "input" class= "col-sm-3 control-label">Technician</label>
                              <div class= "col-sm-5">  
                                  <select name = "userid" id = "userid" class= "form-control">
                                      <option value = ''>Select Technician</option>
                                      <?php foreach($users as $val) { ?>
                                          <option value="<?php echo $val['userid'];?>"><?php echo $val['userid'];?></option>
                                      <?php } ?>
                                  </select>
                              </div> 
  
                          </div>
                          <div class= "form-group">
                              <label for= "input" class= "col-sm-3 control-label">Normal Rate</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="normal_rate" name = "normal_rate" class= "form-control allownumericwithdecimal" value="0.00" placeholder= "Normal Rate" />
                              </div> 
                          </div>
                      <div class= "form-group">
                              <label for= "input" class= "col-sm-3 control-label">Week A/H</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="weekah_rate" name = "weekah_rate" class= "form-control allownumericwithdecimal" value="0.00" placeholder= "Week A/H" />
                              </div> 
                          </div>
                      <div class= "form-group">
                              <label for= "input" class= "col-sm-3 control-label">Saturday</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="saturday_rate" name = "saturday_rate" class= "form-control allownumericwithdecimal" value="0.00" placeholder= "Saturday" />
                              </div> 
                          </div>
                      <div class= "form-group">
                              <label for= "input" class= "col-sm-3 control-label">Sunday</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="sunday_rate" name = "sunday_rate" class= "form-control allownumericwithdecimal" value="0.00" placeholder= "Sunday" />
                              </div> 
                          </div>
                      <div class= "form-group">
                              <label for= "input" class= "col-sm-3 control-label">Public Holiday</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="pubhol_rate" name = "pubhol_rate" class= "form-control allownumericwithdecimal" value="0.00" placeholder= "Public Holiday" />
                              </div> 
                          </div>
                        <div class= "form-group">
                             <label for= "season_start_date" class= "col-sm-3 control-label">Start Date</label>
                             <div class= "col-sm-3">
                                 <div class= "input-group">
                                     <input type = "text" class= "form-control datepicker" id = "startdate" name = "startdate" placeholder="Start Date" value = "" readonly=""/>
                                     <div class= "input-group-addon">
                                         <i class= "fa fa-calendar"></i>
                                     </div>
                                 </div>
                              </div>
                         </div>
                        <div class= "form-group">
                            <label for= "season_end_date" class= "col-sm-3 control-label">End Date</label>
                            <div class= "col-sm-3">
                                <div class= "input-group">
                                    <input type = "text" class= "form-control datepicker" id = "enddate" name = "enddate" placeholder="End Date" value = ""  readonly="" />
                                    <div class= "input-group-addon">
                                        <i class= "fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <div class="form-group">
                          <label class="col-sm-3 control-label">Active</label>
                          <div class="col-sm-6">
                             <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="isactive" value="1">
                               </label>
                             </div>
                          </div>
                      </div>

                  </div>
                  <div class="modal-footer"> 
                      <input type ="hidden" name ="contractid" id ="contractid" value =""/> 
                        <input type ="hidden" name ="contechnicianid" id ="contechnicianid" value =""/>
                   
                      <input type ="hidden" name ="mode" id ="mode" value =""/> 
                      <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
                      <button type ="button" id="cancel" name ="btnCancel" class="btn btn-default" data-loading-text="Cancel" data-dismiss="modal" aria-label="Close" >Cancel</button>
                  </div>

                  </form>
              </div>
            </div>
            </div>
           
        </div><!-- /.box -->	
    </div>
</div>