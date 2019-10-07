/* global base_url, angular, app */

"use strict";
app.controller('ContractParentJobsCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
    $scope.filterOptions = {
        filtertext : ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
  
    $scope.parentJobGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [ 
            { 
                displayName:'Month',
                cellTooltip: true,
                name: 'monthofyear',
                width: 60
            },
             
            { 
                displayName:'Year',
                cellTooltip: true,
                name: 'year',
                width: 60
            },
            { 
                displayName:'Parent JobID',
                cellTooltip: true,
                name: 'parentjobid',
                width: 100
            },
            { 
                displayName:'Contract Order',
                cellTooltip: true,
                name: 'service',
                width: 120
            },
            { 
                displayName:'Job Order Ref1',
                cellTooltip: true,
                name: 'custordref',
                width: 120
            },
            { 
                displayName:'Job Order Ref2',
                cellTooltip: true,
                name: 'custordref2',
                width: 120
            },
            { 
                displayName:'Job Order Ref3',
                cellTooltip: true,
                name: 'custordref3',
                width: 120
            },
            {   displayName:'Created', 
                cellTooltip: true, 
                name: 'leaddate',  
                width: 100 
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
                width: 60,
                enableSorting: false,
                visible :$('#delete_con_parent_job').val()==='1'?true:false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteParentJob(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
            }
         ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = '';
                  paginationOptions.field = '';
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                loadParentJobPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               loadParentJobPage();
             });
 	
         }
        };
       
         $scope.changeText = function() {
            loadParentJobPage();
        }; 
       $scope.refreshGrid = function() {
            loadParentJobPage();
        };
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext : ''
            };
 
            loadParentJobPage();
        };
    
        $scope.exportToExcel = function(){
            var params = { 
                contractid : $("#contractdetailform #contractid").val() 
            }; 
        
            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
        
            window.open(base_url+'contracts/exportcontractparentjobs?'+qstring);
        };
       
        var loadParentJobPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                contractid : $("#contractdetailform #contractid").val() 
            }; 
        
            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
        
            $scope.overlay = true;
            $http.get(base_url+'contracts/loadcontractparentjobs?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.parentJobGrid.totalItems = response.total;
                    $scope.parentJobGrid.data = response.data;  
                }

            });
        };

       // loadParentJobPage();
       
        $scope.deleteParentJob = function(entity) {

            bootbox.confirm("Delete parent job <b>"+entity.parentjobid+"</b> from the contract?", function(result) {
                if (result) {
                    $.post( base_url+"contracts/deletecontractparentjob", { id:entity.id, contractid : entity.contractid}, function( response ) {
                        if (response.success) {
                            loadParentJobPage();
                            bootbox.alert(response.message);
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.addParentJob = function() { 
          
            $("#createParentJobForm #contactModalErrorMsg").hide(); 
            $("#createParentJobForm #contactModalSuccessMsg").hide();
            $("#createParentJobForm #contactModalErrorMsg").html(''); 
            $("#createParentJobForm #contactModalSuccessMsg").html('');
            $('#createParentJobForm').trigger("reset");
            $("#createParentJobForm .alert-danger").hide(); 
            $("#createParentJobForm span.help-block").remove();
            $("#createParentJobForm .has-error").removeClass("has-error");
            $('#createParentJobForm #btnsave').button("reset");
            $('#createParentJobForm #btncancel').button("reset");
            $('#createParentJobForm #btnsave').removeAttr("disabled");
            $('#createParentJobForm #btncancel').removeAttr("disabled");
            $("#createParentJobForm .close").css('display', 'block');
            
        
            $("#createParentJobForm #contractid").val($("#contractdetailform #contractid").val() ); 
          
            $('#createParentJobForm input[name="attendancedate"]').datepicker('setEndDate', null);
            $('#createParentJobForm input[name="completiondate"]').datepicker('setStartDate', null);
            $('#createParentJobForm input[name="active"]').prop('checked', true); 
            
            $("#createParentJobModal #loading-img").show();
            $("#createParentJobModal #sitegriddiv").hide();
             $("#createParentJobModal .modal-footer").hide();
              $("#createParentJobModal").modal();
            $.get( base_url+"contracts/getcontractparentjobrules", {contractid: $("#contractdetailform #contractid").val()}, function( response ) {
                
                if (response.success) {
                    var result = response.data;
                   
                    if(result.success){
                         
                        $("#createParentJobForm #parentjobrule").val(result.data.code); 
                        $("#createParentJobForm #parentjobvalue").val(result.data.parentjob_method); 
                        $("#createParentJobForm #custordref1code").val(result.data.ordref1_code); 
                        $("#createParentJobForm #custordref1value").val(result.data.ordref1_method);
                        $("#createParentJobForm #custordref2code").val(result.data.ordref2_code); 
                        $("#createParentJobForm #custordref2value").val(result.data.ordref2_method);
                        $("#createParentJobForm #custordref3code").val(result.data.ordref3_code); 
                        $("#createParentJobForm #custordref3value").val(result.data.ordref3_method);
                          
                        $("#createParentJobForm #estimated_sell").val(result.data.estimated_sell);
                        $("#createParentJobForm #internal_buffer").val(result.data.internal_buffer); 
                        $("#createParentJobForm #jobstage").val(result.data.initial_jobstage);
                        if(parseInt(result.data.is_chargeable) === 1){
                            $('#createParentJobForm input[name="ischargeable"]').prop('checked', true); 
                        }
                        else{
                            $('#createParentJobForm input[name="ischargeable"]').prop('checked', false); 
                        }
                        if(parseInt(result.data.create_materials_job) === 1){
                            $('#createParentJobForm input[name="ismaterialsjob"]').prop('checked', true); 
                        }
                        else{
                            $('#createParentJobForm input[name="ismaterialsjob"]').prop('checked', false); 
                        }
                        if(parseInt(result.data.create_safetysheet_jobs) === 1){
                            $('#createParentJobForm input[name="issafetysheetjob"]').prop('checked', true); 
                        }
                        else{
                            $('#createParentJobForm input[name="issafetysheetjob"]').prop('checked', false); 
                        }
                    }
                     
                    $("#createParentJobForm #monthofyear").val(response.data.monthofyear); 
                    $("#createParentJobForm #year").val(response.data.year); 
                    $("#createParentJobForm #attendancedate").val(response.data.attendancedate); 
                    $("#createParentJobForm #completiondate").val(response.data.completiondate);
                    $('#createParentJobForm input[name="attendancedate"]').datepicker('setEndDate', response.data.completiondate);
                    $('#createParentJobForm input[name="completiondate"]').datepicker('setStartDate', response.data.attendancedate);
                    $('#createParentJobForm input[name="attendancedate"]').datepicker('setDate', response.data.attendancedate);
                    $('#createParentJobForm input[name="completiondate"]').datepicker('setDate', response.data.completiondate);
                    
                    $("#createParentJobModal #loading-img").hide();
                    $("#createParentJobModal #sitegriddiv").show();
                    $("#createParentJobModal .modal-footer").show();
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
            
           
        };
    }
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
 
$( document ).ready(function() {
    
    $("#createParentJobForm #attendancedate").on('changeDate', function(e) {
        $('#createParentJobForm input[name="completiondate"]').datepicker('setStartDate', e.date);
    });
    
    $("#createParentJobForm #completiondate").on('changeDate', function(e) {
        $('#createParentJobForm input[name="attendancedate"]').datepicker('setEndDate', e.date);
    });
    
    
    $(document).on('change', '#createParentJobForm #monthofyear, #createParentJobForm #year', function(){
        
        var month = $("#createParentJobForm #monthofyear").val();
        if(parseInt(month)<10){
            month = '0'+ month;
        }
        var startdate = '01/'+  month +'/'+ $("#createParentJobForm #year").val()
         var enddate = '30/'+  month +'/'+ $("#createParentJobForm #year").val()
        if(month === '02')
        {
            enddate = '28/'+  month +'/'+ $("#createParentJobForm #year").val()
        }
       
        $("#createParentJobForm #attendancedate").val(startdate); 
        $("#createParentJobForm #completiondate").val(enddate);
        $('#createParentJobForm input[name="attendancedate"]').datepicker('setEndDate', enddate);
        $('#createParentJobForm input[name="completiondate"]').datepicker('setStartDate', startdate);
        $('#createParentJobForm input[name="attendancedate"]').datepicker('setDate', startdate);
        $('#createParentJobForm input[name="completiondate"]').datepicker('setDate', enddate);
                    
        
    });
        
     
     $(document).on('click', '#createParentJobForm #modalsave', function(){ 
          var month = $("#createParentJobForm #monthofyear").val();
          var monthname = $("#createParentJobForm #monthofyear option:selected").text();
          var year = $("#createParentJobForm #year").val();
        var  selections= [];
        
        
        if($('#createParentJobForm input[name="islabourjob"]').prop('checked')){
            selections.push('Labour');
        }
        if($('#createParentJobForm input[name="ismaterialsjob"]').prop('checked')){
            selections.push('Materials');
        }
        if($('#createParentJobForm input[name="issafetysheetjob"]').prop('checked')){
            selections.push('Safety Sheet');
        }
        
         
         bootbox.confirm("Create "+selections.join(', ')+" Jobs for "+ monthname +" "+ year +"?", function(result) {
                if (result) {
            $("span:eq(0)", "#createParentJobForm #modalsave").css("display", 'block');
            $("span:eq(1)", "#createParentJobForm #modalsave").css("display", 'none');
            $("#createParentJobForm #cancel").button('loading');

            $("#createParentJobForm #contactModalErrorMsg").hide(); 
            $("#createParentJobForm #contactModalSuccessMsg").hide();
            $.post( base_url+"contracts/savecontractparentjobs", $('#createParentJobForm').serialize(), function( response ) {
                $("span:eq(0)", "#createParentJobForm #modalsave").css("display", 'none');
                $("span:eq(1)", "#createParentJobForm #modalsave").css("display", 'block');
                $("#createParentJobForm #cancel").button('reset');
                if (response.success) {
                    
                    $("#createParentJobForm #contactModalSuccessMsg").html(response.message);
                    $("#createParentJobForm #contactModalSuccessMsg").show();

                    $("#createParentJobModal").modal('hide');
                    $( "#ContractParentJobsCtrl .btn-refresh" ).click();
                    modaloverlap();
                     
                }
                else {
                    bootbox.alert(response.message);
                }

            });
            }
         });    
       });         
   
});

