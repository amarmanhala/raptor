/* global app, angular, base_url, readDocURL, readImageURL, bootbox */

"use strict";
if($("#JobTaskCtrl").length) {

    app.controller('JobTaskCtrl', [
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

      $scope.jobTaskGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnResizing: true,
        enableColumnMenus: false,
        columnDefs: [   
            { 
                displayName:'Task Id',
                name: 'taskid',
                cellTooltip: true,
                width:120
            },
            { 
                displayName:'Date',
                name: 'tdate',
                cellTooltip: true,
                width:150,
                sort: {
                    direction: uiGridConstants.DESC
                }
            },
            { 
                displayName:'Detail',
                name: 'detail',
                cellTooltip: true
            },
            { 
                displayName:'Allocated To',
                name: 'allocatedto',
                cellTooltip: true
            },
            { 
                displayName:'Followup Date',
                name: 'followupdate',
                cellTooltip: true,
                width:150
            },
            { 
                displayName:'Completed',
                name: 'completed',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.completed == \'on\'"><input type="checkbox" checked="checked" disabled="disabled"></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.completed != \'on\'"><input type="checkbox" disabled="disabled"></div>'
            },
            { 
                displayName:'Email',
                name: 'email',
                cellTooltip: true,
                enableSorting: false,
                width:90,
                cellTemplate: '<div class="ui-grid-cell-contents"><a title = "email" style="cursor:pointer;font-size:18px;" ng-click="grid.appScope.jobTaskEmail(row.entity)"><i class= "fa fa-envelope"></i></a></div>'
            }

        ],
        onRegisterApi: function(gridApi) {
            $scope.gridApi = gridApi;

            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
              paginationOptions.pageNumber = newPage;
              paginationOptions.pageSize = pageSize;
              jobTaskPage();
            });
            
            gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = '';
                  paginationOptions.field = '';
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                jobTaskPage();
            });
        }
      };
 
        $scope.refreshJobTask = function() {
           jobTaskPage();
        };
        
        $scope.jobTaskEmail = function(row) {
            var jobid = $("#jobdetail_form #jobid").val();
            var jobtaskid = row.taskid;
            openSendEmailDialog(jobid, '', 'jobtask', jobtaskid);
        };

       var jobTaskPage = function() {

            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                jobid : $("#jobdetail_form #jobid").val()
            }; 


            var qstring = $.param(params);

            $scope.overlay = true;
            $http.get(base_url+'jobs/loadjobtasks?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.jobTaskGrid.totalItems = response.total;
                    $scope.jobTaskGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
        }; 

        jobTaskPage();
    }
    ]);
}



$( document ).ready(function() {
     if (typeof $.fn.validate === "function") {         
      
        $("#addtask_form").validate({
            rules: {
                description: {  
                    validaterequired: true
                },
                allocatedto: {  
                    validaterequired: true
                },
                followupdate: {  
                    validaterequired: true
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
            },
            errorPlacement: function (error, element) {
                if(element.parent().is('.input-group')) {
                    error.appendTo(element.parent().parent());
                }
                else {
                    error.appendTo(element.parent());
                }
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
                
                $("#addtask_form #modalsave").button('loading'); 
                $("#addtask_form #cancel").button('loading'); 
                $("#addtask_form .close").css('display', 'none');
                $.post( base_url+"jobs/createjobtask", $("#addtask_form").serialize(), function( data ) {
                    if(data.success) {
                        $("#addTaskModal").modal('hide');
                        $('#myjobstatus').html('<div class="alert alert-success" >Job Task Created.</div>');
                        clearMsgPanel();
                        $("#JobTaskCtrl .btn-refresh" ).click(); 
                    }
                    else{
                        $('#addtask_form #status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                    }
                    $("#addtask_form #modalsave").button('reset'); 
                    $("#addtask_form #cancel").button('reset'); 
                    $("#addtask_form .close").css('display', 'block');
                }, 'json');
                return false;
                
            }
        });
    }
    
    $("#addjobtask").on('click', function() {
        $('#addtask_form').trigger("reset");
        $("#addtask_form span.help-block").remove();
        $("#addtask_form .has-error").removeClass("has-error");
        $('#addtask_form #status').html('');
        $("#addTaskModal").modal();
    });

    $("#addtask_form #cancel").on('click', function() {
        $('#addtask_form #status').html('');
        $("#addTaskModal").modal('hide');
    });
    
});