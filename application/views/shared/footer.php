<?php   if($banner_array['active'] == 1 && $banner_array['footer']!="") {?>
        <div class="content-footer">
            <img height="100" src="<?php echo $banner_array['footer'];?>" />
        </div>
<?php } ?>

Copyright &copy; <?php echo date('Y').' '. RAPTOR_APP_COPYRIGHT; ?> 
 <div class="pull-right">
    <b><?php echo trim(RAPTOR_APP_TITLE .' '. RAPTOR_APP_SUBTITLE); ?></b><?php echo RAPTOR_APP_VERSION; ?>
</div>
  

