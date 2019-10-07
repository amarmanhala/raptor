<div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	 
        <form name="addContactForm" id="addContactForm" class="form-horizontal" ng-submit="saveAddContact(addContactForm)" autocomplete="off" novalidate>
            
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
                    <label for= "input" class= "col-sm-3 control-label">Contact Name</label>
                    <div class= "col-sm-5">  
                        <input type = "text" id="firstname" name = "firstname" class= "form-control" placeholder= "Name" />
                    </div> 
 
                    <div style="display:none;" class= "col-sm-4">  
                       <input type = "text" id="surname" name = "surname" class= "form-control" placeholder= "Last Name" />
                   </div> 
                    <div style="display:none;" class= "col-sm-4" id="FromAddressDiv">  
                        <button type="button" class="btn btn-default" id="btnfromaddress">From Address</button>
                   </div>
               </div>
                 <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Position</label>
                    <div class= "col-sm-5">  
                        <input type = "text" id="position" name = "position" class= "form-control" placeholder= "position" />
                    </div> 

               </div>
                 <div class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Reports To</label>
                   <div class= "col-sm-5"> 
                        <select name = "bossid" id = "bossid" class= "form-control">
                            <option value = ''>-Select-</option>
                            <?php foreach($reportstocontacts as $val) { ?>
                                <option value="<?php echo $val['contactid'];?>"><?php echo $val['name'];?></option>
                            <?php } ?>
                        </select>
                       </div> 
               </div>
            <div class= "form-group" style="display:none;">
                    <label for= "input" class= "col-sm-3 control-label">Role</label>
                   <div class= "col-sm-5"> 
                        <select name = "role" id = "role" class= "form-control">
                            <option value = ''>-Select-</option>
                            <?php foreach($role as $val) { ?>
                                <option value="<?php echo $val['name'];?>"><?php echo $val['name'];?></option>
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

            <div style="display:none;" class= "form-group">
                    <label for= "input" class= "col-sm-3 control-label">Mail Group</label>
                   <div class= "col-sm-9"> 
                        <select id = "mailgroup" name = "mailgroup[]" class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "Mail Group" data-size = "auto" data-width= "100%">
                            <?php foreach($mailgroups as $val) { ?>
                                <option value="<?php echo $val['mailgroupdesc'];?>"><?php echo $val['mailgroupdesc'];?></option>
                            <?php } ?>
                        </select>
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
                       <select name = "state" id = "state" class= "form-control">
                            <option value = ''>-Select-</option>
                            <?php foreach($states as $val) { ?>
                                <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                            <?php } ?>
                       </select>
                     </div>
                     
                     <div class= "col-sm-3">
                        <input type="text" id="postcode" name="postcode" placeholder="Postcode" class="form-control postcodetypeahead" >
                     </div>

             </div>
             <div class= "form-group">
                     <label for= "input" class= "col-sm-3 control-label">Territory</label>
                     <div class= "col-sm-4">
                         <input type = "text" class= "form-control" id = "territory" name = "territory" readonly = "readonly" />
                     </div>
                </div> 
            
        </div>
        <div class="modal-footer">
            <input type="hidden" id="fromaddress" name="fromaddress" />
            <input type="hidden" id="labelid" name="labelid" />
            <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
            <button type ="button" id="cancel" name ="btnCancel" class="btn btn-default" data-loading-text="Cancel" data-dismiss="modal" aria-label="Close"  >Cancel</button>
        </div>
        
        </form>
    </div>
  </div>
</div>