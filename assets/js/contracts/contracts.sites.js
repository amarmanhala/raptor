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
        
   app.controller('ContractSitesCtrl', [
        '$scope', '$http', 'uiGridConstants', '$q', function($scope, $http, uiGridConstants, $q) {

          // filter
    $scope.addressFilter = {
        filtertext: '',
        suburb: '',
        state: '',
        contractid : $("#contractdetailform #contractid").val() 
    };

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.selectedRows = [];
    $scope.docrowselected = false;
    $scope.edit_opt = $('#edit_site').val()==='1'?'':'disabled="disabled"';
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
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                name: 'isactive', 
                width:55,
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Active</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center"><input type="checkbox" value="{{row.entity.id}}" '+$scope.edit_opt+'  data-contractid="{{row.entity.contractid}}"  class="chk_isactive"  ng-checked="row.entity.isactive == 1" /></div>'
            },
            { 
                displayName:'Edit',
                name: 'id',
                cellTooltip: true,
                enableFiltering: false,  
                 enableSorting: false,
                visible :$('#edit_site').val()==='1'?true:false, 
                width: 40,  
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
                cellTemplate: '<div class="ui-grid-cell-contents  text-center"><a title = "Delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.deleteSite(row.entity)" ><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                suburb: '',
                state: '',
                contractid : $("#contractdetailform #contractid").val() 
            };
            $('#ContractSitesCtrl .selectpicker').selectpicker('deselectAll');
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
            $http.get(base_url+'contracts/loadcontractsites?'+ qstring, {
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
           window.open(base_url+'contracts/exportcontractsites?'+qstring);
        };


      // addressPage();
        $scope.$watch(function() {
            $('.selectpicker').each(function() {
                $(this).selectpicker('refresh');
            });
        });
        $(document).on('change', '#addressGrid .chk_isactive', function() {
            var id = $(this).val();
            var contractid = $(this).attr('data-contractid');
            var value;
            if($(this).is(":checked")) {
                value = 1;
            } else {
                value = 0;
            }
            updateSiteStatus(id, contractid, value);

        }); 
    
        var updateSiteStatus = function(id, contractid, value) {
 
            var params = { 
                id  : id,
                contractid: contractid,
                isactive: value
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.post(base_url+'contracts/updatesitestatus', qstring, {
                headers : {
                    "content-type" : "application/x-www-form-urlencoded"
                }
            }).success(function(data) {
                $scope.overlay = false;
                if (data.success) {
                    $.each($scope.addressGrid.data, function( key, val ) {
                        if(parseInt(val.id) === parseInt(id)){
                            if(parseInt(val.status) === 1){
                                $scope.addressGrid.data[key].isactive = 0;
                            }
                            else{
                                $scope.addressGrid.data[key].isactive = 1;
                            }
                            return;
                        }
                        
                    });
                     
                }
                else {
                    bootbox.alert(data.message);
                    
                }
            });
        };
         
        $scope.deleteSite = function(entity) {

            bootbox.confirm("Delete site <b>"+entity.siteline2+"</b> ?", function(result) {
                if (result) {
                    $scope.overlay = true;
                    $.post( base_url+"contracts/deletecontractsite", { id:entity.id, contractid:entity.contractid }, function( response ) {
                        $scope.overlay = false;
                        if (response.success) { 
                         
                            bootbox.alert('Site deleted successfully.');
                            addressPage();
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        
        $scope.updateSiteLatLong = function() {

            if($.trim($('#siteform #latitude').val()) === '' || $.trim($('#siteform #longitude').val()) === ''|| $.trim($('#siteform #labelid').val()) === ''){
                bootbox.alert('No latitude and logitude available for this address.');
                return false;
            } 
            
            bootbox.confirm("Update site GPS for <b>"+$.trim($('#siteform #site').val())+"</b> ?", function(result) {
                if (result) {
                    $scope.overlay = true;
                    $.post( base_url+"contracts/updatesitelatlong", { labelid:$('#siteform #labelid').val(), latitude:$('#siteform #latitude').val(), longitude:$('#siteform #longitude').val()}, function( response ) {
                        $scope.overlay = false;
                        if (response.success) { 
                            bootbox.alert('GPS saved successfully.');
                           
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
         var deferred;  
     
        //Any function returning a promise object can be used to load values asynchronously
        $scope.getSites = function(val) {

            deferred = $q.defer(); 
            $http.get(base_url+'ajax/loadsitesearch', {
                params: {
                        search: val,
                        state : $.trim($('#siteform #state').val())
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
       
        $scope.onSiteSelect = function ($item, $model, $label) {
             
            $scope.sitesearch = $item.site;
             
            $("#siteform #labelid").val($item.labelid);
            $("#siteform #selectlabelid").val($item.labelid);
            $("#siteform #state").val($item.sitestate);
            $("#siteform #sitesuburb").val($item.sitesuburb);
            $("#siteform #site").val($item.site);
            $("#siteform #latitude").val($item.latitude_decimal);
            $("#siteform #longitude").val($item.longitude_decimal);
             
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
            $("#sitesModal h4.modal-title").html('Edit Site for ' + $("#contractdetailform #name").val());
         
            $("#sitesModal").modal();
            $("#sitesModal #loading-img").show();
            $("#sitesModal #sitegriddiv").hide();
            $("#siteform #contractid").val($("#contractdetailform #contractid").val()); 
            $("#siteform #siteid").val(id); 
            $("#siteform #mode").val('edit');  
            
            
            $("#siteform #groupid").val('');
            $scope.sitegroupids = index.sitegroupids;
            $("#siteform #groupid").selectpicker();
            $("#siteform #labelid").val(index.labelid);
           
            $("#siteform #state").val(index.sitestate);
            $("#siteform #sitesuburb").val(index.sitesuburb);
            $("#siteform #site").val(index.site);
            $("#siteform #latitude").val(index.latitude_decimal);
            $("#siteform #longitude").val(index.longitude_decimal); 
            
            $.get( base_url+"contracts/getavailablesites", {contractid: $("#contractdetailform #contractid").val(),state : $("#siteform #state").val(), labelid:$("#siteform #labelid").val()}, function( response ) {
                
                if (response.success) {
                   
                    var optionhtml = '<option value ="">Select</option>';
                    
                    $.each( response.data, function( key, value ) {
                        optionhtml += '<option value="'+ value.labelid +'" data-siteline2="'+ value.siteline2 +'" data-state="'+ value.sitestate +'"  data-sitesuburb="'+ value.sitesuburb +'"  data-site="'+ value.address +'"  data-lat="'+ value.latitude_decimal +'"  data-long="'+ value.longitude_decimal +'">'+ value.site +'</option>';
                        
                    });
                     
                    $("#siteform #selectlabelid").html(optionhtml); 
                    $("#sitesModal #loading-img").hide();
                    $("#sitesModal #sitegriddiv").show();
                    $("#siteform #selectlabelid").val(index.labelid);
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
            $("#sitesModal h4.modal-title").html('Add Site for ' + $("#contractdetailform #name").val());
            $("#sitesModal").modal();
            $("#sitesModal #loading-img").show();
            $("#sitesModal #sitegriddiv").hide();
            $("#siteform #contractid").val($("#contractdetailform #contractid").val()); 
            $("#siteform #siteid").val(''); 
            $("#siteform #mode").val('add');  
            $('#siteform input[name="isactive"]').prop('checked', true);
           
            $("#siteform #labelid").val('');
            $("#siteform #selectlabelid").val('');
            $("#siteform #state").val('');
            $("#siteform #sitesuburb").val('');
            $("#siteform #site").val('');
            $("#siteform #latitude").val('');
            $("#siteform #longitude").val('');
            $("#siteform #groupid").val('');
            $("#siteform #groupid").selectpicker('refresh');
            
            $.get( base_url+"contracts/getavailablesites", {contractid: $("#contractdetailform #contractid").val(),state : $("#siteform #state").val(), labelid:0}, function( response ) {
                
                if (response.success) {
                   
                    var optionhtml = '<option value ="">Select</option>';
                    
                    $.each( response.data, function( key, value ) {
                        optionhtml += '<option value="'+ value.labelid +'" data-siteline2="'+ value.siteline2 +'" data-state="'+ value.sitestate +'"  data-sitesuburb="'+ value.sitesuburb +'"  data-site="'+ value.address +'"  data-lat="'+ value.latitude_decimal +'"  data-long="'+ value.longitude_decimal +'">'+ value.site +'</option>';
                        
                    });
                     
                    $("#siteform #selectlabelid").html(optionhtml); 
                    $("#sitesModal #loading-img").hide();
                    $("#sitesModal #sitegriddiv").show();
                    $("#siteform #selectlabelid").val('');
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
            
            
        };

        $(document).on('click', '#sitesModal #btnsave', function() {

           
            var labelid = $("#siteform #labelid"); 
             
            if($.trim(labelid.val()) === "") {
                $('#sitesModal .status').html('<div class="alert alert-danger" >Select Site Address.</div>');
                 
                return false;
            } else {
                $('#sitesModal .status').html('');
                $(labelid).parent().parent().removeClass("has-error");
            }
  

            $("#siteform #btnsave").button('loading'); 
            $("#siteform #btncancel").button('loading'); 
            $.post( base_url+"contracts/savecontractsite", $("#siteform").serialize(), function( data ) {
                $('#siteform #btnsave').removeAttr("disabled");
                $('#siteform #btncancel').removeAttr("disabled");
                
                $('#siteform #btnsave').removeClass("disabled");
                $('#siteform #btncancel').removeClass("disabled");
                $('#siteform #btnsave').html("Save");
                $('#siteform #btncancel').html("Cancel");
                if(data.success) {
                 
                    $("#sitesModal").modal('hide');
                    addressPage();
                    bootbox.alert(data.message);
                   
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
            $.get( base_url+"contracts/getavailablesites", {contractid: $("#contractdetailform #contractid").val(),state : $("#siteform #state").val(), labelid:$("#siteform #labelid").val()}, function( response ) {
                $('#siteform #btnsave').removeAttr("disabled");
                $('#siteform #btncancel').removeAttr("disabled");
                 
                if (response.success) {
                   
                    var optionhtml = '<option value ="">Select</option>';
                    
                    $.each( response.data, function( key, value ) {
                        optionhtml += '<option value="'+ value.labelid +'" data-siteline2="'+ value.siteline2 +'" data-state="'+ value.sitestate +'"  data-sitesuburb="'+ value.sitesuburb +'"  data-site="'+ value.address +'"  data-lat="'+ value.latitude_decimal +'"  data-long="'+ value.longitude_decimal +'">'+ value.site +'</option>';
                        
                    });
                     
                    $("#siteform #selectlabelid").html(optionhtml); 
                    $("#siteform #selectlabelid").val('');
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
            
            
        });
      
        $(document).on('change', '#siteform #selectlabelid', function() {
   
            if($(this).val() !== ''){
                
                $("#siteform #labelid").val($(this).val());
                $("#siteform #state").val($('#siteform #selectlabelid option:selected').attr('data-state'));
                $("#siteform #sitesuburb").val($('#siteform #selectlabelid option:selected').attr('data-sitesuburb'));
                $("#siteform #site").val($('#siteform #selectlabelid option:selected').attr('data-site'));
                $("#siteform #latitude").val($('#siteform #selectlabelid option:selected').attr('data-lat'));
                $("#siteform #longitude").val($('#siteform #selectlabelid option:selected').attr('data-long'));
            }
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
         
            if(markers.length === 0) {
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
        
        
        $scope.showMap = function() {
         
            if($.trim($('#siteform #latitude').val()) === '' || $.trim($('#siteform #longitude').val()) === ''){
                bootbox.alert('No latitude and logitude available for this address.');
                return false;
            } 
            $('.map-overlay').show();
            markers = [];
            labelIndex = 0;
            var myLatlng, latitude, longitude, formatted_address, title;
          
            latitude = parseFloat($.trim($('#siteform #latitude').val()));
            longitude = parseFloat($.trim($('#siteform #longitude').val()));
            formatted_address = $.trim($('#siteform #site').val());
            title = $.trim($('#siteform #sitesuburb').val());
            if(!isNaN(latitude) && !isNaN(longitude)) {
                var marker = [title, formatted_address, latitude, longitude];
                markers.push(marker);
            }
           
            if(markers.length === 0) {
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

 

var getGPS = function() {
            
    var siteline1 = $.trim($('#siteform #site').val()); 
    
    var geocoder = new google.maps.Geocoder();
    //var address = '100 Main Street Burwood NSW 2136 Australia';
    if(siteline1 !== '' ) {
        $("span:eq(0)", "#siteform #getgps").css("display", 'block');
        $("span:eq(1)", "#siteform #getgps").css("display", 'none');
        //$("#siteform #latitude").val('');
        //$("#siteform #longitude").val(''); 
        geocoder.geocode({ 'address': siteline1 }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK)
          {
            $("span:eq(0)", "#siteform #getgps").css("display", 'none');
            $("span:eq(1)", "#siteform #getgps").css("display", 'block');
            $("#siteform #latitude").val(results[0].geometry.location.lat());
            $("#siteform #longitude").val(results[0].geometry.location.lng());
          }
        }); 
    } else {
        bootbox.alert('Please select Site address fields for get GPS latitude, longitude');
    }
};

     
        
       
 