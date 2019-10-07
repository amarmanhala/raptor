<!-- Default box -->
<div class= "box"  ng-app="app" id="CustomerAddressCtrl" ng-controller= "CustomerAddressCtrl">
   
    <div class= "box-header with-border">
        <h3 class= "box-title text-blue">Addresses</h3>
        <div class= "pull-right text-right">
            <input type="hidden" id="edit_address" name="edit_address" value="<?php echo $edit_address ? '1':'0';?>">
            <?php if($add_address) { ?>
                <a class= "btn btn-primary" href= "<?php echo site_url("customers/addaddress");?>" title = "Add New Address"><i class= "fa fa-plus"></i></a> 
            <?php } ?>
            <?php  if($ADDRESS_UPLOAD) { ?>
                   <button type="button" class="btn  btn-info" id="btn_import" title="Import Excel" ng-click="importAddress();" ><i class="fa fa-upload"></i></button>
                   <br><a href="javascript:void(0)"  ng-click="exportImportTemplate()">Import Template</a>&nbsp;
               <?php }?> 
        </div>
    </div>
    <div class= "box-header  with-border">
         <div class="row">
            <div class= "col-sm-1 col-md-1"  >
                <label class="control-label">&nbsp;</label>
                <div>
                <button type = "button"  class= "btn btn-info" title = "Show on Map" ng-click="showOnMap()"><i class= "fa fa-map-marker" title = "Show on Map"></i></button>
                </div>
            </div>
                <div class= "col-sm-3 col-md-2" >
                    <label class= "control-label">Status</label>
                    <select class= "form-control selectpicker" name = "status" id = "status" ng-change = "changeFilters()" ng-model= "addressFilter.status" >
                        <option value = '1'>Active</option>
                        <option value = '0'>Inactive</option>
                        <option value = ''>All</option>
                      
                    </select>
                </div>
                <div class= "col-sm-4 col-md-2" >
                    <label class= "control-label">State</label>
                    <select class= "form-control selectpicker" name = "state" id = "state" ng-change = "changeFilters()" ng-model= "addressFilter.state" >
                        <option value = ''>All</option>
                        <?php foreach($states as $val) { ?>
                            <option value="<?php echo $val['abbreviation'];?>"><?php echo $val['abbreviation'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-4 col-md-3" >
                    <label class= "control-label">Site FM</label>
                    <select class= "form-control selectpicker" name = "sitefm" id = "sitefm" ng-change = "changeFilters()" ng-model= "addressFilter.sitefm" >
                        <option value = ''>All</option>
                        <?php foreach($sitefmcontacts as $val) { ?>
                            <option value="<?php echo $val['contactid'];?>"><?php echo $val['sitefm'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div  class="col-sm-12 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                        <input type = "text" id = "externalfiltercomp" placeholder="Search........."  ng-change = "changeText()" class= "form-control" ng-model= "addressFilter.filtertext" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshAddressGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <?php if($export_address) { ?>
                                <button type="button" class= "btn btn-success" title = "Export To Excel" ng-click="exportToExcel()"><i title="Export To Excel"  class= "fa fa-file-excel-o"></i></button> 
                                <?php } ?>
                            </span>
                    </div>
                    
                </div>
               
            </div>    
        
        
         
    </div>
    <div class= "box-body">
         <?php 
        if($this->session->flashdata('success')) 
        {
            echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
        }
    ?>
        <div id="addressGrid">
            <div ui-grid = "addressGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns ui-grid-selection class= "gridwithselect"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
        <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
    
    <div class= "modal fade" id = "addressesModel" tabindex= "-1" role = "dialog" aria-labelledby = "addressesModalLabel" data-backdrop= "static" data-keyboard = "FALSE">
        <div class= "modal-dialog modal-lg" role = "document" >
          <div class= "modal-content">
            <div class= "modal-header">
                <button type = "button" class= "close" ng-click="closeModal()"><span aria-hidden= "TRUE">&times;</span></button>
                <h4 class= "modal-title" id = "exampleModalLabel"><?php echo $customerData['companyname'];?></h4>
            </div>
            <div class= "modal-body">
                <div id="address-map" style="height: 450px;border:1px solid #d2d6de;"></div>    
            </div>
            <div class= "modal-footer">
                <button type = "button" class= "btn btn-default" ng-click="closeModal()">Close</button>
            </div>
               <!-- Loading (remove the following to stop the loading)-->
            <div class= "overlay map-overlay">
                  <i class= "fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->

          </div>
        </div>
      </div>
     
    <div class="modal fade" id ="importAddressModal" tabindex="-1" role ="dialog" aria-labelledby="importAddressModalLabel" data-backdrop="static" data-keyboard ="false">
      <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Import Address</h4>
            </div>
        <form name ="importAddressform" id ="importAddressform" class="form-horizontal" method ="post" enctype="multipart/form-data" action="<?php echo site_url('customers/importaddressexcel') ?>" >

            <div class="modal-body">

                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>
                     
                    <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Upload Excel </label>
                        <div class="col-sm-9">
                            <input type="file" name="importfile" id="importfile" onchange="readExcelURL(this);" />
                        </div>
                    </div>
                    <div class="progress">
                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
                              <span class="sr-only">0% Complete (success)</span>
                            </div>
                    </div>


                     <div id="status"></div>  

                </div>
            </div>
                <div class="modal-footer">
                    <div class="form-group">
                          <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                          <div class="col-sm-9"> 
                             <button type ="submit" name ="btnsave" id ="btnsave" class="btn btn-primary" data-loading-text ="Importing...">Import Excel</button>
                            &nbsp;&nbsp;<button type ="button" name ="btncancel" id ="btncancel" class="btn btn-default" data-loading-text ="Importing...">Cancel</button>
                           </div>
                    </div> 

                  </div>     

               </form>
        </div>
      </div>
    </div>
    
</div><!-- /.box -->	



    
