/* global base_url, angular, bootbox */

"use strict";
    var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('CostCenterCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

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

    $scope.edit_costcentre = $('#edit_costcentre').val()==='1'?true:false;
    $scope.edit_opt = $('#edit_costcentre').val()==='1'?'':'disabled="disabled"';
    $scope.gridOptions = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [  
            
            { 
                displayName:'Cost Centre',
                cellTooltip: true,
                name: 'costcentre',
                width: 150
            },
            {   displayName:'Description', 
                cellTooltip: true, 
                name: 'description', 
                enableFiltering: false
            },
            { 
                displayName:'Active',
                name: 'isactive', 
                width:80,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.isactive == 0 "><input type="checkbox" value="{{row.entity.id}}"  class="chk_isactive" '+$scope.edit_opt+'/></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.isactive == 1"><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="chk_isactive" '+$scope.edit_opt+'/></div>'
            },
            { 
                displayName:'Edit',
                name: 'id',
                cellTooltip: true,
                enableFiltering: false,  
                 enableSorting: false,
                visible :$('#edit_costcentre').val()==='1'?true:false, 
                width: 60,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.id}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editCostCentre(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_costcentre').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteCostCentre(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
           
           window.open(base_url+'customers/exportcostcentres?'+$.param($scope.filterOptions));
        };
        $scope.exportImportTemplate = function(){
           
           window.open(base_url+'customers/downloadcostcentretemplate');
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
            $http.get(base_url+'customers/loadcostcentres?'+ qstring, {
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
        

        $(document).on('change', '.chk_isactive', function(event) {
            var id = $(this).val();
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateCostCentres(id, 'isactive', value);

        }); 
    
        var updateCostCentres = function(id, field, value) {
 
            var params = { 
                id  : id,
                field: field,
                value: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'customers/updatecostcentre', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {

                }
                else {
                    bootbox.alert(data.message);
                    
                }
            });
        };
        
        $scope.deleteCostCentre = function(entity) {

            bootbox.confirm("Are you sure to delete cost Centre <b>"+entity.costcentre+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"customers/deletecostcentre", { id:entity.id }, function( response ) {
                        if (response.success) {
                            getPage();
                            $('#mycostcentrestatus').html('<div class="alert alert-success" >Cost Centre deleted successfully.</div>');
                            clearMsgPanel();
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editCostCentre = function(index, id) {
         
            
            $("#costcentreModal #loading-img").show();
            $("#costcentreModal #sitegriddiv").hide();
            $('#costcentreform').trigger("reset");
            $("#costcentreform .alert-danger").hide(); 
            $("#costcentreform span.help-block").remove();
            $("#costcentreform .has-error").removeClass("has-error");
            $('#costcentreform #btnsave').button("reset");
            $('#costcentreform #btncancel').button("reset");
            $('#costcentreform #btnsave').removeAttr("disabled");
            $('#costcentreform #btncancel').removeAttr("disabled");
            $("#costcentreform .close").css('display', 'block');
            $("#costcentreModal h4.modal-title").html('Edit Cost Centre ' + index.costcentre);
            $("#costcentreModal").modal();

            $("#costcentreform #costcentreid").val(id); 
            $("#costcentreform #mode").val('edit');  
             

            setTimeout(function(){ 
                $("#costcentreModal #loading-img").hide();
                $("#costcentreModal #sitegriddiv").show();

            }, 1000);
            
             
            $("#costcentreform #costcentre").val(index.costcentre); 
            $("#costcentreform #description").val(index.description); 
               
            

        };
        $scope.importCostCenter = function() { 
            
            $("#importcostcentreModal #loading-img").show();
            $("#importcostcentreModal #sitegriddiv").hide();
            $('#importcostcentreform').trigger("reset");
            $("#importcostcentreform .alert-danger").hide(); 
            $("#importcostcentreform span.help-block").remove();
            $("#importcostcentreform .has-error").removeClass("has-error");
            $('#importcostcentreform #btnsave').button("reset");
            $('#importcostcentreform #btncancel').button("reset");
            $('#importcostcentreform #btnsave').removeAttr("disabled");
            $('#importcostcentreform #btncancel').removeAttr("disabled");
            $("#importcostcentreModal .close").css('display', 'block');
             $('#status').empty();
            var percentVal = '0';
            $('.progress-bar').attr('aria-valuenow',percentVal);
            $('.progress-bar').css('width',percentVal+"%");
            $('.sr-only').html(percentVal + "% Complete ");
            $("#importcostcentreModal").modal();
             setTimeout(function(){ 
                $("#importcostcentreModal #loading-img").hide();
                $("#importcostcentreModal #sitegriddiv").show();

            }, 1000);
        };
        
        
        
        
        $scope.addCostCenter = function() { 
         
            $("#costcentreModal #loading-img").show();
            $("#costcentreModal #sitegriddiv").hide();
            $('#costcentreform').trigger("reset");
            $("#costcentreform .alert-danger").hide(); 
            $("#costcentreform span.help-block").remove();
            $("#costcentreform .has-error").removeClass("has-error");
            $('#costcentreform #btnsave').button("reset");
            $('#costcentreform #btncancel').button("reset");
            $('#costcentreform #btnsave').removeAttr("disabled");
            $('#costcentreform #btncancel').removeAttr("disabled");
            $("#costcentreform .close").css('display', 'block');
            $("#costcentreModal h4.modal-title").html('Add Cost Centre');
            $("#costcentreModal").modal();

            $("#costcentreform #costcentreid").val(''); 
            $("#costcentreform #mode").val('add');  

            setTimeout(function(){ 
                $("#costcentreModal #loading-img").hide();
                $("#costcentreModal #sitegriddiv").show();

            }, 1000);

        };

        $(document).on('click', '#costcentreModal #btnsave', function() {

           
            var costcentre = $("#costcentreform #costcentre");
            var description = $("#costcentreform #description");
            $("#costcentreform span.help-block").remove();

           
            if($.trim(costcentre.val()) === "") {
                $(costcentre).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(costcentre.parent());
            } else {
                $(costcentre).parent().removeClass("has-error");
            }

            if($.trim(description.val()) === "") {
                $(description).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(description.parent());
            } else {
                $(description).parent().removeClass("has-error");
            }

            if($.trim(costcentre.val()) === "" || $.trim(description.val()) === ""){
                 return false;
            }

            $("#costcentreform #btnsave").button('loading'); 
            $("#costcentreform #btncancel").button('loading'); 
            $.post( base_url+"customers/addeditcostcentre", $("#costcentreform").serialize(), function( data ) {
                $('#costcentreform #btnsave').removeAttr("disabled");
                $('#costcentreform #btncancel').removeAttr("disabled");
                
                $('#costcentreform #btnsave').removeClass("disabled");
                $('#costcentreform #btncancel').removeClass("disabled");
                $('#costcentreform #btnsave').html("Save");
                $('#costcentreform #btncancel').html("Cancel");
                if(data.success) {

                    $( "#CostCenterCtrl .btn-refresh" ).click(); 
                    $("#costcentreModal").modal('hide');
                    $('#mycostcentrestatus').html('<div class="alert alert-success" >Cost Centre update successfully.</div>');
                    clearMsgPanel();
                }
                else{
                     $('#costcentreModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#costcentreModal #btncancel', function() {
            $("#costcentreModal").modal('hide');
        });
        
        $(document).on('click', '#importcostcentreModal #btncancel', function() {
            $("#importcostcentreModal").modal('hide');
        });
      
    }
]);
 
app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$(document).ready(function() {
   
  
    $("#importcostcentreModal #btnsave").on('click', function() {
         
            var fileup = $("#importcostcentreform #importfile"); 
            $("#importcostcentreform span.help-block").remove();
          

            if($.trim(fileup.val()) === "") {
                $(fileup).parent().parent().addClass("has-error");
                $('<span class="help-block">Please select upload file.</span>').appendTo(fileup.parent());
                return false;
            } else {

                if(readExcelURL(fileup)){
                    $(fileup).parent().parent().removeClass("has-error");
                }
                else{
                    $(fileup).parent().parent().addClass("has-error");
                    $("<span class='help-block'>Please select valid file. File Format : 'xls','xlsx'</span>").appendTo(fileup.parent());
                    return false;
                }


            } 
            
            return true;
        });
    
    if (typeof $.fn.ajaxForm === "function") {
       
        $('#importcostcentreform').ajaxForm({
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
                   var out2 = $.parseJSON(xhr.responseText);
                    if(out2.success){
                        $('#status').html('<div class="alert alert-success" >'+out2.message+'</div>');
                        setTimeout(function(){ 
                            $("#importcostcentreModal").modal('hide');
                            $( "#CostCenterCtrl .btn-refresh" ).click();
                            //document.location.reload(); 
                        }, 1000);
                    }
                    else{
                        $('#status').html('<div class="alert alert-danger" >'+out2.message+'</div>');
                    }
                    
                }
            });
        }
    
});
function clearMsgPanel(){
    setTimeout(function(){ 
            $("#mycostcentrestatus").html('');
           
    }, 3000);
} 
 
 
 var readExcelURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
     
    if($.inArray(ext, ['xls','xlsx']) === -1) {
        $(input).val('');
        
        bootbox.alert('invalid file format!');
        return false;
    }
     return true;
}; 