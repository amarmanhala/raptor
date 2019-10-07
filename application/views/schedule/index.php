<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">Schedule</h3>
    </div>
    <div class= "box-header  with-border"> 
        <div class="row">
            <div class="col-sm-5 col-md-4" >
                <div id ="datepickerinline"></div>
            </div>
            <div class="col-sm-7 col-md-8" >
                <div class="clearfix">
            <?php foreach ($schedule_items as $key=> $value) {
                    if (trim($value['colour']) == "") {
                        $value['colour'] == 'white';
                    }
                    if (trim($value['captioncolour']) == "") {
                        $value['captioncolour'] == 'black';
                    } ?>
                        <div class="col-xs-4 col-sm-3 col-md-2 no-padding" style ="margin-right:13px;"><div class="box text-center" style ="vertical-align:middel;min-height:85px;font-size:13px;background-color:<?php echo $value['colour'];?>;color:<?php echo $value['captioncolour'];?>;"><?php echo $value['caption1'].'<br/>'.$value['caption2'];?></div></div>
            <?php  } ?>
                </div>   
            </div>
        </div>
    </div>
    <div class= "box-header  with-border"> 
        <form role ="form" name ="calendarform" id ="calendarform">
        <div class="row">
            <div class= "col-sm-4 col-md-3">
                
            </div>
            <div class= "col-sm-4 col-md-3">
                <label class= "control-label">Select Technician</label>
                <select class="form-control selectpicker"  data-live-search= "TRUE" id ="technicion" name ="technicion">
                    <option value ="">-Select-</option>
                    <?php
                     foreach ($technicians as $value) {
                         $selected = '';
                         if (count($schedulelayout)>0 && $schedulelayout['singletech'] == $value['contactid']) {
                             $selected = 'selected';
                         }  ?>
                     <option value ="<?php echo $value['contactid'];?>" <?php echo $selected;?>><?php echo $value['contactname'];?></option>
                    <?php  } ?>
                </select>
            </div>
            <div class= "col-sm-6 col-md-7">
                <label class= "control-label">Show Technicians</label>
                <select class= "form-control selectpicker" id ="technicians" name ="technicians"  multiple data-live-search= "TRUE" title = "Show Technicians" data-size = "auto" data-width= "100%" >
                    <?php  $multitech = '';
                    foreach ($technicians as $value) {
                        $selected = '';
                        if (count($schedulelayout)>0 && (count($schedulelayout['multitech'])>0 && in_array($value['contactid'], $schedulelayout['multitech']))) {
                            $selected = 'selected';
                            $multitech = implode(',', $schedulelayout['multitech']);
                        }  ?>
                        <option value ="<?php echo $value['contactid'];?>" <?php echo $selected;?>><?php echo $value['contactname'];?></option>
                <?php   } ?>
 
                </select>
            </div>
            <div class= "col-sm-2 col-md-2">
                <label class= "control-label">Time slot</label>
                <?php   $calenderview = '';
                        if (count($schedulelayout)>0) { 
                            $calenderview = $schedulelayout['view'];
                        }
                        if($calenderview == '') {
                            $calenderview = 'month';
                        }
                           
                           ?>
                <select class="form-control" id ="timeslot" name ="timeslot">
                    <option value ="00:30:00">-Select-</option>
                    <option value ="00:01:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:01:00") {echo " selected";}?>>1 min</option>
                    <option value ="00:02:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:02:00") {echo " selected";}?>>2 mins</option>
                    <option value ="00:05:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:05:00") {echo " selected";}?>>5 mins</option>
                    <option value ="00:10:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:10:00") {echo " selected";}?>>10 mins</option>
                    <option value ="00:15:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:15:00") {echo " selected";}?>>15 mins</option>
                    <option value ="00:30:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:30:00") {echo " selected";}?>>30 mins</option>
                    <option value ="00:60:00"<?php if (count($schedulelayout)>0 && $schedulelayout['timeslot'] == "00:60:00") {echo " selected";}?>>60 mins</option>
                </select>
            </div>
        </div>
            <input type ="hidden" name ="jobid" id ="jobid" value ="<?php echo $job['jobid'];?>" />
            <input type ="hidden" name ="jobstatusid" id ="jobstatusid" value ="<?php echo $job['statusid'];?>" />
            <input type ="hidden" name ="jobstatus" id ="jobstatus" value ="<?php echo $job['stage'];?>" />
            <input type ="hidden" name ="jobnumber" id ="jobnumber" value ="<?php echo $job['jobnumber'] == NULL ? $job['jobid']:$job['jobnumber'];?>" />
            <input type ="hidden" name ="calenderview" id ="calenderview" value ="<?php echo $calenderview;?>" />
            <input type ="hidden" name ="internaltask" id ="internaltask" value ="<?php echo $createinternaltask;?>" />
                 
        </form>
    </div>
    <div class="box-body">
        <!-- THE CALENDAR -->
        <div id ="calendar"></div>
    </div><!-- /.box-body -->
    <div id ="scheduleinnerloading" class="overlay" style ="display:none;"><!--  class="overlay" -->
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <div id ="scheduleouterloading" class="overlay" style ="display:none;">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div><!-- /.box -->
<div class="modal fade" id ="editAppointModal" tabindex="-1" role ="dialog" aria-labelledby="editAppointModalLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog modal-lg" role ="document">
    <div class="modal-content">
	<center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
        <form name ="editappoint_form" id ="editappoint_form" class="form-horizontal" method ="post" style ="display:none;">
            <div class="modal-header">
              <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id ="editAppointModalLabel">Edit Task</h4>
            </div>
            <div class="modal-body">
                 <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Status:</label>
                    <div class="col-sm-2" style ="padding-right:0"><!-- fa fa-fw fa-stop-->
                        <a href="javascript:void(0);" class="pause" title ="Pause"><i class="fa fa-fw fa-pause"></i></a>
                        <a href="javascript:void(0);" class="play active" title ="Start"><i class="fa fa-fw fa-play-circle"></i></a>
                        <a href="javascript:void(0);" class="stop" style ="display:none;" title ="Stop"><i class="fa fa-fw fa-stop"></i></a>
                         
                        <label id ="status" class="control-label"></label>
                    </div>
                    <label for="name" class="control-label col-sm-1">Invite:</label>
                    <div class="col-sm-3">
                        <select class="form-control" name ="technician" id ="technician">
                            <option value ="">Select Technician</option>
                         </select>
                    </div>
                    <!--  fa-unlock-alt   -->
                    <label for="name" class="control-label col-sm-2">Locked: <a href="javascript:void(0);" id ="lockicon"><i  class="fa fa-fw fa-lock" style ="color:#000;"></i></a></label>
                    <label for="name" class="control-label col-sm-2">Complete: <input id ="checkboxcomplete" type ="checkbox" value ="1" /></label>
                </div>
                <hr />
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Customer:</label>
                    <div class="col-sm-4">
                        <input class="form-control" type ="text" readonly="readonly" id ="customer" />
                     </div>
                    <label for="name" class="control-label col-sm-2">Contact:</label>
                    <div class="col-sm-4">
                       <input class="form-control" type ="text" readonly="readonly" id ="contact" />
                     </div>
                        
                </div>
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Address:</label>
                    <div class="col-sm-4">
                       <input class="form-control" type ="text" readonly="readonly" id ="address1" />
                     </div>
                    <label for="name" class="control-label col-sm-2">Phone:</label>
                    <div class="col-sm-4">
                        <input class="form-control" type ="text" readonly="readonly" id ="phone" />
                     </div>
                        
                </div>
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">&nbsp;</label>
                    <div class="col-sm-4">
                       <input class="form-control" type ="text" readonly="readonly" id ="address2" />
                     </div>
                    <label for="name" class="control-label col-sm-2">Job No:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type ="hidden" readonly="readonly" id ="jobid" />
                        <input class="form-control" type ="text" readonly="readonly" id ="jobnumber" />
                     </div>
                        
                </div>
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">&nbsp;</label>
                    <div class="col-sm-2 ">
                        <input class="form-control" type ="text" readonly="readonly" id ="jobsuburb" />
                     </div>
                    <div class="col-sm-1">
                        <input class="form-control" type ="text" readonly="readonly" id ="jobstate" />
                     </div>
                    <div class="col-sm-1 " >
                        <input class="form-control" type ="text" readonly="readonly"  id ="jobpostcode" />
                     </div>
                    <label for="name" class="control-label col-sm-2">DCFM Job id:</label>
                    <div class="col-sm-3">
                       <input class="form-control" type ="text" readonly="readonly" id ="dcfmjobid" />
                     </div>
                </div>
                 <hr />
                 <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Date:</label>
                    <div class="col-sm-3">
                       <input class="form-control" type ="text" readonly="readonly" id ="date" />
                     </div>
                    <label for="name" class="control-label col-sm-1 custom">Start:</label>
                    <div class="col-sm-2">
                       <input class="form-control" type ="text" readonly="readonly" id ="start" />
                     </div>
                    <label for="name" class="control-label col-sm-1">Activity:</label>
                    <div class="col-sm-3">
                        <select class="form-control" id ="activity">
                          <?php
                          foreach ($etp_activity as $value) {
                          ?>
                          <option value ="<?php echo $value['id'];?>"><?php echo $value['activity'];?></option>
                          <?php    
                          }
                          ?>
                        </select>
                     </div>
                </div>
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Technician:</label>
                    <div class="col-sm-3">
                        <input class="form-control" type ="text" readonly="readonly" id ="technician" />
                     </div>
                    <label for="name" class="control-label col-sm-1 custom">Duration(h):</label>
                    <div class="col-sm-2">
                       <input class="form-control" type ="text" readonly="readonly" id ="duration" />
                     </div>
                    <label for="name" class="control-label col-sm-1">Appt ID:</label>
                    <div class="col-sm-2">
                       <input class="form-control" type ="text" readonly="readonly" id ="apptid" />
                     </div>
                </div>
                  <hr />
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Description:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="4" id ="description" readonly="readonly"></textarea>
                     </div>
                </div>
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Lock Reason:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="4" id ="lockreason" readonly="readonly"></textarea>
                     </div>
                </div>
                 
            </div>
            <div class="modal-footer">
                <input  type ="hidden" name ="editappoint" value ="editappoint" />
                <input  type ="hidden" id ="happtid" name ="happtid" value ="" />
                <input  type ="hidden" id ="hprogress" name ="hprogress" value ="" />
                <input  type ="hidden" id ="hcomplete" name ="hcomplete" value ="" />
                <input  type ="hidden" id ="hstatus" name ="hstatus" value ="" />
                <input  type ="hidden" id ="hlock" name ="hlock" value ="" />
                <button type ="button" class="btn btn-danger" id ="deleteappoint">Delete</button>
                <button type ="button" class="btn btn-default" id ="cancel">Close</button>
            </div>
	  
	</form>
    </div>
  </div>
