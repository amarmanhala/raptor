/* global app, angular, base_url, readDocURL, readImageURL, bootbox */

"use strict";
if($("#JobDocumentsCtrl").length) {

    app.controller('JobDocumentsCtrl', [
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

      $scope.jobDocumentsGrid = {
        paginationPageSizes: [10, 25, 50, 100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
        columnDefs: [   
            { 
                displayName:'Document Name',
                name: 'docname',
                cellTooltip: true,
                width:370,
                cellTemplate: '<div class="ui-grid-cell-contents" title="{{row.entity.docname}}"><a href="{{row.entity.docurl}}"  target="_blank" >{{row.entity.docname}}</a></div>'
            },
            { 
                displayName:'Type',
                name: 'doctype',
                cellTooltip: true,
                width:150
            },
            { 
                displayName:'Description',
                name: 'documentdesc',
                cellTooltip: true,
                enableSorting: false
            },
            { 
                displayName:'Date',
                name: 'dateadded',
                cellTooltip: true,
                width:100,
                sort: {
                    direction: uiGridConstants.DESC
                },
            }
        ],
        onRegisterApi: function(gridApi) {
            $scope.gridApi = gridApi;

            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
              paginationOptions.pageNumber = newPage;
              paginationOptions.pageSize = pageSize;
              jobDocumentPage();
            });
            
            gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length === 0) {
                  paginationOptions.sort = '';
                  paginationOptions.field = '';
                } else {
                  paginationOptions.sort = sortColumns[0].sort.direction;
                  paginationOptions.field = sortColumns[0].field;
                }
                jobDocumentPage();
            });
        }
      };
 
       $scope.refreshJobDocument = function() {
           jobDocumentPage();
        };

       var jobDocumentPage = function() {

            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                jobid : $("#jobdetail_form #jobid").val()
            }; 


            var qstring = $.param(params);

            $scope.overlay = true;
            $http.get(base_url+'documents/loadjobdocumentsbyjobid?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.jobDocumentsGrid.totalItems = response.total;
                    $scope.jobDocumentsGrid.data = response.data;  
                }
                $scope.overlay = false;
            });

        }; 

        jobDocumentPage();


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
                   
                    if($("#uploaddocModal #uploadtype").val()==="adddoc"){
                        $("#uploaddocModal").modal('hide');
                        $('#myjobstatus').html('<div class="alert alert-success" >'+response.message+'</div>');
                        clearMsgPanel();
                        $("#JobDocumentsCtrl .btn-refresh" ).trigger('click'); 

                    }
                    else{
                         $('#status').html('<div class="alert alert-success" >'+response.message+'</div>');

                         setTimeout(function(){ 
                             document.location.reload(); 
                         }, 500);
                    }
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

    $(document).on('click', "#adddoc, #addimage", function() {

       $('#upload_form').trigger("reset");
       $('#upload_form #modalsave').removeAttr("disabled");
       $("#upload_form span.help-block").remove();
       $("#upload_form .has-error").removeClass("has-error");

       $("#uploaddocModal").modal();
       $("#uploaddocModal #uploadtype").val($(this).attr('id'));
       if($(this).attr('id')==="adddoc"){
            $("#uploaddocModal #uploaddocModalTitle").html('Upload Document');
            $("#upload_form #fileup").attr('onchange',"readJobDocURL(this);");
            $('#upload_form #doctype').html($('#upload_form #tempdoctype').html());
       }
       else{
            $("#uploaddocModal #uploaddocModalTitle").html('Upload Image');
            $("#upload_form #fileup").attr('onchange',"readJobImageURL(this);");
            $('#upload_form #doctype').html($('#upload_form #tempdocimage').html());
       }
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

       var uploadtype = $("#upload_form #uploadtype");
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

           if($.trim(uploadtype.val())==="adddoc"){
               if(readJobDocURL(fileup)){
                   $(fileup).parent().parent().removeClass("has-error");
               }
               else{
                   $(fileup).parent().parent().addClass("has-error");
                   $("<span class='help-block'>Please select valid file. File Format : 'pdf','docx'</span>").appendTo(fileup.parent());
                   return false;
               }
           }
           else{
               if(readJobImageURL(fileup)){
                   $(fileup).parent().parent().removeClass("has-error");
               }
               else{
                   $(fileup).parent().parent().addClass("has-error");
                   $("<span class='help-block'>Please select valid file. File Format : 'png','jpg','jpeg','gif'</span>").appendTo(fileup.parent());
                    return false;
               }
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

var readJobImageURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg','gif']) === -1) {
        $(input).val('');
        $(input).parent().find('span.help-block').remove();
        $(input).parent().parent().addClass("has-error");
        $('<span class="help-block">Invalid file format! allowed type png, jpg, jpeg, gif.</span>').appendTo($(input).parent());
        return false;
    }
    $(input).parent().parent().removeClass("has-error");
    $(input).parent().find('span.help-block').remove();
    return true;
};

var readJobDocURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();

    if($.inArray(ext, ['pdf','docx']) === -1) {
            $(input).val('');
            $(input).parent().find('span.help-block').remove();
        $(input).parent().parent().addClass("has-error");
        $('<span class="help-block">Invalid file format! allowed type pdf, docx.</span>').appendTo($(input).parent());
        return false;
    }
    $(input).parent().parent().removeClass("has-error");
    $(input).parent().find('span.help-block').remove();
    return true;
};