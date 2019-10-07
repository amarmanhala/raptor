<!-- Default box -->
<div class= "box" ng-app="app" id = "AnnouncementsCtrl"  ng-controller= "AnnouncementsCtrl">
    <div class= "box-header with-border">
        <h3 class= "box-title text-blue">Announcements</h3>
    </div>
    <div class= "box-header  with-border"> 
        <div class="row">
            <div class= "col-sm-9 col-md-6" style="padding-right: 0px;">
                <label class="radio-inline">
                    <input type="radio" name="status" value="active" ng-model="filterOptions.status" ng-change = "refreshFilter()" >Active Only
                </label>
                <label class="radio-inline">
                    <input type="radio" name="status" value="all" ng-model="filterOptions.status" ng-change = "refreshFilter()" >All
                </label>
            </div>
            <div  class="col-sm-12 col-md-6 text-right">
                <button type="button" class="btn  btn-primary" ng-click="addAnnouncement();" title="Add"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
            </div>
        </div> 
    </div>
    <div class= "box-body">
        <div id="contactGrid">
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
    <div class="modal fade" id ="announcementModel" tabindex="-1" role ="dialog" aria-labelledby="announcementModelLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog modal-lg" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class= "modal-title text-blue text-center">Add Announcements</h4>
                </div>
                <form name ="announcementform" id ="announcementform" class="form-horizontal" method ="post"  >

                <div class="modal-body">
                    <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                    <div id ="sitegriddiv" style ="display:none;"> 
                        <div class="status"></div>

                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Caption</label>
                            <div class="col-sm-7" >
                                <input type="text" name="caption" id="caption" class="form-control" placeholder="Caption" value=""/>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Activation Date</label>
                            <div class="col-sm-5">
                                <div class="input-group">
                                    <input type ="text" readonly ="readonly"  name ="activationdate" id ="activationdate" placeholder="Activation Date Time" class="form-control datetimepicker ">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Browser</label>
                            <div class="col-sm-3" >
                                <select name = "browser" id = "browser" class= "form-control">
                                    <option value = ''>All Browser</option>
                                    <option value = "Internet Explorer" >Internet Explorer</option>
                                    <option value = "Firefox" >Firefox</option> 
                                    <option value = "Chrome" >Chrome</option> 
                                    <option value = "Safari" >Safari</option> 
                              
                                </select>
                            </div>
                       
                            <label for="input" class="col-sm-1 control-label">Version</label>
                            <div class="col-sm-3" >
                                <select name = "browser_version" id = "browser_version" class= "form-control">
                                    <option value = '0'>All Version</option>
                                    <?php for($i=1;$i<100;$i++){
                                        echo '<option value = "'.$i.'" >'.$i.' or Higher</option>';
                                    } ?>
                                   
                              
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Persistent</label>
                            <div class="col-sm-7" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="ispersistent" id="ispersistent"  value="1">
                                </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Active</label>
                            <div class="col-sm-7" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="isactive" id="isactive"  value="1">
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Content</label>
                            <div class="col-sm-10" >
                                <textarea name="content" id="ckeditor" class="form-control ckeditor"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                    <div class="modal-footer">
                        <div class="form-group">
                              <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                              <div class="col-sm-9">
                                  <input type ="hidden" name ="announcementid" id ="announcementid" value =""/>  
                                  <input type ="hidden" name ="mode" id ="mode" value =""/> 
                                  <input type ="hidden" name ="reset" id ="reset" value =""/> 
                                 <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                                &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                               </div>
                        </div> 

                      </div>     

                   </form>
            </div>
        </div>
    </div>
</div><!-- /.box -->


 
