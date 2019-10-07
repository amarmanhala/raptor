<div class="row" id="userSecurityCtrl"  ng-controller="userSecurityCtrl">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header  with-border">
                <h3 class="box-title">Secured Functions</h3>
            </div>
            <div class="box-header  with-border">
                <div class="row">
                    <div class="col-md-5" style="padding-right: 0px;">
                        <button type="button" class="btn btn-primary" ng-click="addEditContact()">Add/Edit Contact</button>
                        <button type="button" class="btn btn-success" ng-click="functionAccess('give')">Grant</button>
                        <button type="button" class="btn btn-danger" ng-click="functionAccess('revoke')">Revoke</button>
                    </div>
                    <div class="col-md-5" style="padding-right: 0px;">
                        <div class="row">
                            <div class= "col-sm-4" style="padding-right: 0px;">
                                <select id="contacts" name="contacts" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.contact">
                                    <option value="">Contacts</option>
                                    <?php foreach($contacts as $key=>$value) {  ?>
                                    <option value="<?php echo $value['contactid'];?>"><?php echo $value['firstname'];?></option> 
                                <?php } ?>
                                </select>
                            </div>
                            <div class= "col-sm-3" style="padding-right: 0px;">
                                <select id="contacts" name="role" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.role">
                                    <option value="">Role</option>
                                    <?php foreach($role as $key=>$value) {  ?>
                                    <option value="<?php echo $value['role'];?>"><?php echo $value['role'];?></option> 
                                <?php } ?>
                                </select>
                            </div>
                            <div class= "col-sm-5" style="padding-right: 0px;">
                                <select id="contacts" name="function" class="form-control" ng-change = "changeFilters()" ng-model= "filterOptions.function">
                                    <option value="">Function</option>
                                    <?php foreach($functions as $key=>$value) {  ?>
                                    <option value="<?php echo $value['functionname'];?>"><?php echo $value['functionname'];?></option> 
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 text-right"> 
                        <div class="input-group input-group">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning btn-sm" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default  btn-sm btn-refresh" title = "Refresh Data" ng-click= "refreshUserSecurityGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($export_contactsecurity) { ?>
                                <button type="button" class="btn btn-success  btn-sm" title = "Export To Excel" ng-click= "exportToExcel()"><i class="fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body">
               <div id="securityGrid">
                   <div ui-grid ="securityGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns  ui-grid-selection class="gridwithselect"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" ng-show="overlay">
                  <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!--end loading -->
        </div>	
    </div>
</div>