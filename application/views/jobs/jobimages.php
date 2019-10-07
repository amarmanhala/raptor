<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <button id ="addimage" class="btn btn-primary">Upload Image</button> 
            </div>
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                <?php foreach($documentImages as $value) {
     
                    $imagepath = checkDocumentImage($this->config->item('document_dir'), $this->config->item('document_path'), $value['documentid'], $value['docformat']);
                    $thumb = $imagepath['thumb'];
                    $fullimage = $imagepath['original'];
                    if($thumb == ''){
                        $thumb = $fullimage;
                    }
                    
                ?>
               
                    <li class="item">
                      <div class="product-img">
                           <a href="<?php echo $fullimage;?>" target="_blank"><img class="attachment-img responsive margin"   src="<?php echo $thumb;?>" alt="<?php echo $value['docname'];?>"></a>
                        
                      </div>
                      <div class="product-info">
                          <h4 class="product-title">Id: <?php echo $value['documentid'];?> <?php echo $value['dateadded'];?></h4>
                        <h4 class="product-title"><?php echo $value['docname'];?></h4>
                        
                        <span class="product-description" style="text-align:justify;">
                          <?php echo $value['documentdesc'];?>
                        </span>
                      </div>
                    </li><!-- /.item -->
                 
                     
                
               
                <?php } ?>

                </ul>

            </div>
        </div>
    </div>
</div>