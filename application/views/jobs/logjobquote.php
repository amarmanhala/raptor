<!-- Default box -->
<div class="box">
 
    <div class="box-body">
        
       <p>  <?php 
                if($this->session->flashdata('error')) 
                {
                        echo '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-ban"></i> '.$this->session->flashdata('error').'</div>';	
               }
                   if($this->session->flashdata('success')) 
                {
                        echo '<div class="alert alert-success  alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="icon fa fa-check"></i> '.$this->session->flashdata('success').'</div>';	
               }
        ?>	</p>
        <div class="col-md-12">
            <form id="validation-form" name="newJobForm" action="" method="post" class="form-horizontal" >
                  <?php 
                  dynamicform_elements($fields);
                 ?>
                
          <div class="form-group">
              <label for="input" class="col-sm-2 control-label">&nbsp;</label>
               <div class="col-sm-10">
             <button type="submit" class="btn btn-primary  btn-flat">Save</button>
               </div>
          </div>  
                
	</form>
        </div>
    </div><!-- /.box-body -->
    
</div><!-- /.box -->
<div class="modal fade" id="isitelookup" tabindex="-1" role="dialog" aria-labelledby="isitelookupModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
	

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Site Lookup</h4><span>Select a site by Clicking on it</span>
      </div>
      <div class="modal-body">
		 <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                 <div id="sitegriddiv"> 
                     <input type="hidden" name="show_custom_attributes" id="show_custom_attributes" value="<?php echo (isset($ContactRules["show_custom_attributes_in_address_list"]) && $ContactRules["show_custom_attributes_in_address_list"] == "1") ? 'yes' : 'no'; ?>">
            <table class="table table-striped table-bordered table-condensed table-hover">
	<thead>    
	<tr class="theader">
		<th class="col-md-2">Company Name</th>
                <?php  
                    if ((isset($ContactRules["show_custom_attributes_in_address_list"]) && $ContactRules["show_custom_attributes_in_address_list"] == "1")){ 
                       ?> 
                          <th class="col-md-1">BE</th>
		<th class="col-md-1">BU</th>
		<th class="col-md-1">Site Ref</th>
                 
               <?php } ?>
                <th class="col-md-1">Street</th>
		<th class="col-md-1">Suburb</th>
		<th class="col-md-1">State</th>
		<th class="col-md-1">Post Code</th>
		<th class="col-md-1">FM-Email</th>
		<th class="col-md-1">FM</th>
                <th class="col-md-1">Site Phone</th>
	</tr>    
	</thead>
	<tbody  id="sitegridbody">
		 
	</tbody>
	</table>
                     </div>    
      </div>
      <div class="modal-footer">
          <button type="button" id="modalok"  class="btn btn-primary" data-loading-text="Ok">OK</button>
      </div>
	  
	 
    </div>
  </div>
</div>
      
<div class="modal fade" id="attend" tabindex="-1" role="dialog" aria-labelledby="attendModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
 
	 
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Select Attendance Date Time</h4>
      </div>
      <div class="modal-body form-horizontal">
		   
		<div class="form-group" >
                    <label for="recipient-name" class="col-sm-3" class="control-label">Attendance Date:</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control datepicker" name="attendancedate" id="attendancedate" readonly="readonly"   />
                        </div>
                        <span class="help-block with-errors"></span>
                    </div>
                </div>
                <div class="form-group" >
                    <label for="recipient-name" class="col-sm-3" class="control-label">Attendance Time:</label>
                    <div class="col-sm-4">
                        <div class="bootstrap-timepicker">
                      
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <input type="text" class="form-control timepicker" placeholder="" id="attendancetime" name="attendancetime" value=""   readonly="readonly"   />
                            </div>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                 <div class="form-group" >
                    <label for="recipient-name" class="col-sm-3" class="control-label">&nbsp;</label>
                    <div class="col-sm-4">
                          <div class="checkbox">
                                <label><input class="" type="checkbox" id="mustattend" name="mustattend" value="1"  >Must attend at this time.</label>
                            </div>
                    </div>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" name="timebtnok" id="timebtnok" class="btn btn-primary" data-loading-text="Saving...">Ok</button>
         <button type="button" name="timebtncancel" id="timebtncancel" class="btn" data-loading-text="Cancel...">Cancel</button>
      </div>
	  
    </div>
  </div>
</div>
<?php if(isset($ContactRules["custordref1_from_customerpo"]) && $ContactRules["custordref1_from_customerpo"] == "1"){ ?>
<div class="modal fade" id="customerPOModal" tabindex="-1" role="dialog" aria-labelledby="customerPOModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Customer PO Summary</h4>
        </div>
        <div class="modal-body">
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>    
                    <tr class="theader">
                        <th class="col-md-2">PO Number</th>
                        <th class="col-md-1">Date</th>
                        <th class="col-md-1 text-right">Value</th>
                        <th class="col-md-1 text-right">Remaining</th>
                    </tr>    
                </thead>
        	<tbody  >
		   <?php  foreach ($poData as $key => $value) { ?>
                     <tr >
                        <td class="col-md-2"><?php echo $value['ponumber']; ?></td>
                        <td class="col-md-1"><?php echo format_date($value['fromdate']); ?></td>
                        <td class="col-md-1 text-right"><?php echo format_amount($value['amount_ex_tax']); ?></td>
                        <td class="col-md-1 text-right"><?php echo format_amount($value['amount_remaining']); ?></td>
                    </tr>             
                    
                    <?php  } ?>
                </tbody>
            </table>   
        </div>
        <div class="modal-footer">
            <button type="button" id="modalok"  class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
        </div>
    </div>
  </div>
</div>
<?php } ?>
<script>
var prioritydata = <?php echo json_encode($priority); ?>;
</script>