</div>
  
<div class="modal fade" id ="inviteAppointModal" tabindex="-1" role ="dialog" aria-labelledby="inviteAppointModalLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog" role ="document">
    <div class="modal-content">
	<center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
        <form name ="inviteappoint_form" id ="inviteappoint_form" class="form-horizontal" method ="post" style ="display:none;">
            <div class="modal-header">
               <h4 class="modal-title" id ="inviteAppointModalLabel">Invite Appointment</h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Date:</label>
                    <div class="col-sm-4">
                        <input class="form-control datepicker" type ="text" readonly="readonly" id ="date" />
                     </div>
                </div>
                 
            </div>
            <div class="modal-footer">
                <input  type ="hidden" id ="happtid" name ="happtid" value ="" />
                <input  type ="hidden" id ="technician" name ="technician" value ="" />
                <button type ="button" class="btn btn-success" id ="modalsave" data-loading-text ="Saving...">Yes</button>
                <button type ="button" class="btn btn-default" id ="cancel" data-loading-text ="Cancel">Cancel</button>
            </div>
            
	</form>
    </div>
  </div>
</div> 
  
<div class="modal fade" id ="stopAppointModal" tabindex="-1" role ="dialog" aria-labelledby="stopAppointModalLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog" role ="document">
    <div class="modal-content">
	<center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
        <form name ="stopappoint_form" id ="stopappoint_form" class="form-horizontal" method ="post" style ="display:none;">
            <div class="modal-header">
               <h4 class="modal-title" id ="stopAppointModalLabel">Stop Task</h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <label for="name" class="col-sm-3">Start time:</label>
                    <div class="col-sm-3" id ="starttime">

                     </div>
                    <div class="col-sm-3">
                        <a href="javascript:void(0);" id ="adjust" ><span class="glyphicon glyphicon-wrench"></span></a>
                     </div>
                    
                    
                </div>
                <div class="form-group ">
                    <label for="name" class="col-sm-3">End time:</label>
                    <div class="col-sm-9" id ="endtime">

                     </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-3">Duration:</label>
                    <div class="col-sm-9" id ="duration">

                     </div>
                   
                </div>
                <div class="form-group ">
                    <div class="col-sm-6">
                        <div class="radio">
                            <div>
                                <label>
                                    <input type ="radio" name ="closeaction" id ="closeaction1" value ="close" checked ="checked">
                                    Close this task only
                                </label>
                            </div>
                      </div>
                        <div class="radio">
                            <div>
                                <label>
                                    <input type ="radio" name ="closeaction" id ="closeaction2" value ="reallocate">
                                    Allocate to another technician

                                </label>
                            </div>
