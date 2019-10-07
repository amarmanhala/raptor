/* global angular, base_url, bootbox, google */

"use strict";
 
        
   app.controller('ContractScheduleCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

          // filter
    $scope.filterOptions = {
        filtertext: '',
        fromdate: '',
        todate: '',
        contractid : $("#contractdetailform #contractid").val() 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.selectedRows = [];
    $scope.docrowselected = false;
    $scope.edit_opt = $('#edit_schedule').val()==='1'?'':'disabled="disabled"';
    $scope.scheduleGrid = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        multiSelect: false,
        columnDefs: [ 
             
            {   displayName:'Name', 
                cellTooltip: true, 
                name: 'name',   
                minWidth: 165 
            }, 
            {   displayName:'Season', 
                cellTooltip: true, 
                name: 'season', 
                width: 100
                 
            },
            { 
                displayName:'Works',
                cellTooltip: true,
                name: 'works',
                width: 120
            },
            {   displayName:'Start Date', 
                cellTooltip: true, 
                name: 'season_start_date', 
                enableFiltering: false, 
                width: 100 
            },
            {   displayName:'End Date', 
                cellTooltip: true, 
                name: 'season_end_date', 
                enableFiltering: false, 
                width: 90 
            },
            {   displayName:'Frequency', 
                cellTooltip: true, 
                name: 'frequency_count', 
                enableFiltering: false, 
                width: 90 
            },
            {   displayName:'Period', 
                cellTooltip: true, 
                name: 'period', 
                enableFiltering: false, 
                width: 90 
            },
            {   displayName:'Visits/Year', 
                cellTooltip: true, 
                name: 'visitsperyear', 
                enableFiltering: false, 
                width: 90 
            },
            {   displayName:'First Job', 
                cellTooltip: true, 
                name: 'firstjobdate', 
                enableFiltering: false, 
                width: 90 
            },
            
            {   displayName:'Last Scheduled', 
                cellTooltip: true, 
                name: 'last_scheduled', 
                enableFiltering: false, 
                width: 140 
            },
            {   displayName:'Sites', 
                cellTooltip: true,
                enableSorting: false,
                name: 'sites', 
                width: 70,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><a href="javascript:void(0)" ng-click="grid.appScope.ScheduledSites(row.entity, row.entity.id)"  ><i class = "fa fa-map-marker" style="font-size:20px;"></i></a></div>'
            },
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'isactive', 
                width:55,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.id}}" '+$scope.edit_opt+'  data-contractid="{{row.entity.contractid}}"  id="chk_schedule_{{row.entity.id}}" class="chk_isactive"  ng-checked="row.entity.isactive == 1" /></div>'
            },
            { 
                displayName:'Edit',
                name: 'id',
                cellTooltip: true,
                enableFiltering: false,  
                 enableSorting: false,
                visible :$('#edit_schedule').val()==='1'?true:false, 
                width: 40,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="Edit" ><a  href="javascript:void(0)" ng-click="grid.appScope.editSchedule(row.entity, row.entity.id)"  ><i class = "fa fa-edit" style="font-size:20px;"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_schedule').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "Delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteSchedule(row.entity)" ><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                schedulePage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               schedulePage();
             });
             
             gridApi.selection.on.rowSelectionChanged($scope,function(row){
                $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
                if($scope.selectedRows.length === 0){
                    $scope.docrowselected = false;
                }
                else{
                    $scope.docrowselected = true;
                }
            });
            
            gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
                $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
                if($scope.selectedRows.length === 0){
                    $scope.docrowselected = false;
                }
                else{
                    $scope.docrowselected = true;
                }
            });
 	
         }
       };
       
       $scope.scheduleAvailableSitesGrid = {
            enableSorting: true,
            enableColumnMenus: false,
            enablePagination:false,
            enablePaginationControls:false,
            columnDefs: [ 
                {   displayName:'labelid', 
                    cellTooltip: true, 
                    visible:false,
                    name: 'labelid', 
                    width:50
                },
                 {   displayName:'Site', 
                    cellTooltip: true, 
                    name: 'site' 
                }//,
//                {   displayName:'Site Ref', 
//                    cellTooltip: true, 
//                    name: 'siteref',   
//                    width: 65 
//                },
//                {   displayName:'State', 
//                    cellTooltip: true, 
//                    name: 'sitestate', 
//                    enableFiltering: false, 
//                    width: 65 
//                },
//                { 
//                    displayName:'Suburb',
//                    cellTooltip: true,
//                    name: 'sitesuburb',
//                    width: 100
//                }, 
//                {   displayName:'Street', 
//                    cellTooltip: true, 
//                    name: 'siteline2', 
//                    enableFiltering: false, 
//                    minWidth: 120 
//                }
            ],
            onRegisterApi: function(gridApi) {
                $scope.gridScheduleAvailableSitesApi = gridApi;
            }
       };
       $scope.scheduleSelectedSitesGrid = {
            enableSorting: true,
            enableColumnMenus: false,
            enablePagination:false,
            enablePaginationControls:false,
            columnDefs: [ 
                {   displayName:'labelid', 
                    cellTooltip: true, 
                    visible:false,
                    name: 'labelid', 
                    width:50
                },
                {   displayName:'Site', 
                    cellTooltip: true, 
                    name: 'site' 
                }//,
//                {   displayName:'Site Ref', 
//                    cellTooltip: true, 
//                    name: 'siteref',   
//                    width: 65 
//                },
//                {   displayName:'State', 
//                    cellTooltip: true, 
//                    name: 'sitestate', 
//                    enableFiltering: false, 
//                    width: 65 
//                },
//                { 
//                    displayName:'Suburb',
//                    cellTooltip: true,
//                    name: 'sitesuburb',
//                    width: 100
//                }, 
//                {   displayName:'Street', 
//                    cellTooltip: true, 
//                    name: 'siteline2', 
//                    enableFiltering: false, 
//                    minWidth: 120 
//                }
            ],
            onRegisterApi: function(gridApi) {
                $scope.gridScheduleSelectedSitesApi = gridApi;
            }
       };
       
        $scope.changeText = function() {
            schedulePage();
        }; 
        
        $scope.changeFilters = function() {
           schedulePage();
        };
        
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext: '',
                fromdate: '',
                todate: '',
                contractid : $("#contractdetailform #contractid").val() 
            };
            $('#ContractScheduleCtrl #filterform input[name="fromdate"]').datepicker('setEndDate', null);
            $('#ContractScheduleCtrl #filterform input[name="todate"]').datepicker('setStartDate', null);
           schedulePage();
        };
        
        $scope.refreshGrid = function() {
            schedulePage();
        };
        
        var schedulePage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'contracts/loadcontractschedules?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{
                    $scope.scheduleGrid.totalItems = response.total;
                    $scope.scheduleGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
       
      
        $scope.exportToExcel = function(){
           var qstring = $.param($scope.filterOptions);
           window.open(base_url+'contracts/exportcontractschedules?'+qstring);
        };


        //schedulePage();
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $(document).on('change', '#scheduleGrid .chk_isactive', function() {
            var id = $(this).val();
            var contractid = $(this).attr('data-contractid');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateScheduleStatus(id, contractid, value);

        }); 
    
        var updateScheduleStatus = function(id, contractid, value) {
 
            var params = { 
                id  : id,
                contractid: contractid,
                isactive: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'contracts/updateschedulestatus', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                    $.each($scope.scheduleGrid.data, function( key, val ) {
                        if(parseInt(val.id) === parseInt(id)){
                            if(parseInt(val.status) === 1){
                                $scope.scheduleGrid.data[key].isactive = 0;
                            }
                            else{
                                $scope.scheduleGrid.data[key].isactive = 1;
                            }
                            return;
                        }
                        
                    });
                     
                }
                else {
                    bootbox.alert(data.message);
                    
                }
            });
        };
         
        $scope.deleteSchedule = function(entity) {

            bootbox.confirm("Are you sure to delete Schedule <b>"+entity.name+"</b>", function(result) {
                if (result) {
                    $scope.overlay = true;
                    $.post( base_url+"contracts/deletecontractschedule", { id:entity.id, contractid:entity.contractid }, function( response ) {
                        $scope.overlay = false;
                        if (response.success) { 
                         
                            bootbox.alert('schedule deleted successfully.');
                            schedulePage();
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.makeScheduleRow = [];
        
        $scope.makeSchedule = function() {
            $scope.makeScheduleRow = [];
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length !== 1){ 
                bootbox.alert('Please select a single schedule for Make Contract Schedule.');
                return false;
            }

            var index = $scope.selectedRows[0];
            
            $('#updatescheduleform').trigger("reset");
            $("#updatescheduleform .alert-danger").hide(); 
            $("#updatescheduleform span.help-block").remove();
            $("#updatescheduleform .has-error").removeClass("has-error");
            $('#updatescheduleform #btnsave').button("reset");
            $('#updatescheduleform #btndelete').button("reset");
            $('#updatescheduleform #btncancel').button("reset");
            $('#updatescheduleform #btnsave').removeAttr("disabled");
            $('#updatescheduleform #btncancel').removeAttr("disabled");
            $('#updatescheduleform #btndelete').removeAttr("disabled");
            $("#updatescheduleform .close").css('display', 'block');
           
            $("#UpdateContractschedulesModal").modal();
            $("#UpdateContractschedulesModal #loading-img").show();
            $("#UpdateContractschedulesModal #schedulegriddiv").hide();
            $("#UpdateContractschedulesModal .modal-footer").hide();
            $("#updatescheduleform #contractid").val($("#contractdetailform #contractid").val()); 
            $("#updatescheduleform #scheduleid").val(index.id); 
    
            $.get( base_url+"contracts/loadcontractscheduledetail", {scheduleid: index.id}, function( response ) {
                
                if (response.success) {
                    
                    $scope.makeScheduleRow = response.data;
                    $("#updatescheduleform #name").val(response.data.name);
                    $("#updatescheduleform #season_start_date").val(response.data.season_start_date);
                    $("#updatescheduleform #season_end_date").val(response.data.season_end_date);
                    $("#updatescheduleform #firstjobdate").val(response.data.firstjobdate);
                    $("#updatescheduleform #last_scheduled").val(response.data.last_scheduled);
                    $("#updatescheduleform #frequency_count").val(index.frequency_count);
                    $("#updatescheduleform #frequency_period").val(index.frequency_period); 
                    //$('#updatescheduleform input[name="schedule_end_date"]').datepicker('setEndDate', response.data.season_end_date);
                    $('#updatescheduleform input[name="schedule_start_date"]').datepicker('setEndDate', response.data.season_end_date);
                    $('#updatescheduleform input[name="schedule_start_date"]').datepicker('setStartDate', response.data.season_start_date);
                    $('#updatescheduleform input[name="schedule_start_date"]').datepicker('setDate', response.data.schedule_from_date);
                    
                    //$('#updatescheduleform input[name="schedule_end_date"]').datepicker('setEndDate', response.data.season_end_date);
                    //$('#updatescheduleform input[name="schedule_end_date"]').datepicker('setStartDate', response.data.schedule_from_date);
                     
                    $("#UpdateContractschedulesModal #loading-img").hide();
                    $("#UpdateContractschedulesModal #schedulegriddiv").show();
                    $("#UpdateContractschedulesModal .modal-footer").show();
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
        };
        
        $scope.editSchedule = function(index, id) {
          
            $('#scheduleform').trigger("reset");
            $("#scheduleform .alert-danger").hide(); 
            $("#scheduleform span.help-block").remove();
            $("#scheduleform .has-error").removeClass("has-error");
            $('#scheduleform #btnsave').button("reset");
            $('#scheduleform #btncancel').button("reset");
            $('#scheduleform #btnsave').removeAttr("disabled");
            $('#scheduleform #btncancel').removeAttr("disabled");
            $("#scheduleform .close").css('display', 'block');
            $("#schedulesModal h4.modal-title").html('Edit Schedule for ' + $("#contractdetailform #name").val());
         
            $("#schedulesModal").modal();
            $("#schedulesModal #loading-img").show();
            $("#schedulesModal #schedulegriddiv").hide();
            $("#scheduleform #contractid").val($("#contractdetailform #contractid").val()); 
            $("#scheduleform #scheduleid").val(id); 
            $("#scheduleform #mode").val('edit');  
           
            setTimeout(function(){ 
                $("#schedulesModal #loading-img").hide();
                $("#schedulesModal #schedulegriddiv").show();

            }, 1000);
            $("#scheduleform #name").val(index.name);
            $("#scheduleform #servicetypeid").val(index.servicetypeid);
            $('#scheduleform input[name="season_start_date"]').datepicker('setEndDate', index.season_end_date);
            $('#scheduleform input[name="season_end_date"]').datepicker('setStartDate', index.season_start_date);
            
            //$('#scheduleform input[name="firstjobdate"]').datepicker('setEndDate', index.season_end_date);
            //$('#scheduleform input[name="firstjobdate"]').datepicker('setStartDate', index.season_start_date);
            $("#scheduleform #seasonid").val(index.seasonid);
            $("#scheduleform #subworksid").val(index.subworksid);
            $("#scheduleform #season_start_date").val(index.season_start_date);
            $('#scheduleform input[name="season_start_date"]').datepicker('setDate', index.season_start_date);
            $("#scheduleform #season_end_date").val(index.season_end_date);
            $('#scheduleform input[name="season_end_date"]').datepicker('setDate', index.season_end_date);
            $("#scheduleform #frequency_count").val(index.frequency_count);
            $("#scheduleform #frequency_period").val(index.frequency_period); 
            $("#scheduleform #visitsperyear").val(index.visitsperyear);
            //$("#scheduleform #firstjobdate").val(index.firstjobdate);
            $('#scheduleform input[name="firstjobdate"]').datepicker('setDate', index.firstjobdate);
            $("#scheduleform #frequency_count").trigger("change");
            $("#scheduleform #maxfloat").val(index.maxfloat);
            $("#scheduleform #last_scheduled").val(index.last_scheduled);
            
            if(parseInt(index.sun_ok) === 1){
                $('#scheduleform input[name="sun_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="sun_ok"]').prop('checked', false);
            } 
            
            if(parseInt(index.mon_ok) === 1){
                $('#scheduleform input[name="mon_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="mon_ok"]').prop('checked', false);
            } 
             
            if(parseInt(index.tue_ok) === 1){
                $('#scheduleform input[name="tue_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="tue_ok"]').prop('checked', false);
            } 
            
            if(parseInt(index.wed_ok) === 1){
                $('#scheduleform input[name="wed_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="wed_ok"]').prop('checked', false);
            } 
            
            if(parseInt(index.thu_ok) === 1){
                $('#scheduleform input[name="thu_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="thu_ok"]').prop('checked', false);
            } 
             
            if(parseInt(index.fri_ok) === 1){
                $('#scheduleform input[name="fri_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="fri_ok"]').prop('checked', false);
            } 
             
            if(parseInt(index.sat_ok) === 1){
                $('#scheduleform input[name="sat_ok"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="sat_ok"]').prop('checked', false);
            } 
             
            if($('#scheduleGrid #chk_schedule_' + id).is(":checked")) {
             
                $('#scheduleform input[name="isactive"]').prop('checked', true);
            }
            else{
                $('#scheduleform input[name="isactive"]').prop('checked', false);
            } 
            
            

        };
        
        $scope.addSchedule = function() { 
         
            
            $('#scheduleform').trigger("reset");
            $("#scheduleform .alert-danger").hide(); 
            $("#scheduleform span.help-block").remove();
            $("#scheduleform .has-error").removeClass("has-error");
            $('#scheduleform #btnsave').button("reset");
            $('#scheduleform #btncancel').button("reset");
            $('#scheduleform #btnsave').removeAttr("disabled");
            $('#scheduleform #btncancel').removeAttr("disabled");
            $("#scheduleform .close").css('display', 'block');
            $("#schedulesModal h4.modal-title").html('Add Schedule for ' + $("#contractdetailform #name").val());
            $("#schedulesModal").modal();
            
            $("#schedulesModal #loading-img").show();
            $("#schedulesModal #schedulegriddiv").hide();
            
            $("#scheduleform #contractid").val($("#contractdetailform #contractid").val()); 
            $("#scheduleform #scheduleid").val(''); 
            $("#scheduleform #mode").val('add');  
            $("#scheduleform #frequency_count").val('1');
            $('#scheduleform #visitsperyear').val('0');
            $('#scheduleform input[name="season_start_date"]').datepicker('setEndDate', null);
            $('#scheduleform input[name="season_end_date"]').datepicker('setStartDate', null);
            
            $('#scheduleform input[name="firstjobdate"]').datepicker('setEndDate', null);
            $('#scheduleform input[name="firstjobdate"]').datepicker('setStartDate', null);
            
            setTimeout(function(){ 
                $("#schedulesModal #loading-img").hide();
                $("#schedulesModal #schedulegriddiv").show();

            }, 1000);
            
            $('#scheduleform input[name="sun_ok"]').prop('checked', true);
            $('#scheduleform input[name="mon_ok"]').prop('checked', true);
            $('#scheduleform input[name="tue_ok"]').prop('checked', true);
            $('#scheduleform input[name="wed_ok"]').prop('checked', true);
            $('#scheduleform input[name="thu_ok"]').prop('checked', true);
            $('#scheduleform input[name="fri_ok"]').prop('checked', true);
            $('#scheduleform input[name="sat_ok"]').prop('checked', true);
            $('#scheduleform input[name="isactive"]').prop('checked', true);
 
            
        };

        $scope.ScheduledSites = function(index, id) {
             
            $scope.ScheduledSitesFilter = '';
            $scope.selectedscheduleid = index.id;
            $("#schedulesModal h4.modal-title").html('Scheduled Sites - '+index.name)
            ScheduledSitesModel();
            $("#ScheduledSitesModal").modal();
             
        };
        $scope.changeScheduledSitesFilters = function() {
            
            if($scope.addlabelids.length+$scope.removelabelids.length > 0){
                bootbox.confirm("Do you want save changes?", function(result) {
                    if (result) {
                        saveScheduleSites();
                    }
                    else{
                        ScheduledSitesModel();
                    }
                });
            }
            else{
                ScheduledSitesModel();
            }
        };
        
        var saveScheduleSites = function() {
            $scope.ScheduledSiteoverlay = true; 
            $.post( base_url+"contracts/savecontractschedulesites", { scheduleid:$scope.selectedscheduleid, addlabelids:$scope.addlabelids, removelabelids:$scope.removelabelids }, function( response ) {
                $scope.ScheduledSiteoverlay = false;
                if (response.success) { 
                    bootbox.alert(response.message);
                    ScheduledSitesModel();
                }
                else {
                    bootbox.alert(response.message);
                }
            });
        };
        
        $scope.clearScheduledSitesFilters = function() {
            $scope.ScheduledSitesFilter = '';
            ScheduledSitesModel();
        };
         
        $scope.manageScheduleSites = function(type) {
            
            var LeftGridRows = $scope.scheduleAvailableSitesGrid.data;
            var RightGridRows = $scope.scheduleSelectedSitesGrid.data;
            
            if(type === 'lhs'){
                var selectedRows = $scope.gridScheduleAvailableSitesApi.selection.getSelectedRows();
            
                if(selectedRows.length === 0){
                    bootbox.alert('Please select one or more Sites.');
                    return false;
                }
                $scope.scheduleAvailableSitesGrid.totalItems = 0;
                $scope.scheduleAvailableSitesGrid.data = [];  

                $scope.scheduleSelectedSitesGrid.totalItems = 0;
                $scope.scheduleSelectedSitesGrid.data = []; 
                $.each(selectedRows, function( key, val ) {
                    var result = false;
                    $.each($scope.removelabelids, function( key1, val1 ) {
                        if(val.labelid === val1){
                            $scope.removelabelids.splice(key1,1);
                            result = true;
                            
                        }
                        
                    });
                    if (!result){
                        $scope.addlabelids.push(val.labelid);
                    }
                    RightGridRows.push(val);
                    for (var i =0; i < LeftGridRows.length; i++)
                    {
                        if (LeftGridRows[i].labelid === val.labelid) {
                           LeftGridRows.splice(i,1);
                           break;
                        }
                    }
                    
                    
                });
                
            }
            if(type === 'rhs'){
                
                var selectedRows = $scope.gridScheduleSelectedSitesApi.selection.getSelectedRows();
            
                if(selectedRows.length === 0){
                    bootbox.alert('Please select one or more Sites.');
                    return false;
                }
                $scope.scheduleAvailableSitesGrid.totalItems = 0;
                $scope.scheduleAvailableSitesGrid.data = [];  

                $scope.scheduleSelectedSitesGrid.totalItems = 0;
                $scope.scheduleSelectedSitesGrid.data = []; 
                $.each(selectedRows, function( key, val ) {
                    var result = false;
                    $.each($scope.addlabelids, function( key1, val1 ) {
                        if(val.labelid === val1){
                            $scope.addlabelids.splice(key1,1);
                            result = true;
                            
                        }
                        
                    });
                    if (!result){
                        $scope.removelabelids.push(val.labelid);
                    }
                    LeftGridRows.push(val);
                    
                    for (var i =0; i < RightGridRows.length; i++)
                    {
                        if (RightGridRows[i].labelid === val.labelid) {
                           RightGridRows.splice(i,1);
                           break;
                        }
                    }
                });
            }
            
            $scope.scheduleAvailableSitesGrid.totalItems = LeftGridRows.length;
            $scope.scheduleAvailableSitesGrid.data = LeftGridRows;  
                    
            $scope.scheduleSelectedSitesGrid.totalItems = RightGridRows.length;
            $scope.scheduleSelectedSitesGrid.data = RightGridRows;  
        };
        
        
        $scope.ScheduledSitesFilter = '';
        $scope.selectedscheduleid = '';
        $scope.addlabelids = [];
        $scope.removelabelids = [];
       var ScheduledSitesModel = function() {
           
            $scope.addlabelids = [];
            $scope.removelabelids = [];
            if($scope.selectedscheduleid === ''){
                return false;
            }
            var params = {
                state  : $scope.ScheduledSitesFilter, 
                contractid : $("#contractdetailform #contractid").val(),
                scheduleid : $scope.selectedscheduleid
            }; 
            $scope.ScheduledSiteoverlay = true; 
            var qstring = $.param(params);
            $http.get(base_url+'contracts/loadcontractscheduleSites?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.ScheduledSiteoverlay = false;
                if (response.success === false) {
                     bootbox.alert(response.message);
                    
                }else{
                    $scope.scheduleAvailableSitesGrid.totalItems = response.data.availableSite.length;
                    $scope.scheduleAvailableSitesGrid.data = response.data.availableSite;  
                    
                    $scope.scheduleSelectedSitesGrid.totalItems = response.data.selectedSite.length;
                    $scope.scheduleSelectedSitesGrid.data = response.data.selectedSite;  
                }
               
            });
       };
       
        $(document).on('click', '#ScheduledSitesModal #modalsave', function() {
            
            if($scope.addlabelids.length+$scope.removelabelids.length > 0){
                $scope.ScheduledSiteoverlay = true;
                $.post( base_url+"contracts/savecontractschedulesites", { scheduleid:$scope.selectedscheduleid, addlabelids:$scope.addlabelids, removelabelids:$scope.removelabelids }, function( response ) {
                    $scope.ScheduledSiteoverlay = false;
                    if (response.success) { 

                        bootbox.alert(response.message);
                        $("#ScheduledSitesModal").modal('hide');
                    }
                    else {
                        bootbox.alert(response.message);
                    }
                });
            }else{
                $("#ScheduledSitesModal").modal('hide');
            }
            
            
            
        });
        
        $(document).on('click', '#schedulesModal #btnsave', function() {

            var name = $("#scheduleform #name");
            var servicetypeid = $('#scheduleform #servicetypeid');
            var season_start_date = $("#scheduleform #season_start_date");
            var season_end_date = $("#scheduleform #season_end_date");
            var frequency_count = $("#scheduleform #frequency_count"); 
            var firstjobdate =  $("#scheduleform #firstjobdate"); 
            var maxfloat = $("#scheduleform #maxfloat"); 
            
            $("#scheduleform span.help-block").remove();
            var validationerror = false;
            if($.trim(name.val()) === "") {
                $(name).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(name.parent());
                validationerror = true;
            } else {
                $(name).parent().removeClass("has-error");
            }
            if($.trim(servicetypeid.val()) === "") {
                $(servicetypeid).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(servicetypeid.parent());
                validationerror = true;
            } else {
                $(servicetypeid).parent().removeClass("has-error");
            }
            
            if($.trim(season_start_date.val()) === "") {
                $(season_start_date).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(season_start_date.parent().parent());
                validationerror = true;
            } else {
                $(season_start_date).parent().parent().removeClass("has-error");
            }
            if($.trim(season_end_date.val()) === "") {
                $(season_end_date).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(season_end_date.parent().parent());
                validationerror = true;
            } else {
                $(season_end_date).parent().parent().removeClass("has-error");
            }
            if($.trim(frequency_count.val()) === "") {
                $(frequency_count).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(frequency_count.parent());
                validationerror = true;
            } else {
                if(parseInt($.trim(frequency_count.val())) === 0) {
                    $(frequency_count).parent().addClass("has-error");
                    $('<span class="help-block">This field is required.</span>').appendTo(frequency_count.parent());
                    validationerror = true;
                } else {
                    $(frequency_count).parent().removeClass("has-error");
                }
                 
            }
            if($.trim(firstjobdate.val()) === "") {
                $(firstjobdate).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(firstjobdate.parent().parent());
                validationerror = true;
            } else {
                $(firstjobdate).parent().parent().removeClass("has-error");
            }
            
            if($.trim(maxfloat.val()) === "") {
                $(maxfloat).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(maxfloat.parent());
                validationerror = true;
            } else {
                $(maxfloat).parent().removeClass("has-error");
            }

            if(validationerror){
                 return false;
            }
           
            $("#scheduleform #btnsave").button('loading'); 
            $("#scheduleform #btncancel").button('loading'); 
            $.post( base_url+"contracts/savecontractschedule", $("#scheduleform").serialize(), function( data ) {
                $('#scheduleform #btnsave').removeAttr("disabled");
                $('#scheduleform #btncancel').removeAttr("disabled");
                
                $('#scheduleform #btnsave').removeClass("disabled");
                $('#scheduleform #btncancel').removeClass("disabled");
                $('#scheduleform #btnsave').html("Save");
                $('#scheduleform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#schedulesModal").modal('hide');
                    schedulePage();
                    bootbox.alert(data.message);
                   
                }
                else{
                     $('#schedulesModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#schedulesModal #btncancel', function() {
            $("#schedulesModal").modal('hide');
        });
        
        
        $(document).on('click', '#updatescheduleform #btndelete', function() {
 
            var season_start_date = $("#updatescheduleform #schedule_start_date");
            var season_end_date = $("#updatescheduleform #schedule_end_date");
            
            $("#updatescheduleform span.help-block").remove();
            var validationerror = false;
            
            if($.trim(season_start_date.val()) === "") {
                $(season_start_date).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(season_start_date.parent().parent());
                validationerror = true;
            } else {
                $(season_start_date).parent().parent().removeClass("has-error");
            }
            if($.trim(season_end_date.val()) === "") {
                $(season_end_date).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(season_end_date.parent().parent());
                validationerror = true;
            } else {
                $(season_end_date).parent().parent().removeClass("has-error");
            }
             

            if(validationerror){
                 return false;
            }
            bootbox.confirm("Delete scheduled jobs from <b>"+$.trim(season_start_date.val())+"</b> to <b>"+$.trim(season_end_date.val())+"</b> ?", function(result) {
                if (result) {
                    $("#updatescheduleform #btndelete").button('loading'); 
                    $("#updatescheduleform #btnsave").button('loading'); 
                    $("#updatescheduleform #btncancel").button('loading'); 
                    $.post( base_url+"contracts/deletecontractschedulework", $("#updatescheduleform").serialize(), function( data ) {
                        $('#updatescheduleform #btnsave').removeAttr("disabled");
                        $('#updatescheduleform #btncancel').removeAttr("disabled");
                        $('#updatescheduleform #btndelete').removeAttr("disabled");

                        $('#updatescheduleform #btndelete').removeClass("disabled");
                        $('#updatescheduleform #btnsave').removeClass("disabled");
                        $('#updatescheduleform #btncancel').removeClass("disabled");
                        $('#updatescheduleform #btnsave').html("Save");
                        $('#updatescheduleform #btncancel').html("Cancel");
                        if(data.success) {

                            $("#UpdateContractschedulesModal").modal('hide');
                            schedulePage();
                            bootbox.alert(data.message);

                        }
                        else{
                             $('#UpdateContractschedulesModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                        }
                    }, 'json');
                }
            });
        });
        
        $(document).on('click', '#updatescheduleform #btnsave', function() {
 
            var season_start_date = $("#updatescheduleform #schedule_start_date");
            var season_end_date = $("#updatescheduleform #schedule_end_date");
            
            $("#updatescheduleform span.help-block").remove();
            var validationerror = false;
            
            if($.trim(season_start_date.val()) === "") {
                $(season_start_date).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(season_start_date.parent().parent());
                validationerror = true;
            } else {
                $(season_start_date).parent().parent().removeClass("has-error");
            }
            if($.trim(season_end_date.val()) === "") {
                $(season_end_date).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(season_end_date.parent().parent());
                validationerror = true;
            } else {
                $(season_end_date).parent().parent().removeClass("has-error");
            }
             

            if(validationerror){
                 return false;
            }
            
            $("#updatescheduleform #btndelete").button('loading'); 
            $("#updatescheduleform #btnsave").button('loading'); 
            $("#updatescheduleform #btncancel").button('loading'); 
            $.post( base_url+"contracts/createcontractschedulework", $("#updatescheduleform").serialize(), function( data ) {
                $('#updatescheduleform #btnsave').removeAttr("disabled");
                $('#updatescheduleform #btncancel').removeAttr("disabled");
                $('#updatescheduleform #btndelete').removeAttr("disabled");
                
                $('#updatescheduleform #btndelete').removeClass("disabled");
                $('#updatescheduleform #btnsave').removeClass("disabled");
                $('#updatescheduleform #btncancel').removeClass("disabled");
                $('#updatescheduleform #btnsave').html("Save");
                $('#updatescheduleform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#UpdateContractschedulesModal").modal('hide');
                    schedulePage();
                    bootbox.alert(data.message);
                   
                }
                else{
                     $('#UpdateContractschedulesModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });
        
    }
]);



app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$( document ).ready(function() {
    
    $("#ContractScheduleCtrl #filterform #fromdate").on('changeDate', function(e) {
        $('#ContractScheduleCtrl #filterform input[name="todate"]').datepicker('setStartDate', e.date);
    });
    
    $("#ContractScheduleCtrl #filterform #todate").on('changeDate', function(e) {
        $('#ContractScheduleCtrl #filterform input[name="fromdate"]').datepicker('setEndDate', e.date);
    });
    
    
    $("#scheduleform #season_start_date").on('changeDate', function(e) {
        $('#scheduleform input[name="season_end_date"]').datepicker('setStartDate', e.date);
        if($('#scheduleform input[name="firstjobdate"]').val() !== ''){
            var firstjobdate = $('#scheduleform input[name="firstjobdate"]').datepicker("getDate");
            if(firstjobdate<e.date){
                $('#scheduleform input[name="firstjobdate"]').datepicker('setDate', e.date);
            }
        }
        $('#scheduleform input[name="firstjobdate"]').datepicker('setStartDate', e.date);
        calculateVisitper();
        calculateMaxFloat();
    });
    
    $("#scheduleform #season_end_date").on('changeDate', function(e) {
        $('#scheduleform input[name="season_start_date"]').datepicker('setEndDate', e.date);
        if($('#scheduleform input[name="firstjobdate"]').val() !== ''){
            var firstjobdate = $('#scheduleform input[name="firstjobdate"]').datepicker("getDate");
            if(firstjobdate>e.date){
                $('#scheduleform input[name="firstjobdate"]').datepicker('setDate', e.date);
            }
        }
        $('#scheduleform input[name="firstjobdate"]').datepicker('setEndDate', e.date);
        calculateVisitper();
        calculateMaxFloat();
    });
   
   
        
    $(document).on('change', '#scheduleform #frequency_count', function() {
         calculateVisitper();
         calculateMaxFloat();
    });
    $(document).on('change', '#scheduleform #frequency_period', function() {
         calculateVisitper();
         calculateMaxFloat();
    });
    
    $("#updatescheduleform #schedule_start_date").on('changeDate', function(e) {
        $('#updatescheduleform input[name="schedule_end_date"]').datepicker('setStartDate', e.date);
        calculateVisitCreated();
    });
    
    $("#updatescheduleform #schedule_end_date").on('changeDate', function(e) {
        $('#updatescheduleform input[name="schedule_start_date"]').datepicker('setEndDate', e.date);
        calculateVisitCreated();
    });
    $(document).on('click', '#updatescheduleform #btnwholeperiod', function() {
        $('#updatescheduleform input[name="schedule_end_date"]').val($('#updatescheduleform input[name="season_end_date"]').val());
        $('#updatescheduleform input[name="schedule_end_date"]').datepicker('setDate', $('#updatescheduleform input[name="season_end_date"]').val());
        calculateVisitCreated();
    });
   
    
    var calculateVisitCreated = function(){
        $('#updatescheduleform #visits_created').val('0');
        if($('#updatescheduleform input[name="schedule_start_date"]').val() === '' || $('#updatescheduleform input[name="schedule_end_date"]').val() === '' || $('#updatescheduleform input[name="frequency_count"]').val() === '' || $('#updatescheduleform input[name="frequency_period"]').val() === ''){

            return false;
        }

        var startdate = $('#updatescheduleform input[name="schedule_start_date"]').datepicker("getDate");
        var enddate = $('#updatescheduleform input[name="schedule_end_date"]').datepicker("getDate");
        var frequency = parseInt($('#updatescheduleform #frequency_count').val());
        var period = $('#updatescheduleform #frequency_period').val(); 
         var diff;
        if(period === "D"){
            diff =  mydiff(startdate, enddate, 'days');
        }
        else if(period === "W"){
            diff =  mydiff(startdate, enddate, 'weeks');
        }
        else{
            diff =  mydiff(startdate, enddate, 'months');
        }
        if(isNaN(diff) || diff === undefined){
            return false;
        }
        var visitsperyear = diff/frequency;
        $('#updatescheduleform #visits_created').val(parseInt(visitsperyear));
    };
    
        var calculateVisitper = function(){
            $('#scheduleform #visitsperyear').val('0');
            if($('#scheduleform input[name="season_start_date"]').val() === '' || $('#scheduleform input[name="season_end_date"]').val() === '' || $('#scheduleform input[name="frequency_count"]').val() === '' || $('#scheduleform input[name="frequency_period"]').val() === ''){
               
                return false;
            }
            
            var startdate = $('#scheduleform input[name="season_start_date"]').datepicker("getDate");
            var enddate = $('#scheduleform input[name="season_end_date"]').datepicker("getDate");
            var frequency = parseInt($('#scheduleform #frequency_count').val());
            var period = $('#scheduleform #frequency_period').val(); 
             var diff;
            if(period === "D"){
                diff =  mydiff(startdate, enddate, 'days');
            }
            else if(period === "W"){
                diff =  mydiff(startdate, enddate, 'weeks');
            }
            else{
                diff =  mydiff(startdate, enddate, 'months');
            }
            if(isNaN(diff) || diff === undefined){
                return false;
            }
            var visitsperyear = diff/frequency;
            $('#scheduleform #visitsperyear').val(parseInt(visitsperyear));
            
        };
        function mydiff(date1,date2,interval) {
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            date1 = new Date(date1);
            date2 = new Date(date2);
            var timediff = date2 - date1;
            if (isNaN(timediff)) return NaN;
            switch (interval) {
                case "years": return date2.getFullYear() - date1.getFullYear();
                case "months": return (
                    ( date2.getFullYear() * 12 + date2.getMonth() )
                    -
                    ( date1.getFullYear() * 12 + date1.getMonth() )
                )+1;
                case "weeks"  : return Math.floor(timediff / week);
                case "days"   : return Math.floor(timediff / day); 
                case "hours"  : return Math.floor(timediff / hour); 
                case "minutes": return Math.floor(timediff / minute);
                case "seconds": return Math.floor(timediff / second);
                default: return undefined;
            }
        }
        function daydiff(first, second) {
            return Math.round((second-first)/(1000*60*60*24));
        }
        var calculateMaxFloat = function(){
            var maxdays = 31;
            if($('#scheduleform input[name="frequency_count"]').val() !== '' && $('#scheduleform input[name="frequency_period"]').val() !== ''){
  
                var frequency = parseInt($('#scheduleform #frequency_count').val());
                var period = $('#scheduleform #frequency_period').val(); 
         
                if(period === "D"){

                    maxdays = parseInt(0);
                }
                else if(period === "W"){ 
                    maxdays = parseInt(frequency*7);
                }
                else{ 
                    maxdays = parseInt(frequency*31);
                }
                
                
            }
            var i;
            var lastfloat = $('#scheduleform #maxfloat').val();
            var optionhtml = '';
            if(maxdays === 0){
                optionhtml ='<option value="0">Select</option>';
            }
            else{
                optionhtml ='<option value="">Select</option>';
            }
           
            for(i=1;i<=maxdays;i++){
                optionhtml = optionhtml + '<option value="'+i+'">'+i+'</option>';
            }
            $('#scheduleform #maxfloat').html(optionhtml);
            if(lastfloat !== ''){
                if(parseInt(lastfloat)<maxdays){
                    $('#scheduleform #maxfloat').val(lastfloat);
                }
            }
            $('#scheduleform #maxfloat').val(maxdays);
        };
    
});