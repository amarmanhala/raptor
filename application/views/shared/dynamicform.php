<!-- Default box -->
<div class="box">
 
    <div class="box-body">
        
       <p>  <?php 
                if($this->session->flashdata('error')) 
                {
                        echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '.$this->session->flashdata('error').'</div>';	
               }
                   if($this->session->flashdata('success')) 
                {
                        echo '<div class="alert alert-success  alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '.$this->session->flashdata('success').'</div>';	
               }
        ?>	</p>
        <div class="col-md-12">
            <form id="validation-form" name="dynamic_form" action="" method="post" class="form-horizontal" >
                  <?php 
                  dynamicform_elements($fields);
                  
              if(isset($fields)){
                foreach($fields as $fieldarray){ 
                    if(count($fieldarray)==1){
                        $field=$fieldarray[0];
                    ?>
                     <?php if($field['type']=="hidden"){ ?> 
                        <input type="hidden"     id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>"   />
                     <?php }else { ?>
                    <div class="form-group ">
                        <label class="control-label col-sm-2" for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?>&nbsp;</label>
                        <div class="<?php if(isset($field['divclass']) && $field['divclass']!=""){ echo $field['divclass']; } else { echo 'col-sm-10';  }?> <?php if (form_error($field['name'])) {echo ' has-error';}?>">
                            <?php if($field['type']=="checkbox"){ ?>
                            <div class="checkbox">
                                <label><input class="<?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" type="checkbox" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>"  <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?>><?php echo $field['label2']; ?></label>
                            </div>
                            <?php }else if($field['type']=="select"){ ?>
                                <select id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>"    <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> >
                                    <option data-hidden="true" value="">Choose an option</option>
                                    <?php  foreach ($field['options'] as $keyvalue => $displayvalue) { ?>
                                    <option value="<?php echo $keyvalue ?>"  <?php if($field['value']==$keyvalue) {echo 'selected="selected"';} ?>  ><?php echo $displayvalue ?></option>
                                    <?php  } ?>
                                </select>
                             <?php }else if($field['type']=="date"){ ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> readonly="readonly"   />
                                </div>
                             <?php }else if($field['type']=="time"){ ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control timepicker <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> readonly="readonly"   />
                                </div>
                             <?php }else if($field['type']=="phone" ){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>
                            <?php }else if($field['type']=="mobile" ){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>
                            <?php }else if($field['type']=="fax" ){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fax"></i>
                                    </div>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>
                             <?php }else if($field['type']=="email"){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                <input type="email" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>  
                            <?php }else if($field['type']=="textarea"){ ?>
                                <textarea class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> ><?php echo $field['value'];?></textarea>	
                           <?php }else if($field['type']=="textboxwithsearchbtn"){ ?>
                                <div class="input-group">
                                 <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                <div class="input-group-addon ">
                                    <a href="javascript:void(0)"   id="btn_<?php echo $field['name']; ?>"><i class="fa fa-eye"></i></a>
                                    </div>
                                
                              </div><!-- /input-group -->
                               
                            <?php }else{  ?>
                               <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                            <?php } ?>
                                
                                 
                             <?php echo form_error($field['name'], '<label class="help-block with-errors error" for="name" generated="true">', '</label>'); ?>
			</div>
                    </div>
                     <?php } ?>
              <?php } else { ?>
                         <div class="form-group ">
               <?php   foreach($fieldarray as $field){ ?>
                          <?php if($field['type']=="hidden"){ ?> 
                        <input type="hidden"     id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>"   />
                     <?php }else { ?>
                   
                        <label class="control-label <?php if(isset($field['labelclass']) && $field['labelclass']!=""){ echo $field['labelclass']; } else { echo 'col-sm-2';  }?>" for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?>&nbsp;</label>
                        <div class="<?php if(isset($field['divclass']) && $field['divclass']!=""){ echo $field['divclass']; } else { echo 'col-sm-10';  }?> <?php if (form_error($field['name'])) {echo ' has-error';}?>">
                            <?php if($field['type']=="checkbox"){ ?>
                            <div class="checkbox">
                                <label><input class="<?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" type="checkbox" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>"  <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?>><?php echo $field['label2']; ?></label>
                            </div>
                            <?php }else if($field['type']=="select"){ ?>
                                <select id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>"    <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> >
                                    <option data-hidden="true" value="">Choose an option</option>
                                    <?php  foreach ($field['options'] as $keyvalue => $displayvalue) { ?>
                                    <option value="<?php echo $keyvalue ?>"  <?php if($field['value']==$keyvalue) {echo 'selected="selected"';} ?>  ><?php echo $displayvalue ?></option>
                                    <?php  } ?>
                                </select>
                             <?php }else if($field['type']=="date"){ ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control datepicker <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> readonly="readonly"   />
                                </div>
                             <?php }else if($field['type']=="time"){ ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control timepicker <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> readonly="readonly"   />
                                </div>
                             <?php }else if($field['type']=="phone" ){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>
                            <?php }else if($field['type']=="mobile" ){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile"></i>
                                    </div>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>
                            <?php }else if($field['type']=="fax" ){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fax"></i>
                                    </div>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>
                             <?php }else if($field['type']=="email"){  ?>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                <input type="email" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                                 </div>  
                            <?php }else if($field['type']=="textarea"){ ?>
                                <textarea class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> ><?php echo $field['value'];?></textarea>	
                           
                            <?php }else{  ?>
                                <input type="text" class="form-control <?php if(isset($field['fieldclass']) && $field['fieldclass']!=""){ echo $field['fieldclass']; } ?>" placeholder="<?php echo $field['placeholder']; ?>" id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value'];?>" <?php if(isset($field['other']) && $field['other']!=""){ echo $field['other']; } ?> />
                            <?php } ?>
                             <?php echo form_error($field['name'], '<label class="help-block with-errors error" for="name" generated="true">', '</label>'); ?>
			</div>
                   
                     <?php } 
                        } ?>
                         </div>
                   <?php   } ?>
             <?php } ?>
            <?php } ?>
                
          <div class="form-group">
              <label for="input" class="col-sm-2 control-label">&nbsp;</label>
               <div class="col-sm-10">
             <button type="submit" class="btn btn-primary  btn-flat">Save</button>
               </div>
          </div>  
                
	</form>
        </div>
    </div><!-- /.box-body -->
    
</div><!-- /.box -->

      