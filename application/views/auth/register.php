<div class="register-box">
      <div class="login-logo">
          <a href="<?php echo site_url()?>"><!--<b>DCFM</b>--><img  src="<?php echo base_url();?>assets/img/jobtrackercrop.png"  /></a>
      </div><!-- /.login-logo -->

      <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
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
        <form action="" method="post" name="register_form" id="register_form">
          <div class="form-group has-feedback  <?php if (form_error('firstname')) echo ' has-error';?>">
            <input type="text" class="form-control requeird" placeholder="Full name" name="firstname" id="firstname" requeird value="<?php echo $this->input->post('firstname');?>" />
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
            	<?php echo form_error('firstname', '<label class="error" for="firstname" generated="true">', '</label>'); ?>
          </div>
          <div class="form-group has-feedback  <?php if (form_error('email')) echo ' has-error';?>">
            <input type="email" class="form-control requeird" placeholder="Email" name="email" id="email" requeird value="<?php echo $this->input->post('email');?>"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            	<?php echo form_error('email', '<label class="error" for="email" generated="true">', '</label>'); ?>
          </div>
          <div class="form-group has-feedback  <?php if (form_error('password')) echo ' has-error';?>">
            <input type="password" class="form-control requeird" placeholder="Password" name="password" id="password" requeird/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            	<?php echo form_error('password', '<label class="error" for="password" generated="true">', '</label>'); ?>
          </div>
          <div class="form-group has-feedback  <?php if (form_error('confirm_password')) echo ' has-error';?>">
            <input type="password" class="form-control requeird" placeholder="Retype password" name="confirm_password" id="confirm_password" requeird/>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            	<?php echo form_error('confirm_password', '<label class="error" for="confirm_password" generated="true">', '</label>'); ?>
          </div>
          <div class="row">
            <div class="col-xs-8 <?php if (form_error('agree')) echo ' has-error';?>">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="agree" value="1" class="requeird"> I agree to the terms
                </label>
              </div>
              <?php echo form_error('agree', '<label class="error" for="agree" generated="true">', '</label>'); ?>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
            </div><!-- /.col -->
          </div>
        </form>
 
        <a href="<?php echo site_url('auth/login'); ?>" class="text-center">I already have a account</a>
      </div><!-- /.form-box -->
    </div><!-- /.register-box -->

