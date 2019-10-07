/* global base_url, angular, app */

"use strict";
 var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('CustomerContactCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {
  
         // filter
    $scope.filterOptions = {
        filtertext : '',
        role   : '',
        state  : '',
        status : '',
        customerid : '',
        company : '',
        updatestatuscontactid : ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    
    $scope.contactGrid = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [
            { 
                displayName:'Select',
                name: 'select',
                enableSorting: false,
                width:40,
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="contactscheckbox[]" value="{{row.entity.contactid}}" contact-title="{{row.entity.contactname}}" role="{{row.entity.role}}" /></div>',
                pinnedLeft:true,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  /></div>'
            }, 
            { 
                displayName:'Name',
                cellTooltip: true,
                name: 'contactname',
                width: 150
            },
            { 
                displayName:'Role',
                cellTooltip: true,
                name: 'role',
                width: 100
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'state',
                enableFiltering: false,
                width: 70
            },
            { displayName:'Email', 
                cellTooltip: true, 
                name: 'email',
                width: 200
            },
            {   displayName:'Mobile', 
                cellTooltip: true, 
                name: 'mobile',  
                width: 100 
            },
            { 
                displayName:'Invited',
                cellTooltip: true,
                name: 'cp_invitesendtime',
                width: 140
            },
           
            { 
                displayName:'Last Login',
                cellTooltip: true,
                name: 'last_login',
                enableFiltering: false,
                width: 140
            },
            {   displayName:'Status', 
                cellTooltip: true, 
                name: 'status', 
                width: 70,
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ><a href="javascript:void(0)" ng-click="grid.appScope.updatestatus(row.entity.contactid, row.entity.status)" >{{row.entity.status}}</a></div>'
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
       
       $scope.changeCustomerText = function() {
            var text = $scope.filterOptions.company;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
                $scope.contactGrid.totalItems = 0;
                $scope.contactGrid.data = [];  
            } 
        };
       
      $scope.changeContactFilter = function() {
           contactPage();
       };
       
       $scope.clearContactFilters = function() {
            $scope.filterOptions = {
                filtertext : '',
                role   : '',
                state  : '',
                status : '',
                customerid : '',
                company : '',
                updatestatuscontactid : ''
            };

            $('.selectpicker').selectpicker('deselectAll');
            contactPage();
        };
        $scope.updatestatus = function(contactid, status) {
            $scope.filterOptions.updatestatuscontactid = contactid;
            contactPage();
        };
    
       
    var contactPage = function() {
           
        if($scope.filterOptions.customerid === '' || $scope.filterOptions.customerid == undefined || $scope.filterOptions.company === '' || $scope.filterOptions.company == undefined) {
            $scope.filterOptions.customerid = ''; 
            bootbox.alert('Select Customer');
            return false;
        }
        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        
        
        var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
        $scope.filterOptions.updatestatuscontactid = '';
        $scope.overlay = true;
        $http.get(base_url+'admin/customercontacts/loadcustomercontacts?'+ qstring, {
            headers : {
                "content-type" : "application/x-www-form-urlencoded"
            }
        }).success(function(response) {
            if (response.success === false) {
                bootbox.alert(response.message);
            }else{
                $scope.contactGrid.totalItems = response.total;
                $scope.contactGrid.data = response.data;  
            }
            $scope.overlay = false;
        });
    };

      
       
        var deferred;  
     
        //Any function returning a promise object can be used to load values asynchronously
        $scope.getCustomer = function(val) {

            deferred = $q.defer(); 
            $http.get(base_url+'admin/customercontacts/loadcustomersearch', {
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
       
        $scope.onCustomerSelect = function ($item, $model, $label) {
             
            $scope.filterOptions.company = $item.companyname;
            $scope.filterOptions.customerid = $item.customerid;  
            contactPage();
         };
       
     }
     ]);

     app.filter('trusted', function ($sce) {
         return function (value) {
           return $sce.trustAsHtml(value);
         };
     });

$( document ).ready(function() {
    
     $(document).on('click', '#contactGrid input[name="select_all"]', function(e){

        if(this.checked){
           $('#contactGrid input[name="contactscheckbox[]"]:not(:checked)').trigger('click');
        } else {
           $('#contactGrid input[name="contactscheckbox[]"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    $(document).on('click', '#contactGrid input[name="contactscheckbox[]"]', function(e){

        var chkbox_all = $('#contactGrid input[name="contactscheckbox[]"]');
        var chkbox_checked    = $('#contactGrid input[name="contactscheckbox[]"]:checked');
        var chkbox_select_all  = $('#contactGrid input[name="select_all"]');

            // If none of the checkboxes are checked
            if (chkbox_checked.length === chkbox_all.length){
               chkbox_select_all.prop('checked', true);

            // If some of the checkboxes are checked
            } else {
               chkbox_select_all.prop('checked', false);

            }

         // Prevent click event from propagating to parent
         e.stopPropagation();
    });
    
     $(document).on('click', '#contactMenuModel #btnsave', function() {
 
            $("#contactmenuform #btnsave").button('loading'); 
            $("#contactmenuform #btncancel").button('loading'); 
            $.post( base_url+"admin/customercontacts/updatecontactmenuaccess", $("#contactmenuform").serialize(), function( data ) {
                $('#contactmenuform #btnsave').removeAttr("disabled");
                $('#contactmenuform #btncancel').removeAttr("disabled");
                
                $('#contactmenuform #btnsave').removeClass("disabled");
                $('#contactmenuform #btncancel').removeClass("disabled");
                $('#contactmenuform #btnsave').html("Save");
                $('#contactmenuform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#contactMenuModel").modal('hide');
                    bootbox.alert('Contact Menu Access updated.');
                 
                }
                else{
                    bootbox.alert(data.message);
                
                }
            }, 'json');
        });

        $(document).on('click', '#contactMenuModel #btncancel', function() {
            $("#contactMenuModel").modal('hide');
        });
    
});


var contactMenuAccess = function() {
    
     
    var chkbox_checked = $('#contactGrid input[name="contactscheckbox[]"]:checked');
    if(chkbox_checked.length === 0){
        bootbox.alert('Select a contact');
        return false;
    }
    if(chkbox_checked.length > 1){
        bootbox.alert('Select one contact only.');
        return false;
    }
 
    var contactid = chkbox_checked.val();
    var contactname = chkbox_checked.attr('contact-title');
    var role = chkbox_checked.attr('role');
    
    
    $("#contactMenuModel #loading-img").show();
    $("#contactMenuModel #sitegriddiv").hide();
    $('#contactmenuform').trigger("reset");
    $("#contactmenuform .alert-danger").hide(); 
    $("#contactmenuform span.help-block").remove();
    $("#contactmenuform .has-error").removeClass("has-error");
    $('#contactmenuform #btnsave').button("reset");
    $('#contactmenuform #btncancel').button("reset");
    $('#contactmenuform #btnsave').removeAttr("disabled");
    $('#contactmenuform #btncancel').removeAttr("disabled");
    $("#contactmenuform .close").css('display', 'block'); 
    $("#contactMenuModel").modal();

    $("#contactmenuform #contactid").val(contactid); 
    $("#contactmenuform #contactname").val(contactname);  
   

    var option ='';
    $("#contactmenuform #role").html(option); 
    $.get( base_url+"admin/customercontacts/loadcontactmenu", { get:1,contactid:contactid}, function( data ) {
        
        if (data.success === false) {
            $("#contactMenuModel").modal('hide');
            bootbox.alert(data.message);

        }else{
            var datas = data.data;
            $.each( datas.roles, function( key, value ) {
                option = option + '<option value="'+ value.role +'">'+ value.role +'</option>';
            }); 
            $("#contactmenuform #role").html(option); 
            $("#contactmenuform #role").val(role);
            
            option ='';
            $.each( datas.modules, function( key, value ) {
                option = option + '<tr><td>'+ value.module +'</td><td class="text-center">';
                if(value.visible==1){
                    option = option + '<input type="checkbox" data-module="'+ value.module +'" value="'+ value.id +'" name="moduleid[]" checked="checked">';
                }
                else{
                    option = option + '<input type="checkbox" data-module="'+ value.module +'" value="'+ value.id +'" name="moduleid[]" >';
                }
                option = option + '</td></option>';
            }); 
            
            $("#contactmenuform #menumoduletbl tbody").html(option); 
        } 

          
        
        $("#contactMenuModel #loading-img").hide();
        $("#contactMenuModel #sitegriddiv").show();
                
    }, 'json');
    
            
              
    
};

var sendPortalInvitation = function() {
    
    var length = $('#contactGrid input[name="contactscheckbox[]"]').length;
    var chkbox_checked = $('#contactGrid input[name="contactscheckbox[]"]:checked');
    if(chkbox_checked.length === 0){
        bootbox.alert('Please select contacts for send portal invitations.');
        return false;
    }
    
    var contacts = [];
    var contactids = [];
    var i = 0;
    $(chkbox_checked).each(function() {
        contacts.push($(this).attr('contact-title'));
        contactids.push($(this).val());
        i++;  
    });
    
    contacts = contacts.join(", ");
    
     bootbox.dialog({
        message: "Send portal Invite <b>"+contactids.length+"</b> contacts?",
        title: 'Send Portal Invitation',
        buttons: 			
        {   
           "ok" :
            {
                "label" : "Yes",
                "className" : "btn-sm btn-primary",
                 callback: function() {
                    
                    $.ajax({
                        url : base_url+"customers/sendportalinvitations",
                        data: { contactids:contactids },
                        method: 'post',
                        beforeSend: function(){
                            bootbox.dialog({
                                closeButton : false,
                                message: "<span class='bigger-110'>Processing..</span>",
                                title: "Processing.."
                            });
                        },
                        complete: function(response){
                            
                        },
                        success: function(response) {
                            if (response.success) {
                               bootbox.hideAll();
                               bootbox.alert(contactids.length+' contacts invited.');
                               $( "#CustomerContactCtrl .btn-refresh" ).click(); 
                           }
                           else {
                               bootbox.alert(response.message);
                           }
                        }
                    });
                }
            }, 
           "cancel" :
            {
                "label" : "Cancel",
                "className" : "btn-sm btn-default"
            }
        }
    });
    
    
};


