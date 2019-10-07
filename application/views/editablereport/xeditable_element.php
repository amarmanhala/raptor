<?php if($class == 'editable_target_appendable'): ?>
<span 
    data-type="text" 
    class="editable_newvalue" 
    data-source="" 
    id="newvalue_<?php echo $fieldnum . $id; ?>"  
    data-inputclass="<?php echo $inputclass; ?>" 
    data-pk="1" 
    data-showbuttons="<?php echo $showbuttons; ?>" 
    data-url="newvalue/<?php echo $id . "/" . $fieldnum  ?>"  
    data-value="" 
    data-title="" 
    data-placeholder="enter new value"></span>
<?php endif; ?>
<span 
    data-type="<?php echo $type; ?>" 
    class="<?php echo $class; ?>" 
    data-source="<?php echo base_url("EditableReport/" . $source); ?>" 
    id="target_<?php echo $fieldnum . $id; ?>"  
    data-newvaluetarget="#newvalue_<?php echo $fieldnum . $id; ?>" 
    data-recordid="<?php echo $fieldnum . $id; ?>"  
    data-inputclass="<?php echo $inputclass; ?>" 
    data-pk="1" 
    data-showbuttons="<?php echo $showbuttons; ?>" 
    data-url="<?php echo base_url("EditableReport/update_xeditable/$tablenum/$id/$fieldnum");  ?>" 
    data-ajaxurl="<?php echo $ajaxurl; ?>" 
    data-value="<?php echo $value ?>" 
    data-title="<?php echo $xetitle; ?>" ><?php echo $display; ?></span>    
&nbsp;
<a 
    href='#' 
    class='editable_link' 
    data-recordid="<?php echo  $fieldnum . $id; ?>" 
    id='link_<?php echo  $fieldnum . $id; ?>'
    ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

