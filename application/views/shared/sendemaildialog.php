<div id="EmailDialog">
    <div class="modal fade" id ="EmailDialogModal" tabindex="-1" role ="dialog" aria-labelledby ="EmailDialogModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg" role ="document">
            <div class="modal-content">
                <form name ="emailDialogForm" id ="emailDialogForm"   class="form-horizontal" autocomplete="off" novalidate>
                <div class="modal-header">
                    <div class="pull-right"><button type ="button" class="close"  onclick="closeSendEmailDialog();"><span aria-hidden="TRUE">&times;</span></button></div> 
                    <h4 class="modal-title text-bold text-blue" id ="EmailModalLabel">Compose Email</h4>
                </div>
                <div class="modal-body  custom-form-margin form-horizontal">
                    <div class="status">
                        <div class="alert alert-danger" id="emailModalErrorMsg" style="display:none;"></div>
                        <div class="alert alert-success" id="emailModalSuccessMsg" style="display:none;"></div>
                    </div>
                    <center id ="loading-img" style ="display: none;"><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>

                    <div id ="sitegriddiv"> 
                        <div class="form-group">
                            <label for="input" class="col-sm-2  control-label">Attachments<br>
                            <button class="btn btn-flat btn-default" type="button" onclick="openDocumentEmailDialog();"><i class="fa fa-search-plus"></i>&nbsp;Choose</button><br>
                            </label>
                            <div class="col-sm-10" >
                                <div style="overflow: auto; max-height: 200px;">
                            
                                    <table id ="emailattachmenttbl" class="table table-striped table-bordered table-condensed table-hover" style ="margin-bottom:0px">
                                        <thead>    
                                            <tr class="theader">
 
                                                <th>Document Name</th> 
                                                <th style ="width:100px">File Size (Kb)</th>
                                               <th style ="width:50px">Remove</th>
                                            </tr>    
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                             </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Recipients:</label>
                            <div class="col-sm-10" >
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-default" type="button" onclick="openRecipientEmailDialog()"><i class="fa fa-search-plus"></i>&nbsp;Choose</button>
                                    </div>
                                    <input type ="text"  name ="recipients" id ="recipients" placeholder="Recipients" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">CC:</label>
                            <div class="col-sm-10" >
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button class="btn btn-flat btn-default" type="button"  onclick="openRecipientEmailDialog()"><i class="fa fa-search-plus"></i>&nbsp;Choose</button>
                                    </div>
                                    <input type ="text"  name ="cc" id ="cc" placeholder="cc" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Subject:</label>
                            <div class="col-sm-10" >
                                <input type ="text" name ="subject" id ="subject" placeholder="Subject" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input" class="col-sm-2 control-label">Message:</label>
                            <div class="col-sm-10" >
                                <textarea name ="message" rows="5" id ="message" placeholder="Message Body" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" > 
                    <button type="submit" name ="btnSave" id="btnSave" class="btn btn-primary" data-loading-text ="<i class='fa fa-spinner fa-spin'></i>&nbsp;Sending...">Send</button>
                    <button type ="button" name ="btnCancel" id="btnCancel" class="btn btn-default" data-loading-text="Cancel" onclick="closeSendEmailDialog()">Cancel</button>
                </div>
             </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id ="EmailRecipientDialogModal" tabindex="-1" role ="dialog" aria-labelledby ="EmailRecipientDialogModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="pull-right"><button type ="button" class="close"  onclick="closeRecipientEmailDialog()"><span aria-hidden="TRUE">&times;</span></button></div> 
                    <h4 class="modal-title text-bold text-blue">Recipient Chooser</h4>
                </div>
                <div class="modal-body">
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title  text-green"><button class="btn btn-box-tool" data-widget ="collapse"><i class="fa fa-minus"></i></button>&nbsp;Customer Contacts</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12" style="overflow: auto; max-height: 200px;">
                                <table id="emailCustomerContacts" class="table table-striped table-bordered table-condensed table-hover" style ="margin-bottom:0px">
                                    <thead>    
                                        <tr class="theader">
                                            <th style ="min-width:20px" class="text-center">To</th>
                                            <th style ="min-width:20px" class="text-center">CC</th>
                                            <th style ="min-width:160px">Name</th>
                                            <th>Email</th>
                                            <th style ="min-width:80px">Position</th>
                                        </tr>    
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                    
                                    
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    
                    <div class="box box-success collapsed-box">
                        <div class="box-header with-border">
                          <h3 class="box-title  text-green"><button class="btn btn-box-tool" data-widget ="collapse"><i class="fa fa-plus"></i></button>&nbsp;Contacts</h3>
                        </div>
                        <div class="box-body" style="display:none;">
                            <div class="row">
                                <div class="col-sm-12" style="overflow: auto; max-height: 200px;">
                                    <table id="emailInternalContacts" class="table table-striped table-bordered table-condensed table-hover" style ="margin-bottom:0px">
                                        <thead>    
                                            <tr class="theader">
                                                <th style ="min-width:20px" class="text-center">To</th>
                                                <th style ="min-width:20px" class="text-center">CC</th>
                                                <th style ="min-width:160px">Name</th>
                                                <th>Email</th>
                                                <th style ="min-width:80px">Position</th>
                                            </tr>    
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    
                                    
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
                <div class="modal-footer" > 
                    <button type ="button" class="btn btn-default" onclick="closeRecipientEmailDialog()">Close</button>
                </div>
            </div>
        </div>
    </div>
 
    <div class="modal fade" id ="EmailDocumentDialogModal" tabindex="-1" role ="dialog" aria-labelledby ="EmailDocumentDialogModalLabel" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog  modal-lg" role ="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="pull-right"><button type ="button" class="close"  onclick="closeDocumentEmailDialog()"><span aria-hidden="TRUE">&times;</span></button></div> 
                    <h4 class="modal-title text-bold text-blue">Document Chooser</h4>
                </div>
                <div class="modal-body">
                    
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title  text-green"><button class="btn btn-box-tool" data-widget ="collapse"><i class="fa fa-minus"></i></button>&nbsp;Documents</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12" style="overflow: auto; max-height: 200px;">
                                    <table id="emailOtherDocuments" class="table table-striped table-bordered table-condensed table-hover" style ="margin-bottom:0px">
                                        <thead>    
                                            <tr class="theader">
                                                <th style ="width:30px">Select</th> 
                                                <th>Document Name</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Date Added</th>
                                            </tr>    
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
                <div class="modal-footer" > 
                    <button type ="button" class="btn btn-default"   onclick="closeDocumentEmailDialog()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>