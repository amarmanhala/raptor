<!-- Default box -->
<div class="box"  ng-app= "app" id="potentialJobsCtrl"  ng-controller="potentialJobsCtrl">
<!--    <div class="box-header with-border">
      <h3 class="box-title"><?php //echo $page_title;?></h3>
    </div>-->
    <div class="box-body nav-tabs-custom" >
        <div id="potentialjobsstatus"></div>   
        <?php  if($this->session->flashdata('message')) {
         	echo '<div class="alert alert-success">'.$this->session->flashdata('message').'</div>';	
            } ?>	
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active" ><a href="#waitingapproval" aria-controls="waitingapproval" role="tab" data-toggle="tab">Waiting Approval</a></li>
            <li role="presentation"><a href="#declined" aria-controls="declined" role="tab" data-toggle="tab">Declined</a></li>
        </ul>
         <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="waitingapproval">
                <?php $this->load->view('jobs/potentialjobs_waitingapproval');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="declined">
                <?php $this->load->view('jobs/potentialjobs_declined');?>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->