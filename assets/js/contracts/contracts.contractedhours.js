/* global base_url, angular, app */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch',    

app.controller('ContractedHoursCtrl', [
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
  
    $scope.ContractedHoursGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [ 
            { 
                displayName:'Name',
                cellTooltip: true,
                name: 'name',
                width: 100
            },
             
            { 
                displayName:'Sun',
                cellTooltip: true,
                name: 'sun_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.sun_from}} - {{row.entity.sun_to}}</div>'
            },
            { 
                displayName:'Mon',
                cellTooltip: true,
                name: 'mon_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.mon_from}} - {{row.entity.mon_to}}</div>'
            },
            { 
                displayName:'Tue',
                cellTooltip: true,
                name: 'thu_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.thu_from}} - {{row.entity.thu_to}}</div>'
            },
            { 
                displayName:'Web',
                cellTooltip: true,
                name: 'wed_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.wed_from}} - {{row.entity.wed_to}}</div>'
            },
            { 
                displayName:'Thu',
                cellTooltip: true,
                name: 'thu_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.thu_from}} - {{row.entity.thu_to}}</div>'
            },
            { 
                displayName:'Fri',
                cellTooltip: true,
                name: 'fri_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.fri_from}} - {{row.entity.fri_to}}</div>'
            },
            { 
                displayName:'Sat',
                cellTooltip: true,
                name: 'sat_from',
                width: 120,
                cellTemplate: '<div class="ui-grid-cell-contents text-center">{{row.entity.sat_from}} - {{row.entity.sat_to}}</div>'
            },
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'isactive', 
                width: 70,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.id}}"  data-id="{{row.entity.id}}"  class="chk_isactive"  ng-checked="row.entity.isactive == 1" /></div>'
            },
            { 
                displayName:'Action',
                field:'action',
                width: 50,  
                enableSorting: false,
                enableFiltering: false, 
                visible :$('#edit_contract').val()==='1'?true:false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.name}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editContractedHours(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
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
                ContractedHoursPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               ContractedHoursPage();
             });
 	
         }
        };
        
    
        
       
        var ContractedHoursPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                contractid : $("#contractdetailform #contractid").val() 
            }; 
        
            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
        
            $scope.overlay = true;
            $http.get(base_url+'contracts/loadcontractedhours?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.ContractedHoursGrid.totalItems = response.total;
                    $scope.ContractedHoursGrid.data = response.data;  
                }

            });
        };

       
        ContractedHoursPage();
        
        $scope.editContractedHours = function(index, id) {
          
            $('#contractHoursForm').trigger("reset");
            $("#contractHoursForm .alert-danger").hide(); 
            $("#contractHoursForm span.help-block").remove();
            $("#contractHoursForm .has-error").removeClass("has-error");
            $('#contractHoursForm #btnsave').button("reset");
            $('#contractHoursForm #btncancel').button("reset");
            $('#contractHoursForm #btnsave').removeAttr("disabled");
            $('#contractHoursForm #btncancel').removeAttr("disabled");
            $("#contractHoursForm .close").css('display', 'block');
            $("#contractHoursModal h4.modal-title").html('Edit - ' + index.name + ' Template');
           
            $("#contractHoursForm #contractedhoursid").val(id); 
            $("#contractHoursForm #mode").val('edit');  
             
            
            $("#contractHoursForm #contactModalErrorMsg").hide(); 
            $("#contractHoursForm #contactModalSuccessMsg").hide();
            $("#contractHoursForm #contactModalErrorMsg").html(''); 
            $("#contractHoursForm #contactModalSuccessMsg").html('');
             
            $("#contractHoursForm #name").val(index.name);
            $("#contractHoursForm #sun_from").val(index.sun_from); 
            $("#contractHoursForm #sun_to").val(index.sun_to);
            
            $("#contractHoursForm #mon_from").val(index.mon_from); 
            $("#contractHoursForm #mon_to").val(index.mon_to);
             $("#contractHoursForm #tue_from").val(index.tue_from); 
            $("#contractHoursForm #tue_to").val(index.tue_to);
            
            $("#contractHoursForm #wed_from").val(index.wed_from); 
            $("#contractHoursForm #wed_to").val(index.wed_to);
            
            $("#contractHoursForm #thu_from").val(index.thu_from); 
            $("#contractHoursForm #thu_to").val(index.thu_to);
            
            $("#contractHoursForm #fri_from").val(index.fri_from); 
            $("#contractHoursForm #fri_to").val(index.fri_to);
            
            $("#contractHoursForm #sat_from").val(index.sat_from); 
            $("#contractHoursForm #sat_to").val(index.sat_to);
            
            $("#contractHoursForm #sortorder").val(index.sortorder);
             
            
            if(parseInt(index.isactive) === 1){
                $('#contractHoursForm input[name="isactive"]').prop('checked', true);
            }
            else{
                $('#contractHoursForm input[name="isactive"]').prop('checked', false);
            }   
             $("#contractHoursModal").modal();
      $("span:eq(0)", "#contractHoursForm #modalsave").css("display", 'none');
                    $("span:eq(1)", "#contractHoursForm #modalsave").css("display", 'block');
        };
        
        
        $scope.addContractedHours = function() { 
          
            $("#contractHoursForm #contactModalErrorMsg").hide(); 
            $("#contractHoursForm #contactModalSuccessMsg").hide();
            $("#contractHoursForm #contactModalErrorMsg").html(''); 
            $("#contractHoursForm #contactModalSuccessMsg").html('');
            
            $('#contractHoursForm').trigger("reset");
            $("#contractHoursForm .alert-danger").hide(); 
            $("#contractHoursForm span.help-block").remove();
            $("#contractHoursForm .has-error").removeClass("has-error");
            $('#contractHoursForm #btnsave').button("reset");
            $('#contractHoursForm #btncancel').button("reset");
            $('#contractHoursForm #btnsave').removeAttr("disabled");
            $('#contractHoursForm #btncancel').removeAttr("disabled");
            $("#contractHoursForm .close").css('display', 'block');
            $("#contractHoursModal h4.modal-title").html('Add Contracted Hours Template');
                    $("span:eq(0)", "#contractHoursForm #modalsave").css("display", 'none');
                    $("span:eq(1)", "#contractHoursForm #modalsave").css("display", 'block');
            $("#contractHoursForm #sortorder").val(($scope.ContractedHoursGrid.totalItems+1));  
            $("#contractHoursForm #contractedhoursid").val(''); 
            $("#contractHoursForm #mode").val('add');   
            $('#contractHoursForm input[name="active"]').prop('checked', true); 
            $("#contractHoursModal").modal();
        };
        
          
        $("#contractHoursForm").validate({
            rules: {
                name: {  
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
                    $(e).parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().removeClass("has-error");
                }
            },
            submitHandler: function() {
                $("span:eq(0)", "#contractHoursForm #modalsave").css("display", 'block');
                $("span:eq(1)", "#contractHoursForm #modalsave").css("display", 'none');
                $("#contractHoursForm #cancel").button('loading');
                
                $("#contractHoursForm #contactModalErrorMsg").hide(); 
                $("#contractHoursForm #contactModalSuccessMsg").hide();
                $.post( base_url+"contracts/savecontractedhours", $('#contractHoursForm').serialize(), function( response ) {
                    $("span:eq(0)", "#contractHoursForm #modalsave").css("display", 'none');
                    $("span:eq(1)", "#contractHoursForm #modalsave").css("display", 'block');
                    $("#contractHoursForm #cancel").button('reset');
                    if (response.success) {
                        if (response.data.success) {
                            $("#contractHoursForm #contactModalSuccessMsg").html(response.message);
                            $("#contractHoursForm #contactModalSuccessMsg").show();
                             
                            $("#contractHoursModal").modal('hide');
                            ContractedHoursPage();
                            modaloverlap();
                        }
                        else{
                            $("#contractHoursForm #contactModalErrorMsg").html(response.data.message);
                            $("#contractHoursForm #contactModalErrorMsg").show();
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
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
 
$( document ).ready(function() {
    
    
        
    if (typeof $.fn.validate === "function") {         
       
      
    }
    
});

