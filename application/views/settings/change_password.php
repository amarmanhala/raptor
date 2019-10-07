<!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Change Password</h3>
       
    </div>
    <div class="box-body">
        <p>  <?php 
        if($this->session->flashdata('message')) 
                {
                        echo '<div class="alert alert-warning">'.$this->session->flashdata('message').'</div>';	
               }

                if($this->session->flashdata('error')) 
                {
                        echo '<div class="alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
               }
                   if($this->session->flashdata('success')) 
                {
                        echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
               }
            if (isset($ContactRules["allow_simple_password"]) && $ContactRules["allow_simple_password"] == 1){
               $password_help = '<p>Password must contain at least '. $this->config->item('min_password_length', 'ion_auth')  .' characters long.</p>';
            }
            else{
                //, one special character(!@#$%&*()^,._;:-), one number(0-9)
                $password_help = '<p>Password must contain at least one uppercase character(A-Z) and be at least '. $this->config->item('min_password_length', 'ion_auth')  .' characters long.</p>';
            }
                        
                       
		?>	</p>
        <div class="col-md-7">
        <form action="" method="post" class="form-horizontal">
          <div class="form-group has-feedback <?php if (form_error('old')) echo ' has-error';?>">
               <label for="old" class="col-sm-4 control-label">Current Password</label>
              <div class="col-sm-6">  
                  <input type="password" name="old" class="form-control" placeholder="Old Password" value="<?php echo set_value('old');?>" autocomplete="off" />
               <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <?php echo form_error('old', '<label class="error" for="old" generated="true">', '</label>'); ?>
              </div> 
             
          </div>
          <div class="form-group has-feedback <?php if (form_error('new')) echo ' has-error';?>">
             <label for="new" class="col-sm-4 control-label">New Password</label>
             <div class="col-sm-6"> 
            <input type="password" name="new" class="form-control" placeholder="New Password" value="<?php echo set_value('new');?>" data-toggle="tooltip2" title="<?php echo $password_help;?>" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <?php echo form_error('new', '<label class="error" for="new" generated="true">', '</label>'); ?>
             </div>
            
          </div>
          <div class="form-group has-feedback <?php if (form_error('new_confirm')) echo ' has-error';?>">
            <label for="new_confirm" class="col-sm-4 control-label">Confirm Password</label>
            <div class="col-sm-6">
            <input type="password" name="new_confirm" class="form-control" placeholder="Confirm Password"  value="<?php echo set_value('new_confirm');?>"  data-toggle="tooltip2" title="<?php echo $password_help;?>" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
               <?php echo form_error('new_confirm', '<label class="error" for="new_confirm" generated="true">', '</label>'); ?>
            </div>
         
          </div>
          <div class="form-group has-feedback <?php if (form_error('new_confirm')) echo ' has-error';?>">
              <label for="new_confirm" class="col-sm-4 control-label">&nbsp;</label>
               <div class="col-sm-8">
             <button type="submit" class="btn btn-primary btn-flat">Save</button>
               </div>
          </div>  
	</form>
        </div>
    </div><!-- /.box-body -->
    <!--<div class="box-footer">
      Footer
    </div>--><!-- /.box-footer-->
  </div><!-- /.box -->

      