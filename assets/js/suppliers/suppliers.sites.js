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
        
   app.controller('SupplierSitesCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

          // filter
    $scope.addressFilter = {
        filtertext: '',
        state: '',
        supplierid : $('#supplierid').val() 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.selectedRows = [];
    $scope.docrowselected = false;
    $scope.addressGrid = {
        paginationPageSizes: [10, 25, 50,100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        multiSelect: true,
        columnDefs: [ 
             
            {   displayName:'Site Ref', 
                cellTooltip: true, 
                name: 'siteref',   
                width: 65 
            }, 
            {   displayName:'Street Address', 
                cellTooltip: true, 
                name: 'siteline2', 
                enableFiltering: false 
                 
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
            { 
                displayName:'Edit',
                name: 'id',
                cellTooltip: true,
                enableFiltering: false,  
                 enableSorting: false,
                visible :$('#edit_site').val()==='1'?true:false, 
                width: 60,  
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Edit</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" title="Edit" ><a  href="javascript:void(0)" ng-click="grid.appScope.editSite(row.entity, row.entity.id)"  ><i class = "fa fa-edit"></i></a></div>'
            },
            { 
                displayName:'Delete',
                name: 'delete',
                cellTooltip: true,
                enableFiltering: false, 
             
                visible :$('#delete_site').val()==='1'?true:false, 
                width: 60,
                enableSorting: false,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Delete</div>',
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteSite(row.entity)" ><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
             
             gridApi.selection.on.rowSelectionChanged($scope,function(row){
                $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
                if($scope.selectedRows.length === 0){
                    $scope.docrowselected = false;
                }
                else{
                    $scope.docrowselected = true;
                }
            });
            
            gridApi.selection.on.rowSelectionChangedBatch($scope,function(rows){
                $scope.selectedRows = $scope.gridApi.selection.getSelectedRows();
                if($scope.selectedRows.length === 0){
                    $scope.docrowselected = false;
                }
                else{
                    $scope.docrowselected = true;
                }
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
                supplierid : $('#supplierid').val()
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
            $http.get(base_url+'suppliers/loadsites?'+ qstring, {
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
           window.open(base_url+'suppliers/exportsites?'+qstring);
        };


       addressPage();
        
         $scope.deleteSite = function(entity) {

            bootbox.confirm("Are you sure to delete Site <b>"+entity.siteline2+"</b>", function(result) {
                if (result) {
                    $scope.overlay = true;
                    $.post( base_url+"suppliers/deletesuppliersite", { id:entity.id }, function( response ) {
                        if (response.success) {
                            addressPage();
                            bootbox.alert('site deleted successfully.');
                           
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.editSite = function(index, id) {
          
            $('#siteform').trigger("reset");
            $("#siteform .alert-danger").hide(); 
            $("#siteform span.help-block").remove();
            $("#siteform .has-error").removeClass("has-error");
            $('#siteform #btnsave').button("reset");
            $('#siteform #btncancel').button("reset");
            $('#siteform #btnsave').removeAttr("disabled");
            $('#siteform #btncancel').removeAttr("disabled");
            $("#siteform .close").css('display', 'block');
            $("#sitesModal h4.modal-title").html('Edit Site for ' + $("#supplierdetailform #companyname").val());
         
            $("#sitesModal").modal();
            $("#sitesModal #loading-img").show();
            $("#sitesModal #sitegriddiv").hide();
            $("#siteform #supplierid").val($("#supplierdetailform #supplierid").val()); 
            $("#siteform #siteid").val(id); 
            $("#siteform #mode").val('edit');  
            $("#siteform #labelid > option").each(function() {
             
                if($(this).attr('data-state') == index.sitestate){
                     $(this).removeAttr('style');
                }
                else{
                    $(this).css('display','none');
                }
                
            });
            $("#siteform #state").val(index.sitestate); 
            $("#siteform #labelid").val(index.labelid); 
        
            if(parseInt(index.isactive) === 1){
                $('#siteform input[name="isactive"]').prop('checked', true);
            }
            else{
                $('#siteform input[name="isactive"]').prop('checked', false);
            } 
            
            $.get( base_url+"suppliers/getavailablesites", {supplierid: $("#supplierdetailform #supplierid").val(),state : $("#siteform #state").val(), labelid:index.labelid}, function( response ) {
                
                if (response.success) {
                   
                    var optionhtml = '<option value ="">Select</option>';
                    
                    $.each( response.data, function( key, value ) {
                        optionhtml += '<option value="'+ value.labelid +'" data-siteline2="'+ value.siteline2 +'" data-state="'+ value.sitestate +'"  data-sitesuburb="'+ value.sitesuburb +'"  data-site="'+ value.address +'"  data-lat="'+ value.latitude_decimal +'"  data-long="'+ value.longitude_decimal +'">'+ value.site +'</option>';
                        
                    });
                     
                    $("#siteform #labelid").html(optionhtml); 
                    $("#sitesModal #loading-img").hide();
                    $("#sitesModal #sitegriddiv").show();
                    $("#siteform #labelid").val(index.labelid);
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');

        };
        
        $scope.addSite = function() { 
         
            
            $('#siteform').trigger("reset");
            $("#siteform .alert-danger").hide(); 
            $("#siteform span.help-block").remove();
            $("#siteform .has-error").removeClass("has-error");
            $('#siteform #btnsave').button("reset");
            $('#siteform #btncancel').button("reset");
            $('#siteform #btnsave').removeAttr("disabled");
            $('#siteform #btncancel').removeAttr("disabled");
            $("#siteform .close").css('display', 'block');
            $("#sitesModal h4.modal-title").html('Add Site for ' + $("#supplierdetailform #companyname").val());
            $("#sitesModal").modal();
            $("#sitesModal #loading-img").show();
            $("#sitesModal #sitegriddiv").hide();
            $("#siteform #supplierid").val($("#supplierdetailform #supplierid").val()); 
            $("#siteform #siteid").val(''); 
            $("#siteform #mode").val('add');  
            $('#siteform input[name="isactive"]').prop('checked', true);
             
            
            $.get( base_url+"suppliers/getavailablesites", {supplierid: $("#supplierdetailform #supplierid").val(),state : $("#siteform #state").val(), labelid:0}, function( response ) {
                
                if (response.success) {
                   
                    var optionhtml = '<option value ="">Select</option>';
                    
                    $.each( response.data, function( key, value ) {
                        optionhtml += '<option value="'+ value.labelid +'" data-siteline2="'+ value.siteline2 +'" data-state="'+ value.sitestate +'"  data-sitesuburb="'+ value.sitesuburb +'"  data-site="'+ value.address +'"  data-lat="'+ value.latitude_decimal +'"  data-long="'+ value.longitude_decimal +'">'+ value.site +'</option>';
                        
                    });
                     
                    $("#siteform #labelid").html(optionhtml); 
                    $("#sitesModal #loading-img").hide();
                    $("#sitesModal #sitegriddiv").show();
                    $("#siteform #labelid").val('');
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');

        };

        $(document).on('click', '#sitesModal #btnsave', function() {

           
            var labelid = $("#siteform #labelid"); 
            $("#siteform span.help-block").remove();

           
            if($.trim(labelid.val()) === "") {
                $(labelid).parent().parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(labelid.parent().parent());
                return false;
            } else {
                $(labelid).parent().parent().removeClass("has-error");
            }
  

            $("#siteform #btnsave").button('loading'); 
            $("#siteform #btncancel").button('loading'); 
            $.post( base_url+"suppliers/savesuppliersite", $("#siteform").serialize(), function( data ) {
                $('#siteform #btnsave').removeAttr("disabled");
                $('#siteform #btncancel').removeAttr("disabled");
                
                $('#siteform #btnsave').removeClass("disabled");
                $('#siteform #btncancel').removeClass("disabled");
                $('#siteform #btnsave').html("Save");
                $('#siteform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#sitesModal").modal('hide');
                    addressPage();
                    bootbox.alert('site update successfully.');
                   
                }
                else{
                     $('#sitesModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#sitesModal #btncancel', function() {
            $("#sitesModal").modal('hide');
        });
        
                
        $(document).on('change', '#siteform #state', function() {
            
            $("#siteform #btnsave").attr('disabled','disabled'); 
            $("#siteform #btncancel").attr('disabled','disabled');
            $.get( base_url+"suppliers/getavailablesites", {supplierid: $("#supplierdetailform #supplierid").val(),state : $("#siteform #state").val(), labelid:$("#siteform #labelid").val()}, function( response ) {
                $('#siteform #btnsave').removeAttr("disabled");
                $('#siteform #btncancel').removeAttr("disabled");
                 
                if (response.success) {
                   
                    var optionhtml = '<option value ="">Select</option>';
                    
                    $.each( response.data, function( key, value ) {
                        optionhtml += '<option value="'+ value.labelid +'" data-siteline2="'+ value.siteline2 +'" data-state="'+ value.sitestate +'"  data-sitesuburb="'+ value.sitesuburb +'"  data-site="'+ value.address +'"  data-lat="'+ value.latitude_decimal +'"  data-long="'+ value.longitude_decimal +'">'+ value.site +'</option>';
                        
                    });
                     
                    $("#siteform #labelid").html(optionhtml); 
     
                    $("#siteform #labelid").val('');
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
    
        });
      
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

 



     
        
       
 