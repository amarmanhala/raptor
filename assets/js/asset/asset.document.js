/* global base_url, google, bootbox, parseFloat */
/* global base_url, bootbox, app */

"use strict";

app.controller('AssetDocumentCtrl', [
    '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

     // filter
    $scope.filterOptions = {
        filtertext : '' 
    };
   var paginationOptions = {
     pageNumber: 1,
     pageSize: 10,
     sort: null,
    field: null
   };
   
   $scope.gridAssetDocumentOptions = {
     paginationPageSizes: [10, 20, 30],
     paginationPageSize: 10,
     useExternalPagination: true,
     useExternalSorting: true,
     enableColumnResizing: true,
     enableColumnMenus: false,
     //enableFiltering: true,
     columnDefs: [
                { 
                    displayName:'Doc id',
                    cellTooltip: true,
                    name: 'documentid',
                    width: 70
                },
                { 
                    displayName:'Type',
                    cellTooltip: true,
                    name: 'doctype',
                    width: 80
                },
                { 
                    displayName:'Description', 
                    cellTooltip: true, 
                    name: 'documentdesc', 
                    enableFiltering: false
                     
                },
                { 
                    displayName:'Link', 
                    cellTooltip: true, 
                    name: 'docname', 
                    enableFiltering: false,
                    cellTemplate: '<div class="ui-grid-cell-contents"><a href="javascript:void(0);"  ng-click="grid.appScope.validateDocument(row.entity.documentid)" target="_blank">{{row.entity.docname}}</a></div>'
                     
                },
                { 
                    displayName:'Date',
                    cellTooltip: true,
                    name: 'dateadded',
                    width: 90
                },
                { 
                    displayName:'Added By',
                    cellTooltip: true,
                    name: 'userid',
                    width: 150
                },
                { 
                    displayName:'File Size', 
                    cellTooltip: true, 
                    name: 'filesize',  
                    width: 50 
                     
                },
                { 
                    displayName:'Approved', 
                    cellTooltip: true, 
                    name: 'activity_name', 
                    enableFiltering: false, 
                    width: 70 ,
                    cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.documentid}}"    class="chk_approved"  ng-checked="row.entity.approved == 1" /></div>'
                     
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
            getAssetDocument();
        });

         gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
           paginationOptions.pageNumber = newPage;
           paginationOptions.pageSize = pageSize;
           getAssetDocument();
         });

     }
   };
 
   $scope.validateDocument = function(documentid) {
        $('#AssetDocumentCtrl .overlay').show();
         $.get( base_url+"documents/checkdocument", { documentid:documentid }, function( response ) {
             $('#AssetDocumentCtrl .overlay').hide();
             if (response.success) {
                  window.open(base_url+'documents/viewdocument/'+documentid);
             }
             else {
                 bootbox.alert(response.message);
             }
         }); 
     }; 

   var getAssetDocument = function() {

       $('#AssetDocumentCtrl .overlay').show();
        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort,
            assetid : $('#tab_asset_form #assetid').val()
        }; 

        var qstring = $.param(params);
         var url = base_url+'asset/loadassetdocuments?='+qstring;

         $http.get(url).success(function (data) {
             $('#AssetDocumentCtrl .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message); 
                    return false;
                }else{

                    $scope.gridAssetDocumentOptions.totalItems = data.total;
                    $scope.gridAssetDocumentOptions.data = data.data;  
                } 

                
         });
   };

   getAssetDocument();
    
          if (typeof $.fn.ajaxForm === "function") {
        $('#assetdocumentform').ajaxForm({
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
               var data = $.parseJSON(xhr.responseText);

                $("#docclose").css("display", "block");
                $("#docsavebtn").button('reset');
                $("#docclosebtn").button('reset');
                if(data.success) {

                    if(data.data.success) {
                        $('#assetdocumentform #status').html('<div class="alert alert-success" >'+data["message"]+'</div>');
                         $("#assetdocumentmodal").modal('hide');
                        var options = '';
                        var options1 = '';
                        $.each(data.data.data, function(k, v) {
                            var link = '<a href="'+base_url+'documents/download/'+v.documentid+'"  target="_blank">'+v.docname+'</a>';
                            options = options + '<tr id="'+v.documentid+'"><td>'+v.documentid+'</td><td>'+v.doctype+'</td><td>'+v.documentdesc+'</td><td>'+link+'</td><td>'+v.dateadded+'</td><td>'+v.userid+'</td><td>'+v.filesize+'</td><td><input type="checkbox" /></td></tr>';
                            var doctype = v.doctype;
                            if(doctype.toLowerCase() === 'image') {
                                    var filename =  v.documentid+'_thumb.'+v.docformat;
                                    filename = v.image_path+filename;
                                    var desc = v.documentdesc;
                                    if(desc.length>20) {
                                            desc = desc.substr(0, 20);
                                            desc = desc+"...";
                                    }
                                    options1 = options1 + '<div class="col-xs-2"><div class="thumbnail"><div style="width:120px;height:120px; margin: auto;"><img src="'+filename+'" alt="" /></div><div><p>'+desc+'</p><p><a href="#" class="btn btn-default btn-sm" onclick="getdocument('+v.documentid+');">Edit</a></p></div></div></div>';

                            }
                        });

                         getAssetDocument();
                        if(options1 !== '') {
                            $("#photo_grid").html(options1);	 
                        }
                    }
                    else {
                        $('#assetdocumentform #status').html('<div class="alert alert-danger" >'+data["message"]+'</div>');
                    }

                }
                else{
                    bootbox.alert(data.message);
                }

            }
        });
    }
      
    }
]);

