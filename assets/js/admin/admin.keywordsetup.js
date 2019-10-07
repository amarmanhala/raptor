/* global base_url, angular, app */

"use strict";
 var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('KeywordCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {
  
         // filter
    $scope.filterOptions = {
        filtertext : '',
        trade   : '',
        works  : '',
        subworks : ''
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    
    $scope.keywordGrid = {
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
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="keywordcheckbox[]" value="{{row.entity.id}}" keyword-title="{{row.entity.word}}" /></div>',
                pinnedLeft:true,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="select_all"  value="1"  /></div>'
            }, 
            { 
                displayName:'Trade',
                cellTooltip: true,
                name: 'se_trade_name'
            },
            { 
                displayName:'Works',
                cellTooltip: true,
                name: 'se_works_name'
            },
            { 
                displayName:'Sub-Works',
                cellTooltip: true,
                name: 'se_subworks_name',
                enableFiltering: false
            },
            { displayName:'Keyword', 
                cellTooltip: true, 
                name: 'word'
            },
            {   displayName:'Weighting', 
                cellTooltip: true, 
                name: 'weighting'
            },
            { 
                displayName:'Edit',
                name: 'edit',
                cellTooltip: true,
                enableFiltering: false,  
                enableSorting: false, 
                width: 60,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.id}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editKeyword(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false,  
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteKeyword(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                keywordPage();
            });
            
            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               keywordPage();
            });
 	
         }
       };
       
       $scope.changeCustomerText = function() {
            var text = $scope.filterOptions.company;
            if(text === undefined){
                return false;
            }
            if(text === null || text.length === 0) { 
                $scope.keywordGrid.totalItems = 0;
                $scope.keywordGrid.data = [];  
            } 
        };
       
      $scope.changeFilter = function() {
           keywordPage();
       };
       
       $scope.clearFilters = function() {
            $scope.filterOptions = {
                filtertext : '',
                trade   : '',
                works  : '',
                subworks : ''
            };

            $('.selectpicker').selectpicker('deselectAll');
            keywordPage();
        };
        $scope.updatestatus = function(contactid, status) {
            $scope.filterOptions.updatestatuscontactid = contactid;
            keywordPage();
        };
    
       
    var keywordPage = function() {

        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        
        
        var qstring = $.param(params)+'&'+$.param($scope.filterOptions);

        $scope.overlay = true;
        $http.get(base_url+'admin/keywordsetup/loadkeywords?'+ qstring, {
            headers : {
                "content-type" : "application/x-www-form-urlencoded"
            }
        }).success(function(response) {
            if (response.success === false) {
                bootbox.alert(response.message);
            }else{
                $scope.keywordGrid.totalItems = response.total;
                $scope.keywordGrid.data = response.data;  
            }
            $scope.overlay = false;
        });
    };

    $scope.exportToExcel = function(){
        var qstring = $.param($scope.filterOptions);
        window.open(base_url+'admin/keywordsetup/exportkeywordworks?'+qstring);
    };
         
    $scope.updateWeighting = function() {
       var weighting = $('#KeywordCtrl #weighting').val();
       if($.trim(weighting) === '') {
           bootbox.alert('weighting empty');
           return false;
       }
       if(parseInt(weighting) < 1 || parseInt(weighting) > 100) {
           bootbox.alert('weighting between 1-100');
           return false;
       }

       var chkbox_checked = $('#keywordGrid input[name="keywordcheckbox[]"]:checked');
       if(chkbox_checked.length === 0){
           bootbox.alert('Select a keyword to update weighting');
           return false;
       }

       var ids = [];
       var names = [];
       $('#keywordGrid input[name="keywordcheckbox[]').each(function () {
           if ($(this).prop('checked')) {
               ids.push($(this).val());
               names.push($(this).attr('keyword-title'));
           }
       });
       console.log(ids);

       bootbox.confirm("Are you sure to update Weighting for <b>"+names+"</b>", function(result) {
           if (result) {

               $.post( base_url+"admin/keywordsetup/updateweighting", { ids:ids, weighting:weighting }, function( response ) {
                   if (response.success) {
                       keywordPage();
                       bootbox.alert('Weighting updated');
                   }
                   else {
                       bootbox.alert(response.message);
                   }
               });
           }
       });
    };
    
        
    $scope.deleteKeyword = function(entity) {

        bootbox.confirm("Are you sure to delete <b>"+entity.word+"</b>", function(result) {
            if (result) {

                $.post( base_url+"admin/keywordsetup/deletekeywordworks", { id:entity.id }, function( response ) {
                    if (response.success) {
                        bootbox.alert('Record deleted successfully');
                        keywordPage();

                    }
                    else {
                        bootbox.alert(response.message);
                    }
                });
            }
        });
    };
        
        
    $scope.editKeyword = function(index, id) {


        $("#keywordModel #loading-img").show();
        $("#keywordModel #sitegriddiv").hide();
        $('#keywordform').trigger("reset");
        $("#keywordform .alert-danger").hide(); 
        $("#keywordform span.help-block").remove();
        $("#keywordform .has-error").removeClass("has-error");
        $('#keywordform #btnsave').button("reset");
        $('#keywordform #btncancel').button("reset");
        $('#keywordform #btnsave').removeAttr("disabled");
        $('#keywordform #btncancel').removeAttr("disabled");
        $("#keywordform .close").css('display', 'block');
        $("#keywordModel h4.modal-title").html('Edit Keyword : ' + index.word);
        $("#keywordModel").modal();

        $("#keywordform #keywordid").val(id); 
        $("#keywordform #mode").val('edit');
        
        $("#keywordform #keyword").val(index.word); 
        $("#keywordform #strade").val(index.tradeid);
        $("#keywordform #sworks").val(index.worksid);
        $("#keywordform #ssubworks").val(index.subworksid);
        $("#keywordform #sweighting").val(index.weighting);
        $("#keywordform #keyword").attr('disabled', 'disabled'); 

        $("#keywordModel #loading-img").hide();
        $("#keywordModel #sitegriddiv").show();
        $("#keywordform #reset").val(''); 
    };


    $scope.addKeyword = function() { 

        $("#keywordModel #loading-img").show();
        $("#keywordModel #sitegriddiv").hide();
        $('#keywordform').trigger("reset");
        $("#keywordform .alert-danger").hide(); 
        $("#keywordform span.help-block").remove();
        $("#keywordform .has-error").removeClass("has-error");
        $('#keywordform #btnsave').button("reset");
        $('#keywordform #btncancel').button("reset");
        $('#keywordform #btnsave').removeAttr("disabled");
        $('#keywordform #btncancel').removeAttr("disabled");
        $("#keywordform #keyword").removeAttr('disabled');
        $("#keywordform .close").css('display', 'block');
        $("#keywordModel h4.modal-title").html('Add Keyword');
        $("#keywordModel").modal();

        $("#keywordform #keywordid").val(''); 
        $("#keywordform #mode").val('add');  
        $("#keywordform #reset").val(''); 

        $("#keywordform #keyword").val(''); 
        $("#keywordform #strade").val('');
        $("#keywordform #sworks").val('');
        $("#keywordform #ssubworks").val('');
        $("#keywordform #sweighting").val('');
        $("#keywordModel #loading-img").hide();
        $("#keywordModel #sitegriddiv").show();
    };

    $(document).on('click', '#keywordModel #btnsave', function() {


        var keyword = $("#keywordform #keyword");
        var trade = $("#keywordform #strade");
        var works = $("#keywordform #sworks");
        var subworks = $("#keywordform #ssubworks");
        var weighting = $("#keywordform #sweighting");

        $("#keywordform span.help-block").remove();


        if($.trim(keyword.val()) === "") {
            $(keyword).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(keyword.parent());
        } else {
            $(keyword).parent().removeClass("has-error");
        }
        
        if($.trim(trade.val()) === "") {
            $(trade).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(trade.parent());
        } else {
            $(trade).parent().removeClass("has-error");
        }
        
        if($.trim(works.val()) === "") {
            $(works).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(works.parent());
        } else {
            $(works).parent().removeClass("has-error");
        }
        
        if($.trim(subworks.val()) === "") {
            $(subworks).parent().addClass("has-error");
            $('<span class="help-block">This field is required.</span>').appendTo(subworks.parent());
        } else {
            $(subworks).parent().removeClass("has-error");
        }
        
        var wetrue = false;
        if($.trim(weighting.val()) === "") {
            $(weighting).parent().addClass("has-error");
            wetrue = false;
            $('<span class="help-block">Field required.</span>').appendTo(weighting.parent());
        } else {
            if(parseInt(weighting.val()) < 1 || parseInt(weighting.val()) > 100) {
                $(weighting).parent().addClass("has-error");
                $('<span class="help-block">Between 1-100.</span>').appendTo(weighting.parent());
                wetrue = false;
            } else {
                wetrue = true;
                $(weighting).parent().removeClass("has-error");
            }
        }

        if($.trim(keyword.val()) === "" || $.trim(trade.val()) === "" || $.trim(works.val()) === "" || $.trim(subworks.val()) === "" || wetrue === false) {
            return false;
        }

        $("#keywordform #btnsave").button('loading'); 
        $("#keywordform #btncancel").button('loading'); 
        $.post( base_url+"admin/keywordsetup/addeditkeywordworks", $("#keywordform").serialize(), function( response ) {
            $('#keywordform #btnsave').removeAttr("disabled");
            $('#keywordform #btncancel').removeAttr("disabled");

            $('#keywordform #btnsave').removeClass("disabled");
            $('#keywordform #btncancel').removeClass("disabled");
            $('#keywordform #btnsave').html("Save");
            $('#keywordform #btncancel').html("Cancel");
            if(response.success) {
                if (response.data.success) {
                    $("#keywordModel").modal('hide');
                    bootbox.alert(response.data.message);
                    announcementsPage();
                }
                else{
                    $('#keywordModel .status').html('<div class="alert alert-danger" >'+response.data.message+'</div>');
                }

            }
            else{
                $('#keywordModel .status').html('<div class="alert alert-danger" >'+response.message+'</div>');
            }
        }, 'json');
    });

    $(document).on('click', '#keywordModel #btncancel', function() {
        $("#keywordModel").modal('hide');
    });

    keywordPage();
    }
     
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

$( document ).ready(function() {
    
     $(document).on('click', '#keywordGrid input[name="select_all"]', function(e){

        if(this.checked){
           $('#keywordGrid input[name="keywordcheckbox[]"]:not(:checked)').trigger('click');
        } else {
           $('#keywordGrid input[name="keywordcheckbox[]"]:checked').trigger('click');
        }

        // Prevent click event from propagating to parent
        e.stopPropagation();
    });

    $(document).on('click', '#keywordGrid input[name="keywordcheckbox[]"]', function(e){

        var chkbox_all = $('#keywordGrid input[name="keywordcheckbox[]"]');
        var chkbox_checked    = $('#keywordGrid input[name="keywordcheckbox[]"]:checked');
        var chkbox_select_all  = $('#keywordGrid input[name="select_all"]');

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