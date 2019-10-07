 <div class="modal fade" id ="addressattributeModal" tabindex="-1" role ="dialog" aria-labelledby="addressattributeModalLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Address Attribute</h4>
                </div>
                <form name ="addressattributeform" id ="addressattributeform" class="form-horizontal" method ="post"  >
                    <div class="modal-body">
                        <center id ="loading-img" >
                            <img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" />
                        </center>
                        <div id ="sitegriddiv" style ="display:none;"> 
                            <div class="status"></div>

                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Attribute</label>
                                <div class="col-sm-9">
                                    <input class="form-control" id="newattribute" name="newattribute" placeholder="Attribute">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Caption</label>
                                <div class="col-sm-6">
                                    <input class="form-control" id="caption" name="caption" placeholder="Caption">
                                </div>
                            </div>  
                            <div class="form-group">
                               <label for="name" class="col-sm-3 control-label">Type</label>
                               <div class="col-sm-6">
                                   <select name = "attributetypeid" id = "attributetypeid" class= "form-control">
                                       <option value="">Select Type</option>
                                       <?php foreach($attribute_types as $val) { ?>
                                            <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                                        <?php } ?>
                                  </select>
                               </div>
                            </div>
                            <div class="form-group">
                               <label for="name" class="col-sm-3 control-label">Highlighted</label>
                               <div class="col-sm-6">
                                  <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="highlighted" id="highlighted" value="1">
                                    </label>
                                  </div>
                               </div>
                            </div>

                            <div class="form-group">
                               <label for="name" class="col-sm-3 control-label">Active</label>
                               <div class="col-sm-6">
                                  <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="status" id="status"  value="1">
                                    </label>
                                  </div>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                              <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                              <div class="col-sm-9">
                                  <input type ="hidden" name ="addressattributeid" id ="addressattributeid" value =""/> 
                                  <input type ="hidden" name ="mode" id ="mode" value =""/> 
                                  <button type ="submit" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                                &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                               </div>
                        </div> 

                      </div>     
                </form>
            </div>
        </div>
    </div>
  

