<div class="row">
    <div class="col-md-12">
        <form name="tab_supplier_form" id="tab_supplier_form" class="form-horizontal" action=""  role="form" method="post">
            <div class="box">
                <div class="box-header  with-border">
                    <h3 class="box-title text-blue">Supplier Info</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Supplier:</label>
                        <div class="col-sm-8 col-md-6">
                            <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Name" value="<?php echo $asset['supplier_name'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Address:</label>
                        <div class="col-sm-8 col-md-6">
                            <input type="text" class="form-control" id="supplier_address" name="supplier_address" placeholder="Address" value="<?php echo $asset['supplier_address'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Phone:</label>
                        <div class="col-sm-5 col-md-3">
                            <input type="text" class="form-control" id="supplier_phone" name="supplier_phone" placeholder="Phone" value="<?php echo $asset['supplier_phone'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Contact:</label>
                        <div class="col-sm-5 col-md-3">
                            <input type="text" class="form-control" id="supplier_contact_name" name="supplier_contact_name" placeholder="Contact" value="<?php echo $asset['supplier_contact_name'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Email:</label>
                        <div class="col-sm-8 col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="supplier_email" name="supplier_email" placeholder="Email" value="<?php echo $asset['supplier_email'];?>" />
                                <div class="input-group-addon">
                                    <a href="mailto:<?php echo $asset['supplier_email'];?>" target="_top"><span class="glyphicon glyphicon-envelope"></span></a>
                                </div>
                            </div>	
                            
                            <span class="help-block with-errors"></span>
                        </div>
                       
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Website:</label>
                        <div class="col-sm-8 col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="supplier_website" name="supplier_website" placeholder="http://demo.com" value="<?php echo $asset['supplier_website'];?>" />
                                <div class="input-group-addon">
                                    <a target="_blank" href="<?php echo $asset['supplier_website'];?>"><span class="glyphicon glyphicon-link"></span></a>
                                </div>
                            </div>
                            
                            <span class="help-block with-errors"></span>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Preferred Contractor:</label>
                        <div class="col-sm-8 col-md-6">
                            <input type="text" class="form-control" id="preferred_contractor" name="preferred_contractor" placeholder="Preferred Contractor" value="<?php echo $asset['preferred_contractor'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Preferred Contractor Phone:</label>
                        <div class="col-sm-5 col-md-3">
                            <input type="text" class="form-control" id="preferred_contractor_phone" name="preferred_contractor_phone" placeholder="Preferred Contractor Phone" value="<?php echo $asset['preferred_contractor_phone'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Preferred Contractor Email:</label>
                        <div class="col-sm-8 col-md-6">
                            <input type="text" class="form-control" id="preferred_contractor_email" name="preferred_contractor_email" placeholder="Preferred Contractor Email" value="<?php echo $asset['preferred_contractor_email'];?>" />
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                  
                    
                </div>
                 <div class="box-footer">
                     <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">&nbsp;</label>
                        <div class="col-sm-8 col-md-6">
                           <button type="submit" class="btn btn-primary" id="supplier_submit" name="supplier_submit">Save Supplier</button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="asset_form_post" id="asset_form_post" value="2" />	
                    <input type="hidden" name="assetid" id="assetid" value="<?php echo $asset['assetid'];?>" />	
                </div>
            </div> 
        </form>
    </div>
</div>
