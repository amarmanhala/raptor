<div class= "row">
    <div class= "col-md-12">
        <form name = "supplierdetailform" id = "supplierdetailform" class= "form-horizontal" method = "post">
 
            <div class= "box">
                <div class= "box-header"></div>
                <div class= "box-body">
                    <div class= "form-group <?php if (form_error('companyname')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Business Name: </label>
                        <div class= "col-sm-8">
                            <input type = "text" class= "form-control" id = "companyname" name = "companyname" placeholder= "Company Name" value = "<?php echo set_value('companyname');?>" required = "required" />
                         <?php echo form_error('companyname', '<span class= "help-block with-errors" for= "companyname" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group">
                        <label for= "input" class= "col-sm-2 control-label">Business Structure: </label>
                        <div class= "col-sm-2 <?php if (form_error('structure')) echo ' has-error';?>">
                            <select id = "structure" name = "structure" class= "form-control" required = "required" >
                                <option value = ''>-Select-</option>
                                <option value = "Company" <?php if (strtolower(set_value('structure')) == 'company') echo "selected";?>>Company</option>
                                <option value = "Sole Trader" <?php if (strtolower(set_value('structure')) == 'sole trader') echo "selected";?>>Sole Trader</option>
                                <option value = "Partnership" <?php if (strtolower(set_value('structure')) == 'partnership') echo "selected";?>>Partnership</option>
                            </select>
                            <?php echo form_error('structure', '<span class= "help-block with-errors" for= "structure" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <label for= "input" class= "col-sm-1 control-label">ABN: </label>
                        <div class= "col-sm-2 <?php if (form_error('abn')) echo ' has-error';?>">
                            <input type = "text" class= "form-control" id = "abn" name = "abn" value = "<?php echo set_value('abn');?>" data-inputmask= '"mask": "99 999 999 999"' data-mask />
                            <?php echo form_error('abn', '<span class= "help-block with-errors" for= "abn" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <div class="col-sm-3 checkbox">
                            <label class="">
                                <input type="checkbox"  id="isgstregistered" name="isgstregistered" value="1" <?php if(set_value('isgstregistered') == "1") echo "checked";?> />GST Registered</label>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('typeid')) echo ' has-error';?>">
                        <label for= "typeid" class= "col-sm-2 control-label">Type: </label>
                        <div class= "col-sm-10">
                            <?php 
                             $code = '';
                            foreach ($suppliertypes as $key => $value) { ?>
                                <label class="radio-inline"><input type="radio" value="<?php echo $value['id']; ?>" data-code="<?php echo $value['code']; ?>" name="typeid" <?php if(set_value('typeid') == $value['id']){  echo "checked"; $code = $value['code'];  }?> ><?php echo $value['name'];?></label>
                            <?php } ?>
                            <?php echo form_error('typeid', '<span class= "help-block with-errors" for= "typeid" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group primarytradediv <?php if (form_error('tradeid')) echo ' has-error';?>" style="<?php if($code != 'T'){echo 'display:none';} ?>">
                        <label for= "input" class= "col-sm-2 control-label">Primary Trade: </label>
                        <div class= "col-sm-5">
                            <select name = "tradeid" id = "tradeid" class= "form-control">
                                <option value = ''>-Select-</option>
                                <?php foreach ($se_trades as $key => $value) { ?>
                                      <option value = "<?php echo $value['id'];?>" <?php if (set_value('tradeid') == $value['id']) echo "selected";?>><?php echo $value['se_trade_name'];?></option> 
                                <?php } ?>
                            </select>
                            <input type="hidden" name="primarytrade" id="primarytrade" value="<?php echo set_value('primarytrade'); ?>"/>
                          <?php echo form_error('tradeid', '<span class= "help-block with-errors" for= "tradeid" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class= "box">
                <div class= "box-header">
                <label>Business Address</label>
                </div>
                <div class= "box-body">
                    <div class= "form-group <?php if (form_error('shipping1')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Address </label>
                        <div class= "col-sm-8">
                            <input type = "text" class= "form-control" id = "shipping1" name = "shipping1" placeholder= "Address1" value = "<?php echo set_value('shipping1');?>" required = "required" />
                            <?php echo form_error('shipping1', '<span class= "help-block with-errors" for= "shipping1" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('shipping2')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">&nbsp;</label>
                        <div class= "col-sm-8">
                            <input type = "text" class= "form-control" id = "shipping2" name = "shipping2" placeholder= "Address2" value = "<?php echo set_value('shipping2');?>" />
                            <?php echo form_error('shipping2', '<span class= "help-block with-errors" for= "shipping2" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group ">
                        <label for= "input" class= "col-sm-2 control-label">City:</label>
                        <div class= "col-sm-2 <?php if (form_error('shipsuburb')) echo ' has-error';?>">
                            <input type = "text" class= "form-control suburbtypeahead" data-suburb= "shipsuburb1"   data-state = "shipstate" data-postcode = "shippostcode" id = "shipsuburb" name = "shipsuburb" placeholder= "search.."  value = "<?php echo set_value('shipsuburb');?>" required = "required" />
                            <input type = "hidden" class= "updatesuburb" data-suburb= "shipsuburb"   id = "shipsuburb1" name = "shipsuburb1"   value = "<?php echo set_value('shipsuburb1');?>" />
                            <?php echo form_error('shipsuburb', '<span class= "help-block with-errors" for= "shipsuburb" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <label for= "input" class= "col-sm-1 control-label">State: </label>
                        <div class= "col-sm-2 <?php if (form_error('shipstate')) echo ' has-error';?>">
                            <select name = "shipstate" id = "shipstate" class= "form-control" required = "required" readonly="readonly"  >
                                <option value = ''>-Select-</option>
                                <?php foreach ($states as $key => $value) { ?>
                                        <option value = "<?php echo $value['abbreviation'];?>" <?php if (set_value('shipstate') == $value['abbreviation']) echo "selected";?>><?php echo $value['abbreviation'];?></option> 
                                <?php } ?>
                            </select>
                            <?php echo form_error('shipstate', '<span class= "help-block with-errors" for= "shipstate" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <label for= "input" class= "col-sm-1 control-label" style="padding-right: 0px" >Post Code</label>
                        <div class= "col-sm-2 <?php if (form_error('shippostcode')) echo ' has-error';?>">
                            <input type = "text" class= "form-control postcodetypeahead" id = "shippostcode" name = "shippostcode" placeholder= "search.." readonly="readonly"   value = "<?php echo set_value('shippostcode');?>" required = "required" />
                            <?php echo form_error('shippostcode', '<span class= "help-block with-errors" for= "shippostcode" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                </div>
            </div>	
            <div class= "box">
                <div class= "box-header">
                <label>Mailing Address</label>
                <div class="pull-right">
                    <button type="button" class="btn btn-sm btn-info" id="btncopyaddress" >Copy Business Address</button>
                </div>
                </div>
                <div class= "box-body">
                    <div class= "form-group <?php if (form_error('mail1')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Address </label>
                        <div class= "col-sm-8">
                            <input type = "text" class= "form-control" id = "mail1" name = "mail1" placeholder= "Address1" value = "<?php echo set_value('mail1');?>" required = "required" />
                            <?php echo form_error('mail1', '<span class= "help-block with-errors" for= "mail1" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('mail2')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">&nbsp;</label>
                        <div class= "col-sm-8">
                            <input type = "text" class= "form-control" id = "mail2" name = "mail2" placeholder= "Address2" value = "<?php echo set_value('mail2');?>" />
                            <?php echo form_error('mail2', '<span class= "help-block with-errors" for= "mail2" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group ">
                        <label for= "input" class= "col-sm-2 control-label">City:</label>
                        <div class= "col-sm-2 <?php if (form_error('mailsuburb')) echo ' has-error';?>">
                            <input type = "text" class= "form-control suburbtypeahead" data-suburb= "mailsuburb1"  data-state = "state" data-postcode = "postcode" id = "mailsuburb" name = "mailsuburb" placeholder= "search.."  value = "<?php echo set_value('mailsuburb');?>" required = "required" />
                            <input type = "hidden" class= "updatesuburb" data-suburb= "mailsuburb"   id = "mailsuburb1" name = "mailsuburb1"   value = "<?php echo set_value('mailsuburb1');?>" />
                            <?php echo form_error('mailsuburb', '<span class= "help-block with-errors" for= "mailsuburb" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <label for= "input" class= "col-sm-1 control-label">State: </label>
                        <div class= "col-sm-2 <?php if (form_error('state')) echo ' has-error';?>">
                            <select name = "state" id = "state" class= "form-control" required = "required" readonly="readonly"  >
                                <option value = ''>-Select-</option>
                             <?php foreach ($states as $key => $value) { ?>
                                    <option value = "<?php echo $value['abbreviation'];?>" <?php if (set_value('state') == $value['abbreviation']) echo "selected";?>><?php echo $value['abbreviation'];?></option> 
                            <?php } ?>
                            </select>
                            <?php echo form_error('state', '<span class= "help-block with-errors" for= "state" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <label for= "input" class= "col-sm-1 control-label"  style="padding-right: 0px" >Post Code</label>
                        <div class= "col-sm-2 <?php if (form_error('postcode')) echo ' has-error';?>">
                            <input type = "text" class= "form-control postcodetypeahead" id = "postcode" name = "postcode" placeholder= "search.." readonly="readonly"    value = "<?php echo set_value('postcode');?>" required = "required" />
                            <?php echo form_error('postcode', '<span class= "help-block with-errors" for= "postcode" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('country')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Country Code: </label>
                        <div class= "col-sm-4">
                           <input type = "text" class= "form-control" id = "country" name = "country" placeholder= "Country" value = "<?php   echo set_value('country', 'AUS');?>" />
                          <?php echo form_error('country', '<span class= "help-block with-errors" for= "country" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
		</div>
            </div>
            <div class= "box">
                <div class= "box-header"></div>
                <div class= "box-body">
                    <div class= "form-group">
                        <label for= "input" class= "col-sm-2 control-label">Phone: </label>
                        <div class= "col-sm-3 <?php if (form_error('phone')) echo ' has-error';?>">
                            <div class= "input-group">
                                <input type = "text" class= "form-control" id = "phone" name = "phone" placeholder="xx xxxx xxxx" value = "<?php echo set_value('phone');?>" pattern= "[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask= '"mask": "99 9999 9999"' data-mask />
                                <div class= "input-group-addon">
                                    <i class= "fa fa-phone"></i>
                                </div>
                            </div>
                            <?php echo form_error('phone', '<span class= "help-block with-errors" for= "phone" generated = "TRUE">', '</span>'); ?>
                        </div>
                        <label for= "input" class= "col-sm-1 control-label">Fax: </label>
                        <div class= "col-sm-3 <?php if (form_error('fax')) echo ' has-error';?>">
                            <div class= "input-group">
                                <input type = "text" class= "form-control" id = "fax" name = "fax" placeholder="xx xxxx xxxx" value = "<?php echo set_value('fax');?>" pattern= "[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask= '"mask": "99 9999 9999"' data-mask />
                                <div class= "input-group-addon">
                                    <i class= "fa fa-fax"></i>
                                </div>
                            </div>
                            <?php echo form_error('fax', '<span class= "help-block with-errors" for= "fax" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="form-group <?php if (form_error('email')) echo ' has-error';?>">
                        <label for="input" class="col-sm-2 control-label">Mobile: </label>
                        <div class="col-sm-3">
                            <div class= "input-group">
                                <input type = "text" class= "form-control" id = "mobile" placeholder="xxxx xxx xxx" name = "mobile" value = "<?php echo set_value('mobile');?>" pattern= "[0-9]{4} [0-9]{3} [0-9]{3}" data-inputmask= '"mask": "9999 999 999"' data-mask />
                                <div class= "input-group-addon">
                                    <i class= "fa fa-mobile"></i>
                                </div>
                            </div>
                            <?php echo form_error('mobile', '<span class= "help-block with-errors" for= "mobile" generated = "TRUE">', '</span>'); ?>
                          
                        </div>
                    </div>
                    
                    <div class= "form-group <?php if (form_error('email')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Email: </label>
                        <div class= "col-sm-7">
                            <div class= "input-group">
                                <input type = "email" class= "form-control" id = "email" name = "email" placeholder= "Email"    value = "<?php echo set_value('email');?>" required = "required" />
                                <div class= "input-group-addon">
                                    <i class= "fa fa-envelope"></i>
                                </div>
                            </div>
                            <?php echo form_error('email', '<span class= "help-block with-errors" for= "email" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('url')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Website: </label>
                        <div class= "col-sm-7">
                            <div class= "input-group">
                                <input type = "url" class= "form-control" id = "url" name = "url" placeholder= "http://www.yourwebsite.com"    value = "<?php echo set_value('url');?>" />
                                <div class= "input-group-addon">
                                    <i class= "fa fa-link"></i>
                                </div>
                            </div>
                            <?php echo form_error('url', '<span class= "help-block with-errors" for= "url" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group">
                        <label for= "input" class= "col-sm-2 control-label">&nbsp;</label>
                        <div class= "col-sm-10">
                        <input  type = "submit" class= "btn btn-primary" name = "companysave" value = "Save" />
                       <?php if($this->input->get('from')){ ?>
                            <a href= "<?php echo site_url($this->input->get('from'));?>" class= "btn btn-default">Cancel</a>
                       <?php }
                        else{ ?>
                            <a href= "<?php echo site_url("suppliers");?>" class= "btn btn-default">Cancel</a>
                       <?php } ?>
                        
                        
                        </div>
                    </div>
                </div>
            </div>
       	</form>	
    </div>
</div>
