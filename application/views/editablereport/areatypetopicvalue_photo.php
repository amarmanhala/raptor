<div class="row top-margin">
    <div class="col-md-2"><h4>Photos</h4></div>
</div>
<div class="row">
    <div class="col-md-2"><span class="pull-right"><label for="">Add Photos</label></span></div>
    <div class="col-md-10"> 
       <?php $this->load->view('editablereport/file_upload');  ?> 
    </div>
</div>

<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>    
        <tr>
            <th></th>
            <th class="col-md-3">Description</th>
            <th class="col-md-3">Note</th>
        </tr>    
    </thead>    
    <tbody>    
     <?php foreach ($photos as $index2=>$photo): ?>
        <tr>
            <td>
                <div class="row">
                    <div class="col-md-12">
                        <img id="<?php echo $photo['target'] ?>" src="<?php echo $photo['url'] ?>" class="img-responsive" alt=""> 
                        <?php //echo $photo['id'] . " "; echo $photo['scope_guid']; ?>
                    </div>
                </div>                    
                <div class="row top-margin">
                    <div class="col-md-8">
                        <?php $this->load->view('editablereport/image_buttons', $photo); ?>
                    </div>                        
                    <div class="col-md-4">
                        <?php $this->load->view('editablereport/image_action_buttons', $photo); ?>
                    </div>
                </div> 
            </td>
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $photo['x_editable']['documentdesc']); ?>
            </td>
            <td>
                <?php //echo $photo['docnote']; ?>
                <?php $this->load->view('editablereport/xeditable_element', $photo['x_editable']['docnote']); ?>
            </td>                
        </tr>  
    <?php endforeach; ?>
    </tbody>    
</table>