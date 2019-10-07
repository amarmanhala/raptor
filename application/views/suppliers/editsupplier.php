  <!-- Default box -->
<div class= "box">
 <div class= "box-header with-border">
      <h3 class= "box-title">Edit Supplier - <?php echo $supplier['companyname'] ?></h3>
       
    </div> 
    <div class= "box-body nav-tabs-custom custom-box-body" id = "mycustomer" >
         <div id="mysupplierstatus"></div> 
        <?php    
        if ($this->session->flashdata('error')) 
        {
           echo '<div class= "alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
        }
        if ($this->session->flashdata('success')) 
        {
           echo '<div class= "alert alert-success">'.$this->session->flashdata('success').'</div>';	
        }
        ?>	
        <!-- Nav tabs -->
        <ul class= "nav nav-tabs" role = "tablist" >
          <li role = "presentation" <?php if ($this->session->flashdata('activetab')== "company" || $this->session->flashdata('activetab')== NULL || $this->session->flashdata('activetab')== '') {echo 'class= "active"';} ?>><a href= "#company" aria-controls= "company" role = "tab" data-toggle = "tab">Company</a></li>
          <li role = "presentation" <?php if ($this->session->flashdata('activetab')== "contacts") {echo 'class= "active"';} ?>><a href= "#contacts" aria-controls= "contacts" role = "tab" data-toggle = "tab">Contacts</a></li>
          <li role = "presentation" <?php if ($this->session->flashdata('activetab')== "documents") {echo 'class= "active"';} ?>><a href= "#documents" aria-controls= "documents" role = "tab" data-toggle = "tab">Documents</a></li>
          <li role = "presentation" <?php if ($this->session->flashdata('activetab')== "inductions") {echo 'class= "active"';} ?>><a href= "#inductions" aria-controls= "inductions" role = "tab" data-toggle = "tab">Inductions</a></li>
          <?php if($supplier['typecode'] == 'L') {?>
          <li role = "presentation" <?php if ($this->session->flashdata('activetab')== "sites") {echo 'class= "active"';} ?>><a href= "#sites" aria-controls= "sites" role = "tab" data-toggle = "tab">Sites</a></li>
          <?php } ?>
        
        </ul>

        <!-- Tab panes -->
        <div class= "tab-content"  ng-app= "app" >
            <div role = "tabpanel" class= "tab-pane <?php if ($this->session->flashdata('activetab')== "company" || $this->session->flashdata('activetab')== NULL || $this->session->flashdata('activetab')== '') {echo ' active';} ?>" id = "company">
                  <?php $this->load->view('suppliers/supplierdetail_edit');?>
            </div>
           
            <div role = "tabpanel" class= "tab-pane <?php if ($this->session->flashdata('activetab')== "contacts") {echo ' active';} ?>" id = "contacts">
                 <?php $this->load->view('suppliers/contacts');?>
            </div>
 
            <div role = "tabpanel" class= "tab-pane <?php if ($this->session->flashdata('activetab')== "documents") {echo ' active';} ?>" id = "documents">
                  <?php $this->load->view('suppliers/documents');?>
            </div>
            <div role = "tabpanel" class= "tab-pane <?php if ($this->session->flashdata('activetab')== "inductions") {echo ' active';} ?>" id = "inductions">
                  <?php $this->load->view('suppliers/inductions');?>
            </div>
             <?php if($supplier['typecode'] == 'L') {?>
             <div role = "tabpanel" class= "tab-pane <?php if ($this->session->flashdata('activetab')== "sites") {echo ' active';} ?>" id = "sites">
                  <?php $this->load->view('suppliers/sites');?>
            </div>
            <?php } ?>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
 