  <!-- Default box -->
<div class= "box">
    <div class= "box-header with-border" >
        <h3 class= "box-title text-blue">Contracts - <?php echo $contract['name'] ?></h3>
    </div> 
    <div class= "box-body nav-tabs-custom custom-box-body" id = "mycustomer" >
        
        <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
        <input type="hidden" name="custordref2_label" id="custordref2_label" value="<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>"/>
        <input type="hidden" name="custordref3_label" id="custordref3_label" value="<?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?>"/>
        <input type="hidden" name="sitereflabel1" id="sitereflabel1" value="<?php echo isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"]:'Site Ref 1';?>"/>
        <input type="hidden" name="sitereflabel2" id="sitereflabel2" value="<?php echo isset($ContactRules["sitereflabel2"]) ? $ContactRules["sitereflabel2"]:'Site Ref 2';?>"/>
        
        <div id="mysupplierstatus"></div> 
        <?php    
        if ($this->session->flashdata('error')) {
           echo '<div class= "alert alert-danger error">'.$this->session->flashdata('error').'</div>';	
        }
        if ($this->session->flashdata('success')) {
           echo '<div class= "alert alert-success">'.$this->session->flashdata('success').'</div>';	
        }
        ?>	
        <!-- Nav tabs -->
        <ul class= "nav nav-tabs" role = "tablist">
            <li role = "presentation" class= "active"><a href= "#information" aria-controls= "information" role = "tab" data-toggle = "tab" class="loadingdata" >Information</a></li>
            <li role = "presentation" ><a href= "#sites" aria-controls= "sites" role = "tab" class="loadingdata" >Sites</a></li>
            <li role = "presentation" ><a href= "#schedules" aria-controls= "schedules" role = "tab" class="loadingdata" >Schedules</a></li>
            <li role = "presentation" ><a href= "#rules" aria-controls= "rules" role = "tab" class="loadingdata" >Rules</a></li>
            <li role = "presentation"><a href= "#workorders" aria-controls= "workorders" role = "tab" class="loadingdata" >Work Orders</a></li>
            <li role = "presentation" ><a href= "#technicians" aria-controls= "technicians" role = "tab" class= "loadingdata">Technicians</a></li>
            <li role = "presentation"><a href= "#parentjobs" aria-controls= "parentjobs" role = "tab" class= "loadingdata">Parent Jobs</a></li>
            <li role="presentation"><a href="#editlog" aria-controls="editlog" role="tab" data-toggle="tab" class="loadingdata" >Edit Log</a></li>
<!--        <li role = "presentation"><a href= "#auditlog" aria-controls= "auditlog" role = "tab" class="loadingdata" >Audit Log</a></li>-->
        </ul>

        <!-- Tab panes -->
        <div class= "tab-content" ng-app="app"  >
            <div role = "tabpanel" class= "tab-pane active" id = "information">
                  <?php $this->load->view('contracts/contractinformation_edit');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "sites"> 
                <?php $this->load->view('contracts/contractsites');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "schedules">
                <?php $this->load->view('contracts/contractschedules');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "rules">
                <?php $this->load->view('contracts/rules');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "workorders">
                <?php $this->load->view('contracts/workorders');?>
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "technicians">
                <?php $this->load->view('contracts/contracttechnicians');?>
             
            </div>
            <div role = "tabpanel" class= "tab-pane" id = "parentjobs">
                <?php $this->load->view('contracts/contractparentjobs');?>
                
            </div>
            <div role="tabpanel" class="tab-pane" id="editlog"  >
                <?php $this->load->view('shared/editlog');?>
            </div>
            
<!--        <div role = "tabpanel" class= "tab-pane" id = "auditlog">
                <?php //$this->load->view('contracts/auditlog');?>
            </div> -->
 
        </div>
         
    </div><!-- /.box-body -->
</div><!-- /.box -->
 