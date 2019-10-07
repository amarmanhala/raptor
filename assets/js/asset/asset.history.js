/* global base_url, google, bootbox, parseFloat */
/* global base_url, bootbox, app */

"use strict";

app.controller('AssetHistoryCtrl', [
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
  
   $scope.assetHistoryOptions = {
     paginationPageSizes: [10, 20, 30],
     paginationPageSize: 10,
     useExternalPagination: true,
     useExternalSorting: true,
     enableColumnResizing: true,
     enableColumnMenus: false,
     //enableFiltering: true,
     columnDefs: [
                { 
                    displayName:'Date',
                    cellTooltip: true,
                    name: 'activity_date',
                    width: 120
                },
                { 
                    displayName:'Job ID',
                    cellTooltip: true,
                    name: 'jobid',
                    width: 90
                },
                { 
                    displayName:'PO Ref', 
                    cellTooltip: true, 
                    name: 'poref',  
                    width: 90 
                     
                },
                { 
                    displayName:'Activity Category', 
                    cellTooltip: true, 
                    name: 'activity_name', 
                    enableFiltering: false, 
                    width: 150 
                },
                { 
                    displayName:'Description', 
                    cellTooltip: true, 
                    name: 'activity_description', 
                    enableFiltering: false
                     
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
            getAssetHistory();
        });

         gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
           paginationOptions.pageNumber = newPage;
           paginationOptions.pageSize = pageSize;
           getAssetHistory();
         });

     }
   };
 
    

   var getAssetHistory = function() {

       $('#AssetHistoryCtrl .overlay').show();
        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort,
            assetid : $('#tab_asset_form #assetid').val()
        }; 

        var qstring = $.param(params);
         var url = base_url+'asset/loadassethistory?='+qstring;

         $http.get(url).success(function (data) {
             $('#AssetHistoryCtrl .overlay').hide();
                if (data.success === false) {
                    bootbox.alert(data.message); 
                    return false;
                }else{

                    $scope.assetHistoryOptions.totalItems = data.total;
                    $scope.assetHistoryOptions.data = data.data;  
                } 

                
         });
   };

   getAssetHistory();
    
    var deferred;  
     
    //Any function returning a promise object can be used to load values asynchronously
    $scope.getJobs = function(val) {

            deferred = $q.defer(); 
            $http.get(base_url+'asset/loadjobids', {
                params: {
                            search: val
                       },
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{

                    deferred.resolve(response.data);
                }

            });
            return deferred.promise;  

        };
       
        $scope.onJobSelect = function ($item, $model, $label) {
             
            $scope.jobid = $item.jobid;
            $("#assetdetailhistoryform #hidjobid").val($item.jobid); 
           
         };
       
    //Any function returning a promise object can be used to load values asynchronously
    $scope.getPOs = function(val) {

        var cid = $("#assetdetailhistoryform #jobid").val();
        if($.trim(cid) === "") {
            $scope.poref = '';
                alert("please select Jobid.");
                return false;
        }
        
            deferred = $q.defer(); 
            $http.get(base_url+'asset/loadporef', {
                params: {
                            search: val,
                            jobid: cid
                       },
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{

                    deferred.resolve(response.data);
                }

            });
            return deferred.promise;  

        };
       
        $scope.onPOSelect = function ($item, $model, $label) {
             
            $scope.poref = $item.poref;
            $("#assetdetailhistoryform #hidporef").val($item.poref); 
           
         };
    
    $("#assetdetailhistoryform").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                    jobid: {
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    }, 
                    poref: {
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    }, 
                    activity_date: {
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

                    $("#assetdetailhistoryform .e-status").html('');
                    $("#assetdetailhistoryform #dhsavebtn").button('loading');
                    $("#assetdetailhistoryform #dhclosebtn").button('loading');
                    $("#assetdetailhistoryform #dhclose").css("display", "none");

                    $.ajax({ url: base_url + 'asset/udpatehistory', data: $("#assetdetailhistoryform").serialize(), method:'post', dataType: 'json', 
                              success: function(data) {
                                    $("#assetdetailhistoryform #dhclose").css("display", "block");
                                    $("#assetdetailhistoryform #dhsavebtn").button('reset');
                                    $("#assetdetailhistoryform #dhclosebtn").button('reset');

                                    if(data.success) {
                                        $("#assetdetailhistorymodal").modal('hide');
                                        getAssetHistory();
                                         
                                    }
                                    else{
                                        $("#assetdetailhistoryform .e-status").html('<div class="alert alert-danger">'+data.message+'</div>');

                                    }
                    }
                }); 
            return false;
            }
    });
    }
]);

$( document ).ready(function() {
    
 
    if($('#assetdetailhistoryform #poref1').length) {
        
        $('#assetdetailhistoryform #poref1').typeahead({
            ajax: {
                url: base_url + 'asset/loadporef',
                method: 'get',
                preDispatch: function (query) {
                    
                    return {
                        search: query,
                        jobid: cid
                    };
                },
                preProcess: function (response) {
                    if(response.success) {
                       return response.data; 
                    }
                    else{
                        bootbox.alert(response.message);
                        return false;
                    }
                               
                }
            },
            onSelect: function(item) {
               $("#assetdetailhistoryform #hidporef").val(item.value);	
                $("#assetdetailhistoryform #dhsavebtn").removeAttr('disabled');
            },
                displayField: 'poref',
                valueField: 'poref'           
        });
    }
  
    $(document).on('click', '#add_asset_note_history', function() {
	 
        var modalb = $("#assetdetailhistorymodal").modal({
                backdrop: 'static',
                keyboard:false
        });
        $("#assetdetailhistoryform #assetid").val($("#tab_asset_form #assetid").val());
        $("#assetdetailhistoryform #jobid").val('');
        $("#assetdetailhistoryform #hidjobid").val('');
        $("#assetdetailhistoryform #poref").val('');
        $("#assetdetailhistoryform #poref").removeAttr('disabled','disabled');
        $("#assetdetailhistoryform #hidporef").val('');
        $("#assetdetailhistoryform #activity_date").val('');
        $("#assetdetailhistoryform #activity_category").val('');
        $("#assetdetailhistoryform #activity_category").select2();
        $("#assetdetailhistoryform #description").val('');
        $("#assetdetailhistoryform .e-status").html('');
        $("#assetdetailhistoryform #dhsavebtn").removeAttr('disabled');
        //$("#assetdetailhistoryform #dhsavebtn").attr('disabled','disabled');
        modalb.modal('show');
    });
 
});   
