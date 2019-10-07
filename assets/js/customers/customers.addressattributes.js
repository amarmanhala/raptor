/* global base_url, angular, bootbox */

"use strict";
if($("#AddressAttributeCtrl").length) {
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('AddressAttributeCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

          // filter
    $scope.filterOptions = {
        filtertext: ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.edit__addressattributes = $('#edit_addressattributes').val()==='1'?true:false;
    $scope.edit_opt = $('#edit_addressattributes').val()==='1'?'':'disabled="disabled"';
    $scope.gridOptions = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [  
            {   displayName:'Attribute Name', 
                cellTooltip: true, 
                name: 'name', 
                enableFiltering: false
            },
            { 
                displayName:'Caption',
                cellTooltip: true,
                name: 'caption',
                width: 120
            },
             { 
                displayName:'Type',
                cellTooltip: true,
                name: 'type',
                width: 100
            },
            { 
                displayName:'Active',
                name: 'status', 
                width:80,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"  ng-if="row.entity.customerid == 0"><input type="checkbox" value="{{row.entity.id}}"  class="chk_status" disabled="disabled"  ng-if="row.entity.status==0"/><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="chk_status" disabled="disabled" ng-if="row.entity.status == 1"/></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.customerid != 0"><input type="checkbox" value="{{row.entity.id}}"  class="chk_status" '+$scope.edit_opt+'  ng-if="row.entity.status==0"/><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="chk_status" '+$scope.edit_opt+' ng-if="row.entity.status == 1"/></div>'
            },
            { 
                displayName:'Edit',
                name: 'id',
                cellTooltip: true,
                enableFiltering: false,  
                 enableSorting: false,
                visible :$('#edit_addressattributes').val()==='1'?true:false, 
                width: 60,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="Edit" ><a  href="javascript:void(0)" ng-click="grid.appScope.editAddressAttribute(row.entity, row.entity.id)" ng-if="row.entity.customerid !=0"><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_addressattributes').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteAddressAttribute(row.entity)" ng-if="row.entity.customerid !=0"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                getPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               getPage();
             });
 	
         }
       };
        
        $scope.exportToExcel = function(){
           
           window.open(base_url+'customers/exportaddressattributes?'+$.param($scope.filterOptions));
        };
        
        $scope.changeText = function() {
            getPage();
        }; 
        
        $scope.changeFilters = function() {
           getPage();
        };
        
        $scope.clearFilters = function() {
            
            $scope.filterOptions = {
                filtertext: ''
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
        var getPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'customers/loadaddressattributes?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.gridOptions.totalItems = response.total;
                    $scope.gridOptions.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
    
       getPage();
        

        $(document).on('change', '.chk_status', function(event) {
            var id = $(this).val();
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateAddressAttribute(id, 'status', value);

        }); 
    
        var updateAddressAttribute = function(id, field, value) {
 
            var params = { 
                id  : id,
                field: field,
                value: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'customers/updateaddressattribute', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                    $.each($scope.gridOptions.data, function( key, val ) {
                        if(parseInt(val.id) === parseInt(id)){
                            if(parseInt(val.status) === 1){
                                $scope.gridOptions.data[key].status = 0;
                            }
                            else{
                                $scope.gridOptions.data[key].status = 1;
                            }
                            return;
                        }
                        
                    });
                     
                }
                else {
                    bootbox.alert(data.message);
                    
                }
            });
        };
        
        $scope.deleteAddressAttribute = function(entity) {

            bootbox.confirm("Are you sure to delete Address Attribute <b>"+entity.name+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"customers/deleteaddressattribute", { id:entity.id }, function( response ) {
                        if (response.success) {
                            getPage();
                            $('#myaddressattributestatus').html('<div class="alert alert-success" >Address Attribute deleted successfully.</div>');
                            clearMsgPanel();
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editAddressAttribute = function(index, id) {
          
            
           
            $("#addressattributeModal #loading-img").show();
            $("#addressattributeModal #sitegriddiv").hide();
            $('#addressattributeform').trigger("reset");
            $("#addressattributeform .alert-danger").hide(); 
            $("#addressattributeform span.help-block").remove();
            $("#addressattributeform .has-error").removeClass("has-error");
            $('#addressattributeform #btnsave').button("reset");
            $('#addressattributeform #btncancel').button("reset");
            $('#addressattributeform #btnsave').removeAttr("disabled");
            $('#addressattributeform #btncancel').removeAttr("disabled");
            $("#addressattributeform .close").css('display', 'block');
            $("#addressattributeModal h4.modal-title").html('Edit Address Attribute ' + index.name);
            $("#addressattributeModal").modal();

            $("#addressattributeform #addressattributeid").val(id); 
            $("#addressattributeform #mode").val('edit');  
            
            setTimeout(function(){ 
                $("#addressattributeModal #loading-img").hide();
                $("#addressattributeModal #sitegriddiv").show();

            }, 500);
             
            $("#addressattributeform #newattribute").val(index.name); 
            $("#addressattributeform #caption").val(index.caption); 
            $("#addressattributeform #attributetypeid").val(index.attributetypeid); 
      
            if(parseInt(index.highlighted) === 1){
                $('#addressattributeform input[name="highlighted"]').prop('checked', true);
            }
            else{
                $('#addressattributeform input[name="highlighted"]').prop('checked', false);
            }
            if(parseInt(index.status) ===1){
                $('#addressattributeform input[name="status"]').prop('checked', true);
            }
            else{
                $('#addressattributeform input[name="status"]').prop('checked', false);
            }
            
           
        };
       
    }
]);
 
app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
}
$(document).ready(function(){
   
   $(document).on('click', '#createaddressattribute', function() {
       $("#addressattributeModal #loading-img").show();
        $("#addressattributeModal #sitegriddiv").hide();
        $('#addressattributeform').trigger("reset");
        $("#addressattributeform .alert-danger").hide(); 
        $("#addressattributeform span.help-block").remove();
        $("#addressattributeform .has-error").removeClass("has-error");
        $('#addressattributeform #btnsave').button("reset");
        $('#addressattributeform #btncancel').button("reset");
        $('#addressattributeform #btnsave').removeAttr("disabled");
        $('#addressattributeform #btncancel').removeAttr("disabled");
        $("#addressattributeform .close").css('display', 'block');
        $("#addressattributeModal h4.modal-title").html('Add Address Attribute');
        $("#addressattributeModal").modal();

        $("#addressattributeform #addressattributeid").val(''); 
        $("#addressattributeform #mode").val('add');  

        setTimeout(function(){ 
            $("#addressattributeModal #loading-img").hide();
            $("#addressattributeModal #sitegriddiv").show();

        }, 500);
   });
     
    if (typeof $.fn.validate === "function") {   
        $("#addressattributeform").validate({
            rules: {
                newattribute: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                caption: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                attributetypeid: {  
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
                if(element.parent().is('.input-group')) {
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
                 $("#addressattributeform #btnsave").button('loading'); 
                $("#addressattributeform #btncancel").button('loading'); 
                $("#addressattributeform .alert-danger").hide(); 
                $.post( base_url+"customers/addeditaddressattribute", $('#addressattributeform').serialize(), function( response ) {
                    $('#addressattributeform #btnsave').removeAttr("disabled");
                    $('#addressattributeform #btncancel').removeAttr("disabled");

                    $('#addressattributeform #btnsave').removeClass("disabled");
                    $('#addressattributeform #btncancel').removeClass("disabled");
                    $('#addressattributeform #btnsave').html("Save");
                    $('#addressattributeform #btncancel').html("Cancel");
                    if (response.success) {
                        if (response.data.success) {
                            $("#addressattributeModal").modal('hide');
                            $('#addressattributeform').trigger("reset");
                            
                            if($("#AddressAttributeCtrl").length>0){
                                $( "#AddressAttributeCtrl .btn-refresh" ).click(); 
                                $("#addressattributeModal").modal('hide');
                                $('#myaddressattributestatus').html('<div class="alert alert-success" >Address Attribute update successfully.</div>');
                                clearMsgPanel();
                            }
                            else{
                                var options = "<option value = ''>-Select-</option>";
                                $.each( response.data.data, function( key, val ) {
                                    options = options + "<option value = '"+val.id+"' data-type='"+val.type+"'>"+val.name+"</option>";
                                });
                                $('#address_attribute_form #attribute').html(options);
                                $('#address_attribute_form #attribute').val( response.data.id);
                                $('#address_attribute_form #attribute').trigger('change');
                                modaloverlap();
                            }
                        }
                        else{
                            $('#addressattributeModal .status').html('<div class="alert alert-danger" >'+response.data.message+'</div>');
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
    $(document).on('click', '#addressattributeModal #btncancel', function() {
        $("#addressattributeModal").modal('hide');
    });
    
});

function clearMsgPanel(){
    setTimeout(function(){ 
            $("#myaddressattributestatus").html('');
           
    }, 3000);
} 
 