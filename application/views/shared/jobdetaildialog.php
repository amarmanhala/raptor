<div class="modal fade" id ="jobDetailModal" tabindex="-1" role ="dialog" aria-labelledby="jobDetailModalLabel">
    <div class="modal-dialog  modal-lg" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pull-right">Esc or&nbsp;<button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> 
                <h4 class="modal-title text-center" id ="jobDetailModalLabel">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <center style ="display: none;">
                    <img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" />
                </center>
                <div id ="jobdetail_form" class=" custom-form-margin form-horizontal"  style ="display:none;">
                     
                     <div class="form-group">
                        <label class="control-label col-xs-4 col-sm-2  text-blue">Lead Date</label>
                        <label class="control-label col-xs-8 col-sm-4" id ="leaddate"></label>
                        <label class="control-label col-xs-4 col-sm-2  text-blue">Job Stage</label>
                        <label class="control-label col-xs-8 col-sm-4" id ="jobstage"></label>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Company Name:</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="companyname"></label>

                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue"><?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?></label>
                                <label class="control-label col-xs-8 col-sm-8" id ="custordref"></label>

                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Site</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="site"></label> 
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group" id="qapprovaldiv">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Quote Approved</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="qapproval"></label>
                            </div>
                            <div class="form-group" id="japprovaldiv" >
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Job Approval</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="japproval"></label>
                            </div>
                            <div class="form-group" id="vapprovaldiv">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Variation Approval</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="vapproval"></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Site FM</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="sitefm"></label>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Site FM Ph</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="sitefmph"></label>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Site FM Email</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="sitefmemail"></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Contact</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="sitecontact"></label>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Contact Ph</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="sitephone"></label>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-xs-4 col-sm-4  text-blue">Email</label>
                                <label class="control-label col-xs-8 col-sm-8" id ="siteemail"></label>
                            </div>
                        </div>
                       
                       
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12  text-blue">Job Description</label>
                        <label class="control-label col-xs-12" id ="jobdescription" style="white-space: pre-wrap;"></label>

                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>
 