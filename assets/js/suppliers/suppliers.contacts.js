/* global base_url, angular, app */

"use strict";
app.controller('ContactCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
    $scope.filterOptions = {
        filtertext : '',
        tradeid   : '',
        state  : '',
        supplierid : $('#supplierid').val()
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    $scope.edit_opt = $('#edit_contact').val()==='1'?'':'disabled="disabled"';
    $scope.contactGrids = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [ 
            { 
                displayName:'Action',
                field:'action',
                width: 60,
                visible :$('#edit_contact').val()==='1'?true:false, 
                enableSorting: false,
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Action</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.contactid}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editContact(row.entity, row.entity.contactid)"><i class = "fa fa-edit"></i></a></div>'
            }, 
            { 
                displayName:'Login', 
                field:'primarycontactid', 
                width: 55, 
                visible :$('#allow_etp_login').val()==='1'?true:false, 
                enableSorting: false, 
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Login</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.primarycontactid ==\'\'">&nbsp;</div>'+
                              '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.primarycontactid != \'\'">'+
                              '<a title = "Falcon Login" class= "btn btn-info btn-xs" href= "'+base_url+'../falcon/auth/falogin/{{row.entity.primarycontactid}}" target="_blank"><i class= "fa fa-unlock-alt"></i></a>&nbsp;'+
                               '</div>'

            },
            { 
                displayName:'Name',
                cellTooltip: true,
                name: 'contactname',
                width: 150
            },
            { 
                displayName:'Position',
                cellTooltip: true,
                name: 'position',
                width: 100
            },
            { 
                displayName:'Trade',
                cellTooltip: true,
                name: 'trade',
                width: 100
            },
            {   displayName:'Mobile', 
                cellTooltip: true, 
                name: 'mobile',  
                width: 100 
            },
            
            { displayName:'Email', 
                cellTooltip: true, 
                name: 'etp_email',
                width: 170
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'suburb',
                width: 100
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'state',
                enableFiltering: false,
                width: 75
            },
            { 
                displayName:'Reports To',
                cellTooltip: true,
                name: 'reportsto',
                enableFiltering: false,
                width: 130
            },
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'active', 
                width: 70,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.contactid}}" '+$scope.edit_opt+'  data-id="{{row.entity.contactid}}"  class="chk_active"  ng-checked="row.entity.active == 1" /></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_contact').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteContact(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                contactPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               contactPage();
             });
 	
         }
        };
       
        $scope.changeContactFilter = function() {
            contactPage();
        };
       
        $scope.clearContactFilters = function() {
            $scope.filterOptions = {
                filtertext : '',
                tradeid   : '',
                state  : '',
                supplierid : $('#supplierid').val() 
            };

            $('.selectpicker').selectpicker('deselectAll');
            contactPage();
        };
    
        $scope.exportToExcel = function(){
            var qstring = $.param($scope.filterOptions);
            window.open(base_url+'suppliers/exportsuppliercontacts?'+qstring);
        };
       
        var contactPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
        
            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
        
            $scope.overlay = true;
            $http.get(base_url+'suppliers/loadsuppliercontacts?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.contactGrids.totalItems = response.total;
                    $scope.contactGrids.data = response.data;  
                }

            });
        };

        contactPage();
       
        $scope.deleteContact = function(entity) {

            bootbox.confirm("Are you sure to delete Contact <b>"+entity.name+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"suppliers/deletesuppliercontact", { id:entity.contactid }, function( response ) {
                        if (response.success) {
                            contactPage();
                            $('#mysupplierstatus').html('<div class="alert alert-success" >Contact deleted successfully.</div>');
                            clearMsgPanel();
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editContact = function(index, id) {
          
            $('#supplierContactForm').trigger("reset");
            $("#supplierContactForm .alert-danger").hide(); 
            $("#supplierContactForm span.help-block").remove();
            $("#supplierContactForm .has-error").removeClass("has-error");
            $('#supplierContactForm #btnsave').button("reset");
            $('#supplierContactForm #btncancel').button("reset");
            $('#supplierContactForm #btnsave').removeAttr("disabled");
            $('#supplierContactForm #btncancel').removeAttr("disabled");
            $("#supplierContactForm .close").css('display', 'block');
            $("#supplierContactModal h4.modal-title").html('Edit Contact - ' + index.contactname);
           
            $("#supplierContactForm #contactid").val(id); 
            $("#supplierContactForm #mode").val('edit');  
            
            $("#supplierContactForm #bossid > option").each(function() {
                if(this.value == id){
                    $(this).css('display','none');
                }
            });
            
            $("#supplierContactForm #contactModalErrorMsg").hide(); 
            $("#supplierContactForm #contactModalSuccessMsg").hide();
            $("#supplierContactForm #contactModalErrorMsg").html(''); 
            $("#supplierContactForm #contactModalSuccessMsg").html('');
            
            $("#supplierContactForm #customerid").val($("#supplierdetailform #supplierid").val()); 
            $("#supplierContactForm #firstname").val(index.firstname);
            $("#supplierContactForm #surname").val(index.surname); 
            $("#supplierContactForm #position").val(index.position); 
            $("#supplierContactForm #tradeid").val(index.tradeid); 
            $("#supplierContactForm #bossid").val(index.bossid); 
            $("#supplierContactForm #mobile").val(index.mobile); 
            $("#supplierContactForm #phone").val(index.phone); 
            $("#supplierContactForm #email").val(index.etp_email); 
            $("#supplierContactForm #street1").val(index.street1);
            $("#supplierContactForm #street2").val(index.street2); 
            $("#supplierContactForm #suburb").val(index.suburb); 
            $("#supplierContactForm #suburb1").val(index.suburb); 
            $("#supplierContactForm #state").val(index.state); 
            $("#supplierContactForm #postcode").val(index.postcode);
            
            
            if(parseInt(index.etp_onschedule) === 1){
                $('#supplierContactForm input[name="etp_onschedule"]').prop('checked', true);
            }
            else{
                $('#supplierContactForm input[name="etp_onschedule"]').prop('checked', false);
            } 
            if(parseInt(index.primarycontact) === 1){
                $('#supplierContactForm input[name="primarycontact"]').prop('checked', true);
            }
            else{
                $('#supplierContactForm input[name="primarycontact"]').prop('checked', false);
            }   

            if(parseInt(index.active) === 1){
                $('#supplierContactForm input[name="active"]').prop('checked', true);
            }
            else{
                $('#supplierContactForm input[name="active"]').prop('checked', false);
            }   
             $("#supplierContactModal").modal();

        };
        
        
        $scope.addContact = function() { 
          
            $("#supplierContactForm #contactModalErrorMsg").hide(); 
            $("#supplierContactForm #contactModalSuccessMsg").hide();
            $("#supplierContactForm #contactModalErrorMsg").html(''); 
            $("#supplierContactForm #contactModalSuccessMsg").html('');
            $('#supplierContactForm').trigger("reset");
            $("#supplierContactForm .alert-danger").hide(); 
            $("#supplierContactForm span.help-block").remove();
            $("#supplierContactForm .has-error").removeClass("has-error");
            $('#supplierContactForm #btnsave').button("reset");
            $('#supplierContactForm #btncancel').button("reset");
            $('#supplierContactForm #btnsave').removeAttr("disabled");
            $('#supplierContactForm #btncancel').removeAttr("disabled");
            $("#supplierContactForm .close").css('display', 'block');
            $("#supplierContactModal h4.modal-title").html('Add Contact');
            

            $("#supplierContactForm #customerid").val($("#supplierdetailform #supplierid").val()); 
            $("#supplierContactForm #contactid").val(''); 
            $("#supplierContactForm #mode").val('add');  
            $("#supplierContactForm #bossid > option").each(function() {
                $(this).removeAttr('style');
                
            });
            $('#supplierContactForm input[name="active"]').prop('checked', true);
            $('#supplierContactForm input[name="etp_onschedule"]').prop('checked', true);
            $('#supplierContactForm input[name="primarycontact"]').prop('checked', false);
            $("#supplierContactModal").modal();
        };
    }
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
 
$( document ).ready(function() {

        
    if (typeof $.fn.validate === "function") {         
       
        
        $("#supplierContactForm").validate({
            rules: {
                firstname: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                surname: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                position: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                street1: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                suburb: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                phone: {  
                    regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                },
                mobile: { 
                    regex: /^[0-9]{4} [0-9]{3} [0-9]{3}$/
                },
                email: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    },
                    validemail:/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
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
                $("span:eq(0)", "#supplierContactForm #modalsave").css("display", 'block');
                $("span:eq(1)", "#supplierContactForm #modalsave").css("display", 'none');
                $("#supplierContactForm #cancel").button('loading');
                
                $("#supplierContactForm #contactModalErrorMsg").hide(); 
                $("#supplierContactForm #contactModalSuccessMsg").hide();
                $.post( base_url+"suppliers/savesuppliercontact", $('#supplierContactForm').serialize(), function( response ) {
                    $("span:eq(0)", "#supplierContactForm #modalsave").css("display", 'none');
                    $("span:eq(1)", "#supplierContactForm #modalsave").css("display", 'block');
                    $("#supplierContactForm #cancel").button('reset');
                    if (response.success) {
                        if (response.data.success) {
                            $("#supplierContactForm #contactModalSuccessMsg").html(response.message);
                            $("#supplierContactForm #contactModalSuccessMsg").show();
                             
                            $("#supplierContactModal").modal('hide');
                            $( "#ContactCtrl .btn-refresh" ).click();
                            modaloverlap();
                        }
                        else{
                            $("#supplierContactForm #contactModalErrorMsg").html(response.data.message);
                            $("#supplierContactForm #contactModalErrorMsg").show();
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