$( document ).ready(function() {
 
    $(document).on('click', '#add_asset_document', function() {
        
        var modalb = $("#assetdocumentmodal").modal({
                backdrop: 'static',
                keyboard:false
        });
        $("#assetdocumentform #assetid").val($("#tab_asset_form #assetid").val());
        $("#assetdocumentform #assetdoctype").val("");
        $("#assetdocumentform #assetdoctype").select2();
        $("#assetdocumentform #documentdesc").val("");
        $("#assetdocumentform #docfileupload").val("");


        modalb.modal('show');
    });
	

    $(document).on('click', '.chk_approved', function(e) {
        var checked = $(this).is(":checked");
        var chk = 0;
        if(checked) {
            chk = 1;
        }
        var id = $(this).val();


        $.ajax({ url: base_url + 'asset/approvedocument', data: { "id":id, "chk":chk }, method:'post', dataType: 'json', 
            success: function(response) {
                 if(response.success) {
                    if(chk === 0) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                }
                else{
                    bootbox.alert(response.message);
                }

            }
        }); 
     });
         
    $(document).on('click', '#assetdocumentform #docsavebtn', function() {


        var assetdoctype=$("#assetdocumentform #assetdoctype");
        var documentdesc=$("#assetdocumentform #documentdesc");
        var fileup = $("#assetdocumentform #docfileupload"); 
        $("#assetdocumentform span.help-block").remove();

        if($.trim(assetdoctype.val()) === "") {
            $(assetdoctype).parent().parent().addClass("has-error");
            $('<span class="help-block">Please select Asset Doc. Type.</span>').appendTo(assetdoctype.parent());
            return false;
        }
        if($.trim(documentdesc.val()) === "") {
            $(documentdesc).parent().parent().addClass("has-error");
            $('<span class="help-block">Please Enter  Asset Doc. Caption.</span>').appendTo(documentdesc.parent());
            return false;
        }
        if($.trim(fileup.val()) === "") {
            $(fileup).parent().parent().addClass("has-error");
            $('<span class="help-block">Please select upload file.</span>').appendTo(fileup.parent());
            return false;
        } else {

            if(readTabDocURL(fileup)){
                $(fileup).parent().parent().removeClass("has-error");
            }
            else{
                $(fileup).parent().parent().addClass("has-error");
                $("<span class='help-block'>Please select valid file. File Format : 'pdf','jpg','gif','png','jpeg'</span>").appendTo(fileup.parent());
                return false;
            }


        }
        $("#assetdocumentform #assetdoctypename").val($("#assetdocumentform #assetdoctype option:selected").text());

        return true;
    });
    
  
     
    if($('#assetimageform').length) {
        $("#assetimageform").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                    mdocumentdesc: {
                            required:  {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                            }
                    } 
            },
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
                        if(element.val()==="")
                        {
                                element.focus();
                        }
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
                        $(e).parent().parent().parent().removeClass("has-error");
                    }
                    else{
                        $(e).parent().parent().removeClass("has-error");
                    }


                },
            submitHandler: function() {
                $("#mdocsavebtn").button('loading');
                $("#mdocclosebtn").button('loading');
                $("#mdocclose").css("display", "none");

                $.ajax({ url: base_url + 'asset/udpatedocumentdata', data: $("#assetimageform").serialize(), method:'post', dataType: 'json', 
                    success: function(response) {
                        if(response.success) {
                            document.location.reload();
                        }
                        else{
                            bootbox.alert(response.message);
                        }

                    }
                }); 
                return false;
            }
        });
    }
    
});   
var readTabDocURL = function(input) {
	
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['pdf','png','jpg','jpeg','gif']) === -1) {
            $(input).val('');

        bootbox.alert('invalid file format!');
        return false;
    }

    return true;
};
 
 var readExcelURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
     
    if($.inArray(ext, ['xls','xlsx']) === -1) {
        $(input).val('');
        
        bootbox.alert('invalid file format!');
        return false;
    }
     return true;
};

var getdocument = function(id) {
	var url = base_url + 'asset/loaddocumentdata';
	var assetid = $("#tab_asset_form #assetid").val();
	$("#assetimagedescmodal img").css("display", "block");
	$("#assetimageform").css("display", "none");
	$("#assetimagedescmodal").modal('show');
	$.ajax({ url: url, data: { "id":id, "assetid":assetid }, method:'post', dataType: 'json', 
            success: function(response) {
                
                $("#assetimagedescmodal img").css("display", "none");
                $("#assetimageform").css("display", "block");
                if(response.success) {
                    $.each(response.data, function(k, v) {
                        $("#assetimageform #assetid").val(assetid);
                        $("#assetimageform #mdocumentdesc").val(v.documentdesc);
                        $("#assetimageform #mdocnote").val(v.docnote);
                        $("#assetimageform #hdocumentid").val(v.documentid);
                    });
                }
                else{
                    bootbox.alert(response.message);
                }
                	
        }}); 
};