  <!-- Default box -->
<div class= "box">
 <div class= "box-header with-border">
      <h3 class= "box-title">Add New Supplier</h3>
       
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
          <li role = "presentation" class= "active"><a href= "#company" aria-controls= "company" role = "tab" data-toggle = "tab">Company</a></li>
          <li role = "presentation" class= "disabled"><a href= "#contacts" aria-controls= "contacts" role = "tab" class= "disabled">Contacts</a></li>
          <li role = "presentation" class= "disabled"><a href= "#documents" aria-controls= "documents" role = "tab" class= "disabled">Documents</a></li>
          <li role = "presentation" class= "disabled"><a href= "#inductions" aria-controls= "inductions" role = "tab" class= "disabled">Inductions</a></li>
        </ul>

        <!-- Tab panes -->
        <div class= "tab-content">
            <div role = "tabpanel" class= "tab-pane active" id = "company">
                  <?php $this->load->view('suppliers/supplierdetail_add');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "contacts">
                 
            </div>
            
            <div role = "tabpanel" class= "tab-pane" id = "documents">
                 
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "inductions">
                 
            </div>
             
 
        </div>
    </div><!-- /.box-body -->
  </div><!-- /.box -->

      