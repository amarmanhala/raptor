/* global angular, base_url, bootbox, app */

"use strict";
if($("#waitingApprovalJobsCtrl").length) {
    app.controller('waitingApprovalJobsCtrl', [
    '$scope', '$http', 'uiGridConstants', '$timeout', function($scope, $http, uiGridConstants, $timeout) {


        // filter
        $scope.filterOptions = {
            state: '',
            suburb: '',
            contactid: '',
            filterText: '' 
        }; 

       var paginationOptions = {
            pageNumber: 1,
            pageSize: 25,
            sort: '',
            field: ''
       };
       
       


        $scope.gridWaitingApproval = {
            paginationPageSizes: [10, 25, 50, 100],
            paginationPageSize: 25,
            useExternalPagination: true,
            useExternalSorting: true,
            enableColumnMenus: false, 
            columnDefs: [
//                    { 
//                        displayName:'Select',
//                        cellTooltip: true,
//                        enableSorting: false,
//                        name: 'select',
//                        width: 50,
//                        headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="waitingapprovaltbl"/></div>',
//                        cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="waitingapprovaljobid[]"  data-targetdiv="waitingapprovaltbl" value="{{row.entity.jobid}}"  /></div>'
//                    }, 
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        //pinnedLeft:true,
                        width:80,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span   data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
                    { 
                        displayName:$('#custordref1_label').val(),
                        cellTooltip: true,
                        name: 'custordref',
                        enableSorting: true,
                        width: 110 
                    },
                    { 
                        displayName:'Gl-Code',
                        cellTooltip: true,
                        name: 'glcode',
                        enableSorting: true,
                        width: 80 
                    },
                    { 
                        displayName:'Date In',
                        cellTooltip: true,
                        name: 'leaddate',
                        enableSorting: true,
                        width: 80 
                    },
                    { 
                        displayName:'Due Date',
                        cellTooltip: true,
                        name: 'duedate',
                        enableSorting: true,
                        width: 80 
                    },
                    { 
                        displayName:'Site',
                        cellTooltip: true,
                        name: 'site', 
                        width: 200,
                        cellTemplate: '<div class="ui-grid-cell-contents"  title="{{row.entity.site}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
                    { 
                        displayName:'Suburb',
                        cellTooltip: true,
                        name: 'sitesuburb', 
                        width: 90 
                    },
                    { 
                        displayName:'Site FM',
                        cellTooltip: true,
                        name: 'sitefm', 
                        width: 120 
                    },
                    { 
                        displayName:'Job Description',
                        cellTooltip: true,
                        name: 'shortdescription',
                        enableSorting: false,
                        width: 300,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
                    { 
                        displayName:'Job Stage',
                        cellTooltip: true,
                        name: 'portaldesc',
                        enableSorting: true,
                        width: 150 
                    }

            ],
            onRegisterApi: function(gridApi) {
                $scope.gridApi = gridApi;

                gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
                    paginationOptions.pageNumber = newPage;
                    paginationOptions.pageSize = pageSize;
                    getPage();
                });

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
                
                gridApi.selection.on.rowSelectionChanged($scope, function() {
                    
                    var selectedRows = $scope.gridApi.selection.getSelectedRows();
                    
                    selectedRows.forEach(function(rowEntity) {
                        if(document.getElementById('widget_jobid')) {
                            $("#widget_jobid").val(rowEntity.jobid);
                        }
                    });
                    if(selectedRows.length == 0) {
                        $("#widget_jobid").val('');
                    }
                    getBudgetWidgetData();
                });
                
                //ROWS RENDER
                gridApi.core.on.rowsRendered($scope, function () {
                     
                    // each rows rendered event (init, filter, pagination, tree expand)
                    // Timeout needed : multi rowsRendered are fired, we want only the last one
                    if (rowsRenderedTimeout) {
                        $timeout.cancel(rowsRenderedTimeout);
                    }
                    
                    rowsRenderedTimeout = $timeout(function () {
                        alignContainers('#waitingapprovaltbl', $scope.gridApi.grid);
                    });
                    
                });

                //SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#waitingapprovaltbl', $scope.gridApi.grid);
                });
            }
        };
        
     
        //$scope.gridWaitingApproval.multiSelect = false;
        //$scope.gridWaitingApproval.modifierKeysToMultiSelect = false;
        //$scope.gridWaitingApproval.noUnselect = true;

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
                suburb: '',
                contactid: '',
                filterText: '' 
            }; 
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
//        $scope.$watch(function() {
//            $('#waitingApprovalJobsCtrl .selectpicker').each(function() {
//                $(this).selectpicker('refresh');
//            });
//        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'jobs/exportexcel/waitingapproval?'+$.param($scope.filterOptions));

        };

        var getPage = function() {
     
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#waitingApprovalJobsCtrl .overlay').show();
            $http.get(base_url+'jobs/loadwaitingapprovaljobs?'+ qstring ).success(function (data) {
           
                    $('#waitingApprovalJobsCtrl .overlay').hide();
                    if (data.success === false) {
                        bootbox.alert(data.message);
                        return false;
                    }else{ 
                        $scope.gridWaitingApproval.totalItems = data.total;
                        $scope.gridWaitingApproval.data = data.data;  
                    } 

              });

        }; 
        
        //getPage();
        
        $(document).on('click', '#waitingApprovalJobsCtrl #approvebtn', function() {
            
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for approval.');
                return false;
            }

            console.log('click Waiting Approval >> Approve');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                jobids.push(rowEntity.jobid);

            });


            bootbox.confirm('Are you sure you want to approve <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $.post( base_url+"jobs/updatejobapprove", {jobids: jobids}, function( data ) {
                        if(data.success) {
                            $('#myjobsstatus').html('<div class="alert alert-success" >Jobs approved and ready for DCFM approval.</div>');
                            clearMsgPanel();

                            $("#waitingApprovalJobsCtrl .btn-refresh" ).click();
                            $("#waitingDCFMReviewJobsCtrl .btn-refresh" ).click();

                        }
                        else{
                             $('#myjobsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
                             clearMsgPanel();
                        }
                    }, 'json');
                }
            });

        });

        $(document).on('click', '#waitingApprovalJobsCtrl #declinebtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for decline.');
                return false;
            }

            console.log('click Waiting Approval >> decline');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                jobids.push(rowEntity.jobid);

            });


            bootbox.confirm('Are you sure you want to decline <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $.post( base_url+"jobs/updatejobdecline", {jobids: jobids}, function( data ) {
                        if(data.success) {
                            $('#myjobsstatus').html('<div class="alert alert-success" >Jobs declined.</div>');
                            clearMsgPanel(); 
                            $("#waitingApprovalJobsCtrl .btn-refresh" ).click();  
                        }
                        else{
                             $('#myjobsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
                             clearMsgPanel();
                        }
                    }, 'json');
                }
            });

        });

        $(document).on('click', '#waitingApprovalJobsCtrl #requestquotebtn', function() {
 
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for quote request.');
                return false;
            }

            console.log('click Waiting Approval');
            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
               
                jobids.push(rowEntity.jobid);

            });


            bootbox.confirm('Are you sure you want to quote request <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $.post( base_url+"jobs/updatejobrequestquote", {jobids: jobids}, function( data ) {
                        if(data.success) {
                            $('#myjobsstatus').html('<div class="alert alert-success" >Job Quote requested.</div>');
                            clearMsgPanel(); 
                            $("#waitingApprovalJobsCtrl .btn-refresh" ).click();  
                        }
                        else{
                             $('#myjobsstatus').html('<div class="alert alert-danger" >'+data.message+'</div>');
                             clearMsgPanel();
                        }
                    }, 'json');
                }
            });

        });
       
        $(document).on('click', '#waitingApprovalJobsCtrl #allocatebtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length !== 1){ 
                bootbox.alert('Please select a single job for allocation.');
                return false;
            }

            var jobid = $scope.selectedRows[0].jobid;
            $("#allocationModal").modal();
 
            $("#allocationform #jobid").val(jobid);
            $("#allocationModal #btnsave").button('reset');
            $('#allocationModal #btnsave').removeAttr("disabled");
            $('#allocationModal .status').html('');
            $('#allocationform').trigger("reset");
            $("#allocationform .alert-danger").hide(); 
            $("#allocationform span.help-block").remove();
            $("#allocationform .has-error").removeClass("has-error");
            $('#allocationform #btnsave').button("reset");
            $('#allocationform #btncancel').button("reset");
            $('#allocationform #btnsave').removeAttr("disabled");
            $('#allocationform #btncancel').removeAttr("disabled");
            $("#allocationform .close").css('display', 'block');
            $("#allocationModal h4.modal-title").html('Job Allocate - ' + jobid);
            $("#allocationModal #jbid").html(jobid);
            $("#allocationModal #jdesc").html($scope.selectedRows[0].jobdescription);
            
            $('#allocationform #rdbdcfm').prop('checked', true);
            $("#allocationform #supplierid").val('');
            $("#allocationform #supplierid").selectpicker('refresh');
            $("#allocationform #internasupplierid").val('');
            $("#allocationform #internasupplierid").selectpicker('refresh');
            $('#othersupplierdiv').hide();
            $('#internalsupplierdiv').hide();
              
        });

        $(document).on('click', "#allocationform input[name=allocateto]", function() {
            var typecode = $(this).val();
      
            $('#othersupplierdiv').hide();
            $('#internalsupplierdiv').hide();
            
            if(typecode === 'Supplier'){
               $('#othersupplierdiv').show();
            }
            if(typecode === 'Internal'){
               $('#internalsupplierdiv').show();
            }
            
        });
        
        
        
                
        $(document).on('click', '#waitingApprovalJobsCtrl #wa_updateglcode', function() {
            
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            var glcode = $("#wa_glcode").val();
            if(glcode === ''){
                bootbox.alert('Please select gl code.');
                return false;
            }
            
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select one or more job for glcode update.');
                return false;
            }

            var  jobids= [];
            $scope.selectedRows.forEach(function(rowEntity) {
                jobids.push(rowEntity.jobid);
            });

            bootbox.confirm('Are you sure you want to update Gl Code for <b>"'+ jobids.length+ '"</b> jobs?', function(result) {
                if(result) {
                    $("#wa_updateglcode").button('loading');
                    $.post( base_url+"jobs/updatemultipleglcodes", { jobids: jobids, glcode:glcode }, function( response ) {
                        if(response.success) {
                            $("#wa_updateglcode").button('reset');
                            $("#waitingApprovalJobsCtrl .btn-refresh" ).click();
                        }
                        else{
                            bootbox.alert(response.message);
                        }
                    }, 'json');
                }
            });
        });

        
        
        
        
        
        
        
        
        
        
        
    }
    ]);

    app.filter('trusted', function ($sce) {
        return function (value) {
            return $sce.trustAsHtml(value);
        };
    });
}

