<!-- Default box -->
<div class= "box" id = "ComplianceCtrl" ng-app= "app" ng-controller= "ComplianceCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
        <div class="pull-right">
             
            
        </div>
    
    </div>
    <div class= "box-header  with-border"> 
            <div class="row">
                <div class= "col-sm-6 col-md-3" >
                    <label class= "control-label">State</label>
                    <select class= "form-control selectpicker"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.state">
                         
                            <?php foreach($states as $key=>$value) { 
                            $selected = '';
                            if(set_value('fstate') == $value['abbreviation']) {
                                $selected = 'selected';
                            }
                            ?>
                            <option value="<?php echo $value['abbreviation'];?>" <?php echo $selected;?>><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-4" >
                    <label class= "control-label">Trade</label>
                    <select class= "form-control selectpicker"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.trade">
                       
                     <?php foreach($trades as $key=>$value) { 
                            $selected = '';
                            if(set_value('trade') == $value['se_trade_name']) {
                                $selected = 'selected';
                            }
                      ?>
                                <option value="<?php echo $value['se_trade_name'];?>" <?php echo $selected;?>><?php echo $value['se_trade_name'];?></option> 
                  <?php } ?>
                        </select>
                </div>
                <div  class="col-sm-12 col-md-5">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control" placeholder="Search" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success"  ng-click="exportToExcel()" title="Export To Excel"><i title="Export To Excel" class="fa fa-file-excel-o"></i></button>
                            </span>
                    </div>
                    
                </div>
               
            </div>    
        
    </div>
      
    <div class= "box-body">
        <div id="mycompliancestatus"></div>   
         <?php  if($this->session->flashdata('success'))  {
         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
            }
            if($this->session->flashdata('error')) {
         	echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	
            } ?>
  
        <div>
            <div category-header="gridOptions"></div>
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        
             
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" style = "display:none">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->  
<script type ="text/javascript">
         var headercategory = [{ name: 'Contractor Details' , visible: true}];
         var captionData = <?php echo json_encode($captionData); ?>;
       
          
        <?php if (isset($captionData)) { 
                foreach ($captionData as $key => $value) {  ?>
                    //headercategory.push(<?php echo json_encode($value); ?>);
                    headercategory.push({ name: '<?php echo $value['caption']; ?>' , visible: '<?php echo $value['has_startdate']>0 || $value['has_number']>0 || $value['has_expiry']>0 ? TRUE : FALSE; ?>' });
        <?php }
          } ?>
       
</script>
    
 
