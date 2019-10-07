/* global base_url, angular, bootbox */

"use strict";
    var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('GlCodesCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

          // filter
    $scope.filterOptions = {
        filtertext: '',
        accounttype:'',
        labelid: '',
        state: [],
        jobtypeid:'',
        budget_categoryid:'',
        budget_itemid:'',
        se_tradeid:'',
        se_worksid:'',
        se_subworksid:'',
        asset_categoryid:'',
        showasset : true
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.edit_glcode = $('#edit_glcode').val()==='1'?true:false;
    $scope.edit_opt = $('#edit_glcode').val()==='1'?'':'disabled="disabled"';
    $scope.gridOptions = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [  
            
            { 
                displayName:'Site Address',
                cellTooltip: true,
                name: 'address',
                width: 150
            },
            {   displayName:'Asset', 
                cellTooltip: true, 
                name: 'asset', 
                enableFiltering: false ,
                width: 100
            },
            { 
                displayName:'Asset Category',
                cellTooltip: true,
                name: 'assetcategory',
                width: 120
            },
            {   displayName:'Job Types', 
                cellTooltip: true, 
                name: 'jobtype', 
                enableFiltering: false, 
                width: 100 
            },
            {   displayName:'Categories', 
                cellTooltip: true, 
                name: 'budgetcategory', 
                enableFiltering: false, 
                width: 100 
            },
            { displayName:'Items', 
                cellTooltip: true, 
                name: 'budgetitem', 
                enableFiltering: false, 
                width: 100 
            },
            { displayName:'Trades', 
                cellTooltip: true, 
                name: 'trade', 
                enableFiltering: false, 
                width: 100 
            },
            { displayName:'Works', 
                cellTooltip: true, 
                name: 'works', 
                enableFiltering: false, 
                width: 100 
            },
            { 
                displayName:'Sub Works', 
                cellTooltip: true, 
                name: 'subworks', 
                enableFiltering: false, 
                width: 100 
            },
            { displayName:'Type', 
                cellTooltip: true, 
                name: 'accountt', 
                enableFiltering: false, 
                width: 100 
            },
             
            { displayName:'GL Code', 
                cellTooltip: true, 
                name: 'accountcode', 
                enableFiltering: false, 
                width: 100 
            },
            { displayName:'Account Description', 
                cellTooltip: true, 
                name: 'accountname', 
                enableFiltering: false, 
                width: 180 
            },
            { 
                displayName:'Auto-Select',
                name: 'isautoselect', 
                width:100,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Auto-Select</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.isautoselect == 0 "><input type="checkbox" value="{{row.entity.id}}"  class="chk_isautoselect" '+$scope.edit_opt+'/></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.isautoselect == 1"><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="chk_isautoselect" '+$scope.edit_opt+'/></div>'
            },
            { 
                displayName:'Active',
                name: 'isactive', 
                width:80,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.isactive == 0 "><input type="checkbox" value="{{row.entity.id}}"  class="chk_isactive" '+$scope.edit_opt+'/></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.isactive == 1"><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="chk_isactive" '+$scope.edit_opt+'/></div>'
            },
            { 
                displayName:'Action',
                name: 'id',
                cellTooltip: true,
                enableFiltering: false,  
                visible :$('#edit_glcode').val()==='1'?true:false, 
                width: 60,  
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Action</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.id}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editGlCode(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
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
           
           window.open(base_url+'customers/exportglcodes?'+$.param($scope.filterOptions));
        };
        $scope.exportImportTemplate = function(){
           
           window.open(base_url+'customers/downloadglcodetemplate');
        };
        
        $scope.changeText = function() {
            getPage();
        }; 
        
        $scope.changeFilters = function() {
           getPage();
        };
        
        $scope.clearFilters = function() {
            var showasset = $scope.filterOptions.showasset;
            $scope.filterOptions = {
                filtertext: '',
                accounttype:'',
                labelid: '',
                state: [],
                jobtypeid:'',
                budget_categoryid:'',
                budget_itemid:'',
                se_tradeid:'',
                se_worksid:'',
                se_subworksid:'',
                asset_categoryid:'',
                showasset:showasset
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
            $http.get(base_url+'customers/loadglcodes?'+ qstring, {
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
       

        $('#assets').on('ifChecked', function () {
            $.each($scope.gridOptions.columnDefs, function(key, value){
                 if($scope.gridOptions.columnDefs[key].name ==='asset' || $scope.gridOptions.columnDefs[key].name ==='assetcategory'){
                     $scope.gridOptions.columnDefs[key].visible = true;
                 }
                 
            });
            getPage();
            $scope.filterOptions.showasset = true;
            $('.showasset').show();
        });

        // For onUncheck callback
        $('#assets').on('ifUnchecked', function () {
         
            $.each($scope.gridOptions.columnDefs, function(key, value){
                 
                if($scope.gridOptions.columnDefs[key].name ==='asset' || $scope.gridOptions.columnDefs[key].name ==='assetcategory'){
                      
                    $scope.gridOptions.columnDefs[key].visible = false;
                }
            }); 
            getPage();
            $scope.filterOptions.showasset = false;
            $('.showasset').hide();
        });
        
        
        $(document).on('change', '.chk_isautoselect', function(event) {
            var id = $(this).val();
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateGLCodes(id, 'isautoselect', value);


        });

        $(document).on('change', '.chk_isactive', function(event) {
            var id = $(this).val();
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateGLCodes(id, 'isactive', value);

        }); 
    
        var updateGLCodes = function(id, field, value) {
 
            var params = { 
                id  : id,
                field: field,
                value: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'customers/updateglcodes', qstring, {
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
        
        $scope.editGlCode = function(index, id) {
         
            var option = '<option value="0">All Works</option>';
            $("#glcodeform #se_worksid").html(option); 
            option = '<option value="0">All Sub-Works</option>';
            $("#glcodeform #se_subworksid").html(option); 
            $("#glcodeModal #loading-img").show();
            $("#glcodeModal #sitegriddiv").hide();
            $('#glcodeform').trigger("reset");
            $("#glcodeform .alert-danger").hide(); 
            $("#glcodeform span.help-block").remove();
            $("#glcodeform .has-error").removeClass("has-error");
            $('#glcodeform #btnsave').button("reset");
            $('#glcodeform #btncancel').button("reset");
            $('#glcodeform #btnsave').removeAttr("disabled");
            $('#glcodeform #btncancel').removeAttr("disabled");
            $("#glcodeform .close").css('display', 'block');
            $("#glcodeModal h4.modal-title").html('Edit GL-Code ' + index.accountcode + ' ' + index.accountname);
            $("#glcodeModal").modal();

            $("#glcodeform #glcodeid").val(id); 
            $("#glcodeform #mode").val('edit');  
             

            setTimeout(function(){ 
                $("#glcodeModal #loading-img").hide();
                $("#glcodeModal #sitegriddiv").show();

            }, 1000);
            
            
            $("#glcodeform #accounttype").val(index.accounttype); 
            $("#glcodeform #accountcode").val(index.accountcode); 
            $("#glcodeform #accountname").val(index.accountname); 
            $("#glcodeform #labelid").val(index.labelid); 
            if(index.labelid !==0){
                 $("#glcodeform #address").val(index.address); 
            }
            $("#glcodeform #asset_categoryid").val(index.asset_categoryid); 
           $("#glcodeform #assetid").val(index.assetid); 
            if(index.labelid !==0){
                 $("#glcodeform #asset").val(index.asset); 
            }
            $("#glcodeform #jobtypeid").val(index.jobtypeid); 
            $("#glcodeform #budget_categoryid").val(index.budget_categoryid); 
            $("#glcodeform #budget_itemid").val(index.budget_itemid); 
            $("#glcodeform #se_tradeid").val(index.se_tradeid); 
           
            if(index.se_worksid !==0){
                var option = '<option value="'+index.se_worksid+'">'+index.works+'</option>';
                $("#glcodeform #se_worksid").append(option); 
            }
             if(index.se_subworksid !==0){
                var option = '<option value="'+index.se_subworksid+'">'+index.subworks+'</option>';
                $("#glcodeform #se_subworksid").append(option);
                 
            }
            
            $("#glcodeform #se_worksid").val(index.se_worksid); 
            $("#glcodeform #se_subworksid").val(index.se_subworksid); 
            

        };
        $scope.importGlCode = function() { 
            var option = '<option value="0">All Works</option>';
            $("#importglcodeform #se_worksid").html(option); 
            option = '<option value="0">All Sub-Works</option>';
            $("#importglcodeform #se_subworksid").html(option); 
            $("#importglcodeModal #loading-img").show();
            $("#importglcodeModal #sitegriddiv").hide();
            $('#importglcodeform').trigger("reset");
            $("#importglcodeform .alert-danger").hide(); 
            $("#importglcodeform span.help-block").remove();
            $("#importglcodeform .has-error").removeClass("has-error");
            $('#importglcodeform #btnsave').button("reset");
            $('#importglcodeform #btncancel').button("reset");
            $('#importglcodeform #btnsave').removeAttr("disabled");
            $('#importglcodeform #btncancel').removeAttr("disabled");
            $("#importglcodeModal .close").css('display', 'block');
             $('#status').empty();
            var percentVal = '0';
            $('.progress-bar').attr('aria-valuenow',percentVal);
            $('.progress-bar').css('width',percentVal+"%");
            $('.sr-only').html(percentVal + "% Complete ");
            $("#importglcodeModal").modal();
             setTimeout(function(){ 
                $("#importglcodeModal #loading-img").hide();
                $("#importglcodeModal #sitegriddiv").show();

            }, 1000);
        };
        
        
        
        
        $scope.addGlCode = function() { 
        
            var option = '<option value="0">All Works</option>';
            $("#glcodeform #se_worksid").html(option); 
            option = '<option value="0">All Sub-Works</option>';
            $("#glcodeform #se_subworksid").html(option); 
            $("#glcodeModal #loading-img").show();
            $("#glcodeModal #sitegriddiv").hide();
            $('#glcodeform').trigger("reset");
            $("#glcodeform .alert-danger").hide(); 
            $("#glcodeform span.help-block").remove();
            $("#glcodeform .has-error").removeClass("has-error");
            $('#glcodeform #btnsave').button("reset");
            $('#glcodeform #btncancel').button("reset");
            $('#glcodeform #btnsave').removeAttr("disabled");
            $('#glcodeform #btncancel').removeAttr("disabled");
            $("#glcodeform .close").css('display', 'block');
            $("#glcodeModal h4.modal-title").html('Add GL-Code');
            $("#glcodeModal").modal();

            $("#glcodeform #glcodeid").val(''); 
            $("#glcodeform #mode").val('add');  

            setTimeout(function(){ 
                $("#glcodeModal #loading-img").hide();
                $("#glcodeModal #sitegriddiv").show();

            }, 1000);

        };

        $(document).on('click', '#glcodeModal #btnsave', function() {

            var accounttype = $("#glcodeform #accounttype");
            var accountcode = $("#glcodeform #accountcode");
            var accountname = $("#glcodeform #accountname");
            $("#glcodeform span.help-block").remove();

            if($.trim(accounttype.val()) === "") {
                $(accounttype).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(accounttype.parent());
            } else {
                $(accounttype).parent().removeClass("has-error");
            }
            if($.trim(accountcode.val()) === "") {
                $(accountcode).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(accountcode.parent());
            } else {
                $(accountcode).parent().removeClass("has-error");
            }

            if($.trim(accountname.val()) === "") {
                $(accountname).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(accountname.parent());
            } else {
                $(accountname).parent().removeClass("has-error");
            }

            if($.trim(accounttype.val()) === "" || $.trim(accountcode.val()) === "" || $.trim(accountname.val()) === ""){
                 return false;
            }

            $("#glcodeform #btnsave").button('loading'); 
            $("#glcodeform #btncancel").button('loading'); 
            $.post( base_url+"customers/addeditglcode", $("#glcodeform").serialize(), function( data ) {
                $('#glcodeform #btnsave').removeAttr("disabled");
                $('#glcodeform #btncancel').removeAttr("disabled");
                
                $('#glcodeform #btnsave').removeClass("disabled");
                $('#glcodeform #btncancel').removeClass("disabled");
                $('#glcodeform #btnsave').html("Save");
                $('#glcodeform #btncancel').html("Cancel");
                if(data.success) {

                    $( "#GlCodesCtrl .btn-refresh" ).click(); 
                    $("#glcodeModal").modal('hide');
                    $('#myglcodestatus').html('<div class="alert alert-success" >Gl-Code update successfully.</div>');
                    clearMsgPanel();


                }
                else{
                     $('#glcodeModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');

                }
              }, 'json');
        });

        $(document).on('click', '#glcodeModal #btncancel', function() {
            $("#glcodeModal").modal('hide');
        });
        
        $(document).on('click', '#importglcodeModal #btncancel', function() {
            $("#importglcodeModal").modal('hide');
        });
        
         var deferred;  
     
        $scope.changeSiteAddressText= function() {
            var text = $scope.address11;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
               $("#glcodeform #labelid").val(0); 
            } 
        };
        
        $scope.changeAssetText= function() {
            var text = $scope.asset11;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
               $("#glcodeform #assetid").val(0); 
            } 
        };
     
     
        //Any function returning a promise object can be used to load values asynchronously
        $scope.getCustomerSite = function(val) {

            deferred = $q.defer(); 
            $http.get(base_url+'ajax/loadsitesearch', {
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
       
        $scope.onCustomerSiteSelect = function ($item, $model, $label) {
            $("#glcodeform #address").val($item.site); 
            $("#glcodeform #labelid").val($item.labelid); 
        };
        
        //Any function returning a promise object can be used to load values asynchronously
        $scope.getAssets = function(val) {
            
            if($("#glcodeform #asset_categoryid").val() == '') {

                bootbox.alert('Please select Asset Category.');
                return false;
            }
            deferred = $q.defer(); 
            $http.get(base_url+'ajax/loadassetsearch', {
                params: {
                    search: val,
                    asset_categoryid : $("#glcodeform #asset_categoryid").val()
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
       
        $scope.onAssetSelect = function ($item, $model, $label) {
            $("#glcodeform #asset").val($item.asset); 
            $("#glcodeform #assetid").val($item.assetid); 
        };
        
        
    }
]);
 
app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$(document).ready(function() {
   
    $(document).on('change','#glcodeform #se_tradeid', function() {
        var value = $(this).val();
        var option;
         option = '<option value="0">All Works</option>';
            $("#glcodeform #se_worksid").html(option); 
            option = '<option value="0">All Sub-Works</option>';
            $("#glcodeform #se_subworksid").html(option); 
        if(value === '0') {
           
            return false;
        }
            var contactid = $("#contact_trade_form #contactid").val();
            $.get( base_url+"ajax/loadtradeworks", { get:1,contactid:contactid,  tradeid:value }, function( data ) {
                option = '<option value="0">All Works</option>';
               
                if (data.success === false) {
                    bootbox.alert(data.message);
                     
                }else{
                    var datas = data.data;
                    $.each( datas, function( key, value ) {
                        option = option + '<option value="'+ value.id +'">'+ value.se_works_name +'</option>';
                    }); 
                } 
                
                $("#glcodeform #se_worksid").html(option); 
               
                
            }, 'json');
            
        });
        $(document).on('change','#glcodeform #se_worksid', function() {
            var value = $(this).val();
            var option;
            if(value === '0') {
                   option = '<option value="0">All Sub-Works</option>';
                   $("#glcodeform #se_subworksid").html(option); 
                   return false;
            } 
            var tradeid = $("#glcodeform #se_tradeid").val();
            $.get( base_url+"ajax/loadsubworks", {tradeid:tradeid,workid:value }, function( data ) {
                     
                option = '<option value="0">All Sub-Works</option>';
                if (data.success === false) {
                    bootbox.alert(data.message);

                }else{
                    var datas = data.data;
                    $.each( datas, function( key, value ) {
                        option = option + '<option value="'+ value.id +'">'+ value.se_subworks_name +'</option>';
                    });  
                } 
                
                $("#glcodeform #se_subworksid").html(option); 
                 
            }, 'json');
            
        });
        
        $("#importglcodeModal #btnsave").on('click', function() {
         
            var fileup = $("#importglcodeform #importfile"); 
            var accounttype = $("#importglcodeform #accounttype"); 
            $("#importglcodeform span.help-block").remove();

            if($.trim(accounttype.val()) === "") {
                $(accounttype).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(accounttype.parent());
                  return false;
            } else {
                $(accounttype).parent().removeClass("has-error");
            }
            
          

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
       
        $('#importglcodeform').ajaxForm({
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
                            $("#importglcodeModal").modal('hide');
                            $( "#GlCodesCtrl .btn-refresh" ).click();
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
            $("#myglcodestatus").html('');
           
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