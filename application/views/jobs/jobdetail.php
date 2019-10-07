<!-- Default box -->
<div class="box">
    <div class="box-header">
        <div class="col-sm-2" style="padding-right: 0px;">
            <label>Select Print Options:</label>
        </div>
        <div class="col-sm-10" id="jobdetailcheckbox">
            <input type="checkbox" name="jobdtprint[]" value="jl" /> Job Detail &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="jobdtprint[]" value="jn" /> Job Notes &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="jobdtprint[]" value="jd" /> Job Documents &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="jobdtprint[]" value="im" /> Images &nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="jobdtprintal" value="al" /> All &nbsp;&nbsp;&nbsp;
            <a href="javascript:void(0);" onclick="generatePDF();"><img src="<?php echo base_url();?>assets/img/pdf_icon.png" /></a>
        </div>
    </div>
    <div class="box-body nav-tabs-custom custom-box-body" id ="myjobsdetail">
        <div id ="myjobstatus"></div>  
        <?php 
            if ($this->session->flashdata('message')) 
            {
                echo '<div class="alert alert-success">'.$this->session->flashdata('message').'</div>';	
            }
        ?>	

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role ="tablist">
            <li role ="presentation" class="active"><a href="#jobdetail" aria-controls="jobdetail" role ="tab" data-toggle ="tab">Job Detail</a></li>
            <li role ="presentation"><a href="#jobnotes" aria-controls="jobnotes" role ="tab" data-toggle ="tab">Job Notes</a></li>
            <li role ="presentation"><a href="#documents" aria-controls="documents" role ="tab" data-toggle ="tab">Documents</a></li>
            <li role ="presentation"><a href="#images" aria-controls="images" role ="tab" data-toggle ="tab">Images</a></li>
            <li role ="presentation"><a href="#jobtasks" aria-controls="jobtasks" role ="tab" data-toggle ="tab">Job Tasks</a></li>
            <li role ="presentation"><a href="#reports" aria-controls="reports" role ="tab" data-toggle ="tab">Reports</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" ng-app="app">
            <div role ="tabpanel" class="tab-pane active" id ="jobdetail">
                <?php $this->load->view('jobs/jobdetails');?>
            </div>
            <div role ="tabpanel" class="tab-pane" id ="jobnotes">
                <?php $this->load->view('jobs/jobnotes');?>
            </div>
            <div role ="tabpanel" class="tab-pane" id ="documents">
                <?php $this->load->view('jobs/jobdocuments');?>
            </div>
            <div role ="tabpanel" class="tab-pane" id ="images">
                <?php $this->load->view('jobs/jobimages');?>
            </div>
            <div role ="tabpanel" class="tab-pane" id ="jobtasks">
                <?php $this->load->view('jobs/jobtasks');?>
            </div>
            <div role ="tabpanel" class="tab-pane" id ="reports">
                <?php $this->load->view('jobs/jobreports');?>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<?php $this->load->view('jobs/uploaddocument');?>
<?php $this->load->view('shared/sendemaildialog');?>