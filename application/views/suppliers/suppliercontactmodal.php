<div class="modal fade" id="supplierContactModal" tabindex="-1" role="dialog" aria-labelledby="supplierContactModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	 
        <form name="supplierContactForm" id="supplierContactForm" class="form-horizontal"   autocomplete="off" novalidate>
            
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" >Add Contact</h4>
        </div>
            
        <div class="modal-body">
             <div class="status">
                 <div class="alert alert-danger" style="display:none;" id="contactModalErrorMsg"></div>
                <div class="alert alert-success" style="display:none;" id="contactModalSuccessMsg"></div>
            </div>

               <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Name</label>
                    <div class= "col-sm-5">  
                        <input type = "text" id="firstname" name = "firstname" class= "form-control" placeholder= "Name" />
                    </div> 
 
                    <div  class= "col-sm-4">  
                       <input type = "text" id="surname" name = "surname" class= "form-control" placeholder= "Last Name" />
                   </div> 
                </div>
                <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Position</label>
                    <div class= "col-sm-5">  
                        <input type = "text" id="position" name = "position" class= "form-control" placeholder= "position" />
                    </div> 
                </div>
                <div class= "form-group" >
                    <label for= "input" class= "col-sm-3 control-label">Trade</label>
                   <div class= "col-sm-5"> 
                        <select name = "tradeid" id = "tradeid" class= "form-control">
                            <option value = '0'>None</option>
                            <?php foreach($se_trades as $val) { ?>
                                <option value="<?php echo $val['id'];?>"><?php echo $val['se_trade_name'];?></option>
                            <?php } ?>
                        </select>
                   </div> 
               </div>  
                 <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Reports To</label>
                   <div class= "col-sm-5"> 
                        <select name = "bossid" id = "bossid" class= "form-control">
                            <option value = '0'>None</option>
                            <?php foreach($reportstocontacts as $val) { ?>
                                <option value="<?php echo $val['contactid'];?>"><?php echo $val['name'];?></option>
                            <?php } ?>
                        </select>
                       </div> 
               </div>
                

                <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Mobile </label>
                    <div class= "col-sm-4">
                        <input type = "text" class= "form-control" id = "mobile" name = "mobile" pattern= "[0-9]{4} [0-9]{3} [0-9]{3}" data-inputmask= '"mask": "9999 999 999"' data-mask  />
                    </div>
                    <label for= "input" class= "col-sm-1 control-label">Phone</label>
                    <div class= "col-sm-4">
                        <input type = "text" class= "form-control" id = "phone" name = "phone" pattern= "[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask= '"mask": "99 9999 9999"' data-mask />
                    </div>
                </div>  

               <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Email</label>
                   <div class= "col-sm-9">  
                        <input type = "text" id="email" name = "email" class= "form-control" placeholder= "Email" />
                   </div> 
               </div>
 
                <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Address Line 1 </label>
                    <div class= "col-sm-9">
                       <input type = "text" class= "form-control" id = "street1" name = "street1" placeholder= "Address1" />
                    </div>
             </div>
             <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Address Line 2</label>
                    <div class= "col-sm-9">
                       <input type = "text" class= "form-control" id = "street2" name = "street2" placeholder= "Address2" />
                    </div>
             </div>
             <div class= "form-group"  >
                     <label for= "input" class= "col-sm-3 control-label">Suburb/State/Post Code</label>
                     <div class= "col-sm-4">
                        <input type="text" id="suburb" name="suburb" placeholder="Suburb" data-suburb= "suburb1"  data-state = "state" data-postcode = "postcode"  class="form-control suburbtypeahead" />
                        <input type="hidden" id="suburb1" name="suburb1" class= "updatesuburb" data-suburb= "suburb" value="" />
                     </div>
                     <div class= "col-sm-2">
                        <input type="text" id="state" name="state"  readonly="readonly" placeholder="State" class="form-control" >
                        
                     </div>
                     
                     <div class= "col-sm-3">
                        <input type="text" id="postcode" name="postcode"  readonly="readonly" placeholder="Postcode" class="form-control postcodetypeahead" >
                     </div>

             </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">On Schedule</label>
                <div class="col-sm-6">
                   <div class="checkbox">
                     <label>
                         <input type="checkbox" name="etp_onschedule" value="1">
                     </label>
                   </div>
                </div>
            </div>
            
            
            <div class="form-group">
                <label class="col-sm-3 control-label">Primary Contact</label>
                <div class="col-sm-6">
                   <div class="checkbox">
                     <label>
                         <input type="checkbox" name="primarycontact" value="1">
                     </label>
                   </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Active</label>
                <div class="col-sm-6">
                   <div class="checkbox">
                     <label>
                         <input type="checkbox" name="active" value="1">
                     </label>
                   </div>
                </div>
            </div>
            
        </div>
        <div class="modal-footer"> 
            <input type ="hidden" name ="contactid" id ="contactid" value =""/>
            <input type ="hidden" name ="customerid" id ="customerid" value =""/> 
            <input type ="hidden" name ="mode" id ="mode" value =""/> 
            <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
            <button type ="button" id="cancel" name ="btnCancel" class="btn btn-default" data-loading-text="Cancel" data-dismiss="modal" aria-label="Close" >Cancel</button>
        </div>
        
        </form>
    </div>
  </div>
</div>