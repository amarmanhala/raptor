<div class="row" id="waitingApprovalJobsCtrl"  ng-controller="waitingApprovalJobsCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title text-blue">Waiting Approval</h3>
                 
            </div>
            <div class= "box-header  with-border"> 
                <div class="row form-horizontal">
                    
                    <div  class="col-sm-8 col-md-6"> 
                    <?php if (!isset($ContactRules["show_newjob_approval"]) || (isset($ContactRules["show_newjob_approval"]) && $ContactRules["show_newjob_approval"] == "1")){ 
                        if(!$this->session->userdata('raptor_role')!='site contact'){ ?> 
                        <?php if (!isset($ContactRules["direct_allocate"]) || (isset($ContactRules["direct_allocate"]) && $ContactRules["direct_allocate"] == "0")){?>
                            <button id="approvebtn" class="btn btn-success" >Approve</button>
                        <?php }else{?> 
                            <button id="allocatebtn" class="btn btn-success" >Allocate</button>
                        <?php }?> 
                        <button id="requestquotebtn" class="btn btn-primary">Request Quote</button>
                        <button id="declinebtn" class="btn btn-warning">Decline</button>
                        
                         
                     
                        
                    <?php }  
                      } ?>
                    </div>
                    <div class= "col-sm-4 col-md-2" style="padding-right: 0px;"> 
                        <select id="suburb" name="suburb" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.suburb">
                            <option value="">All Suburbs</option>
                        <?php foreach($waitingapprovalSuburb as $key=>$value) {  ?>
                            <option value="<?php echo $value['suburb'];?>"  ><?php echo $value['suburb'];?> (<?php echo $value['count'];?>)</option> 
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
                        <select id="wa_glcode" name="wa_glcode" class="form-control">
                            <option value="">Select GL Code</option>
                            <?php foreach($glcodes as $key=>$value) { ?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['glcode'];?></option> 
                            <?php } ?>
                        </select>
                        <span class="input-group-btn">
                            <button type="submit" id="wa_updateglcode" class="btn btn-default btn-flat" data-loading-text ="Saving...">Update</button>
                        </span>
                    </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="box-body">
                <?php if ((isset($ContactRules["internal_allocate"]) && $ContactRules["internal_allocate"] == "1" && count($technicians)==0)){ ?> 
                    <div class="alert alert-warning alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                        No contacts have been set up for job allocation.
                      </div>
                      
                    <?php } ?>
                <div id="waitingapprovaltbl">
                    <div ui-grid = "gridWaitingApproval" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns ui-grid-pinning ui-grid-selection class="gridwithselect1"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay" style = "display:none">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>	
    </div>
    
    <div class="modal fade" id ="allocationModal" tabindex="-1" role ="dialog" aria-labelledby="allocationModalLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Job Allocation</h4>
                </div>
                <form name ="allocationform" id ="allocationform" class="form-horizontal" method ="post"  >
                    <div class="modal-body">
 
                            <div class="status"></div>
                            <div class="form-group">
                                <label for="input" class="col-sm-3 control-label">Job ID</label>
                                <div class="col-sm-9" id="jbid" style="font-weight: bold;padding-top: 6px;">
                                     
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9" id="jdesc" style="padding-top: 6px;">
                                     
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="input" class="col-sm-3 control-label">Allocate To</label>
                                <div class="col-sm-9" >
                                    <label class="radio-inline"><input type="radio" value="DCFM" name="allocateto" id="rdbdcfm" >DCFM</label>
                                    <label class="radio-inline"><input type="radio" value="Landlord" name="allocateto" id="rdblandlord" >Landlord</label>
                                <?php if ((isset($ContactRules["internal_allocate"]) && $ContactRules["internal_allocate"] == "1")){ ?> 
                                    <label class="radio-inline"><input type="radio" value="Internal" name="allocateto" id="rdbinternal" >Internal</label>
                                <?php }?>
                                    
                                    <label class="radio-inline"><input type="radio" value="Supplier" name="allocateto" id="rdbsupplier" >Other Supplier</label>
                                </div>
                            </div>
                            <div class="form-group" id="othersupplierdiv" >
                                <label for="input" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9" >
                                    <div class="input-group">
                                        <select id="supplierid" name="supplierid" class="form-control selectpicker" >
                                            <option value="">Select Supplier</option>
                                        <?php foreach($suppliers as $key=>$value) { 
                                            if($value['typecode'] != 'I' && $value['typecode'] != 'L') {
                                            ?>
                                            <option value="<?php echo $value['customerid'];?>"  ><?php echo $value['companyname'];?></option> 
                                            <?php
                                            }
                                            } ?>
                                        </select>
                                        <div class="input-group-addon">
                                            <a href="<?php echo site_url("suppliers/add");?>?from=jobs"    title="Add New Supplier" ><i class="fa fa-plus" title="Add New Supplier"></i></a>
                                        </div>
                                    </div>
                                </div>
                                 
                            </div>
                            <div class="form-group" id="internalsupplierdiv" >
                                <label for="input" class="col-sm-3 control-label">Supplier</label>
                                <div class="col-sm-9" >
                                    <div class="input-group">
                                        <select id="internasupplierid" name="internasupplierid" class="form-control selectpicker" >
                                            <option value="">Select Supplier</option>
                                         <?php foreach($suppliers as $key=>$value) { 
                                            if($value['typecode'] == 'I') {
                                            ?>
                                            <option value="<?php echo $value['customerid'];?>"  ><?php echo $value['companyname'];?></option> 
                                            <?php
                                            }
                                            } ?>
                                        </select>
                                        <div class="input-group-addon">
                                            <a href="<?php echo site_url("suppliers/add");?>?from=jobs"    title="Add New Supplier" ><i class="fa fa-plus" title="Add New Supplier"></i></a>
                                        </div>
                                    </div>
                                </div>
                                 
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                              <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                              <div class="col-sm-9">
                                  <input type ="hidden" name ="jobid" id ="jobid" value =""/> 
                                 <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Allocate</button>
                                  <button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
                               </div>
                        </div> 

                      </div>     

                </form>
            </div>
        </div>
    </div>
    
</div>
 

 