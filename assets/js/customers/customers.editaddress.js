"use strict";
    var app = angular.module('app', ['ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch', 
app.controller('AttributeCtrl', [
        '$scope', '$http', 'uiGridConstants', function($scope, $http, uiGridConstants) {

    var paginationOptions = {
        pageNumber: 1,
        pageSize: 25,
        sort: '',
        field: '' 
    };

    $scope.gridOptions = {
        paginationPageSizes: [10, 25, 50, 100],
        paginationPageSize: 25,
        useExternalPagination: true,
        useExternalSorting: true,
        enableColumnMenus: false,
        columnDefs: [ 
            {   displayName:'Attribute Name', 
                cellTooltip: true, 
                name: 'name',
                width: 200
            },
            { 
                displayName:'Value',
                cellTooltip: true,
                name: 'value',
                cellTemplate: '<div class="ui-grid-cell-contents" ng-if="row.entity.edit_attr == 0 ">{{row.entity.value}}</div><div class="ui-grid-cell-contents" ng-if="row.entity.edit_attr == 1 "><input type="text" value="{{row.entity.value}}" attribute-id="{{row.entity.id}}" attribute-type="{{row.entity.type}}" class="attribute_value" ng-class="row.entity.type == \'int\' ? \'allownumericwithoutdecimal\' : \'\'" /></div>'
            },
            {   displayName:'Active', 
                cellTooltip: true,
                enableSorting: false,
                headerCellClass: 'text-right',
                name: 'status', 
                width: 100,
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.edit_attr == 0 "><input type="checkbox" value="{{row.entity.id}}"  class="attribute_active" ng-disabled="true" ng-checked="row.entity.status==1" /></div><div class="ui-grid-cell-contents text-center" ng-if="row.entity.status == 0 && row.entity.edit_attr == 1 "><input type="checkbox" value="{{row.entity.id}}"  class="attribute_active" /></div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.status == 1 && row.entity.edit_attr == 1"><input type="checkbox"  checked="checked" value="{{row.entity.id}}" class="attribute_active" /></div>'
            },
            { 
                displayName:'Action',
                cellTooltip: true,
                enableSorting: false,
                name: 'id',
                width: 100, 
                headerCellClass : 'text-center', 
                headerCellTemplate: '<div class="ui-grid-cell-contents text-center">Action</div>',
                cellTemplate: '<div class="ui-grid-cell-contents text-center" ng-if="row.entity.delete_attr != 1">&nbsp;</div><div class="ui-grid-cell-contents  text-center" ng-if="row.entity.delete_attr == 1"><a title = "delete" class= "btn btn-link btn-xs delete-btn" ng-click="grid.appScope.attributeValueDelete(row.entity)"><i class= "fa fa-minus-circle" style="font-size:20px;color:#dd4b39;"></i></a></div>'
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
                attributePage();
            });
            
             gridApi.pagination.on.paginationChanged($scope, function (newPage, pageSize) {
               paginationOptions.pageNumber = newPage;
               paginationOptions.pageSize = pageSize;
               attributePage();
             });
 	
         }
       };
        
        $scope.refreshAttributeGrid = function() {
            attributePage();
        };
        
        $(document).on('change', '.attribute_active', function() {
            var id = $(this).val();
            var status;
            if($(this).is(":checked")) {
                status = 1;
            } else {
                status = 0;
            }
            
            var attributeData = {
                id:id,
                status:status
            };
            
            updateAddressAttributeValue(attributeData);
        });
        
        $(document).on('change', '.attribute_value', function() {
            var value = $(this).val();
            var id = $(this).attr('attribute-id');
            var type = $(this).attr('attribute-type');
            var attributeData = {
                id:id,
                value:value,
                type:type
            };
                        
            updateAddressAttributeValue(attributeData);
        });
        
        var updateAddressAttributeValue = function(attributeData) {
             
            $scope.overlay = true;
            $.get( base_url+"customers/updateaddressattributevalue", attributeData, function( response ) {
                if (response.success) {
                     attributePage();
                }
                else {
                    bootbox.alert(response.message);
                }
            });
        }
        
        $scope.attributeValueDelete = function(entity) {

            bootbox.confirm("Are you sure to delete this record <b>"+entity.name+" : "+entity.value+"</b>", function(result) {
                if (result) {
                    
                    $.get( base_url+"customers/deleteaddressattributevalue", { id:entity.id }, function( response ) {
                        if (response.success) {
                             attributePage();
                        }
                        else {
                            bootbox.alert(response.message);
                        }
                    });
                }
            });
        };
        
        var attributePage = function() {
           
            var params = {
                page    : paginationOptions.pageNumber,
                size    : paginationOptions.pageSize,
                field   : paginationOptions.field,
                order   : paginationOptions.sort,
                labelid : $("#addressForm #labelid").val()
            }; 

            var qstring = $.param(params);

            $scope.overlay = true;
            $http.get(base_url+'customers/loadaddressattributesvalues?'+ qstring, {
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

        attributePage();

    }
]);


app.filter('trusted', function ($sce) {
    return function (value) {
      return $sce.trustAsHtml(value);
    }
});


$( document ).ready(function() {

        
    if (typeof $.fn.validate === "function") {         
      
        $("#addressForm").validate({
            rules: {
                siteref: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                customername: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                siteline1: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                sitesuburb1: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                sitestate: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                sitepostcode: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                latitude_decimal: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                longitude_decimal: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                contactid: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                sitecontactid: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                }
                
            },
            errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                }
                else{
                   $(e).parent().parent().removeClass('has-info').addClass('has-error');
                } 
            },
            success: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().parent().removeClass("has-error");
                }
               $(e).remove();
            },

            errorPlacement: function (error, element) {
                if(element.parent().is('.input-group')) {
                    error.appendTo(element.parent().parent());
                }
                else error.appendTo(element.parent());
            },
            unhighlight: function(e, errorClass, validClass) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().removeClass("has-error");
                }
            },
            submitHandler: function() {
                return true;
            }
        }); 
        
        $("#addContactForm").validate({
            rules: {
                firstname: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                phone: {  
                    regex: /^[0-9]{2} [0-9]{4} [0-9]{4}$/
                },
                mobile: { 
                    regex: /^[0-9]{4} [0-9]{3} [0-9]{3}$/
                },
                email: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    },
                    validemail:/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
                }
                
            },
            errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                }
                else{
                   $(e).parent().parent().removeClass('has-info').addClass('has-error');
                } 
            },
            success: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().parent().removeClass("has-error");
                }
               $(e).remove();
            },

            errorPlacement: function (error, element) {
                if(element.parent().is('.input-group')) {
                    error.appendTo(element.parent().parent());
                }
                else error.appendTo(element.parent());
            },
            unhighlight: function(e, errorClass, validClass) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().removeClass("has-error");
                }
            },
            submitHandler: function() {
                $("span:eq(0)", "#addContactForm #modalsave").css("display", 'block');
                $("span:eq(1)", "#addContactForm #modalsave").css("display", 'none');
                $("#addContactForm #cancel").button('loading');
                
                $("#addContactForm #contactModalErrorMsg").hide(); 
                $("#addContactForm #contactModalSuccessMsg").hide();
                $.post( base_url+"customers/savecontact", $('#addContactForm').serialize(), function( response ) {
                    $("span:eq(0)", "#addContactForm #modalsave").css("display", 'none');
                    $("span:eq(1)", "#addContactForm #modalsave").css("display", 'block');
                    $("#addContactForm #cancel").button('reset');
                    if (response.success) {
                        if (response.data.success) {
                            $("#addContactForm #contactModalSuccessMsg").html(response.message);
                            $("#addContactForm #contactModalSuccessMsg").show();
                            
                            if(contactType === 'sitefm') {
                                var option = option + '<option value="'+ response.data.data.contactid +'" data-phone="'+ response.data.data.phone +'" data-mobile="'+ response.data.data.mobile +'" data-email="'+ response.data.data.email +'">'+ response.data.data.contactname +'</option>';
                                $("#addressForm #contactid").append(option);
                                $("#addressForm #contactid").val(response.data.data.contactid);
                                $("#addressForm #contactid").trigger('change');
                            } else {
                                var option = option + '<option value="'+ response.data.data.contactid +'" data-phone="'+ response.data.data.phone +'" data-mobile="'+ response.data.data.mobile +'" data-email="'+ response.data.data.email +'" data-contact="'+ response.data.data.contactname +'">'+ response.data.data.contactname +'</option>';
                                $("#addressForm #sitecontactid").append(option);
                                $("#addressForm #sitecontactid").val(response.data.data.contactid);
                                $("#addressForm #sitecontactid").trigger('change');
                            }
                            $("#addContactModal").modal('hide');
                            modaloverlap();
                        }
                        else{
                            $("#addContactForm #contactModalErrorMsg").html(response.data.message);
                            $("#addContactForm #contactModalErrorMsg").show();
                        }
                    }
                    else {
                        bootbox.alert(response.message);
                    }
                    
                });
                
                return false;
            }
        });
         
        
        $("#address_attribute_form").validate({
            rules: {
                attribute: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                },
                value: {  
                     required: {
                        depends:function(){
                            $(this).val($.trim($(this).val()));
                            return true;
                        }   
                    }
                }
            },
            errorElement: "span",
            errorClass: "help-block error",
            highlight: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                }
                else{
                   $(e).parent().parent().removeClass('has-info').addClass('has-error');
                } 
            },
            success: function (e) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().parent().removeClass("has-error");
                }
               $(e).remove();
            },

            errorPlacement: function (error, element) {
                if(element.parent().is('.input-group')) {
                    error.appendTo(element.parent().parent());
                }
                else error.appendTo(element.parent());
            },
            unhighlight: function(e, errorClass, validClass) {
                if($(e).parent().is('.input-group')) {
                    $(e).parent().parent().removeClass("has-error");
                }
                else{
                    $(e).parent().removeClass("has-error");
                }
            },
            submitHandler: function() {
                $("#address_attribute_form #labelid").val($("#addressForm #labelid").val());
                $("#address_attribute_form #modalsave").button('loading');
                $("#address_attribute_form #cancel").button('loading');
                
                $("#address_attribute_form .alert-danger").hide(); 
                $.post( base_url+"customers/createaddressattributevalue", $('#address_attribute_form').serialize(), function( response ) {
                    $("#address_attribute_form #modalsave").button('reset');
                    $("#address_attribute_form #cancel").button('reset');
                    if (response.success) {
                        if (response.data.success) {
                            $("#addressAttributeModal").modal('hide');
                            $('#address_attribute_form').trigger("reset");
                            $("#AttributeCtrl .btn-refresh").trigger('click');
                            modaloverlap();
                        }
                        else{
                            $("#address_attribute_form .alert-danger").html(response.data.message); 
                            $("#address_attribute_form .alert-danger").show(); 
                        }
                    }
                    else {
                        bootbox.alert(response.message);
                    }
                    
                });
                
                return false;
            }
        }); 

    }
    
    
    var initialize =function() {
            
            /*var latlng = new google.maps.LatLng(-34.397, 150.644);
            var mapOptions = {
                zoom: 8,
                center: latlng
            };*/
            
            var mapOptions = {
                zoom: 5
            };

            directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });

            directionsService = new google.maps.DirectionsService();  
            map = new google.maps.Map(document.getElementById('address-map'), mapOptions);
            directionsDisplay.setMap(map);
    };
        
        
         initialize();
        
         
      $("#addattribute").on('click', function() {
        $('#address_attribute_form').trigger("reset");
        $("#address_attribute_form .alert-danger").hide(); 
        $("#address_attribute_form span.help-block").remove();
        $("#address_attribute_form .has-error").removeClass("has-error");
        $('#address_attribute_form #modalsave').button("reset");
        $('#address_attribute_form #cancel').button("reset");
        $("#address_attribute_form .close").css('display', 'block');
        $("#addressAttributeModal").modal();
    });

    $(document).on('click', "#address_attribute_form #cancel, #address_attribute_form .close", function() {
        $("#addressAttributeModal").modal('hide');
        modaloverlap();
    }); 
    
    
    $("#btnfromaddress").on('click', function() {
        if(parseInt($('#addressForm #sitecontactid').val()) >0 ) {
            bootbox.alert('Site contact already exists');
            return false;
        }
        
        bootbox.confirm("Create a site contact for this address?", function(result) {
            if (result) {
                $("#addContactForm #firstname").val($('#addressForm #sitesuburb').val());
                $("#addContactForm #position").val('Site contact');
                $("#addContactForm #street1").val($('#addressForm #siteline2').val());
                $("#addContactForm #suburb").val($('#addressForm #sitesuburb').val());
                $("#addContactForm #state").val($('#addressForm #sitestate').val());
                $("#addContactForm #postcode").val($('#addressForm #sitepostcode').val());
                $("#addContactForm #territory").val($('#addressForm #territory').val());
                $("#addContactForm #sitephone").val($('#addressForm #phone').val());
                $("#addContactForm #sitemobile").val($('#addressForm #mobile').val());
                $("#addContactForm #siteemail").val($('#addressForm #email').val());
                $("#addContactForm #role").val('site contact');
                $("#addContactForm #fromaddress").val('yes');
                $("#addContactForm #labelid").val($('#addressForm #labelid').val());
            }
        });
    });
        
});

