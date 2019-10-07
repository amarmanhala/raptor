
<div class="modal fade" id ="quoteApproveModal" tabindex="-1" role ="dialog" aria-labelledby="quoteApproveModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Approve Quote Request</h4>
        </div>
    <form name ="approvequoteform" id ="approvequoteform" class="form-horizontal" method ="post"  >
      
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
                          <input type ="hidden" name ="openfrom" id ="openfrom" value =""/> 
                         <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Approve</button>
                    &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default">Cancel</button>
                       </div>
                </div> 
 
              </div>     
           
	   </form>
    </div>
  </div>
</div>