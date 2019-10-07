<div class="box" id = "AssetDocumentCtrl" ng-controller= "AssetDocumentCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">Asset Document</h3>
        <div class="pull-right">
            <button type="button" class="btn btn-primary" name="add_asset_document" id="add_asset_document" title="Add Asset Document"><i class= "fa fa-plus"></i></button> 
        </div>
    </div>
    <div class="box-body">
         
           <div>
            <div ui-grid = "gridAssetDocumentOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "gridautoheight1"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" style = "display:none">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
      <!-- Modal -->
<div class="modal fade" id="assetdocumentmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     <form id="assetdocumentform" name="assetdocumentform" class="form-horizontal" method="post" autocomplete="off" enctype="multipart/form-data" action="<?php echo site_url('asset/savedocument') ?>">
      <div class="modal-header">
        <button id="docclose" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Asset Document</h4>
      </div>
      <div class="modal-body">
		  <div class="form-group">
		    <label for="input" class="col-sm-2 control-label">Type</label>
		    <div class="col-sm-5">
		      <select class="form-control select2" id="assetdoctype" name="assetdoctype">
		      	<option value="">-Select-</option>
		      	<?php
			      	foreach($asset_doctype as $value):
			      		echo '<option value="'.$value['id'].'">'.$value['asset_doctype'].'</option>';
			      	endforeach;
		      	?>
		      </select>	
		      <span class="help-block with-errors"></span>
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="input" class="col-sm-2 control-label">Caption</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="documentdesc" name="documentdesc" placehoder="caption" />	
		      <span class="help-block with-errors"></span>
		    </div>
		  </div>
		   <div class="form-group">
		    <label for="input" class="col-sm-2 control-label">&nbsp;</label>
		    <div class="col-sm-7"><!-- -->
		      <input type="file"  id="docfileupload" name="docfileupload" onchange="readTabDocURL(this);"  />	
		      <span class="help-block with-errors"></span>
		    </div>
		  </div>
                  <div class="progress">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
                  <span class="sr-only">0% Complete (success)</span>
                </div>
        </div>
       
             
         <div id="status"></div>   
		
        
      </div>
      <div class="modal-footer">
           <input type="hidden" name="assetid" id="assetid" value="">
           <input type="hidden" name="assetdoctypename" id="assetdoctypename" value="">
       
        <button id="docsavebtn" type="submit" class="btn btn-primary" data-loading-text="Saving...">Save</button>
         <button id="docclosebtn" type="button" class="btn btn-default" data-dismiss="modal" data-loading-text="Close">Close</button>
      </div>
  
      </form>
    </div>
  </div>
</div>

</div><!-- /.box -->
      
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">Photo</h3>
    </div>
    <div class="box-body">
         <div class="row" id="photo_grid">
    <?php
            foreach ($asset_document as $field): 
                    if(strtolower($field['doctype']) != 'image'):
                        continue;
                    endif;

                    $file_thumb_url = $this->config->item("document_path").'thumb/';

                    $filename = $file_thumb_url.$field['documentid'].'_thumb.'.$field['docformat'];
                    if(strlen($field['documentdesc'])>20) {
                            $field['documentdesc'] = substr($field['documentdesc'], 0, 20).'...';
                    }
                ?>
                <div class="col-xs-2 text-center">
            <div class="thumbnail">
                <div style="width:120px;height:120px; margin: auto;">
                        <img src="<?php echo $filename;?>" alt="<?php echo $field['documentdesc'];?>" />	
                </div>	

                <div><!-- class="caption" -->
                        <p id="cap_<?php echo $field['documentid'];?>"><?php echo $field['documentdesc'];?></p>
                    <p><button type="button" id="btn_<?php echo $field['documentid'];?>" class="btn btn-default btn-sm" onclick="getdocument(<?php echo $field['documentid'];?>);">Edit</button></p>
                </div>
             </div>
        </div>			
      <?php
            endforeach; 
        ?>
        </div>	
    </div><!-- /.box-body -->
    
<!-- Modal -->
<div class="modal fade" id="assetimagedescmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
 	
     <form id="assetimageform" name="assetimageform" class="form-horizontal" method="post" autocomplete="off">
      <div class="modal-header">
        <button id="mdocclose" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Document</h4>
      </div>
      <div class="modal-body">
		  <div class="form-group">
		    <label for="input" class="col-sm-2 control-label">Caption</label>
		    <div class="col-sm-7">
		      <input type="text" class="form-control" id="mdocumentdesc" name="mdocumentdesc" placeholder="caption" />	
		      <span class="help-block with-errors"></span>
		    </div>
		  </div>
		   <div class="form-group">
		    <label for="input" class="col-sm-2 control-label">Notes</label>
		    <div class="col-sm-10">
		      <textarea name="mdocnote" id="mdocnote" class="form-control" placeholder="notes" rows="5"></textarea>	
		    </div>
		  </div>
		
        
      </div>
      <div class="modal-footer">
           <input type="hidden" name="assetid" id="assetid" value="">
             <input type="hidden" name="hdocumentid" id="hdocumentid" value="" />

        <button id="mdocsavebtn" type="submit" class="btn btn-primary" data-loading-text="Saving...">Update</button>
                <button id="mdocclosebtn" type="button" class="btn btn-default" data-dismiss="modal" data-loading-text="Close">Close</button>
      </div>
    
      </form>
    </div>
  </div>
</div>

</div><!-- /.box -->