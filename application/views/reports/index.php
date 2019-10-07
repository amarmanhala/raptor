<!-- Default box -->
<div class= "box" ng-app="app" id="reportsCtrl" ng-controller= "reportsCtrl">
   
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">My Reports</h3>
    </div>
    <div class= "box-header  with-border">
         <div class="row">
                <div class= "col-md-3">
                    <label class= "control-label">Date Range</label>
                    <select id="daterange" name="daterange" class="form-control">
                        <option value="alltime" data-fromdate="<?php echo date(str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT));?>" data-todate="<?php echo date(RAPTOR_DISPLAY_DATEFORMAT);?>">Select Period</option>
                        <option value="custom">Custom</option>
                        <?php foreach($periods as $key=>$value) { ?>
                            <option value="<?php echo $key;?>" data-fromdate="<?php echo $value['fromdate'];?>" data-todate="<?php echo $value['todate'];?>"><?php echo $value['title'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class= "control-label">From</label>
                   <div class="input-group">
                       <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" value="<?php echo date(str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT));?>">
                       <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                       </div>
                   </div>
               </div>
               <div class="col-md-2">
                   <label class= "control-label">To</label>
                   <div class="input-group">
                       <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" value="<?php echo date(RAPTOR_DISPLAY_DATEFORMAT);?>">
                       <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                       </div>
                   </div>
               </div>

                <div  class="col-md-5 text-right">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshReportGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                            <button type="button" class= "btn btn-success" title = "Generate Report" ng-click="generateReport()"><i  class= "fa fa-file-text-o"></i></button> 
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
        <div id="reportGrid">
            <div ui-grid = "reportGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
     <div class= "overlay" ng-show="overlay">
        <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
    

</div><!-- /.box -->