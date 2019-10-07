<!-- Default box -->
<div class="box" id="techmapdiv">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">Tech Map </h3>
    </div>
    <div class="box-header with-border">
        <form id="techmap" name="techmap" method="get">
            
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <label class= "control-label">Status</label>
                    <select id="status" name="status" class="form-control select2" >
                        <option value="">Select Status</option>
                        <option value="Unsheduled" <?php if('Unsheduled' == $this->input->get('status') ){ echo 'selected = "selected"'; } ?>>UNSCHEDULED</option>
                        <option value="Scheduled" <?php if('Scheduled' == $this->input->get('status') ){ echo 'selected = "selected"'; } ?>>SCHEDULED</option>-->
                        <option value="In Progress" <?php if('In Progress' == $this->input->get('status') ){ echo 'selected = "selected"'; } ?>>IN PROGRESS</option>  
                        <option value="Completed" <?php if('Completed' == $this->input->get('status') ){ echo 'selected = "selected"'; } ?>>COMPLETED</option>  
                        <option value="Overdue" <?php if('Overdue' == $this->input->get('status') ){ echo 'selected = "selected"'; } ?>>OVERDUE</option> 
                        <option value="Cancelled" <?php if('Cancelled' == $this->input->get('status') ){ echo 'selected = "selected"'; } ?>>CANCELLED</option>  
                    <select>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class= "control-label">State</label>
                    <select id="fstate" name="fstate" class="form-control select2" >
                        <option value="">Select State</option>  
                        <?php foreach ($states as $state): ?>
                        <option value="<?php echo $state['abbreviation']; ?>" <?php if($state['abbreviation'] == $this->input->get('fstate') ){ echo 'selected = "selected"'; } ?> > <?php echo $state['abbreviation']; ?></option> 
                     <?php endforeach; ?>
                                           
                    <select>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class= "control-label">From Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="fromdate" name="fromdate" readonly="readonly" placeholder="From" value="<?php if($this->input->get('fromdate')){ echo $this->input->get('fromdate',TRUE);}?>">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class= "control-label">To Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" id="todate" name="todate" readonly="readonly" placeholder="To" value="<?php if($this->input->get('todate')){ echo $this->input->get('todate',TRUE);}?>">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                
              
            </div>
            <div class="row" style="margin-top: 10px;">
                <div class="col-sm-3">
                    <div class="checkbox icheck" style="display: inline;">
                        <label><input class="" type="checkbox"  value="1" id="showadhocjobs" name="showadhocjobs" <?php if($this->input->get('showadhocjobs')){ echo 'checked="checked"';}?> >&nbsp;Show Ad-hoc Jobs</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="checkbox icheck" style="display: inline;">
                        <label><input class="" type="checkbox"  value="1" id="showcontractjobs" name="showcontractjobs" <?php if($this->input->get('showcontractjobs')){ echo 'checked="checked"';}?>>&nbsp;Show Contract Jobs</label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="checkbox icheck" style="display: inline;">
                        <label><input class="" type="checkbox"  value="1" id="showtechnicians" name="showtechnicians" <?php if($this->input->get('showtechnicians')){ echo 'checked="checked"';}?> >&nbsp;Show Technicians</label>
                    </div>
                </div>
                <div class="col-sm-3 text-right"> 
                    <div class="text-right1">
                        <button type="button" class="btn btn-success" id="refresh" title="Refresh">Refresh</button>
                    </div>
                </div>
            </div>     
        </form>
     
    </div>
    
     
    <div class="box-body" style="height:570px;">
        <div id="myassetstatus"></div>   
         <?php 
 		if($this->session->flashdata('success')) 
 		{
         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
                }
                if($this->session->flashdata('error')) 
 		{
         	echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	
                }
	?>  
        <table id="budgetstbl" class="table table-bordered table-striped" style="margin-bottom:0px">
            <tr class="ccc-header">
            
                <td> <img src="<?php echo base_url().'assets/img/black.png';?>" >&nbsp;Unscheduled </td>
                <td> <img src="<?php echo base_url().'assets/img/green.png';?>" >&nbsp;Scheduled </td>
                <td> <img src="<?php echo base_url().'assets/img/teal.png';?>" >&nbsp;In Progress </td>
                <td> <img src="<?php echo base_url().'assets/img/blue.png';?>" >&nbsp;Completed </td>
                <td> <img src="<?php echo base_url().'assets/img/red.png';?>" >&nbsp;Over Due</td>
                <td> <img src="<?php echo base_url().'assets/img/grey.png';?>" >&nbsp;Cancelled</td>
            </tr>
    </table>
        <div class="map-wrapper" style="width:98%;height:500px;position:absolute;">    
            <!-- Maps DIV : you can move the code below to where you want the maps to be displayed -->
                <div id="MyGmaps" style="width:100%;height:100%; position:absolute;"></div>
            <!-- End of Maps DIV -->
        </div>
  
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay map-overlay">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
  </div><!-- /.box -->
   