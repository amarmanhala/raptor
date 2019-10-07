<!-- Default box -->
<div class= "box" ng-app="app" id = "ModulesCtrl"  ng-controller= "ModulesCtrl">
    <div class= "box-header with-border">
        <h3 class= "box-title text-blue">Modules</h3>
        <div  class="pull-right">
                <button type="button" class="btn  btn-primary" ng-click="addModule();" title="Add"><i class="fa fa-plus"></i></button>
        </div>
    </div>
     
    <div class= "box-body">
        <div id="moduleGrid">
            <div ui-grid = "gridOptions" ui-grid-auto-resize ui-grid-resize-columns ui-grid-expandable-row  ui-grid-exporter  class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
      <div class="modal fade" id ="menuModuleModel" tabindex="-1" role ="dialog" aria-labelledby="menuModuleModelLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog modal-lg" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class= "modal-title text-blue">Edit Module</h4>
                </div>
                <form name ="moduleform" id ="moduleform" class="form-horizontal" method ="post"  >

                <div class="modal-body">
                    <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                    <div id ="sitegriddiv" style ="display:none;"> 
                        <div class="status"></div>

                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Caption</label>
                            <div class="col-sm-7" >
                                <input type="text" name="name" id="name" class="form-control" placeholder="Caption" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Parent Menu</label>
                            <div class="col-sm-7" >
                                <select name = "parentid" id = "parentid" class= "form-control">
                                    <option value = '0'>None</option>
                                    <?php foreach ($routes as $key => $value) { 
                                        $selected = '';
                                        if($value['parentid'] > 0){
                                            continue;
                                        }
                                      
                                   ?>
                                    <option value = "<?php echo $value['id'];?>"<?php echo $selected;?>><?php echo $value['name'];?></option> 
                                   <?php 
                                   } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">URL</label>
                            <div class="col-sm-10" >
                                <div class="input-group">
                                    <span class="input-group-addon" style="background-color: #eee;"><?php echo site_url()?></span>
                                    <input type="text" class="form-control" id = "url1"   placeholder= "URL 1"  name="url1">
                                    <span class="input-group-addon"  style="background-color: #eee;">/</span>
                                    <input type="text" class="form-control" id = "url2"   placeholder= "URL 2"  name="url2">
                                    <span class="input-group-addon" style="background-color: #eee;">/</span>
                                    <input type="text" class="form-control" id = "url3"   placeholder= "URL 3"  name="url3">
                                </div>
                                  
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Show Counter</label>
                            <div class="col-sm-1" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="showcounter" id="showcounter"  value="1">
                                </label>
                                </div>
                            </div>
                            <label for="input" class="col-sm-1 control-label">Keyword</label>
                            <div class="col-sm-2" >
                                 <input type="text" class="form-control" id = "counter_keyword"   placeholder= "Keyword"  name="counter_keyword">
                            </div>
                            <label for="input" class="col-sm-2 control-label">Background</label>
                            <div class="col-sm-1" >
                                <div class="input-group my-colorpicker1">
                                    <input type="text" class="form-control" style="display: none" id = "counter_bgcolor"   placeholder= "Background"  name="counter_bgcolor">
                                    <div class="input-group-addon" style="padding: 0px;border-left: 1px solid #d2d6de">
                                        <i class="counter_bgcolor" style="width: 100%; height: 30px;"></i>
                                    </div>
                                </div><!-- /.input group -->
                            </div>
                            <label for="input" class="col-sm-1 control-label">Color</label>
                            <div class="col-sm-1" >
                                <div class="input-group my-colorpicker1">
                                    <input type="text" class="form-control" style="display: none" id = "counter_color"   placeholder= "Color"  name="counter_color">
                                    <div class="input-group-addon" style="padding: 0px;border-left: 1px solid #d2d6de">
                                        <i class="counter_color" style="width: 100%; height: 30px;"></i>
                                    </div>
                                </div><!-- /.input group -->
                                
                            </div>
                        </div>
                        <div class= "form-group">
                            <label for= "typeid" class= "col-sm-2 control-label">Menu Type: </label>
                            <div class= "col-sm-10">
                                <label class="radio-inline"><input type="radio" value="ICON"  name="menu_icontype" id="menu_icontype_icon" >ICON</label>
                                <label class="radio-inline"><input type="radio" value="IMAGE" name="menu_icontype" id="menu_icontype_image" >IMAGE</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label menu_icon_ele">Menu Icon</label>
                            <div class="col-sm-4 menu_icon_ele" >
                                <div class="input-group">
                                    <input type="text" name="menu_icon" id="menu_icon" class="form-control" placeholder="Menu Icon" value=""/>
                                    <div class="input-group-addon">
                                        <i class="menu_icon" ></i>
                                    </div>
                                </div><!-- /.input group -->
                                
                            </div>
                            <label for="input" class="col-sm-2 control-label menu_image_ele" style="display:none">Menu Image</label>
                            <div class="col-sm-4 menu_image_ele" style="display:none"> 
                                <input type="text" name="menu_image" id="menu_image" class="form-control" placeholder="Menu Image" value=""/>
                            </div>
                            <label for="input" class="col-sm-2 control-label">Order</label>
                            <div class="col-sm-1" >
                                <input type="text" name="sortorder" id="sortorder" class="form-control" placeholder="Sort Order" value=""/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Target Window</label>
                            <div class="col-sm-7" >
                                <select name = "target" id = "target" class= "form-control">
                                    <option value = ''>Default</option>
                                    
                                    <option value = "_blank" >Load in a new window</option>
                                    <option value = "_self" >Load in the same frame as it was clicked</option> 
                                    <option value = "_parent" >Load in the parent frameset</option> 
                                    <option value = "_top" >Load in the full body of the window</option> 
                              
                                </select>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Master Access</label>
                            <div class="col-sm-7" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="masteraccess" id="masteraccess"  value="1">
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">FM Access</label>
                            <div class="col-sm-7" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="fmaccess" id="fmaccess"  value="1">
                                </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="input" class="col-sm-2 control-label">Site Contact Access</label>
                            <div class="col-sm-7" >
                                <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sitecontactaccess" id="sitecontactaccess"  value="1">
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
                         
                    </div>
                </div>
                    <div class="modal-footer">
                        <div class="form-group">
                              <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                              <div class="col-sm-9">
                                  <input type ="hidden" name ="menuid" id ="menuid" value =""/>  
                                  <input type ="hidden" name ="mode" id ="mode" value =""/> 
                                   
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