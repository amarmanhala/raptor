<!-- Default box -->
<div class= "box" id = "BudgetCtrl" ng-app= "app" ng-controller= "BudgetCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue"><?php echo $page_title;?></h3>
        <div class="pull-right text-right">
                
            <?php  if($this->session->userdata('raptor_role') != 'site contact') { ?>
            <button type="button" class="btn btn-primary" id="btn_createbudget" title="Create Budget"><i class="fa fa-plus"></i></button>
            <?php } ?>
             <?php  if($this->session->userdata('raptor_role') == 'master') { ?>
                        <button type="button" class="btn  btn-info" id="btn_import" title="Import Excel" ><i class="fa fa-upload"></i></button>
                        <br><a href="javascript:void(0)"  ng-click="exportImportTemplate()">Import Template</a>&nbsp;
            <?php }?> 
        </div>
      
       
    
     </div>
    <div class= "box-header with-border">
  
        <form name = "budgetfilter" id = "budgetfilter" class= "form-horizontal">
            <div class="row">
                <div class= "col-sm-3 col-md-2" style="padding-right: 0px;">
                    <label class= "control-label">Year</label>
                    <input type="hidden" name="currentyear" id="currentyear" value="<?php echo $defaultFY;?>"/>
                    <input type="hidden" name="sitereflabel1" id="sitereflabel1" value="<?php echo isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"]:'Site Ref 1';?>"/>
                    <input type="hidden" name="sitereflabel2" id="sitereflabel2" value="<?php echo isset($ContactRules["sitereflabel2"]) ? $ContactRules["sitereflabel2"]:'Site Ref 2';?>"/>
                    <select id="year" name="year" class="form-control selectpicker" ng-change = "changeFilters()" ng-model= "filterOptions.year">
                     <?php 
                        $startmonth=0;
                        $startmonth =isset($budget_setting['startmonth'])?$budget_setting['startmonth']:1;
                        for($year=2015; $year<= date('Y')+1;$year++) { 
                            $date=$year.'-'.$startmonth. '-01';
                            $date=  strtotime($date);
                            $todate=strtotime("+1 Years", $date);
                            $todate=strtotime("-1 Days", $todate);
                            $selected = '';
                           
                            if($startmonth==1){
                                $yearselected="Jan-Dec ".$year;
                            }
                            else{
                                $yearselected=date('M Y', $date).' to '.date('M Y', $todate);
                            }
                             $yearvalue= date('Ym', $date).'-'.date('Ym', $todate);
                             
                            if($defaultFY == $yearvalue) {
                                $selected = 'selected';
                            }
                                
                            
                      ?>
                        <option value="<?php echo $yearvalue;?>" <?php echo $selected;?>><?php echo $yearselected;?></option> 
                  <?php } ?>
                </select>
                    
                </div>
                <?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
                <div class= "col-sm-3 col-md-2" style="padding-right: 0px;">
                    <label class= "control-label">State</label>
                    
                        <select id="state" name="state" class="form-control selectpicker" ng-change = "changeFilters()" ng-model= "filterOptions.state">
                            <option value="">All</option>
                            <?php foreach($states as $key=>$value) { 
                            $selected = '';
                            if(set_value('state') == $value['abbreviation']) {
                                $selected = 'selected';
                            }
                            ?>
                            <option value="<?php echo $value['abbreviation'];?>" <?php echo $selected;?>><?php echo $value['abbreviation'];?></option> 
                        <?php } ?>
                        </select>
                   
                </div>
                <?php } ?>
                <?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
                <div class= "col-sm-6 col-md-3" style="padding-right: 0px;">
                    <label class= "control-label">Managers</label>
                        <select id="contactid" name="contactid" class="form-control selectpicker"  ng-change = "changeFilters()" ng-model= "filterOptions.contactid">
                            <option value="">All Managers</option>
                     <?php foreach($contacts as $key=>$value) { 
                            $selected = '';
                            if(set_value('contactid') == $value['contactid']) {
                                $selected = 'selected';
                            }
                      ?>
                                <option value="<?php echo $value['contactid'];?>" <?php echo $selected;?>><?php echo $value['firstname'];?></option> 
                  <?php } ?>
                        </select>
                </div>
                <?php } ?>
                 <div class= "col-sm-4 col-md-1" style="padding-right: 0px;">
                    <label class= "control-label">Bands</label>
                    <select id="band" name="band" class="form-control selectpicker" ng-change = "changeFilters()" ng-model= "filterOptions.band">
                       <option value="">All</option>
                       <option value="1" >0-50%</option> 
                       <option value="2" >50-74%</option> 
                       <option value="3" >75-100%</option> 
                       <option value="4" >Over 100%</option> 
                   </select>
                 </div>
                    
                <div class="col-sm-8 col-md-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group input-group">
                     
                            <input type="text" class="form-control" placeholder="Site Address/Suburb/State/Manager" ng-change="changeText()" ng-model="filterOptions.filterText" id="filterText" name="filterText" aria-invalid="false">
                            <span class="input-group-btn">
 
                                <button type="button" class="btn btn-warning" title = "Clear Filter" ng-click= "clearFilters()"><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                                <button type="button" class="btn btn-default btn-refresh" title = "Refresh Data" ng-click= "refreshGrid()"><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                                <button type="button" class="btn btn-success" ng-click="exportToExcel()" title="Export To Excel"><i class="fa fa-file-excel-o"></i></button>
                            </span>
                    </div><!-- /input-group -->
                    
                </div>
                <div class= "col-sm-6 text-left">
                   <label class="control-label">&nbsp;</label>
                   <div>
                    <?php if(count($budget_setting)>0){  
                        if($budget_setting['ismonthly']==1){ ?>
                            <div class="checkbox icheck" style="display: inline;">
                                <label><input class="" type="checkbox" id="split" name="split" value="ismonthly" checked="checked" disabled>&nbsp;Monthly Split</label>
                            </div>
                        <?php }elseif($budget_setting['isquarterly']==1){  ?>
                                <div class="checkbox icheck" style="display: inline;">
                                   <label><input class="" type="checkbox" id="split" name="split" value="isquarterly" checked="checked" disabled>&nbsp;Quarterly Split</label>
                            </div>
                         <?php }elseif($budget_setting['isannual']==1){  ?>
                        <input type="hidden" name="split" id="split" value="isannual"/>
                          <?php }  ?>
                      <?php }  ?>
                   </div>
                </div>
                <div class= "col-sm-6">
                    <div class= "pull-right text-right ">
                         <label class="control-label">Total</label>
                        <input type="text" readonly="readonly" id="totalbudget" ng-model="totalbudget" value="0" class="form-control" style="min-width:80px;max-width: 120px;text-align: right;padding-right: 5px;"/>
                    </div>
                    
                </div>
            </div>    
        </form>
   
    </div>
      
    <div class= "box-body">
        <?php if(count($budget_setting)==0){?>
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                Please Create Budget setting and budget option before Set Budget
            </div>
      <?php } ?>
        <div id="myassetstatus"></div>   
         <?php 
 		if($this->session->flashdata('success')) 
 		{
         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
                }
                if($this->session->flashdata('error')) 
 		{
         	echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	
                }
	?>
        <div>
            <div ui-grid = "gridOptions" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class= "grid"></div>
        </div>
    </div><!-- /.box-body -->
     <!-- Loading (remove the following to stop the loading)-->
    <div class= "overlay" style = "display:none">
          <i class= "fa fa-refresh fa-spin"></i>
    </div>
    <!-- end loading -->
