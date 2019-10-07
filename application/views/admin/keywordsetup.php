<!-- Default box -->
<div class= "box" ng-app="app" id = "KeywordCtrl"  ng-controller= "KeywordCtrl">
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Keyword Maintenance</h3>
    </div>
    <div class= "box-header  with-border"> 
        <div class="row">
            <div  class="col-sm-12 col-md-12 text-right">
                <button class="btn  btn-primary" ng-click="addKeyword()" title="Add"><i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class= "row">
            <div class= "col-sm-4 col-md-2">
                <label class= "control-label">Trade</label>
                <select id = "trade" name = "trade" class= "form-control selectpicker" ng-model= "filterOptions.trade" ng-change="changeFilter()">
                    <option value = ''>All</option>
                    <?php foreach ($trades as $key => $value) {?>
                        <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_trade_name'] ;?></option> 
                    <?php } ?>
                </select>
            </div>
            <div class= "col-sm-4 col-md-2">
                <label class= "control-label">Works</label>
                <select class= "form-control selectpicker" name = "works" id = "works" ng-model= "filterOptions.works" ng-change="changeFilter()">
                    <option value = ''>All</option>
                    <?php foreach ($works as $key => $value) { ?>
                        <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_works_name'];?></option> 
                    <?php } ?>
                </select>
            </div>
            <div class= "col-sm-4 col-md-2">
                <label class= "control-label">Sub Works</label>
                <select name = "subworks" id = "subworks" class= "form-control selectpicker" ng-model= "filterOptions.subworks" ng-change="changeFilter()">
                    <option value = ''>All</option>
                    <?php foreach ($subworks as $key => $value) { ?>
                        <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_subworks_name'];?></option> 
                    <?php } ?>
                </select>
            </div>
                <div  class="col-sm-12 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                     
                        <input type="text" class="form-control" id = "externalfiltercomp" ng-change = "changeFilter()" placeholder= "Keyword..." ng-model="filterOptions.filtertext"  aria-invalid="false">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                            <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "changeFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                            <button type="button" class="btn btn-success" title = "Export To Excel" ng-click= "exportToExcel()"><i class="fa fa-file-excel-o"></i></button>
                        </span>
                    </div>
                    
                </div>
                <div  class="col-sm-12 col-md-2">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <input type="text" class="form-control allownumericwithdecimal" id = "weighting" placeholder= "weighting" ng-model="weighting">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" title = "Update" ng-click= "updateWeighting()">Update</button>
                        </span>
                    </div>
                    
                </div>
                 

            </div> 
    </div>

    <div class= "box-body">

        <div id="keywordGrid">
            <div ui-grid = "keywordGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
    
<div class="modal fade" id ="keywordModel" tabindex="-1" role ="dialog" aria-labelledby="keywordModelLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class= "modal-title text-blue text-left">Add Keyword</h4>
            </div>
            <form name ="keywordform" id ="keywordform" class="form-horizontal" method ="post"  >

            <div class="modal-body">
                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;"> 
                    <div class="status"></div>

                    
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">Trade</label>
                        <div class="col-sm-6" >
                            <select name = "strade" id = "strade" class= "form-control">
                                <option value="">Select</option>
                                <?php foreach ($works as $key => $value) {?>
                                    <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_works_name'] ;?></option> 
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">Works</label>
                        <div class="col-sm-6" >
                            <select name = "sworks" id = "sworks" class= "form-control">
                                <option value="">Select</option>
                                <?php foreach ($subworks as $key => $value) {?>
                                    <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_subworks_name'] ;?></option> 
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">Sub Works</label>
                        <div class="col-sm-6" >
                            <select name = "ssubworks" id = "ssubworks" class= "form-control">
                                <option value="">Select</option>
                                <?php foreach ($trades as $key => $value) {?>
                                    <option value = "<?php echo $value['id'];?>"  ><?php echo $value['se_trade_name'] ;?></option> 
                                <?php } ?>

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">Keyword</label>
                        <div class="col-sm-5" >
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Keyword" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input" class="col-sm-2 control-label">Weighting</label>
                        <div class="col-sm-3" >
                            <input type="number" name="sweighting" id="sweighting" min="1" max="100" class="form-control" />
                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <div class="form-group">
                          <label for="name" class="col-sm-3" class="control-label">&nbsp;</label>
                          <div class="col-sm-9">
                              <input type ="hidden" name ="keywordid" id ="keywordid" value =""/>  
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