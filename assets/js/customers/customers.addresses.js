/* global angular, base_url, bootbox, google */

"use strict";
//Google Map
var map;
var markers = [];
var gmarkers = [];
var directionsDisplay;
var directionsService;
var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var labelIndex = 0;

var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
app.controller('CustomerAddressCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

          // filter
    $scope.addressFilter = {
        filtertext: '',
        state: '',
        sitefm: '',
        status:'1'
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };
    
    $scope.edit_opt = $('#edit_address').val()==='1'?'':'disabled="disabled"';
    
    $scope.addressGrid = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [  
            { 
                displayName:'Edit',
                name: 'labelid',
                cellTooltip: true,
                enableFiltering: false,
     
                width: 40, 
                visible :$('#edit_address').val()==='1'?true:false, 
                headerCellClass : 'text-center', 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="{{row.entity.siteref}}"><a href="'+base_url+'customers/editaddress/{{ row.entity.labelid }}"><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Active',
                name: 'isactive', 
                width:55,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" name="isactive_{{row.entity.labelid}}" value="{{row.entity.labelid}}" '+$scope.edit_opt+'  data-id="{{row.entity.labelid}}"  class="chk_isactive"  ng-checked="row.entity.isactive == 1" /></div>'
                
            },
            {   displayName:'Site Ref', 
                cellTooltip: true, 
                name: 'siteref',  
                //pinnedLeft:true,
                width: 65 
            },
            { 
                displayName:'Company Name',
                cellTooltip: true,
                name: 'siteline1',
                width: 220
            },
            {   displayName:'Street Address', 
                cellTooltip: true, 
                name: 'siteline2', 
                enableFiltering: false ,
                width: 220
            },
            { 
                displayName:'Suburb',
                cellTooltip: true,
                name: 'sitesuburb',
                width: 120
            },
            {   displayName:'State', 
                cellTooltip: true, 
                name: 'sitestate', 
                enableFiltering: false, 
                width: 65 
            },
            {   displayName:'Post Code', 
                cellTooltip: true, 
                name: 'sitepostcode', 
                enableFiltering: false, 
                width: 90 
            },
            { displayName:'Site FM', 
                cellTooltip: true, 
                name: 'sitefm', 
                enableFiltering: false, 
                width: 150 
            },
            { displayName:'Site Contact', 
                cellTooltip: true, 
                name: 'sitecontact', 
                enableFiltering: false, 
                width: 150 
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
                addressPage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               addressPage();
             });
 	
         }
       };
       
        $scope.changeText = function() {
            addressPage();
        }; 
        
        $scope.changeFilters = function() {
           addressPage();
        };
        
        $scope.clearFilters = function() {
            
            $scope.addressFilter = {
                filtertext: '',
                state: '',
                sitefm: '',
                status:'1'
            };
            addressPage();
        };
        
        $scope.refreshAddressGrid = function() {
            
            addressPage();
        
        };
        
        var addressPage = function() {
           
            var params = {
                page  : paginationOptions.pageNumber,
                size  : paginationOptions.pageSize,
                field : paginationOptions.field,
                order : paginationOptions.sort
            }; 


            var qstring = $.param(params)+'&'+$.param($scope.addressFilter);

            $scope.overlay = true;
            $http.get(base_url+'customers/loadaddresses?'+ qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(response) {
                if (response.success === false) {
                    bootbox.alert(response.message);
                    
                }else{
                    $scope.addressGrid.totalItems = response.total;
                    $scope.addressGrid.data = response.data;  
                }
                $scope.overlay = false;
            });
       };
       
        $scope.exportToExcel = function(){
           var qstring = $.param($scope.addressFilter);
           window.open(base_url+'customers/exportaddress?'+qstring);
        };

         $scope.exportImportTemplate = function(){
           
           window.open(base_url+'customers/downloadaddresstemplate');
        };

       addressPage();
       
       $scope.importAddress = function() { 
            
            $("#importAddressModal #loading-img").show();
            $("#importAddressModal #sitegriddiv").hide();
            $('#importAddressform').trigger("reset");
            $("#importAddressform .alert-danger").hide(); 
            $("#importAddressform span.help-block").remove();
            $("#importAddressform .has-error").removeClass("has-error");
            $('#importAddressform #btnsave').button("reset");
            $('#importAddressform #btncancel').button("reset");
            $('#importAddressform #btnsave').removeAttr("disabled");
            $('#importAddressform #btncancel').removeAttr("disabled");
            $("#importAddressModal .close").css('display', 'block');
            $('#importAddressform #status').empty();
            var percentVal = '0';
            $('.progress-bar').attr('aria-valuenow',percentVal);
            $('.progress-bar').css('width',percentVal+"%");
            $('.sr-only').html(percentVal + "% Complete ");
            $("#importAddressModal").modal();
             setTimeout(function(){ 
                $("#importAddressModal #loading-img").hide();
                $("#importAddressModal #sitegriddiv").show();

            }, 1000);
        };
        
       
        $(document).on('change', '.chk_isactive', function() {
            var id = $(this).val();
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateAddress(id, 'isactive', value);

        }); 
    
        var updateAddress = function(id, field, value) {
 
            var params = { 
                id  : id,
                field: field,
                value: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'customers/updateaddress', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                     $scope.addressGrid.totalItems = 0;
                     $scope.addressGrid.data= [];
                     addressPage();
                }
                else {
                    bootbox.alert(data.message);
                    
                }
            });
        };

        
        var initialize =function() {
          
            var mapOptions = {
                zoom: 5
            };

            directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });
            directionsService = new google.maps.DirectionsService();  
            map = new google.maps.Map(document.getElementById('address-map'), mapOptions);
            directionsDisplay.setMap(map);

        };
        
        initialize();
        
        $scope.closeModal = function() {
             
            for(var t=0;t<gmarkers.length;t++) {
                gmarkers[t].setMap(null);
            }
            $("#addressesModel").modal('hide');
        };  
        
        var addMarker = function() {
            
            var bounds = new google.maps.LatLngBounds();
            
            var minLat = 0;
            var maxLat = 0;
            var minLng = 0;
            var maxLng = 0;
            if(markers.length > 0) {
                minLat = markers[0][2];
                maxLat = markers[0][2];
                minLng = markers[0][3];
                maxLng = markers[0][3];
            }
            var infoWindow = new google.maps.InfoWindow(), marker, i;
            for(i = 0; i < markers.length; i++ ) {
                
                if(minLat>markers[i][2]){
                    minLat = markers[i][2];
                }
                if(maxLat<markers[i][2]){
                    maxLat = markers[i][2];
                }
                if(minLng>markers[i][3]){
                    minLng = markers[i][3];
                }
                if(maxLng<markers[i][3]){
                    maxLng = markers[i][3];
                }
                
                
                var position = new google.maps.LatLng(markers[i][2], markers[i][3]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    //label: labels[labelIndex++ % labels.length],
                    title: markers[i][0]
                });
                
                gmarkers.push(marker);

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infoWindow.setContent(markers[i][1]);
                        infoWindow.open(map, marker);
                    };
                })(marker, i));
        
                map.fitBounds(bounds); 
                
         
            }
            
            if(markers.length > 0) {
                // Automatically center the map fitting all markers on the screen
                var Centrelat = (maxLat + minLat) / 2;
                var Centrelong = (maxLng + minLng ) / 2;
                var position = new google.maps.LatLng(Centrelat, Centrelong);
                map.setCenter(position);
            }
            
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                this.setZoom(3);
                google.maps.event.removeListener(boundsListener);
            });
        };
        
        $scope.showOnMap = function() {
        
            $('.map-overlay').show();
            $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
            if($scope.selectedRows.length === 0){
                bootbox.alert('Please select address for show on map.');
                return false;
            } 
            markers = [];
            labelIndex = 0;
            var myLatlng, latitude, longitude, formatted_address, title;
            $scope.selectedRows.forEach(function(rowEntity) {
                latitude = parseFloat(rowEntity.latitude_decimal);
                longitude = parseFloat(rowEntity.longitude_decimal);
                formatted_address = urldecode(rowEntity.siteaddress);
                title = rowEntity.sitesuburb;
                if(!isNaN(latitude) && !isNaN(longitude)) {
                    var marker = [title, formatted_address, latitude, longitude];
                    markers.push(marker);
                }
                 
            }); 
         
            if(markers.length == 0) {
                $("#addressesModel").modal('hide');
                bootbox.alert('No latitude and logitude available for this address.');
            } else {
                  
                $("#addressesModel").modal();
                 setTimeout(function(){ 
                    var mapOptions = {
                        zoom: 5
                    };

                    directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });

                    directionsService = new google.maps.DirectionsService();  
                    map = new google.maps.Map(document.getElementById('address-map'), mapOptions);
                    directionsDisplay.setMap(map);
                    $('.map-overlay').hide();
                    addMarker();
                }, 1000);
                
            }
        }; 

    }
]);
 
