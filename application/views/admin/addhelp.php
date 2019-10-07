<!-- Default box -->
<div class= "box">
    <div class= "box-header with-border">
      <h3 class= "box-title">Add Help Topic</h3>
    </div>
    <form id = "helpform" name="helpform"   method = "post" class= "form-horizontal">
        <div class= "box-body">
            <p>  <?php if ($this->session->flashdata('error'))  {
                    echo '<div class= "alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
                }
                if ($this->session->flashdata('success'))  {
                    echo '<div class= "alert alert-success">'.$this->session->flashdata('success').'</div>';	
                }?>
            </p>
             
            <div class= "form-group <?php if (form_error('route')) echo ' has-error';?>">
               <label class= "col-sm-2 control-label">Route</label>
              <div class= "col-sm-6"> 
                    <select name = "route" id = "route" class= "form-control">
                        <option value = ''>-Select-</option>
                        <?php foreach ($routes as $key => $value) { 
                            $selected = '';
                            if(rtrim($value['route'],'/') == ''){
                                continue;
                            }
                            if (rtrim($value['route'],'/') == set_value('route')) {
                                $selected = " selected";
                            }
                       ?>
                        <option value = "<?php echo rtrim($value['route'],'/');?>"<?php echo $selected;?>><?php echo $value['module'];?></option> 
                       <?php 
                       } ?>
                        <option value = 'other' <?php if(set_value('route') == 'other'){ echo 'selected'; } ?>>Other</option>
                    </select>
                <?php echo form_error('route', '<span class= "help-block error" for= "route" generated = "TRUE">', '</span>'); ?>
              </div> 
            </div>
            <div id="otherroute" class= "form-group <?php if (form_error('other')) echo ' has-error';?>" <?php if(set_value('route') != 'other'){ echo 'style="display:none;"'; } ?> >
               <label class= "col-sm-2 control-label">Other Route</label>
              <div class= "col-sm-6"> 
                    <input type = "text" name = "other" class= "form-control" placeholder= "Controller/Function Name" value = "<?php echo set_value('other');?>" /> 
                <?php echo form_error('other', '<span class= "help-block error" for= "other" generated = "TRUE">', '</span>'); ?>
              </div> 
          </div> 
            <div class= "form-group <?php if (form_error('caption')) echo ' has-error';?>">
               <label class= "col-sm-2 control-label">Caption</label>
                <div class= "col-sm-6">  
                    <input type = "text" name = "caption" class= "form-control" placeholder= "Caption" value = "<?php echo set_value('caption');?>" />
                    <?php echo form_error('caption', '<span class= "help-block error" for= "caption" generated = "TRUE">', '</span>'); ?>
                </div> 
            </div>
            <div class= "form-group <?php if (form_error('content')) echo ' has-error';?>">
               <label class= "col-sm-2 control-label">Content</label>
              <div class= "col-sm-10">  
                  <textarea name="content" id="ckeditor" class="form-control ckeditor"><?php echo set_value('content');?></textarea>
 
                <?php echo form_error('content', '<span class= "help-block error" for= "content" generated = "TRUE">', '</span>'); ?>
              </div> 
             
          </div>
             
            
        <div class="form-group">
           <label class="col-sm-2 control-label">Active</label>
           <div class="col-sm-6">
              <div class="checkbox">
                <label>
                    <input type="checkbox" name="isactive" value="1" <?php if(set_value('isactive') == '1'){ echo 'checked'; } ?>>
                </label>
              </div>
           </div>
        </div>
  

          
	
         
    </div><!-- /.box-body -->
        <div class= "box-footer">
         <div class= "form-group">
                  <label class= "col-sm-2 control-label">&nbsp;</label>
                   <div class= "col-sm-5">
                    <button type = "submit" class= "btn btn-primary">Save</button>
                    <a href= "<?php echo site_url('admin/helps');?>" class= "btn btn-default" />Cancel</a>
                   </div>

              </div>  
        </div><!-- /.box-footer-->
    
    </form>
</div><!-- /.box -->

      