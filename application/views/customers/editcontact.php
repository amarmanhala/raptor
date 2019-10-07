  <!-- Default box -->
  <div class= "box">
    <div class= "box-header with-border">
      <h3 class= "box-title">Edit Contact</h3>
       
    </div>
    <div class= "box-body">
        
    <form id = "contact_form" action= '' method = "post" class= "form-horizontal" enctype = "multipart/form-data">
        <div class= "col-md-9">

            <div class= "form-group <?php if (form_error('firstname')) echo ' has-error';?>">
               <label for= "input" class= "col-sm-3 control-label">Name</label>
              <div class= "col-sm-7">  
              <input type = "text" name = "firstname" class= "form-control" placeholder= "First Name" value = "<?php echo $contact['firstname'];?>" />
                <?php echo form_error('firstname', '<span class= "help-block error" for= "firstname" generated = "TRUE">', '</span>'); ?>
              </div> 
             
          </div>

            <div class= "form-group <?php if (form_error('position')) echo ' has-error';?>">
               <label for= "input" class= "col-sm-3 control-label">Position</label>
              <div class= "col-sm-7">  
              <input type = "text" name = "position" class= "form-control" placeholder= "position" value = "<?php echo $contact['position'];?>" />
                <?php echo form_error('position', '<span class= "help-block error" for= "position" generated = "TRUE">', '</span>'); ?>
              </div> 
             
          </div>
            
          <div class= "form-group <?php if (form_error('role')) echo ' has-error';?>">
               <label for= "input" class= "col-sm-3 control-label">Role</label>
              <div class= "col-sm-4"> 
                    <select name = "role" id = "role" class= "form-control">
                                    <option value = ''>-Select-</option>
                                     <?php foreach ($role as $key => $value) { 
                                         $selected = '';
                                         if ($value['name'] == $contact['role']) {
                                             $selected = " selected";
                                         }
                                    ?>
                                        <option value = "<?php echo $value['name'];?>"<?php echo $selected;?>><?php echo $value['name'];?></option> 
                                    <?php 
                                    } ?>
                    </select>
                <?php echo form_error('role', '<span class= "help-block error" for= "role" generated = "TRUE">', '</span>'); ?>
              </div> 
          </div>  
          
        <div class= "form-group">
                 <label for= "input" class= "col-sm-3 control-label">Mobile </label>
                <div class= "col-sm-4">
                  <input type = "text" class= "form-control" id = "mobile" name = "mobile" value = "<?php echo $contact['mobile'];?>" pattern= "[0-9]{4} [0-9]{3} [0-9]{3}" data-inputmask= '"mask": "9999 999 999"' data-mask  />
                </div>
                
        </div>  
            
            <div class= "form-group">

                <label for= "input" class= "col-sm-3 control-label">Phone</label>
                <div class= "col-sm-4">
                  <input type = "text" class= "form-control" id = "phone" name = "phone" value = "<?php echo $contact['phone'];?>" pattern= "[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask= '"mask": "99 9999 9999"' data-mask />
                </div>
        </div>  
       
          <div class= "form-group">
               <label for= "input" class= "col-sm-3 control-label">Email</label>
              <div class= "col-sm-7">  
                  <input type = "text" name = "email" class= "form-control" placeholder= "Email" value = "<?php echo $contact['email'];?>" readonly = "readonly" />
              </div> 
          </div>
            
            <div style="display:none;" class= "form-group <?php if (form_error('mailgroup')) echo ' has-error';?>">
               <label for= "input" class= "col-sm-3 control-label">Mail Group</label>
              <div class= "col-sm-5">
                    <select id = "mailgroup" name = "mailgroup[]" class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "Mail Group" data-size = "auto" data-width= "100%">
                        <?php 
                        foreach ($mailgroups as $key => $value) {
                                $selected = '';
                            ?>
                            <option value = "<?php echo $value['id'];?>"<?php echo $selected;?>><?php echo $value['name'] ;?></option> 
                        <?php } ?>
                    </select>
                <?php echo form_error('mailgroup', '<span class= "help-block error" for= "mailgroup" generated = "TRUE">', '</span>'); ?>
              </div> 
          </div>

           <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Address </label>
                <div class= "col-sm-7">
                  <input type = "text" class= "form-control" id = "street1" name = "street1" placeholder= "Address1" value = "<?php echo $contact['street1'];?>" />
                </div>
        </div>
        <div class= "form-group">
            <label for= "input" class= "col-sm-3 control-label">&nbsp;</label>
                <div class= "col-sm-7">
                  <input type = "text" class= "form-control" id = "street2" name = "street2" placeholder= "Address2" value = "<?php echo $contact['street2'];?>" />
                </div>
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Suburb/State/Post Code</label>
                <div class= "col-sm-3">
                      <input type = "text" class= "form-control  suburbtypeahead" data-suburb= "suburb1"  data-state = "state" data-postcode = "postcode" id = "suburb" name = "suburb" placeholder= "search.."  value = "<?php echo $contact['suburb'];?>" />
                 <input type = "hidden" class= "updatesuburb" data-suburb= "suburb" name = "suburb1" id = "suburb1"   value = "<?php echo set_value('suburb');?>" />
                </div>
                <div class= "col-sm-2">
                  <select name = "state" id = "state" class= "form-control">
                  <option value = ''>-Select-</option>
                  <?php foreach ($states as $key => $value) { 
                            $selected = '';
                            if ($contact['state'] == $value['abbreviation']) {
                                $selected = 'selected';
                            }
                      ?>
                                <option value = "<?php echo $value['abbreviation'];?>" <?php echo $selected;?>><?php echo $value['abbreviation'];?></option> 
                  <?php } ?>
                  </select>
                </div>
                <div class= "col-sm-2">
                  <input type = "text" class= "form-control postcodetypeahead" id = "postcode" name = "postcode" placeholder= "search.." value = "<?php echo $contact['postcode'];?>" />
                </div>
                
        </div>
        <div class= "form-group">
                <label for= "input" class= "col-sm-3 control-label">Territory</label>
                <div class= "col-sm-4">
                    <input type = "text" class= "form-control" id = "territory" name = "territory" readonly = "readonly" value = "<?php echo $contact['territory'];?>" />
                </div>
        </div> 
            
            <div class= "form-group <?php if (form_error('bossid')) echo ' has-error';?>">
               <label for= "input" class= "col-sm-3 control-label">Reports To</label>
              <div class= "col-sm-4"> 
                    <select name = "bossid" id = "bossid" class= "form-control">
                        <option value = ''>-Select-</option>
                         <?php foreach ($reportstocontacts as $key => $value) {
                             $selected = '';
                             if ($value['contactid'] == $contact['bossid']) {
                                 $selected = " selected";
                             }
                        ?>
                            <option value = "<?php echo $value['contactid'];?>"<?php echo $selected;?>><?php echo $value['name'];?></option> 
                        <?php 
                        } ?>
                    </select>
                <?php echo form_error('bossid', '<span class= "help-block error" for= "bossid" generated = "TRUE">', '</span>'); ?>
              </div> 
          </div>
            
        <div class="form-group">
           <label class="col-sm-3 control-label">Active</label>
           <div class="col-sm-6">
              <div class="checkbox">
                <label>
                    <input type="checkbox" name="active" value="1" <?php if($contact['active'] == "1") { echo "checked";}?>>
                </label>
              </div>
           </div>
        </div>

          <div class= "form-group">
              <label for= "input" class= "col-sm-3 control-label">&nbsp;</label>
               <div class= "col-sm-5">
             <button type = "submit" class= "btn btn-primary">Save</button>
             <a href= "<?php echo site_url('customers/contacts');?>" class= "btn btn-default" />Cancel</a>
               </div>

          </div>  
	
        </div>
        <div class= "col-md-3">
            
             <!-- Profile Image -->
              <div class= "box box-primary">
                <div class= "box-body box-profile">
                    <img class= "profile-user-img img-responsive img-circle" id = "selected-profile" src= "<?php echo get_profile_images($this->config->item('userphotos_dir'), $this->config->item('userphotos_path'), $contact['photodocid']);?>" alt = "User Image" />
                
                  <p class= "text-muted text-center">&nbsp;</p>
 
                  <span class= "btn btn-primary btn-block btn-file"><span class= "fileinput-new">choose Profile Image</span><input type = "file" name = "profilepic" onchange = "readProfileURL(this);" ></span>
                </div><!-- /.box-body -->
              </div><!-- /.box --> 
           
        </div>
        <input type = "hidden" name = "isprimary" id = "isprimary" value = '' />
        <input type = "hidden" name = "editcontact" id = "editcontact" value = "<?php echo $contact['contactid'];?>" />
        </form>
    </div><!-- /.box-body -->
    <!--<div class= "box-footer">
      Footer
    </div>--><!-- /.box-footer-->
  </div><!-- /.box -->