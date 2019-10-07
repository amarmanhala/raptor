<div class="row">
    <div class="col-md-12">
        <div class="box">
            <form name="tab_asset_form" id="tab_asset_form" class="form-horizontal" action="" role="form" method="post">
                <div class="box-header  with-border">
                    <h3 class="box-title text-blue">Asset id : <?php echo $asset['assetid'];?></h3>
                </div>
                <div class="box-body">
                    <div class="col-md-7">
                         
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Address:</label>
                            <div class="col-sm-7">
                                <select class="form-control select2" id="labelid" name="labelid" data-placeholder="Select Address" data-allow-clear="true" >
                                        <option value="">Select Address</option>
                                        <?php
                                    foreach($site_address as $value){
                                        $selected = '';
                                        if($value['labelid'] == $asset['labelid']){
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="'.$value['labelid'].'" '.$selected.' data-latitude="'.$value['latitude_decimal'].'" data-longitude="'.$value['longitude_decimal'].'" >'.$value['address'].'</option>';
                                    }?>
                                </select>
                                <input type="hidden"  id="site_address" name="site_address"  value="<?php echo $asset['site_address'];?>" autocomplete="off" />
                               
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Location:</label>
                            <div class="col-sm-7">
								<div class="input-group input-group">
                                <select class="form-control" id="location_id" name="location_id" data-placeholder="Select Location" data-allow-clear="true" >
                                    <option value="">Select Location</option>
                                    <?php
                                    foreach($location as $value):
                                        $selected = '';
                                        if($value['asset_location_id'] == $asset['location_id']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['asset_location_id'].'" '.$selected.' data-latitude="'.$value['latitude_decimal'].'" data-longitude="'.$value['longitude_decimal'].'" >'.$value['location'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                                <input type="hidden"   id="location_text" name="location_text"   value="<?php echo $asset['location_text'];?>" autocomplete="off" />
                                <div class="input-group-btn">
										<?php  if(isset($ADD_ASSET_LOCATION) && $ADD_ASSET_LOCATION){ ?>
										<button type="button" class="btn btn-info" title = "Add Location" onclick="openLocation('add');"><i class="fa fa-plus" title = "Add Location" ></i></button>                 
										<?php }
										if(isset($EDIT_ASSET_LOCATION) && $EDIT_ASSET_LOCATION){ ?>
										<button type="button" class="btn btn-info" title = "Edit Location" onclick="openLocation('edit');"><i class= "fa fa-edit" title = "Edit Location"></i></button>
										<?php } ?>
 									</div>
								</div>  
                                  <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Sub-Location:</label>
                            <div class="col-sm-7">
								<div class="input-group input-group">
                                <select class="form-control" id="sublocation_id" name="sublocation_id" data-placeholder="Select Sub Location" data-allow-clear="true" >
                                    <option value="">Select Sub Location</option>
                                    <?php
                                    foreach($sublocation as $value):
                                        $selected = '';
                                        if($value['asset_sublocation_id'] == $asset['sublocation_id']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['asset_sublocation_id'].'" '.$selected.'  >'.$value['sublocation'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                                <input type="hidden"  id="sublocation_text" name="sublocation_text"  value="<?php echo $asset['sublocation_text'];?>" autocomplete="off" />
								<div class="input-group-btn">
										<?php  if(isset($ADD_ASSET_SUBLOCATION) && $ADD_ASSET_SUBLOCATION){ ?>
										<button type="button" class="btn btn-info" title = "Add Sub Location" onclick="openSubLocation('add');"><i class="fa fa-plus" title = "Add Sub Location" ></i></button>                 
										<?php }
										if(isset($EDIT_ASSET_SUBLOCATION) && $EDIT_ASSET_SUBLOCATION){ ?>
										<button type="button" class="btn btn-info" title = "Edit Sub Location" onclick="openSubLocation('edit');"><i class= "fa fa-edit" title = "Edit Sub Location"></i></button>
										<?php } ?>
 									</div>
								</div>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                   
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Manufacturer:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Search.." value="<?php echo $asset['manufacturer'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Model:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="model" name="model" placeholder="Model" value="<?php echo $asset['model'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Serial No:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Serial No" value="<?php echo $asset['serial_no'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Service Tag:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="service_tag" name="service_tag" placeholder="Service Tag" value="<?php echo $asset['service_tag'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Acquired:</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="purchase_date" name="purchase_date" placeholder="Date" value="<?php echo format_date($asset['purchase_date'], RAPTOR_DISPLAY_DATEFORMAT);?>" readonly="" />
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div> 	
                                <span class="help-block with-errors"></span>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Disposal:</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="disposal_date" name="disposal_date" placeholder="Date" value="<?php echo format_date($asset['disposal_date'], RAPTOR_DISPLAY_DATEFORMAT);?>" readonly="" />
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>	
                                <span class="help-block with-errors"></span>
                            </div>
			</div> 
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Warranty:</label>
                            <div class="col-sm-7">
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="warranty_expiry_date" name="warranty_expiry_date" placeholder="Date" value="<?php echo format_date($asset['warranty_expiry_date'], RAPTOR_DISPLAY_DATEFORMAT) ;?>" readonly="" />
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>	
                                <span class="help-block with-errors"></span>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="input" class="col-sm-4 control-label">Category:</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="category_id" name="category_id">
                                    <option value="" >Select Category</option>
                                    <?php
                                    foreach($asset_category as $value):
                                        $selected = '';
                                        if($value['asset_category_id'] == $asset['category_id']):
                                                            $selected = 'selected="selected"';
                                                    endif;
                                            echo '<option value="'.$value['asset_category_id'].'" '.$selected.' data-customlabel1="'.$value['customlabel1'].'" data-customlabel2="'.$value['customlabel2'].'" data-customlabel3="'.$value['customlabel3'].'" data-customlabel4="'.$value['customlabel4'].'" data-customlabel5="'.$value['customlabel5'].'">'.$value['category_name'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                            <input type="hidden" name="category_name" id="category_name" value="<?php echo $asset_categoryname;?>" />
                        </div> 
			 
                     
                        <div class="form-group"  id="customfield1" <?php if($asset_custom_labels['custom1'] == '' || $asset_custom_labels['custom1'] == NULL){echo 'style="display:none"';}?> >
                            <label id="customlabel1" for="input" class="col-sm-4 control-label"><?php echo $asset_custom_labels['custom1'];?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="custom1" name="custom1" placeholder="Label" value="<?php echo $asset['custom1'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
                        <div class="form-group"  id="customfield2" <?php if($asset_custom_labels['custom2'] == '' || $asset_custom_labels['custom2'] == NULL){echo 'style="display:none"';}?> >
                            <label id="customlabel2" for="input" class="col-sm-4 control-label"><?php echo $asset_custom_labels['custom2'];?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="custom2" name="custom2" placeholder="Label" value="<?php echo $asset['custom2'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>
			<div class="form-group"  id="customfield3" <?php if($asset_custom_labels['custom3'] == '' || $asset_custom_labels['custom3'] == NULL){echo 'style="display:none"';}?> >
                            <label id="customlabel3" for="input" class="col-sm-4 control-label"><?php echo $asset_custom_labels['custom3'];?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="custom3" name="custom3" placeholder="Label" value="<?php echo $asset['custom3'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                       </div>
                       <div class="form-group"  id="customfield4" <?php if($asset_custom_labels['custom4'] == '' || $asset_custom_labels['custom4'] == NULL){echo 'style="display:none"';}?> >
                            <label id="customlabel4" for="input" class="col-sm-4 control-label"><?php echo $asset_custom_labels['custom4'];?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="custom4" name="custom4" placeholder="Label" value="<?php echo $asset['custom4'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                       </div>
                       <div class="form-group"  id="customfield5" <?php if($asset_custom_labels['custom5'] == '' || $asset_custom_labels['custom5'] == NULL){echo 'style="display:none"';}?> >
                            <label id="customlabel5" for="input" class="col-sm-4 control-label"><?php echo $asset_custom_labels['custom5'];?></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="custom5" name="custom5" placeholder="Label" value="<?php echo $asset['custom5'];?>" />
                                <span class="help-block with-errors"></span>
                            </div>
                       </div>
                         
                        <div class= "form-group">
                                <label for= "input" class= "col-sm-4 control-label">GPS Latitude:</label>
                                <div class= "col-sm-4">
                                    <input type = "text" class= "form-control allownumericwithdecimalnegative" id = "latitude_decimal" name = "latitude_decimal" readonly="readonly" value="<?php echo $asset['latitude_decimal'];?>" />
                                </div>
                                <div class= "col-sm-4">
                                    <button type="button" class="btn btn-default" id="getgps" onclick="getGPS();"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Searching...</span><span style="display:block;">Get GPS</span></button>
                                </div>
                        </div>
                        <div class= "form-group">
                            <label for= "input" class= "col-sm-4 control-label">GPS Longitude:</label>
                            <div class= "col-sm-4">
                                <input type = "text" class= "form-control allownumericwithdecimalnegative" id = "longitude_decimal" name = "longitude_decimal" readonly="readonly" value="<?php echo $asset['longitude_decimal'];?>" />
                            </div>
                            <div class= "col-sm-4">
                                <button type="button" class="btn btn-link" id="showmap" onclick="showMap();">Map</button>
                            </div>
                        </div> 
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="detail_submit" name="detail_submit">Save</button>
                    <input type="hidden" name="asset_form_post" id="asset_form_post" value="1" />
                    
                     <input type="hidden" id="customerid_a" name="customerid_a" value="<?php echo $asset['customerid'];?>" />
                    <input type="hidden" name="assetid" id="assetid" value="<?php echo $asset['assetid'];?>" />
 
                </div>
                    
            </form>    
        </div><!-- /.box -->
        
        
        <?php //$this->load->view('asset/assethistory');?>
        
        <?php //$this->load->view('asset/assetdocument');?>
         
    </div>
</div>
<?php $this->load->view('asset/modal_asset_location');?>
<?php $this->load->view('asset/modal_asset_sublocation');?>