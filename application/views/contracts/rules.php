<div class= "box" id = "ContractRulesCtrl" ng-controller= "ContractRulesCtrl" >
    <form name = "contractrulesform" action="" id = "contractrulesform" class= "form-horizontal" method = "post">
        <div class= "box-header with-border">
            <h3 class= "box-title text-blue">Contract Rules</h3>
        </div>
        <div class= "box-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class= "form-group <?php if (form_error('parentjobid')) echo ' has-error';?>">
                        <label for= "parentjobid" class= "col-sm-4 control-label">Parent Job</label>
                        <div class= "col-sm-6">
                            <div class="input-group input-group">
                               <input type = "text" class= "form-control" id = "parentjobid" name = "parentjobid" placeholder= "Parent Job" value = "<?php echo set_value('parentjobid', $contract['parentjobid']);?>"   readonly="readonly" />
                                <div class="input-group-btn">
                                    <?php  if($contract['parentjobid'] == 0 || $contract['parentjobid'] == NULL){ ?>
                                    <button type="button" class="btn btn-info" title = "Add Parent Job" onclick="openParentJob('add');"><i class="fa fa-plus" title = "Add Parent Job" ></i></button>                 
                                    <?php }
                                     else { ?>
                                    <button type="button" class="btn btn-info" title = "Edit Parent Job" onclick="openParentJob('edit');"><i class= "fa fa-edit" title = "Edit Parent Job"></i></button>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php echo form_error('parentjobid', '<span class= "help-block with-errors" for= "parentjobid" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('subjobmethodid')) echo ' has-error';?>">
                        <label for= "subjobmethodid" class= "col-sm-4 control-label">Create sub jobs</label>
                        <div class= "col-sm-6">
                            <div class="input-group input-group">
                                <select name = "subjobmethodid" id = "subjobmethodid" class= "form-control">
                                    <option value = ''>-Select-</option>
                                    <?php foreach ($subJobMethods as $key => $value) { ?>
                                          <option value = "<?php echo $value['id'];?>" <?php if (set_value('subjobmethodid', $contract['subjobmethodid']) == $value['id']) echo "selected";?>><?php echo $value['name'];?></option> 
                                    <?php } ?>
                                </select>
                                <div class="input-group-btn"> 
                                    <button type="button" class="btn btn-info" title = "Create Sub Jobs" onclick="openSubJob();"><i class="fa fa-plus" title = "Create Sub Jobs" ></i></button>                 
                                </div>
                            </div>
                            <?php echo form_error('subjobmethodid', '<span class= "help-block with-errors" for= "subjobmethodid" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('billingmethodid')) echo ' has-error';?>">
                        <label for= "billingmethodid" class= "col-sm-4 control-label">Billing Method</label>
                        <div class= "col-sm-6">
                            <select name = "billingmethodid" id = "billingmethodid" class= "form-control">
                                    <option value = ''>-Select-</option>
                                    <?php foreach ($billingMethods as $key => $value) { ?>
                                          <option value = "<?php echo $value['id'];?>" <?php if (set_value('billingmethodid', $contract['billingmethodid']) == $value['id']) echo "selected";?>><?php echo $value['name'];?></option> 
                                    <?php } ?>
                            </select>
                            <?php echo form_error('billingmethodid', '<span class= "help-block with-errors" for= "billingmethodid" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class= "form-group <?php if (form_error('custordref1')) echo ' has-error';?>">
                        <label for= "custordref1" class= "col-sm-4 control-label"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?></label>
                        <div class= "col-sm-6">
                            <input type = "text" class= "form-control" id = "custordref1" name = "custordref1" placeholder= "<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>" value = "<?php echo set_value('custordref1', $contract['custordref1']);?>"   readonly="readonly" />
                            <?php echo form_error('custordref1', '<span class= "help-block with-errors" for= "custordref1" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('custordref2')) echo ' has-error';?>">
                        <label for= "custordref2" class= "col-sm-4 control-label"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?></label>
                        <div class= "col-sm-6">
                            <input type = "text" class= "form-control" id = "custordref2" name = "custordref2" placeholder= "<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>" value = "<?php echo set_value('custordref2', $contract['custordref2']);?>"  readonly="readonly" />
                            <?php echo form_error('custordref2', '<span class= "help-block with-errors" for= "custordref2" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('custordref3')) echo ' has-error';?>">
                        <label for= "custordref3" class= "col-sm-4 control-label"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?></label>
                        <div class= "col-sm-6">
                            <input type = "text" class= "form-control" id = "custordref3" name = "custordref3" placeholder= "<?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?>" value = "<?php echo set_value('custordref3', $contract['custordref3']);?>" readonly="readonly"/>
                            <?php echo form_error('custordref3', '<span class= "help-block with-errors" for= "custordref3" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class= "form-group">
                <label for= "input" class= "col-sm-2 control-label">&nbsp;</label>
                <div class= "col-sm-10">
                    <input  type = "submit" class= "btn btn-primary" name = "companysave" value = "Save" />
                    <a href= "<?php echo site_url("contracts");?>" class= "btn btn-default">Cancel</a>
                    <input  type = "hidden" name = "contract_post_type" id = "contract_post_type" value = "rules"/>
                    <input  type = "hidden" name = "contractid" id = "contractid" value = "<?php echo $contract['id'];?>"/>

                </div>
            </div>
        </div>
    </form>
    
    <div class="modal fade" id ="ContractParentJobsModal" tabindex="-1" role ="dialog" aria-labelledby="UpdateContractschedulesModalLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Contract Parent Job</h4>
                </div>
                <form name ="parentJobform" id ="parentJobform" class="form-horizontal" method ="post"  >

                    <div class="modal-body">
                        <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                        <div id ="parentJobdiv" style ="display:none;"> 
                            <div class="status"></div>
                            <div class="form-group" id="jobiddiv">
                                <label for="name" class="col-sm-3 control-label">Job ID</label>
                                <div class="col-sm-4">
                                    <input type ="text" class= "form-control" readonly="readonly" name ="jobid" id ="jobid" value ="<?php echo $contract['parentjobid'];?>" placeholder="Job ID"/> 
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="customername" class="col-sm-3 control-label">Customer</label>
                                <div class="col-sm-9">
                                    <input type ="text" class= "form-control" readonly="readonly" name ="customername" id ="customername" value ="<?php echo $customer['companyname']; ?>" placeholder="Customer"/> 
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="contractname" class="col-sm-3 control-label">Contract</label>
                                <div class="col-sm-9">
                                    <input type ="text" class= "form-control" readonly="readonly" name ="contractname" id ="contractname" value ="<?php echo $contract['name'];?>" placeholder="Contract"/> 
                                    <input type ="hidden" name ="contractid" id ="contractid" value ="<?php echo $contract['id'];?>"/> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="siteaddress" class="col-sm-3 control-label">Address</label>
                                <div class="col-sm-9">
                                    <div class= "has-feedback">
                                        <input type="text" ng-model="siteaddress" id="siteaddress" name="siteaddress" placeholder="search .." uib-typeahead="site as site.address for site in getSiteAddress($viewValue)"  typeahead-editable="false"    typeahead-on-select="onSiteAddressSelect($item, $model, $label)" typeahead-loading="loadingSiteAddress"   class="form-control"   />
                                        <span class="form-control-feedback" ><i ng-show="loadingSiteAddress" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingSiteAddress" ></i></span>
                                    </div>
                                    <input type ="hidden" name ="labelid" id ="labelid" value ="<?php echo $contract['labelid'];?>" /> 
                                </div>
                            </div>
                     
                            <div class= "form-group">
                                <label for= "contactid" class= "col-sm-3 control-label">Site FM</label>
                                <div class= "col-sm-6">
                                    <select name = "contactid" id = "contactid" class= "form-control">
                                        <option value = ''>-Select-</option>
                                        <?php foreach ($sitefm_contacts as $key => $value) { ?>
                                              <option value = "<?php echo $value['contactid'];?>"  <?php if($value['contactid'] == $contract['contactid']) echo 'selected'; ?> ><?php echo $value['sitefm'];?></option> 
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class= "form-group">
                                <label for= "custordref1" class= "col-sm-3 control-label"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?></label>
                                <div class= "col-sm-4">
                                    <input type = "text" class= "form-control" id = "custordref1" name = "custordref1" placeholder="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>" value = "<?php echo $contract['custordref1'];?>"  />
                                </div>
                            </div>
                            <div class= "form-group">
                                <label for= "custordref2" class= "col-sm-3 control-label"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 3';?></label>
                                <div class= "col-sm-4">
                                    <input type = "text" class= "form-control" id = "custordref2" name = "custordref2" placeholder="<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>" value = "<?php echo $contract['custordref2'];?>"  />
                                </div>
                            </div>
                            <div class= "form-group">
                                <label for= "custordref3" class= "col-sm-3 control-label"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?></label>
                                <div class= "col-sm-4">
                                    <input type = "text" class= "form-control" id = "custordref3" name = "custordref3" placeholder="<?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?>" value = "<?php echo $contract['custordref3'];?>"  />
                                </div>
                            </div>
                            <div class= "form-group">
                                <label for= "visits_created" class= "col-sm-3 control-label">Description</label>
                                <div class= "col-sm-9">
                                    <textarea  class= "form-control" id = "description" name = "description" placeholder="Description" /><?php echo $contract['description'];?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                            <div class="col-sm-9">
                                 
                                <input type ="hidden" name ="mode" id ="mode" value =""/> 
                                &nbsp;<button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                                &nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving..." data-dismiss="modal" aria-label="Close">Cancel</button>
                            </div>
                        </div> 
                    </div>     
                </form>
            </div>
        </div>
    </div>
            
     
</div>	