</div><!-- /.box -->   
       
<div class="modal fade" id="budgetdetail" tabindex="-1" role="dialog" aria-labelledby="budgetdetailModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document"  >
    <div class="modal-content">
 
        <form name="budgetdetail_form" id="budgetdetail_form" class="form-horizontal" method="post"  >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php   
            if($budget_setting['ismonthly']==1){ 
                echo '<h4 class="modal-title" id="exampleModalLabel">Monthly budget detail for <b><span class="sitename"></span></b></h4>';
            }elseif($budget_setting['isquarterly']==1){  
                echo '<h4 class="modal-title" id="exampleModalLabel">Quarterly budget detail for <b><span class="sitename"></span></b></h4>'; 
            }elseif($budget_setting['isannual']==1){  
                echo '<h4 class="modal-title" id="exampleModalLabel">Annual budget detail for <b><span class="sitename"></span></b></h4>'; 
            } 
         ?>
      </div>
        
      <div class="modal-body">
            <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id="sitegriddiv1"> 
                <table id="budgetdetailgrid" class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                        <tr class="theader">
                            <th class="col-md-2" style="width: 120px;">Month</th>
                             <th class="col-md-1" style="width: 150px;">Budget</th>
                        </tr>    
                    </thead>
                    <tbody id="tblbudgetdetailbody">

                    </tbody>
                </table>
            </div>
		 
      </div>
      <div class="modal-footer">
          <input type="hidden" id="recordid" name="recordid" value=""/>
          <input type="hidden" id="year" name="year" value=""/>
           <?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
          <button type="submit" name="budgetbtnsave" id="budgetbtnsave" class="btn btn-primary" data-loading-text="Saving...">Save</button>
            <button type="button" name="budgetbtncancel" id="budgetbtncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
        <?php }else{ ?>
            <button type="button" name="budgetbtncancel" id="budgetbtncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Ok</button>
          <?php }  ?>
      </div>
        </form>
    </div>
  </div>
