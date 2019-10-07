<!-- Logo -->
<a href="<?php echo site_url('dashboard') ?>" class="logo" title="<?php echo trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE); ?>">
   <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b><?php echo RAPTOR_APP_TITLE; ?></b></span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b><?php echo RAPTOR_APP_TITLE; ?></b> <small><?php echo RAPTOR_APP_SUBTITLE; ?></small></span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </a>
 <div class="navbar-left"  style="float: left;">
    <ul class="nav" style="padding-top: 5px;">
        <li>
            <a href="javascript:void(0);"><?php echo $banner_array['companyname'];?></a>
        </li>
    </ul>
 </div>
     <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
     
        <li class="hidden-xs">
             <!-- search form -->
          <form action="<?php echo site_url('search/quick') ?>" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="searchtext" class="form-control" placeholder="Quick Search your (or DCFM) reference number, or site, or description." />
              <span class="input-group-btn">
                <button type="submit"  id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
            
        </li>
      <!-- User Account: style can be found in dropdown.less -->
      <li class="dropdown user user-menu">
        <a href="<?php echo site_url('dashboard') ?>" class="dropdown-toggle" data-toggle="dropdown">
          <img src="<?php echo get_profile_images($this->config->item('userphotos_dir'), $this->config->item('userphotos_path'), $loggeduser->photodocid); ?>" class="user-image" alt="<?php echo $loggeduser->firstname;?>" />
          <span class="hidden-xs"><?php echo $loggeduser->firstname;?></span>
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="<?php echo get_profile_images($this->config->item('userphotos_dir'), $this->config->item('userphotos_path'), $loggeduser->photodocid); ?>" class="img-circle" alt="<?php echo $loggeduser->firstname;?>" />
            <p>
              <?php echo $loggeduser->firstname;?>
           </p>
          </li>
          <!-- Menu Body -->
          <!--<li class="user-body">
                    <div class="col-xs-12 text-center">
                      <a href="<?php echo site_url('settings/changepassword'); ?>" class="btn btn-default btn-flat">Change Password</a>
                    </div>
                   
            </li>-->
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
               <a href="<?php echo site_url('settings/profile'); ?>" class="btn btn-info btn-flat">Profile</a>
            </div>
            <div class="pull-right">
              <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger btn-flat">Sign out</a>
            </div>
          </li>
        </ul>
      </li>
       
    </ul>
  </div>
</nav>
 