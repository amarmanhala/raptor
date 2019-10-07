<div class="row">
  <div class="col-md-12">
        <form name="tab_condition_form" id="tab_condition_form" class="form-horizontal" action=""  role="form" method="post">
            <div class="box">
                <div class="box-header  with-border">
                  <h3 class="box-title text-blue">Condition</h3>
                </div>
                <div class="box-body">
                    
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Condition:</label>
                        <div class="col-sm-6 col-md-4">
                            <select class="form-control select2" id="condition" name="condition" data-placeholder="Select Condition" data-allow-clear="true" >
                                        <option value=""></option>
                                        <?php
                                    foreach($asset_conditions as $value):
                                        $selected = '';
                                        if($value['condition'] == $asset['condition']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['condition'].'" '.$selected.'   >'.$value['condition'].'</option>';
                                    endforeach;
                                    ?>
                            </select>
                           
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Desired Condition:</label>
                        <div class="col-sm-6 col-md-4">
                            <select class="form-control select2" id="desired_condition" name="desired_condition" data-placeholder="Select Desired Condition" data-allow-clear="true" >
                                        <option value=""></option>
                                        <?php
                                    foreach($asset_conditions as $value):
                                        $selected = '';
                                        if($value['condition'] == $asset['desired_condition']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['condition'].'" '.$selected.'   >'.$value['condition'].'</option>';
                                    endforeach;
                                    ?>
                            </select>
                          
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Working:</label>
                        <div class="col-sm-4 col-md-2">
                          <select class="form-control select2" id="isworking" name="isworking">
                                  <option value="-1" <?php if($asset['isworking'] == -1){ echo 'selected';}?>>N/A</option>
                                  <option value="0" <?php if($asset['isworking'] == 0){ echo 'selected';}?>>No</option>	
                                  <option value="1" <?php if($asset['isworking'] == 1){ echo 'selected';}?>>Yes</option>
                          </select>
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Damaged:</label>
                        <div class="col-sm-6 col-md-4">
                          <select class="form-control select2" id="isdamaged" name="isdamaged">
                                  <option value="0" <?php if($asset['isdamaged'] == 0){ echo 'selected';}?>>Undamaged</option>
                                  <option value="1" <?php if($asset['isdamaged'] == 1){ echo 'selected';}?>>Damaged</option>	
                          </select>
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Notes:</label>
                        <div class="col-sm-8 col-sm-8">
                          <textarea class="form-control" id="notes" name="notes" placeholder="Notes" rows="7"><?php echo $asset['notes'];?></textarea>
                          <span class="help-block with-errors"></span>
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
                    <input type="hidden" name="asset_form_post" id="asset_form_post" value="3" />
                    <input type="hidden" name="assetid" id="assetid" value="<?php echo $asset['assetid'];?>" />
                </div>
            </div>
        </form>
    </div>
</div>
