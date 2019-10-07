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
    <div class="box-body nav-tabs-custom" id="portalsettings">
        <div id="mystatementsstatus"></div>   
        <?php if($this->session->flashdata('message')){
            echo '<div class="alert alert-success">'.$this->session->flashdata('message').'</div>';	
        } ?>	
     
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#securedfunctions" aria-controls="securedfunctions" role="tab" data-toggle="tab">Portal Settings</a></li>
            <li role="presentation"><a href="#auditlog" aria-controls="auditlog" role="tab" data-toggle="tab">Audit Log</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" ng-app = "app">
            <div role="tabpanel" class="tab-pane active" id="securedfunctions">
                <?php $this->load->view('settings/portalsettings');?>
            </div>
            <div role="tabpanel" class="tab-pane" id="auditlog">
                <?php $this->load->view('settings/portalauditlog');?>
            </div>
        </div>
    </div><!-- /.box-body -->
</div><!-- /.box -->