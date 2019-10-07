<div class="row">
    <div class="col-md-12">
        <form name="tab_informations_form" id="tab_informations_form" class="form-horizontal" action=""  role="form" method="post">
            <div class="box">
                <div class="box-header  with-border">
                  <h3 class="box-title text-blue">Information</h3>
                </div>
                <div class="box-body">
	
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">Description:</label>
                        <div class="col-sm-8 col-md-6">
                          <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $asset['description'];?>" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">OHS Risk:</label>
                        <div class="col-sm-6 col-md-3 ">
                          <select class="form-control selectpicker" id="ohs_risk" name="ohs_risk">
                            <?php
                            foreach($asset_ohsrisk as $value):
                                $selected = '';
                                if($value['id'] == $asset['ohs_risk']):
                                                    $selected = 'selected="selected"';
                                            endif;
                                    echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['risklevel'].'</option>';
                            endforeach;
                            ?>
                          </select>
                          <span class="help-block with-errors"></span>
                          </div>
                    </div> 
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">Quantity:</label>
                        <div class="col-sm-4 col-md-2 ">
                          <input type="text" class="form-control allownumericwithoutdecimal" id="quantity" name="quantity" placeholder="Quantity" value="<?php echo $asset['quantity'];?>" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">Length (mm):</label>
                        <div class="col-sm-4 col-md-2 ">
                          <input type="text" class="form-control allownumericwithoutdecimal" id="length" name="length" placeholder="Length" value="<?php echo $asset['length'];?>" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">Width (mm):</label>
                        <div class="col-sm-4 col-md-2 ">
                          <input type="text" class="form-control allownumericwithoutdecimal" id="width" name="width" placeholder="Width" value="<?php echo $asset['width'];?>" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">Height(mm):</label>
                        <div class="col-sm-4 col-md-2 ">
                          <input type="text" class="form-control allownumericwithoutdecimal" id="height" name="height" placeholder="Height" value="<?php echo $asset['height'];?>" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                     
                </div>
                <div class="box-footer">
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2  control-label">&nbsp;</label>
                        <div class="col-sm-6 col-md-3">
                           <button type="submit" class="btn btn-primary" id="information_submit" name="information_submit">Save Information</button>
                        </div>
                    </div>
                    <input type="hidden" name="asset_form_post" id="asset_form_post" value="4" />
                    <input type="hidden" name="assetid" id="assetid" value="<?php echo $asset['assetid'];?>" />
                </div>
            </div>
        </form>
    </div>
</div>
