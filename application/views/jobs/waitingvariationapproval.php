<div class="row"  id="waitingVariationApprovalJobsCtrl"  ng-controller="waitingVariationApprovalJobsCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">Waiting Variation Approval</h3>
                
            </div>
             <div class= "box-header  with-border"> 
                <div class="row form-horizontal">
                    
                    <div  class="col-sm-12 col-md-4"> 
                     <?php if (!isset($ContactRules["show_newjob_approval"]) || (isset($ContactRules["show_newjob_approval"]) && $ContactRules["show_newjob_approval"] == "1")){ 
                        if(!$this->session->userdata('raptor_role')!='site contact'){ ?> 
                        <button id="apprivebtn" class="btn btn-success">Approve</button>
                        <button id="declinebtn" class="btn btn-warning">Decline</button>
                        <button id="printvariationbtn" class="btn btn-primary">Print</button>
                    <?php }
                         }
                        ?>
                    </div>
                    <div class= "col-sm-6 col-md-2" style="padding-right: 0px;" > 
                         <select id="suburb" name="suburb" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.suburb">
                            <option value="">All Suburbs</option>
                        <?php foreach($waitingvariationapprovalJobSuburb as $key=>$value) {  ?>
                            <option value="<?php echo $value['suburb'];?>"  ><?php echo $value['suburb'];?> (<?php echo $value['count'];?>)</option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-6 col-md-2" > 
                         <select id="sitefm" name="sitefm" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.sitefm">
                            <option value="">All Site FM</option>
                        <?php foreach($waitingvariationapprovalJobSiteFM as $key=>$value) {  ?>
                            <option value="<?php echo $value['sitefm'];?>"  ><?php echo $value['sitefm'];?> (<?php echo $value['count'];?>)</option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div  class="col-sm-12 col-md-4"> 
                        <div class="input-group input-group">
                            <input type="text" class="form-control" placeholder="Search By: Job ID/<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>/Site Address/Suburb/State" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                 <?php if(isset($ContactRules['EDIT_JOB_GLCODE']) && $ContactRules['EDIT_JOB_GLCODE'] == 1) { ?>
                <div class="row form-horizontal" style="margin-top: 10px;">
                    <div  class="col-sm-12 col-md-4"> 
                    <div class="input-group input-group-sm">
                        <select id="wv_glcode" name="wv_glcode" class="form-control">
                            <option value="">Select GL Code</option>
                            <?php foreach($glcodes as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['glcode'];?></option> 
                            <?php } ?>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" id="wv_updateglcode" class="btn btn-default btn-flat" data-loading-text ="Saving...">Update</button>
                        </span>
                    </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="box-body">
                <div id="waitingvariationapprovaljobstbl">
                    <div ui-grid = "gridWaitingVariationApproval" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns ui-grid-selection class= "gridwithselect1"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" style = "display:none">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
             
        </div>	
    </div>
</div>
<div class="modal fade" id ="jobVariationDeclineModal" tabindex="-1" role ="dialog" aria-labelledby="jobVariationDeclineModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
        <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Decline Job Variation Request</h4>
      </div>
        <form name ="declinejonvariationform" id ="declinejonvariationform" class="form-horizontal" method ="post"  >
      
        <div class="modal-body">
         
            <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id ="sitegriddiv" style ="display:none;"> 
                 <div class="status"></div>
       
       
                <div class="form-group">
                      <label for="name" class="col-sm-3 control-label">Reason:</label>
                      <div class="col-sm-6">
                          <select class="form-control" id ="reason" name ="reason">
                            <option value ="">-Select-</option>
                            <?php foreach($variationDeclineReasons as $key=>$value) {  ?>
                            <option value="<?php echo $value['reason'];?>"  ><?php echo $value['reason'];?></option> 
                        <?php } ?>
                            
                          </select>
                       </div>
                </div> 
                 <div class="form-group">
                      <label for="name" class="col-sm-3 control-label">Notes:</label>
                      <div class="col-sm-9">
                          <textarea class="form-control" id ="notes" name ="notes"></textarea>
                       </div>
                </div>  
            </div>
      </div>
            <div class="modal-footer">
                <div class="form-group">
                      <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                      <div class="col-sm-9">
                          <input type ="hidden" name ="jobid" id ="jobid" value =""/> 
                         <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Decline</button>
                    &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default">Cancel</button>
                       </div>
                </div> 



              </div>     
           
	   </form>
    </div>
  </div>
</div>

<div class="modal fade" id ="jobVariationApproveModal" tabindex="-1" role ="dialog" aria-labelledby="jobVariationApproveModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Approve Job Variation Request</h4>
        </div>
    <form name ="approvejonvariationform" id ="approvejonvariationform" class="form-horizontal" method ="post"  >
      
        <div class="modal-body">
         
            <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id ="sitegriddiv" style ="display:none;"> 
                <div class="status"></div>
                 <div class="form-group">
                    <label for="name" class="col-sm-3" class="control-label">Completion Date:</label>
                     <div class="col-sm-4">
                            <div class="input-group">
                                <input type ="text" class="form-control datepicker" id ="duedate" name ="duedate" readonly="readonly" />
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                       </div>
                    <div class="col-sm-4">
                        <div class="bootstrap-timepicker">
                            <div class="input-group">
                                <input type ="text" class="form-control timepicker" id ="duetime" name ="duetime" readonly="readonly" />
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1'; ?> </label>
                    <div class="col-sm-5" >
                        <input type="text" name="custordref" id="custordref" class="form-control" value="" />
                    </div>
                </div>
                <div class="form-group" <?php if (isset($ContactRules["use_jobid_as_custordref2_in_client_portal"]) && $ContactRules["use_jobid_as_custordref2_in_client_portal"] == "1"){ echo 'style="display:none;"'; }?>>
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 1'; ?></label>
                    <div class="col-sm-5" >
                        <input type="text" name="custordref2" id="custordref2" class="form-control" value="" />
                    </div>
                </div>
                <div class="form-group" <?php if (isset($ContactRules["hide_custordref3_in_client_portal"]) && $ContactRules["hide_custordref3_in_client_portal"] == "1"){ echo 'style="display:none;"'; }?>>
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3'; ?> </label>
                    <div class="col-sm-5" >
                        <input type="text" name="custordref3" id="custordref3" class="form-control" value="" />
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="name" class="col-sm-3 control-label">Notes:</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id ="notes" name ="notes"></textarea>
                    </div>
                </div>  
            </div>
        </div>
            <div class="modal-footer">
                <div class="form-group">
                      <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                      <div class="col-sm-9">
                          <input type ="hidden" name ="jobid" id ="jobid" value =""/> 
                         <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Approve</button>
                    &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default">Cancel</button>
                       </div>
                </div> 



              </div>     
           
	   </form>
    </div>
  </div>
</div>
 

 