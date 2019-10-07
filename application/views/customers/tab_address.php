  <!-- Default box -->
  <div class= "box">
      <form id = "addressForm" name="addressForm" class= "form-horizontal" method="post" autocomplete="off" novalidate>
    <div class= "box-body">
        <?php 
            if($this->session->flashdata('error')) 
            {
                echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	
            }
        ?>
        
        <div class= "col-md-7">
          <div class= "form-group">
               <label for= "input" class= "col-sm-3 control-label">Site Ref:</label>
              <div class= "col-sm-5">  
                  <input type = "text" name = "siteref" class= "form-control" placeholder= "Site Ref" value="<?php echo $address['siteref'];?>" />
              </div> 
             
          </div>
          <div class= "form-group">
               <label for= "input" class= "col-sm-3 control-label">Company Name:</label>
              <div class= "col-sm-5">  
                  <input type = "text" id="customername" name = "customername" class= "form-control" placeholder= "Company Name" value="<?php echo $address['siteline1'];?>" />
              </div> 
             
          </div>  
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Street Address:</label>
                <div class= "col-sm-9">
                  <input type = "text" class= "form-control" id = "siteline1" name = "siteline1" placeholder= "" value="<?php echo $address['siteline2'];?>" />
                </div>
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">&nbsp;</label>
                <div class= "col-sm-9">
                  <input type = "text" class= "form-control" id = "siteline2" name = "siteline2" placeholder= "" value="<?php echo $address['siteline3'];?>" />
                </div>
        </div>
        
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Suburb:</label>
                <div class= "col-sm-4">
                    <input type="text" id="sitesuburb" name="sitesuburb" data-suburb= "sitesuburb1"  data-state = "sitestate" data-postcode = "sitepostcode" placeholder="search.." class="form-control suburbtypeahead" value="<?php echo $address['sitesuburb'];?>" />
                    <input type="hidden" id="sitesuburb1" name="sitesuburb1" class= "updatesuburb" data-suburb= "suburb" value="<?php echo $address['sitesuburb'];?>" />
                </div>
                <div class= "col-sm-2">
                    <select name = "sitestate" id = "sitestate" class= "form-control">
                        <option value = ''>-Select-</option>
                        <?php foreach($states as $val) { 
                                $selected = '';
                                if($val['abbreviation'] == $address['sitestate']) {
                                    $selected = 'selected';
                                }
                            ?>
                            <option value="<?php echo $val['abbreviation'];?>" <?php echo $selected;?>><?php echo $val['abbreviation'];?></option>
                        <?php } ?>
                  </select>
                </div>
                <div class= "col-sm-3">
                    <input type="text" id="sitepostcode" name="sitepostcode" placeholder="Postcode" class="form-control postcodetypeahead" value="<?php echo $address['sitepostcode'];?>"  />
                </div>
                
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Territory</label>
                <div class= "col-sm-4">
                    <input type = "text" class= "form-control" id = "territory" name = "territory" readonly = "readonly" value="<?php echo $address['territory'];?>" />
                </div>
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">GPS Latitude:</label>
                <div class= "col-sm-4">
                    <input type = "text" class= "form-control allownumericwithdecimalnegative" id = "latitude_decimal" name = "latitude_decimal" readonly="readonly" value="<?php echo $address['latitude_decimal'];?>" />
                </div>
                <div class= "col-sm-4">
                    <button type="button" class="btn btn-default" id="getgps" onclick="getGPS();"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Searching...</span><span style="display:block;">Get GPS</span></button>
                </div>
        </div>
        <div class= "form-group">
            <label for= "input" class= "col-sm-3 control-label">GPS Longitude:</label>
            <div class= "col-sm-4">
                <input type = "text" class= "form-control allownumericwithdecimalnegative" id = "longitude_decimal" name = "longitude_decimal" readonly="readonly" value="<?php echo $address['longitude_decimal'];?>" />
            </div>
            <div class= "col-sm-4">
                <button type="button" class="btn btn-link" id="showmap" onclick="showMap();">Map</button>
            </div>
        </div> 
        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">Active</label>
            <div class="col-sm-9">
               <div class="checkbox">
                 <label>
                     <input type="checkbox" name="isactive" id="isactive" value="1" <?php if(set_value('isactive', $address['isactive']) == '1'){echo 'checked="checked"';} ?> >
                 </label>
               </div>
            </div>
        </div>
    </div>
        <div class= "col-md-5">
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Site FM:</label>
                <div class= "col-sm-8">
                    <div class="input-group">
                        <select name = "contactid" id = "contactid" class= "form-control" onchange="changeSiteFM(this);">
                            <option value = ''>-Select-</option>
                            <?php foreach($sitefm_contacts as $val) { 
                                    $selected = '';
                                    if($val['contactid'] == $address['contactid']) {
                                        $selected = 'selected';
                                    }
                                ?>
                                <option value="<?php echo $val['contactid'];?>" <?php echo $selected;?> data-phone="<?php echo $val['phone'];?>" data-mobile="<?php echo $val['mobile'];?>" data-email="<?php echo $val['email'];?>"><?php echo $val['sitefm'];?></option>
                            <?php } ?>
                        </select>
                        <div class="input-group-addon">
                            <a href="javascript:void(0)" onclick="openAddContact('sitefm');" title="Add Site FM" ><i class="fa fa-fw fa-user-plus" title="Add Site FM"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Phone:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="phone" class= "form-control" readonly="readonly" value="<?php echo $address['phone'];?>" />
                </div>
            </div>
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Mobile:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="mobile" class= "form-control" readonly="readonly" value="<?php echo $address['mobile'];?>" />
                </div>
            </div> 
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Email:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="email" class= "form-control" readonly="readonly" value="<?php echo $address['email'];?>" />
                </div>
            </div> 
            
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Site Contact:</label>
                <div class= "col-sm-8">
                    <div class="input-group">
                        <select name = "sitecontactid" id = "sitecontactid" class= "form-control" onchange="changeSiteContact(this);">
                            <option value = ''>-Select-</option>
                            <?php foreach($site_contacts as $val) { 
                                $selected = '';
                                if($val['contactid'] == $address['sitecontactid']) {
                                    $selected = 'selected';
                                }
                            ?>
                                <option value="<?php echo $val['contactid'];?>" <?php echo $selected;?> data-phone="<?php echo $val['phone'];?>" data-mobile="<?php echo $val['mobile'];?>" data-email="<?php echo $val['email'];?>" data-contact="<?php echo $val['sitecontact'];?>"><?php echo $val['sitecontact'];?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="sitecontact" name="sitecontact" value="<?php echo $address['sitecontact'];?>" />
                        <div class="input-group-addon">
                            <a href="javascript:void(0)" onclick="openAddContact('sitecontact');"  title="Add Site Contact" ><i class="fa fa-fw fa-user-plus" title="Add Site Contact"></i></a>
                        </div>
                    </div> 
                </div>
            </div> 
            
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Phone:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="sitephone" name="sitephone" class= "form-control" readonly="readonly" value="<?php echo $address['sitephone'];?>" />
                </div>
            </div>
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Mobile:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="sitemobile" name="sitemobile" class= "form-control" readonly="readonly" value="<?php echo $address['sitemobile'];?>" />
                </div>
            </div> 
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Email:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="siteemail" name="siteemail" class= "form-control" readonly="readonly" value="<?php echo $address['siteemail'];?>" />
                </div>
            </div>  
        </div>
        
    </div><!-- /.box-body -->
    
    <div class="box-footer">
        <div class="col-sm-8">
        <div class= "form-group ">
            <label for= "input" class= "col-sm-1 control-label">Id:</label>
            <div class= "col-sm-2">
                <input type = "text" readonly = "readonly" class= "form-control" value="<?php echo $address['labelid'];?>" />
            </div>

            <label for= "input" class= "col-sm-1 control-label">Added:</label> 
            <div class= "col-sm-3">
                <input type = "text" readonly = "readonly" class= "form-control" value="<?php echo $address['dateadded'];?>" />
            </div>

            <label for= "input" class= "col-sm-1 control-label" style = "padding-left: 0px;padding-right: 0px;">Updated:</label>
            <div class= "col-sm-3">
                <input type = "text" readonly = "readonly" class= "form-control" value="<?php echo $address['editdate'];?>" />
            </div>
        </div>    
        </div>
        <div class="col-sm-4 text-right"><button type ="submit" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin" ></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
        <a href= "<?php echo site_url("customers/addresses");?>" class= "btn btn-default">Cancel</a></div>
        
    </div>
    <input type="hidden" id="labelid" name="labelid" value="<?php echo $address['labelid'];?>" />
</form>

  </div><!-- /.box -->
  

      