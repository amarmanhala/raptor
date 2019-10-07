<!-- Default box -->
<div class= "box" id = "ServiceScheduleCtrl" ng-app= "app" ng-controller= "ServiceScheduleCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
        <div class="pull-right text-right">
             
            <?php
                foreach ($servicestatus as $key=> $value) {
                    if (trim($value['color']) == "") {
                        $value['color'] == 'white';
                    }
                    if (trim($value['textcolor']) == "") {
                        $value['textcolor'] == 'black';
                    }
            ?>
            <button type="button" class="btn btn-flat" style ="background-color:<?php echo $value['color'];?>;color:<?php echo $value['textcolor'];?>;" title = "<?php echo $value['name'];?>"><?php echo $value['name'];?></button>
            <?php       
                }
            ?>
        </div>
    </div>
    <div class= "box-header  with-border"> 
            <div class="row">
                <div class= "col-sm-6 col-md-4">
                    <label class= "control-label">Contract</label>
                    <!--<select class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.contract">-->
                    <select class= "form-control" ng-change = "changeFilters()" ng-model= "filterOptions.contract">
                        <option value="">All</option>
                        <?php foreach($contracts as $key=>$value) { ?>
                        <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                    <label class= "control-label">State</label>
                    <!--<select class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.state">-->
                    <select class= "form-control" ng-change = "changeFilters()" ng-model= "filterOptions.state">
                        <option value="">All</option>
                        <?php foreach($states as $key=>$value) { ?>
                        <option value="<?php echo $value['abbreviation'];?>"><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                    <label class= "control-label">Site</label>
                    <!--<select class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.site">-->
                    <select class= "form-control" ng-change = "changeFilters()" ng-model= "filterOptions.site">
                        <option value="">All</option>
                        <?php foreach($sites as $key=>$value) { ?>
                        <option value="<?php echo $value['labelid'];?>"><?php echo $value['site'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                    <label class= "control-label">Service Type</label>
                    <!--<select class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.servicetype">-->
                    <select class= "form-control" ng-change = "changeFilters()" ng-model= "filterOptions.servicetype">
                        <option value="">All</option>
                        <?php foreach($servicetypes as $key=>$value) { ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class= "col-sm-6 col-md-2">
                    <label class= "control-label">Job Status</label>
                    <!--<select class= "form-control selectpicker" multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%"   ng-change = "changeFilters()" ng-model= "filterOptions.jobstatus">-->
                    <select class= "form-control" ng-change = "changeFilters()" ng-model= "filterOptions.jobstatus">
                        <option value="">All</option>
                        <?php foreach($servicestatus as $key=>$value) { ?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-6 col-md-2">
                    <label class= "control-label">From</label>
                    <div class="input-group">
                       <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" value="<?php echo format_date(date('Y-m-01'), RAPTOR_DISPLAY_DATEFORMAT);?>">
                       <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                       </div>
                   </div>
               </div>
               <div class="col-sm-6 col-md-2">
                   <label class= "control-label">To</label>
                   <div class="input-group">
                       <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" value="<?php echo format_date(date('Y-m-t'), RAPTOR_DISPLAY_DATEFORMAT);?>">
                       <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                       </div>
                   </div>
               </div>
                <div class="col-sm-6 col-md-6">
                   <label class= "control-label">Show</label>
                   <div class="btn-group-horizontal">
                        <button type="button" class="btn btn-default" title = "Day" id="showDay">Day</button>                 
                        <button type="button" class="btn btn-default" title = "Week" id="showWeek">Week</button> 
                        <button type="button" class="btn btn-default" title = "Month" id="showMonth">Month</button>
                        <button type="button" class="btn btn-default" title = "This Week" id="showThisWeek">This Week</button>
                        <button type="button" class="btn btn-primary" title = "This Month" id="showThisMonth">This Month</button>
                    </div>
               </div>
                <div  class="col-sm-12 col-md-2 text-right">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
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
  
        <div id="scheduledGrid">
            <div category-header="gridOptions"></div>
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-selection ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class= "gridwithselect1"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" style = "display:none">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->  
<script type ="text/javascript">
         var headercategory = [
            { name: 'Ref' , visible: true },
            { name: 'Site' , visible: true },
            /*{ name: 'January' , visible: true },
            { name: 'February' , visible: true },
            { name: 'March' , visible: true },
            { name: 'April' , visible: true },
            { name: 'May' , visible: true },
            { name: 'June' , visible: true },
            { name: 'July' , visible: true },
            { name: 'August' , visible: true },
            { name: 'September' , visible: true },
            { name: 'October' , visible: true },
            { name: 'November' , visible: true },
            { name: 'December' , visible: true }*/
        ];
</script>
    
 
