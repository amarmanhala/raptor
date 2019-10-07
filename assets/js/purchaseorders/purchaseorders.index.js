/* global base_url, angular, app */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch',    
app.controller('PurchaseOrdersCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
    $scope.filterOptions = {
        filtertext : '',
        status   : '',
        glcode  : '',
        fromdate: '',
        todate: ''
    };
     var duedate= new Date(); 
    var mm = duedate.getMonth() + 1;
    var y = duedate.getFullYear();
    $scope.loggeddate = '01/'+mm+'/'+y;
    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    $scope.edit_opt = $('#edit_purchaseorder').val()==='1'?'':'disabled="disabled"';
    $scope.purchaseorderGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         multiSelect : false,
         columnDefs: [
            
            
            { 
                displayName:'PO Number',
                cellTooltip: true,
                name: 'ponumber',
                width: 100
            },
            { 
                displayName:'From',
                cellTooltip: true,
                name: 'fromdate',
                width: 100
            },
            { 
                displayName:'To',
                cellTooltip: true,
                name: 'todate',
                width: 100
            },
            { 
                displayName:'Amount',
                cellTooltip: true,
                name: 'amount_ex_tax',
                enableSorting: true,
                width: 100,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.amount_ex_tax_str}}">{{row.entity.amount_ex_tax_str}}</div>'
                 
            },
            { 
                displayName:'Gl Code',
                cellTooltip: true,
                name: 'glcode',
                width: 110
            }, 
             { 
                displayName:'Added Date',
                cellTooltip: true,
                name: 'date_added',
                width: 100
            },
            { 
                displayName:'Amount Used',
                cellTooltip: true,
                name: 'amount_used',
                enableSorting: true,
                width: 120,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.amount_used_str}}">{{row.entity.amount_used_str}}</div>'
                 
            },
            { 
                displayName:'Remaining',
                cellTooltip: true,
                name: 'amount_remaining',
                enableSorting: true,
                width: 100,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.amount_remaining_str}}">{{row.entity.amount_remaining_str}}</div>'
                 
            },
            {   displayName:'Status', 
                cellTooltip: true, 
                name: 'status', 
                width: 80 
            },
            
             { 
                displayName:'Edit',
                field:'edit',
                width: 50,
                visible :$('#edit_purchaseorder').val()==='1'?true:false, 
                enableSorting: false,
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.contactid}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editPurchaseOrder(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
            }, 
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_purchaseorder').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deletePurchaseOrder(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                purchaseOrderPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               purchaseOrderPage();
             });
             
            gridApi.selection.on.rowSelectionChanged($scope, function() {
                    
                var selectedRows = $scope.gridApi.selection.getSelectedRows();
                 
                selectedRows.forEach(function(rowEntity) {
                    if(document.getElementById('selectedponumber')) {
                        $("#selectedponumber").val(rowEntity.ponumber);
                    }
                });
                if(selectedRows.length == 0) {
                    $("#selectedponumber").val('');
                } 
                getAllocatedjobsData();
            });
                
         }
       };
       
      $scope.changeFilter = function() {
           purchaseOrderPage();
       };
       
       $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext : '',
                status   : '',
                glcode  : '',
                fromdate: '',
                todate: ''
            };
            $('#PurchaseOrdersCtrl #filterform input[name="fromdate"]').datepicker('setEndDate', null);
            $('#PurchaseOrdersCtrl #filterform input[name="todate"]').datepicker('setStartDate', null);
            $('.selectpicker').selectpicker('deselectAll');
            purchaseOrderPage();
        };
    
        $scope.exportToExcel = function(){
            var qstring = $.param($scope.filterOptions);
            window.open(base_url+'purchaseorders/exportpurchaseorders?'+qstring);
        };
       $scope.selectedRows = [];
        var purchaseOrderPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'purchaseorders/loadpurchaseorders?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.purchaseorderGrid.totalItems = response.total;
                    $scope.purchaseorderGrid.data = response.data.podate;
                    
                    
                    $('#totlremainexc').html(response.data.remaining.totlremainexc);
                    $('#totlremaininc').html(response.data.remaining.totlremaininc);
                    $("#totlremainexc").attr('class','label '+ response.data.remaining.totlremainexccolor);
                    $("#totlremaininc").attr('class','label '+ response.data.remaining.totlremaininccolor);
                }
                getAllocatedjobsData();
            });
        };
        
       purchaseOrderPage();
       var getAllocatedjobsData = function(){
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length > 0){
               $('.allocatedjobs').show();
               $('.unallocatedjobs').show();
                $('.pon').html($scope.selectedRows[0].ponumber);
                $scope.allocatedjobsummery = {
                    WIP: 0.00,
                    Invoiced: 0.00,
                    Total:0.00,
                    POValue: 0.00,
                    Remaining:0.00
                };
                $scope.allocatedjobsummery.POValue =  $scope.selectedRows[0].amount_ex_tax_str;
                $scope.allocatedjobsummery.Remaining = $scope.selectedRows[0].amount_remaining_str;
           
                
                var params = {
                    ponumber  : $scope.selectedRows[0].ponumber,
                    loggeddate  : $scope.loggeddate
                }; 
                if($scope.selectedRows[0].statuscode === 'CLOSED' || $scope.selectedRows[0].statuscode === 'CANCELLED'){
                    $scope.unAllocatedJobsGrid.enableRowSelection = false;
                    $scope.allocatedJobsGrid.enableRowSelection = false;
                }
                else{
                    $scope.unAllocatedJobsGrid.enableRowSelection = true;
                    $scope.allocatedJobsGrid.enableRowSelection = true;
                }

                var qstring = $.param(params);

                $scope.overlay = true;
                $http.get(base_url+'purchaseorders/loadpurchaseorderjobs?'+ qstring, {
                    headers : {
                        "content-type" : "application/x-www-form-urlencoded"
                    }
                }).success(function(response) {
                    $scope.overlay = false;
                    if (response.success === false) {
                        bootbox.alert(response.message);
                    }else{
                        $scope.unAllocatedJobsGrid.totalItems = response.data.unAllocatedJobs.length;
                        $scope.unAllocatedJobsGrid.data = response.data.unAllocatedJobs;  
                        
                        $scope.allocatedJobsGrid.totalItems = response.data.allocatedjobs.length;
                        $scope.allocatedJobsGrid.data = response.data.allocatedjobs; 
                        
                        $scope.allocatedJobsGrid.data.forEach(function(rowEntity) {
                            $scope.allocatedjobsummery.WIP = $scope.allocatedjobsummery.WIP + parseFloat(rowEntity.wip);
                            $scope.allocatedjobsummery.Invoiced = $scope.allocatedjobsummery.Invoiced + parseFloat(rowEntity.invoiced);
                        });
                       
                        $scope.allocatedjobsummery.WIP = parseFloat($scope.allocatedjobsummery.WIP).toFixed(2);
                        $scope.allocatedjobsummery.Invoiced = parseFloat($scope.allocatedjobsummery.Invoiced).toFixed(2);
                        
                        $scope.allocatedjobsummery.Total = parseFloat($scope.allocatedjobsummery.WIP)  + parseFloat($scope.allocatedjobsummery.Invoiced);  
                        $scope.allocatedjobsummery.Total = parseFloat($scope.allocatedjobsummery.Total).toFixed(2);
                    }

                });
            }
            else{
                $('.allocatedjobs').hide();
                $('.unallocatedjobs').hide();
                $("#selectedponumber").val('')
            }
       };
       
       $scope.allocatedjobsummery = {
            WIP: 0,
            Invoiced: 0,
            Total:0,
            POValue: 0,
            Remaining:0
       };
       $scope.changePoFilter = function (){
           getAllocatedjobsData();
       };
       $scope.deletePurchaseOrder = function(entity) {

            bootbox.confirm("Are you sure to delete Purchase Order <b>"+entity.ponumber+"</b>", function(result) {
                if (result) {
                    $scope.overlay = true;
                    $.post( base_url+"purchaseorders/deletepurchaseorder", { id:entity.id,ponumber:entity.ponumber }, function( response ) {
                        if (response.success) {
                            purchaseOrderPage();
                            $('#purchaseorderstatus').html('<div class="alert alert-success" >Purchase Orders deleted successfully.</div>');
                            clearMsgPanel();
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editPurchaseOrder = function(index, id) {
          
            $('#purchaseOrderForm').trigger("reset");
            $("#purchaseOrderForm #ponumber").attr("readonly", true);
            $("#purchaseOrderForm .alert-danger").hide(); 
            $("#purchaseOrderForm span.help-block").remove();
            $("#purchaseOrderForm .has-error").removeClass("has-error");
            $('#purchaseOrderForm #btnsave').button("reset");
            $('#purchaseOrderForm #btncancel').button("reset");
            $('#purchaseOrderForm #btnsave').removeAttr("disabled");
            $('#purchaseOrderForm #btncancel').removeAttr("disabled");
            $("#purchaseOrderForm .close").css('display', 'block');
            $("#purchaseOrderModal h4.modal-title").html('Edit Purchase Order - ' + index.ponumber);
           
            $("#purchaseOrderForm #customer_po_id").val(id); 
            $("#purchaseOrderForm #mode").val('edit');  
             
            $("#purchaseOrderForm #contactModalErrorMsg").hide(); 
            $("#purchaseOrderForm #contactModalSuccessMsg").hide();
            
            $("#purchaseOrderForm #contactModalErrorMsg").html(''); 
            $("#purchaseOrderForm #contactModalSuccessMsg").html('');
            
            $("#purchaseOrderForm #ponumber").val(index.ponumber);
            $("#purchaseOrderForm #amount_ex_tax").val(index.amount_ex_tax);
            
            $("#purchaseOrderForm #glcode").val(index.glcode); 
            $("#purchaseOrderForm #description").val(index.description);
            
            $("#purchaseOrderForm #fromdate").val(index.fromdate); 
            $('#purchaseOrderForm input[name="fromdate"]').datepicker('setDate', index.fromdate);
            
            $("#purchaseOrderForm #todate").val(index.todate);
            $('#purchaseOrderForm input[name="todate"]').datepicker('setDate', index.todate);
            
            $('#purchaseOrderForm input[name="fromdate"]').datepicker('setEndDate', index.todate);
            $('#purchaseOrderForm input[name="todate"]').datepicker('setStartDate', index.fromdate);
            
            $("#purchaseOrderModal").modal();

        };
        
        
        $scope.addPurchaseOrder = function() { 
          
            $("#purchaseOrderForm #contactModalErrorMsg").hide(); 
            $("#purchaseOrderForm #contactModalSuccessMsg").hide();
            $("#purchaseOrderForm #contactModalErrorMsg").html(''); 
            $("#purchaseOrderForm #contactModalSuccessMsg").html('');
            $('#purchaseOrderForm').trigger("reset");
            $("#purchaseOrderForm #ponumber").removeAttr("readonly");
            $("#purchaseOrderForm .alert-danger").hide(); 
            $("#purchaseOrderForm span.help-block").remove();
            $("#purchaseOrderForm .has-error").removeClass("has-error");
            $('#purchaseOrderForm #btnsave').button("reset");
            $('#purchaseOrderForm #btncancel').button("reset");
            $('#purchaseOrderForm #btnsave').removeAttr("disabled");
            $('#purchaseOrderForm #btncancel').removeAttr("disabled");
            $("#purchaseOrderForm .close").css('display', 'block');
            $("#purchaseOrderModal h4.modal-title").html('Add Purchase Order');
            $("#purchaseOrderForm #customer_po_id").val(''); 
            $("#purchaseOrderForm #mode").val('add');  
            $('#purchaseOrderForm input[name="fromdate"]').datepicker('setEndDate', null);
            $('#purchaseOrderForm input[name="todate"]').datepicker('setStartDate', null);
            
            $("#purchaseOrderModal").modal();
        };
        
        
        
        //Recalculate
        $(document).on('click', '#btnrecalculate', function(){
      

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length !== 1){
                bootbox.alert('Please select a single PO for Recalculate.');
                return false;
            }

            console.log('Click PO Recalculate');
            var id = $scope.selectedRows[0].id;
            var ponumber = $scope.selectedRows[0].ponumber;
            
            
            $scope.overlay = true;
            $.post(base_url + "purchaseorders/recalculatepototal", {id: id,ponumber:ponumber}, function (data) {
                if (data.success) {
                    $('#purchaseorderstatus').html('<div class="alert alert-success" >Recalculate Totals DONE.</div>');
                    clearMsgPanel();
                    purchaseOrderPage(); 


                } else {
                    $('#purchaseorderstatus').html('<div class="alert alert-danger" >' + data.message + '</div>');
                    clearMsgPanel();
                }
            }, 'json');
                   
            
                
        });
        //Cancel PO
        $(document).on('click', '#btncancelpo', function(){
      

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length !== 1){
                bootbox.alert('Please select a single PO for cancel.');
                return false;
            }

            console.log('Click PO Cancel');
            var id = $scope.selectedRows[0].id;
            var ponumber = $scope.selectedRows[0].ponumber;
            
             
            bootbox.confirm({
                message: 'Cancel this PO and release all allocated jobs?',
                buttons: {
                    confirm: {
                        label: 'OK',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-default'
                    }
                },
                callback: function (confirmed) {
                    if (confirmed) {
                        $scope.overlay = true;
                        $.post(base_url + "purchaseorders/cancelpurchaseorder", {id: id,ponumber:ponumber}, function (data) {
                            if (data.success) {
                                $('#purchaseorderstatus').html('<div class="alert alert-success" >PO Cancelled.</div>');
                                clearMsgPanel();
                                purchaseOrderPage(); 
                                
                                
                            } else {
                                $('#purchaseorderstatus').html('<div class="alert alert-danger" >' + data.message + '</div>');
                                clearMsgPanel();
                            }
                        }, 'json');
                    }
                }
            });
                
            
                
        });
        
        //Cancel PO
        $(document).on('click', '#btnlockpo', function(){
      

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length !== 1){
                bootbox.alert('Please select a single PO for Lock.');
                return false;
            }

            console.log('Click PO Lock');
            var id = $scope.selectedRows[0].id;
            var ponumber = $scope.selectedRows[0].ponumber;
            
             
            bootbox.confirm({
                message: 'Close this PO?',
                buttons: {
                    confirm: {
                        label: 'OK',
                        className: 'btn-primary'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-default'
                    }
                },
                callback: function (confirmed) {
                    if (confirmed) {
                        $scope.overlay = true;
                        $.post(base_url + "purchaseorders/closepurchaseorder", {id: id,ponumber:ponumber}, function (data) {
                            if (data.success) {
                                $('#purchaseorderstatus').html('<div class="alert alert-success" >PO Closed.</div>');
                                clearMsgPanel();
                                purchaseOrderPage(); 
                                
                                
                            } else {
                                $('#purchaseorderstatus').html('<div class="alert alert-danger" >' + data.message + '</div>');
                                clearMsgPanel();
                            }
                        }, 'json');
                    }
                }
            });
                
            
                
        });
        
        $scope.unAllocatedJobsGrid = {
            paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: false,
         useExternalSorting: false,
         enableColumnResizing: true,
         enableColumnMenus: false,
         multiSelect : false,
         columnDefs: [
            { 
                displayName:$('#custordref1_label').val(),
                cellTooltip: true,
                name: 'custordref',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:$('#custordref2_label').val(),
                cellTooltip: true,
                name: 'custordref2',
                enableSorting: true,
                width: 100 
            },
            { 
                displayName:'Job ID',
                name: 'jobid',  
                width:80,
                cellTooltip: true,
                cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a></div>'
            },
            { 
                displayName:'Log Date',
                cellTooltip: true,
                name: 'temp_leaddate',
                enableSorting: true, 
                width: 80,
                cellTemplate: '<div class="ui-grid-cell-contents" >{{row.entity.leaddate}}</div>'
            },
             { 
                displayName:'Due Date',
                cellTooltip: true,
                name: 'temp_jcompletedate',
                enableSorting: true,
                width: 80,
                cellTemplate: '<div class="ui-grid-cell-contents" >{{row.entity.jcompletedate}}</div>'
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'sitestate', 
                width: 70 
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'sitesuburb', 
                width: 90 
            },
            { 
                displayName:'Cost Center',
                cellTooltip: true,
                name: 'accountname',
                width: 100
            },
            { 
                displayName:'Site Ref',
                cellTooltip: true,
                name: 'siteref',
                width: 80
            },
            { 
                displayName:'Job Stage',
                cellTooltip: true,
                name: 'portaldesc',
                width: 100
            },
            {   displayName:'Quoted', 
                cellTooltip: true,
                enableSorting: false,
                name: 'quoterqd', 
                width:70,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Quoted</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.jobid}}" disabled="disabled"   class="chk_quoterqd"  ng-checked="row.entity.quoterqd == \'on\'" /></div>'
            },
            { 
                displayName:'WIP',
                cellTooltip: true,
                name: 'wip',
                enableSorting: true,
                width: 80,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.wip_str}}">{{row.entity.wip_str}}</div>'
                 
            },
            { 
                displayName:'Invoiced',
                cellTooltip: true,
                name: 'invoiced',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.invoiced_str}}">{{row.entity.invoiced_str}}</div>'
                 
            },
            { 
                displayName:'Invoice No',
                cellTooltip: true,
                name: 'custinvoiceno',
                width: 100
            }
         ],
         onRegisterApi: function(gridApi) {
             $scope.unAllocatedJobsGridApi = gridApi;
             
             
                
            }
        };
        
        $scope.allocatedJobsGrid = {
            paginationPageSizes: [10, 25, 50,100],
            paginationPageSize: 25,
            useExternalPagination: false,
            useExternalSorting: false,
            enableColumnResizing: true,
            enableColumnMenus: false,
            multiSelect : false,
            columnDefs: [
                { 
                    displayName:'PO Number',
                    cellTooltip: true,
                    name: 'ponumber',
                    width: 100
                },
                { 
                    displayName:$('#custordref1_label').val(),
                    cellTooltip: true,
                    name: 'custordref',
                    enableSorting: true,
                    width: 100 
                },

                { 
                    displayName:'Job ID',
                    name: 'jobid',  
                    width:80,
                    cellTooltip: true,
                    cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a></div>'

                },
                { 
                    displayName:'Log Date',
                    cellTooltip: true,
                    name: 'temp_leaddate',
                    enableSorting: true,
                    width: 80,
                    cellTemplate: '<div class="ui-grid-cell-contents" >{{row.entity.leaddate}}</div>'
                },
                 { 
                    displayName:'Due Date',
                    cellTooltip: true,
                    name: 'temp_duedate',
                    enableSorting: true,
                    width: 80,
                    cellTemplate: '<div class="ui-grid-cell-contents" >{{row.entity.duedate}}</div>'
                },
                { 
                    displayName:'State',
                    cellTooltip: true,
                    name: 'sitestate', 
                    width: 70 
                },
                { 
                    displayName:'Suburb',
                    cellTooltip: true,
                    name: 'sitesuburb', 
                    width: 90 
                },
                { 
                    displayName:'Cost Center',
                    cellTooltip: true,
                    name: 'accountname',
                    width: 100
                },
                { 
                    displayName:'Site Ref',
                    cellTooltip: true,
                    name: 'siteref',
                    width: 80
                },
                { 
                    displayName:'Job Stage',
                    cellTooltip: true,
                    name: 'portaldesc',
                    width: 100
                },
                {   displayName:'Quoted', 
                    cellTooltip: true,
                    enableSorting: false,
                    name: 'quoterqd', 
                    width:70,
                    headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Quoted</div>',
                    cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.jobid}}" disabled="disabled"   class="chk_quoterqd"  ng-checked="row.entity.quoterqd == \'on\'" /></div>'
                },
                { 
                    displayName:'WIP',
                    cellTooltip: true,
                    name: 'wip',
                    enableSorting: true,
                    width: 80,
                    cellClass: 'text-right', 
                    headerCellClass : 'text-right',
                    cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.wip_str}}">{{row.entity.wip_str}}</div>'
                },
                { 
                    displayName:'Invoiced',
                    cellTooltip: true,
                    name: 'invoiced',
                    enableSorting: true,
                    width: 90,
                    cellClass: 'text-right', 
                    headerCellClass : 'text-right',
                    cellTemplate: '<div class="ui-grid-cell-contents text-right" title="{{row.entity.invoiced_str}}">{{row.entity.invoiced_str}}</div>'
                },
                { 
                    displayName:'Invoice No',
                    cellTooltip: true,
                    name: 'custinvoiceno',
                    width: 100
                }
            ],
            onRegisterApi: function(gridApi) {
                $scope.allocatedJobsGridApi = gridApi;
            }
        };
       
        //Cancel PO
        $(document).on('click', '#btnaddjob', function(){
      
            if($scope.unAllocatedJobsGrid.totalItems  ===0){
                bootbox.alert('Please select a Job for Allocate to PO.');
                return false;
            }
            
            $scope.selectedRows = $scope.unAllocatedJobsGridApi.selection.getSelectedRows();
            if($scope.selectedRows.length !== 1){
               bootbox.alert('Please select a Job for Allocate to PO.');
                return false;
            }

           
            console.log('Click Job Allocate to PO');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
                jobids.push(rowEntity.jobid);
            });
            $scope.selectedPORows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedPORows.length ===0 ){
               bootbox.alert('Please select a PO again');
               return false;
            }
            var id = $scope.selectedPORows[0].id;
            var ponumber = $scope.selectedPORows[0].ponumber;
            $scope.overlay = true;
            $.post(base_url + "purchaseorders/allocatejobtopo", {id:id, jobids: jobids,ponumber:ponumber}, function (data) {
                if (data.success) {
                    $('#purchaseorderstatus').html('<div class="alert alert-success" >Jobs Allocated to PO.</div>');
                    clearMsgPanel();
                    purchaseOrderPage(); 


                } else {
                    $('#purchaseorderstatus').html('<div class="alert alert-danger" >' + data.message + '</div>');
                    clearMsgPanel();
                }
            }, 'json');
                
        });
        
        //Cancel PO
        $(document).on('click', '#btnremovejob', function(){
      

            if($scope.allocatedJobsGrid.totalItems  ===0){
                bootbox.alert('Please select a Job for remove Allocate to PO.');
                return false;
            }
            
            $scope.selectedRows = $scope.allocatedJobsGridApi.selection.getSelectedRows();
            if($scope.selectedRows.length !== 1){
               bootbox.alert('Please select a Job for remove Allocate to PO.');
                return false;
            }

            console.log('Click Job Remove from Allocated to PO');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
                jobids.push(rowEntity.jobid);
            });
             $scope.selectedPORows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedPORows.length ===0 ){
               bootbox.alert('Please select a PO again');
               return false;
            }
            var id = $scope.selectedPORows[0].id;
            var ponumber = $scope.selectedPORows[0].ponumber;
            $scope.overlay = true;
            $.post(base_url + "purchaseorders/removejobtopo", {id: id, jobids: jobids,ponumber:ponumber}, function (data) {
                if (data.success) {
                    $('#purchaseorderstatus').html('<div class="alert alert-success" >Jobs Removed from PO.</div>');
                    clearMsgPanel();
                    purchaseOrderPage(); 


                } else {
                    $('#purchaseorderstatus').html('<div class="alert alert-danger" >' + data.message + '</div>');
                    clearMsgPanel();
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

    $("#PurchaseOrdersCtrl #filterform #fromdate").on('changeDate', function(e) {
        $('#PurchaseOrdersCtrl #filterform input[name="todate"]').datepicker('setStartDate', e.date);
    });
    
    $("#PurchaseOrdersCtrl #filterform #todate").on('changeDate', function(e) {
        $('#PurchaseOrdersCtrl #filterform input[name="fromdate"]').datepicker('setEndDate', e.date);
    });
    
    
    $("#purchaseOrderForm #fromdate").on('changeDate', function(e) {
        $('#purchaseOrderForm input[name="todate"]').datepicker('setStartDate', e.date);
         
    });
    
    $("#purchaseOrderForm #todate").on('changeDate', function(e) {
        $('#purchaseOrderForm input[name="fromdate"]').datepicker('setEndDate', e.date);
         
    });
   
    
  
   
    
    if (typeof $.fn.validate === "function") {         
       
        
        $("#purchaseOrderForm").validate({
            rules: {
                ponumber: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                amount_ex_tax: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                glcode: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                fromdate: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                todate: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                }
                
            },
            errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                }
                else{
                   $(e).parent().parent().removeClass('has-info').addClass('has-error');
                } 
            },
            success: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().parent().removeClass("has-error");
                }
               $(e).remove();
            },

            errorPlacement: function (error, element) {
			 
			if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
				var controls = element.closest('div[class*="col-"]');
				if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
				else error.appendTo(element.nextAll('.lbl:eq(0)').eq(0));
			}
			else if(element.is('.select2')) {
				error.appendTo(element.siblings('[class*="select2-container"]:eq(0)'));
			}
			else if(element.is('.chosen-select')) {
				error.appendTo(element.siblings('[class*="chosen-container"]:eq(0)'));
			}
                        else if(element.parent().is('.input-group')) {
				error.appendTo(element.parent().parent());
			}
			else error.appendTo(element.parent());
		},
            unhighlight: function(e, errorClass, validClass) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().removeClass("has-error");
                }
            },
            submitHandler: function() {
                $("span:eq(0)", "#purchaseOrderForm #btnsave").css("display", 'block');
                $("span:eq(1)", "#purchaseOrderForm #btnsave").css("display", 'none');
                $("#purchaseOrderForm #btncancel").button('loading');
                
                $("#purchaseOrderForm #contactModalErrorMsg").hide(); 
                $("#purchaseOrderForm #contactModalSuccessMsg").hide();
                $.post( base_url+"purchaseorders/savepurchaseorder", $('#purchaseOrderForm').serialize(), function( response ) {
                    $("span:eq(0)", "#purchaseOrderForm #btnsave").css("display", 'none');
                    $("span:eq(1)", "#purchaseOrderForm #btnsave").css("display", 'block');
                    $("#purchaseOrderForm #btncancel").button('reset');
                    if (response.success) {
                        if (response.data.success) {
                            $("#purchaseOrderForm #contactModalSuccessMsg").html(response.message);
                            $("#purchaseOrderForm #contactModalSuccessMsg").show();
                             
                            $("#purchaseOrderModal").modal('hide');
                            $( "#PurchaseOrdersCtrl .btn-refresh" ).click();
                            modaloverlap();
                        }
                        else{
                            $("#purchaseOrderForm #contactModalErrorMsg").html(response.data.message);
                            $("#purchaseOrderForm #contactModalErrorMsg").show();
                        }
                    }
                    else {
                        bootbox.alert(response.message);
                    }
                    
                });
                
                return false;
            }
        });
      
    }
    
});
function clearMsgPanel(){
    setTimeout(function(){ 
            $("#purchaseorderstatus").html('');
           
    }, 3000);
}