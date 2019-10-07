<div class= "row" id = "EditlogCtrl" ng-controller= "EditlogCtrl">
    <div class= "col-md-12">
        <div class= "box" >
            <div class= "box-header  with-border">
                <h3 class= "box-title  text-blue">Edit Log</h3>
            </div>
            <div class= "box-header  with-border">
                <div class="row">
                    <input type="hidden" name="editlog_tablename"  id="editlog_tablename" value="<?php if(isset($editlog_tablename)){ echo $editlog_tablename;} ?>" />
                    <input type="hidden" name="editlog_recordid"  id="editlog_recordid" value="<?php if(isset($editlog_recordid)){ echo $editlog_recordid;} ?>" />
                    <div class= "col-sm-6 col-md-4">
                        <label class= "control-label">Field Name</label>
                        <select class= "form-control selectpicker"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"  name = "fieldname" id = "fieldname" ng-change = "changeFilters()" ng-model= "editlogFilter.fieldname">
                         
                            <?php foreach ($editlogfieldname as $key => $value) { ?>
                                <option value = "<?php echo $value['fieldname'];?>"><?php echo $value['count'];?></option> 
                            <?php } ?>

                        </select>
                    </div>
                    <div class= "col-sm-6 col-md-8 text-right">
                        <label class= "control-label">&nbsp;</label>
                        <div>
                            <button type = "button"  class= "btn btn-default btn-refress" title = "Refresh Data" ng-click= "refreshEditlogGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class= "box-body">
               <div>
                   <div ui-grid = "editLogGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class= "grid"></div>
                </div>
            </div>
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay" ng-show="overlay" >
                <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
        </div>	
    </div>
</div>