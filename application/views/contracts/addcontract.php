  <!-- Default box -->
<div class= "box">
 <div class= "box-header with-border">
      <h3 class= "box-title text-blue">Add Contract</h3>
       
    </div> 
    <div class= "box-body nav-tabs-custom custom-box-body" id = "mycustomer">
        
         <?php 
 		if ($this->session->flashdata('message')) 
 		{
         	echo '<div class= "alert alert-success">'.$this->session->flashdata('message').'</div>';	
        }
	?>	
        <!-- Nav tabs -->
        <ul class= "nav nav-tabs" role = "tablist">
          <li role = "presentation" class= "active"><a href= "#information" aria-controls= "information" role = "tab" data-toggle = "tab">Information</a></li>
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "sites" role = "tab" class= "disabled">Sites</a></li>
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "schedule" role = "tab" class= "disabled">Schedule</a></li>
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "rules" role = "tab" class= "disabled">Rules</a></li>
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "workorders" role = "tab" class= "disabled">Work Orders</a></li>
          
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "technicians" role = "tab" class= "disabled">Technicians</a></li>
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "parentjobs" role = "tab" class= "disabled">Parent Jobs</a></li>
          <li role = "presentation" class= "disabled"><a href= "javascript:void(0)" aria-controls= "auditlog" role = "tab" class= "disabled">Audit Log</a></li>
        </ul>

        <!-- Tab panes -->
        <div class= "tab-content">
            <div role = "tabpanel" class= "tab-pane active" id = "information">
                  <?php $this->load->view('contracts/contractinformation_add');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "sites"> 
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "schedule">
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "rules">
            </div>
             <div role = "tabpanel" class= "tab-pane" id = "workorders">
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "technicians">
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "parentjobs">
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "auditlog">
            </div>
 
        </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

      