<div class="modal fade" id="purchaseOrderModal" tabindex="-1" role="dialog" aria-labelledby="purchaseOrderModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	 
        <form name="purchaseOrderForm" id="purchaseOrderForm" class="form-horizontal"   autocomplete="off" novalidate>
            
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"  ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" >Add Purchase Order</h4>
        </div>
            
        <div class="modal-body">
             <div class="status">
                 <div class="alert alert-danger" style="display:none;" id="contactModalErrorMsg"></div>
                <div class="alert alert-success" style="display:none;" id="contactModalSuccessMsg"></div>
            </div>

               <div class= "form-group">
                    <label for= "ponumber" class= "col-sm-3 control-label">PO Number</label>
                    <div class= "col-sm-5">  
                        <input type = "text" id="ponumber" name = "ponumber" class= "form-control" placeholder= "PO Number" />
                    </div> 
  
                </div>
             <?php if(isset($ContactRules['GL_CODE_MANDATORY']) && $ContactRules['GL_CODE_MANDATORY'] == 1) { ?>
                <div class= "form-group" >
                    <label for= "glcode" class= "col-sm-3 control-label">GL Code</label>
                   <div class= "col-sm-5"> 
                        <select name = "glcode" id = "glcode" class= "form-control">
                            <option value = ''>Select GL Code</option>
                            <?php foreach ($glcodes as $key => $value) { ?>
                            <option value = "<?php echo $value['accountname'];?>" ><?php echo $value['accountname'];?></option> 
                        <?php } ?>
                        </select>
                   </div> 
               </div> 
			   <?php } ?>
                <div class= "form-group">
                    <label for= "amount_ex_tax" class= "col-sm-3 control-label">Amount(exc. GST)</label>
                    <div class= "col-sm-5">  
                        <div class= "input-group">
                            <div class= "input-group-addon">
                                <?php echo RAPTOR_CURRENCY_SYMBOL;?>
                            </div>
                            <input type = "text" id="amount_ex_tax" name = "amount_ex_tax" class= "form-control allownumericwithdecimal" value="" placeholder= "" />
                        </div>
                    </div> 
                </div>
            
            
             
                <div class= "form-group">
                    <label for= "description" class= "col-sm-3 control-label">Description</label>
                    <div class= "col-sm-9">
                       <input type = "text" class= "form-control" id = "description" name = "description" placeholder= "Description" />
                    </div>
                </div>
                <div class= "form-group">
                    <label for= "fromdate" class= "col-sm-3 control-label">From Date</label>
                    <div class= "col-sm-5">
                        <div class= "input-group">
                            <input type = "text" class= "form-control datepicker" id = "fromdate" name = "fromdate" placeholder="From Date" value = "" readonly=""/>
                            <div class= "input-group-addon">
                                <i class= "fa fa-calendar"></i>
                            </div>
                        </div>
                     </div>
                </div>
               <div class= "form-group">
                   <label for= "todate" class= "col-sm-3 control-label">To Date</label>
                   <div class= "col-sm-5">
                       <div class= "input-group">
                           <input type = "text" class= "form-control datepicker" id = "todate" name = "todate" placeholder="To Date" value = ""  readonly="" />
                           <div class= "input-group-addon">
                               <i class= "fa fa-calendar"></i>
                           </div>
                       </div>
                   </div>
               </div>
               
           
            
        </div>
        <div class="modal-footer"> 
            <input type ="hidden" name ="customer_po_id" id ="customer_po_id" value =""/> 
            <input type ="hidden" name ="mode" id ="mode" value =""/> 
            <button type="submit" id="btnsave" name ="btnsave" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
            <button type ="button" id="btncancel" name ="btnCancel" class="btn btn-default" data-loading-text="Cancel" data-dismiss="modal" aria-label="Close" >Cancel</button>
        </div>
        
        </form>
    </div>
  </div>
</div>