var changeAttribute = function(elm) {
    if($(elm).val() === '') {
        return false;
    };
    
    var type = $('option:selected', elm).attr('data-type');
    $("#address_attribute_form #type").val(type);
    if(type === 'int') {
         $("#address_attribute_form #value").val('');
        $("#address_attribute_form #value").addClass('allownumericwithoutdecimal');
    } else {
       $("#address_attribute_form #value").removeClass('allownumericwithoutdecimal'); 
    }
};

//Google Map
        var map;
        var markers = [];
        var gmarkers = [];
        var directionsDisplay;
        var directionsService;
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var labelIndex = 0;

    var closeModal = function() {
            for(var t=0;t<gmarkers.length;t++) {
                gmarkers[t].setMap(null);
            }
            
            $("#addressesModel").modal('hide');
        };    
        
        var addMarker = function() {
            var bounds = new google.maps.LatLngBounds();
            var infoWindow = new google.maps.InfoWindow(), marker, i;
            for(i = 0; i < markers.length; i++ ) {
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
                    }
                })(marker, i));
        
                map.fitBounds(bounds);
            }
            
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                this.setZoom(14);
                google.maps.event.removeListener(boundsListener);
            });
        };
  
var showMap = function() {
    
    $('.map-overlay').show();
    
    var customername = $.trim($("#addressForm #customername").val());
    var siteline1 = $.trim($("#addressForm #siteline1").val());
    var sitesuburb = $.trim($("#addressForm #sitesuburb1").val());
    var sitestate = $.trim($("#addressForm #sitestate").val());
    var sitepostcode = $.trim($("#addressForm #sitepostcode").val());
    var country = 'Australia';
    
    var latitude = parseFloat($("#addressForm #latitude_decimal").val());
    var longitude = parseFloat($("#addressForm #longitude_decimal").val());
    var title = customername;
    
    if(siteline1 !== '' && sitesuburb !== '' && sitestate !== '' && sitepostcode !== '' && country !== '') {
        
        var formatted_address = customername+ '<br/> ' +siteline1+'<br/> '+sitesuburb+' '+sitestate+' '+sitepostcode+' '+country;
        markers = [];
        labelIndex = 0; 
        
        var marker = [title, formatted_address, latitude, longitude];
        markers.push(marker);
        if(markers.length == 0) {
            bootbox.alert('No latitude and logitude available for this address.');
        } else {
            $("#mapaddress").html(formatted_address);
            $("#addressesModel").modal();
             setTimeout(function(){ 
                    var mapOptions = {
                        zoom: 10
                    };

                    directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });

                    directionsService = new google.maps.DirectionsService();  
                    map = new google.maps.Map(document.getElementById('address-map'), mapOptions);
                    directionsDisplay.setMap(map);
                    $('.map-overlay').hide();
                    addMarker();
                }, 1000);
        }
         
    } else {
        bootbox.alert('Please fill required address fields and get GPS before view Map.');
    }
};              
               
 

 var getGPS = function() {
            
    var siteline1 = $.trim($("#addressForm #siteline1").val());
    var sitesuburb = $.trim($("#addressForm #sitesuburb1").val());
    var sitestate = $.trim($("#addressForm #sitestate").val());
    var sitepostcode = $.trim($("#addressForm #sitepostcode").val());
    var country = 'Australia';


    var geocoder = new google.maps.Geocoder();
    //var address = '100 Main Street Burwood NSW 2136 Australia';
    if(siteline1 !== '' && sitesuburb !== '' && sitestate !== '' && sitepostcode !== '' && country !== '') {
        $("span:eq(0)", "#addressForm #getgps").css("display", 'block');
        $("span:eq(1)", "#addressForm #getgps").css("display", 'none');
        $("#addressForm #latitude_decimal").val('');
        $("#addressForm #longitude_decimal").val('');
        var address = siteline1+' '+sitesuburb+' '+sitestate+' '+sitepostcode+' '+country;
        geocoder.geocode({ 'address': address }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK)
          {
            $("span:eq(0)", "#addressForm #getgps").css("display", 'none');
            $("span:eq(1)", "#addressForm #getgps").css("display", 'block');
            $("#addressForm #latitude_decimal").val(results[0].geometry.location.lat());
            $("#addressForm #longitude_decimal").val(results[0].geometry.location.lng());
          }
        }); 
    } else {
        bootbox.alert('Please fill required address fields for get GPS latitude, longitude');
    }
};

