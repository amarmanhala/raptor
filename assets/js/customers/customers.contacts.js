/* global base_url, angular, app, bootbox */

"use strict";
var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch',    
app.controller('ContactCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
    $scope.contactFilter = {
        filtertext : '',
        role   : '',
        state  : '',
        bossid : '',
        status : ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    
    $scope.edit_opt = $('#edit_contact').val()==='1'?'':'disabled="disabled"';
    
    $scope.contactGrid = {
        paginationPageSizes: [10, 25, 50, 100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [ 
            { 
                displayName:'Edit',
                field:'edit',
                width: 40,
                visible :$('#edit_contact').val()==='1'?true:false, 
                enableSorting: false,
                enableFiltering: false, 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><a title = "edit" href= "' + base_url + 'customers/editcontact/{{row.entity.contactid}}"><i class= "fa fa-edit"></i></a></div>'
            },
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'active', 
                width:55,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.contactid}}" '+$scope.edit_opt+'  data-id="{{row.entity.contactid}}"  class="chk_active"  ng-checked="row.entity.active == 1" /></div>'
            }, 
            { 
                displayName:'Name',
                cellTooltip: true,
                name: 'contactname',
                //sort: {
                //    direction: uiGridConstants.ASC
                //},
                width: 150
            },
            { 
                displayName:'Position',
                cellTooltip: true,
                name: 'position',
                width: 100
            },
            { 
                displayName:'Role',
                cellTooltip: true,
                name: 'role',
                width: 100
            },
            {   displayName:'Mobile', 
                cellTooltip: true, 
                name: 'mobile',  
                width: 100 
            },
            {   displayName:'Phone', 
                cellTooltip: true, 
                name: 'phone', 
                width: 100 
            },
            { displayName:'Email', 
                cellTooltip: true, 
                name: 'email',
                width: 200
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'suburb',
                width: 120
            },
            { 
                displayName:'State',
                cellTooltip: true,
                name: 'state',
                enableFiltering: false,
                width: 65
            },
            { 
                displayName:'Reports To',
                cellTooltip: true,
                name: 'reportsto',
                enableFiltering: false,
                width: 130
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
            $scope.contactFilter = {
                filtertext : '',
                role   : '',
                state  : '',
                bossid : '',
                status : ''
            };

            $('.selectpicker').selectpicker('deselectAll');
            contactPage();
        };
    
        $scope.exportToExcel = function(){
            var qstring = $.param($scope.contactFilter);
            window.open(base_url+'customers/exportcontacts?'+qstring);
        };
       
        var contactPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 
 
            var qstring = $.param(params)+'&'+$.param($scope.contactFilter);
            $scope.overlay = true;
            
            $http.get(base_url+'customers/loadcontacts?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) { 
                $scope.overlay = false;
                if (response.success === false) {
                    bootbox.alert(response.message);
                }else{
                    $scope.contactGrid.totalItems = response.total;
                    $scope.contactGrid.data = response.data;  
                }
               
            });
        };

       contactPage();
       
       $(document).on('change', '.chk_active', function(event) {
            var id = $(this).val();
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateContactStatus(id, 'active', value);

        }); 
    
        var updateContactStatus = function(id, field, value) {
 
            var params = { 
                id  : id,
                field: field,
                value: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'customers/updatecontact', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                     $scope.contactGrid.totalItems = 0;
                     $scope.contactGrid.data= [];
                     contactPage();
                }
                else {
                    bootbox.alert(data.message);
                    
                }
            });
        };
       
        $scope.sendPortalInvitation = function() {
        
            $('.map-overlay').show();
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select contacts for send portal invitations.');
                return false;
            } 
            var contacts = [];
            var contactids = [];
            var i = 0; 
            $scope.selectedRows.forEach(function(rowEntity) { 
                contacts.push(rowEntity.contactname);
                contactids.push(rowEntity.contactid);
                i++;  
            });
    
            contacts = contacts.join(", ");

             bootbox.dialog({
                message: "Send portal invitations to <b>"+contacts+"</b> ?",
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
                                        bootbox.alert(response.message);
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
    
});




