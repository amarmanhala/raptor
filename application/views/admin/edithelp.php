<!-- Default box -->
<div class= "box">
    <div class= "box-header with-border">
      <h3 class= "box-title">Edit Help Topic : <?php echo $help['id']?></h3>
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
                            if (rtrim($value['route'],'/') == set_value('route', $help['route'])) {
                                $selected = " selected";
                            }
                       ?>
                        <option value = "<?php echo rtrim($value['route'],'/');?>"<?php echo $selected;?>><?php echo $value['module'];?></option> 
                       <?php 
                       } ?>
                        <option value = 'other' <?php if(set_value('route', $help['route']) == 'other'){ echo 'selected'; } ?>>Other</option>
                    </select>
                <?php echo form_error('route', '<span class= "help-block error" for= "route" generated = "TRUE">', '</span>'); ?>
              </div> 
            </div>
            <div id="otherroute" class= "form-group <?php if (form_error('other')) echo ' has-error';?>" <?php if(set_value('route', $help['route']) != 'other'){ echo 'style="display:none;"'; } ?> >
               <label class= "col-sm-2 control-label">Other Route</label>
              <div class= "col-sm-6"> 
                    <input type = "text" name = "other" class= "form-control" placeholder= "Controller/Function Name" value = "<?php echo set_value('other', $help['other']);?>" /> 
                <?php echo form_error('other', '<span class= "help-block error" for= "other" generated = "TRUE">', '</span>'); ?>
              </div> 
          </div> 
            <div class= "form-group <?php if (form_error('caption')) echo ' has-error';?>">
               <label class= "col-sm-2 control-label">Caption</label>
                <div class= "col-sm-6">  
                    <input type = "text" name = "caption" class= "form-control" placeholder= "Caption" value = "<?php echo set_value('caption', $help['caption']);?>" />
                    <?php echo form_error('caption', '<span class= "help-block error" for= "caption" generated = "TRUE">', '</span>'); ?>
                </div> 
            </div>
            <div class= "form-group <?php if (form_error('content')) echo ' has-error';?>">
               <label class= "col-sm-2 control-label">Content</label>
              <div class= "col-sm-10">  
                  <textarea name="content" id="ckeditor" class="form-control ckeditor"><?php echo set_value('content', $help['content']);?></textarea>
 
                <?php echo form_error('content', '<span class= "help-block error" for= "content" generated = "TRUE">', '</span>'); ?>
              </div> 
             
          </div>
             
            
        <div class="form-group">
           <label class="col-sm-2 control-label">Active</label>
           <div class="col-sm-6">
              <div class="checkbox">
                <label>
                    <input type="checkbox" name="isactive" value="1" <?php if(set_value('isactive', $help['isactive']) == '1'){ echo 'checked'; } ?>>
                </label>
              </div>
           </div>
        </div>
        
            <div class="form-group">
                 <div class="col-sm-12 text-right">
                     
                 </div>
                 <div class="col-sm-12">
                    <div class="box">
                        <div class="box-header  with-border">
                            <h3 class="box-title text-blue">Help Links</h3> 
                            <div class= "pull-right">
                                <button type="button" id="addhelplink" class="btn  btn-primary"  title="Add Help Link"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Caption</th> 
                            <th>Link</th>
                            <th class="text-center" style ="width: 70px">Video</th>
                            <th class="text-center" style ="width: 70px">Active</th>
                            <th class="text-center" style ="width: 70px">Order</th>
                            <th class="text-center" style ="width: 70px">Edit</th>
                            <th class="text-center" style ="width: 70px">Delete</th> 
                    </tr>
                    </thead>
                 
                    <tbody id ="helplinkbody"> 
                        <?php  foreach ($help_links as $k => $v) { ?>
                            <tr> 
                                <td><?php echo $v['caption']; ?></td>
                                <td><a href="<?php echo $v['link']; ?>" target="_blank"><?php echo $v['link']; ?></a></td>
                                <td class="text-center"><input type="checkbox" class="isvideo_l" name="isvideo_l[]" id="isvideo_<?php echo $v['id']; ?>" data-helplinkid="<?php echo $v['id']; ?>" data-helpid="<?php echo $v['helpid']; ?>" value="1" <?php if($v['isvideo'] == '1'){ echo 'checked'; } ?>></td>
                                <td class="text-center"><input type="checkbox" class="isactive_l"  name="isactive_l[]" id="isactive_<?php echo $v['id']; ?>" data-helplinkid="<?php echo $v['id']; ?>" data-helpid="<?php echo $v['helpid']; ?>" value="1" <?php if($v['isactive'] == '1'){ echo 'checked'; } ?>></td>
                                <td class="text-center"><?php echo $v['sortorder']; ?></td>
                                <td class="text-center"><a href="javascript:void(0)" data-caption="<?php echo $v['caption']; ?>" data-link="<?php echo $v['link']; ?>" data-sortorder="<?php echo $v['sortorder']; ?>"  data-helplinkid="<?php echo $v['id']; ?>" data-helpid="<?php echo $v['helpid']; ?>" class="edithelplink"><i class="fa fa-edit"></i></a></td>
                                <td class="text-center"><a href="javascript:void(0)" data-helplinkid="<?php echo $v['id']; ?>" data-helpid="<?php echo $v['helpid']; ?>" class= "btn btn-link btn-xs delete-btn deletehelplink"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></td>
                            </tr>
                        <?php } ?> 
                </tbody> 
                  
                </table>
                        </div>
                    </div>
                </div>
            </div>

          
	
         
    </div><!-- /.box-body -->
        <div class= "box-footer">
         <div class= "form-group">
                  <label class= "col-sm-2 control-label">&nbsp;</label>
                   <div class= "col-sm-5">
                       <input type="hidden" name="helpid" id="helpid" value="<?php echo $help['id']?>"/>
                    <button type = "submit" class= "btn btn-primary">Save</button>
                    <a href= "<?php echo site_url('admin/helps');?>" class= "btn btn-default" />Cancel</a>
                   </div>

              </div>  
        </div><!-- /.box-footer-->
    
    </form>