<!--                            <div class="col-sm-4" style ="display:none;">
                              <select class="form-control" name ="stoptech" id ="stoptech">
                                <option value ="">Select technicians</option>
                             </select>
                            </div>-->
                       </div>
                        
                        <div class="radio">
                            <div>
                        <label>
                            <input type ="radio" name ="closeaction" id ="closeaction3" value ="complete">
                            Job fully completed
                        </label>
                            </div>
                      </div>
                     </div>
                    <div class="col-sm-4" style ="padding-top:25px;">
                        <div style ="display:none;">
                              <select class="form-control" name ="stoptech" id ="stoptech">
                                <option value ="">Select technicians</option>
                             </select>
                            </div>
                    </div>
                 </div>
                <div class="form-group">
                    <label for="name" class="control-label col-sm-3">Notes:</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="5" name ="notes" id ="notes"></textarea>
                    </div>
                </div>
                <div class="status"></div>
                  
            </div>
            <div class="modal-footer">
                <input  type ="hidden" id ="happtid" name ="happtid" value ="" />
                <input  type ="hidden" id ="hadjust" name ="hadjust" value ="0" />
                <button type ="button" class="btn btn-success" id ="modalsave" data-loading-text ="Processing...">OK</button>
                <button type ="button" class="btn btn-default" id ="cancel" data-loading-text ="Cancel">Cancel</button>
            </div>
            
	</form>
    </div>
  </div>
