  <!-- Default box -->
  <div class="box"  ng-app= "app" >
    <div class="box-header with-border">
      <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
       
    </div>
    <div class="box-body nav-tabs-custom" id="myjobs">
        <div id="myjobsstatus"></div>   
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
         <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
        <input type="hidden" name="custordref2_label" id="custordref2_label" value="<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>"/>
        <input type="hidden" name="custordref3_label" id="custordref3_label" value="<?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?>"/>
        <input type="hidden" name="sitereflabel1" id="sitereflabel1" value="<?php echo isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"]:'Site Ref 1';?>"/>
        <input type="hidden" name="sitereflabel2" id="sitereflabel2" value="<?php echo isset($ContactRules["sitereflabel2"]) ? $ContactRules["sitereflabel2"]:'Site Ref 2';?>"/>
        <input type="hidden" name="direct_allocate" id="direct_allocate" value="<?php echo isset($ContactRules["direct_allocate"]) ? $ContactRules["direct_allocate"]:'0';?>"/>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            
        <?php 
             $actvatetab = false;
            if ((isset($ContactRules["show_job_approval_tab_in_client_portal"]) && $ContactRules["show_job_approval_tab_in_client_portal"] == "1")){  ?>
            <li role="presentation" class="active"><a href="#waitingapproval" aria-controls="waitingapproval" role="tab" data-toggle="tab" class="loadingdata" >Waiting Approval</a></li>
            <?php $actvatetab = true; } ?>
            <li role="presentation" <?php if(!$actvatetab){ echo 'class="active"'; } ?> ><a href="#waitingdcfmreview" aria-controls="waitingdcfmreview" role="tab" data-toggle="tab" class="loadingdata" >Waiting DCFM Review</a></li>
            <li role="presentation"><a href="#inprogress" aria-controls="inprogress" role="tab" data-toggle="tab" class="loadingdata" >In Progress</a></li>
            <li role="presentation"><a href="#waitingvariationapproval" aria-controls="waitingvariationapproval" role="tab" data-toggle="tab" class="loadingdata" >Waiting Variation Approval</a></li>
            <?php if ((isset($ContactRules["show_jobs_on_hold"]) && $ContactRules["show_jobs_on_hold"] == "1")){  ?>
            <li role="presentation"><a href="#onhold" aria-controls="onhold" role="tab" data-toggle="tab" class="loadingdata" >On Hold</a></li>
            <?php  } ?>
            <li role="presentation"><a href="#completed" aria-controls="completed" role="tab" data-toggle="tab" class="loadingdata" >Completed</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?php 
                $actvatetab = false;
            if ((isset($ContactRules["show_job_approval_tab_in_client_portal"]) && $ContactRules["show_job_approval_tab_in_client_portal"] == "1")) {  ?>
            <div role="tabpanel" class="tab-pane active" id="waitingapproval">
                <?php $this->load->view('jobs/waitingapproval');?>
            </div>
            <?php $actvatetab = true;} ?>
            <div role="tabpanel" class="tab-pane <?php if(!$actvatetab){ echo 'active'; } ?>" id="waitingdcfmreview">
                <?php $this->load->view('jobs/waitingdcfmreview');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="inprogress">
                <?php $this->load->view('jobs/inprogress');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="waitingvariationapproval">
                <?php $this->load->view('jobs/waitingvariationapproval');?>
            </div>
            <?php if ((isset($ContactRules["show_jobs_on_hold"]) && $ContactRules["show_jobs_on_hold"] == "1")){  ?>
            <div role="tabpanel" class="tab-pane" id="onhold">
                <?php $this->load->view('jobs/onholdjobs');?>
            </div>
            <?php  } ?>
            <div role="tabpanel" class="tab-pane" id="completed">
                <?php $this->load->view('jobs/completed');?>
            </div>
        </div>

 
    </div><!-- /.box-body -->
    
  </div><!-- /.box -->