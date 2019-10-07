<div class="login-box">
      <div class="login-logo">
          <a href="<?php echo site_url()?>"><!--<b>DCFM</b>--><img  src="<?php echo base_url();?>assets/img/jobtrackercrop.png"  /></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Please enter your Email so we can send you an email to reset your password</p>
        <?php 
     	   if($this->session->flashdata('error')) 
     	   {
	         	echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	       }
		   
		   if($this->session->flashdata('success')) 
     	   {
	         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';		       }
		?>		
        <form action="" method="post">
          <div class="form-group has-feedback <?php if (form_error('email')) echo ' has-error';?>">
            <input type="email" name="email" class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			<?php echo form_error('email', '<label class="error" for="email" generated="true">', '</label>'); ?>
          </div>
          
          <div class="row">
            <div class="col-xs-8">
               <a href="<?php echo site_url('auth/login'); ?>">Back to Login page</a><br>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-danger btn-block btn-flat">Save</button>
            </div><!-- /.col -->
          </div>
        </form>
 
 

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
 