app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    };
});
 
 
$(document).ready(function() {
   
    $(document).on('click', '#importAddressModal #btncancel', function() {
            $("#importAddressModal").modal('hide');
        });
    $("#importAddressModal #btnsave").on('click', function() {
         
            var fileup = $("#importAddressform #importfile"); 
            $("#importAddressform span.help-block").remove();
          

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
            $("#importAddressform #btnsave").button('loading'); 
            $("#importAddressform #btncancel").button('loading'); 
            return true;
        });
    
    if (typeof $.fn.ajaxForm === "function") {
       
        $('#importAddressform').ajaxForm({
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
                    $('#importAddressform #btnsave').button("reset");
                    $('#importAddressform #btncancel').button("reset");
                    $('#importAddressform #btnsave').removeAttr("disabled");
                    $('#importAddressform #btncancel').removeAttr("disabled");
                   var out2 = $.parseJSON(xhr.responseText);
                    if(out2.success){
                        
                        $('#importAddressform #status').html('<div class="alert alert-success" >'+out2.message+'</div>');
                        setTimeout(function(){ 
                            $("#importAddressModal").modal('hide');
                            $( "#CustomerAddressCtrl .btn-refresh" ).click();
                            //document.location.reload(); 
                        }, 500);
                    }
                    else{
                        $('#importAddressform #status').html('<div class="alert alert-danger" >'+out2.message+'</div>');
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