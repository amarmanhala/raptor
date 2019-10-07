<div class="login-box">
      <div class="login-logo">
          <a href="<?php echo site_url()?>"><!--<b>DCFM</b>--><img  src="<?php echo base_url();?>assets/img/jobtrackercrop.png"  /></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Please Enter your Valid Email and New Password</p>
        <p>  <?php 
         		if($this->session->flashdata('error')) 
         		{
		         	echo '<div class="alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
		       }
			   if($this->session->flashdata('success')) 
         		{
		         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
		       }
                       $password_help = '<p>Password must contain at least one uppercase character(A-Z), one special character(!@#$%&*()^,._;:-), one number(0-9) and be at least '. $this->config->item('min_password_length', 'ion_auth')  .' characters long.</p>';
		?>	</p>
        <form action="" method="post">
          <div class="form-group has-feedback <?php if (form_error('email')) echo ' has-error';?>">
            <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo set_value('email');?>"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            	<?php echo form_error('email', '<label class="error" for="email" generated="true">', '</label>'); ?>
          </div>
          <div class="form-group has-feedback <?php if (form_error('password')) echo ' has-error';?>">
            <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo set_value('password');?>" data-toggle="tooltip2" title="<?php echo $password_help;?>"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            	<?php echo form_error('password', '<label class="error" for="password" generated="true">', '</label>'); ?>
          </div>
          <div class="form-group has-feedback <?php if (form_error('confirm_password')) echo ' has-error';?>">
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" value="<?php echo set_value('confirm_password');?>" data-toggle="tooltip2" title="<?php echo $password_help;?>"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            	<?php echo form_error('confirm_password', '<label class="error" for="confirm_password" generated="true">', '</label>'); ?>
          </div>
          
          <div class="row">
             
            <div class="col-xs-12">
                <?php echo form_hidden('code', $code);?>
		<?php echo form_hidden($csrf); ?>
		<?php echo form_hidden('user_id', $user_id); ?>
              <button type="submit" class="btn btn-danger btn-block btn-flat">Save</button>
            </div><!-- /.col -->
          </div>
        </form>
 
 

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->