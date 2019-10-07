<!-- Default box -->
<div ng-app="app" id="labourDashboardCtrl" ng-controller= "labourDashboardCtrl">
<div class="box">
    <div class="box-header with-border">
        <!-- <h3 class="box-title"><?php //echo $page_title;?></h3>-->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="periods">Period</label>
                    <select id="periods" name="periods" class="form-control select2">
                        <option value="" data-fromdate="<?php echo date(str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT));?>" data-todate="<?php echo date(RAPTOR_DISPLAY_DATEFORMAT);?>">Select Period</option>
                        <option value="custom">Custom</option>
                        <?php foreach($periods as $key=>$value) {
                            $selected='';
                            if(set_value('periods') == $key){
                                $selected ='selected';
                            } 
                            echo '<option value="'.$key.'" '. $selected .' data-fromdate="'.$value['fromdate'].'" data-todate="'.$value['todate'].'">'.$value['title'].'</option>';
                        } ?>
                    </select>
                </div>
                
            </div>
            <div class="col-sm-3 col-md-2 col-xs-6">
                    <label class= "control-label">From</label>
                   <div class="input-group">
                       <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" value="<?php echo date(str_replace('d', '01', RAPTOR_DISPLAY_DATEFORMAT));?>">
                       <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                       </div>
                   </div>
               </div>
               <div class="col-sm-3 col-md-2 col-xs-6">
                   <label class= "control-label">To</label>
                   <div class="input-group">
                       <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" value="<?php echo date(RAPTOR_DISPLAY_DATEFORMAT);?>">
                       <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                       </div>
                   </div>
               </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="contactid">Manager</label>
                    <select id="manager" name="manager" class="form-control select2">
                        <option value="">All</option>
                         <?php foreach($contacts as $key=>$value) { 
                                $selected = '';
                                if(set_value('manager') == $value['contactid']) {
                                    $selected = 'selected';
                                }
                          ?>
                        <option value="<?php echo $value['contactid'];?>" <?php echo $selected;?>><?php echo $value['firstname'];?></option> 
                        <?php } ?>
                    </select>
                </div>
                
            </div>
            <div class="col-sm-6 col-md-2">
                <div class="form-group">
                    <label>Technician</label>
                    <select id="technician" name="technician" class="form-control select2" title="All">
                        <option value="">All</option>
                    </select>
                </div>
                
            </div>
          
        </div>
        <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="state">State</label>
                    <select id="state" name="state" class="form-control select2"  title="All">
                        <option value="">All</option>
                        <?php foreach($states as $key=>$value) { 
                                $selected = '';
                                if(set_value('state') == $value['abbreviation']) {
                                    $selected = 'selected';
                                }
                                ?>
                                <option value="<?php echo $value['abbreviation'];?>" <?php echo $selected;?>><?php echo $value['abbreviation'];?></option> 
                            <?php } ?>
                    </select>
                </div>
		
            </div>
           <div class="col-sm-6 col-md-3">
               <div class="form-group">
                    <label for="site">Site</label>
                    <select id="site" name="site" class="form-control select2" title="All">
                        <option value="">All</option>
                    </select>
                </div>
              
            </div>
            <div class="col-sm-6 col-md-3">
               <div class="form-group">
                    <label for="site">Contract</label>
                    <select id="contract" name="contract" class="form-control select2" title="All">
                        <option value="">All</option>
                        <?php foreach($contracts as $key=>$value) { 
                                $selected = '';
                                if(set_value('contract') == $value['id']) {
                                    $selected = 'selected';
                                }
                          ?>
                        <option value="<?php echo $value['id'];?>" <?php echo $selected;?>><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                </div>
              
            </div>
<!--            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="search">&nbsp;</label>
                    <div>
                    <button id="searchfilter" class="btn btn-primary btn-flat" type="button">Refresh</button>
                    <button id="resetfilter" class="btn btn-warning  btn-flat" type="button">Reset</button>
                    </div>
                </div>
            </div>-->
            <div  class="col-sm-6 col-md-1">
                <label class="control-label">&nbsp;</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                        <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshFilter()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                        <button type = "button" class= "btn btn-success" ng-click="exportToExcel()" title = "Export To Excel"><i class= "fa fa-file-excel-o"></i></button>
                    </span>
                </div>
            </div>
      </div>
        <div class="row">
            <div class="col-sm-3 col-md-2">
                <div class="form-group">
                    <label>Grouping</label>
                </div>
                
            </div>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input type="radio" name="groupby" value="bysite" checked="checked">&nbsp;&nbsp;By Site&nbsp;&nbsp;
                    <input type="radio" name="groupby" value="bytech">&nbsp;&nbsp;By Technician&nbsp;&nbsp;
                    <input type="radio" name="groupby" value="byjob">&nbsp;&nbsp;By Job
                </div>
                
            </div>
        </div>
    </div>
</div><!-- /.box -->
<div class="box box-success">
    <div class="box-header">
<!--      <h3 class="box-title  text-green">Job Stage</h3>-->
<!--      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" id="jobstagecollapse" data-widget ="collapse"><i class="fa fa-minus"></i></button>
       
      </div>-->
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <select id="chartfilter" name="chartfilter" class="form-control">
                        <option value="lbourhr">Labour (hr)</option>
                        <option value="lbour">Labour ($)</option>
                        <option value="material">Material ($)</option>
                    </select>
                </div>
                <div class="chart" id ="jobstageChart"></div>
            </div>
            <div class="col-sm-9">
                <div id="labourGrid">
                    <div ui-grid = "labourGrid" ui-grid-pagination ui-grid-auto-resize ui-grid-pinning ui-grid-resize-columns class= "grid"></div>
                </div>
            </div>
        </div>
    </div><!-- /.box-body -->
    <div class= "overlay" ng-show="overlay">
        <i class= "fa fa-refresh fa-spin"></i>
    </div>
</div><!-- /.box -->
</div>