<div class="modal fade" id ="quoteDeclineModal" tabindex="-1" role ="dialog" aria-labelledby="quoteDeclineModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
        <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Decline Quote Request</h4>
      </div>
        <form name ="declinequoteform" id ="declinequoteform" class="form-horizontal" method ="post"  >
      
        <div class="modal-body">
         
            <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id ="sitegriddiv" style ="display:none;"> 
                 <div class="status"></div>
       
       
                <div class="form-group">
                      <label for="name" class="col-sm-3 control-label">Reason:</label>
                      <div class="col-sm-6">
                          <select class="form-control" id ="reason" name ="reason">
                            <option value ="">-Select-</option>
                            <?php foreach($variationDeclineReasons as $key=>$value) {  ?>
                            <option value="<?php echo $value['reason'];?>"  ><?php echo $value['reason'];?></option> 
                        <?php } ?>
                            
                          </select>
                       </div>
                </div> 
                 <div class="form-group">
                      <label for="name" class="col-sm-3 control-label">Notes:</label>
                      <div class="col-sm-9">
                          <textarea class="form-control" id ="notes" name ="notes"></textarea>
                       </div>
                </div>  
            </div>
      </div>
            <div class="modal-footer">
                <div class="form-group">
                      <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                      <div class="col-sm-9">
                          <input type ="hidden" name ="jobid" id ="jobid" value =""/>
                          <input type ="hidden" name ="openfrom" id ="openfrom" value =""/> 
                         <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Decline</button>
                    &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default">Cancel</button>
                       </div>
                </div> 



              </div>     
           
	   </form>
    </div>
  </div>
</div>
