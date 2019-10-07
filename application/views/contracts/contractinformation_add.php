<div class= "box">
    <form name = "contractdetailform" id = "contractdetailform" class= "form-horizontal" method = "post">
        <div class= "box-header with-border">
            <h2 class="box-title  text-blue">Information</h2>
        </div>
        <div class= "box-body">
            <div class= "form-group <?php if (form_error('name')) echo ' has-error';?>">
                <label for= "name" class= "col-sm-2 control-label">Contract Name</label>
                <div class= "col-sm-8">
                    <input type = "text" class= "form-control" id = "name" name = "name" placeholder= "Contract Name" value = "<?php echo set_value('name');?>" required = "required" />
                 <?php echo form_error('name', '<span class= "help-block with-errors" for= "name" generated = "TRUE">', '</span>'); ?>
                </div>
            </div>
            <div class= "form-group <?php if (form_error('contractref')) echo ' has-error';?>">
                <label for= "input" class= "col-sm-2 control-label">Contract Ref</label>
                <div class= "col-sm-4">
                    <input type = "text" class= "form-control" id = "contractref" name = "contractref" placeholder= "Contract Ref" value = "<?php echo set_value('contractref');?>"  required="required"/>
                    <?php echo form_error('contractref', '<span class= "help-block with-errors" for= "abn" generated = "TRUE">', '</span>'); ?>
                </div>
            </div> 
            <div class= "form-group <?php if (form_error('contracttypeid')) echo ' has-error';?>">
                <label for= "input" class= "col-sm-2 control-label">Type</label>
                <div class= "col-sm-4">
                    <select name = "contracttypeid" id = "contracttypeid" class= "form-control">
                        <option value = ''>-Select-</option>
                        <?php foreach ($contracttypes as $key => $value) { ?>
                              <option value = "<?php echo $value['id'];?>" <?php if (set_value('contracttypeid') == $value['id']) echo "selected";?>><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                    <?php echo form_error('contracttypeid', '<span class= "help-block with-errors" for= "contracttypeid" generated = "TRUE">', '</span>'); ?>
                </div>
            </div>

            <div class= "form-group">
                <label for= "input" class= "col-sm-2 control-label">Start Date</label>
                <div class= "col-sm-3 <?php if (form_error('startdate')) echo ' has-error';?>">
                    <div class= "input-group">
                        <input type = "text" class= "form-control datepicker" id = "startdate" name = "startdate" placeholder="Start Date" value = "<?php echo set_value('startdate');?>" readonly=""/>
                        <div class= "input-group-addon">
                            <i class= "fa fa-calendar"></i>
                        </div>
                    </div>
                    <?php echo form_error('startdate', '<span class= "help-block with-errors" for= "startdate" generated = "TRUE">', '</span>'); ?>
                </div>
                <label for= "input" class= "col-sm-2 control-label">End Date</label>
                <div class= "col-sm-3 <?php if (form_error('enddate')) echo ' has-error';?>">
                    <div class= "input-group">
                        <input type = "text" class= "form-control datepicker" id = "enddate" name = "enddate" placeholder="End Date" value = "<?php echo set_value('enddate');?>"  readonly="" />
                        <div class= "input-group-addon">
                            <i class= "fa fa-calendar"></i>
                        </div>
                    </div>
                    <?php echo form_error('enddate', '<span class= "help-block with-errors" for= "enddate" generated = "TRUE">', '</span>'); ?>
                </div>
<!--                <label for= "input" class= "col-sm-1 control-label">Month</label>
                <div class= "col-sm-1">
                    <input type = "text" class= "form-control" id = "months" name = "months" placeholder="Months" readonly="" value = "<?php //echo set_value('months');?>"   />
                </div>-->
            </div>
            <div class= "form-group">
                <label for= "input" class= "col-sm-2 control-label">Manager</label>
                <div class= "col-sm-4 <?php if (form_error('managerid')) echo ' has-error';?>">
                    <select name = "managerid" id = "managerid" class= "form-control">
                        <option value = ''>-Select-</option>
                        <?php foreach ($managers as $key => $value) { ?>
                              <option value = "<?php echo $value['contactid'];?>" data-phone = "<?php echo $value['phone'];?>" <?php if (set_value('managerid') == $value['contactid']) echo "selected";?>><?php echo $value['firstname'];?></option> 
                        <?php } ?>
                    </select>
                    <?php echo form_error('managerid', '<span class= "help-block with-errors" for= "managerid" generated = "TRUE">', '</span>'); ?>
                </div>
                <label for= "input" class= "col-sm-1 control-label">Phone</label>
                <div class= "col-sm-3 <?php if (form_error('phone')) echo ' has-error';?>">
                    <div class= "input-group">
                        <input type = "text" class= "form-control" id = "phone" name = "phone" placeholder="Phone" value = "<?php echo set_value('phone');?>"  readonly="" />
                        <div class= "input-group-addon">
                            <i class= "fa fa-phone"></i>
                        </div>
                    </div>
                    <?php echo form_error('enddate', '<span class= "help-block with-errors" for= "enddate" generated = "TRUE">', '</span>'); ?>
                </div>

            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Active</label>
                <div class="col-sm-10">
                   <div class="checkbox">
                     <label>
                         <input type="checkbox" name="status" value="1" <?php if(set_value('status') == 1){ echo 'checked'; } ?>>
                     </label>
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


                </div>
            </div>
        </div>
    </form>	
</div>
  