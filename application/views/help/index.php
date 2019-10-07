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
 
 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
       <script src="<?php echo site_url('assets/js/html5shiv.min.js') ?>" type="text/javascript"></script>
       <script src="<?php echo site_url('assets/js/respond.min.js') ?>" type="text/javascript"></script>
       
    <![endif]-->
    
     <![endif]-->
    <script type="text/javascript">
        var base_url = "<?php echo site_url();?>";
        var base_img_url = "<?php echo base_url();?>";
        var table;
        var defaultdateformat = "<?php echo javascript_date_formats(RAPTOR_DISPLAY_DATEFORMAT) ?>";
    </script>
    
    <style type="text/css">
       
        .help-box {
            margin: 3% auto;
            max-width: 800px;
            width:100%;
            border:2px solid #000;
            
            background-color: #FFFFFF;
        }
        .help-box-header{
            width:100%;padding: 10px 20px;}
        .help-box-body{
            width:100%;padding: 10px 30px;}
        
        .help-box-footer{
            width:100%;background: #000;padding: 10px 30px;color: #FFFFFF;}
        .help-links li a{
            font-size: 17px; 
            text-decoration: underline;
        }
    </style>
  </head>
    <body class="login-page">
        <div class="help-box box">
            <div class="box-header help-box-header">
                <h2 class="no-margin" style="padding-bottom: 20px; border-bottom: 3px solid #3c8dbc;"><?php echo $help['caption'];?></h2>
            </div><!-- /.box-header -->
            <div class="box-body help-box-body">
                <?php
                if(count($help_links)>0){   ?>
                <ul class="list-unstyled help-links">
                <?php foreach ($help_links as $key => $value) {
                    echo '<li><a href="'.$value['link'].'" target="_blank">Video: '.$value['caption'].'</a>';
                    if($value['isvideo']==1){
                        //$url = $value['link'];
                        //parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
                        //echo $my_array_of_vars['v']
                        echo '<br><iframe src="https://www.youtube.com/embed/'.getYouTubeVideoId($value['link']).'" width="420" height="315" allowfullscreen></iframe>';
                    }
                    echo '</li>';
                }
                ?>
                </ul>
                <?php } ?>
              
                    <p><?php echo $help['content'];?></p>
         
            </div><!-- /.box-body -->
            <div class="box-footer help-box-footer">
                <h3>Was this page helpful?</h3>
                <p>your feedback helps us to improve this site</p>
                <p>
                    <input type="hidden" name="helpid" id="helpid" value="<?php echo $help['id'];?>"/>
                    <button class="btn btn-info" id="btn_helpfeedback_yes" type="button"  data-loading-text="Submiting..">Yes</button> 
                    <button class="btn btn-info" id="btn_helpfeedback_somewhat" type="button"  data-loading-text="Submiting..">Somewhat</button>
                    <button class="btn btn-info" id="btn_helpfeedback_no" type="button"  data-loading-text="Submiting..">No</button>
                <p>
                <p>
                    Help ID: <?php echo $help['id'];?> Last Updated: <?php echo format_date($help['last_updated']);?>
                </p>
            </div>
        </div>
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="<?php echo base_url('bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/bootbox.min.js') ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/js/help/help.index.js'); ?>" type="text/javascript"></script>
        
    </body>
</html>

