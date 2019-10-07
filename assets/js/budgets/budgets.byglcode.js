/* global parseFloat, base_url, angular, bootbox */
"use strict";
if($("#BudgetCtrl").length) {
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('BudgetCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
        $scope.filterOptions = {
            year: $('#currentyear').val(),
            state: '',
            contactid: '',
            band: '',
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
         enableColumnMenus: false,
         enableFiltering: false,
         showColumnFooter: true,
         columnDefs: [
              
            { 
                displayName:'GL Code',
                cellTooltip: true,
                name: 'accountcode',
                width: 100
            },
            { 
                displayName:'Description',
                cellTooltip: true,
                name: 'accountname',
                enableSorting: true,
                width: 200
            },
            { 
                displayName:'Budget',
                cellTooltip: true,
                name: 'annualbudget',
                enableSorting: true,
                width: 100,
                cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalannualbudget = 0;
                    $scope.gridOptions.data.forEach(function(rowEntity) {
                        totalannualbudget =totalannualbudget +  intVal(rowEntity.annualbudget);
                    });
                    return '$ '+ parseFloat(totalannualbudget).toFixed(2);
                    
                     
                } 
            },
            { 
                displayName:'Actual Spend',
                cellTooltip: true,
                name: 'actual',
                enableSorting: true,
                width: 120,
                 cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalactual = 0;
                    $scope.gridOptions.data.forEach(function(rowEntity) {
                        totalactual =totalactual +  intVal(rowEntity.actual);
                    });
                    return '$ '+ parseFloat(totalactual).toFixed(2);
                   
                }
            },
            { 
                displayName:'Remaining',
                cellTooltip: true,
                name: 'remaining',
                enableSorting: true,
                width: 100,
                 cellClass: 'text-right', 
                headerCellClass : 'text-right',
                footerCellClass : 'text-right', 
                aggregationHideLabel: true,
                aggregationType: function() {
                    var totalremaining = 0;
                    $scope.gridOptions.data.forEach(function(rowEntity) {
                        totalremaining =totalremaining +  intVal(rowEntity.remaining);
                    });
                    return '$ '+ parseFloat(totalremaining).toFixed(2);
                     
                }
                
            },
            { 
                displayName:'%Spent',
                cellTooltip: true,
                name: 'pctspend',
                enableSorting: true,
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents text-right {{row.entity.textcolor}}" title="{{row.entity.pctspend}}">{{row.entity.pctspend}}%</div>',
                headerCellTemplate: '<div class="ng-scope sortable" ng-class="{\'sortable\': sortable }"><div col-index="renderIndex" class="ui-grid-cell-contents text-right"><span ng-class="{\'ui-grid-icon-up-dir\': col.sort.direction == asc, \'ui-grid-icon-down-dir\': col.sort.direction == desc, \'ui-grid-icon-blank\': !col.sort.direction }" ui-grid-visible="col.sort.direction" class="ui-grid-invisible ui-grid-icon-blank">&nbsp;</span><span class="ng-binding">%Spent</span> </div></div>'
            },
            { 
                displayName:'Detail',
                cellTooltip: true,
                name: 'glcodeid',
                enableSorting: false,
                width: 70,
                cellTemplate: '<div class="ui-grid-cell-contents" title="Detail" ng-if="!row.entity.detail" ></div><div class="ui-grid-cell-contents" title="Documents" ng-if="row.entity.detail" ><button type="button" data-id="{{row.entity.glcodeid}}"  data-site="{{row.entity.glcode}}" class="btn btn-flat btn-default btn-xs btn_budget_detail"   >Detail</button></div>'
            },
            { 
                displayName:'Last updated',
                cellTooltip: true,
                name: 'lastupdated',
                width: 130
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
                year: $('#currentyear').val(),
                state: '',
                contactid: '',
                band: '',
                filterText: ''
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
        $scope.exportToExcel=function(){
            
            window.open(base_url+'budgets/downloadexcelbyglcode?'+$.param($scope.filterOptions));

        };
        $scope.exportImportTemplate=function(){
            var url = base_url + 'budgets/downloadimportfilebyglcode/'+$scope.filterOptions.year.replace('-', '/');
            window.open(url);

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
  
            $('#BudgetCtrl .overlay').show();
             $http.get(base_url+'budgets/loadbudgetsbyglcode?'+ qstring ).success(function (data) {
                    if (data.success === false) {
                        bootbox.alert(data.message);
                         
                    }else{
                        $scope.gridOptions.totalItems = data.total;
                        $scope.gridOptions.data = data.data;
                        var total = 0;
                        
                        $scope.gridOptions.data.forEach(function(rowEntity) {
                            total = total + intVal(rowEntity.annualbudget);
                        });
                        $scope.totalbudget = '$ '+ parseFloat(total).toFixed(2);
                    }
                 
                   $('#BudgetCtrl .overlay').hide();
             });
       };

       getPage();
     }
     ]);
}
 
