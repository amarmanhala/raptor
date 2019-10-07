<div class="modal fade" id ="uploaddocModal" tabindex="-1" role ="dialog" aria-labelledby="uploaddocModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
    <form name ="upload_form" id ="upload_form" role ="form" method ="post" action="<?php echo site_url();?>documents/uploadjobdocument" class="form-horizontal"  enctype ="multipart/form-data">
      <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploaddocModalTitle">Upload Document</h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label for="doctype" class="col-sm-2" class="control-label">Type</label>
       <div class="col-sm-6">
                <select name ="doctype" id ="doctype" class="form-control">
                </select>
                <select name ="tempdoctype" id ="tempdoctype" style="display:none;">
                    <option value ="">Select Type</option>
                    <?php foreach ($jobDocFolder as $key => $value) {
                        echo '<option value ="'.$value['caption'].'" data-folder="'.$value['foldername'].'">'.$value['caption'].'</option>';
                    } ?>
                </select>
                <select name ="tempdocimage" id ="tempdocimage" style="display:none;">
                    <option value ="">Select Type</option>
                    <?php foreach ($jobImagesFolder as $key => $value) {
                        echo '<option value ="'.$value['caption'].'" data-folder="'.$value['foldername'].'">'.$value['caption'].'</option>';
                    } ?>
                </select>
       </div>
           
            </div>
            
            <div class="form-group">
                <label for="description" class="col-sm-2" class="control-label">Description</label>
                <div class="col-sm-10">
                <input type ="text" id ="description" name ="description" placeholder="Description" class="form-control">
                </div>
            </div>
          <div class="form-group">
                <label for="fileup" class="col-sm-2" class="control-label">File</label>
                <div class="col-sm-10 ">
                    <input  type ="file" name ="fileup"  class="file" id ="fileup" >

                    <div class="input-group">

                        <input type="text" class="form-control" disabled placeholder="Upload Image">
                        <span class="input-group-btn">
                          <button class="browse btn btn-primary" type="button">Browse</button>
                        </span>
                    </div>
                </div>
            </div>
             
          <div class="progress">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role ="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style ="width: 1%">
                  <span class="sr-only">0% Complete (success)</span>
                </div>
        </div>
         <div id ="status"></div>         
    
          <div class="form-group">
              <div class="col-sm-10 col-sm-offset-2">
                    <button type ="submit" id ="modalsave" class="btn btn-primary">Save</button>&nbsp;
                    <button type ="button" id ="cancel" class="btn btn-Default">Cancel</button>
              </div>
                
            </div>
      </div>
        <input  type ="hidden" name ="uploadtype" id ="uploadtype"  value ="" />
        <input  type ="hidden" name ="filedata" id ="filedata"  value ="" />
        <input  type ="hidden" name ="jobid" id ="jobid" value ="<?php echo $job['jobid'] ?>" />
        <input  type ="hidden" name ="foldername" id ="foldername" value ="" /> 
     </form>
    </div>
  </div>
</div>