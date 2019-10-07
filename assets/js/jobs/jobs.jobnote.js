/* global app, angular, base_url, readDocURL, readImageURL, bootbox */

"use strict";
if($("#JobNoteCtrl").length) {

    app.controller('JobNoteCtrl', [
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

      $scope.jobNotesGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnResizing: true,
        enableColumnMenus: false,
        columnDefs: [   
            { 
                displayName:'Job note Id',
                name: 'jobnoteid',
                cellTooltip: true,
                sort: {
                    direction: uiGridConstants.DESC
                },
                width:120
            },
            { 
                displayName:'Date',
                name: 'date',
                cellTooltip: true,
                width:150
            },
            { 
                displayName:'Note type',
                name: 'notetype',
                cellTooltip: true,
                width:150
            },
            { 
                displayName:'Notes',
                cellTooltip: true,
                name: 'notes',
                enableSorting: false, 
                cellTemplate: '<div class="ui-grid-cell-contents pre-wrap"  title="{{row.entity.notes}}" ng-bind-html="COL_FIELD | trusted"></div>'
            },
             
            { 
                displayName:'Email',
                name: 'email',
                cellTooltip: true,
                enableSorting: false,
                width:90,
                cellTemplate: '<div class="ui-grid-cell-contents"><a title = "email" style="cursor:pointer;font-size:18px;" ng-click="grid.appScope.jobNoteEmail(row.entity)"><i class= "fa fa-envelope"></i></a></div>'
            }

        ],
        onRegisterApi: function(gridApi) {
            $scope.gridApi = gridApi;

            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
              paginationOptions.pageNumber = newPage;
              paginationOptions.pageSize = pageSize;
              jobNotePage();
            });
            
            gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = '';
                  paginationOptions.field = '';
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                jobNotePage();
            });
        }
      };
 
        $scope.refreshJobNote = function() {
           jobNotePage();
        };
        
        $scope.jobNoteEmail = function(row) {
            var jobid = $("#jobdetail_form #jobid").val();
            var jobnoteid = row.jobnoteid;
            openSendEmailDialog(jobid, '', '', jobnoteid);
        };

       var jobNotePage = function() {

            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                jobid : $("#jobdetail_form #jobid").val()
            }; 


            var qstring = $.param(params);

            $scope.overlay = true;
            $http.get(base_url+'jobs/loadjobnotes?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.jobNotesGrid.totalItems = response.total;
                    $scope.jobNotesGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
        }; 

        jobNotePage();
    }
    ]);
    app.filter('trusted', function ($sce) {
            return function (value) {
              return $sce.trustAsHtml(value);
            };
    });
}



$( document ).ready(function() {
         
    if (typeof $.fn.validate === "function") {         
      
        $("#jobnote_form").validate({
            rules: {
                notes: {  
                    validaterequired: true
                }
            },
            errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                $(e).parent().parent().removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
                $(e).parent().parent().removeClass("has-error");
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.parent());
            },
            unhighlight: function(e, errorClass, validClass) {
                $(e).parent().removeClass("has-error");
            },
            submitHandler: function() {
                $("#jobnote_form #modalsave").button('loading'); 
                $("#jobnote_form #cancel").button('loading'); 
                $("#jobnote_form .close").css('display', 'none');
                $.post( base_url+"jobs/createjobnote", $("#jobnote_form").serialize(), function( data ) {
                   
                    if(data.success) {
                        $("#jobNoteModal").modal('hide');
                        $('#myjobstatus').html('<div class="alert alert-success" >Job Notes Created.</div>');
                        clearMsgPanel();
                        $("#JobNoteCtrl .btn-refresh" ).click(); 
                    }
                    else{
                        $('#jobnote_form #status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                    }
                    $("#jobnote_form #modalsave").button('reset'); 
                    $("#jobnote_form #cancel").button('reset'); 
                    $("#jobnote_form .close").css('display', 'block');
                }, 'json');
                return false;
            }
        });
    }
    
    $("#addjobnotebtn").on('click', function() {

        $('#jobnote_form').trigger("reset");
        $("#jobnote_form span.help-block").remove();
        $("#jobnote_form .has-error").removeClass("has-error");
        $('#jobnote_form #status').html('');
        $("#jobNoteModal").modal();
    });

    $("#jobnote_form #cancel").on('click', function() {
        $('#jobnote_form #status').html('');
        $("#jobNoteModal").modal('hide');
    });
});