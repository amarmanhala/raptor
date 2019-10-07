  <!-- Default box -->
  <div class= "box">
    <div class= "box-header with-border">
      <h3 class= "box-title">Add Address</h3>
    </div>
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
                  <input type = "text" name = "siteref" id="siteref" class= "form-control" placeholder= "Site Ref" value="<?php echo set_value('siteref'); ?>"/>
              </div> 
          </div>
          <div class= "form-group">
               <label for= "input" class= "col-sm-3 control-label">Company Name:</label>
              <div class= "col-sm-5">  
                  <input type = "text" name = "customername" class= "form-control" placeholder= "Company Name" value="<?php echo set_value('customername',$customer['companyname']); ?>"/>
              </div> 
             
          </div>  
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Street Address:</label>
                <div class= "col-sm-9">
                  <input type = "text" class= "form-control" id = "siteline1" name = "siteline1" value="<?php echo set_value('siteline1'); ?>"/>
                </div>
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">&nbsp;</label>
                <div class= "col-sm-9">
                  <input type = "text" class= "form-control" id = "siteline2" name = "siteline2" value="<?php echo set_value('siteline2'); ?>"/>
                </div>
        </div>
        
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Suburb:</label>
                <div class= "col-sm-4">
                   <input type="text" id="sitesuburb" name="sitesuburb" data-suburb= "sitesuburb1"  data-state = "sitestate" data-postcode = "sitepostcode" placeholder="search.." class="form-control suburbtypeahead" />
                   <input type="hidden" id="sitesuburb1" name="sitesuburb1" class= "updatesuburb" data-suburb= "suburb" value="" />
                </div>
                <div class= "col-sm-2">
                    <select name = "sitestate" id = "sitestate" class= "form-control">
                        <option value = ''>-Select-</option>
                        <?php foreach($states as $val) { ?>
                            <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                        <?php } ?>
                  </select>
                </div>
                <div class= "col-sm-3">
                    <input type="text" id="sitepostcode" name="sitepostcode" placeholder="Postcode" class="form-control postcodetypeahead"  />
                </div>
                
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Territory</label>
                <div class= "col-sm-4">
                    <input type = "text" class= "form-control" id = "territory" name = "territory" readonly = "readonly" />
                </div>
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">GPS Latitude:</label>
                <div class= "col-sm-4">
                    <input type = "text" class= "form-control allownumericwithdecimalnegative" id = "latitude_decimal" name = "latitude_decimal" readonly="readonly" />
                </div>
                <div class= "col-sm-4">
                    <button type="button" id="getgps" class="btn btn-default" onclick="getGPS();"><span style="display:none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Searching...</span><span style="display:block;">Get GPS</span></button>
                </div>
        </div>
        <div class= "form-group">
            <label for= "input" class= "col-sm-3 control-label">GPS Longitude:</label>
            <div class= "col-sm-4">
                <input type = "text" class= "form-control allownumericwithdecimalnegative" id = "longitude_decimal" name = "longitude_decimal" readonly="readonly" />
            </div>
        </div> 
        <div class="form-group">
            <label for="name" class="col-sm-3 control-label">Active</label>
            <div class="col-sm-9">
               <div class="checkbox">
                 <label>
                     <input type="checkbox" name="isactive" id="isactive" value="1" <?php if(set_value('isactive','1')=='1'){echo 'checked="checked"';} ?> >
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
                            <?php foreach($sitefm_contacts as $val) { ?>
                                <option value="<?php echo $val['contactid'];?>" data-phone="<?php echo $val['phone'];?>" data-mobile="<?php echo $val['mobile'];?>" data-email="<?php echo $val['email'];?>"><?php echo $val['sitefm'];?></option>
                            <?php } ?>
                         </select>
                        <div class="input-group-addon">
                            <a href="javascript:void(0)" onclick="openAddContact('sitefm');"  title="Add Site FM" ><i class="fa fa-fw fa-user-plus" title="Add Site FM"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Phone:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="phone" class= "form-control" readonly="readonly" />
                </div>
            </div>
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Mobile:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="mobile" class= "form-control" readonly="readonly" />
                </div>
            </div> 
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Email:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="email" class= "form-control" readonly="readonly" />
                </div>
            </div> 
            
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Site Contact:</label>
                <div class= "col-sm-8">
                    <div class="input-group">
                        <select name = "sitecontactid" id = "sitecontactid" class= "form-control" onchange="changeSiteContact(this);">
                            <option value = ''>-Select-</option>
                            <?php foreach($site_contacts as $val) { ?>
                                <option value="<?php echo $val['contactid'];?>" data-phone="<?php echo $val['phone'];?>" data-mobile="<?php echo $val['mobile'];?>" data-email="<?php echo $val['email'];?>" data-contact="<?php echo $val['sitecontact'];?>"><?php echo $val['sitecontact'];?></option>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="sitecontact" name="sitecontact" />
                        <div class="input-group-addon">
                            <a href="javascript:void(0)" onclick="openAddContact('sitecontact')"  title="Add Site Contact" ><i class="fa fa-fw fa-user-plus" title="Add Site Contact"></i></a>
                        </div>
                    </div> 
                </div>
            </div> 
            
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Phone:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="sitephone" name="sitephone" class= "form-control" readonly="readonly" />
                </div>
            </div>
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Mobile:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="sitemobile" name="sitemobile" class= "form-control" readonly="readonly" />
                </div>
            </div> 
            <div class= "form-group">
                <label for= "input" class= "col-sm-4 control-label">Email:</label>
                <div class= "col-sm-8">
                    <input type = "text" id="siteemail" name="siteemail" class= "form-control" readonly="readonly" />
                </div>
            </div> 
            
            <div class= "form-group">
              <label for= "input" class= "col-sm-4 control-label">&nbsp;</label>
               <div class= "col-sm-8">
                  
               </div>
          </div>  
           
        </div>
       
         
    </div><!-- /.box-body -->
    <div class="box-footer text-right">
         <button type ="submit" class="btn btn-primary"><span style="display:none;"><i class="fa fa-spinner fa-spin" ></i>&nbsp;Saving...</span><span style="display:block;">Save</span></button>
         <?php if($this->input->get('from')){ ?>
             <a href= "<?php echo site_url($this->input->get('from'));?>#sites" class= "btn btn-default">Cancel</a>
        <?php }
         else{ ?>
             <a href= "<?php echo site_url("customers/addresses");?>" class= "btn btn-default">Cancel</a>
        <?php } ?>
         
         
    </div>
     </form>
  </div><!-- /.box -->
  <?php $this->load->view('shared/addcontact');?>
  

      