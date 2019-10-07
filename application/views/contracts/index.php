<!-- Default box -->
<div class= "box" ng-app="app" id = "ContractCtrl"  ng-controller= "ContractCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">My Contracts</h3>
        <div class= "pull-right">
             
            <input type="hidden" id="edit_contract" name="edit_contract" value="<?php echo $EDIT_CONTRACT ? '1':'0';?>">
            <input type="hidden" id="delete_contract" name="delete_contract" value="<?php echo $DELETE_CONTRACT ? '1':'0';?>">
            <?php if($ADD_CONTRACT) { ?>
            <a class= "btn btn-primary btn-sm" href= "<?php echo site_url('contracts/add');?>" title = "Add New Contract"><i class= "fa  fa-plus"></i></a> 
            <?php } ?>
        </div>
    </div>
    <div class= "box-header  with-border"> 
            <div class= "row">
                
                <div class= "col-sm-6 col-md-3">
                    <label class= "control-label">Type</label>
                    <select class= "form-control selectpicker" name = "contracttypeid" id = "contracttypeid" ng-model= "filterOptions.contracttypeid" ng-change="changeFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($contracttypes as $key => $value) { ?>
                            <option value = "<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>

                    </select>
                </div>
                <div class= "col-sm-6 col-md-4">
                    <label class= "control-label">Manager</label>
                    <select class= "form-control selectpicker" name = "managerid" id = "managerid" ng-model= "filterOptions.managerid" ng-change="changeFilter()">
                        <option value = ''>All</option>
                        <?php foreach ($managers as $key => $value) { ?>
                            <option value = "<?php echo $value['contactid'];?>"  ><?php echo $value['firstname'];?></option> 
                        <?php } ?>

                    </select>
                </div>  
                <div  class="col-sm-12 col-md-5">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control" id = "externalfiltercomp" ng-change = "changeFilter()" placeholder= "Search..." ng-model="filterOptions.filtertext"  aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($EXPORT_CONTRACT) { ?>
                                    <button type = "button" class= "btn btn-success" ng-click="exportToExcel()" title = "Export To Excel"><i class= "fa fa-file-excel-o"></i></button>
                                <?php } ?>
                            </span>
                    </div>
                    
                </div>
                 

            </div>
 
    </div>

    <div class= "box-body">
        <div id="mycontractstatus"></div> 
    <?php 
           if($this->session->flashdata('success')) 
           {
               echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
           }
       ?>
        <div id="contractGrid">
            <div ui-grid = "contractGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->	
