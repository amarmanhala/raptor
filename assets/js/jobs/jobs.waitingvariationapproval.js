/* global angular, base_url, bootbox, app */

"use strict";
if($("#waitingVariationApprovalJobsCtrl").length) {
    app.controller('waitingVariationApprovalJobsCtrl', [
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

        $scope.gridWaitingVariationApproval = {
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
//                        //headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  data-targettableid="waitingvariationapprovaljobstbl"/></div>',
//                        cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="waitingvariationapprovaljobid[]"  data-targetdiv="waitingvariationapprovaljobstbl" value="{{row.entity.jobid}}"  /></div>'
//                    }, 
                    { 
                        displayName:'Job ID',
                        name: 'jobid', 
                        width:80,
                        cellTooltip: true,
                        cellTemplate: '<div class="ui-grid-cell-contents" ><a href="'+ base_url +'jobs/jobdetail/{{row.entity.jobid}}" title="view job detail" target="_blank"  >{{row.entity.jobid}}</a>&nbsp;&nbsp;<span data-jobid="{{row.entity.jobid}}"   class="glyphicon glyphicon-list-alt job-detail-model" style="cursor:pointer;" title="Quick View"></span></div>'
                    },
//                    { 
//                        displayName:'PDF',
//                        cellTooltip: true,
//                        name: 'pdf',
//                        enableSorting: false,
//                        width: 80 ,
//                        cellTemplate: '<div class="ui-grid-cell-contents text-center" ><a href="'+ base_url +'jobs/jobdetail/{{row.entity.jobid}}" title="PDF" target="_blank"  ><img src="'+base_img_url +'assets/img/pdf_icon.png"/></a></div>'
//                    },
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
//                    { 
//                        displayName:'Job Stage',
//                        cellTooltip: true,
//                        name: 'portaldesc',
//                        enableSorting: true,
//                        width: 150 
//                    }

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
                        alignContainers('#waitingvariationapprovaljobstbl', $scope.gridApi.grid);
                    });
                    
                });

                //SCROLL END
                gridApi.core.on.scrollEnd($scope, function () {
                    alignContainers('#waitingvariationapprovaljobstbl', $scope.gridApi.grid);
                });
            }
        };
    
        //$scope.gridWaitingVariationApproval.multiSelect = false;
        //$scope.gridWaitingVariationApproval.modifierKeysToMultiSelect = false;
        //$scope.gridWaitingVariationApproval.noUnselect = true;

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
//            $('#waitingVariationApprovalJobsCtrl .selectpicker').each(function() {
//                $(this).selectpicker('refresh');
//            });
//        });
        
        $scope.exportToExcel=function(){
            
            window.open(base_url+'jobs/exportexcel/waitingvariationapproval?'+$.param($scope.filterOptions));

        };

        var getPage = function() {
     
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $('#waitingVariationApprovalJobsCtrl .overlay').show();
            $http.get(base_url+'jobs/loadwaitingvariationapprovaljobs?'+ qstring ).success(function (data) {
           
                    $('#waitingVariationApprovalJobsCtrl .overlay').hide();
                    if (data.success === false) {
                        bootbox.alert(data.message);
                        return false;
                    }else{ 
                        $scope.gridWaitingVariationApproval.totalItems = data.total;
                        $scope.gridWaitingVariationApproval.data = data.data;  
                    } 

              });

        }; 
        
        //getPage();
        
        $(document).on('click', '#waitingVariationApprovalJobsCtrl #printvariationbtn', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            
            if($scope.selectedRows.length !== 1){
                bootbox.alert('Please select a single job for Print PDF.');
                return false;
            }

            var jobid = $scope.selectedRows[0].jobid;
            console.log('click job Variation PDF');

            window.open(base_url+"jobs/variationpdf/"+jobid);

        });
        
        $(document).on('click', '#waitingVariationApprovalJobsCtrl #declinebtn', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            
            if($scope.selectedRows.length !== 1){
                bootbox.alert('Please select a single job for decline.');
                return false;
            }

            var jobid = $scope.selectedRows[0].jobid;

            bootbox.confirm('Are you sure you want to decline job variation request <b>'+ jobid+ '</b> .?', function(result) {
                if(result) {

                    $("#jobVariationDeclineModal").modal();

                    $('#jobVariationDeclineModal #loading-img').show();
                    $('#jobVariationDeclineModal #sitegriddiv').hide();

                    $("#jobVariationDeclineModal #btnsave").button('reset');
                    $('#jobVariationDeclineModal #btnsave').attr("disabled", "disabled");
                    $('#jobVariationDeclineModal .status').html('');
                    $("#declinejonvariationform #reason").val('');
                    $("#declinejonvariationform #notes").val('');
                    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
                        if(data.success){   
                            $("#declinejonvariationform #jobid").val(data.data.jobid); 
                            $('#jobVariationDeclineModal #loading-img').hide();
                            $('#jobVariationDeclineModal #sitegriddiv').show(); 
                            $('#jobVariationDeclineModal #btnsave').removeAttr("disabled");

                        }else{
                            $("#jobVariationDeclineModal").modal('hide');
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });
        
        $(document).on('click', '#waitingVariationApprovalJobsCtrl #apprivebtn', function() {
        
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
             if($scope.selectedRows.length !== 1){
                bootbox.alert('Please select a single job for approval.');
                return false;
            }

            var jobid = $scope.selectedRows[0].jobid;
            console.log('click job Variation Approval');


            bootbox.confirm('Are you sure you want to approve job variation request <b>'+ jobid+ '</b> .?', function(result) {
                if(result) {

                    $("#jobVariationApproveModal").modal();

                    $('#jobVariationApproveModal #loading-img').show();
                    $('#jobVariationApproveModal #sitegriddiv').hide();

                    $("#jobVariationApproveModal #btnsave").button('reset');
                    $('#jobVariationApproveModal #btnsave').attr("disabled", "disabled");
                    $('#jobVariationApproveModal .status').html('');
                    $("#approvejonvariationform #notes").val('');
                    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
                        if(data.success){ 
                            $("#approvejonvariationform #duedate").val(data.data.duedate);
                            $("#approvejonvariationform #duetime").val(data.data.duetime);
                            $("#approvejonvariationform #jobid").val(data.data.jobid);
                            $("#approvejonvariationform #custordref").val(data.data.custordref);
                            $("#approvejonvariationform #custordref2").val(data.data.custordref2);
                            $("#approvejonvariationform #custordref3").val(data.data.custordref3); 
                            $('#jobVariationApproveModal #loading-img').hide();
                            $('#jobVariationApproveModal #sitegriddiv').show(); 
                            $('#jobVariationApproveModal #btnsave').removeAttr("disabled");

                        }else{
                            $("#jobVariationApproveModal").modal('hide');
                            bootbox.alert(data.message);
                        }

                    },'json');
                }
            });
        });
        
             
        $(document).on('click', '#waitingVariationApprovalJobsCtrl #wv_updateglcode', function() {
            
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            var glcode = $("#wv_glcode").val();
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
                    $("#wv_updateglcode").button('loading');
                    $.post( base_url+"jobs/updatemultipleglcodes", { jobids: jobids, glcode:glcode }, function( response ) {
                        if(response.success) {
                            $("#wv_updateglcode").button('reset');
                            $("#waitingVariationApprovalJobsCtrl .btn-refresh" ).click();
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
     
    $(document).on('click', '#waitingvariationapprovaljobstbl input[name="waitingvariationapprovaljobid[]"]', function(e) {
         
        var $chkbox_all = $('#waitingvariationapprovaljobstbl input[name="waitingvariationapprovaljobid[]"]');
        if(this.checked){
        
            $chkbox_all.prop("checked", false); 
            this.checked = true;
        }
         
        // Prevent click event from propagating to parent
        e.stopPropagation();
    });
    
    
    
    
    
   
    
    $(document).on('click', '#jobVariationApproveModal #btnsave', function() {
  
       var reason = $("#approvejonvariationform #duedate");
        var comments = $("#approvejonvariationform #notes");
        $("#declinequote_form span.help-block").remove();

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

        $("#approvejonvariationform #btnsave").button('loading'); 
        
        $.post( base_url+"jobs/updatejobvariationapproval", $("#approvejonvariationform").serialize(), function( data ) {
            $("#jobVariationApproveModal #btnsave").button('reset');
            $('#jobVariationApproveModal #btnsave').attr("disabled", "disabled");
            if(data.success) {
                $( "#waitingVariationApprovalJobsCtrl .btn-refresh" ).click(); 
                $("#jobVariationApproveModal").modal('hide');
                $('#myjobsstatus').html('<div class="alert alert-success" >Job variation approved successfully.</div>');
                clearMsgPanel();
                 
              
            }
            else{
                 $('#jobVariationApproveModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');

            }
          }, 'json');
              
  
    });
    
    $(document).on('click', '#jobVariationApproveModal #btncancel', function() {
 
         $("#jobVariationApproveModal").modal('hide');
    });
    
    
    
    $(document).on('change', '#declinejonvariationform #reason', function() {
 
         $("#declinejonvariationform #notes").val('Variation declined due to ' + $(this).val());
    });
    $(document).on('click', '#jobVariationDeclineModal #btnsave', function() {
  
       var reason = $("#declinejonvariationform #reason");
        var comments = $("#declinejonvariationform #notes");
        $("#declinejonvariationform span.help-block").remove();

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

        $("#declinejonvariationform #btnsave").button('loading'); 
        
        $.post( base_url+"jobs/updatejobvariationdecline", $("#declinejonvariationform").serialize(), function( data ) {
            $("#declinejonvariationform #btnsave").button('reset');
            $('#declinejonvariationform #btnsave').attr("disabled", "disabled");
            
            if(data.success) {
                $( "#waitingVariationApprovalJobsCtrl .btn-refresh" ).click(); 
                $("#jobVariationDeclineModal").modal('hide');
                $('#myjobsstatus').html('<div class="alert alert-success" >Job variation declined successfully.</div>');
                clearMsgPanel();
                
            }
            else{
                 $('#jobVariationDeclineModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');

            }
          }, 'json');
              
  
    });
    
    $(document).on('click', '#jobVariationDeclineModal #btncancel', function() {
 
         $("#jobVariationDeclineModal").modal('hide');
    });
});