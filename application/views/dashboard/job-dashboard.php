<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <!-- <h3 class="box-title"><?php echo $page_title;?></h3>-->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="periods">Period</label>
                    <select id="periods" name="periods" class="form-control select2 chartfilter">
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
                    <select id="manager" name="manager" class="form-control select2 chartfilter" >
                        <?php if($this->session->userdata('raptor_role') == 'master' || count($contacts)>1) { ?>
                        <option value="">All Managers</option>
                        <?php } ?>
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
                    <label for="state">State</label>
                    <select id="state" name="state" class="form-control select2 chartfilter"  title="All" >
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
                    <label for="site">Sites</label>
                    <select id="site" name="site" class="form-control select2 chartfilter"  >
                        <?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
                        <option value="">All Sites</option>
                         <?php } ?>
                          <?php foreach($sites as $key=>$value) { 
                                 $selected = '';
                                 if(set_value('fsites') == $value['id']) {
                                     $selected = 'selected';
                                 }
                           ?>
                        <option value="<?php echo $value['id'];?>" <?php echo $selected;?>><?php echo $value['name'];?></option> 
                       <?php } ?>
                    </select>
                </div>
              
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="form-group">
                    <label for="search">&nbsp;</label>
                    <div>
                    <button id="searchfilter" class="btn btn-primary btn-flat" type="button">Refresh</button>
                    <button id="resetfilter" class="btn btn-warning  btn-flat" type="button">Reset</button>
                    </div>
                </div>
            </div>
            
      </div>
    </div>
</div><!-- /.box -->
<div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title  text-green">Job Stage</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" id="jobstagecollapse" data-widget ="collapse"><i class="fa fa-minus"></i></button>
       
      </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="chart" id ="jobstageChart" ></div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
<div class="box box-warning">
    <div class="box-header with-border">
      <h3 class="box-title text-orange">Job Completion</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" id="jobcompletioncollapse" data-widget ="collapse"><i class="fa fa-minus"></i></button>
       
      </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="chart"  id ="jobcompletionChart"></div>
            </div>
         </div>
    </div><!-- /.box-body -->
</div><!-- /.box --> 
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title text-red">Job Attendance</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" id="jobattendancescollapse" data-widget ="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="chart"  id ="jobattendancesChart"></div>
            </div>
         </div>
    </div><!-- /.box-body -->
</div><!-- /.box --> 

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">Job Counts</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool"  id="jobtrendcollapse" data-widget ="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="chart" id ="jobtrendChart"></div>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box --> 
<?php if(isset($dcfm_client_iframe_url) && $dcfm_client_iframe_url !='') {?>
<iframe class="iframe" style="display: none;" src="<?php echo $dcfm_client_iframe_url ?>" id="iframe1" onLoad="autoResize('iframe1');"></iframe>
<?php } ?>