</div>

<?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
  <div class="modal fade" id="createbudget"   role="dialog" aria-labelledby="createbudgetModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
 
        <form name="createbudget_form" id="createbudget_form" class="form-horizontal" method="post"  >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Set Annual Budget By Site</h4> 
      </div>
        
      <div class="modal-body">
            <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id="sitegriddiv"> 
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">Financial Year </label>
                    <div class="col-sm-9" id="fy">
                        
                    </div>
                </div>
                 <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">Site </label>
                    <div class="col-sm-9">
                        <select id="siteid" name="siteid" class="form-control select2" data-placeholder="Select an site" required >
                            <option value="">-Select-</option>
                            <?php if($this->session->userdata('raptor_role') == 'master') { ?>  
                                <option value="0">All Sites</option>
                                
                           <?php } 
                            foreach($sites as $key=>$value) {  ?>
                                <option value="<?php echo $value['id'];?>" ><?php echo $value['name'];?></option> 
                            <?php } ?>
                        
                        </select>
                    </div>
                </div>
                <div class="form-group">
                        <label for="input" class="col-sm-3 control-label">Annual Budget ($)</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control allownumericwithdecimal" id="annualbudget" name="annualbudget" placeholder="Annual Budget" value="" required/>
                   
                        </div>
                </div>
                     
            </div>
		 
      </div>
      <div class="modal-footer">
          <input type="hidden" name="selyear" id="selyear" value=""/>
          <button type="submit" name="budgetbtnsave" id="budgetbtnsave" class="btn btn-primary" data-loading-text="Saving...">Save</button>
          <button type="button" name="budgetbtncancel" id="budgetbtncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
        
      </div>
        </form>
    </div>
  </div>
</div>
<?php  }  ?>

  <?php   if($this->session->userdata('raptor_role') == 'master') { ?>
  <div class="modal fade" id="importbudgetexcel"   role="dialog" aria-labelledby="importbudgetexcelModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" >
    <div class="modal-content">
 
        <form name="importbudget_form" id="importbudget_form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo site_url('budgets/importbudgetexcelbysite') ?>" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"  >Import Budget Excel </h4> 
      </div>
        
      <div class="modal-body">
            <center id="loading-img" ><img src="<?php echo base_url();?>assets/img/ajax-loader1.gif" /></center>
            <div id="sitegriddiv"> 
                <div class="alert alert-danger" id="errormsg" style="display: none">
                  
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">Financial Year </label>
                    <div class="col-sm-9" id="fy">
                        
                    </div>
                </div>
                 <div class="form-group">
                    <label for="input" class="col-sm-3 control-label">Upload Excel </label>
                    <div class="col-sm-9">
                        <input type="file" name="importfile" id="importfile" onchange="readExcelURL(this);" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="input" class="col-sm-3 control-label"></label>
                    <div class="col-sm-9">
                        <div class="radio">
                        <label>
                          <input type="radio" name="updateoption" id="updateoption1" value="1" checked>
                          Update All Excel Site Budgets
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="updateoption" id="updateoption2" value="2">
                          Add Pending Site Budgets
                        </label>
                      </div>
                    </div>
                </div>
                  <div class="progress">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 1%">
                  <span class="sr-only">0% Complete (success)</span>
                </div>
        </div>
       
             
         <div id="status"></div>   
            </div>
		 
      </div>
      <div class="modal-footer">
          <input type="hidden" name="ifyear" id="ifyear" value=""/>
          <button type="submit" name="budgetbtnsave" id="budgetbtnsave" class="btn btn-primary" data-loading-text="Saving...">Save</button>
          <button type="button" name="budgetbtncancel" id="budgetbtncancel" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
        
      </div>
        </form>
    </div>
  </div>
</div>
<?php  }  ?>