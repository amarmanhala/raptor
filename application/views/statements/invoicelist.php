  <!-- Default box -->
<div class="box"  ng-app= "app" >
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $page_title;?></h3>
    </div>
    <div class="box-body nav-tabs-custom" id="mystatements">
        <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
        <input type="hidden" name="custordref2_label" id="custordref2_label" value="<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>"/>
        <input type="hidden" name="custordref3_label" id="custordref3_label" value="<?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?>"/>
        <input type="hidden" name="sitereflabel1" id="sitereflabel1" value="<?php echo isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"]:'Site Ref 1';?>"/>
        <input type="hidden" name="sitereflabel2" id="sitereflabel2" value="<?php echo isset($ContactRules["sitereflabel2"]) ? $ContactRules["sitereflabel2"]:'Site Ref 2';?>"/>
        <div id="mystatementsstatus"></div>   
         <?php if($this->session->flashdata('message')){
         	echo '<div class="alert alert-success">'.$this->session->flashdata('message').'</div>';	
            } ?>	
     
        <ul class="nav nav-tabs" role="tablist">
            <?php
              $actvatetab=false;
             if ((isset($ContactRules["show_invoicefinalize_tab_in_clientportal"]) && $ContactRules["show_invoicefinalize_tab_in_clientportal"] == "1")){  ?>
            <li role="presentation" <?php if(!$actvatetab){ echo 'class="active"'; } ?> ><a href="#finalisedinvoices" aria-controls="finalisedinvoices" role="tab" data-toggle="tab">To Be Finalised</a></li>
	    <?php $actvatetab=true;  } ?>
            <?php if ((isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1")){  ?>
                <li role="presentation" <?php if(!$actvatetab){ echo 'class="active"'; } ?>><a href="#fmapprovalinvoices" aria-controls="fmapprovalinvoices" role="tab" data-toggle="tab">For FM Approval</a></li>
                <?php  if($this->session->userdata('raptor_role') == 'master') { ?>
                <li role="presentation"  ><a href="#finalapprovalinvoices" aria-controls="finalapprovalinvoices" role="tab" data-toggle="tab">For Final Approval</a></li>
                <?php  } ?>
                
            <?php } else {  ?>
                <li role="presentation" <?php if(!$actvatetab){ echo 'class="active"'; } ?>><a href="#forapprovalinvoices" aria-controls="forapprovalinvoices" role="tab" data-toggle="tab">For Approval</a></li>
            <?php }  ?>
            <li role="presentation"  ><a href="#openinvoices" aria-controls="openinvoices" role="tab" data-toggle="tab">Open Invoices</a></li>
	    <?php if ((isset($ContactRules["show_invoice_history_tab_in_clientportal"]) && $ContactRules["show_invoice_history_tab_in_clientportal"] == "1")){  ?>
            <li role="presentation"><a href="#invoicehistory" aria-controls="invoicehistory" role="tab" data-toggle="tab">Invoice History</a></li>
	    <?php } ?>
            <li role="presentation"><a href="#batchhistory" aria-controls="batchhistory" role="tab" data-toggle="tab">Batch History</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content"  ng-app= "app">
            
            <?php
             $actvatetab=false;
            if ((isset($ContactRules["show_invoicefinalize_tab_in_clientportal"]) && $ContactRules["show_invoicefinalize_tab_in_clientportal"] == "1")) { ?>
                <div role="tabpanel" class="tab-pane <?php if(!$actvatetab){ echo 'active'; } ?>" id="finalisedinvoices">
                    <?php $this->load->view('statements/finalisedinvoices');?>
                </div>
            <?php $actvatetab=true; } ?>
             <?php if ((isset($ContactRules["show_final_approval_tab_in_clientportal"]) && $ContactRules["show_final_approval_tab_in_clientportal"] == "1")) { ?>
                <div role="tabpanel" class="tab-pane <?php if(!$actvatetab){ echo 'active'; } ?>" id="fmapprovalinvoices">
                    <?php $this->load->view('statements/fmapprovalinvoices'); ?>
                </div>
                <?php  if($this->session->userdata('raptor_role') == 'master') { ?>
                    <div role="tabpanel" class="tab-pane" id="finalapprovalinvoices">
                        <?php $this->load->view('statements/finalapprovalinvoices'); ?>
                    </div>
                <?php } 
            }else{ ?>
                <div role="tabpanel" class="tab-pane <?php if(!$actvatetab){ echo 'active'; } ?>" id="forapprovalinvoices">
                    <?php $this->load->view('statements/finalapprovalinvoices'); ?>
                </div>
            <?php } ?>
            <div role="tabpanel" class="tab-pane " id="openinvoices">
                <?php $this->load->view('statements/openinvoices');?>
            </div>
             
            <?php if ((isset($ContactRules["show_invoice_history_tab_in_clientportal"]) && $ContactRules["show_invoice_history_tab_in_clientportal"] == "1")) { ?>
            <div role="tabpanel" class="tab-pane " id="invoicehistory">
                <?php $this->load->view('statements/invoicehistory');?>
            </div>
            <?php } ?>
            <div role="tabpanel" class="tab-pane " id="batchhistory">
                <?php $this->load->view('statements/batchhistory');?>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
  
  <div class="modal fade" id="queryInvoiceModel"   role="dialog" aria-labelledby="queryInvoiceModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
 
        <form name="queryInvoice_form" id="queryInvoice_form" class="form-horizontal" method="post"     >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Query Invoice</h4> 
      </div>
        
      <div class="modal-body">
             
                <div class="alert alert-danger" id="errormsg" style="display: none">
                  
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-2 control-label">Recipients </label>
                    <div class="col-sm-10" >
                        <input type="text" name="recipients" id="recipients" class="form-control requeird" readonly="" value="finance@dcfm.com.au" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-2 control-label">Subject </label>
                    <div class="col-sm-10" >
                        <input type="text" name="subject" id="subject" class="form-control requeird" readonly="" value="Invoice query - " />
                    </div>
                </div>
                 <div class="form-group">
                    <label for="input" class="col-sm-2 control-label">Query</label>
                    <div class="col-sm-10">
                        <textarea name="query" id="query" class="form-control requeird"></textarea>
                    </div>
                </div>
                 
       
             
         <div class="status"></div>   
            
      </div>
      <div class="modal-footer">
          <input type="hidden" name="ino" id="ino" value=""/>
          <button type="button" name="btnsave" id="btnsave" class="btn btn-primary" data-loading-text="Sending...">Send</button>
          <button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
        
      </div>
        </form>
    </div>
  </div>
</div>
  
  
  <div class="modal fade" id="editInvoiceModel"   role="dialog" aria-labelledby="editInvoiceModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
 
        <form name="editInvoice_form" id="editInvoice_form" class="form-horizontal" method="post"     >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Edit Invoice</h4> 
      </div>
        
      <div class="modal-body">
            
                <div class="alert alert-danger" id="errormsg" style="display: none">
                  
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1'; ?> </label>
                    <div class="col-sm-5" >
                        <input type="text" name="custordref" id="custordref" class="form-control requeird" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 1'; ?></label>
                    <div class="col-sm-5" >
                        <input type="text" name="custordref2" id="custordref2" class="form-control requeird" value="" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3'; ?> </label>
                    <div class="col-sm-5" >
                        <input type="text" name="custordref3" id="custordref3" class="form-control requeird" value="" />
                    </div>
                </div>
                <?php if(isset($ContactRules['EDIT_JOB_GLCODE']) && $ContactRules['EDIT_JOB_GLCODE'] == 1) { ?>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label"><?php echo isset($ContactRules["edit_invoice_gl_caption"]) ? $ContactRules["edit_invoice_gl_caption"] : 'GL Code';?></label>
                    <div class="col-sm-5" >
                        <select name="glcode" id="glcode" class="form-control">
                            <option value=''>Select Cost Center</option>
                            <?php foreach ($cuglchart as $key => $value) {
                                echo '<option value="'.$value['expenseaccount'].'">'.$value['name'].'</option>';
                            } ?>
                        </select>
                         
                    </div>
                </div> 
                <?php } ?>
                <?php if(isset($ContactRules['allow_address_entry_in_client_portal']) && $ContactRules['allow_address_entry_in_client_portal'] == 1) { ?>
                <div class="form-group allow_address_entry" >
                    <label for="input" class="col-sm-3 control-label">Site Address</label>
                    <div class="col-sm-9" >
                         <input type="text" name="siteline2" id="siteline2" class="form-control" value="" />
                    </div>
                </div> 
          
      
                <div class= "form-group allow_address_entry"  >
                     <label for= "input" class= "col-sm-3 control-label">Suburb/State/Post Code</label>
                     <div class= "col-sm-4">
                        <input type="text" id="sitesuburb" name="sitesuburb" placeholder="Suburb" data-suburb= "sitesuburb1"  data-state = "sitestate" data-postcode = "sitepostcode"  class="form-control suburbtypeahead" />
                        <input type="hidden" id="sitesuburb1" name="sitesuburb1" class= "updatesuburb" data-suburb= "sitesuburb" value="" />
                     </div>
                     <div class= "col-sm-2">
                        <input type="text" id="sitestate" name="sitestate"  readonly="readonly" placeholder="State" class="form-control" >
                        
                     </div>
                     
                     <div class= "col-sm-3">
                        <input type="text" id="sitepostcode" name="sitepostcode"  readonly="readonly" placeholder="Postcode" class="form-control postcodetypeahead" >
                     </div>

                </div>
                <?php } ?>
             
         <div class="status"></div>   
          
		 
      </div>
      <div class="modal-footer">
          <input type="hidden" name="invoiceno" id="invoiceno" value=""/>
          <input type="hidden" name="jobid" id="jobid" value=""/>
          <input type="hidden" name="tableid" id="tableid" value=""/>
          <button type="button" name="btnsave" id="btnsave" class="btn btn-primary" data-loading-text="Saving...">Save</button>
          <button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
        
      </div>
        </form>
    </div>
  </div>
</div>
  
  
 <div class="modal fade" id="approvalinvoiceModel"   role="dialog" aria-labelledby="approvalinvoiceModelLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog  modal-lg" role="document" >
    <div class="modal-content">
 <form name="approvalinvoice_form" id="approvalinvoice_form" class="form-horizontal" method="post"     >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Approve Invoices</h4> 
      </div>
        
      <div class="modal-body">
            <div class="status"></div>
            <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id="sitegriddiv">
                <div class="row1 clearfix">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="input" class="col-sm-3 control-label">Set Est. Pay Date</label>
                            <div class="col-sm-3" >
                                <input type="text" name="estpaydate" id="estpaydate" readonly="" placeholder="<?php echo javascript_date_formats(RAPTOR_DISPLAY_DATEFORMAT); ?>" class="form-control requeird datepicker" value="<?php echo date(RAPTOR_DISPLAY_DATEFORMAT, strtotime('+7 days', time()))?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <table id="approveinvstbl" class="table table-striped table-bordered table-condensed table-hover" style="margin-bottom:0px">
                            <thead>    
                                <tr class="theader">
                                    <th style="min-width:80px">Job ID</th>
                                    <th style="min-width:80px">Invoice No.</th>
                                    <th style="min-width:80px"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1'; ?></th>
                                    <th style="min-width:80px"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2'; ?></th>
                                    <th style="min-width:80px"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3'; ?></th>
                                    <th style="min-width:80px" class="text-right">$ Invoiced</th>
                                    <th style="min-width:80px">Date Emailed</th> 
                                    <th><input name="select_all" value="1" type="checkbox" checked="" data-targettableid="approveinvstbl"></th>
                                </tr>    
                            </thead>
                            <tbody id="tblapprovalinvbody">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-right">Total</th>
                                    <th class="text-right"><?php echo RAPTOR_CURRENCY_SYMBOL;?> <span id="approveinvstotal">0.00</span></th>
                                    <th colspan="3" class="text-right" >Selected Invoice : <span id="selectedinvcount">0</span></th>
                                </tr>
                            </tfoot>
                    </table>

                </div>
                </div>
            </div>
        </div>
        
        </div>
       <div class="modal-footer">
          <input type="hidden" name="ino" id="ino" value=""/>
          <input type="hidden" name="jobid" id="jobid" value=""/>
          <input type="hidden" name="tableid" id="tableid" value=""/>
          <button type="button" name="btnsave" id="btnsave" class="btn btn-primary" data-loading-text="Saving...">Mark Approved</button>
          <button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
        
      </div>
 </form>
    </div>
  </div>
</div>
  
  <div class="modal fade" id="budgetSpendModal"   role="dialog" aria-labelledby="budgetSpendModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog  modal-lg" role="document" >
    <div class="modal-content">
 <form name="budgetspend_form" id="budgetspend_form" class="form-horizontal" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Actual and Projected Spend v Budget</h4> 
      </div>
        
      <div class="modal-body">
            <div id="sitegriddiv">
                <div class="form-group">
                    <div class="col-md-6">
                        <div class="radio inline">
                            <label><input type="radio" name="budgettype" value="glcode" checked>&nbsp;By GL Code</label>
                        </div>
                        <div class="radio inline">
                            <label><input type="radio" name="budgettype" value="site">&nbsp;By Site</label>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" id="refreshdata" class="btn btn-default btn-refresh" title="Refresh Data"><i class="fa fa-refresh" title="Refresh Data"></i></button>
                    </div>
                </div>
                <div class="form-group">
                    <label class= "col-md-1 control-label">Period:</label>
                    <div class= "col-md-3">
                        <select class= "form-control" name = "period" id = "period">
                            <option value = 'monthtodate'>Month to Date</option>
                            <option value = 'lastmonth'>Last Month</option>
                            <option value = 'yeartodate'>Year to Date</option>
                            <option value = 'custom'>Custom</option>
                        </select>
                    </div>
                    <label class= "col-md-1 control-label">From:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type ="text" readonly ="readonly" name ="fromdate" id ="fromdate" placeholder="From Date" class="form-control datepicker">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <label class= "col-md-1 control-label">To:</label>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type ="text" readonly ="readonly" name ="todate" id ="todate" placeholder="To Date" class="form-control datepicker">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="box box-solid" style="overflow-x: auto;overflow-y: auto;">
                            <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                            <div class= "chart" id = "budgetSpendChart">

                            </div><!-- /.box-body -->
                        </div>
                    </div>   
                </div>
            </div>
        </div>    
       <div class="modal-footer">
          <button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Close</button>
      </div>
   
      </form>
    </div>
  </div>
</div>
     
  
  
  
  
  <div class="modal fade" id="emailinvoicesModel"   role="dialog" aria-labelledby="emailinvoicesModelLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog  modal-lg" role="document" >
    <div class="modal-content">
 
        <form name="emailInvoices_form" id="emailInvoices_form" class="form-horizontal" method="post"     >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Email Invoices</h4> 
      </div>
        
      <div class="modal-body">
            <div class="status"></div>
            <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id="sitegriddiv">
                <div class="row1 clearfix" style="max-height: 400px;overflow-x: auto;">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="input" class="col-sm-1 control-label">Recipients</label>
                            <div class="col-sm-11" >
                                <input type="text" name="recipients" id="recipients" placeholder="Recipients" class="form-control requeird" value=""/>
                                <span class="help-block">Please use comma's for multiple Recipient</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-1 control-label">Subject</label>
                            <div class="col-sm-11" >
                                <input type="text" name="subject" id="subject" placeholder="Subject" class="form-control requeird" value="Invoices for  from DCFM Australia Pty Ltd" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-1 control-label">Message</label>
                            <div class="col-sm-11" >
                                <textarea name="message" rows="5" id="message" placeholder="Message" class="form-control requeird">Please find  invoices attached. <?php echo PHP_EOL.PHP_EOL; ?> Should there be any issue with regards to these please contact DCFM Australia Pty Ltd at your earliest convenience.<?php echo PHP_EOL.PHP_EOL.PHP_EOL; ?>Thanks and regards,<?php echo PHP_EOL.PHP_EOL; ?>Team DCFM!<?php echo PHP_EOL; ?>Ph:     02 9460 7676 <?php echo PHP_EOL; ?>Fax:    02 9460 8913 <?php echo PHP_EOL; ?>Email:  dcfm@dcfm.com.au <?php echo PHP_EOL; ?>DCFM Australia Pty Ltd <?php echo PHP_EOL; ?>ABN   69 122487 076</textarea>

                            </div>
                        </div>

                    <div class="form-group">
                        <table id="emailinvstbl" class="table table-striped table-bordered table-condensed table-hover" style="margin-bottom:0px">
                        <thead>    
                            <tr class="theader">


                                <th style="min-width:80px">Job ID</th>
                                <th style="min-width:80px">Invoice No.</th>
                                <th style="min-width:80px"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1'; ?></th>
                                <th style="min-width:80px"><?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2'; ?></th>
                                <th style="min-width:80px"><?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3'; ?></th>
                                <th style="min-width:80px" class="text-right">$ Invoiced</th>
                                 <th style="min-width:80px">Date Emailed</th>
                                <th><input name="select_all" value="1" type="checkbox" checked="" data-targettableid="emailinvstbl"></th>
                        </tr>    
                        </thead>
                        <tbody id="tblemailinvstbody">
                        
                        </tbody>
                        <tfoot >
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right"><?php echo RAPTOR_CURRENCY_SYMBOL;?> <span id="approveinvstotal">0.00</span></th>
                                <th colspan="3" class="text-right" >Selected Invoice : <span id="selectedinvcount">0</span></th>
                            </tr> 
                        </tfoot>
                        </table>

                    </div>
                    </div>
                </div>
            </div>
        
        </div>
       <div class="modal-footer">
          <input type="hidden" name="ino" id="ino" value=""/>
          <input type="hidden" name="jobid" id="jobid" value=""/>
          <input type="hidden" name="tableid" id="tableid" value=""/>
          <button type="button" name="btnsave" id="btnsave" class="btn btn-primary" data-loading-text="Sending...">Send Email</button>
          <button type="button" name="btncancel" id="btncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
        
      </div>
        
        </form>
    </div>
  </div>
</div>