$( document ).ready(function() {
        
    
        
    $(document).on('click', '#btn_createbudget', function() {

        $("#createbudget").modal();
        $("#createbudget #fy").html($('#budgetfilter #year option:selected').text());
        $("#createbudget #selyear").val($('#budgetfilter #year').val());

        $('#createbudget #loading-img').show();
        $('#createbudget #sitegriddiv').hide();
        $("#createbudget #siteid").val('');
        $("#createbudget #annualbudget").val('');
        $("#createbudget #budgetbtnsave").button('reset');
        $('#createbudget #budgetbtnsave').attr("disabled", "disabled");
        $("#createbudget #siteid").select2();
        $('#createbudget #loading-img').hide();
        $('#createbudget #sitegriddiv').show(); 
        $('#createbudget #budgetbtnsave').removeAttr("disabled");


    });
    $(document).on('click', '#createbudget #budgetbtncancel', function() {

        $("#createbudget #budgetbtnsave").button('reset');
        $("#createbudget").modal('hide');
    });
         
        
    $(document).on('click', '#btn_import', function() {
            
        $("#importbudgetexcel").modal();
        $("#importbudgetexcel #fy").html($('#budgetfilter #year option:selected').text());
        $("#importbudgetexcel #ifyear").val($('#budgetfilter #year').val());
        $("#importbudgetexcel #errormsg").hide();
        $("#importbudgetexcel #errormsg").html('');
        $('#importbudgetexcel #loading-img').show();
        $('#importbudgetexcel #sitegriddiv').hide();

        $("#importbudgetexcel #importfile").val('');
        $("#importbudgetexcel #filedata").val('');
        $("#importbudgetexcel #fileformat").val('');
         $("#importbudget_form span.help-block").remove();
        $('#status').empty();
            var percentVal = '0';
            $('.progress-bar').attr('aria-valuenow',percentVal);
            $('.progress-bar').css('width',percentVal+"%");
            $('.sr-only').html(percentVal + "% Complete ");
        $("#importbudgetexcel #budgetbtnsave").button('reset');
        setTimeout(function(){ 
            $('#importbudgetexcel #loading-img').hide();
            $('#importbudgetexcel #sitegriddiv').show(); 
        }, 1000); 


    });
       
    $(document).on('click', '#importbudgetexcel #budgetbtncancel', function() {
        $("#importbudgetexcel #errormsg").hide();
        $("#importbudgetexcel #errormsg").html('');
        $("#importbudgetexcel #budgetbtnsave").button('reset');
        $("#importbudgetexcel").modal('hide');
    });
        
        
        
    $(document).on('click', '.btn_budget_detail', function() {
            
        $("#budgetdetail").modal();
        $('#budgetdetail #loading-img').show();
        $('#budgetdetail #sitegriddiv1').hide();
        $("#budgetdetail #budgetbtnsave").button('reset');
        $('#budgetdetail #budgetbtnsave').attr("disabled", "disabled");


        var recordid= $(this).attr('data-id');
        var year= $('#budgetfilter #year').val();
        var site= $(this).attr('data-site');
        $('#budgetdetail #recordid').val(recordid);
        $('#budgetdetail #year').val(year);
        $("#budgetdetail .sitename").html(site);

        $.get( base_url + 'budgets/loadbudgetdetailbyglcode/'+recordid+'/'+year, { 'get':1}, function( data ) {

            if (data.success) {
                var $result = '';
                $.each( data.data, function( key, val ) {
                   
                    $result = $result+'<tr><td>'+val.month+'</td>';
                    $result = $result+'<td><input type="text" style="width:120px;" class="allownumericwithdecimal" id="amount_'+ val.id +'" name="amount['+val.id+']" class="form-control1" value="'+ val.amount+'" /></td></tr>';
                    
                });
                
                $("#budgetdetail #tblbudgetdetailbody").html($result);
                $('#budgetdetail #loading-img').hide();
                $('#budgetdetail #sitegriddiv1').show(); 
                $('#budgetdetail #budgetbtnsave').removeAttr("disabled");   
               
            }else{
                bootbox.alert(data.message);
                $("#budgetdetail").modal('hide');
                return false;
                
            }  
        
        },'json');
	 	
           
    });
        
    $(document).on('click', '#budgetdetail #budgetbtncancel', function() {
          $("#budgetdetail #budgetbtnsave").button('reset');
          $("#budgetdetail #sitegriddiv").html('');
          $("#budgetdetail").modal('hide');
    });
        
    $(document).on('click', '#budgetdetail #budgetbtnsave', function() {

        bootbox.confirm("Save changes to budget for <b>"+ $("#budgetdetail .sitename").html()+ " ?</b>", function(result) {
            if(result) {
                $("#budgetdetail #budgetbtnsave").button('loading');
                $.post( base_url+"/budgets/updatebudget/q", $("#budgetdetail_form").serialize(), function( response ) {
                    $("#budgetdetail #budgetbtnsave").button('reset');

                    if(response.success) {
                        $("#budgetdetail").modal('hide');
                        $( "#BudgetCtrl .btn-refresh" ).click();
                   
                    }
                    else{
                        bootbox.alert(response.message);
                    }
                }, 'json');
            }
        });

        return false;
    });
        
        
    if (typeof $.fn.validate === "function") {
            $.validator.addMethod("regex", function(value, element, regexpr) {
                     if(value===""){
                             return true;
                     }          
                  return regexpr.test(value);
             }, "Please enter a valid requested formet."); 

             $.validator.addMethod("fileval", function(value, element, regexpr) {
                          if(value===""){
                                  return true;
                          }          
                       return regexpr.test(value);
             }, "Please select image/pdf file formet."); 
             
            $.validator.addMethod("validatefile", function(value) {
		 
		if($.trim(value) === '') {
			return false;
		}
		else {
			return true;
		}

            }, 'This field is required.');

              $.validator.setDefaults({
                  ignore: ':not(select:visible, input:visible, textarea:visible)'
              });
      
    
             

             $("#createbudget_form").validate({
                errorElement: 'span',
		errorClass: 'help-block',
		focusInvalid: false,
		rules: {
			siteid: {  
                                required:  {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            },
                        annualbudget: {
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
			 
                         $(e).parent().parent().removeClass('has-info').addClass('has-error');
                    }
                    else{
                       $(e).parent().removeClass('has-info').addClass('has-error');
                    } 
                           
                    
		},
               
		success: function (e) {
                   if($(e).parent().is('.input-group')) {
			$(e).parent().parent().removeClass("has-error");
                    }
                    else{
                        $(e).parent().removeClass("has-error");
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
                        else if(element.is('.selectpicker')) {
				error.appendTo(element.siblings('[class*="bootstrap-select"]:eq(0)'));
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
			  
                        $("#createbudget #budgetbtnsave").button('loading');
			$.post( base_url+"budgets/createannualbudgetbyglcode/q", $("#createbudget_form").serialize(), function( data ) {
                            $("#createbudget #budgetbtnsave").button('reset');
				if(data.success) {
                                    if(data.data.success) {
                                        $("#createbudget").modal('hide');
                                        $( "#BudgetCtrl .btn-refresh" ).click();
                                    }
                                    else{
                                     
                                        var rid = data.data.recordid;
                                        if($('#siteid').val()===0){
                                            bootbox.dialog({
                                                    message: "<span class='bigger-110'>Monthly budgets already exist for sites. Do you want to split $"+ $('#annualbudget').val() +" evenly between each month ?</span>",
                                                    buttons:
                                                    {
                                                            "success" :
                                                             {
                                                                    "label" : "Update All Site Budgets",
                                                                    "className" : "btn-sm btn-success",
                                                                    "callback": function() {
                                                                        $.post( base_url+"budgets/updateannualbudgetbyglcode/q", $("#createbudget_form").serialize(), function( data2 ) {
                                                                            if(data2.success) {
                                                                                $("#createbudget").modal('hide');
                                                                                $( "#BudgetCtrl .btn-refresh" ).click();
                                                                            }
                                                                            else{
                                                                                bootbox.alert(data.message);
                                                                            }
                                                                        }, 'json');
                                                                    }
                                                            },
                                                            "click" :
                                                            {
                                                                    "label" : "Add Pending Site Budgets",
                                                                    "className" : "btn-sm btn-primary",
                                                                    "callback": function() {
                                                                         $.post( base_url+"budgets/addannualbudget/q", $("#createbudget_form").serialize(), function( data2 ) {
                                                                            if(data2.success) {
                                                                                $("#createbudget").modal('hide');
                                                                                $( "#BudgetCtrl .btn-refresh" ).click();
                                                                            } 
                                                                            else{
                                                                                bootbox.alert(data.message);
                                                                            }
                                                                        }, 'json');
                                                                    }
                                                            }, 
                                                            "button" :
                                                            {
                                                                    "label" : "Cancel",
                                                                    "className" : "btn-sm"
                                                            }
                                                    }
                                            }); 
                                        }
                                        else{
                                            bootbox.dialog({
                                                    message: "<span class='bigger-110'>Monthly budgets already exist for this site. Do you want to split $"+ $('#annualbudget').val() +" evenly between each month ?</span>",
                                                    buttons:
                                                    {
                                                        "click" :
                                                        {
                                                                "label" : "Update Budget Amount",
                                                                "className" : "btn-sm btn-primary",
                                                                "callback": function() {
                                                                     $.post( base_url+"budgets/updateannualbudgetbyglcode/q", $("#createbudget_form").serialize(), function( data2 ) {
                                                                            if(data2.success) {
                                                                                $("#createbudget").modal('hide');
                                                                                $( "#BudgetCtrl .btn-refresh" ).click();
                                                                            }
                                                                            else{
                                                                                bootbox.alert(data.message);
                                                                            }
                                                                        }, 'json');
                                                                }
                                                        }, 
                                                        "button" :
                                                        {
                                                                "label" : "Cancel",
                                                                "className" : "btn-sm",
                                                                "callback": function() {
                                                                    $("#createbudget").modal('hide'); 
                                                                    $("#budgetdetail").modal();
                                                                    $('#budgetdetail #loading-img').show();
                                                                    $('#budgetdetail #sitegriddiv').hide();
                                                                    $('#budgetdetail #budgetbtnsave').attr("disabled", "disabled");
                                                                    var record_id= rid;
                                                                    var year= $('#selyear').val();
                                                                    var site= $('#siteid option:selected').text();
                                                                    $("#budgetdetail .sitename").html(site);
                                                                    $.get( base_url + 'budgets/budgetdetail/'+record_id+'/'+year, { 'get':1}, function( data ) {

                                                                        $("#budgetdetail #sitegriddiv").html(data);
                                                                        $('#budgetdetail #loading-img').hide();
                                                                        $('#budgetdetail #sitegriddiv').show(); 
                                                                        $('#budgetdetail #budgetbtnsave').removeAttr("disabled");
                                                                    },'html');
                                                                }
                                                        }
                                                    }
                                            });
                                        }
                                       
                                    }
                                }
                                else{
                                    bootbox.alert(data.message);
                                    $("#createbudget").modal('hide');
                                   
                                }
					
					
					
			}, 'json');
                        
	    	return false;  
				 
					
				 
		}
	}); 
    }
    
    $("#importbudgetexcel #budgetbtnsave").on('click', function() {
         
        var fileup = $("#importbudget_form #importfile"); 
        $("#importbudget_form span.help-block").remove();

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
        $('#importbudget_form').ajaxForm({
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
                            $("#importbudgetexcel").modal('hide');
                            $( "#BudgetCtrl .btn-refresh" ).click();
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

 
 var readExcelURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
     
    if($.inArray(ext, ['xls','xlsx']) === -1) {
        $(input).val('');
        
        bootbox.alert('invalid file format!');
        return false;
    }
     return true;
};