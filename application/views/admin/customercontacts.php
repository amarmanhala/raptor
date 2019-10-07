<!-- Default box -->
<div class= "box" ng-app="app" id = "CustomerContactCtrl"  ng-controller= "CustomerContactCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Customer Contacts</h3>
    </div>
    <div class= "box-header  with-border"> 
        <div class="row">
            <div class= "col-sm-9 col-md-6" style="padding-right: 0px;">
                <label class= "control-label">Customer:</label>
                <div class= "has-feedback">
                     <input type="text" ng-model="filterOptions.company" id="company" name="company" placeholder="search company.." uib-typeahead="customer as customer.companyname for customer in getCustomer($viewValue)"    typeahead-on-select="onCustomerSelect($item, $model, $label)" typeahead-loading="loadingCustomer"   class="form-control" ng-change="changeCustomerText()" />
                    <span class="form-control-feedback" ><i ng-show="loadingCustomer" class="fa fa-spinner typeahead-lodaing"></i><i class="glyphicon glyphicon-search" ng-hide="loadingCustomer" ></i></span>
                </div>
            </div>
        </div>
        <div class= "row">
            <div class= "col-sm-4 col-md-3">
                <label class= "control-label">Role</label>
                <select id = "role" name = "role[]" class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" ng-model= "filterOptions.role" ng-change="changeContactFilter()">
                    <?php foreach ($contact_roles as $key => $value) {?>
                        <option value = "<?php echo $value['name'];?>"  ><?php echo $value['name'] ;?></option> 
                    <?php } ?>
                </select>
            </div>
            <div class= "col-sm-4 col-md-2">
                <label class= "control-label">State</label>
                <select class= "form-control selectpicker" name = "state" id = "state" ng-model= "filterOptions.state" ng-change="changeContactFilter()">
                    <option value = ''>All</option>
                    <?php foreach ($states as $key => $value) { ?>
                        <option value = "<?php echo $value['abbreviation'];?>"  ><?php echo $value['abbreviation'];?></option> 
                    <?php } ?>
                </select>
            </div>
            <div class= "col-sm-4 col-md-2">
                <label class= "control-label">Status</label>
                <select name = "status" id = "status" class= "form-control selectpicker" ng-model= "filterOptions.status" ng-change="changeContactFilter()">
                    <option value = ''>All</option>
                    <option value = 'Active'>Active</option>
                    <option value = 'Inactive'>Inactive</option>
                    <option value = 'Invited'>Invited</option>
                </select>
            </div>
                <div  class="col-sm-12 col-md-5">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                        <input type="text" class="form-control" id = "externalfiltercomp" ng-change = "changeContactFilter()" placeholder= "Search..." ng-model="filterOptions.filtertext"  aria-invalid="false">
                        <span class="input-group-btn">

                            <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearContactFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                            <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeContactFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                            <button type="button" class= "btn btn-info" onclick="sendPortalInvitation();" title = "Send Portal Invitation"><i class= "fa fa-envelope"></i></button>
                            <button type="button" class= "btn btn-default" onclick="contactMenuAccess();" title = "Contact Menu Access"><i class= "glyphicon glyphicon-lock"></i></button>

                        </span>
                    </div>
                    
                </div>
                 

            </div> 
    </div>

    <div class= "box-body">

        <div id="contactGrid">
            <div ui-grid = "contactGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->

 <div class="modal fade" id ="contactMenuModel" tabindex="-1" role ="dialog" aria-labelledby="contactMenuModelLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class= "modal-title text-blue text-center">Contact Menu Access</h4>
            </div>
        <form name ="contactmenuform" id ="contactmenuform" class="form-horizontal" method ="post"  >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                  
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-7" >
                            <input type="text" name="contactname" id="contactname" class="form-control" value="" readonly="readonly"/>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label for="input" class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-4" >
                            <select class= "form-control" name = "role" id = "role"  >
                                
                            </select> 
                        </div>
                    </div>
                    <div class="form-group" >
                        <div class="col-sm-12"  >
                            <div   style="overflow: auto; max-height: 200px;">
                            <table id ="menumoduletbl" class="table table-striped table-bordered table-condensed table-hover" style ="margin-bottom:0px">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center" style="width: 120px;">Visible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                 
                </div>
            </div>
                <div class="modal-footer">
                    <div class="form-group">
                          <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                          <div class="col-sm-9">
                              <input type ="hidden" name ="contactid" id ="contactid" value =""/>  
                             <button type ="button" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Saving...">Save</button>
                            &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Saving...">Cancel</button>
                           </div>
                    </div> 

                  </div>     

               </form>
        </div>
      </div>
    </div>

 
