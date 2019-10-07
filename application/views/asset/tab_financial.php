<div class="row">
  <div class="col-md-12">
        <form name="tab_financial_form" id="tab_financial_form" class="form-horizontal" action=""  role="form" method="post">
            <div class="box">
                <div class="box-header  with-border">
                  <h3 class="box-title text-blue">Financial</h3>
                </div>
                <div class="box-body">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Purchase Price ($):</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control allownumericwithoutdecimal" id="purchase_price" name="purchase_price" placeholder="Purchase Price" value="<?php echo $asset['purchase_price'];?>" />
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Purchase Date:</label>
                            <div class="col-sm-4"> 
                                <input type="text" class="form-control" id="purchase_date" name="purchase_date" placeholder="Date" value="<?php echo format_date($asset['purchase_date'], RAPTOR_DISPLAY_DATEFORMAT);?>" readonly="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Life Expectancy (Y):</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control allownumericwithoutdecimal" id="life_expectancy" name="life_expectancy" placeholder="Life Expectancy" value="<?php echo $asset['life_expectancy'];?>" />
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Current Age(Y):</label>
                            <div class="col-sm-4"> 
                                <input type="text" class="form-control" id="current_age" name="current_age" placeholder="Current Age(Y)" value="<?php  if(isset($asset['current_age'])){ echo $asset['current_age'];}?>" readonly="" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Annual Depreciation (%):</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control allownumericwithoutdecimal" id="annual_depreciation_rate" name="annual_depreciation_rate" placeholder="Annual Depreciation" value="<?php echo $asset['annual_depreciation_rate'];?>" />
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Depreciation Method:</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" id="depreciation_method_id" name="depreciation_method_id" data-placeholder="Depreciation Method"   >
                                        <option value="">Select</option>
                                        <?php
                                    foreach($depreciation_methods as $value):
                                        $selected = '';
                                        if($value['id'] == $asset['depreciation_method_id']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['id'].'" '.$selected.'   >'.$value['name'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                               <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Current Value ($):</label>
                            <div class="col-sm-4 col-xs-9">
                                <input type="text" class="form-control allownumericwithoutdecimal" id="current_value" name="current_value" placeholder="Current Value" value="<?php echo $asset['current_value'];?>" readonly="" />
                                    
                              <span class="help-block with-errors"></span>
                            </div>
                            <div class="col-sm-2  col-xs-3">
                                 <button type="button" class="btn btn-default" title = "Calculate" onclick="calculateCValue()" >Calculate</button>                 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Salvage Value ($):</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control allownumericwithoutdecimal" id="salvage_value" name="salvage_value" placeholder="Salvage Value" value="<?php echo $asset['salvage_value'];?>" />
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Replacement Value ($):</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control allownumericwithoutdecimal" id="replacement_value" name="replacement_value" placeholder="Replacement Value" value="<?php echo $asset['replacement_value'];?>" />
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-5 control-label">Service Cost to Date ($):</label>
                            <div class="col-sm-4">
                              <input type="text" class="form-control allownumericwithoutdecimal" id="service_cost_to_date" name="service_cost_to_date" placeholder="Service Cost to Date ($)" value="<?php echo $asset['service_cost_to_date'];?>" />
                              <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Cost Centre:</label>
                            <div class="col-sm-7">
                                <select class="form-control select2" id="costcentre" name="costcentre" data-placeholder="Cost Centre" >
                                       <option value="">Select</option>
                                        <?php
                                    foreach($costcentres as $value):
                                        $selected = '';
                                        if($value['costcentre'] == $asset['costcentre']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['costcentre'].'" '.$selected.' data-description="'.$value['description'].'"  >'.$value['costcentre'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                               <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Asset Account:</label>
                            <div class="col-sm-7">
                                <select class="form-control select2" id="assetaccount" name="assetaccount" data-placeholder="Asset Account"   >
                                        <option value="">Select</option>
                                        <?php
                                    foreach($assetaccounts as $value):
                                        $selected = '';
                                        if($value['accountcode'] == $asset['assetaccount']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['accountcode'].'" '.$selected.'   >'.$value['name'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                               <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Expense Account:</label>
                            <div class="col-sm-7">
                                <select class="form-control select2" id="expenseaccount" name="expenseaccount" data-placeholder="Expense Account"  >
                                        <option value="">Select</option>
                                        <?php
                                    foreach($expenseaccounts as $value):
                                        $selected = '';
                                        if($value['accountcode'] == $asset['expenseaccount']):
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
                <div class="box-footer">
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">&nbsp;</label>
                        <div class="col-sm-6 col-sm-3">
                           <button type="submit" class="btn btn-primary" id="condition_submit" name="condition_submit">Save Condition</button>
                        </div>
                    </div>
                    <input type="hidden" name="asset_form_post" id="asset_form_post" value="financial" />
                    <input type="hidden" name="assetid" id="assetid" value="<?php echo $asset['assetid'];?>" />
                </div>
            </div>
        </form>
    </div>
</div>
