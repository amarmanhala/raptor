<div class= "row">
    <div class= "col-md-12">
        <form name = "jobclosureform" id = "jobclosureform" action ="" class= "form-horizontal" method = "post">
            <div class= "box">
                <div class="box-header  with-border">
                    <h3 class="box-title text-blue">Job Closure</h3>
                </div>
                <div class= "box-body">
                    <div class= "form-group <?php if (form_error('finishdate')) echo ' has-error';?>">
                        <label for= "finishdate" class= "col-sm-2 control-label">Job Completed: </label>
                        <div class= "col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="finishdate" name="finishdate" placeholder="Date" value="<?php echo set_value('finishdate');?>" readonly="" />
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>	
                           <?php echo form_error('finishdate', '<span class= "help-block with-errors" for= "finishdate" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('invoicenumber')) echo ' has-error';?>">
                        <label for= "input" class= "col-sm-2 control-label">Supplier Invoice No.: </label>
                        <div class= "col-sm-2 ">
                             <input type="text" class="form-control" id="invoicenumber" name="invoicenumber" placeholder="Invoice No" value="<?php echo set_value('invoicenumber');?>" readonly="" />
                            <?php echo form_error('invoicenumber', '<span class= "help-block with-errors" for= "invoicenumber" generated = "TRUE">', '</span>'); ?>
                        </div>
                         
                    </div>
                    <div class= "form-group <?php if (form_error('labour')) echo ' has-error';?>">
                        <label for= "labour" class= "col-sm-2 control-label">Labour: </label>
                        <div class= "col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo RAPTOR_CURRENCY_SYMBOL;?></span>
                                <input type ="text" value ="<?php echo set_value('labour');?>" name ="labour" id ="labour" class="form-control allownumericwithdecimal">
                            </div>
                            <?php echo form_error('labour', '<span class= "help-block with-errors" for= "labour" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('materials')) echo ' has-error';?>">
                        <label for= "materials" class= "col-sm-2 control-label">Materials: </label>
                        <div class= "col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo RAPTOR_CURRENCY_SYMBOL;?></span>
                                <input type ="text" value ="<?php echo set_value('materials');?>" name ="materials" id ="materials" class="form-control allownumericwithdecimal">
                            </div>
                            <?php echo form_error('materials', '<span class= "help-block with-errors" for= "materials" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('other')) echo ' has-error';?>">
                        <label for= "other" class= "col-sm-2 control-label">Other: </label>
                        <div class= "col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo RAPTOR_CURRENCY_SYMBOL;?></span>
                                <input type ="text" value ="<?php echo set_value('other');?>" name ="other" id ="other" class="form-control allownumericwithdecimal">
                            </div>
                            <?php echo form_error('other', '<span class= "help-block with-errors" for= "other" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('total')) echo ' has-error';?>">
                        <label for= "total" class= "col-sm-2 control-label">Total: </label>
                        <div class= "col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo RAPTOR_CURRENCY_SYMBOL;?></span>
                                <input type ="text" value ="<?php echo set_value('total');?>" name ="total" id ="total" class="form-control allownumericwithdecimal">
                            </div>
                            <?php echo form_error('total', '<span class= "help-block with-errors" for= "total" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('gst')) echo ' has-error';?>">
                        <label for= "gst" class= "col-sm-2 control-label">GST: </label>
                        <div class= "col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo RAPTOR_CURRENCY_SYMBOL;?></span>
                                <input type ="text" value ="<?php echo set_value('gst');?>" name ="gst" id ="gst" class="form-control allownumericwithdecimal">
                            </div>
                            <?php echo form_error('gst', '<span class= "help-block with-errors" for= "gst" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('invtotal')) echo ' has-error';?>">
                        <label for= "invtotal" class= "col-sm-2 control-label">Invoice Total (inc GST): </label>
                        <div class= "col-sm-2">
                            <div class="input-group">
                                <span class="input-group-addon"><?php echo RAPTOR_CURRENCY_SYMBOL;?></span>
                                <input type ="text" value ="<?php echo set_value('invtotal');?>" name ="invtotal" id ="invtotal" class="form-control allownumericwithdecimal">
                            </div>
                            <?php echo form_error('invtotal', '<span class= "help-block with-errors" for= "invtotal" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('invoicedate')) echo ' has-error';?>">
                        <label for= "invoicedate" class= "col-sm-2 control-label">Supplier Invoice Date: </label>
                        <div class= "col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="invoicedate" name="invoicedate" placeholder="Date" value="<?php echo set_value('invoicedate');?>" readonly="" />
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>	
                           <?php echo form_error('invoicedate', '<span class= "help-block with-errors" for= "invoicedate" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class= "form-group <?php if (form_error('finishdate')) echo ' has-error';?>">
                        <label for= "finishdate" class= "col-sm-2 control-label">Supplier Due Date: </label>
                        <div class= "col-sm-8">
                            <div class="input-group">
                                <input type="text" class="form-control datepicker" id="duedate" name="duedate" placeholder="Date" value="<?php echo set_value('duedate');?>" readonly="" />
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>	
                           <?php echo form_error('duedate', '<span class= "help-block with-errors" for= "duedate" generated = "TRUE">', '</span>'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">GL Code:</label>
                        <div class="col-sm-7">
                            <select class="form-control select2" id="glcode" name="glcode" data-placeholder="Asset Account"   >
                                    <option value="">Select</option>
                                    <?php
                                foreach($glcodes as $value):
                                    $selected = '';
                                    if($value['accountcode'] == set_value('glcode')):
                                                        $selected = 'selected="selected"';
                                                endif;
                                        echo '<option value="'.$value['accountcode'].'" '.$selected.'   >'.$value['name'].'</option>';
                                endforeach;
                                ?>
                            </select>
                           <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
            </div>
            
       	</form>	
    </div>
</div>
 