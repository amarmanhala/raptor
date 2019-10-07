/* global base_url, angular, app, bootbox */

"use strict";
 var app = angular.module('app', ['ui.grid', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('ModulesCtrl', [
        '$scope', '$http', 'uiGridConstants', '$log', function($scope, $http, uiGridConstants, $log) {
  
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
    
    $scope.gridOptions = {
        
         useExternalSorting: false,
         enableColumnResizing: true,
         enableColumnMenus: false, 
         columnDefs: [
             
            { 
                displayName:'ID',
                cellTooltip: true,
                name: 'id',
                width: 60,
                visible : false
            },
            { 
                displayName:'Name',
                cellTooltip: true,
                name: 'name' 
            },
            { 
                displayName:'Parent',
                cellTooltip: true,
                name: 'parent' 
            },
            { 
                displayName:'URL 1',
                cellTooltip: true,
                name: 'url1',
                enableFiltering: false,
                width: 120
            },
            { 
                displayName:'URL 2',
                cellTooltip: true,
                name: 'url2',
                enableFiltering: false,
                width: 120
            },
            { 
                displayName:'URL 3',
                cellTooltip: true,
                name: 'url3',
                enableFiltering: false,
                width: 100
            },
            { 
                displayName:'Counter',
                name: 'showcounter', 
                width:70,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Counter</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.id}}" data-id="{{row.entity.id}}"  class="chk_showcounter"  ng-checked="row.entity.showcounter == 1" /></div>'
            },
            { 
                displayName:'Icon',
                name: 'menu_icon', 
                width:50,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Icon</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><i class="{{row.entity.menu_icon}}" ng-if="row.entity.menu_icontype==\'ICON\'"></i><i ng-if="row.entity.menu_icontype==\'IMAGE\'"><img src="'+base_img_url+'assets/img/{{row.entity.menu_image}}"/></i></div>'
            },
            { 
                displayName:'Order',
                name: 'sortorder', 
                width:50,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Order</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center">{{row.entity.sortorder}}</div>'
            },
            { 
                displayName:'Active',
                name: 'isactive', 
                width:70,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.id}}" data-id="{{row.entity.id}}"  class="chk_isactive"  ng-checked="row.entity.isactive == 1" /></div>'
            },
             
            { 
                displayName:'Edit',
                name: 'edit',
                cellTooltip: true,
                enableFiltering: false,  
                enableSorting: false, 
                width: 50,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="Edit Module"><a   href="javascript:void(0)" ng-click="grid.appScope.editModule(row.entity, row.entity.id)" ><i class = "fa fa-edit"></i></a></div>'
            } 
             
         ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             
            
         }
       };
       
        
       
      $scope.refreshFilter = function() {
           ModulesPage();
    };
       
       
    var ModulesPage = function() {
           
        
        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        
        
        var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
  
        $scope.overlay = true;
        $http.get(base_url+'admin/modules/loadmodules?'+ qstring, {
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
 
    ModulesPage();
   
    $scope.addModule = function() { 
         
            $("#menuModuleModel #loading-img").show();
            $("#menuModuleModel #sitegriddiv").hide();
            $('#moduleform').trigger("reset");
            $("#moduleform .alert-danger").hide(); 
            $("#moduleform span.help-block").remove();
            $("#moduleform .has-error").removeClass("has-error");
            $('#moduleform #btnsave').button("reset");
            $('#moduleform #btncancel').button("reset");
            $('#moduleform #btnsave').removeAttr("disabled");
            $('#moduleform #btncancel').removeAttr("disabled");
            $("#moduleform .close").css('display', 'block');
            $("#menuModuleModel h4.modal-title").html('Add Module');
            $("#menuModuleModel").modal();

            $("#moduleform #menuid").val(''); 
            $("#moduleform #mode").val('add');  
  
            
            $("#moduleform #name").val(''); 
            $("#moduleform #parentid").val(0); 
            
            $("#moduleform #url1").val(''); 
            $("#moduleform #url2").val(''); 
            $("#moduleform #url3").val(''); 
            $("#moduleform #counter_bgcolor").val('#dd4b39'); 
            $("#moduleform .counter_bgcolor").css('background-color','#dd4b39');
            $("#moduleform #counter_color").val('#ffffff'); 
            $("#moduleform .counter_color").css('color','#ffffff'); 
            $("#moduleform #menu_icon").val('fa fa-th'); 
            $("#moduleform .menu_icon").attr('class','menu_icon fa fa-th'); 
            
    
            $("#moduleform #sortorder").val(parseInt($scope.gridOptions.totalItems)+1); 
            $("#moduleform #target").val(''); 
            
            $('#moduleform input[name="showcounter"]').prop('checked', false);
            $('#moduleform input[name="isactive"]').prop('checked', true);
            $('#moduleform input[name="masteraccess"]').prop('checked', true);
            $('#moduleform input[name="fmaccess"]').prop('checked', false);
            $('#moduleform input[name="sitecontactaccess"]').prop('checked', false);
            $('#moduleform #menu_icontype_icon').prop('checked', true);
            $('.menu_icon_ele').show();
            $('.menu_image_ele').hide();
        
            setTimeout(function(){ 
                $("#menuModuleModel #loading-img").hide();
                $("#menuModuleModel #sitegriddiv").show();

            }, 1000);
 
        };
   
        $(document).on('click', "#moduleform input[name=menu_icontype]", function() {
            var typecode = $(this).val();
          
            $('.menu_icon_ele').hide();
            $('.menu_image_ele').hide();
            
            if(typecode === 'ICON'){
               $('.menu_icon_ele').show();
            }
            if(typecode === 'IMAGE'){
               $('.menu_image_ele').show();
            }
            
        });
     $scope.editModule = function(index, id) {
            
        $("#menuModuleModel #loading-img").show();
        $("#menuModuleModel #sitegriddiv").hide();
        $('#moduleform').trigger("reset");
        $("#moduleform .alert-danger").hide(); 
        $("#moduleform span.help-block").remove();
        $("#moduleform .has-error").removeClass("has-error");
        $('#moduleform #btnsave').button("reset");
        $('#moduleform #btncancel').button("reset");
        $('#moduleform #btnsave').removeAttr("disabled");
        $('#moduleform #btncancel').removeAttr("disabled");
        $("#moduleform .close").css('display', 'block');
        $("#menuModuleModel h4.modal-title").html('Edit Module - ' + index.name);
        $("#menuModuleModel").modal();

        $("#moduleform #menuid").val(id); 
        $("#moduleform #mode").val('edit');  

        setTimeout(function(){ 
            $("#menuModuleModel #loading-img").hide();
            $("#menuModuleModel #sitegriddiv").show();
        }, 1000);
            
            
        $("#moduleform #name").val(index.name); 
        $("#moduleform #parentid").val(index.parentid); 

        $("#moduleform #url1").val(index.url1); 
        $("#moduleform #url2").val(index.url2); 
        $("#moduleform #url3").val(index.url3);
        
        $("#moduleform #counter_keyword").val(index.counter_keyword); 

        $("#moduleform #counter_bgcolor").val(index.counter_bgcolor); 
        $("#moduleform .counter_bgcolor").css('background-color',index.counter_bgcolor);
        
        $("#moduleform #counter_color").val(index.counter_color); 
        $("#moduleform .counter_color").css('color',index.counter_color); 
        
        $("#moduleform #menu_icon").val(index.menu_icon); 
        $("#moduleform .menu_icon").attr('class','menu_icon '+ index.menu_icon); 
        $("#moduleform #menu_image").val(index.menu_image); 
        if(index.menu_icontype === 'ICON'){
            $('#moduleform #menu_icontype_icon').prop('checked', true);
            
            $('.menu_icon_ele').show();
            $('.menu_image_ele').hide();
        }
        else{
            $('#moduleform #menu_icontype_image').prop('checked', true);
           
            $('.menu_icon_ele').hide();
            $('.menu_image_ele').show();
        }
       
        
       

        $("#moduleform #sortorder").val(index.sortorder); 
        $("#moduleform #target").val(index.target); 
        
        if(parseInt(index.showcounter) === 1){
            $('#moduleform input[name="showcounter"]').prop('checked', true);
        }
        else{
            $('#moduleform input[name="showcounter"]').prop('checked', false);
        }   

        if(parseInt(index.isactive) === 1){
            $('#moduleform input[name="isactive"]').prop('checked', true);
        }
        else{
            $('#moduleform input[name="isactive"]').prop('checked', false);
        }   

        if(parseInt(index.masteraccess) === 1){
            $('#moduleform input[name="masteraccess"]').prop('checked', true);
        }
        else{
            $('#moduleform input[name="masteraccess"]').prop('checked', false);
        }   

        if(parseInt(index.fmaccess) === 1){
            $('#moduleform input[name="fmaccess"]').prop('checked', true);
        }
        else{
            $('#moduleform input[name="fmaccess"]').prop('checked', false);
        }
        if(parseInt(index.sitecontactaccess) === 1){
            $('#moduleform input[name="sitecontactaccess"]').prop('checked', true);
        }
        else{
            $('#moduleform input[name="sitecontactaccess"]').prop('checked', false);
        }
           
    };
   
    var updateMenuModule = function(id, field, value, input) {
    
            var postData = {
                id:id, 
                value: value,
                field: field
            }; 

            $.each($scope.gridOptions.data, function( key, val ) {
                if(parseInt(val.id) === parseInt(id)){
                    if(field === 'showcounter'){
                        $scope.gridOptions.data[key].showcounter = value;
                    }
                    else if(field === 'isactive'){
                        $scope.gridOptions.data[key].isactive = value;
                    }
                   
                }
                        
            });

            input.addClass('custom-input-success');
            var qstring = $.param(postData);
            $scope.overlay = true;
            $http.post(base_url+'admin/modules/updatemodule', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                    setTimeout(removecls(input), 2000);
                }
                else {
                    bootbox.alert(data.message);
                }
            });
            
        };

        var removecls = function(input) {
            input.removeClass('custom-input-success');
        };
 

        $(document).on('change', '.chk_showcounter', function() {
            
            var rulename_id = $(this).attr('data-id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateMenuModule(rulename_id, 'showcounter', value, $(this));
        
        }); 
        
        $(document).on('change', '.chk_isactive', function() {
            
            
            
            var rulename_id = $(this).attr('data-id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateMenuModule(rulename_id, 'isactive', value, $(this));
         
        });
        
         $(document).on('click', '#menuModuleModel #btnsave', function() {

           
            var caption = $("#moduleform #name");
           
           
            $("#moduleform span.help-block").remove();
          
           
            if($.trim(caption.val()) === "") {
                $(caption).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(caption.parent());
            } else {
                $(caption).parent().removeClass("has-error");
            }
 
 

            if($.trim(caption.val()) === ""){
                 return false;
            }

            $("#moduleform #btnsave").button('loading'); 
            $("#moduleform #btncancel").button('loading'); 
            $.post( base_url+"admin/modules/addeditmodule", $("#moduleform").serialize(), function( response ) {
                $('#moduleform #btnsave').removeAttr("disabled");
                $('#moduleform #btncancel').removeAttr("disabled");
                
                $('#moduleform #btnsave').removeClass("disabled");
                $('#moduleform #btncancel').removeClass("disabled");
                $('#moduleform #btnsave').html("Save");
                $('#moduleform #btncancel').html("Cancel");
                if(response.success) {
                    
                    $("#menuModuleModel").modal('hide');
                    bootbox.alert('Module update successfully.');
                    ModulesPage();
                    
                    
                }
                else{
                     $('#menuModuleModel .status').html('<div class="alert alert-danger" >'+response.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#menuModuleModel #btncancel', function() {
            $("#menuModuleModel").modal('hide');
        });
}

]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$(document).ready(function(){
    
    $(document).on('change', "#moduleform #menu_icon", function() {
        var menuclass = $(this).val();
        $("#moduleform .menu_icon").attr('class','menu_icon '+ menuclass); 
         
    });
    
    //Colorpicker
    $(".my-colorpicker1").colorpicker();
});
 
 