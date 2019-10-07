  <!-- Default box -->
  <div class="box"  ng-app= "app" >
    <div class="box-header with-border">
      <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
       
    </div>
    <div class="box-body nav-tabs-custom" id="myquotes">
        <div id="myquotestatus"></div>   
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
      
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
           
            <li role="presentation" class="active"><a href="#pendingapproval" aria-controls="pendingapproval" role="tab" data-toggle="tab">Pending Your Approval</a></li>
<!--            <li role="presentation"><a href="#inprogress" aria-controls="inprogress" role="tab" data-toggle="tab">In Progress</a></li>-->
            <li role="presentation"><a href="#pendingsubmission" aria-controls="pendingsubmission" role="tab" data-toggle="tab">Pending Submission</a></li>
             
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="pendingapproval">
                <?php $this->load->view('quotes/pendingapproval');?>
            </div>
<!--            <div role="tabpanel" class="tab-pane " id="inprogress">
                <?php //$this->load->view('quotes/inprogress');?>
            </div>-->
            
            <div role="tabpanel" class="tab-pane" id="pendingsubmission">
                <?php $this->load->view('quotes/waitingdcfmreview');?>
            </div> 
        </div>

 
    </div><!-- /.box-body -->
    
  </div><!-- /.box -->