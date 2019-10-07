
<div class="login-box" style="margin-bottom: 0px;">
      <div class="login-logo">
          <a href="<?php echo site_url()?>"><!--<b>DCFM</b>--><img  src="<?php echo base_url();?>assets/img/jobtrackercrop.png"  /></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body" >
        <p class="login-box-msg">Sign in to start your session</p>
        <!--<div class="alert-box error"><span>error: </span>Write your error message here.</div>
                            <div class="alert-box success"><span>success: </span>Write your success message here.</div>
                            <div class="alert-box warning"><span>warning: </span>Write your warning message here.</div>
                            <div class="alert-box notice"><span>notice: </span>Write your notice message here.</div>-->
         <?php 
         		if($this->session->flashdata('error')) 
         		{
		         	echo '<div class="alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
		       }
			   if($this->session->flashdata('success')) 
         		{
		         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
		       }
		?>		

 
        <form action="" method="post">
          <div class="form-group has-feedback <?php if (form_error('email')) echo ' has-error';?>">
            <input type="email" name="email" class="form-control" placeholder="Email"  value ="<?php echo set_value('email', $remember_me)?>"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			<?php echo form_error('email', '<label class="error" for="email" generated="true">', '</label>'); ?>
          </div>
          <div class="form-group has-feedback <?php if (form_error('password')) echo ' has-error';?>">
            <input type="password" name="password" class="form-control" placeholder="Password" />
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
			<?php echo form_error('password', '<label class="error" for="password" generated="true">', '</label>'); ?>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember_me" <?php if ($remember_me) {echo 'checked';}?>> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              
                <button type="submit" class="btn btn-danger btn-block btn-flat" <?php if(isset($site_module) && count($site_module) > 0 && $site_module['sitestatus']==0){ echo 'disabled="disabled"'; }?>>Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>
 
      <a href="<?php echo site_url('auth/forgotpassword'); ?>">Forgot Password</a><br>

      </div><!-- /.login-box-body -->
      
    </div><!-- /.login-box -->
    <?php if(isset($site_module) && count($site_module) > 0 && $site_module['sitemessagestatus']==1){
        ?>
    <div class="site-module-box text-center">
        <?php echo $site_module['sitemessage']; ?>
    </div>
    
    <?php }?>
    