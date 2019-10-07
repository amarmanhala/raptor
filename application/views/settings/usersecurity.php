<!-- Default box -->
<style>
    table#noaccessgrid td, table#noaccessgrid th {
        font-size: 13px;
    }
    table#hasaccessgrid td, table#hasaccessgrid th {
        font-size: 13px;
    }
</style>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $page_title;?></h3>
    </div>
    <div class="box-body nav-tabs-custom" id="mystatements">
        <div id="mystatementsstatus"></div>   
        <?php if($this->session->flashdata('message')){
            echo '<div class="alert alert-success">'.$this->session->flashdata('message').'</div>';	
        } ?>	
     
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#securedfunctions" aria-controls="securedfunctions" role="tab" data-toggle="tab">Secured Functions</a></li>
               <?php if($view_contactsecurity_auditlog) { ?>
            <li role="presentation"><a href="#auditlog" aria-controls="auditlog" role="tab" data-toggle="tab">Audit Log</a></li>
              <?php } ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" ng-app = "app">
            <div role="tabpanel" class="tab-pane active" id="securedfunctions">
                <?php $this->load->view('settings/securedfunctions');?>
            </div>
               <?php if($view_contactsecurity_auditlog) { ?>
            <div role="tabpanel" class="tab-pane" id="auditlog">
                <?php $this->load->view('settings/auditlog');?>
            </div>
              <?php } ?>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<div class="modal fade" id="addEditSecurityModal" tabindex="-1" role="dialog" aria-labelledby="addEditSecurityModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
	 
        <form name="addEditSecurityForm" id="addEditSecurityForm" class="form-horizontal" autocomplete="off" novalidate>
            
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" >Add/Edit Contact Security</h4>
        </div>
            
        <div class="modal-body">
            <div class= "form-group">
                    <div class= "col-sm-4 col-md-3" style="padding-right: 0px;">
                    <label class= "control-label">Name</label>
                    <select id="contactname" name="contactname" class="form-control" onchange="getContactSecurityData(this);">
                            <option value="">Contacts</option>
                            <?php foreach($contacts as $key=>$value) {  ?>
                            <option value="<?php echo $value['contactid'];?>" contact-role="<?php echo $value['role'];?>"><?php echo $value['firstname'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-4 col-md-2" style="padding-right: 0px;">
                        <label class= "control-label">Role</label>
                        <input type="text" name="rolename" id="rolename" class="form-control" readonly="readonly">
                    </div>
                    <div class= "col-sm-4 col-md-3" style="padding-right: 0px;">
                    <label class= "control-label">Copy From</label>
                        <select id="copycontactname" name="copycontactname" class="form-control">
                            <option value="">Contacts</option>
                            <?php foreach($contacts as $key=>$value) {  ?>
                            <option value="<?php echo $value['contactid'];?>" contact-role="<?php echo $value['role'];?>"><?php echo $value['firstname'];?></option> 
                        <?php } ?>
                        </select>
                    </div>
                    <div class= "col-sm-6 col-md-1" style="padding-right: 0px;">
                        <label class= "control-label">&nbsp;</label>
                        <button type="button" title="Copy" class="form-control btn btn-warning" onclick="copyContactSecurityData();">Copy</button>
                    </div>
                    <div class= "col-sm-6 col-md-3 text-right">
                        <label class= "control-label">&nbsp;</label>
                        <div>
                            <button type= "button" title="Grant All" class="btn btn-success" onclick="grantAll();">Grant All</button>
                            <button type = "button" title="Revoke All" class= "btn btn-danger" onclick="revokeAll();">Revoke All</button> 
                        </div>

                    </div>
            </div>
            <div class= "form-group">
                <center style="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                 
                
                
                <div class= "col-sm-5 hideonload">
                       <div class="box">
                           <div class="box-header">
                               <h3 class="box-title">No Access</h3>
                           </div>
                           <div class="box-body table-responsive" style="height:250px;padding: 0px">
                                
                                <table id="noaccessgrid" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Module</th>
<!--                                            <th>Function</th>-->
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                       </div>
                    </div>
                <div class= "col-sm-2 hideonload">
                    <div style="vertical-align:middle;" class="text-center">
                        <table border="0" style="width:100%;">
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="manageSecurity('lhs');"><</button>
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-primary" onclick="manageSecurity('rhs');">></button>
                                </td>
                            </tr>
                        </table>
                        
                    </div>
                    
                </div>
                    <div class= "col-sm-5 hideonload">
                       <div class="box">
                           <div class="box-header">
                               <h3 class="box-title">Has Access</h3>
                           </div>
                            <div class="box-body table-responsive" style="height:250px;padding: 0px">
                                <table id="hasaccessgrid" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Module</th>
<!--                                            <th>Function</th>-->
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                           </div>
                       </div>
                        
                    </div>
         
            </div>    
        </div>
        <div class="modal-footer"> 
            <button type="submit" id="modalsave" name ="modalsave" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>&nbsp;Saving...">Save</button>
            <button type ="button" id="modalclose" name ="modalclose" class="btn btn-default" data-loading-text="Close" data-dismiss="modal" aria-label="Close" >Close</button>
        </div>
        
        </form>
    </div>
  </div>
</div>