</div><!-- /.box -->
 <div class="modal fade" id ="helplinkModel" tabindex="-1" role ="dialog" aria-labelledby="helplinkModelLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class= "modal-title text-blue text-center">Add Link</h4>
                </div>
                <form name ="helplinkform" id ="helplinkform" class="form-horizontal" method ="post"  >

                <div class="modal-body">
                    <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                    <div id ="sitegriddiv" style ="display:none;"> 
                        <div class="status"></div>

                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Caption</label>
                            <div class="col-sm-10" >
                                <input type="text" name="caption" id="caption" class="form-control" placeholder="Caption" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Link</label>
                            <div class="col-sm-10" >
                                <div class= "input-group">
                                    <input type="url" name="link" id="link" class="form-control" placeholder="http://www.youtube.com/watch?v=ABCDEFGHIGK" value=""/>
                                    <div class= "input-group-addon">
                                        <i class= "fa fa-link"></i>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                         <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Video</label>
                            <div class="col-sm-10" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="isvideo" id="isvideo"  value="1">
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Active</label>
                            <div class="col-sm-10" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="isactive" id="isactive"  value="1">
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Order</label>
                            <div class="col-sm-2" >
                                <input type="text" name="sortorder" id="sortorder" class="form-control allownumericwithoutdecimal" placeholder="Order" value=""/>
                            </div>
                        </div>

                    </div>
                </div>
                    <div class="modal-footer">
                        <div class="form-group">
                              <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                              <div class="col-sm-9">
                                  <input type ="hidden" name ="helplinkid" id ="helplinkid" value =""/>  
                                  <input type ="hidden" name ="mode" id ="mode" value =""/> 
                                  <input type ="hidden" name ="helpid" id ="helpid" value ="<?php echo $help['id']?>"/> 
                                 <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                                 <button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                               </div>
                        </div> 

                      </div>     

                   </form>
            </div>
        </div>
    </div>

      