
/* global base_url, angular, bootbox */

"use strict";
if($("#AssetCtrl").length) {
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('AssetCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
        $scope.filterOptions = {
            state: '',
            categoryid: '',
            contractid:'',
            filterText: ''
        };

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };
  
       $scope.gridOptions = {
         paginationPageSizes: [10, 25, 50, 100, 200],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         enableFiltering: false,
         columnDefs: [
            
            { 
                displayName:'Action', 
                field:'assetid', 
                width: 60, 
                visible :$('#edit_asset').val()==='1'?true:false, 
                enableSorting: false, 
                enableFiltering: false, 
                 
                headerCellClass : 'text-center', 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Action</div>',
                cellTemplate: '<div class="ui-grid-cell-contents" ng-if="!row.entity.edit">&nbsp;</div>'+
                              '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.edit">'+
                              '<a title = "edit"   href= "'+base_url+'asset/edit/{{row.entity.assetid}}"><i class= "fa fa-edit"></i></a>&nbsp;'+
                              '</div>'

            },
            { 
                displayName:'Site Address',
                cellTooltip: true,
                name: 'siteline2',
                width: 200
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'sitesuburb',
                enableSorting: true,
                width: 100
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'sitestate',
                enableSorting: true,
                width: 100
            },
            
            { 
                displayName:'Location',
                cellTooltip: true,
                name: 'location',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Category',
                cellTooltip: true,
                name: 'category_name',
                enableSorting: true,
                width: 200
            },
             { 
                displayName:'Manufacturer',
                cellTooltip: true,
                name: 'manufacturer',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Service Tag',
                cellTooltip: true,
                name: 'service_tag',
                enableSorting: true,
                width: 150 
            },
            { 
                displayName:'Purchase Date',
                cellTooltip: true,
                name: 'purchase_date',
                width: 100
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
                getPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               getPage();
             });
         }
       };
       
       $scope.gridOptions.multiSelect = false;
       $scope.gridOptions.modifierKeysToMultiSelect = false;
       $scope.gridOptions.noUnselect = true;
       
       $scope.changeText = function() {
            var text = $scope.filterOptions.filterText;
            if(text.length === 0 || text.length>1) { 
                getPage();
            } 
        };
        $scope.changeFilters = function() {
            getPage();
        };
        $scope.clearFilters = function() {
            paginationOptions.sort = '';
            paginationOptions.field = '';
            $scope.filterOptions = {
                state: '',
                categoryid: '',
                contractid:'',
                filterText: ''
            }; 
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'asset/exportassets?'+$.param($scope.filterOptions));

        };
        
       var getPage = function() {
            if(typeof $scope.filterOptions.filterText === 'undefined') {
                $scope.filterOptions.filterText = '';
            }
                
             
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#AssetCtrl .overlay').show();
             $http.get(base_url+'asset/loadassets?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.gridOptions.totalItems = data.total;
                        $scope.gridOptions.data = data.data;  
                    }
                 
                   $('#AssetCtrl .overlay').hide();
             });
       };

       getPage();
       
       //Asset Service
       $scope.ScheduleService = function() {
           
            
            $('#addScheduleServiceForm').trigger("reset");
            $("#addScheduleServiceForm .status").html('');
            $("#addScheduleServiceForm .alert-danger").hide(); 
            $("#addScheduleServiceForm span.help-block").remove();
            $("#addScheduleServiceForm .has-error").removeClass("has-error");
            $('#addScheduleServiceForm #modalsave').button("reset"); 
            $('#addScheduleServiceForm #btncancel').button("reset");
            $('#addScheduleServiceForm #modalsave').removeAttr("disabled");
            $('#addScheduleServiceForm #btncancel').removeAttr("disabled"); 
            $("#addScheduleServiceForm .close").css('display', 'block');
            $scope.assetcategory = '';
            $("#addScheduleServiceForm #servicetypeid").val(''); 
            $("#addScheduleServiceForm #formtypeid").val(''); 
            $("#addScheduleServiceForm #jobid").val(''); 
            $('#addScheduleServiceForm #rdbselected').prop('checked', true);
             
            $("#ScheduledServiceModal").modal();   
            $scope.assetSelectedRow = [];
            
            $scope.assetAvailableGrid.totalItems = 0;
            $scope.assetAvailableGrid.data = []; 
            
            $scope.assetSelectedGrid.totalItems = $scope.assetSelectedRow.length;
            $scope.assetSelectedGrid.data = $scope.assetSelectedRow;
            getAssetService();
            
            
            
        };
        
        $scope.changeAssetServiceFilters = function() {
            getAssetService();
        };
        
        $scope.clearAssetServiceFilters = function() {
            $scope.assetcategory = '';
            getAssetService();
        };
        
        var getAssetService = function() {
             
            $scope.ScheduleServiceoverlay = true;
            $("#ScheduledServiceModal .modal-footer").hide();
            $scope.selectedassetupper = [];
   
             $http.get(base_url+'asset/loadassetforschedules?category='+$scope.assetcategory  ).success(function (data) {
                    $scope.ScheduleServiceoverlay = false;
                    $("#ScheduledServiceModal .modal-footer").show();
                    if (data.success === false) {
                        bootbox.alert(data.message);
                        $("#ScheduledServiceModal").modal('hide');
                         
                    }else{
                        $scope.assetAvailableGrid.totalItems = data.data.length;
                        $scope.assetAvailableGrid.data = data.data;  
                    }
                  
             });
        };
        
        $scope.assetAvailableGrid = {
            enableSorting: true,
            enableColumnMenus: false,
            multiSelect: true,
            enableRowSelection: true,
            enableSelectAll: true, 
            enableRowHeaderSelection: true,
            enablePagination:false,
            enablePaginationControls:false,
            columnDefs: [
               { 
                    displayName:'Location',
                    cellTooltip: true,
                    name: 'location_text',
                    enableSorting: true,
                    width: 150 
                },
                { 
                    displayName:'Sub Location',
                    cellTooltip: true,
                    name: 'sublocation_text',
                    enableSorting: true,
                    width: 150 
                },
                { 
                    displayName:'Manufacturer',
                    cellTooltip: true,
                    name: 'manufacturer',
                    enableSorting: true,
                    width: 120 
                },
                { 
                    displayName:'Service Tag',
                    cellTooltip: true,
                    name: 'service_tag',
                    enableSorting: true,
                    width: 120 
                },
                { 
                    displayName:'Serial No',
                    cellTooltip: true,
                    name: 'serial_no',
                    enableSorting: true,
                    width: 80 
                },
                { 
                    displayName:'Asset ID',
                    cellTooltip: true,
                    name: 'client_asset_id',
                    enableSorting: true,
                    width: 80 
                },
                 { 
                    displayName:'Last Serviced',
                    cellTooltip: true,
                    name: 'last_service_date',
                    enableSorting: true,
                    width: 110 
                } 
            ],
            onRegisterApi: function(gridApi) {
                $scope.gridAssetAvailableApi = gridApi;
                
                gridApi.selection.on.rowSelectionChanged($scope, function() {
                    $scope.selectedassetupper = $scope.gridAssetAvailableApi.selection.getSelectedRows();
                   
                });
                gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
                    $scope.selectedassetupper = $scope.gridAssetAvailableApi.selection.getSelectedRows();
                 
               });
                

            }
       };
        
        $scope.assetSelectedGrid = {
            enableSorting: true,
            enableColumnMenus: false,
            enablePagination:false,
            enablePaginationControls:false,
            columnDefs: [
                { 
                    displayName:'Location',
                    cellTooltip: true,
                    name: 'location_text',
                    enableSorting: true,
                    width: 150 
                },
                { 
                    displayName:'Sub Location',
                    cellTooltip: true,
                    name: 'sublocation_text',
                    enableSorting: true,
                    width: 150 
                },
                { 
                    displayName:'Manufacturer',
                    cellTooltip: true,
                    name: 'manufacturer',
                    enableSorting: true,
                    width: 120 
                },
                { 
                    displayName:'Service Tag',
                    cellTooltip: true,
                    name: 'service_tag',
                    enableSorting: true,
                    width: 120 
                },
                { 
                    displayName:'Serial No',
                    cellTooltip: true,
                    name: 'serial_no',
                    enableSorting: true,
                    width: 80 
                },
                { 
                    displayName:'Asset ID',
                    cellTooltip: true,
                    name: 'client_asset_id',
                    enableSorting: true,
                    width: 80 
                },
                { 
                    displayName:'Service Type',
                    cellTooltip: true,
                    name: 'servicetype',
                    enableSorting: true,
                    width: 110 
                },
                { 
                    displayName:'CheckList',
                    cellTooltip: true,
                    name: 'checklist',
                    enableSorting: true,
                    width: 110 
                },
                { 
                    displayName:'Activity',
                    cellTooltip: true,
                    name: 'activity',
                    enableSorting: true,
                    width: 100 
                },
                { 
                    displayName:'Delete',
                    name: 'delete',
                    cellTooltip: true,
                    enableFiltering: false,   
                    width: 60,
                    enableSorting: false,
                    headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                    cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "Delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteSelectedAsset(row.entity)" ><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
                }
            ],
            onRegisterApi: function(gridApi) {
                $scope.gridAssetSelectedApi = gridApi;

            }
       };
       
       
       $scope.addSelectedAsset = function() {
            if($scope.selectedassetupper.length === 0){ 
                bootbox.alert('Please select one or more assets from the upper grid.');
                return false;
            }
            
            if($.trim($("#addScheduleServiceForm #servicetypeid").val()) === "") {
                bootbox.alert('Please select Service Type.');
                return false;
            }  
            if($.trim($("#addScheduleServiceForm #checklistid").val()) === "") {
                bootbox.alert('Please select Checklist.');
                return false;
            }  
            if($.trim($("#addScheduleServiceForm #activityid").val()) === "") {
                bootbox.alert('Please select Activity.');
                return false;
            }
            
            $.each($scope.selectedassetupper, function( key, val ) {
                var key1 =0;
                for(key1=$scope.assetSelectedRow.length-1; key1>=0; key1--){
                    if($scope.assetSelectedRow[key1].assetid === val.assetid){
                        $scope.assetSelectedRow.splice(key1,1);
                        break;
                    }
                }
                 
                val.checklistid = $("#addScheduleServiceForm #checklistid").val();
                val.servicetypeid = $("#addScheduleServiceForm #servicetypeid").val();
                val.activityid = $("#addScheduleServiceForm #activityid").val();
                
                val.checklist = $("#addScheduleServiceForm #checklistid option:selected").text();
                val.servicetype = $("#addScheduleServiceForm #servicetypeid option:selected").text();
                val.activity = $("#addScheduleServiceForm #activityid option:selected").text();
                $scope.assetSelectedRow.push(val);
                
                 
            });
            
            var upplerGrid = $scope.assetAvailableGrid.data;
            
            $scope.assetAvailableGrid.totalItems = 0;
            $scope.assetAvailableGrid.data = []; 
            
            $scope.assetSelectedGrid.totalItems = $scope.assetSelectedRow.length;
            $scope.assetSelectedGrid.data = $scope.assetSelectedRow; 
            
            $scope.assetAvailableGrid.totalItems = upplerGrid.length;
            $scope.assetAvailableGrid.data = upplerGrid; 
            
            
       };
       
       $scope.deleteSelectedAsset = function(entity) {

            var key1 =0;
            for(key1=$scope.assetSelectedRow.length-1; key1>=0; key1--){
                if($scope.assetSelectedRow[key1].assetid === entity.assetid){
                    $scope.assetSelectedRow.splice(key1,1);
                    break;
                }
            }
            
            $scope.assetSelectedGrid.totalItems = $scope.assetSelectedRow.length;
            $scope.assetSelectedGrid.data = $scope.assetSelectedRow;
        };
        
        
        $(document).on('click', '#ScheduledServiceModal #modalsave', function() {

            
            if($scope.assetSelectedRow.length === 0){ 
                bootbox.alert('Please select one or more assets from the upper grid.');
                return false;
            }
            var allocateto = $('#addScheduleServiceForm input[name=allocateto]:checked').val();
            if(allocateto === 'selected'){
                var jobid = $("#addScheduleServiceForm #jobid");
                if($.trim(jobid.val()) === "") {
                    bootbox.alert('Please select job for allocation.');
                    return false;
                } 
            }
            
            var assetdata = [];
         
            $.each($scope.assetSelectedRow, function( key, val ) {
                 
                assetdata.push({
                    assetid : val.assetid,
                    servicetypeid : val.servicetypeid,
                    checklistid : val.checklistid,
                    activityid : val.activityid
                });
                
                 
            });
            
            var postdata = {
                allocateto : allocateto,
                jobid : $("#addScheduleServiceForm #jobid").val(),
                assetdata : assetdata
            };
            
            
            $("#addScheduleServiceForm #modalsave").button('loading'); 
            $("#addScheduleServiceForm #btncancel").button('loading'); 
            $.post( base_url+"asset/saveassetservice", postdata, function( data ) {
                $('#addScheduleServiceForm #modalsave').removeAttr("disabled");
                $('#addScheduleServiceForm #btncancel').removeAttr("disabled");
                
                $('#addScheduleServiceForm #modalsave').removeClass("disabled");
                $('#addScheduleServiceForm #btncancel').removeClass("disabled");
                $('#addScheduleServiceForm #modalsave').html("Save");
                $('#addScheduleServiceForm #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#ScheduledServiceModal").modal('hide');
                    bootbox.alert(data.message);
                    if(allocateto === 'new'){
                       window.location.href = base_url+ "logjobquote?tempjobid="+ data.total;
                    }
                }
                else{
                     $('#ScheduledServiceModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });
         
     }
     ]);
}
 