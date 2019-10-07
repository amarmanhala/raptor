<!-- Default box -->
<div class= "box" id="AttributeCtrl" ng-controller= "AttributeCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Address Attribute</h3>
        <div class= "pull-right">
            <input type="hidden" id="edit_addressattributes" name="edit_addressattributes" value="<?php echo $EDIT_ADDRESS_ATTRIBUTE ? '1':'0';?>">
            <input type="hidden" id="delete_addressattributes" name="delete_addressattributes" value="<?php echo $DELETE_ADDRESS_ATTRIBUTE ? '1':'0';?>">
            <?php if($ADD_ADDRESS_ATTRIBUTE) { ?>
             <button type="button" class="btn  btn-primary" id="addattribute" title="Add"><i class="fa fa-plus"></i></button>
          <?php } ?>
         
        <button type = "button"  class= "btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshAttributeGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
        </div>
    </div>

    <div class= "box-body">
        <div>
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->	
  
<div class="modal fade" id="addressAttributeModal" tabindex="-1" role="dialog" aria-labelledby="addressAttributeModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form name="address_attribute_form" id="address_attribute_form" class="form-horizontal" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="addressAttributeModalLabel">Add Address Attribute</h4>
        </div>
            
        <div class="modal-body">
            <div class="alert alert-danger" style="display:none;"></div>
            <div class="form-group">
               <label for="name" class="col-sm-3 control-label">Attribute</label>
               <div class="col-sm-6">
                   <select name = "attribute" id = "attribute" class= "form-control" onchange="changeAttribute(this);">
                        <option value = ''>-Select-</option>
                        <?php foreach($address_attributes as $val) { ?>
                            <option value="<?php echo $val['id'];?>" data-type="<?php echo $val['type'];?>"><?php echo $val['name'];?></option>
                        <?php } ?>
                  </select>
               </div>
               <?php if($CREATE_ADDRESS_ATTRIBUTE) { ?>
               <div class="col-sm-1">
                   <button type="button" class= "btn btn-primary" id="createaddressattribute" title = "Add New Address Label Attribute"><i class= "fa fa-plus"></i></button> 
               </div>
               <?php }?>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-3 control-label">Value</label>
                <div class="col-sm-9">
                    <input class="form-control" id="value" name="value" placeholder="Value">
                    <input type="hidden" id="type" name="type">
                </div>
            </div> 
            
            <div class="form-group">
               <label for="name" class="col-sm-3 control-label">Active</label>
               <div class="col-sm-6">
                  <div class="checkbox">
                    <label>
                        <input type="checkbox" name="status" value="1">
                    </label>
                  </div>
               </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-3 control-label">&nbsp;</label>
                <div class="col-sm-8">
                    <button type="submit" name="modalsave" id="modalsave" class="btn btn-primary" data-loading-text="Saving...">Save</button>
                    &nbsp;&nbsp;<button type="button" name="cancel" id="cancel" class="btn btn-default" data-loading-text="Cancel">Cancel</button>
                </div>
            </div>
        </div>
            <input type="hidden" id="labelid" name="labelid">
        </form>
    </div>
  </div>
</div>

<?php $this->load->view('customers/addressattribute_modal'); ?>