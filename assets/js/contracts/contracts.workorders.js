/* global angular, base_url, bootbox, google, app */

"use strict";
         
   app.controller('WorkOrdersCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

        // filter
    $scope.siteParentJobOrderFilter = {
        filtertext: '',
        month: '',
        year: '',
        state: '',
        contractid : $("#contractdetailform #contractid").val() 
    };

    $scope.siteOrderFilter = {
        filtertext: '',
        labelid:'',
        filterby: 'all',
        month: '',
        year: '',
        serviceid: '',
        contractid : $("#contractdetailform #contractid").val() 
    };
    
    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };
 
     var paginationOptions1 = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };
    $scope.siteParentJobOrderGrid = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        multiSelect: false,
        enableCellEdit: true,
        editOnFocus: true,
        enableCellEditOnFocus: true,
        columnDefs: [ 
            {   displayName:'Label ID', 
                cellTooltip: true, 
                name: 'labelid', 
                enableCellEdit: false,
                width: 65 
            },
            {   displayName:'Site Ref', 
                cellTooltip: true, 
                name: 'siteref',  
                enableCellEdit: false,
                width: 65 
            }, 
            {   displayName:'Street Address', 
                cellTooltip: true, 
                name: 'siteline2', 
                enableFiltering: false,
                enableCellEdit: false,
                width: 200 
                 
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'sitesuburb',
                enableCellEdit: false,
                width: 120
            },
            {   displayName:'State', 
                cellTooltip: true, 
                name: 'sitestate', 
                enableFiltering: false, 
                enableCellEdit: false,
                width: 65 
            },
            { 
                displayName:$('#custordref1_label').val(),
                cellTooltip: true,
                name: 'custordref',
                enableSorting: true,
                width: 100,
                enableCellEdit: true,
                editOnFocus: true,
                enableCellEditOnFocus: true
               // cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.custordref}}" class="form-control topcustordref1_label"  /></div>'
            },
            { 
                displayName:$('#custordref2_label').val(),
                cellTooltip: true,
                name: 'custordref2',
                enableSorting: true,
                width: 100,
                enableCellEdit: true,
                enableCellEditOnFocus: true
               // cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.custordref2}}" class="form-control topcustordref2_label"  /></div>' 
            },
            { 
                displayName:$('#custordref3_label').val(),
                cellTooltip: true,
                name: 'custordref3',
                width: 100,
                enableCellEdit: true,
                enableCellEditOnFocus: true
                //cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.custordref2}}" class="form-control topcustordref2_label"  /></div>' 
            }
         ],
         onRegisterApi: function(gridApi) {
             $scope.gridsiteParentJobOrderApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = '';
                  paginationOptions.field = '';
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                siteParentJobOrderData();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               siteParentJobOrderData();
             });
             
            gridApi.edit.on.afterCellEdit($scope,function(rowEntity, colDef, newValue, oldValue){
                if(newValue !== oldValue){
                    saveParentJobOrder(rowEntity.id, colDef.name, newValue);
                }
                $scope.$apply();
            });
             
             gridApi.selection.on.rowSelectionChanged($scope,function(row){
                $scope.selectedRows = $scope.gridsiteParentJobOrderApi.selection.getSelectedRows();
               
                if($scope.selectedRows.length === 0){
                     $scope.siteOrderFilter.labelid='';
                     if($scope.siteOrderFilter.filterby === 'site'){
                         $scope.siteOrderFilter.filterby= 'all';
                     }
                }
                else{
                    $scope.siteOrderFilter.filterby= 'site';
                    $scope.siteOrderFilter.labelid=$scope.selectedRows[0].labelid;
                }
                siteOrderData();
            });
          
        }
    };
       
       
    $scope.siteOrderGrid = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        multiSelect: false,
        enableCellEdit: true,
        editOnFocus: true,
        enableCellEditOnFocus: true,
        columnDefs: [ 
             
            {   displayName:'Date', 
                cellTooltip: true, 
                name: 'scheduledate',  
                enableCellEdit: false,
                width: 95 
            }, 
            {   displayName:'Service', 
                cellTooltip: true, 
                name: 'name', 
                enableCellEdit: false,
                enableFiltering: false 
                 
            },
            { 
                displayName:$('#custordref1_label').val(),
                cellTooltip: true,
                name: 'customer_order_reference1',
                enableSorting: true,
                width: 100,
                enableCellEdit: true,
                enableCellEditOnFocus: true
                //cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.customer_order_reference1}}" class="form-control topcustordref1_label"  /></div>' 
            },
            { 
                displayName:$('#custordref2_label').val(),
                cellTooltip: true,
                name: 'customer_order_reference2',
                enableSorting: true,
                width: 100,
                enableCellEdit: true,
                enableCellEditOnFocus: true
                //cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.customer_order_reference2}}" class="form-control bottomcustordref2_label"  /></div>' 
            },
            { 
                displayName:$('#custordref3_label').val(),
                cellTooltip: true,
                name: 'customer_order_reference3',
                width: 100,
                enableCellEdit: true,
                enableCellEditOnFocus: true
                //cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.customer_order_reference3}}" class="form-control bottomcustordref3_label"  /></div>'
             },
            { 
                displayName:'Amount ($)',
                cellTooltip: true,
                name: 'orderamount',
                enableSorting: true,
                width: 100,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right',
                type: 'number',
                
                enableCellEdit: true,
                enableCellEditOnFocus: true
                //cellTemplate: '<div class="ui-grid-cell-contents text-center"><input   type="text" data-id="{{row.entity.id}}" value="{{row.entity.orderamount}}" class="form-control allownumericwithoutdecimal bottomorderamount"  /></div>' 
               
            } 
         ],
         onRegisterApi: function(gridsiteOrderApi) {
             $scope.gridsiteOrderApi = gridsiteOrderApi;
             
             gridsiteOrderApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions1.sort = '';
                  paginationOptions1.field = '';
                } else {
                  paginationOptions1.sort = sortColumns[0].sort.direction;
                  paginationOptions1.field = sortColumns[0].field;
                }
                siteOrderData();
            });
            
             gridsiteOrderApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions1.pageNumber = newPage;
               paginationOptions1.pageSize = pageSize;
               siteOrderData();
             });
             
            gridsiteOrderApi.edit.on.afterCellEdit($scope,function(rowEntity, colDef, newValue, oldValue){
                if(newValue !== oldValue){
                    saveSiteOrder(rowEntity.id, colDef.name, newValue);
                }
                $scope.$apply();
            });
 	
         }
       };
       
        $scope.changeSiteParentJobOrderText = function() {
            siteParentJobOrderData();
        }; 
        
        $scope.changeSiteParentJobOrderFilters = function() {
           siteParentJobOrderData();
        };
        
        $scope.clearSiteParentJobOrderFilters = function() {
                $scope.siteParentJobOrderFilter = {
                    filtertext: '',
                    month: '',
                    year: '',
                    state: '',
                    contractid : $("#contractdetailform #contractid").val() 
                }; 
           siteParentJobOrderData();
        };
        
        $scope.refreshSiteParentJobOrderGrid = function() {
            siteParentJobOrderData();
        };
        $scope.exportSiteParentJobOrderToExcel = function(){
           var qstring = $.param($scope.siteParentJobOrderFilter);
           window.open(base_url+'contracts/exportsiteparentjoborders?'+qstring);
        };
        
        $scope.siteorderloaded = false; 
        
        var siteParentJobOrderData = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
 
            var qstring = $.param(params)+'&'+$.param($scope.siteParentJobOrderFilter);

            $scope.overlay = true;
            $http.get(base_url+'contracts/loadsiteparentjoborders?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{
                    $scope.siteParentJobOrderGrid.totalItems = response.total;
                    $scope.siteParentJobOrderGrid.data = response.data;  
                }
                
                if($scope.siteOrderFilter.labelid !== '' || $scope.siteorderloaded === false){
                    $scope.siteOrderFilter.labelid = '';
                    siteOrderData();
                }
                $scope.siteorderloaded = true;
                
            });
       };
       
      // siteParentJobOrderData();
        
        $scope.changeSiteOrderFilters2 = function() {
            if($scope.siteOrderFilter.filterby !== 'period') {
                $scope.siteOrderFilter.month = '';
                $scope.siteOrderFilter.year = '';
            }
            siteOrderData();
        }; 
 
        $scope.changeSiteOrderFilters1 = function() {
            $scope.siteOrderFilter.filterby = 'period';
            siteOrderData();
        }; 
        
        $scope.changeSiteOrderFilters = function() {
           siteOrderData();
        };
        
        $scope.clearSiteOrderFilters = function() {
            $scope.siteOrderFilter = {
                filtertext: '',
                labelid:'',
                filterby: 'all',
                month: '',
                year: '',
                serviceid: '',
                contractid : $("#contractdetailform #contractid").val() 
            }; 
           siteOrderData();
        };
        
        $scope.refreshSiteOrderGrid = function() {
            siteOrderData();
        };
        
        $scope.exportSiteOrderToExcel = function(){
            
           var qstring = $.param($scope.siteOrderFilter);
           window.open(base_url+'contracts/exportsiteorders?'+qstring);
           
        };
        
        
        
        
        var siteOrderData = function() {
           
            var params = {
                page  : paginationOptions1.pageNumber,
                size  : paginationOptions1.pageSize,
                field : paginationOptions1.field,
                order : paginationOptions1.sort
            }; 
 
            var qstring = $.param(params)+'&'+$.param($scope.siteOrderFilter);

            $scope.overlay1 = true;
            $http.get(base_url+'contracts/loadsiteorders?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
              
                $scope.overlay1 = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{
                    $scope.siteOrderGrid.totalItems = response.total;
                    $scope.siteOrderGrid.data = response.data;  
                }
            });
        };
        
        
        var saveParentJobOrder = function(id, field, value) {
    
            var postData = {
                id:id,
                value: value,
                field: field
            }; 

            
            var qstring = $.param(postData);
            $scope.overlay = true;
            $http.post(base_url+'contracts/updateparentjoborder', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                     
                }
                else {
                    bootbox.alert(data.message);
                }
            });
            
        };
        
        var saveSiteOrder = function(id, field, value) {
    
            var postData = {
                id:id,
                value: value,
                field: field
            }; 

            
            var qstring = $.param(postData);
            $scope.overlay1 = true;
            $http.post(base_url+'contracts/updatecustomersiteorder', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay1 = false;
                if (data.success) {
                     
                }
                else {
                    bootbox.alert(data.message);
                }
            });
            
        };
        
        $scope.exportImportTemplate = function(){
            var params = {
                 contractid : $("#contractdetailform #contractid").val() 
            }; 
            var qstring = $.param(params);
           window.open(base_url+'contracts/downloadsiteordertemplate?'+qstring);
        };
        
        $scope.importSiteOrder = function() { 
            
            $("#importsiteorderModal #loading-img").show();
            $("#importsiteorderModal #sitegriddiv").hide();
            $('#importsiteorderform').trigger("reset");
            $("#importsiteorderform .alert-danger").hide(); 
            $("#importsiteorderform span.help-block").remove();
            $("#importsiteorderform .has-error").removeClass("has-error");
            $('#importsiteorderform #btnsave').button("reset");
            $('#importsiteorderform #btncancel').button("reset");
            $('#importsiteorderform #btnsave').removeAttr("disabled");
            $('#importsiteorderform #btncancel').removeAttr("disabled");
            $("#importsiteorderModal .close").css('display', 'block');
             $('#importsiteorderModal #status').empty();
            var percentVal = '0';
            $('.progress-bar').attr('aria-valuenow',percentVal);
            $('.progress-bar').css('width',percentVal+"%");
            $('.sr-only').html(percentVal + "% Complete ");
            $("#importsiteorderModal").modal();
             setTimeout(function(){ 
                $("#importsiteorderModal #loading-img").hide();
                $("#importsiteorderModal #sitegriddiv").show();

            }, 500);
        };
       
        $scope.addSiteOrder = function() { 
         
          
            $('#CreateWorkOrderform').trigger("reset");
            $("#CreateWorkOrderform .alert-danger").hide(); 
            $("#CreateWorkOrderform span.help-block").remove();
            $("#CreateWorkOrderform .has-error").removeClass("has-error");
            $('#CreateWorkOrderform #btnsave').button("reset");
            $('#CreateWorkOrderform #btncancel').button("reset");
            $('#CreateWorkOrderform #btnsave').removeAttr("disabled");
            $('#CreateWorkOrderform #btncancel').removeAttr("disabled"); 
            $("#CreateWorkOrderModal").modal();
  

        };

        $(document).on('click', '#CreateWorkOrderModal #btnsave', function() {

           
           var serviceid = $("#CreateWorkOrderform #serviceid");
            var month = $("#CreateWorkOrderform #month");
            var year = $("#CreateWorkOrderform #year");
            $("#CreateWorkOrderform span.help-block").remove();

            if($.trim(serviceid.val()) === "") {
                $(serviceid).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(serviceid.parent());
            } else {
                $(serviceid).parent().removeClass("has-error");
            }
            if($.trim(month.val()) === "") {
                $(month).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(month.parent());
            } else {
                $(month).parent().removeClass("has-error");
            }

            if($.trim(year.val()) === "") {
                $(year).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(year.parent());
            } else {
                $(year).parent().removeClass("has-error");
            }

            if($.trim(serviceid.val()) === "" || $.trim(month.val()) === "" || $.trim(year.val()) === ""){
                return false;
            }

            $("#CreateWorkOrderform #btnsave").button('loading'); 
            $("#CreateWorkOrderform #btncancel").button('loading'); 
            $.post( base_url+"contracts/createsiteorders", $("#CreateWorkOrderform").serialize(), function( data ) {
                $('#CreateWorkOrderform #btnsave').removeAttr("disabled");
                $('#CreateWorkOrderform #btncancel').removeAttr("disabled");
                
                $('#CreateWorkOrderform #btnsave').removeClass("disabled");
                $('#CreateWorkOrderform #btncancel').removeClass("disabled");
                $('#CreateWorkOrderform #btnsave').html("Save");
                $('#CreateWorkOrderform #btncancel').html("Cancel");
                if(data.success) {
                    siteParentJobOrderData();
                    siteOrderData(); 
                    $("#CreateWorkOrderModal").modal('hide');
                    bootbox.alert(data.message);
                    
                }
                else{
                     $('#CreateWorkOrderModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#CreateWorkOrderModal #btncancel', function() {
            $("#CreateWorkOrderModal").modal('hide');
        });
        
        $(document).on('click', '#importsiteorderModal #btncancel', function() {
            $("#importsiteorderModal").modal('hide');
        });
        
            
        $(document).on('change', '#siteParentJobOrderGrid .chk_isactive', function() {
            var id = $(this).val();
            var contractid = $(this).attr('data-contractid');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateSiteStatus(id, contractid, value);

        }); 
    
       
          
    }
]);
 
app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$(document).ready(function() {
   
    
  
  
    $("#importsiteorderModal #btnsave").on('click', function() {
         
            var serviceid = $("#importsiteorderform #serviceid");
            var fileup = $("#importsiteorderform #importfile"); 
            $("#importsiteorderform span.help-block").remove();
          

            if($.trim(serviceid.val()) === "") {
                $(serviceid).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(serviceid.parent());
                return false;
            } else {
                $(serviceid).parent().removeClass("has-error");
            }

            if($.trim(fileup.val()) === "") {
                $(fileup).parent().parent().addClass("has-error");
                $('<span class="help-block">Please select upload file.</span>').appendTo(fileup.parent());
                return false;
            } else {

                if(readExcelURL(fileup)){
                    $(fileup).parent().parent().removeClass("has-error");
                }
                else{
                    $(fileup).parent().parent().addClass("has-error");
                    $("<span class='help-block'>Please select valid file. File Format : 'xls','xlsx'</span>").appendTo(fileup.parent());
                    return false;
                }


            } 
            $("#importsiteorderform #btnsave").button('loading'); 
            $("#importsiteorderform #btncancel").button('loading'); 
            return true;
        });
    
    if (typeof $.fn.ajaxForm === "function") {
       
        $('#importsiteorderform').ajaxForm({
                beforeSend: function() {
                    $('#importsiteorderModal #status').empty();
                    var percentVal = '0';
                    $('.progress-bar').attr('aria-valuenow',percentVal);
                    $('.progress-bar').css('width',percentVal+"%");
                    $('.sr-only').html(percentVal + "% Complete ");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete;
                   $('.progress-bar').attr('aria-valuenow',percentVal);
                   $('.progress-bar').css('width',percentVal+"%");
                   $('.sr-only').html(percentVal + "% Complete ");
                },
                success: function() {
                    var percentVal = '100';
                   $('.progress-bar').attr('aria-valuenow',percentVal);
                   $('.progress-bar').css('width',percentVal+"%");
                    $('.sr-only').html(percentVal + "% Complete ");
                },
                complete: function(xhr) {
                   var out2 = $.parseJSON(xhr.responseText);
                    $('#importsiteorderform #btnsave').button("reset");
                    $('#importsiteorderform #btncancel').button("reset");
                    $('#importsiteorderform #btnsave').removeAttr("disabled");
                    $('#importsiteorderform #btncancel').removeAttr("disabled");
                    if(out2.success){
                        $('#importsiteorderModal #status').html('<div class="alert alert-success" >'+out2.message+'</div>');
                        setTimeout(function(){ 
                            $("#importsiteorderModal").modal('hide');
                            $( "#WorkOrdersCtrl .btn-refresh" ).click();
                             
                        }, 1000);
                    }
                    else{
                        $('#importsiteorderModal #status').html('<div class="alert alert-danger" >'+out2.message+'</div>');
                    }
                    
                }
            });
        }
    
});
 
 var readExcelURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
     
    if($.inArray(ext, ['xls','xlsx']) === -1) {
        $(input).val('');
        
        bootbox.alert('invalid file format!');
        return false;
    }
     return true;
}; 

var updateContract = function() {
 
        var params = { 
            id  : $("#contractdetailform #contractid").val(),
            field: 'workordermethodid',
            value: $("#WorkOrdersCtrl #workordermethodid").val()
        }; 

        $.post( base_url+"contracts/updatecontract", params, function( data ) {
            if(data.success) {

            }
            else{

            }
        }, 'json');


    };