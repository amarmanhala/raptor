/* global base_url, parseFloat, bootbox, angular, Highcharts */

var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
$( document ).ready(function() {
    
    // Handle click on "Select all" control
    $(document).on('click', '.ui-grid-header-cell-row input[name="select_all"]', function(e){
        var divtabid=$(this).attr('data-targettableid');
        if(this.checked){
            $('#'+divtabid+' input[data-targetdiv='+divtabid+']:not(:checked)').trigger('click');
        } else {
            $('#'+divtabid+' input[data-targetdiv='+divtabid+']:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
   });
    
    $(document).on('click', '.ui-grid-cell input[type="checkbox"]', function(e){

        var tableid=$(this).data('targetdiv');

        updateDataTableSelectAllCtrl(tableid);

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });
    
    $(document).on('click', 'ul.nav-tabs .loadingdata', function(e){
        
        var targetdiv = $(this).attr('href');
        $(targetdiv+ " .btn-refresh" ).click();
        $(this).removeClass('loadingdata');
    });
    var hash = window.location.hash;
    if(hash!=''){
        $( "ul.nav.nav-tabs li" ).each(function( index ) {
            var targetdiv = $(this).children('a').attr('href');
            if(hash === targetdiv){
                $(targetdiv+ " .btn-refresh" ).click();
                $(this).children('a').removeClass('loadingdata');
            }
        });
    } 
    else{
        $( "ul.nav.nav-tabs li.active" ).each(function( index ) {
            var targetdiv = $(this).children('a').attr('href');
            $(targetdiv+ " .btn-refresh" ).click();
            $(this).children('a').removeClass('loadingdata');
        });
    }
});   
function clearMsgPanel(){
    setTimeout(function(){ 
            $("#myjobsstatus").html('');
           
    }, 3000);
}
function updateDataTableSelectAllCtrl(divtabid){
 
   var $chkbox_all = $("#"+divtabid+" input[data-targetdiv="+divtabid+"]" );
    var $chkbox_checked    = $("#"+divtabid+" input[data-targetdiv="+divtabid+"]:checked");
   var chkbox_select_all  = $('#'+divtabid+' input[name="select_all"]');
 
   // If none of the checkboxes are checked
   if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.prop('checked', true);
      
   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.prop('checked', false);
      
   }
}