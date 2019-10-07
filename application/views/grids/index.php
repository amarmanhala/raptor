<!-- Default box -->
<div class="box"  ng-app="app" ng-controller="GridCtrl">
    <div class="box-body">
        <div class="row">
             <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <div class="box box-warning  box-solid">
                <div class="box-header">
                  <h3 class="box-title">Quotes</h3>
                </div>
                <div class="box-body grid-box no-padding">
                  <table id ="quotegridbox" class="table  table-bordered table-striped">
                    <thead>    
                    <tr>
                       <th>Stage</th>
                       
                      <th class="text-center" style ="width: 40px">Count</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quotegrid as $key=>$value) {  ?>   
                     
                        <tr id ="q_<?php echo $key;?>" ng-click ="refreshQuotes(<?php echo $key;?>,'<?php echo $value['QStatus'];?>')" style="cursor: pointer">
                            <td><?php echo $value['QStatus']; ?></td>
                        <td class="text-center"><?php echo (int)$value['count'];?></td>
                       
                      <tr>
                    <?php } ?>
                    </tbody>      
                  </table>
                
                
                </div><!-- /.box-body -->
               
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <div class="box box-primary box-solid">
                    <div class="box-header">
                        <h3 class="box-title">Jobs</h3>
                    </div>
                    <div class="box-body grid-box no-padding">
                        <table id ="jobgridbox" class="table  table-bordered table-striped">
                            <thead>    
                                <tr>
                                    <th>Stage</th>
                                    <th class="text-center" style ="width: 40px">Count</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($jobgrid as $key=>$value) {  ?>        
                                <tr id ="j_<?php echo $key;?>" ng-click ="refreshJobs(<?php echo $key;?>,'<?php echo $value['stage'];?>')" style="cursor: pointer">
                                    <td><?php echo $value['stage']; ?></td>
                                    <td class="text-center"><?php echo (int)$value['count'];?></td>
                                </tr>
                              
                            <?php } ?>
                            </tbody>      
                      </table>

                    </div><!-- /.box-body -->

                  </div><!-- /.box -->
                </div><!-- /.col -->
           
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
              <div class="box box-success box-solid">
                <div class="box-header">
                  <h3 class="box-title">Invoices</h3>
                </div>
                <div class="box-body grid-box no-padding">
                  <table  id ="invoicegridbox" class="table  table-bordered table-striped">
                    <thead>    
                    <tr>
                       <th>Stage</th>
                       
                      <th class="text-center" style ="width: 40px">Count</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($invoicegrid as $key=>$value) {  ?>   
                        <tr id ="i_<?php echo $key;?>" ng-click ="refreshInvoices(<?php echo $key;?>,'<?php echo $value['invstatus'];?>')" style="cursor: pointer">
                            <td><?php echo $value['invstatus'];?></td>
                            <td class="text-center"><?php echo (int)$value['count'];?></td>
                        </tr>
                        <?php } ?>
                    </tbody>      
                  </table>
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
          
          
          
          <div class="row">
            <div class="col-md-12">
                <div class="box" id ="gridjobdetail" style ="display:none;">
                    <div class="box-header  with-border">
                        <h3 class="box-title">Job Stage - <span class="stagename"></span></h3>
                    </div>
                    <div class="box-body">
                      <div>
                           <div ui-grid ="jobs" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class="grid"></div>
                      </div>
                    </div>
                    <!-- Loading (remove the following to stop the loading)-->
                    <div class= "overlay" style = "display:none">
                          <i class= "fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <div class="box" id ="gridquotedetail" style ="display:none;">
                    <div class="box-header  with-border">
                       <h3 class="box-title">Quotes Detail  - <span class="stagename"></span></h3>
                     </div>
                    <div class="box-body">
                      <div>
                           <div ui-grid ="quotes" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class="grid"></div>
                      </div>
                    </div>
                    <!-- Loading (remove the following to stop the loading)-->
                    <div class= "overlay" style = "display:none">
                          <i class= "fa fa-refresh fa-spin"></i>
                    </div>
                </div>
                <div class="box" id ="gridinvoicedetail" style ="display:none;">
                    <div class="box-header  with-border">
                       <h3 class="box-title">Invoices  - <span class="stagename"></span></h3>
                     </div>
                    <div class="box-body">
                      <div>
                           <div ui-grid ="invoices" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class="grid"></div>
                      </div>
                    </div>
                    <!-- Loading (remove the following to stop the loading)-->
                    <div class= "overlay" style = "display:none">
                          <i class= "fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
          <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
        <input type="hidden" name="custordref2_label" id="custordref2_label" value="<?php echo isset($ContactRules["custordref2_label"]) ? $ContactRules["custordref2_label"]:'Order Ref 2';?>"/>
        <input type="hidden" name="custordref3_label" id="custordref3_label" value="<?php echo isset($ContactRules["custordref3_label"]) ? $ContactRules["custordref3_label"]:'Order Ref 3';?>"/>
        <input type="hidden" name="sitereflabel1" id="sitereflabel1" value="<?php echo isset($ContactRules["sitereflabel1"]) ? $ContactRules["sitereflabel1"]:'Site Ref 1';?>"/>
        <input type="hidden" name="sitereflabel2" id="sitereflabel2" value="<?php echo isset($ContactRules["sitereflabel2"]) ? $ContactRules["sitereflabel2"]:'Site Ref 2';?>"/>
    </div><!-- /.box-body -->
 
  </div><!-- /.box -->
  
  
  

      