<?php if(isset($page_title)){?>
<h1>
	<?php echo $page_title; ?>
    <?php if(isset($page_sub_title)&& $page_sub_title!=""){?>
    <small><?php echo $page_sub_title; ?></small>
    <?php } ?>
    <?php if(isset($help) && count($help) > 0) { ?>
        <a href="<?php echo site_url('help/index/'.$help['id']); ?>" target="_blank"><i class="fa fa-question-circle text-blue"></i></a>
    <?php } ?>
</h1>
<?php }
$breadcrumb_homelink = site_url('dashboard');
if($loggeduser->israptoradmin == 1) {
    $breadcrumb_homelink = site_url('admin/dashboard');
}
?>

  <ol class="breadcrumb">
  	  <li><a href="<?php echo $breadcrumb_homelink;?>"><i class="fa fa-home"></i> Home</a></li>
  	  <?php if(count($template['breadcrumbs'])>0){ 
  	  		foreach($template['breadcrumbs'] as $key=>$value){ 
		  		if($value['uri']==""){
		  			if($key==count($template['breadcrumbs'])-1){
						echo '<li class="active">'.$value["name"].'</li>';
					}
					else
					{
						echo '<li>'.$value["name"].'</li>';
					}
				}
				else{
					echo '<li><a href="'.$value["uri"].'">'.$value["name"].'</a></li>';
				}
	  		}  
	   }else { 
    		echo '<li class="active">'.$page_title.'</li>';
       }  ?>
  </ol>
