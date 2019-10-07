<!-- Default box -->
<div class="box" id="QuickSearchCtrl" ng-app="app" ng-controller="QuickSearchCtrl">
    <div class="box-header with-border">
        <h3 class="box-title text-blue"><?php echo $page_title;?> >> <?php echo $searchtext;?> </h3>
 
    </div> 
  
        <div class="box-body" id ="search-result-page" >
             <input type="hidden" name="custordref1_label" id="custordref1_label" value="<?php echo isset($ContactRules["custordref1_label"]) ? $ContactRules["custordref1_label"]:'Order Ref 1';?>"/>
              <input type="hidden" name="searchtext" id="searchtext" value="<?php echo $searchtext; ?>"/>
            <div>
                <div ui-grid ="jobs" ui-grid-pagination ui-grid-auto-resize ui-grid-resize-columns class="grid"></div>
            </div>
        </div><!-- /.box-body -->
         <!-- Loading (remove the following to stop the loading)-->
        <div class= "overlay" style = "display:none">
              <i class= "fa fa-refresh fa-spin"></i>
        </div>
</div><!-- /.box -->
 