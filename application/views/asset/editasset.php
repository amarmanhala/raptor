  <!-- Default box -->
  <div class="box" ng-app= "app">
<!--    <div class="box-header with-border">
        <h3 class="box-title">&nbsp;</h3>
       
    </div>-->
    <div class="box-body nav-tabs-custom custom-box-body" id="myassetdetail">
        
         <?php 
 		if($this->session->flashdata('message')) 
 		{
         	echo '<div class="alert alert-success">'.$this->session->flashdata('message').'</div>';	
        }
	?>	

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">Detail</a></li>
            <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
            <li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab">History</a></li>
            <li role="presentation"><a href="#editlog" aria-controls="editlog" role="tab" data-toggle="tab">Edit Log</a></li>
            <li role="presentation"><a href="#supplierinfo" aria-controls="supplierinfo" role="tab" data-toggle="tab">Supplier Info</a></li>
            <li role="presentation"><a href="#condition" aria-controls="condition" role="tab" data-toggle="tab">Condition</a></li>
            <li role="presentation"><a href="#information" aria-controls="information" role="tab" data-toggle="tab">Information</a></li>
            <li role="presentation"><a href="#financial" aria-controls="financial" role="tab" data-toggle="tab">Financial</a></li>
            <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab">Service</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" >
            <div role="tabpanel" class="tab-pane active" id="detail">
                <?php $this->load->view('asset/tab_detail');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="documents"  >
                <?php $this->load->view('asset/assetdocument');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="history"  >
                <?php $this->load->view('asset/assethistory');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="editlog"  >
                <?php $this->load->view('shared/editlog');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="supplierinfo">
                <?php $this->load->view('asset/tab_supplier');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="condition">
                <?php $this->load->view('asset/tab_condition');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="information">
                <?php $this->load->view('asset/tab_information');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="financial">
                <?php $this->load->view('asset/tab_financial');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="service">
                <?php $this->load->view('asset/tab_service');?>
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
  


