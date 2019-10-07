"use strict";
if($("#contact_ctrl").length) {
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
    app.controller('ContactCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

         // filter
         $scope.filterOptions = {
             filterText: ''
         };

       var paginationOptions = {
         pageNumber: 1,
         pageSize: 10,
         sort: null,
        field: null,
        description_filter: '',
        type_filter: ''
       };

       $scope.gridOptions = {
         paginationPageSizes: [10, 20, 30],
         paginationPageSize: 10,
         useExternalPagination: true,
         useExternalSorting: true,
         enableColumnResizing: true,
         enableColumnMenus: false,
         //enableFiltering: true,
         columnDefs: [
                         { 
                             displayName:'Name',
                             cellTooltip: true,
                             name: 'firstname'
                         },
                         { 
                             displayName:'Suburb',
                             cellTooltip: true,
                             name: 'suburb',
                             width: 90
                         },
                         { displayName:'State', cellTooltip: true, name: 'state', enableFiltering: false, width: 65 },
                         { displayName:'Post Code', cellTooltip: true, name: 'postcode', enableFiltering: false, width: 90 },
                         { displayName:'Mobile', cellTooltip: true, name: 'mobile', enableFiltering: false, width: 110 },
                         { displayName:'Email', cellTooltip: true, name: 'etp_email', enableFiltering: false },
                         { displayName:'Access level', cellTooltip: true, name: 'etp_accesslevel', enableFiltering: false, width: 110 },
                         { displayName:'Action', field:'links', width: 80, enableSorting: false, enableFiltering: false, cellTemplate: '<div ng-bind-html="COL_FIELD | trusted"></div>' },

         ],
         onRegisterApi: function(gridApi) {
             $scope.gridApi = gridApi;
             
             gridApi.core.on.sortChanged($scope, function(grid, sortColumns) {
                if (sortColumns.length == 0) {
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

             /*$scope.gridApi.core.on.filterChanged($scope, function() {
                    paginationOptions.description_filter = $scope.gridOptions.columnDefs[0].filter.term;
                    paginationOptions.type_filter = $scope.gridOptions.columnDefs[1].filter.term;
                    setTimeout(getPage(), 800);
             });*/	
         }
       };

       var getPage = function() {
             /*if(paginationOptions.description_filter == null) {
                 paginationOptions.description_filter = '';
             }
             if(paginationOptions.type_filter == null) {
                 paginationOptions.type_filter = '';
             }*/
             if(paginationOptions.sort == null) {
                 paginationOptions.sort = '';
             }
             if(paginationOptions.field == null) {
                 paginationOptions.field = '';
             }
             //var url = base_url+'contacts/loadcontacts?page='+paginationOptions.pageNumber+'&size='+paginationOptions.pageSize+'&field='+paginationOptions.field+'&order='+paginationOptions.sort+'&description_filter='+paginationOptions.description_filter+'&type_filter='+paginationOptions.type_filter;
             var url = base_url+'contacts/loadcontacts?page='+paginationOptions.pageNumber+'&size='+paginationOptions.pageSize+'&field='+paginationOptions.field+'&order='+paginationOptions.sort;

             $http.get(url)
             .success(function (data) {
                   $scope.gridOptions.totalItems = data.total;
                   $scope.gridOptions.data = data.data;
             });
       };

       getPage();
     }
     ]);

     app.filter('trusted', function ($sce) {
         return function (value) {
           return $sce.trustAsHtml(value);
         }
     });
}



$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {         
      
        var validator = $("#contact_form").validate({
            rules: {
                phone: {  
                     regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                },
                mobile: { 
                     regex: /^[0-9]{4} [0-9]{3} [0-9]{3}$/
                },
                nonbillable: { 
                    required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }, 
                }
            },
            success: function(label) {
                label.remove();
            },
            errorPlacement: function( error, element ) {
              error.appendTo(element.parent().find("span.with-errors"));
            },
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parent().addClass("has-error");	
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parent().removeClass("has-error");	
            },
            submitHandler: function() {
                return true;
            }
        }); 

      }
       
});
 
 var readProfileURL = function(input) {
	
    var ext = $(input).val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
        $(input).val('');
       
        bootbox.alert('invalid file format!');
        return false;
    }
 
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $('#selected-profile').attr('src',e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
   }
};