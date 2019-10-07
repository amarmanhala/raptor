/* global bootbox, base_url, app, angular, defaultdateformat */

"use strict";
    app.controller('portalSettingsCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

        
        
    $scope.filterOptions = {
        filtertext : '' 
    };
    $scope.edit_opt = $('#edit_portalsettings').val()==='1'?'':'disabled="disabled"';
    $scope.portalSettingsGrid = {
        enableSorting: true,
        enableColumnMenus: false,
        enablePagination:false,
        enablePaginationControls:false,
        columnDefs: [ 
            {   displayName:'ID', 
                cellTooltip: true, 
                name: 'rulename_id', 
                width:50
            },
            {   displayName:'Setting', 
                cellTooltip: true, 
                name: 'caption'
            },
           
            {   displayName:'Value', 
                name: 'value',
                 
                headerCellClass : 'text-center', 
                cellTemplate: '\
                            <div class="ui-grid-cell-contents text-center" ng-if="row.entity.valuetype == \'N\'"><input name="portaltext" type="text" data-rulename_id="{{row.entity.rulename_id}}" value="{{row.entity.value}}" class="form-control allownumericwithoutdecimal" '+$scope.edit_opt+'/></div>\n\
                            <div class="ui-grid-cell-contents text-center" ng-if="row.entity.valuetype == \'S\'"><input name="portaltext" type="text" data-rulename_id="{{row.entity.rulename_id}}" value="{{row.entity.value}}" class="form-control" '+$scope.edit_opt+'/></div>\n\
                            <div class="ui-grid-cell-contents text-center" ng-if="row.entity.valuetype == \'B\'"><input type="checkbox" data-rulename_id="{{row.entity.rulename_id}}" class="chk_rule_value" ng-checked="row.entity.value == 1" '+$scope.edit_opt+'/></div>'
            },
             { 
                displayName:'For Master',
                name: 'is_master', 
                width:100,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">For Master</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.rulename_id}}" data-rulename_id="{{row.entity.rulename_id}}"  class="chk_is_master"  ng-checked="row.entity.is_master == 1" '+$scope.edit_opt+'/></div>'
            },
             { 
                displayName:'Active',
                name: 'is_sitefm', 
                width:100,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">For Site FM</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.rulename_id}}" data-rulename_id="{{row.entity.rulename_id}}"  class="chk_is_sitefm"  ng-checked="row.entity.is_sitefm == 1" '+$scope.edit_opt+'/></div>'
            },
             { 
                displayName:'Active',
                name: 'is_sitecontact', 
                width:120,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">For Site Contact</div>',
               cellTemplate: '<div class="ui-grid-cell-contents  text-center"><input type="checkbox" value="{{row.entity.rulename_id}}" data-rulename_id="{{row.entity.rulename_id}}"  class="chk_is_sitecontact"  ng-checked="row.entity.is_sitecontact == 1" '+$scope.edit_opt+'/></div>'
            }
         ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
         }
       };

        $scope.changeText = function() {
            var text = $scope.filterOptions.filtertext;
            
            if(text.length === 0 || text.length>1) { 
                portalSettingsPage();
            } 
        };
        
        $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext : '' 
            };  
            portalSettingsPage();
        };
        
        $scope.refreshPortalSettingsGrid = function() {
            portalSettingsPage();
        };
 
        $scope.exportPortalSettings = function(){ 
          var qstring = $.param($scope.filterOptions);
           window.open(base_url+'settings/exportportalsettings?'+ qstring);
        };
        
        var portalSettingsPage = function() {

            var qstring = $.param($scope.filterOptions);
            $scope.overlay = true;
            $http.get(base_url+'settings/loadportalsettings?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                     bootbox.alert(response.message);
                    
                }else{
                    $scope.portalSettingsGrid.totalItems = response.total;
                    $scope.portalSettingsGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
        portalSettingsPage();
        
        var savePortalSettings = function(rulename_id, field, value, input) {
    
            var postData = {
                rulename_id:rulename_id,
                value: value,
                field: field
            }; 

            $.each($scope.portalSettingsGrid.data, function( key, val ) {
                if(parseInt(val.rulename_id) === parseInt(rulename_id)){
                    if(field === 'is_master'){
                        $scope.portalSettingsGrid.data[key].is_master = value;
                    }
                    else if(field === 'is_sitefm'){
                        $scope.portalSettingsGrid.data[key].is_sitefm = value;
                    }
                    else if(field === 'is_sitecontact'){
                        $scope.portalSettingsGrid.data[key].is_sitecontact = value;
                    }
                    else{
                        $scope.portalSettingsGrid.data[key].value = value;
                    }
                     
                    postData.is_master = $scope.portalSettingsGrid.data[key].is_master;
                    postData.is_sitefm = $scope.portalSettingsGrid.data[key].is_sitefm;
                    postData.is_sitecontact = $scope.portalSettingsGrid.data[key].is_sitecontact;
                    postData.rule_value = $scope.portalSettingsGrid.data[key].value;
                }
                        
            });

            input.addClass('custom-input-success');
            var qstring = $.param(postData);
            $scope.overlay = true;
            $http.post(base_url+'settings/updateportalsettings', qstring, {
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
 

        $(document).on('change', '.chk_rule_value', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'rule_value', value, $(this));
        
        }); 
        
        $(document).on('change', '.chk_is_master', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'is_master', value, $(this));
         
        });
        
        $(document).on('change', '.chk_is_sitefm', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'is_sitefm', value, $(this));
          
        });
        
        $(document).on('change', '.chk_is_sitecontact', function() {
            
            var rulename_id = $(this).attr('data-rulename_id');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            savePortalSettings(rulename_id, 'is_sitecontact', value, $(this));

        });

        $(document).on('change', "#portalsettingsgrid input[type='text'][name='portaltext']",function(){
            var rulename_id = $(this).attr('data-rulename_id');
            var value = $.trim($(this).val());
           
            savePortalSettings(rulename_id, 'rule_value', value, $(this));
        });
    }
]);

