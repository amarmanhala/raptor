<!-- Default box -->
<div class= "box" >
    <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Job - Edit Report</h3>
       
    </div>
    <div class= "box-header  with-border"> 
            <div class= "row">
                
                <div class= "col-sm-6 col-md-3">
                    <label class= "control-label">Job ID</label>
                    <?php $this->load->view('editablereport/formelement_jobidselect'); ?>
                </div>
                <div class= "col-sm-6 col-md-3">
                    <label class= "control-label">Report</label>
                   <?php $this->load->view('editablereport/formelement_reportselect'); ?>
                </div>
                <div class= "col-sm-6 col-md-3">
                    <label class= "control-label">Area</label>
                    <?php $this->load->view('editablereport/formelement_reportareaselect'); ?>
                </div>
                <div class= "col-sm-6 col-md-3">
                    <label class= "control-label">&nbsp;</label>
                    <div>
                         <button id="preview" class="btn btn-success" data-jobid="<?php echo $job_id;?>" data-reportid="<?php echo $report_id;?>">Preview</button>
                        <button id="savereport" class="btn btn-primary"  data-jobid="<?php echo $job_id;?>" data-reportid="<?php echo $report_id;?>">Save</button>
                        <button id="share" class="btn btn-warning" data-jobid="<?php echo $job_id;?>" data-reportid="<?php echo $report_id;?>">Share</button>
                    
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
        <?php $this->load->view('editablereport/list_areatypetopicvalue'); ?>
    </div><!-- /.box-body -->
     
</div><!-- /.box -->	



<div class="modal fade" id ="shareReportModal" tabindex="-1" role ="dialog" aria-labelledby="shareReportModalLabel" data-backdrop="static" data-keyboard ="false">
  <div class="modal-dialog" role ="document">
    <div class="modal-content">
    <form name ="sharereport_form" id ="sharereport_form" role ="form" method ="post" action="<?php echo site_url();?>jobs/addtask" class="form-horizontal">
      <div class="modal-header">
        <button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="shareReportTitle">Share Report</h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label class="col-sm-3" class="control-label">Email:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-3" class="control-label">Note:</label>
                <div class="col-sm-9">
                    <textarea type ="text" id ="notes" rows="5" name ="notes" class="form-control" placeholder="Notes"><?php echo $shareUrl;?></textarea>
                </div>
            </div>
          
          <div id ="status"></div> 
          <div class="form-group">
              <div class="col-sm-10 col-sm-offset-3">
                    <button type ="submit" id ="modalsave" class="btn btn-primary" data-loading-text="Sending.." data-jobid="<?php echo $job_id;?>" data-reportid="<?php echo $report_id;?>">Send</button>&nbsp;
                    <button type ="button" id ="cancel" class="btn btn-Default" data-loading-text="Cancel">Cancel</button>
              </div>
                
            </div>
      </div>
        <input  type ="hidden" name ="modaljobid" id ="modaljobid" value ="<?php echo $job_id;?>" />
        <input  type ="hidden" name ="modalreportid" id ="modalreportid" value ="<?php echo $report_id;?>" />
     </form>
    </div>
  </div>
</div>
<form style="display:none" target="_blank" name ="previewreport_form" id ="previewreport_form" role ="form" method ="post" action=" http://crm.dcfm.com.au/infbase/ajax/ajaxpostBack.php">
    <input type="text" name="params" value="<?php echo '!I!url=sitereport!I!mode=PropertyInspectionReport!I!reportid='.$report_id.'!I!jobid='.$job_id;?>" />
</form>