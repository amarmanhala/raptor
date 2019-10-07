<?php if(isset($browserannouncement) && count($browserannouncement)>0) {?>
<div class="modal fade" id ="browserannouncementModal" tabindex="-1" role ="dialog" aria-labelledby="browserannouncementModalLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header" style ="border-bottom-color: #d73925;border-width: 2px;background-color: #ccc;">
                <h3 class="modal-title text-center">ANNOUNCEMENT</h3>
            </div>
            <div class="modal-body">
                <h3><?php echo $browserannouncement['caption'];?></h3>
                <p><?php echo $browserannouncement['content']; ?></p>
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    <div class="checkbox ">
                        <label>
                            <input type="checkbox" name="dont_show_again" id="dont_show_again"> I've got it. Don't show again.
                        </label>
                    </div>
                </div>
                <input  type ="hidden" id ="messageid" name ="messageid" value ="<?php echo $browserannouncement['id'];?>" />
                <button type ="button" class="btn btn-primary" class="close" data-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div> 
<?php } ?>
  