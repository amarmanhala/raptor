  <!-- Default box -->
  <div class="box">
    <div class="box-body nav-tabs-custom custom-box-body">


        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#editaddress" aria-controls="editaddress" role="tab" data-toggle="tab">Address</a></li>
            <!--<li role="presentation"><a href="#editlog" aria-controls="editlog" role="tab" data-toggle="tab">Edit Log</a></li>-->
            <li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">Address Attributes</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="editaddress">
                <?php $this->load->view('customers/tab_address');?>
            </div>
            <!--<div role="tabpanel" class="tab-pane" id="editlog">
                <?php //$this->load->view('asset/tab_editlog');?>
            </div>-->
            <div role="tabpanel" class="tab-pane" id="attributes"  ng-app="app">
                <?php $this->load->view('customers/tab_attributes');?>
            </div>

        </div>
    </div><!-- /.box-body -->
    
    <div class= "modal fade" id = "addressesModel" tabindex= "-1" role = "dialog" aria-labelledby = "addressesModalLabel" data-backdrop= "static" data-keyboard = "FALSE">
    <div class= "modal-dialog modal-lg" role = "document" >
      <div class= "modal-content">
        <div class= "modal-header">
            <button type = "button" class= "close" onclick="closeModal();"><span aria-hidden= "TRUE">&times;</span></button>
            <p id="mapaddress"></p>
        </div>
        <div class= "modal-body">
            <div id = "address-map" style="height: 400px;border:1px solid #d2d6de;"></div>    
        </div>
        <div class= "modal-footer">
              <button type = "button" class= "btn btn-default" onclick="closeModal();">Close</button>
        </div>
        <!-- Loading (remove the following to stop the loading)-->
        <div class= "overlay map-overlay">
              <i class= "fa fa-refresh fa-spin"></i>
        </div>
        <!-- end loading -->

      </div>
    </div>
  </div>
    
  </div><!-- /.box -->
  
<?php $this->load->view('shared/addcontact');?>