
<!-- Default box -->
<div class= "box" ng-app="app" id = "ContactCtrl"  ng-controller= "ContactCtrl">
     <?php 
        if($this->session->flashdata('success')) 
        {
            echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
        }
    ?>
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Contacts</h3>
        <div class= "pull-right">
            <input type="hidden" id="edit_contact" name="edit_contact" value="<?php echo $edit_contact ? '1':'0';?>">
          <?php if($add_contact) { ?>
            <a class= "btn btn-primary btn-sm" href= "<?php echo site_url('customers/addcontact');?>" title = "Add New Contact"><i class= "fa  fa-user-plus"></i></a> 
            <?php } ?>
            
            <?php if($import_contact) { ?>
            <button type = "button" class= "btn btn-sm btn-warning" id = "import contacts" title = "Import Contacts"><i class= "fa fa-upload"></i></button>
            <?php } ?>
            <?php if($invite_contact) { ?>
            <button type = "button" class= "btn btn-sm btn-default" ng-click="sendPortalInvitation()" title = "Send Portal Invitation"><i class= "fa fa-envelope"></i></button>
            <?php } ?>
          
        </div>


    </div>
    <div class= "box-header  with-border"> 
            <div class= "row">
                <div class= "col-sm-6 col-md-2">
                <label class= "control-label">State</label>
                
                    <select class= "form-control selectpicker" name = "state" id = "state" ng-model= "contactFilter.state" ng-change="changeContactFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($states as $key => $value) { ?>
                            <option value = "<?php echo $value['abbreviation'];?>" <?php if (set_value('shipstate') == $value['abbreviation']) echo "selected";?>><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>

                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                <label class= "control-label">Role</label>
                
                    <select id = "role" name = "role[]" class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" ng-model= "contactFilter.role" ng-change="changeContactFilter()">

                        <?php foreach ($contact_roles as $key => $value) {?>
                            <option value = "<?php echo $value['role']; ?>"  ><?php echo $value['role']; ?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                <label class= "control-label">Reports To</label>
                    <select name = "bossid" id = "bossid" class= "form-control selectpicker" ng-model= "contactFilter.bossid" ng-change="changeContactFilter()">
                        <option value = ''>All</option>
                         <?php foreach ($reportstocontacts as $key => $value) { ?>
                            <option value = "<?php echo $value['contactid'];?>"><?php echo $value['name'];?></option> 
                        <?php 
                        } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                    <label class= "control-label">Status</label>
                    <select name = "status" id = "status" class= "form-control selectpicker" ng-model= "contactFilter.status" ng-change="changeContactFilter()">
                        <option value = ''>All</option>
                        <option value = '1'>Active</option>
                        <option value = '0'>Inactive</option> 
                    </select>
                </div>
                <div  class="col-sm-12 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control" id = "externalfiltercomp" ng-change = "changeContactFilter()" placeholder= "Search..." ng-model="contactFilter.filtertext"  aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearContactFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeContactFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($export_contact) { ?>
                                    <button type = "button" class= "btn btn-success" ng-click="exportToExcel()" title = "Export To Excel"><i class= "fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                    </div>
                    
                </div>
                 

            </div>
 
    </div>

    <div class= "box-body">

        <div id="contactGrid">
            <div ui-grid = "contactGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns  ui-grid-selection class="gridwithselect"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->	
