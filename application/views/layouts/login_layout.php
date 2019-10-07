<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo $template['title'] ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.4 -->
    <?php echo link_tag('bootstrap/css/bootstrap.min.css', 'stylesheet', 'text/css'); ?>
 
    <!-- Font Awesome Icons -->
    <?php echo link_tag('assets/css/font-awesome.min.css', 'stylesheet', 'text/css'); ?>
    
    <!-- Theme style -->
    <?php echo link_tag('assets/css/font-sourcesans.css', 'stylesheet', 'text/css'); ?> 
    <?php echo link_tag('assets/css/AdminLTE.min.css', 'stylesheet', 'text/css'); ?>
 
    <!-- iCheck -->
    <?php echo link_tag('plugins/iCheck/square/grey.css', 'stylesheet', 'text/css'); ?>
 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
       <script src="<?php echo site_url('assets/js/html5shiv.min.js') ?>" type="text/javascript"></script>
       <script src="<?php echo site_url('assets/js/respond.min.js') ?>" type="text/javascript"></script>
       
    <![endif]-->
    <style type="text/css">
         .icheckbox_square-grey {
            background-position: -24px 0;
         }
        .login-page, .register-page {
            background: #fff none repeat scroll 0 0;
            border-top: 10px solid #481468;
        }
        
        .login-box, .register-box {
            margin: 3% auto;
            max-width: 768px;
            width:100%;
            border:1px solid #d2d6de;
            padding-bottom: 25px;
        }
        .login-box .login-logo img{width:100%;}
        .login-box-body, .register-box-body{  max-width: 360px;
            width:100%;margin: auto;background-color: #d2d6de;}
        
.site-module-box {
    border: 0px solid red;
    margin: auto;
    max-width: 360px;
    padding: 5px;
    width: 100%;
    font-size: 35px;
}
.footer-logo{
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 25px;
    text-align: center;
}
.footer-logo img{
    max-width: 160px;
}
    </style>
  </head>
  <body class="login-page">
  
        <?php echo $template['body'] ?>
    <div class="footer-logo">
        <span class="small">Powered By</span><br>
          <a href="<?php echo site_url()?>"><img  src="<?php echo base_url();?>assets/img/itglobal.jpg"  /></a>
      </div><!-- /.login-logo -->
  	 
     <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>
     <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url('bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
       <!-- iCheck -->
    <script src="<?php echo base_url('plugins/iCheck/icheck.min.js') ?>" type="text/javascript"></script>
    <!-- jQuery 2.1.4 -->
  
    
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-grey',
          radioClass: 'iradio_square-grey',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>

 