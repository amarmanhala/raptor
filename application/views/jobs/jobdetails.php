<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
                <form name ="jobdetail_form" id ="jobdetail_form" class="form-horizontal" method ="post">
                    <div class="row">
                        <div class="col-sm-8">
                            
                    <table class="table border-zero-only">
                        <tbody>
                             <tr>
                                 <td style ="width:140px;"><label>Job Id:</label></td>
                                 <td><?php echo $job['jobid'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Job Status:</label></td>
                                 <td><?php echo $job['portaldesc'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Site:</label></td>
                                 <td><?php echo $job['siteline1'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Site Address:</label></td>
                                 <td><?php echo $job['location'];?></td>
                             </tr>

                             <tr>
                                 <td><label>Territory:</label></td>
                                 <td><?php echo $job['territory'];?></td>
                             </tr>
                             <tr>
                                 <td><label><?php echo $job['custordref1_label'];?>:</label></td>
                                 <td><?php echo $job['custordref'];?>
                             </tr>
                             <tr>
                                 <td><label><?php echo $job['custordref2_label'];?>:</label></td>
                                 <td><?php echo $job['custordref2'];?></td>
                             </tr>
                             <?php if($job['custordref3_access']) { ?>
                             <tr>
                                 <td><label><?php echo $job['custordref3_label'];?>:</label></td>
                                 <td><?php echo $job['custordref3'];?></td>
                             </tr>
                             <?php } ?>
                             <?php if(isset($ContactRules['EDIT_JOB_GLCODE']) && $ContactRules['EDIT_JOB_GLCODE'] == 1) { ?>
                             <tr>
                                 <td><label>Gl Code:</label></td>
                                 <td>
                                    <div class="input-group input-group-sm">
                                        <select id="jobdetail_glcode" name="jobdetail_glcode" class="form-control">
                                            <option value="">-Select-</option>
                                            <?php foreach($glcodes as $key=>$value) {
                                                $selected = '';
                                                if($job['custglchartid'] == $value['id']) {
                                                    $selected = ' selected';
                                                }
                                            ?>
                                                <option value="<?php echo $value['id'];?>"<?php echo $selected;?>><?php echo $value['glcode'];?></option> 
                                            <?php } ?>
                                        </select>
                                        <span class="input-group-btn">
                                            <button type="submit" id="updateglcode" class="btn btn-default btn-flat" data-loading-text ="Saving...">Update</button>
                                        </span>
                                    </div>
                                     
                                 </td>
                             </tr>
                             <?php } else if($job['custglchartid'] != 0) { ?>
                             <tr>
                                 <td><label>Gl Code:</label></td>
                                 <td><?php echo $job['glcode'];?></td>
                             </tr>
                             <?php } ?>
                             <tr>
                                 <td><label>Priority:</label></td>
                                 <td><?php echo $job['priority'];?></td>
                             </tr>
                              <tr>
                                 <td><label>$ Limit:</label></td>
                                 <td><?php echo $job['notexceed'];?></td>
                             </tr>
                              <tr>
                                 <td><label>Quoted Required?:</label></td>
                                 <td><?php echo $job['quoted'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Entry Date:</label></td>
                                 <td><?php echo $job['pdate'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Due Date:</label></td>
                                 <td><?php echo $job['duedate'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Due Time:</label></td>
                                 <td><?php echo $job['duetime'];?></td>
                             </tr>
                             <?php if($job['quoteapprovalby'] != '' && $job['quoteapprovalby'] != NULL) {?>
                             <tr>
                                 <td><label>Quote Approved:</label></td>
                                 <td><?php echo $job['quoteapprovalby'] .' on date '. $job['qdateaccepted'] ;?></td>
                             </tr>
                             <?php } ?>
                             <?php if($job['jobapprovalby'] != '' && $job['jobapprovalby'] != NULL) {?>
                             <tr>
                                 <td><label>Job Approval:</label></td>
                                 <td><?php echo $job['jobapprovalby'] .' on date '. $job['jobapprovaldate'] ;?></td>
                             </tr>
                             <?php } ?>
                             <?php if($job['variationapprovalby'] != '' && $job['variationapprovalby'] != NULL) {?>
                             <tr>
                                 <td><label>Variation Approval:</label></td>
                                 <td><?php echo $job['variationapprovalby'] .' on date '. $job['vapprovaldate'] ;?></td>
                            </tr>
                            <?php } ?>
                             <?php if($job['vapprovalref'] != '' && $job['vapprovalref'] != NULL) {?>
                            <tr>
                                 <td><label>Variation Ref:</label></td>
                                 <td><?php echo $job['vapprovalref'];?></td>
                             </tr>
                             <?php } ?>
                       
                             <tr>
                                 <td><label>Completion Date:</label></td>
                                 <td><?php echo $job['jcompletedate'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Site FM:</label></td>
                                 <td><?php echo $job['sitefm'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Site Contact:</label></td>
                                 <td><?php echo $job['sitecontact'];?></td>
                             </tr>
                             <tr>
                                 <td><label>Job Description:</label></td>
                                 <td><?php echo $job['jobdescription'];?></td>
                             </tr>
                        </tbody>
                    </table>    
                        </div>
                        <div class="col-sm-4">
                            <table class="table table-bordered">
                                
                                <?php if(!$this->session->userdata('raptor_role') != 'site contact' && $canapprove == 1){
                                  if($job['quoterqd'] == 'on' && $job['quotestatus'] == 'pending_approval'){ ?>
                          <tr>
                                    <td colspan="2" class="text-right">
                                        
                                <button id="approveQuotebtn" class="btn btn-success">Approve Quote</button>
                                <button id="declineQuotebtn" class="btn btn-warning">Decline Quote</button>
                    </td>
                                </tr>
                           <?php }  
                           } ?>
                                
                                 <?php if (isset($ContactRules["direct_allocate"]) && $ContactRules["direct_allocate"] == "1"){?>
                            
                                <tr>
                                    <td colspan="2" class="text-right">
                                        <?php if(count($poData)==0){?>
                                            <button type="button" id="allocatebtn" class="btn btn-info btn-sm" >Allocate</button>
                                        <?php } elseif (count($poData) > 0 && $poData['accepted'] !='on') { ?>
                                                <button type="button" id="reallocatebtn" class="btn btn-info btn-sm" >Reallocate</button>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php if(count($poData)>0){?>
                                    <tr>
                                        <td style ="width:120px;"><label>Supplier:</label></td>
                                        <td><?php echo $poData['suppliername'];?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Contact:</label></td>
                                        <td><?php echo $poData['primarycontact'];?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Phone:</label></td>
                                        <td><?php echo $poData['supplierphone'];?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Email:</label></td>
                                        <td><?php echo $poData['supplieremail'];?></td>
                                    </tr>
                                <?php }?>
                        <?php }?> 
                            </table>
                        </div>
                    </div>
                    
                    <input  type ="hidden" id ="jobid" name="jobid" value ="<?php echo $job['jobid'];?>" />
                    <input type ="hidden" name ="edit_report" id ="edit_report" value ="<?php echo $EDIT_REPORT;?>"/> 
                </form>
            </div>
        </div>	
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
                                     <?php echo $job['jobid'];?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="input" class="col-sm-3 control-label">Description</label>
                                <div class="col-sm-9" id="jdesc" style="padding-top: 6px;">
                                     <?php echo $job['jobdescription'];?>
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
                                            if($value['typecode'] == 'I' && $value['typecode'] != 'L') {
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
                                  <input type ="hidden" name ="jobid" id ="jobid" value ="<?php echo $job['jobid'];?>"/> 
                                 <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Allocate</button>
                                  <button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
                               </div>
                        </div> 

                      </div>     

                </form>
            </div>
        </div>
</div>
<?php 
if(!$this->session->userdata('raptor_role') != 'site contact' && $canapprove == 1){
if($job['quoterqd'] == 'on' && $job['quotestatus'] == 'pending_approval'){  
  $this->load->view('quotes/quotedeclinemodal');  
$this->load->view('quotes/quoteapprovalmodal');  

}
}
?>