$( document ).ready(function() {
     
    $(document).on('click', '#allocationModal #btnsave', function() {

       
 
        $("#allocationform span.help-block").remove();
        
        var typecode = $('#allocationform input[name=allocateto]:checked').val();
        
        if(typecode === 'Supplier'){
            var supplierid = $("#allocationform #supplierid");
            if($.trim(supplierid.val()) === "") {
                $(supplierid).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(supplierid.parent().parent());
                return false;
            } else {
                $(supplierid).parent().parent().removeClass("has-error");
            }
 
        }
        if(typecode === 'Internal'){
            var supplierid = $("#allocationform #internasupplierid");
            if($.trim(supplierid.val()) === "") {
                $(supplierid).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(supplierid.parent().parent());
                return false;
            } else {
                $(supplierid).parent().parent().removeClass("has-error");
            }
 
        }
        $("#allocationform #btnsave").button('loading'); 

        $.post( base_url+"jobs/updatejoballocation", $("#allocationform").serialize(), function( response ) {
            $("#allocationModal #btnsave").button('reset');
            $('#allocationModal #btnsave').removeAttr("disabled");
            if(response.success) {
                if(response.data.success){
//                    if(typecode === 'Internal'){
//                         bootbox.alert('Job allocated to internal Supplier.');
//                        var jobid= $("#allocationform #jobid").val();
//                        window.location.href = base_url+ "schedule/allocate/"+ jobid;
//                    }
//                    else{
                        $("#allocationModal").modal('hide');
                        $('#myjobsstatus').html('<div class="alert alert-success" >Job allocated.</div>');
                        clearMsgPanel(); 

                        $("#waitingApprovalJobsCtrl .btn-refresh" ).click();
                        $("#waitingDCFMReviewJobsCtrl .btn-refresh" ).click();
                   // }
                }
                else{
                    bootbox.dialog({
                                     message: "<span class='bigger-110'>"+response.data.message+"</span>",
                                     buttons: 			
                                     {
                                        "click" :
                                         {
                                             "label" : "OK",
                                             "className" : "btn-sm btn-success",
                                             callback: function() {
                                                  window.open(base_url+'suppliers');
                                            }
                                         },
                                         "cancel" :
                                         {
                                            "label" : "Cancel",
                                            "className" : "btn-sm  btn-primary falcon-warning-btn",
                                            callback: function() {
                                               modaloverlap();
                                            }
                                         }
                                     }
                                });
                        
                        $('#allocationModal .status').html('<div class="alert alert-danger" >'+response.data.message+'</div>');
                }
            }
            else{
                 $('#allocationModal .status').html('<div class="alert alert-danger" >'+response.message+'</div>');
            }
        }, 'json');
    });
});