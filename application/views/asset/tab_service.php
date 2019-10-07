<div class="row">
    <div class="col-md-12"> 
        <form name="tab_service_form" id="tab_service_form" class="form-horizontal" role="form" action=""  method="post">
            <div class="box">
                <div class="box-header  with-border">
                    <h3 class="box-title text-blue">Service</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">&nbsp;</label>
                        <div class="col-sm-8 col-md-8">
                            <div class="checkbox">
                                <label><input type="checkbox" id="haslogbook" name="haslogbook" value="1" <?php if($asset['haslogbook']==1){ echo 'checked'; }?> />Has Logbook</label>
                            </div>
                          
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Logbook Location:</label>
                        <div class="col-sm-6 col-md-3">
                          <input type="text" class="form-control" id="logbooklocation" name="logbooklocation" value="<?php echo $asset['logbooklocation'];?>" placeholder="Logbook Location" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Criticality:</label>
                        <div class="col-sm-6 col-md-3">
                          <input type="text" class="form-control" id="asset_criticality" name="asset_criticality" value="<?php echo $asset['asset_criticality'];?>" placeholder="Criticality" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">&nbsp;</label>
                        <div class="col-sm-8 col-md-8">
                            <div class="checkbox">
                                <label><input type="checkbox" id="isreportrequired" name="isreportrequired" value="1" <?php if($asset['isreportrequired']==1){ echo 'checked'; }?> />Report required</label>
                            </div>
 
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Annual Visits:</label>
                        <div class="col-sm-4 col-md-2">
                          <input type="text" class="form-control allownumericwithoutdecimal" id="annual_visits" name="annual_visits" value="<?php echo $asset['annual_visits'];?>" placeholder="Visits" />
                          <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">Visit Frequency:</label>
                        <div class="col-sm-6 col-md-3">
                          <select class="form-control selectpicker" id="visit_frequency" name="visit_frequency">
                            <?php
                            foreach($frequency as $value):
                                $selected = '';
                                if($value['id'] == $asset['visit_frequency']):
                                                    $selected = 'selected="selected"';
                                            endif;
                                    echo '<option value="'.$value['id'].'" '.$selected.'>'.$value['description'].'</option>';
                            endforeach;
                            ?>
                          </select>
                          <span class="help-block with-errors"></span>
                        </div>
                    </div> 
                </div>
                <div class="box-footer">
                    <div class="form-group">
                        <label for="input" class="col-sm-4 col-md-2 control-label">&nbsp;</label>
                        <div class="col-sm-8 col-md-8">
                           <button type="submit" class="btn btn-primary" id="history_submit" name="history_submit">Save History</button>
                        </div>
                    </div>
                    <input type="hidden" name="asset_form_post" id="asset_form_post" value="5" />
                    <input type="hidden" name="assetid" id="assetid" value="<?php echo $asset['assetid'];?>" />
                </div>
            </div>
        </form>
    </div>
</div>