</div>  
  
<div class="modal fade" id ="lockAppointModal" tabindex="-1" role ="dialog" aria-labelledby="lockAppointModalLabel" data-backdrop="static" data-keyboard ="false">
    <div class="modal-dialog" role ="document">
    <div class="modal-content">
	<center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
        <form name ="lockappoint_form" id ="lockappoint_form" class="form-horizontal" method ="post" style ="display:none;">
            <div class="modal-header">
               <h4 class="modal-title" id ="lockAppointModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="form-group ">
                    <label for="name" class="control-label col-sm-2">Reason:</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="4" id ="reason"></textarea>
                     </div>
                </div>
                 
            </div>
            <div class="modal-footer">
                <input  type ="hidden" id ="happtid" name ="happtid" value ="" />
                <input  type ="hidden" id ="hlock" name ="hlock" value ="" />
                <button type ="button" class="btn btn-success" id ="modalsave" data-loading-text ="Processing...">Ok</button>
                <button type ="button" class="btn btn-default" id ="cancel" data-loading-text ="Cancel">Cancel</button>
            </div>
            
	</form>
    </div>
  </div>
</div>
  
<div class="modal fade" id ="timeSheetModal" tabindex="-1" role ="dialog" aria-labelledby="timeSheetModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-lg" role ="document">
    <div class="modal-content">
	<center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
        <form name ="timesheet_form" id ="timesheet_form" class="form-horizontal" method ="post" style ="display:none;">
            <div class="modal-header">
                <div class="pull-right"><button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
               <h4 class="modal-title" id ="timeSheetModalLabel">Time Sheet</h4>
            </div>
            <div class="modal-body">
                <a title ="Export To Excel" id ="exporttimesheet" class="btn btn-success btn-sm" href="javascript:void(0);"><i class="fa fa-file-excel-o"></i></a>
                <table class="table table-bordered">
                 <thead>    
                    <tr>
                      <th>Date</th>
                      <th>Appt Id</th>
                      <th>Job</th>
                      <th>Suburb</th>
                      <th>Start Time</th>
                      <th>Duration (h)</th>
                  
                      <th style ="width: 200px">Job Description</th>
                    </tr>
                 </thead>
                 <tbody id ="timesheettbl">
                 </tbody> 
                </table>
            </div>
            <div class="modal-footer">
               <button type ="button" class="btn btn-default" id ="cancel" data-loading-text ="Cancel">Close</button>
            </div>
	</form>
    </div>
  </div>
