<div class="box"  >
    <div class="box-header with-border">
        <h3 class="box-title">Tech Schedule</h3>
        
    </div>
    <div class= "box-header  with-border"> 
        <form role ="form" name ="calendarform" id ="calendarform">
            <div class="row"> 
                <div class="col-sm-6 col-md-2">
                <div class="form-group">
                    <label for="state">State</label>
                    <select id="state" name="state" class="form-control selectpicker"  multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" >
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
                    <select id="site" name="site" class="form-control selectpicker"    multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" >
                        <?php foreach($sites as $key=>$value) { 
                                $selected = '';
                                if(set_value('site') == $value['id']) {
                                    $selected = 'selected';
                                }
                          ?>
                        <option value="<?php echo $value['id'];?>" <?php echo $selected;?>><?php echo $value['name'];?></option> 
                        <?php } ?>
                    </select>
                </div>
              
            </div>
            <div class="col-sm-6 col-md-2">
               <div class="form-group">
                    <label for="site">Contract</label>
                    <select id="contract" name="contract" class="form-control selectpicker"    multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" >
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
                
                <div class= "col-sm-4 col-md-3">
                    <label class= "control-label">Technicians</label>
                    <select class="form-control selectpicker"  data-live-search= "TRUE" id ="technicians" name ="technicians"   multiple data-live-search= "TRUE" title = "All" data-size = "auto" data-width= "100%" >
                        <?php foreach ($technicians as $value) {
                            $selected = '';
                             ?>
                        <option value ="<?php echo $value['userid'];?>" <?php echo $selected;?>><?php echo $value['userid'];?></option>
                        <?php   } ?>
                    </select>
                </div>
                 
                <div class= "col-sm-2 col-md-2">
                    <label class= "control-label">Time slot</label>
                    
                    <select class="form-control" id ="timeslot" name ="timeslot">
                        <option value ="00:30:00">-Select-</option>
                        <option value ="00:01:00" >1 min</option>
                        <option value ="00:02:00">2 mins</option>
                        <option value ="00:05:00">5 mins</option>
                        <option value ="00:10:00">10 mins</option>
                        <option value ="00:15:00" selected="selected">15 mins</option>
                        <option value ="00:30:00">30 mins</option>
                        <option value ="00:60:00">60 mins</option>
                    </select>
                </div>
            </div>
             <input type ="hidden" name ="calenderview" id ="calenderview" value ="month" />
         </form>
    </div>
    <div class="box-body">
        <!-- THE CALENDAR -->
        <div id ="calendar"></div>
    </div><!-- /.box-body -->
    <div id ="scheduleinnerloading" class="overlay" style ="display:none;"> 
        <!--  class="overlay" -->
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <div id ="scheduleouterloading" class="overlay" style ="display:none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div><!-- /.box -->
 
<div class="modal fade" id ="timeSheetModal" tabindex="-1" role ="dialog" aria-labelledby="timeSheetModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg" role ="document">
        <div class="modal-content">
            <center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <form name ="timesheet_form" id ="timesheet_form" class="form-horizontal" method ="post" style ="display:none;">
                <div class="modal-header">
                    <div class="pull-right">
                        <button type ="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <h4 class="modal-title" id ="timeSheetModalLabel">Time Sheet</h4>
                </div>
                <div class="modal-body">
                    <a title ="Export To Excel" id ="exporttimesheet" class="btn btn-success btn-md" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i></a>
                    <table class="table table-bordered">
                        <thead>    
                            <tr>
                                <th>Date</th>
                                <th>Appt Id</th>
                                <th>Job</th>
                                <th><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?></th>
                                <th>Suburb</th>
                                <th>Start Time</th>
                                <th>Duration (h)</th> 
                            </tr>
                        </thead>
                        <tbody id ="timesheettbl"></tbody> 
                    </table>
                </div>
                <div class="modal-footer">
                   <button type ="button" class="btn btn-default" id ="cancel" data-loading-text ="Cancel">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>  
<?php
    $calendermultitech = array();
    $calendermulti = array();

    foreach ($technicians as $value) {
        $calendermultitech[] = 
            '<div class="btn-group">' .
                '<button type="button" title="Time Sheet" data-id="' . $value['userid'] . '" data-name= "' . $value['userid'] . '" class="btn btn-success timesheetbtn">' .
                    '<i class="fa fa-file-excel-o"></i>' . 
                '</button>' .
            '</div>' .
            '<br/>' . 
            $value['userid'];
        $calendermulti[] = "'" . $value['userid'] . "'";
    }
?>
<script type ="text/javascript">
    var calenderdate = '';
    <?php if (isset($calenderdate)) { ?>
        calenderdate = "<?php echo $calenderdate;?>";
    <?php } ?>
    var calendermultitech = <?php echo json_encode($calendermultitech);?>;
    var calendermulti = Array(<?php echo implode(', ', $calendermulti);?>);
    var createcalenderevent = 0;
    var createinternaltask = 0;
    <?php if (isset($createcalenderevent)) { ?>
        createcalenderevent = <?php echo $createcalenderevent;?>;
        createinternaltask = <?php echo $createinternaltask;?>;
    <?php } ?> 
</script>
 