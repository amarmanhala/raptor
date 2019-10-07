<div class="modal fade" id ="OrgChartModal" tabindex="-1" role ="dialog" aria-labelledby ="OrgChartModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog  modal-lg" role ="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="pull-right"><button type ="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div> 
                <h4 class="modal-title text-bold text-blue">Organisational Chart</h4>
            </div>
            <div class="modal-body">
                <center id ="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
                <div id ="sitegriddiv" style ="display:none;max-height: 450px;overflow: auto;"> 
                     <div id="orgchart_div"></div>
                </div>
            </div>
        </div>
    </div>
</div> 
 