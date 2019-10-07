  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
     <!-- <h3 class="box-title"><?php echo $page_title;?></h3>-->
      <div class="row">
           
          <div class="col-sm-3">
              <select id="fyear" name="fyear" class="form-control select2 chartfilter"  >
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
           <div class="col-sm-4">
                <select id="fcontactid" name="fcontactid" class="form-control select2 chartfilter" o >
                    <?php if($this->session->userdata('raptor_role') == 'master' || count($contacts)>1) { ?>
                    <option value="">All Managers</option>
                    <?php } ?>
                     <?php foreach($contacts as $key=>$value) { 
                            $selected = '';
                            if(set_value('fcontactid') == $value['contactid']) {
                                $selected = 'selected';
                            }
                      ?>
                    <option value="<?php echo $value['contactid'];?>" <?php echo $selected;?>><?php echo $value['firstname'];?></option> 
                    <?php } ?>
                </select>
          </div>
           <div class="col-sm-5">
              <select id="fsite" name="fsite" class="form-control select2 chartfilter"  >
                   <?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
                    <option value="">All Sites</option>
                    <?php } ?>
                     <?php foreach($sites as $key=>$value) { 
                            $selected = '';
                            if(set_value('fsites') == $value['id']) {
                                $selected = 'selected';
                            }
                      ?>
                    <option value="<?php echo $value['id'];?>" <?php echo $selected;?>><?php echo $value['name'];?></option> 
                  <?php } ?>
                </select>
          </div>
      </div>
    </div>
    <div class="box-body">
           <div class="row">
            <div class="col-md-6">
               <!-- LINE CHART -->
              <div class="box box-info">
            <!--<div class="box-header with-border">
                  <h3 class="box-title">12 month, monthly and YTD</h3>
                   
                </div>-->
                <div class="box-body">
                  <div class="chart" id="lineChart_12month" >
                
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->
            <div class="col-md-6">
             

              <!-- BAR CHART -->
              <div class="box box-success">
                <!--        <div class="box-header with-border">
          <h3 class="box-title">Budget v Actual YTD for the site</h3>
                  
                </div>-->
                <div class="box-body">
                  <div class="chart" id="barChart_budgetvsactual">
                    
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col (RIGHT) -->
          </div><!-- /.row -->
           <?php   if($this->session->userdata('raptor_role') == 'master') { ?>
          <div class="row">
            <div class="col-md-12">
               <!-- LINE CHART -->
              <div class="box box-info">
            <!--<div class="box-header with-border">
                  <h3 class="box-title">12 month, monthly and YTD</h3>
                   
                </div>-->
                <div class="box-body">
                  <div class="chart" id="barChart_budgetvsactualfm" >
                
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (Top) -->
            <div class="col-md-6">
               <!-- LINE CHART -->
              <div class="box box-info">
            <!--<div class="box-header with-border">
                  <h3 class="box-title">12 month, monthly and YTD</h3>
                   
                </div>-->
                <div class="box-body">
                  <div class="chart" id="piechart_budgetbyfm" >
                
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->
            <div class="col-md-6">
             

              <!-- BAR CHART -->
              <div class="box box-success">
                <!--        <div class="box-header with-border">
          <h3 class="box-title">Budget v Actual YTD for the site</h3>
                  
                </div>-->
                <div class="box-body">
                  <div class="chart" id="piechart_spendbyfm">
                    
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col (RIGHT) -->
          </div><!-- /.row -->
           <?php } ?>
          <?php   if($this->session->userdata('raptor_role') != 'site contact') { ?>
          <div class="row">
              <div class="col-md-12">
             

              <!-- BAR CHART -->
              <div class="box box-success">
                 
                <div class="box-body">
                    <div class="checkbox" style="display: inline; position: absolute; z-index: 2147483647; right: 5px;">
                        <label><input class="" type="checkbox" id="exclude0site" name="exclude0site" value="1" checked="checked">&nbsp;Exclude sites with no spend</label>
                    </div>
                  <div class="chart" id="steckedbarchart_sitespend">
                    
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col (RIGHT) -->
            <div class="col-md-6">
               <!-- LINE CHART -->
              <div class="box box-info">
            <!--<div class="box-header with-border">
                  <h3 class="box-title">12 month, monthly and YTD</h3>
                   
                </div>-->
                <div class="box-body">
                  <div class="chart" id="piechart_budgetbysite" >
                
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->
            <div class="col-md-6">
               <!-- LINE CHART -->
              <div class="box box-info">
            <!--<div class="box-header with-border">
                  <h3 class="box-title">12 month, monthly and YTD</h3>
                   
                </div>-->
                <div class="box-body">
                  <div class="chart" id="piechart_spendbysite" >
                
                  </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col (LEFT) -->
            
          </div><!-- /.row -->
        <?php } ?>
    </div><!-- /.box-body -->
   
  </div><!-- /.box -->