/* global bootbox, base_url, app, angular */

"use strict";
    app.controller('userSecurityCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

    // filter
    $scope.filterOptions = {
        filtertext: '',
        contact: '',
        role: '',
        function: ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.securityGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [  
            {   displayName:'Date', 
                cellTooltip: true, 
                name: 'createdate',
                width:130
            },
            {   displayName:'Contact Name', 
                cellTooltip: true, 
                name: 'firstname'
            },
            { 
                displayName:'Role',
                cellTooltip: true,
                name: 'role'
            },
            {   displayName:'Function', 
                cellTooltip: true, 
                name: 'functionname'
            },
            { 
                displayName:'Description',
                cellTooltip: true,
                name: 'description'
            },
            {   displayName:'Has Access', 
                cellTooltip: true, 
                name: 'isactive', 
                enableFiltering: false, 
                width: 95,
                headerCellClass : 'text-center', 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Has Access</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.isactive == 0"><input type="checkbox" value="{{row.entity.id}}" class="security_active" /></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.isactive == 1"><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="security_active" /></div>'
            },
            { 
                displayName:'Delete',
                name: 'id',
                cellTooltip: true,
                enableSorting: false,
                width: 80,
                headerCellClass : 'text-center', 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.userSecurityDelete(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                userSecurityPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               userSecurityPage();
             });
 	
         }
       };
        
        $scope.changeFilters = function() {
           userSecurityPage();
        };
        
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext: '',
                contact: '',
                role: '',
                function: ''
            };
           userSecurityPage();
        };
        
        $scope.functionAccess = function(str) {
            
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length === 0){ 
                var msg;
                if(str === 'give') {
                    msg = 'Please select atleast one row for give access.';
                } else {
                    msg = 'Please select atleast one row for revoke access.'; 
                }
                bootbox.alert(msg);
                return false;
            }
            
            var ids = [];
            var contacts = [];
            var functions = [];
            $scope.selectedRows.forEach(function(rowEntity) {
           
                ids.push(rowEntity.id);
                contacts.push(rowEntity.firstname);
                functions.push(rowEntity.functionname);
            });
            
            contacts = contacts.join(', ');
            functions = functions.join(', ');
            
            msg = '';
            if(str === 'give') {
                msg = "Grant access for <b>"+contacts+"</b> to <b>"+functions+"</b>?";
            } else {
                msg = "Revoke access for <b>"+contacts+"</b> to <b>"+functions+"</b>?"; 
            }
                
            bootbox.confirm(msg, function(result) {
                if (result) {
                    var securityData = {};
                    if(str === 'give') {
                        securityData = {
                            ids:ids,
                            giveaccess:1
                        };
                    } else {
                        securityData = {
                            ids:ids,
                            revokeaccess:1
                        };
                    }
                    updateUserSecurity(securityData);
                   
                }
            });
            
            
        };
        
        $scope.refreshUserSecurityGrid = function() {
            userSecurityPage();
        };
        
        $(document).on('change', '.security_active', function() {
            var id = $(this).val();
            var hasaccess;
            if($(this).is(":checked")) {
                hasaccess = 1;
            } else {
                hasaccess = 0;
            }
            
            var securityData = {
                id:id,
                hasaccess:hasaccess
            };
            
            updateUserSecurity(securityData);
        });
        
        var updateUserSecurity = function(securityData) {
             
            $scope.overlay = true;
            $.post( base_url+"settings/updateusersecurity", securityData, function( response ) {
                if (response.success) {
                     userSecurityPage();
                }
                else {
                    bootbox.alert(response.message);
                }
            });
        }
        
        $scope.userSecurityDelete = function(entity) {

            bootbox.confirm("Are you sure to delete function <b>"+entity.functionname+"</b> for <b>"+entity.firstname+"</b>", function(result) {
                if (result) {
                    
                    $.get( base_url+"settings/deleteusersecurity", { id:entity.id }, function( response ) {
                        if (response.success) {
                             userSecurityPage();
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        $scope.addEditContact = function() {
            $('#addEditSecurityForm').trigger("reset");
            $('#addEditSecurityModal #noaccessgrid tbody').html('');
            $('#addEditSecurityModal #hasaccessgrid tbody').html('');
            $("#addEditSecurityForm .close").show();
            $("#addEditSecurityForm #modalsave").button('reset');
            $("#addEditSecurityForm #modalclose").button('reset');
            $("#addEditSecurityModal").modal();
        };
        
        var userSecurityPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

            $scope.overlay = true;
            $http.get(base_url+'settings/loadusersecurity?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                     
                }else{
                    $scope.securityGrid.totalItems = response.total;
                    $scope.securityGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
       
        $scope.exportToExcel = function(){
           var qstring = $.param($scope.filterOptions);
           window.open(base_url+'settings/exportsecurity?'+qstring);
        };


        userSecurityPage();

    }
]);


$( document ).ready(function() {

    $(document).on('click', '#securityGrid input[name="select_all"]', function(e){

        if(this.checked){
           $('#securityGrid input[name="securitycheckbox[]"]:not(:checked)').trigger('click');
        } else {
           $('#securityGrid input[name="securitycheckbox[]"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    $(document).on('click', '#securityGrid input[name="securitycheckbox[]"]', function(e){

        var chkbox_all = $('#securityGrid input[name="securitycheckbox[]"]');
        var chkbox_checked    = $('#securityGrid input[name="securitycheckbox[]"]:checked');
        var chkbox_select_all  = $('#securityGrid input[name="select_all"]');

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
    
    $("#addEditSecurityForm #modalsave").on('click', function() {
       var noaccess = $('#noaccessgrid input[name="noaccesscheckbox[]"]').length;
       var hasaccess = $('#hasaccessgrid input[name="hasaccesscheckbox[]"]').length;
       if(noaccess == 0 && hasaccess == 0) {
           bootbox.alert("No row for save", modaloverlap());
           return false;
       }
       
       var chkbox = $('#hasaccessgrid input[name="hasaccesscheckbox[]"]');

        var functionids = [];
        $(chkbox).each(function() {
            functionids.push($(this).val());
        });
        
        var contactid = $("#addEditSecurityForm #contactname").val();
        
        var postData = {
            contactid: contactid,
            functionids:functionids
        };
        
        $("#addEditSecurityForm #modalsave").button('loading');
        $("#addEditSecurityForm #modalclose").button('loading');
        $("#addEditSecurityForm .close").hide();
        $.post( base_url+"settings/savecontactsecuritydata", postData, function( response ) {
            $("#addEditSecurityForm .close").show();
            $("#addEditSecurityForm #modalsave").button('reset');
            $("#addEditSecurityForm #modalclose").button('reset');
            if (response.success) {
                $("#userSecurityCtrl .btn-refresh").trigger('click');
                $("#addEditSecurityModal").modal('hide');
            }
            else {
                bootbox.alert(response.message);
            }
        });
       return false; 
    });
});

    
var getContactSecurityData = function(elm) {
    
    var contactid = $(elm).val();
    $("#addEditSecurityForm #copycontactname").prop('selectedIndex', 0);
    if(contactid === '') {
        $('#addEditSecurityModal #noaccessgrid tbody').html('');
        $('#addEditSecurityModal #hasaccessgrid tbody').html('');
        $('#addEditSecurityModal #rolename').val('');
        return false;
    }
    
    if($.trim($('option:selected', elm).attr('contact-role')) === '') {
        bootbox.alert("Contact Role empty.");
        return false;
    }
    
    $('#addEditSecurityModal #rolename').val($('option:selected', elm).attr('contact-role'));
    
    var postData = {
        contactid: contactid,
        role:$('option:selected', elm).attr('contact-role')
    };
    
    contactSecurityData(postData);
    //console.log(postData);

};

var copyContactSecurityData = function() {
    
    var elm = $("#addEditSecurityForm #copycontactname");
    var copycontactid = $(elm).val();
    var contactid = $("#addEditSecurityForm #contactname").val();
    if(copycontactid === '') {
        bootbox.alert("Select Copy From");
        return false;
    }
    
    if(contactid === '') {
        bootbox.alert("Select Name");
        return false;
    }
    
    if($.trim($('option:selected', elm).attr('contact-role')) === '') {
        bootbox.alert("Copy From Contact Role empty.");
        return false;
    }
    
    var postData = {
        contactid: copycontactid,
        role:$('option:selected', elm).attr('contact-role')
    };
    
    contactSecurityData(postData);
    //console.log(postData);
};

var contactSecurityData = function(postData) {
    $("#addEditSecurityForm .hideonload").hide();
    $("#addEditSecurityForm center").show();
    $.post( base_url+"settings/getcontactsecuritydata", postData, function( response ) {
        $("#addEditSecurityForm center").hide();
        $("#addEditSecurityForm .hideonload").show();
        if (response.success) {
            var noaccess = '', hasaccess = '';
            $.each( response.data.noaccess, function( key, value ) {
                noaccess += '<tr><td><input type="checkbox" name="noaccesscheckbox[]" value="' + value.id + '"</td><td>' + value.name + '</td><!--<td>' + value.functionname + '</td>--><td>' + value.description + '</td></tr>'; 
            });
            $.each( response.data.hasaccess, function( key, value ) {
                hasaccess += '<tr><td><input type="checkbox" name="hasaccesscheckbox[]" value="' + value.id + '"</td><td>' + value.name + '</td><!--<td>' + value.functionname + '</td>--><td>' + value.description + '</td></tr>'; 
            });
            
            $("#noaccessgrid tbody").html(noaccess);
            $("#hasaccessgrid tbody").html(hasaccess);
           // console.log(response);
        }
        else {
            bootbox.alert(response.message);
        }
    });
};

var manageSecurity = function(type) {
    
  if(type === 'lhs') {
        var length = $('#hasaccessgrid input[name="hasaccesscheckbox[]"]').length;
        var chkbox_checked = $('#hasaccessgrid input[name="hasaccesscheckbox[]"]:checked');
        
        if(length == 0) {
           bootbox.alert('No row for revoke access', modaloverlap());
           return false; 
        }
        if(chkbox_checked.length === 0) {
            bootbox.alert('Please select atleast one row for revoke access', modaloverlap());
            return false;
        }

        var rows = [];
        var str;
        $(chkbox_checked).each(function() {
            str = encodeURI($(this).parent().parent().html());
            str = str.replace("hasaccesscheckbox", "noaccesscheckbox"); 
            rows.push('<tr>' + str + '</tr>');
            $(this).parent().parent().remove();
        });

        $('#noaccessgrid tbody').prepend(decodeURI(rows.join('')));
  } 
  
  if(type === 'rhs') {
        var length = $('#noaccessgrid input[name="noaccesscheckbox[]"]').length;
        var chkbox_checked = $('#noaccessgrid input[name="noaccesscheckbox[]"]:checked');
        
        if(length == 0) {
           bootbox.alert('No row for give access', modaloverlap());
           return false; 
        }
        if(chkbox_checked.length === 0) {
            bootbox.alert('Please select atleast one row for give access', modaloverlap());
            return false;
        }

        var rows = [];
        var str;
        $(chkbox_checked).each(function() {
            str = encodeURI($(this).parent().parent().html());
            str = str.replace("noaccesscheckbox", "hasaccesscheckbox"); 
            rows.push('<tr>' + str + '</tr>');
            $(this).parent().parent().remove();
        });

        $('#hasaccessgrid tbody').prepend(decodeURI(rows.join('')));
    }  

};

var grantAll = function() {
    var length = $('#noaccessgrid input[name="noaccesscheckbox[]"]').length;
    var chkbox = $('#noaccessgrid input[name="noaccesscheckbox[]"]');

    if(length == 0) {
       bootbox.alert('No row for give access', modaloverlap());
       return false; 
    }

    var rows = [];
    var str;
    $(chkbox).each(function() {
        str = encodeURI($(this).parent().parent().html());
        str = str.replace("noaccesscheckbox", "hasaccesscheckbox"); 
        rows.push('<tr>' + str + '</tr>');
        $(this).parent().parent().remove();
    });

    $('#hasaccessgrid tbody').prepend(decodeURI(rows.join('')));
};

var revokeAll = function() {
    var length = $('#hasaccessgrid input[name="hasaccesscheckbox[]"]').length;
    var chkbox = $('#hasaccessgrid input[name="hasaccesscheckbox[]"]');

    if(length == 0) {
       bootbox.alert('No row for revoke access', modaloverlap());
       return false; 
    }

    var rows = [];
    var str;
    $(chkbox).each(function() {
        str = encodeURI($(this).parent().parent().html());
        str = str.replace("hasaccesscheckbox", "noaccesscheckbox"); 
        rows.push('<tr>' + str + '</tr>');
        $(this).parent().parent().remove();
    });

    $('#noaccessgrid tbody').prepend(decodeURI(rows.join('')));
};