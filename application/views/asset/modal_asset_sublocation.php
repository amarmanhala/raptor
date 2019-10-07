<div class="modal fade" id ="SubLocationModal" tabindex="-1" role ="dialog" aria-labelledby="SubLocationModalLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Sub Location</h4>
            </div>
            <form name ="sublocationform" id ="sublocationform" class="form-horizontal" method ="post"  >
                <div class="modal-body">
                    <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                    <div id ="sublocgriddiv" style ="display:none;"> 
                        <div class="status"></div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-9">
                                <input type ="text" class= "form-control"  name ="address" id ="address" value ="" placeholder="Site Address" readonly=""/> 
                                <input type ="hidden"  name ="labelid" id ="labelid" value =""/>
                                <input type ="hidden"  name ="lat" id ="lat" value =""/>
                                <input type ="hidden"  name ="long" id ="long" value =""/>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Location</label>
                            <div class="col-sm-9">
                                <input type ="text" class= "form-control"  name ="location" id ="location" value ="" placeholder="Location" readonly=""/> 
                                <input type ="hidden"  name ="location_id" id ="location_id" value =""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Sub Location</label>
                            <div class="col-sm-9">
                                <input type ="text" class= "form-control"  name ="sublocation" id ="sublocation" value ="" placeholder="Sub Location"/> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Notes</label>
                            <div class="col-sm-9">
                                <textarea name="notes" id="notes" class="form-control"></textarea>
                            </div>
                        </div>
                         
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">Active</label>
                            <div class="col-sm-9">
                                <div class="checkbox">
                                    <label><input type="checkbox" id="is_active" name="is_active" value="1"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                        <div class="col-sm-9"> 
                            <input type ="hidden" name ="asset_sublocation_id" id ="asset_sublocation_id" value =""/> 
                            <input type ="hidden" name ="mode" id ="mode" value =""/> 
                            <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                            &nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                        </div>
                    </div> 
                </div>     
            </form>
        </div>
    </div>
</div>