var contactType = '';
var openAddContact = function(type) {
    
    $('#addContactForm').trigger("reset");
    $("#addContactForm #contactModalErrorMsg").hide(); 
    $("#addContactForm #contactModalSuccessMsg").hide();
    $("#addContactForm span.help-block").remove();
    $("#addContactForm .has-error").removeClass("has-error");
    
    contactType = type;
    if(type === 'sitefm') {
        $("#addContactForm #role").val('sitefm');
         $("#addContactForm #FromAddressDiv").hide(); 
    }
    if(type === 'sitecontact') {
         $("#addContactForm #FromAddressDiv").show(); 
         $("#addContactForm #role").val('site contact');
    }
    $("#addContactModal").modal();
};

var changeSiteFM = function(elm) {
    if($(elm).val() === '' || $(elm).val() == undefined) {
        $("#addressForm #phone").val('');
        $("#addressForm #mobile").val('');
        $("#addressForm #email").val('');
        return false;
    };
    
    $("#addressForm #phone").val($('option:selected', elm).attr('data-phone'));
    $("#addressForm #mobile").val($('option:selected', elm).attr('data-mobile'));
    $("#addressForm #email").val($('option:selected', elm).attr('data-email'));
};

var changeSiteContact = function(elm) {
    
    if($(elm).val() === '' || $(elm).val() == undefined) {
        $("#addressForm #sitephone").val('');
        $("#addressForm #sitemobile").val('');
        $("#addressForm #siteemail").val('');
        $("#addressForm #sitecontact").val('');
        return false;
    };
    
    $("#addressForm #sitephone").val($('option:selected', elm).attr('data-phone'));
    $("#addressForm #sitemobile").val($('option:selected', elm).attr('data-mobile'));
    $("#addressForm #siteemail").val($('option:selected', elm).attr('data-email'));
    $("#addressForm #sitecontact").val($('option:selected', elm).attr('data-contact'));
};