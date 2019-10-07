
/* global base_url, angular, bootbox */

"use strict";
if($("#CustomerDocumentCtrl").length) {
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('CustomerDocumentCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {
 
         // filter
        $scope.filterOptions = {
            documenttype:[],
            monthyear:[],
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
         showColumnFooter: true,
         enableColumnMenus: false,
         enableFiltering: false,
         
         columnDefs: [
               
            { 
                displayName:'Date Added',
                cellTooltip: true,
                name: 'dateadded',
                enableSorting: true,
                width: 130 
            },
            { 
                displayName:'Document Type',
                cellTooltip: true,
                name: 'doctype',
                enableSorting: true,
                width: 250
            },
            { 
                displayName:'Document Name',
                cellTooltip: true,
                name: 'docname',
                enableSorting: true,
                width: 200,
                cellTemplate: '<div class="ui-grid-cell-contents"><a href="javascript:void(0);"  ng-click="grid.appScope.validateDocument(row.entity.documentid)" target="_blank">{{row.entity.docname}}</a></div>'
            },
            { 
                displayName:'Description',
                cellTooltip: true,
                name: 'documentdesc',
                enableSorting: true 
            },
            { 
                displayName:'File Size',
                cellTooltip: true,
                name: 'filesize',
                enableSorting: true,
                width: 100
            } 
        ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = null;
                  paginationOptions.field = null;
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
                filterText: ''
            }; 
            getPage();
        };
        $scope.refreshGrid = function() {
            getPage();
        };
        
        $scope.validateDocument = function(documentid) {
           $('#CustomerDocumentCtrl .overlay').show();
            $.get( base_url+"documents/checkdocument", { documentid:documentid }, function( response ) {
                $('#CustomerDocumentCtrl .overlay').hide();
                if (response.success) {
                     window.open(base_url+'documents/viewdocument/'+documentid);
                }
                else {
                    bootbox.alert(response.message);
                }
            }); 
        };
        
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        
        $scope.exportToExcel=function(){
            
            window.open(base_url+'documents/exportcustomerdocuments?'+$.param($scope.filterOptions));

        };
        
       var getPage = function() {
            if(typeof $scope.filterOptions.filterText === 'undefined') {
                $scope.filterOptions.filterText = '';
            }
              
             if(paginationOptions.sort === null) {
                 paginationOptions.sort = '';
             }
             if(paginationOptions.field === null) {
                 paginationOptions.field = '';
             }
            var params = { 
                page  : paginationOptions.pageNumber,
                size :  paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
            var qstring = $.param(params) + '&'+ $.param($scope.filterOptions);
  
            $scope.overlay = true; 
             $http.get(base_url+'documents/loadcustomerdocuments?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.gridOptions.totalItems = data.total;
                        $scope.gridOptions.data = data.data;  
                    }
                  $scope.overlay = false; 
             });
       };

       getPage();
     }
     ]);
}
 
$( document ).ready(function() {
  
    if (typeof $.fn.ajaxForm === "function") {   
             $('#upload_form').ajaxForm({
           beforeSend: function() {
               $('#status').empty();
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
              var response = $.parseJSON(xhr.responseText);
               if(response.success){
                    
                    $("#uploaddocModal").modal('hide');
                    $('#mydocumentstatus').html('<div class="alert alert-success" >'+response.message+'</div>');

                      setTimeout(function(){ 
                          $('#mydocumentstatus').html('');
                     }, 4000);


                    $("#CustomerDocumentCtrl .btn-refresh" ).trigger('click'); 

                   
                }
               else{
                    
                   $('#status').html('<div class="alert alert-danger" >'+response.message+'</div>');
               }
           }
       });
  }    

   $(document).on('change', "#uploaddocModal #doctype", function() {
       $("#upload_form #foldername").val($(this).find(':selected').attr('data-folder'));
   });

    $(document).on('click', "#adddoc", function() {

       $('#upload_form').trigger("reset");
       $('#upload_form #modalsave').removeAttr("disabled");
       $("#upload_form span.help-block").remove();
       $("#upload_form .has-error").removeClass("has-error");

       $("#uploaddocModal").modal();
       
        
        $('#status').empty();

       $('.progress-bar').attr('aria-valuenow',0);
       $('.progress-bar').css('width',"0%");
       $('.sr-only').html("0% Complete ");
       $("#upload_form #doctype").val(''); 
       $("#upload_form #description").val('');
       $("#upload_form #fileup").val('');
       $("#upload_form #foldername").val('');
       $("#upload_form #filedata").val('');
       $("#upload_form").show();
   });

    $("#uploaddocModal #modalsave").on('click', function() {

        
       var doctype = $("#upload_form #doctype");
       var description = $("#upload_form #description");
       var fileup = $("#upload_form #fileup"); 
       $("#upload_form span.help-block").remove();

       if($.trim(doctype.val()) === "") {
           $(doctype).parent().parent().addClass("has-error");
           $('<span class="help-block">Please Select Doc Type.</span>').appendTo(doctype.parent());
       } else {
           $(doctype).parent().parent().removeClass("has-error");
       }
       if($.trim(description.val()) === "") {
           $(description).parent().parent().addClass("has-error");
           $('<span class="help-block">Please entered description.</span>').appendTo(description.parent());
       } else {
           $(description).parent().parent().removeClass("has-error");
       }
       if($.trim(fileup.val()) === "") {
           $(fileup).parent().parent().addClass("has-error");
           $('<span class="help-block">Please select upload file.</span>').appendTo(fileup.parent());
       } else {
            if(readDocURL(fileup)){
                $(fileup).parent().parent().removeClass("has-error");
            }
            else{
                $(fileup).parent().parent().addClass("has-error");
                $("<span class='help-block'>Please select valid file. File Format : 'pdf','docx','png','jpg','jpeg','gif'</span>").appendTo(fileup.parent());
                return false;
            }
            
       }

        if($.trim(doctype.val()) === "" || $.trim(description.val()) === "" || $.trim(fileup.val()) === ""){
            return false;
        }
        return true;
   });

   $("#uploaddocModal #cancel").on('click', function() {
       $("#uploaddocModal").modal('hide');
   });

});

var readDocURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['pdf','docx','png','jpg','jpeg','gif']) === -1) {
        $(input).val('');
        $(input).parent().find('span.help-block').remove();
        $(input).parent().parent().addClass("has-error");
        $('<span class="help-block">Invalid file format! allowed type pdf, docx, png, jpg, jpeg, gif.</span>').appendTo($(input).parent());
        return false;
    }
    $(input).parent().parent().removeClass("has-error");
    $(input).parent().find('span.help-block').remove();
    return true;
};

 