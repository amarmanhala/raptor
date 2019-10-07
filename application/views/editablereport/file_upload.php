<div class="row">
    <div class="col-md-3">    
        <!-- The fileinput-button span is used to style the file input field as button -->
        <span class="btn btn-default btn-sm fileinput-button">
            <i class="glyphicon glyphicon-plus"></i>
            <span>Photo</span>
            <!-- The file input field used as target for the file upload widget -->
            <input data-guid="<?php echo $guid; ?>" class="fileupload" id="fileupload" type="file" name="files[]" multiple>
        </span>
    </div>
    
    <div class="col-md-4">
        <!-- upload progress bar -->
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-info"></div>
        </div>
    </div>
    
    <div class="col-md-5"> 
        <div id="uploadfiles_alert" class="alert alert-small alert-danger" style="display: none;" ></div>
    </div>
    
</div>

            