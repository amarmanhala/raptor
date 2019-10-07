<?php
$menu = array();
foreach($navigation as $key=>$value) {
    if($value['parentid'] == 0) {
        $value['child'] = array();
        $menu[$value['id']] = $value;
    }
}
foreach($navigation as $key=>$value) {
    if($value['parentid'] != 0) {
        if(isset($menu[$value['parentid']])){
            $menu[$value['parentid']]['child'][] = $value;
        }
        else{
            $value['child'] = array();
            $menu[$value['id']] = $value;
        }
    }
}
?>
<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <div class="sidebar-logo">
    <img src="<?php echo get_logo_images($this->config->item('branding_dir'), $this->config->item('branding_path'), $loggeduser->customerid); ?>"   alt="DCFM Client Portal"   />
    </div>
    <!-- Sidebar user panel -->
   
 
  <!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu">
    <?php  
    
    
      foreach ($menu as $key => $value) {
            $url1= trim($value['url1']);
            $url2= trim($value['url2']);
            $url3= trim($value['url3']);
            $url = $url1;
            if($url2!=''){
                $url = $url . '/'.$url2;
            }
            if($url3!=''){
                $url = $url . '/'.$url3;
            }
            if (count($value['child'])== 0) {
                 $active='';
                 $barcolor='';
                 $scriptTextColor = '';
                 $scriptBackground = '';
                 $scriptBorderLeft = '';
                 if($url2!=''){
                     if ($this->uri->segment(1) == $url1 && $this->uri->segment(2) == $url2) {
                         $active = 'class="active"';
                        if($value['bar_color'] != '' || $value['bar_color'] != NULL) {
                            $barcolor = 'style="';
                            $barcolor .= 'border-left-color: '.$value['bar_color'].' !important;background: '.$value['bar_color'].' none repeat scroll 0 0;';
                            if($value['text_color'] != '' || $value['text_color'] != NULL) {
                                $barcolor .= 'color: '.$value['text_color'];
                            }
                            $barcolor .= '"';
                        }
                     }
                 }
                 else{
                     if ($this->uri->segment(1) == $url1 ) {
                         $active = 'class="active"';
                         if($value['bar_color'] != '' || $value['bar_color'] != NULL) {
                            $barcolor = 'style="';
                            $barcolor .= 'border-left-color: '.$value['bar_color'].' !important;background: '.$value['bar_color'].' none repeat scroll 0 0;';
                            if($value['text_color'] != '' || $value['text_color'] != NULL) {
                                $barcolor .= 'color: '.$value['text_color'];
                            }
                            $barcolor .= '"';
                        } 
                     }
                }
                
                if($value['bar_color'] != '' || $value['bar_color'] != NULL) {
                    $scriptBorderLeft = $value['bar_color'];
                    $scriptBackground = $value['bar_color'].' none repeat scroll 0 0';
                    $scriptTextColor = $value['text_color'];
                } 
                if($value['text_color'] != '' || $value['text_color'] != NULL) {
                    $scriptTextColor = $value['text_color'];
                } 
                
                
                
                 ?>            
      <li <?php echo $active;?>>
                        <a onmouseover="navigationMouseOver(this, '<?php echo $scriptTextColor;?>', '<?php echo $scriptBackground;?>', '<?php echo $scriptBorderLeft;?>');" <?php if($active == '') { echo 'onmouseout="navigationMouseOut(this);"';}?> <?php echo $barcolor;?> href="<?php echo site_url($url); ?>" title ="<?php echo $value['name'];?>" target ="<?php echo $value['target'];?>">
                            <?php if($value['menu_icontype'] == 'ICON' || $value['menu_image'] == '' || $value['menu_image'] == NULL ){?>
                                <i class="<?php echo $value['menu_icon'];?>"></i>
                            <?php } else {?>
                                <i class="menu-i-image ion"><img src="<?php echo base_url();?>assets/img/<?php echo $value['menu_image'];?>" class="menuicon-image"></i>
                            <?php } ?>
                            <span><?php echo $value['name'];?></span>
                            <?php if($value['showcounter']==1){ ?>
                            <small class="label pull-right <?php echo $value['counter_keyword'];?>" style="background-color:<?php echo $value['counter_bgcolor'];?>;color:<?php echo $value['counter_color'];?> "><?php echo isset($menucounter[$value['counter_keyword']]) ? $menucounter[$value['counter_keyword']] : '0';?></small>
                            <?php } ?>
                        </a>
                   </li>      
              <?php 
             } else {
                $active='';
                $barcolor='';
                $scriptTextColor = '';
                $scriptBackground = '';
                foreach ($value['child'] as $key2=> $value2) {  
                    $url12= $value2['url1'];
                    $url22= $value2['url2'];
                    if($url22!=''){
                        if ($this->uri->segment(1) == $url12 && $this->uri->segment(2) == $url22) {
                            $active = ' active';
                            if($value['bar_color'] != '' || $value['bar_color'] != NULL) {
                                $barcolor = 'style="';
                                $barcolor .= 'border-left-color: '.$value['bar_color'].' !important;background: '.$value['bar_color'].' none repeat scroll 0 0;';
                                if($value['text_color'] != '' || $value['text_color'] != NULL) {
                                    $barcolor .= 'color: '.$value['text_color'];
                                }
                                $barcolor .= '"';
                            } 
                        }
                    }
                    else{
                        if ($this->uri->segment(1) == $url12 ) {
                            $active = ' active';
                            if($value['bar_color'] != '' || $value['bar_color'] != NULL) {
                                $barcolor = 'style="';
                                $barcolor .= 'border-left-color: '.$value['bar_color'].' !important;background: '.$value['bar_color'].' none repeat scroll 0 0;';
                                if($value['text_color'] != '' || $value['text_color'] != NULL) {
                                    $barcolor .= 'color: '.$value['text_color'];
                                }
                                $barcolor .= '"';
                            } 
                        }
                    }
                    if($value['bar_color'] != '' || $value['bar_color'] != NULL) {
                        $scriptBorderLeft = $value['bar_color'];
                        $scriptBackground = $value['bar_color'].' none repeat scroll 0 0';
                        $scriptTextColor = $value['text_color'];
                    } 
                    if($value['text_color'] != '' || $value['text_color'] != NULL) {
                        $scriptTextColor = $value['text_color'];
                    } 
                     
                }
              
                 
                ?>            
                <li class="treeview <?php  echo $active;?>">
                    <a href="#" onmouseover="navigationMouseOver(this, '<?php echo $scriptTextColor;?>', '<?php echo $scriptBackground;?>', '<?php echo $scriptBorderLeft;?>');" <?php if($active == '') { echo 'onmouseout="navigationMouseOut(this);"';}?> <?php echo $barcolor;?> title ="<?php echo $value['name'];?>">
                        <?php if($value['menu_icontype'] == 'ICON' || $value['menu_image'] == '' || $value['menu_image'] == NULL ){?>
                            <i class="<?php echo $value['menu_icon'];?>"></i>
                        <?php } else {?>
                            <i class="menu-i-image ion"><img src="<?php echo base_url();?>assets/img/<?php echo $value['menu_image'];?>" class="menuicon-image"></i>
                        <?php } ?>
                         
                        <span><?php echo $value['name'];?></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                      <?php foreach ($value['child'] as $key2=> $value2) {  
                            $active='';
                            $barcolor='';
                            $scriptTextColor = '';
                            $scriptBackground = '';
                            $url12= $value2['url1'];
                            $url22= $value2['url2'];
                            $url32= $value2['url3'];
                            $url = $url12;
                            if($url22!=''){
                                $url = $url . '/'.$url22;
                            }
                            if($url32!=''){
                                $url = $url . '/'.$url32;
                            }
                            
                            if($url22!=''){
                                if ($this->uri->segment(1) == $url12 && $this->uri->segment(2) == $url22) {
                                    $active = ' class="active"';
                                    if($value2['bar_color'] != '' || $value2['bar_color'] != NULL) {
                                        $barcolor = 'style="';
                                        $barcolor .= 'border-left-color: '.$value2['bar_color'].' !important;background: '.$value2['bar_color'].' none repeat scroll 0 0;';
                                        $scriptBorderLeft = $value2['bar_color'].' !important';
                                        $scriptBackground = $value2['bar_color'].' none repeat scroll 0 0;';
                                        if($value2['text_color'] != '' || $value2['text_color'] != NULL) {
                                            $barcolor .= 'color: '.$value2['text_color'];
                                            $scriptTextColor = $value2['text_color'];
                                        }
                                        $barcolor .= '"';
                                    } 
                                }
                            }
                            else{
                                if ($this->uri->segment(1) == $url12 ) {
                                    $active = ' class="active"';
                                    if($value2['bar_color'] != '' || $value2['bar_color'] != NULL) {
                                        $barcolor = 'style="';
                                        $barcolor .= 'border-left-color: '.$value2['bar_color'].' !important;background: '.$value2['bar_color'].' none repeat scroll 0 0;';
                                        $scriptBorderLeft = $value2['bar_color'].' !important';
                                        $scriptBackground = $value2['bar_color'].' none repeat scroll 0 0;';
                                        if($value2['text_color'] != '' || $value2['text_color'] != NULL) {
                                            $barcolor .= 'color: '.$value2['text_color'];
                                            $scriptTextColor = $value2['text_color'];
                                        }
                                        $barcolor .= '"';
                                    } 
                                }
                            }
                            if($value2['bar_color'] != '' || $value2['bar_color'] != NULL) {
                                $scriptBorderLeft = $value2['bar_color'];
                                $scriptBackground = $value2['bar_color'].' none repeat scroll 0 0';
                                $scriptTextColor = $value2['text_color'];
                            } 
                            if($value2['text_color'] != '' || $value2['text_color'] != NULL) {
                                $scriptTextColor = $value2['text_color'];
                            } 
                          ?>   
                        <li <?php  echo $active;?>>
                            <a href="<?php echo site_url($url);?>" onmouseover="navigationMouseOver(this, '<?php echo $scriptTextColor;?>', '<?php echo $scriptBackground;?>', '<?php echo $scriptBorderLeft;?>');" <?php if($active == '') { echo 'onmouseout="navigationMouseOut(this);"';}?> <?php echo $barcolor;?> title ="<?php echo $value2['name'];?>" target ="<?php echo $value2['target'];?>">
                                
                                <?php if($value2['menu_icontype'] == 'ICON' || $value2['menu_image'] == '' || $value2['menu_image'] == NULL ){?>
                                <i class="<?php echo $value2['menu_icon'];?>"></i>
                            <?php } else {?>
                                <i class="menu-i-image ion"><img src="<?php echo base_url();?>assets/img/<?php echo $value2['menu_image'];?>" class="menuicon-image"></i>
                            <?php } ?>
                                <span><?php echo $value2['name'];?></span>
                                <?php if($value2['showcounter']==1){ ?>
                                <small class="label pull-right <?php echo $value2['counter_keyword'];?>" style="background-color:<?php echo $value2['counter_bgcolor'];?>;color:<?php echo $value2['counter_color'];?> "><?php echo isset($menucounter[$value2['counter_keyword']]) ? $menucounter[$value2['counter_keyword']] : '0';?></small>
                                <?php } ?>
                            </a>
                        </li>
                      <?php } ?>
                    </ul>
                </li>   
            <?php }
         }
      ?>
 
  </ul>
</section>
