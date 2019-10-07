<!-- Default box -->
<div  id = "CustomerDocumentCtrl"  ng-app="app" ng-controller= "CustomerDocumentCtrl">
    <div class= "box" >
        <div class="box-header with-border">
            <h3 class="box-title text-blue">Customer Documents</h3>
            <div class= "pull-right">
                <?php if($UPLOAD_CUSTOMER_DOCUMENT){?>
                <button id ="adddoc" class="btn btn-primary" title = "Upload Document"><i class= "fa fa-plus"></i></button> 
                <?php } ?>
            </div>

        </div>
        <div class= "box-header  with-border form-horizontal">
               <div class="row">
                    <div class= "col-sm-6 col-md-3">
                        <label class= "control-label">Document Type</label>
                        <select class= "form-control selectpicker"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.documenttype">
                        <?php foreach ($docType as $key => $value) {
                                echo '<option value="'.$value['doctype'].'">'.$value['doctype'].' ('.$value['count'].')</option>';
                            }?>
                        </select>
                    </div>
                     
                    <div class= "col-sm-6 col-md-3">
                        <label class= "control-label">Month Added</label>
                        <select class= "form-control selectpicker"   multiple data-live-search= "TRUE"  title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.monthyear">
                            <?php foreach ($monthDocCount as $key => $value) {
                                echo '<option value="'.$value['month_added'].'">'.$value['month_added'].' ('.$value['count'].')</option>';
                            }?>
                        </select>
                    </div>
                    <div  class="col-sm-12 col-md-5 col-md-offset-1">
                        <label class="control-label">&nbsp;</label>
                        <div class="input-group input-group">

                                <input type="text" class="form-control" placeholder="Documentid/Doctype/Doc Name/Description" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                                <span class="input-group-btn">

                                    <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                    <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                     <?php if($EXPORT_DOC){?>
                                        <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                                    <?php } ?>
                                </span>
                        </div>
                    </div>
                      
                </div>    
 
        </div>

        <div class= "box-body">
             <div id="mydocumentstatus"></div>   
             <div>
                <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
            </div>
        </div><!-- /.box-body -->
        <!-- Loading (remove the following to stop the loading)-->
        <div class="overlay" ng-show="overlay" >
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->
    </div><!-- /.box -->
    <div class="modal fade" id ="uploaddocModal" tabindex="-1" role ="dialog" aria-labelledby="uploaddocModalLabel" data-backdrop="static" data-keyboard ="false">
        <div class="modal-dialog" role ="document">
          <div class="modal-content">
          <form name ="upload_form" id ="upload_form" role ="form" method ="post" action="<?php echo site_url();?>documents/uploadcustomerdocument" class="form-horizontal"  enctype ="multipart/form-data">
            <div class="modal-header">
              <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="uploaddocModalTitle">Upload Document</h4>
            </div>
            <div class="modal-body">
                  <div class="form-group">
                      <label for="doctype" class="col-sm-2 control-label">Type</label>
                        <div class="col-sm-6">
                            <select name ="doctype" id ="doctype" class="form-control">
                                 <option value ="">Select Type</option>
                                <?php foreach ($docFolder as $key => $value) {
                                    echo '<option value ="'.$value['caption'].'" data-folder="'.$value['foldername'].'">'.$value['caption'].'</option>';
                                } ?>
                            </select>
                        </div>

                  </div>

                  <div class="form-group">
                      <label for="description" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-10">
                      <input type ="text" id ="description" name ="description" placeholder="Description" class="form-control">
                      </div>
                  </div>
                    <div class="form-group">
                        <label for="fileup" class="col-sm-2 control-label">File</label>
                        <div class="col-sm-10 ">
                            <input  type ="file" name ="fileup"  class="file" id ="fileup" onchange="readDocURL(this)">

                            <div class="input-group">
                                
                                <input type="text" class="form-control" disabled placeholder="Upload Image">
                                <span class="input-group-btn">
                                  <button class="browse btn btn-primary" type="button">Browse</button>
                                </span>
                            </div>
                        </div>
                    </div>
                  
                <div class="progress">
                      <div class="progress-bar progress-bar-primary progress-bar-striped" role ="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style ="width: 1%">
                        <span class="sr-only">0% Complete (success)</span>
                      </div>
              </div>
               <div id ="status"></div>         

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                          <button type ="submit" id ="modalsave" class="btn btn-primary">Save</button>&nbsp;
                          <button type ="button" id ="cancel" class="btn btn-Default">Cancel</button>
                    </div>

                  </div>
            </div> 
              <input  type ="hidden" name ="foldername" id ="foldername" value ="" /> 
           </form>
          </div>
        </div>
    </div>
</div>