</div>  
  
<div class="modal fade" id ="addInternalJobModal" tabindex="-1" role ="dialog" aria-labelledby="addInternalJobModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
	<center style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
        <form name ="addinternaljob_form" id ="addinternaljob_form" class="form-horizontal" method ="post">
      <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id ="addInternalJobModalLabel">Add internal job task</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Technician:</label>
              <div class="col-sm-4">
                  <select class="form-control" name ="technician" id ="technician">
                      <option value ="">Select..</option>
                      <?php
                         foreach ($technicians as $value) {
                             echo '<option value ="'.$value['contactid'].'">'.$value['firstname'].'</option>';   
                        }
                      ?>
                  </select>
               </div>
        </div>
        <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Job Id:</label>
              <div class="col-sm-4">
                  <select class="form-control" name ="jobid" id ="jobid">
                      <option value ="">Select..</option>
                      <?php
                         foreach ($internaljobs as $value) {
                            $job = $value['custordref'].':'.$value['custordref2'];
                            $jobnumber= $value['jobnumber'] == NULL ? $job:$value['jobnumber'];
                            echo '<option value ="'.$value['jobid'].'">'.$jobnumber.'</option>';   
                         }
                      ?>   
                  </select>
               </div>
        </div>
        <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Activity:</label>
              <div class="col-sm-4">
                  <select class="form-control" name ="activity" id ="activity">
                      <option value ="">Select..</option>
                      <?php
                         foreach ($etp_activity as $value) {
                             if ($value['is_billable'] == '0') {
                                 echo '<option value ="'.$value['id'].'">'.$value['activity'].'</option>';   
                             }
                        }
                      ?>
                  </select>
               </div>
        </div>  
          
       <div class="form-group">
            <label for="name" class="col-sm-3 control-label">Date:</label>
             <div class="col-sm-4">
                    <div class="input-group">
                        <input type ="text" class="form-control datepicker" id ="date" name ="date" readonly="readonly" placeholder="dd/mm/yyyy" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
               </div>
        </div>   
       <div class="form-group">
           <label for="name" class="col-sm-3 control-label">Start Time:</label>
           <div class="col-sm-2">
               <input type ="text" class="form-control" id ="starttime" name ="starttime" readonly="readonly" />
           </div>
        </div>
       <div class="form-group">
           <label for="name" class="col-sm-3 control-label">Duration (h):</label>
           <div class="col-sm-2">
               <input type ="text" class="form-control allownumericwithoutdecimal" id ="duration" name ="duration" value ="1" readonly="readonly" />
           </div>
        </div> 
        <div class="form-group">
              <label for="name" class="col-sm-3 control-label">Description:</label>
              <div class="col-sm-9">
                  <textarea class="form-control" id ="description" name ="description" rows="4" placeholder="Description"></textarea>
               </div>
        </div>  
          
      </div>
            <div class="modal-footer">
                <input type ="hidden" name ="ilabelid" id ="ilabelid" value ="<?php if (count($internaljobs)>0) echo $internaljobs[0]['labelid'];?>" />    
                <button type ="submit" name ="modalsave" id ="modalsave" class="btn btn-primary" data-loading-text ="Saving...">Create Task</button>
                  &nbsp;&nbsp;<button type ="button" name ="cancel" id ="cancel" class="btn btn-default" data-loading-text ="Cancel">Cancel</button>
            </div>
            
  </form>
    </div>
  </div>
</div>

<?php
$calendermultitech = array();
$calendermulti = array();

foreach ($technicians as $value) {
    
    $calendermultitech[] = '<div class="btn-group-vertical"><button type="button" data-id="'.$value['contactid'].'" data-name= "'.$value['firstname'].'" class="btn btn-default timesheetbtn" style="margin-bottom:2px;">Time sheet</button><button type="button" data-id="'.$value['contactid'].'" data-name= "'.$value['firstname'].'" class="btn btn-primary calendersortbtn" style="margin-bottom:2px;">Sort</button><button type="button" data-id="'.$value['contactid'].'" data-name= "'.$value['firstname'].'" class="btn btn-flat btn-warning bumpschedule">Bump</button></div><br/>'.$value['firstname'];
    $calendermulti[] = "'".$value['firstname']."'";

    
} ?>
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
 