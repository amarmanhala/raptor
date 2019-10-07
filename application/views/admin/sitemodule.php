<!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Site Status</h3>
       
    </div>
    <div class="box-body">
        <p>  <?php  if($this->session->flashdata('error')) {
                        echo '<div class="alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
                    }
                    if($this->session->flashdata('success'))  {
                        echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
                    }
                    
		?>	</p>
        <div class="col-md-12">
        <form action="" method="post" class="form-horizontal">
              
             
            <div class="form-group">
                <label for="old" class="col-sm-3 control-label">Site Active</label>
                <div class="col-sm-8">
                    <div  class="toggles-chk">
                        <input type="checkbox" name="sitestatus" id="sitestatus" class="ios-toggle" value="1" <?php if(count($site_module)>0 &&  $site_module['sitestatus'] == 1){ echo 'checked'; } ?>/>
                        <label for="sitestatus" class="checkbox-label" data-off="OFFLINE" data-on="LIVE"></label>
                    </div>
                </div> 
            </div>
            <div class="form-group">
                <label for="new" class="col-sm-3 control-label">Login Page Message</label>
                <div class="col-sm-8">
                    <div  class="toggles-chk">
                        <input type="checkbox" name="sitemessagestatus" id="sitemessagestatus" class="ios-toggle" value="1" <?php if(count($site_module)>0 &&  $site_module['sitemessagestatus'] == 1){ echo 'checked'; } ?>/>
                        <label for="sitemessagestatus" class="checkbox-label" data-off="Unpublished" data-on="Published"></label>
                    </div>
                     
                </div>
            </div>
            <div class="form-group">
                <label for="new" class="col-sm-3 control-label">&nbsp;</label>
                <div class="col-sm-8"> 
                    <textarea name="sitemessage" id="sitemessage" class="form-control" rows="6"><?php if(count($site_module)>0){ echo $site_module['sitemessage']; } ?></textarea>
                    
                </div>
            </div>
            <div class="form-group">
                <label for="new" class="col-sm-3 control-label">&nbsp;</label>
                <div class="col-sm-8"> 
                    <input type="text" name="sitemessagedate" class="form-control" placeholder="" readonly="readonly" value="<?php if(count($site_module)>0){ echo $site_module['sitemessagedate'] ==1 ? 'Published at ' : 'Unpublished at ';  echo format_datetime($site_module['sitemessagedate']); } ?>"/>
                </div>
            </div>
          <div class="form-group">
              <label class="col-sm-3 control-label">&nbsp;</label>
               <div class="col-sm-8">
                   <input type="hidden" name="sitemodule"   value="update"/>
                    <button type="submit" class="btn btn-primary btn-flat">Save</button>
               </div>
          </div>  
	</form>
        </div>
    </div><!-- /.box-body -->
    <!--<div class="box-footer">
      Footer
    </div>--><!-- /.box-footer-->
  </div><!-- /.box -->

      