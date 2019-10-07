/* global base_url, angular, app, bootbox */

"use strict";
 var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('AnnouncementsCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {
  
         // filter
    $scope.filterOptions = {
        filtertext : '',
        status   : 'active' 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize  : 25,
        sort      : '',
        field     : ''
    };
    
    $scope.gridOptions = {
        paginationPageSizes: [10, 25, 50,100],
         paginationPageSize: 25,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         columnDefs: [
             
            { 
                displayName:'ID',
                cellTooltip: true,
                name: 'id',
                width: 60
            },
            { 
                displayName:'Caption',
                cellTooltip: true,
                name: 'caption' 
            },
            { 
                displayName:'Activation Date',
                cellTooltip: true,
                name: 'activationdate',
                enableFiltering: false,
                width: 200
            },
            { 
                displayName:'Browser',
                cellTooltip: true,
                name: 'browser',
                width: 150,
                 cellTemplate: '<div class="ui-grid-cell-contents" >{{row.entity.browser}}<span ng-if="row.entity.browser_version != 0"> - {{row.entity.browser_version}}</span></div>'
            },
            { 
                displayName:'Active',
                name: 'isactive', 
                width:80,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.isactive == 0">NO</div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.isactive == 1">YES</div>'
            },
            { 
                displayName:'Edit',
                name: 'edit',
                cellTooltip: true,
                enableFiltering: false,  
                enableSorting: false, 
                width: 60,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.id}}"><a  href="javascript:void(0)" ng-click="grid.appScope.editAnnouncement(row.entity, row.entity.id)"><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false,  
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteAnnouncement(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                announcementsPage();
            });
            
            gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               announcementsPage();
            });
 	
         }
       };
       
        
       
      $scope.refreshFilter = function() {
           announcementsPage();
       };
       
       
    var announcementsPage = function() {
           
        
        var params = {
            page  : paginationOptions.pageNumber,
            size  : paginationOptions.pageSize,
            field : paginationOptions.field,
            order : paginationOptions.sort
        }; 
        
        
        var qstring = $.param(params)+'&'+$.param($scope.filterOptions);
  
        $scope.overlay = true;
        $http.get(base_url+'admin/announcements/loadannouncements?'+ qstring, {
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
 
    announcementsPage();
    
    $scope.deleteAnnouncement = function(entity) {

            bootbox.confirm("Are you sure to delete Announcement <b>"+entity.caption+"</b>", function(result) {
                if (result) {
                    
                    $.post( base_url+"admin/announcements/deleteannouncement", { id:entity.id }, function( response ) {
                        if (response.success) {
                            bootbox.alert('Announcement deleted successfully');
                            announcementsPage();
                            
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editAnnouncement = function(index, id) {
         
            
            $("#announcementModel #loading-img").show();
            $("#announcementModel #sitegriddiv").hide();
            $('#announcementform').trigger("reset");
            $("#announcementform .alert-danger").hide(); 
            $("#announcementform span.help-block").remove();
            $("#announcementform .has-error").removeClass("has-error");
            $('#announcementform #btnsave').button("reset");
            $('#announcementform #btncancel').button("reset");
            $('#announcementform #btnsave').removeAttr("disabled");
            $('#announcementform #btncancel').removeAttr("disabled");
            $("#announcementform .close").css('display', 'block');
            $("#announcementModel h4.modal-title").html('Edit Announcement : ' + id);
            $("#announcementModel").modal();

            $("#announcementform #announcementid").val(id); 
            $("#announcementform #mode").val('edit');  
             

            setTimeout(function(){ 
                $("#announcementModel #loading-img").hide();
                $("#announcementModel #sitegriddiv").show();

            }, 1000);
            
            $("#announcementform #reset").val(''); 
            $("#announcementform #caption").val(index.caption); 
            $("#announcementform #browser").val(index.browser); 
            $("#announcementform #browser_version").val(index.browser_version); 
            
            $("#announcementform #activationdate").val(index.activationdate); 
            $("#announcementform #ckeditor").html(index.content);
            if(parseInt(index.isactive) === 1){
                $('#announcementform input[name="isactive"]').prop('checked', true);
            }
            else{
                $('#announcementform input[name="isactive"]').prop('checked', false);
            }   
            if(parseInt(index.ispersistent) === 1){
                $('#announcementform input[name="ispersistent"]').prop('checked', true);
            }
            else{
                $('#announcementform input[name="ispersistent"]').prop('checked', false);
            }   
            
            CKEDITOR.instances.ckeditor.setData(index.content);
//            CKEDITOR.replace('ckeditor',
//                {
//                    extraPlugins: 'imageuploader'
//                }
//            );

        };
        
        
        $scope.addAnnouncement = function() { 
         
            $("#announcementModel #loading-img").show();
            $("#announcementModel #sitegriddiv").hide();
            $('#announcementform').trigger("reset");
            $("#announcementform .alert-danger").hide(); 
            $("#announcementform span.help-block").remove();
            $("#announcementform .has-error").removeClass("has-error");
            $('#announcementform #btnsave').button("reset");
            $('#announcementform #btncancel').button("reset");
            $('#announcementform #btnsave').removeAttr("disabled");
            $('#announcementform #btncancel').removeAttr("disabled");
            $("#announcementform .close").css('display', 'block');
            $("#announcementModel h4.modal-title").html('Add Announcement');
            $("#announcementModel").modal();

            $("#announcementform #announcementid").val(''); 
            $("#announcementform #mode").val('add');  
            $("#announcementform #reset").val(''); 
            
            $("#announcementform #caption").val(''); 
            $("#announcementform #browser").val(''); 
            $("#announcementform #browser_version").val('0'); 
            $("#announcementform #activationdate").val('');
            $('#announcementform input[name="isactive"]').prop('checked', true);
            $('#announcementform input[name="ispersistent"]').prop('checked', false);
            CKEDITOR.instances.ckeditor.setData('');
            setTimeout(function(){ 
                $("#announcementModel #loading-img").hide();
                $("#announcementModel #sitegriddiv").show();

            }, 1000);
            
//            CKEDITOR.replace('ckeditor',
//                {
//                    extraPlugins: 'imageuploader'
//                }
//            );
        };

        $(document).on('click', '#announcementModel #btnsave', function() {

           
            var caption = $("#announcementform #caption");
            var activationdate = $("#announcementform #activationdate");
            var editorText = CKEDITOR.instances.ckeditor.getData();
            $("#announcementform #ckeditor").html(editorText);
           
            $("#announcementform span.help-block").remove();
          
           
            if($.trim(caption.val()) === "") {
                $(caption).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(caption.parent());
            } else {
                $(caption).parent().removeClass("has-error");
            }

            if($.trim(activationdate.val()) === "") {
                $(activationdate).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(activationdate.parent().parent());
            } else {
                $(activationdate).parent().parent().removeClass("has-error");
            }
 

            if($.trim(caption.val()) === "" || $.trim(activationdate.val()) === ""){
                 return false;
            }

            $("#announcementform #btnsave").button('loading'); 
            $("#announcementform #btncancel").button('loading'); 
            $.post( base_url+"admin/announcements/addeditannouncement", $("#announcementform").serialize(), function( response ) {
                $('#announcementform #btnsave').removeAttr("disabled");
                $('#announcementform #btncancel').removeAttr("disabled");
                
                $('#announcementform #btnsave').removeClass("disabled");
                $('#announcementform #btncancel').removeClass("disabled");
                $('#announcementform #btnsave').html("Save");
                $('#announcementform #btncancel').html("Cancel");
                if(response.success) {
                    if (response.data.success) {
                        $("#announcementModel").modal('hide');
                        bootbox.alert('Announcement update successfully.');
                        announcementsPage();
                    }
                    else{
                        
                         bootbox.dialog({
                                     message: "<span class='bigger-110'>"+response.data.message+"</span>",
                                     buttons: 			
                                     {
                                        "click" :
                                         {
                                             "label" : "OK",
                                             "className" : "btn-sm btn-success",
                                             callback: function() {
                                                 $("#announcementform #reset").val('yes'); 
                                                 $.post( base_url+"admin/announcements/addeditannouncement", $("#announcementform").serialize(), function( data ) {
                                                     if(data.success) {
                                                            $("#announcementModel").modal('hide');
                                                            bootbox.alert('Announcement update successfully.');
                                                            announcementsPage();
                                                     }
                                                     else{
                                                        $("#announcementform #reset").val(''); 
                                                        modaloverlap();
                                                        bootbox.alert(data.message);
                                                        return false;
                                                     }
                                                 }, 'json');
                                                  
                                            }
                                         },
                                         "cancel" :
                                         {
                                            "label" : "Cancel",
                                            "className" : "btn-sm  btn-primary falcon-warning-btn",
                                            callback: function() {
                                               modaloverlap();
                                            }
                                         }
                                     }
                                 });
                        
                        $('#announcementModel .status').html('<div class="alert alert-danger" >'+response.data.message+'</div>');
                    }
                    
                }
                else{
                     $('#announcementModel .status').html('<div class="alert alert-danger" >'+response.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#announcementModel #btncancel', function() {
            $("#announcementModel").modal('hide');
        });
       
    
}
]);

app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});

 