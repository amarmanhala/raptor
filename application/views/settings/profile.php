<form id="profile_form" action="" method="post" class="form-horizontal" enctype="multipart/form-data">
  <!-- Default box -->
  <div class="row">
            <div class="col-md-9">

                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title">Profile</h3>

                  </div>
                  <div class="box-body">
                      <p>  <?php 
                                      if($this->session->flashdata('error')) 
                                      {
                                              echo '<div class="alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
                                     }
                                         if($this->session->flashdata('success')) 
                                      {
                                              echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
                                     }
                              ?>	</p>
                      <div class="col-md-12">
                         
                        <div class="form-group <?php if (form_error('name')) echo ' has-error';?>">
                             <label for="old" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-9">  
                            <input type="text" name="name" class="form-control requeird" placeholder="Full Name" value="<?php echo $loggeduser->firstname;?>" />
                              <?php echo form_error('name', '<label class="error" for="name" generated="true">', '</label>'); ?>
                            </div> 

                        </div>
                         <div class="form-group">
                              <label for="input" class="col-sm-2 control-label">Address </label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" id="street1" name="street1" placeholder="Address1" value="<?php echo $loggeduser->street1;?>" />
                                <span class="help-block with-errors"></span>
                              </div>
                      </div>
                      <div class="form-group">
                              <label for="input" class="col-sm-2 control-label">&nbsp;</label>
                              <div class="col-sm-9">
                                <input type="text" class="form-control" id="street2" name="street2" placeholder="Address2" value="<?php echo $loggeduser->street2;?>" />
                                <span class="help-block with-errors"></span>
                              </div>
                      </div>
                      <div class="form-group">
                              <label for="input" class="col-sm-2 control-label">Suburb</label>
                              <div class="col-sm-4">
                                <input type="text" class="form-control suburbtypeahead" data-suburb="suburb1"  data-state="state" data-postcode="postcode" id="suburb" name="suburb" placeholder="search.."  value="<?php echo $loggeduser->suburb;?>" />
                                <input type="hidden" class="updatesuburb" data-suburb="suburb"  name="suburb1" id="suburb1"  value="<?php echo $loggeduser->suburb;?>" />
                                   <span class="help-block with-errors"></span>
                              </div>
                              <label for="input" class="col-sm-1 control-label">State </label>
                              <div class="col-sm-4">
                                <select name="state" id="state" class="form-control">
                                <option value="">-Select-</option>
                                <?php foreach($states as $key=>$value) { 
                                          $selected = '';
                                          if($loggeduser->state == $value['abbreviation']) {
                                              $selected = 'selected';
                                          }
                                    ?>
                                              <option value="<?php echo $value['abbreviation'];?>" <?php echo $selected;?>><?php echo $value['abbreviation'];?></option> 
                                <?php } ?>
                                </select>
                                <span class="help-block with-errors"></span>
                              </div>
                              </div>
                      <div class="form-group">
                              <label for="input" class="col-sm-2 control-label">Post Code</label>
                              <div class="col-sm-4">
                                <input type="text" class="form-control postcodetypeahead" id="postcode" name="postcode" placeholder="search.." value="<?php echo $loggeduser->postcode;?>" />
                                <span class="help-block with-errors"></span>
                              </div>

                      </div>
                      <div class="form-group">
                               <label for="input" class="col-sm-2 control-label">Mobile: </label>
                              <div class="col-sm-4">
                                <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $loggeduser->mobile;?>" pattern="[0-9]{4} [0-9]{3} [0-9]{3}" data-inputmask='"mask": "9999 999 999"' data-mask  />
                                <span class="help-block with-errors"></span>
                              </div>
                              <label for="input" class="col-sm-1 control-label">Phone</label>
                              <div class="col-sm-4">
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $loggeduser->phone;?>" pattern="[0-9]{2} [0-9]{4} [0-9]{4}" data-inputmask='"mask": "99 9999 9999"' data-mask />
                                <span class="help-block with-errors"></span>
                              </div>
                      </div>     


                       
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">&nbsp;</label>
                             <div class="col-sm-3">
                           <button type="submit" class="btn btn-primary btn-block btn-flat">Save</button>
                             </div>
                        </div>  
                  
                      </div>
                  </div><!-- /.box-body -->
                  <!--<div class="box-footer">
                    Footer
                  </div>--><!-- /.box-footer-->
                </div><!-- /.box -->
            </div>
                <div class="col-md-3">
                    
                    <?php if($UPLOAD_LOGO){ ?>
                <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                    <h3 class="profile-username text-center" style="margin-top: 0px;">Logo</h3>
                    <img class="profile-user-img img-responsive" id="selected-logo" height="90px" src="<?php echo get_logo_images($this->config->item('branding_dir'), $this->config->item('branding_path'), $loggeduser->customerid); ?>" alt="" />
                    <p class="text-muted text-center">preferred size (e.g. 230px x 90px)</p>
 
                    <span class="btn btn-primary btn-block btn-file"><span class="fileinput-new">Upload Logo</span><input type="file" name="companylogo" onchange="readcompanylogoURL(this);" ></span>
                </div><!-- /.box-body -->
              </div><!-- /.box --> 
                    <?php } ?>
              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" id="selected-profile" src="<?php echo get_profile_images($this->config->item('userphotos_dir'), $this->config->item('userphotos_path'), $loggeduser->photodocid); ?>" alt="<?php echo $loggeduser->firstname;?>" />
                  <h3 class="profile-username text-center"><?php echo $loggeduser->firstname;?></h3>
                  <p class="text-muted text-center"><?php echo $loggeduser->suburb;?></p>
 
                  <span class="btn btn-primary btn-block btn-file"><span class="fileinput-new">Upload user photo</span><input type="file" name="profilepic" onchange="readProfileURL(this);" ></span>
                </div><!-- /.box-body -->
              </div><!-- /.box --> 

              <!-- About Me Box -->
              
              
            </div><!-- /.col -->
  </div>   
</form>