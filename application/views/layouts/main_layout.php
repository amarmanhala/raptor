<!DOCTYPE html>
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
    <!-- Ionicons -->
    <?php echo link_tag('assets/css/ionicons.min.css', 'stylesheet', 'text/css'); ?>
    
   
    <?php if (isset($cssToLoad) && count($cssToLoad)) : ?>
               <?php foreach ($cssToLoad as $css) : ?>
                <?php if (is_array($css) && count($css)) : ?>
                    <link rel="stylesheet" href="<?php echo $css['src'];?>" type ="text/css" media ="<?php echo $css['media'];?>" />
                 <?php else : ?>
                   <link rel="stylesheet" href="<?php echo $css; ?>" type ="text/css" />
                <?php endif; ?>
               <?php endforeach; ?>
    <?php endif; ?>
     <!-- Theme style -->
     <?php echo link_tag('assets/css/font-sourcesans.css', 'stylesheet', 'text/css'); ?> 
    <?php echo link_tag('assets/css/AdminLTE.css', 'stylesheet', 'text/css'); ?>
 
     <!-- AdminLTE Skins.-->
    <?php echo link_tag('assets/css/skins/skin-red.css', 'stylesheet', 'text/css'); ?>

      
	  
    <?php echo link_tag('assets/css/custom.css', 'stylesheet', 'text/css'); ?>
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
       <script src="<?php echo base_url('assets/js/html5shiv.min.js') ?>" type="text/javascript"></script>
       <script src="<?php echo base_url('assets/js/respond.min.js') ?>" type="text/javascript"></script>
       
    <![endif]-->
     <script src="<?php echo base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>
    <?php if (isset($headerJsToLoad) && count($headerJsToLoad)) : ?>
        <?php foreach ($headerJsToLoad as $js) : ?>
            <?php if (is_array($js) && count($js)) : ?>
                <script type="text/javascript"
                        src="<?php echo $js['src']; ?>"<?php if ($js['async']) : ?> async="async"<?php endif; ?>></script>
            <?php else : ?>
                <script type="text/javascript" src="<?php echo $js; ?>"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <script type="text/javascript">
        var base_url = "<?php echo site_url();?>";
        var base_img_url = "<?php echo base_url();?>";
        var defaultdateformat = "<?php echo javascript_date_formats(RAPTOR_DISPLAY_DATEFORMAT) ?>";
        var marketingWidth = "<?php echo $this->config->item('MARKETING_WIDTH');?>";
        var marketingPauseTime = "<?php echo $this->config->item('MARKETING_PAUSETIME');?>";
        var fullyear = "<?php echo date('Y', time());?>";
        var month = "<?php echo date('m', time());?>";
        month = month-1;
        var day = "<?php echo date('d', time());?>";
    </script>
    
  </head>
  <body class="skin-red sidebar-mini<?php if($this->config->item('SHOW_MARKETING')) { echo ' control-sidebar-open';}?>">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
       <?php echo $template['partials']['header'] ?>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <?php echo $template['partials']['navigation'] ?>
        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->
      	<!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
		<!-- Content Header (Page header) -->
                <?php 
                       if($banner_array['active'] == 1 && $banner_array['header']!="") { 
                ?>
                <div class="header-banner">
                    <img height="100" src="<?php echo $banner_array['header'];?>" />
                </div>
               
          <?php } ?>
        	<section class="content-header">
        		<?php echo $template['partials']['page_header'] ?>
            </section>

	        <!-- Main content -->
	        <section class="content">
				<?php echo $template['body'] ?>
		    </section>
		    <!-- /.content -->
		</div>
	      	<!-- /.content-wrapper -->	
            <footer class="main-footer">
             <?php echo $template['partials']['footer'] ?>
              
            </footer>
                <?php if($this->config->item('SHOW_MARKETING')) { ?>
            <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-light" style="position: absolute; height: 100%;">
            <?php $this->load->view('shared/marketing');?>
        </aside>
        <div class="control-sidebar-bg"></div>
        <?php } ?>
    </div><!-- ./wrapper -->

    <?php $this->load->view('shared/jobdetaildialog'); ?>
   <?php $this->load->view('shared/announcements'); ?>
    <?php $this->load->view('shared/browserannouncements'); ?>
    <?php if($this->config->item('SHOW_MARKETING')) { 
        $this->load->view('shared/orgchart'); ?>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="<?php echo base_url('assets/js/google-orgchart.js') ?>" type="text/javascript"></script>
    <?php } ?>
    <!-- Bootstrap 3.3.2 JS -->
   
    <script src="<?php echo base_url('bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/bootbox.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/uigrid-row-height.js') ?>" type="text/javascript"></script>
    <?php if (isset($jsToLoad) && count($jsToLoad)) : ?>
        <?php foreach ($jsToLoad as $js) : ?>
            <?php if (is_array($js) && count($js)) : ?>
                <script type="text/javascript"
                        src="<?php echo $js['src']; ?>"<?php if ($js['async']) : ?> async="async"<?php endif; ?>></script>
            <?php else : ?>
                <script type="text/javascript" src="<?php echo $js; ?>"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="<?php echo base_url('assets/js/jquery.cookies.js') ?>" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('assets/js/app.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/js/custom.js') ?>" type="text/javascript"></script>
     <!-- js load based on custom.js -->
    <?php if (isset($viewJsToLoad) && count($viewJsToLoad)) : ?>
        <?php foreach ($viewJsToLoad as $js) : ?>
            <?php if (is_array($js) && count($js)) : ?>
                <script type ="text/javascript"
                        src="<?php echo $js['src']; ?>"<?php if ($js['async']) : ?> async="async"<?php endif; ?>></script>
            <?php else : ?>
                <script type ="text/javascript" src="<?php echo $js; ?>"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
   
  </body>
</html>

 