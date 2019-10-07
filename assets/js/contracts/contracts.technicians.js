/* global base_url, angular, app */

"use strict";
app.controller('ContractTechniciansCtrl', [
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
  
    $scope.technicianGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [ 
            { 
                displayName:'User',
                cellTooltip: true,
                name: 'userid',
                width: 100
            },
             
            { 
                displayName:'Contact',
                cellTooltip: true,
                name: 'contact',
                width: 120
            },
            { 
                displayName:'Normal Rate',
                cellTooltip: true,
                name: 'normal_rate',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right currency', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right' 
            },
            { 
                displayName:'Week A/H',
                cellTooltip: true,
                name: 'weekah_rate',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right currency', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right' 
            },
            { 
                displayName:'Saturday',
                cellTooltip: true,
                name: 'saturday_rate',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right currency', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right' 
            },
            { 
                displayName:'Sunday',
                cellTooltip: true,
                name: 'sunday_rate',
                enableSorting: true,
                width: 90,
                cellClass: 'text-right currency', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right' 
            },
            { 
                displayName:'Public Holiday',
                cellTooltip: true,
                name: 'pubhol_rate',
                enableSorting: true,
                width: 120,
                cellClass: 'text-right currency', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right' 
            },
            { 
                displayName:'Start',
                cellTooltip: true,
                name: 'startdate',
                width: 100
            },
            {   displayName:'End', 
                cellTooltip: true, 
                name: 'enddate',  
                width: 100 
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
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.userid}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editTechnician(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
            }, 
//            { 
//                displayName:'Delete',
//                name: 'delete',
//                cellTooltip: true,
//                enableFiltering: false, 
//               
//                width: 60,
//                enableSorting: false,
//                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
//                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteTechnician(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
//            }
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
                technicianPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               technicianPage();
             });
 	
         }
        };
       
         $scope.changeText = function() {
            technicianPage();
        }; 
       $scope.refreshGrid = function() {
            technicianPage();
        };
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext : ''
            };
 
            technicianPage();
        };
    
        $scope.exportToExcel = function(){
            var params = { 
                contractid : $("#contractdetailform #contractid").val() 
            }; 
        
            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
            window.open(base_url+'contracts/exportcontracttechnicians?'+qstring);
        };
       
        var technicianPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort,
                contractid : $("#contractdetailform #contractid").val() 
            }; 
        
            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
        
            $scope.overlay = true;
            $http.get(base_url+'contracts/loadcontracttechnicians?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.technicianGrid.totalItems = response.total;
                    $scope.technicianGrid.data = response.data;  
                }

            });
        };

       // technicianPage();
       
        $scope.deleteTechnician = function(entity) {

            bootbox.confirm("Are you sure to delete Technician <b>"+entity.userid+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"contracts/deletecontracttechnician", { id:entity.id, contractid : entity.contractid  }, function( response ) {
                        if (response.success) {
                            technicianPage();
                            bootbox.alert(response.message);
                            
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editTechnician = function(index, id) {
          
            $('#contractTechnicianForm').trigger("reset");
            $("#contractTechnicianForm .alert-danger").hide(); 
            $("#contractTechnicianForm span.help-block").remove();
            $("#contractTechnicianForm .has-error").removeClass("has-error");
            $('#contractTechnicianForm #btnsave').button("reset");
            $('#contractTechnicianForm #btncancel').button("reset");
            $('#contractTechnicianForm #btnsave').removeAttr("disabled");
            $('#contractTechnicianForm #btncancel').removeAttr("disabled");
            $("#contractTechnicianForm .close").css('display', 'block');
            $("#contractTechnicianModal h4.modal-title").html('Edit Technician - ' + index.userid);
           
            $("#contractTechnicianForm #contechnicianid").val(id); 
            $("#contractTechnicianForm #mode").val('edit');  
            
           
            
            $("#contractTechnicianForm #contactModalErrorMsg").hide(); 
            $("#contractTechnicianForm #contactModalSuccessMsg").hide();
            $("#contractTechnicianForm #contactModalErrorMsg").html(''); 
            $("#contractTechnicianForm #contactModalSuccessMsg").html('');
            
            $("#contractTechnicianForm #contractid").val($("#contractdetailform #contractid").val()); 
            $("#contractTechnicianForm #userid").val(index.userid);
            $("#contractTechnicianForm #normal_rate").val(index.normal_rate); 
            $("#contractTechnicianForm #weekah_rate").val(index.weekah_rate); 
            $("#contractTechnicianForm #saturday_rate").val(index.saturday_rate); 
            $("#contractTechnicianForm #sunday_rate").val(index.sunday_rate); 
            $("#contractTechnicianForm #pubhol_rate").val(index.pubhol_rate); 
            $("#contractTechnicianForm #startdate").val(index.startdate); 
            $("#contractTechnicianForm #enddate").val(index.enddate); 
            $("#contractTechnicianForm #notes").html(index.notes);
            if(index.enddate !== ''){
                $('#contractTechnicianForm input[name="startdate"]').datepicker('setEndDate', index.enddate);
            }
            if(index.startdate !== ''){
                $('#contractTechnicianForm input[name="enddate"]').datepicker('setStartDate', index.startdate);
            }
             
            
            if(parseInt(index.isactive) === 1){
                $('#contractTechnicianForm input[name="isactive"]').prop('checked', true);
            }
            else{
                $('#contractTechnicianForm input[name="isactive"]').prop('checked', false);
            }   
             $("#contractTechnicianModal").modal();

        };
        
        
        $scope.addTechnician = function() { 
          
            $("#contractTechnicianForm #contactModalErrorMsg").hide(); 
            $("#contractTechnicianForm #contactModalSuccessMsg").hide();
            $("#contractTechnicianForm #contactModalErrorMsg").html(''); 
            $("#contractTechnicianForm #contactModalSuccessMsg").html('');
            $('#contractTechnicianForm').trigger("reset");
            $("#contractTechnicianForm .alert-danger").hide(); 
            $("#contractTechnicianForm span.help-block").remove();
            $("#contractTechnicianForm .has-error").removeClass("has-error");
            $('#contractTechnicianForm #btnsave').button("reset");
            $('#contractTechnicianForm #btncancel').button("reset");
            $('#contractTechnicianForm #btnsave').removeAttr("disabled");
            $('#contractTechnicianForm #btncancel').removeAttr("disabled");
            $("#contractTechnicianForm .close").css('display', 'block');
            $("#contractTechnicianModal h4.modal-title").html('Add Technician');
            
            $("#contractTechnicianForm #notes").html('');
            $("#contractTechnicianForm #contractid").val($("#contractdetailform #contractid").val() ); 
            $("#contractTechnicianForm #contechnicianid").val(''); 
            $("#contractTechnicianForm #mode").val('add');  
            $('#contractTechnicianForm input[name="startdate"]').datepicker('setEndDate', null);
            $('#contractTechnicianForm input[name="enddate"]').datepicker('setStartDate', null);
            $('#contractTechnicianForm input[name="active"]').prop('checked', true); 
            $("#contractTechnicianModal").modal();
        };
    }
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
 
$( document ).ready(function() {
    $("#contractTechnicianForm #startdate").on('changeDate', function(e) {
        $('#contractTechnicianForm input[name="enddate"]').datepicker('setStartDate', e.date);
    });
    
    $("#contractTechnicianForm #enddate").on('changeDate', function(e) {
        $('#contractTechnicianForm input[name="startdate"]').datepicker('setEndDate', e.date);
    });
    
        
    if (typeof $.fn.validate === "function") {         
       
        
        $("#contractTechnicianForm").validate({
            rules: {
                userid: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                normal_rate: {
                    number: true
                },
                weekah_rate: {
                    number: true
                },
                saturday_rate: {
                    number: true
                },
                sunday_rate: {
                    number: true
                },
                pubhol_rate: {
                    number: true
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
                $("span:eq(0)", "#contractTechnicianForm #modalsave").css("display", 'block');
                $("span:eq(1)", "#contractTechnicianForm #modalsave").css("display", 'none');
                $("#contractTechnicianForm #cancel").button('loading');
                
                $("#contractTechnicianForm #contactModalErrorMsg").hide(); 
                $("#contractTechnicianForm #contactModalSuccessMsg").hide();
                $.post( base_url+"contracts/savecontracttechnician", $('#contractTechnicianForm').serialize(), function( response ) {
                    $("span:eq(0)", "#contractTechnicianForm #modalsave").css("display", 'none');
                    $("span:eq(1)", "#contractTechnicianForm #modalsave").css("display", 'block');
                    $("#contractTechnicianForm #cancel").button('reset');
                    if (response.success) {
                        if (response.data.success) {
                            $("#contractTechnicianForm #contactModalSuccessMsg").html(response.message);
                            $("#contractTechnicianForm #contactModalSuccessMsg").show();
                             
                            $("#contractTechnicianModal").modal('hide');
                            $( "#ContractTechniciansCtrl .btn-refresh" ).click();
                            modaloverlap();
                        }
                        else{
                            $("#contractTechnicianForm #contactModalErrorMsg").html(response.data.message);
                            $("#contractTechnicianForm #contactModalErrorMsg").show();
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
    
});

