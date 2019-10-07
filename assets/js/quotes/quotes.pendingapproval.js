/* global angular, base_url, bootbox, app */

"use strict";
if($("#PendingApprovalQuotesCtrl").length) {
    app.controller('PendingApprovalQuotesCtrl', [
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

        $scope.gridPendingApproval = {
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
//                        width: 60,
//                        //headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="pendingapprovaltbl"/></div>',
//                        cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="pendingapprovaljobid[]"  data-targetdiv="pendingapprovaltbl" value="{{row.entity.jobid}}"  /></div>'
//                    }, 
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        width:100,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+base_url+'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span   data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
                    { 
                        displayName:$('#custordref1_label').val(),
                        cellTooltip: true,
                        name: 'custordref',
                        enableSorting: true,
                        width: 130 
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
                        width: 100 
                    },
                    { 
                        displayName:'Site FM',
                        cellTooltip: true,
                        name: 'sitefm', 
                        width: 150 
                    },
                    { 
                        displayName:'Job Description',
                        cellTooltip: true,
                        name: 'shortdescription',
                        enableSorting: false,
                        width: 300,
                        cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.jobdescription}}" ng-bind-html="COL_FIELD | trusted"></div>'
                    },
//                    { 
//                        displayName:'Job Stage',
//                        cellTooltip: true,
//                        name: 'portaldesc',
//                        enableSorting: true,
//                        width: 150 
//                    },
                    { 
                        displayName:'Due Date',
                        cellTooltip: true,
                        name: 'duedate',
                        enableSorting: true,
                        width: 100 
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
                
                // ROWS RENDER
                gridApi.core.on.rowsRendered($scope, function () {
                    // each rows rendered event (init, filter, pagination, tree expand)
                    // Timeout needed : multi rowsRendered are fired, we want only the last one
                    if (rowsRenderedTimeout) {
                        $timeout.cancel(rowsRenderedTimeout);
                    }
                    rowsRenderedTimeout = $timeout(function () {
                        alignContainers('#pendingapprovaltbl', $scope.gridApi.grid);
                    });
                });

                // SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#pendingapprovaltbl', $scope.gridApi.grid);
                });
            }
        };

        $scope.gridPendingApproval.multiSelect = false; 

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
        
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $scope.exportToExcel=function(){
            
            window.open(base_url+'quotes/exportexcel/pendingapproval?'+$.param($scope.filterOptions));

        };

        var getPage = function() {
     
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#PendingApprovalQuotesCtrl .overlay').show();
            $http.get(base_url+'quotes/loadpendingapprovalquotes?'+ qstring ).success(function (data) {
           
                    $('#PendingApprovalQuotesCtrl .overlay').hide();
                    if (data.success === false) {
                        bootbox.alert(data.message);
                        return false;
                    }else{ 
                        $scope.gridPendingApproval.totalItems = data.total;
                        $scope.gridPendingApproval.data = data.data;  
                    } 

              });

        }; 
        
        getPage();
        
        $(document).on('click', '#PendingApprovalQuotesCtrl #printquotebtn', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length !== 1){ 
                bootbox.alert('Please select a single quote for print pdf.'); 
                return false;
            }

            var jobid = $scope.selectedRows[0].jobid;

            console.log('click print'); 
            window.open(base_url+"quotes/quotepdf/"+jobid);


        });

        $(document).on('click', '#PendingApprovalQuotesCtrl #approvebtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length !== 1){ 
                bootbox.alert('Please select a single quote for approval.');
                return false;
            }

             var jobid = $scope.selectedRows[0].jobid;
            console.log('click quote Approval');


            bootbox.confirm('Are you sure you want to approve quote request <b>'+ jobid+ '</b> .?', function(result) {
                if(result) {

                    $("#quoteApproveModal").modal();

                    $('#quoteApproveModal #loading-img').show();
                    $('#quoteApproveModal #sitegriddiv').hide();

                    $("#quoteApproveModal #btnsave").button('reset');
                    $('#quoteApproveModal #btnsave').attr("disabled", "disabled");
                    $('#quoteApproveModal .status').html('');
                    $("#approvequoteform #notes").val('');
                    $('#approvequoteform input[name="duedate"]').datepicker('setStartDate', new Date());
                    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
                        if(data.success){ 
                            $("#approvequoteform #duedate").val(data.data.duedate);
                            $("#approvequoteform #duetime").val(data.data.duetime);
                            $("#approvequoteform #jobid").val(data.data.jobid);
                            $("#approvequoteform #custordref").val(data.data.custordref);
                            $("#approvequoteform #custordref2").val(data.data.custordref2);
                            $("#approvequoteform #custordref3").val(data.data.custordref3); 
                            $('#quoteApproveModal #loading-img').hide();
                            $('#quoteApproveModal #sitegriddiv').show(); 
                            $('#quoteApproveModal #btnsave').removeAttr("disabled");

                        }else{
                            $("#quoteApproveModal").modal('hide');
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });

       

        $(document).on('click', '#PendingApprovalQuotesCtrl #declinebtn', function() {

            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             
            if($scope.selectedRows.length !== 1){ 
                bootbox.alert('Please select a single quote for decline.'); 
                return false;
            }

            var jobid = $scope.selectedRows[0].jobid;

            bootbox.confirm('Are you sure you want to decline quote request <b>'+ jobid+ '</b> .?', function(result) {
                if(result) {

                    $("#quoteDeclineModal").modal();

                    $('#quoteDeclineModal #loading-img').show();
                    $('#quoteDeclineModal #sitegriddiv').hide();

                    $("#quoteDeclineModal #btnsave").button('reset');
                    $('#quoteDeclineModal #btnsave').attr("disabled", "disabled");
                    $('#quoteDeclineModal .status').html('');
                    $("#declinequoteform #reason").val('');
                    $("#declinequoteform #notes").val('');
                    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
                        if(data.success){   
                            $("#declinequoteform #jobid").val(data.data.jobid); 
                            $('#quoteDeclineModal #loading-img').hide();
                            $('#quoteDeclineModal #sitegriddiv').show(); 
                            $('#quoteDeclineModal #btnsave').removeAttr("disabled");

                        }else{
                            $("#quoteDeclineModal").modal('hide');
                            bootbox.alert(data.message);
                        }

                    },'json');
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
     
    $(document).on('click', '#quoteApproveModal #btnsave', function() {
        var openfrom = $("#approvequoteform #openfrom").val();
        var reason = $("#approvequoteform #duedate");
        var comments = $("#approvequoteform #notes");
        $("#declinequote_form span.help-block").remove();

        if($.trim(reason.val()) === "") {
            $(reason).parent().parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(reason.parent().parent());
        } else {
            $(reason).parent().parent().removeClass("has-error");
        }
        if($.trim(comments.val()) === "") {
            $(comments).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(comments.parent());
        } else {
            $(comments).parent().removeClass("has-error");
        }


         if($.trim(comments.val()) === "" || $.trim(reason.val()) === ""){
             return false;
         }

        $("#approvequoteform #btnsave").button('loading'); 
 
        $.post( base_url+"quotes/updatequoteapproval", $("#approvequoteform").serialize(), function( data ) {
            if(data.success) {
                if(openfrom !== 'jobdetail'){
                    $( "#inprogressQuotesCtrl .btn-refresh" ).click(); 
                    $( "#PendingApprovalQuotesCtrl .btn-refresh" ).click(); 
                    $("#quoteApproveModal").modal('hide');
                    $('#myquotestatus').html('<div class="alert alert-success" >Quote Request approved successfully.</div>');
                    clearMsgPanel();
                }
                else{
                    $("#quoteApproveModal").modal('hide');
                    bootbox.alert('Quote Request approved successfully.');
                    location.reload();
                }

            }
            else{
                 $('#quoteApproveModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');

            }
          }, 'json');
    });

    $(document).on('click', '#quoteApproveModal #btncancel', function() {
        $("#quoteApproveModal").modal('hide');
    });

     
   $(document).on('change', '#declinequoteform #reason', function() {
            $("#declinequoteform #notes").val('Quote declined due to ' + $(this).val());
    });

    $(document).on('click', '#quoteDeclineModal #btnsave', function() {

        var openfrom = $("#declinequoteform #openfrom").val();
        var reason = $("#declinequoteform #reason");
        var comments = $("#declinequoteform #notes");
        $("#declinequoteform span.help-block").remove();

        if($.trim(reason.val()) === "") {
            $(reason).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(reason.parent());
        } else {
            $(reason).parent().removeClass("has-error");
        }
        if($.trim(comments.val()) === "") {
            $(comments).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(comments.parent());
        } else {
            $(comments).parent().removeClass("has-error");
        }

        if($.trim(comments.val()) === "" || $.trim(reason.val()) === ""){
            return false;
        }

        $("#declinequoteform #btnsave").button('loading'); 

        $.post( base_url+"quotes/updatequotedecline", $("#declinequoteform").serialize(), function( data ) {
            if(data.success) {
                if(openfrom !== 'jobdetail'){
                    $( "#inprogressQuotesCtrl .btn-refresh" ).click(); 
                    $( "#PendingApprovalQuotesCtrl .btn-refresh" ).click(); 
                    $("#quoteDeclineModal").modal('hide');
                    $('#myquotestatus').html('<div class="alert alert-success" >Quote declined successfully.</div>');
                    clearMsgPanel();
                }
                else{
                     $("#quoteDeclineModal").modal('hide');
                    bootbox.alert('Quote Request declined successfully.');
                    location.reload();
                }

            }
            else{
                 $('#quoteDeclineModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');

            }
          }, 'json');


    });

    $(document).on('click', '#quoteDeclineModal #btncancel', function() {

         $("#quoteDeclineModal").modal('hide');
    });
    
     
    
});