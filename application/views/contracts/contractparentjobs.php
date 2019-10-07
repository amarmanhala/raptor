<div class= "row">
    <div class= "col-md-12">
        <!-- Default box -->
        <div class= "box"  id = "ContractParentJobsCtrl" ng-controller= "ContractParentJobsCtrl">
            <div class= "box-header with-border">
              <h3 class= "box-title text-blue">Parent Jobs</h3>
                <div class= "pull-right">
                    <input type="hidden" id="delete_con_parent_job" name="delete_con_parent_job" value="<?php echo $DELETE_CONTRACT_PARENT_JOB ? '1':'0';?>">
                    <?php if($ADD_CONTRACT_PARENT_JOB) { ?>
                     &nbsp;<button type="button" class="btn  btn-primary" ng-click="addParentJob();" title="Add a Parent Job"><i class="fa fa-plus"></i></button> 
                   <?php } ?>
                </div>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                    
                    <div class= "col-sm-12 col-md-7" >
                        <?php if($contract['parentjobid'] != NULL && $contract['parentjobid'] != 0) { ?>
                        Contract Parent Job : <a href="<?php echo site_url('jobs/jobdetail/'. $contract['parentjobid']);  ?>" target="_blank"><?php echo $contract['parentjobid'];?></a>
                        <?php } ?>
                    </div>
                    <div  class="col-sm-12 col-md-5">
                        <div class="input-group input-group">
                            <input type = "text"  placeholder="Search........."  ng-change = "changeText()" class= "form-control" ng-model= "filterOptions.filtertext" />
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
                <div id="parentJobGrid">
                   <div ui-grid = "parentJobGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns   class= "gridautoheight"></div>
               </div>
            </div><!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" ng-show="overlay">
               <i class= "fa fa-refresh fa-spin"></i>
           </div>
           <!-- end loading -->
           
           
            <div class="modal fade" id="createParentJobModal" tabindex="-1" role="dialog" aria-labelledby="createParentJobModalLabel" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
              <div class="modal-content">

                  <form name="createParentJobForm" id="createParentJobForm" class="form-horizontal"   autocomplete="off" novalidate>

                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"  ><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" >Create Parent Job</h4>
                  </div>

                  <div class="modal-body">
                       <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                        <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status">
                        <div class="alert alert-danger" style="display:none;" id="contactModalErrorMsg"></div>
                       <div class="alert alert-success" style="display:none;" id="contactModalSuccessMsg"></div>
                   </div>

                         <div class= "form-group">
                              <label for= "input" class= "col-sm-2">Month</label>
                              <div class= "col-sm-3">  
                                  <select name = "monthofyear" id = "monthofyear" class= "form-control">
                                      <?php for($i = 1; $i <= 12; $i++) { ?>
                                        <option value="<?php echo $i;?>"><?php echo date('M', strtotime(date('Y-'.$i.'-1'))); ?></option>
                                <?php } ?>
                                  </select>
                              </div>
                              <div class="col-sm-3">
                                  <select class= "form-control" name = "year" id = "year" >
                                  <?php for($i = 2016; $i <= (date('Y')+2); $i++) { ?>
                                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                    <?php } ?>
                                    </select>
                              </div>
                        </div>
                      <div class= "form-group">
                              <label for= "input" class= "col-sm-2">&nbsp;</label>
                              <div class= "col-sm-3">  
                                  Rule
                              </div>
                              <div class="col-sm-3">
                                 Value
                              </div>
                        </div>
                          <div class= "form-group">
                              <label for= "input" class= "col-sm-2">Parent Job</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="parentjobrule" name = "parentjobrule" class= "form-control" value="" placeholder= "" readonly="" disabled=""/>
                              </div>
                              <div class= "col-sm-5">  
                                  <input type = "text" id="parentjobvalue" name = "parentjobvalue" class= "form-control" value="" placeholder= "" />
                              </div> 
                          </div>
                         <div class= "form-group">
                              <label for= "input" class= "col-sm-2">Order Ref1</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="custordref1code" name = "custordref1code" class= "form-control" value="" placeholder= "" readonly="" disabled=""/>
                              </div>
                              <div class= "col-sm-6">  
                                  <input type = "text" id="custordref1value" name = "custordref1value" class= "form-control" value="" placeholder= "" />
                              </div> 
                          </div>
                          <div class= "form-group">
                              <label for= "input" class= "col-sm-2">Order Ref2</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="custordref2code" name = "custordref2code" class= "form-control" value="" placeholder= "" readonly="" disabled=""/>
                              </div>
                              <div class= "col-sm-6">  
                                  <input type = "text" id="custordref2value" name = "custordref2value" class= "form-control" value="" placeholder= "" />
                              </div> 
                          </div>
                          <div class= "form-group">
                              <label for= "input" class= "col-sm-2">Order Ref3</label>
                              <div class= "col-sm-3">  
                                  <input type = "text" id="custordref3code" name = "custordref3code" class= "form-control" value="" placeholder= "" readonly="" disabled=""/>
                              </div>
                              <div class= "col-sm-6">  
                                  <input type = "text" id="custordref3value" name = "custordref3value" class= "form-control" value="" placeholder= "" />
                              </div> 
                          </div>
                          <div class= "form-group">
                              <label for= "input" class= "col-sm-5">Est. Sell</label>
                              
                              <div class= "col-sm-3">  
                                  <div class= "input-group">
                                    <div class= "input-group-addon">
                                        <?php echo RAPTOR_CURRENCY_SYMBOL;?>
                                    </div>
                                  <input type = "text" id="estimated_sell" name = "estimated_sell" class= "form-control allownumericwithdecimal" value="" placeholder= "" />
                                </div> 
                            </div> 
                          </div>
                          <div class= "form-group">
                              <label for= "input" class= "col-sm-5">Buffer</label> 
                              <div class= "col-sm-3">
                                <div class= "input-group">
                                    <div class= "input-group-addon">
                                        <?php echo RAPTOR_CURRENCY_SYMBOL;?>
                                    </div>
                                    <input type = "text" id="internal_buffer" name = "internal_buffer" class= "form-control allownumericwithdecimal" value="" placeholder= "" />
                                </div>
                                  
                              </div> 
                          </div>
                      <div class= "form-group">
                              <label for= "input" class= "col-sm-5">Job Stage</label>
                              <div class= "col-sm-5">  
                                  <select class= "form-control" name = "jobstage" id = "jobstage"    >
                                    <option value="">Select Job Stage</option>
                                <?php foreach ($jobstages as $key => $value) { ?>
                                  <option value="<?php echo $value['jobstagedesc'];?>" ><?php echo $value['jobstagedesc'];?></option> 
                                <?php } ?>
                                </select>
                              </div> 
                          </div>
                      
                        <div class= "form-group">
                             <label for= "Attendance" class= "col-sm-5">Attendance Date</label>
                             <div class= "col-sm-4">
                                 <div class= "input-group">
                                     <input type = "text" class= "form-control datepicker" id = "attendancedate" name = "attendancedate" placeholder="Attendance Date" value = "" readonly=""/>
                                     <div class= "input-group-addon">
                                         <i class= "fa fa-calendar"></i>
                                     </div>
                                 </div>
                              </div>
                         </div>
                        <div class= "form-group">
                            <label for= "season_end_date" class= "col-sm-5">Completion Date</label>
                            <div class= "col-sm-4">
                                <div class= "input-group">
                                    <input type = "text" class= "form-control datepicker" id = "completiondate" name = "completiondate" placeholder="Completion Date" value = ""  readonly="" />
                                    <div class= "input-group-addon">
                                        <i class= "fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <div class="form-group">
                          <label class="col-sm-5">Chargeable</label>
                          <div class="col-sm-7">
                             <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="ischargeable" value="1">
                               </label>
                             </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-5">Create Labour Job</label>
                          <div class="col-sm-7">
                             <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="islabourjob" value="1">
                               </label>
                             </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-5">Create Materials Job</label>
                          <div class="col-sm-7">
                             <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="ismaterialsjob" value="1">
                               </label>
                             </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-5">Create Safety Sheet Job</label>
                          <div class="col-sm-7">
                             <div class="checkbox">
                               <label>
                                   <input type="checkbox" name="issafetysheetjob" value="1">
                               </label>
                             </div>
                          </div>
                      </div>
                        </div>
                  </div>
                  <div class="modal-footer"> 
                      <input type ="hidden" name ="contractid" id ="contractid" value =""/>  
                      <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving...</span><span style="display:block;">Create Jobs</span></button>
                      <button type ="button" id="cancel" name ="btnCancel" class="btn btn-default" data-loading-text="Cancel" data-dismiss="modal" aria-label="Close" >Cancel</button>
                  </div>

                  </form>
              </div>
            </div>
            </div>
           
    
        </div><!-- /.box -->	
    </div>
</div>