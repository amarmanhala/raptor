/* global base_url, google, bootbox, parseFloat, angular */

"use strict";
var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection']); //'ngTouch',  

$( document ).ready(function() {
    
    var map;var marker;
    
    function initialize() {
    	
       	marker = new google.maps.Marker();

        var mapCanvas = document.getElementById('address-map');
        //var latitude = -34.397;
        //var longitude = 150.644;
        
        var latitude = document.getElementById('latitude_decimal').value;
        var longitude = document.getElementById('longitude_decimal').value;
               
        var slocation = new google.maps.LatLng(latitude,longitude);
        var mapOptions  = {
            zoom:12,
            center: slocation,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

       map = new google.maps.Map(mapCanvas, mapOptions );
       if(latitude !== '') {
            marker.setPosition(slocation);
            marker.setMap(map);	
            map.setCenter(slocation);
       }
 
    }
    
    var setmapmarker = function() {

    	var latitude = -34.397;
        var longitude = 150.644;
        latitude = document.getElementById('latitude_decimal').value;
        longitude = document.getElementById('longitude_decimal').value;
        var slocation = new google.maps.LatLng(latitude,longitude);
         
    	marker.setPosition(slocation);
        marker.setMap(map);
        map.setCenter(slocation);
    };
    if($('#address-map').length) {
        google.maps.event.addDomListener(window, 'load', initialize);
    }
    
    
    
    $(document).on('change', '#labelid', function() {
       
        $("#tab_asset_form #site_address").val($("#tab_asset_form #labelid option:selected").text());
        $('#tab_asset_form #latitude_decimal').val($("#tab_asset_form #labelid option:selected").data('latitude'));
        $('#tab_asset_form #longitude_decimal').val($("#tab_asset_form #labelid option:selected").data('longitude'));
        $('#location_id').html('');
        $('#location_text').val('');
        $('#sublocation_id').html('<option value="">Select Sub-Location</option>');
        $('#sublocation_text').val('');
        
        var options = '<option value="">Select Location</option>';
        if($(this).val()!==""){
            $.get( base_url + 'asset/loadlocations', { search:'', labelid :$(this).val()}, function( response ) {
              
               
                if(response.success) {
                    $.each( response.data, function( key, val ) {
                        options = options+'<option value="'+val.asset_location_id+'" data-latitude="'+ val.latitude_decimal +'" data-longitude="'+ val.longitude_decimal +'" >'+val.location+'</option>';
                    });
                }
                else{
                    bootbox.alert(response.message);
                    return false;
                }
               
               
               
                $("#location_id").html(options);
               // $("#location_id").select2();
            },'json');
        }
         else{
              $("#location_id").html(options);
            // $("#location_id").select2();
         }  
        setmapmarker();
        
        //$("#tab_asset_form #sublocation_id").select2();
       
       //return true;
    });
    
    $(document).on('change', '#location_id', function() {
       
        $("#tab_asset_form #location_text").val($("#tab_asset_form #location_id option:selected").text());
        if($("#tab_asset_form #location_id option:selected").data('latitude')!='' && $("#tab_asset_form #location_id option:selected").data('latitude')!=null){
            $('#tab_asset_form #latitude_decimal').val($("#tab_asset_form #location_id option:selected").data('latitude'));
            $('#tab_asset_form #longitude_decimal').val($("#tab_asset_form #location_id option:selected").data('longitude'));
        }
        else{
            $('#tab_asset_form #latitude_decimal').val($("#tab_asset_form #labelid option:selected").data('latitude'));
            $('#tab_asset_form #longitude_decimal').val($("#tab_asset_form #labelid option:selected").data('longitude'));
        }
        $('#sublocation_id').html('');
        $('#sublocation_text').val('');
        
        var options = '<option value="">Select Sub-Location</option>';
        if($(this).val()!==""){
            $.get( base_url + 'asset/loadsublocations', { search:'', locationid :$(this).val()}, function( response ) {
                
                if(response.success) {
                    $.each( response.data, function( key, val ) {
                        options = options+'<option value="'+val.asset_sublocation_id+'" >'+val.sublocation+'</option>';
                    });
                }
                else{
                    bootbox.alert(response.message);
                    return false;
                }
                
               
                $("#sublocation_id").html(options);
                //$("#sublocation_id").select2();
            },'json');
        }
         else{
              $("#sublocation_id").html(options);
             //$("#sublocation_id").select2();
         }  
 
       setmapmarker();
       //return true;
    });
    
    $(document).on('change', '#sublocation_id', function() {
       
        $("#tab_asset_form #sublocation_text").val($("#tab_asset_form #sublocation_id option:selected").text());
    });
    $(document).on('change', '#tab_asset_form #category_id', function() {
   
        var id = $(this).val();

        $("#tab_asset_form #customfield1").removeAttr('style');
        $("#tab_asset_form #customfield2").removeAttr('style');
        $("#tab_asset_form #customfield3").removeAttr('style');
        $("#tab_asset_form #customfield4").removeAttr('style');
        $("#tab_asset_form #customfield5").removeAttr('style');
 
        

        if(id === "") {
                
            $("#tab_asset_form #customfield1").hide();
            $("#tab_asset_form #customfield2").hide();
            $("#tab_asset_form #customfield3").hide();
            $("#tab_asset_form #customfield4").hide();
            $("#tab_asset_form #customfield5").hide();
            
            
            $("#tab_asset_form #customlabel1").html('');
            $("#tab_asset_form #customlabel2").html('');
            $("#tab_asset_form #customlabel3").html('');
            $("#tab_asset_form #customlabel4").html('');
            $("#tab_asset_form #customlabel5").html('');
            $("#tab_asset_form #category_name").val('');
                return false;
        }

   	$("#tab_asset_form #category_name").val($("#tab_asset_form #category_id option:selected").text());
        if($("#tab_asset_form #category_id option:selected").data('customlabel1') !=='' && $("#tab_asset_form #category_id option:selected").data('customlabel1') !==null){
            $("#tab_asset_form #customlabel1").html($("#tab_asset_form #category_id option:selected").data('customlabel1')+':');
        }
        else{
             $("#tab_asset_form #customfield1").hide();
        }
         if($("#tab_asset_form #category_id option:selected").data('customlabel2') !=='' && $("#tab_asset_form #category_id option:selected").data('customlabel2') !==null){
            $("#tab_asset_form #customlabel2").html($("#tab_asset_form #category_id option:selected").data('customlabel2')+':');
        }
        else{
             $("#tab_asset_form #customfield2").hide();
        }
         if($("#tab_asset_form #category_id option:selected").data('customlabel3') !=='' && $("#tab_asset_form #category_id option:selected").data('customlabel3') !==null){
            $("#tab_asset_form #customlabel3").html($("#tab_asset_form #category_id option:selected").data('customlabel3')+':');
        }
        else{
             $("#tab_asset_form #customfield3").hide();
        }
         if($("#tab_asset_form #category_id option:selected").data('customlabel4') !=='' && $("#tab_asset_form #category_id option:selected").data('customlabel4') !==null){
            $("#tab_asset_form #customlabel4").html($("#tab_asset_form #category_id option:selected").data('customlabel4')+':');
        }
        else{
             $("#tab_asset_form #customfield4").hide();
        }
         if($("#tab_asset_form #category_id option:selected").data('customlabel5') !==''  && $("#tab_asset_form #category_id option:selected").data('customlabel5') !==null){
            $("#tab_asset_form #customlabel5").html($("#tab_asset_form #category_id option:selected").data('customlabel5')+':');
        }
        else{
             $("#tab_asset_form #customfield5").hide();
        }
        
     	
   }); 
    
    
    $('.nav-tabs li.disabled > a[data-toggle=tab]').on('click', function(e) {
	   return false;
    });
    
    if($('#manufacturer').length) {
        
        $('#manufacturer').typeahead({
            ajax: {
                url: base_url + 'asset/loadmanufacturers',
                method: 'get',
                preDispatch: function (query) {
                    return {
                        search : query
                    };
                },
                preProcess: function (response) {
                     
                    if(response.success) {
                       return response.data; 
                    }
                    else{
                        bootbox.alert(response.message);
                        return false;
                    }
                                
                }
            },
            displayField: 'manufacturer',
            valueField: 'manufacturer'           
        });
    }
   
     
        
        
        if($('#tab_asset_form').length) {
            
            $("#tab_asset_form").validate({
                    errorElement: 'span',
                    errorClass: 'help-block',
                    rules: {
                        
                            labelid: {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            },
                            site_address: {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            },
//                            location_id: {
//                                required: {
//                                    depends:function(){
//                                        $(this).val($.trim($(this).val()));
//                                        return true;
//                                    }   
//                                }
//                            },
                            manufacturer: {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            },
                            purchase_date: {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            }, 
                            category_id: {
                                required: {
                                    depends:function(){
                                        $(this).val($.trim($(this).val()));
                                        return true;
                                    }   
                                }
                            }
                            
                    },
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
                                 
                                if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                                        var controls = element.closest('div[class*="col-"]');
                                        if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                                        else error.appendTo(element.nextAll('.lbl:eq(0)').eq(0));
                                }
                                else if(element.is('.select2')) {
                                        error.appendTo(element.siblings('[class*="select2-container"]:eq(0)'));
                                }
                                else if(element.is('.chosen-select')) {
                                        error.appendTo(element.siblings('[class*="chosen-container"]:eq(0)'));
                                }
                                else if(element.parent().is('.input-group')) {
                                        error.appendTo(element.parent().parent());
                                }
                                else error.appendTo(element.parent());
                        },
                        unhighlight: function(e, errorClass, validClass) {
                             if($(e).parent().is('.input-group')) {
                                $(e).parent().parent().parent().removeClass("has-error");
                            }
                            else{
                                $(e).parent().parent().removeClass("has-error");
                            }


                        },
                    submitHandler: function() {
                        return true;
                    }
            });
        }
       
        $(document).on('click', '.nav-tabs li.disabled > a[data-toggle=tab]', function(e) {
 
	   return false;
	});
	 
        $(document).on('click', '#locationModal #btnsave', function() {

            var name = $("#locationform #location");
           
            $("#locationform span.help-block").remove();
            var validationerror = false;
            if($.trim(name.val()) === "") {
                $(name).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(name.parent());
                validationerror = true;
            } else {
                $(name).parent().removeClass("has-error");
            }
             
            if(validationerror){
                 return false;
            }
           
            $("#locationform #btnsave").button('loading'); 
            $("#locationform #btncancel").button('loading'); 
            $.post( base_url+"asset/addeditassetlocation", $("#locationform").serialize(), function( data ) {
                $('#locationform #btnsave').removeAttr("disabled");
                $('#locationform #btncancel').removeAttr("disabled");
                
                $('#locationform #btnsave').removeClass("disabled");
                $('#locationform #btncancel').removeClass("disabled");
                $('#locationform #btnsave').html("Save");
                $('#locationform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#locationModal").modal('hide');
                    var oldlocation_id = $('#tab_asset_form #location_id').val();
                    $('#location_id').html('');
                    $('#location_text').val('');
                    if(oldlocation_id !== data.total){
                        $('#tab_asset_form #sublocation_id').html('<option value="">Select Sub-Location</option>');
                        $('#tab_asset_form #sublocation_text').val('');
                    }
                    var options = '<option value="">Select Location</option>';
        
                    $.each( data.data, function( key, val ) {
                        if(data.total === val.asset_location_id){
                            $('#location_text').val(val.location);
                        }
                        options = options+'<option value="'+val.asset_location_id+'" data-latitude="'+ val.latitude_decimal +'" data-longitude="'+ val.longitude_decimal +'" >'+val.location+'</option>';
                    });
              
                    $("#tab_asset_form #location_id").html(options);
                    $('#location_id').val(data.total);
        
                    
                    bootbox.alert(data.message);
                   
                }
                else{
                     $('#locationModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#locationModal #btncancel', function() {
            $("#locationModal").modal('hide');
        });
        
        
        $(document).on('click', '#SubLocationModal #btnsave', function() {

            var name = $("#sublocationform #sublocation");
          
            
            $("#sublocationform span.help-block").remove();
            var validationerror = false;
            if($.trim(name.val()) === "") {
                $(name).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(name.parent());
                validationerror = true;
            } else {
                $(name).parent().removeClass("has-error");
            }
             
            if(validationerror){
                 return false;
            }
           
            $("#sublocationform #btnsave").button('loading'); 
            $("#sublocationform #btncancel").button('loading'); 
            $.post( base_url+"asset/addeditassetsublocation", $("#sublocationform").serialize(), function( data ) {
                $('#sublocationform #btnsave').removeAttr("disabled");
                $('#sublocationform #btncancel').removeAttr("disabled");
                
                $('#sublocationform #btnsave').removeClass("disabled");
                $('#sublocationform #btncancel').removeClass("disabled");
                $('#sublocationform #btnsave').html("Save");
                $('#sublocationform #btncancel').html("Cancel");
                if(data.success) {
 
                    $("#SubLocationModal").modal('hide');
                    var oldlocation_id = $('#tab_asset_form #sublocation_id').val(); 
                    $('#tab_asset_form #sublocation_id').html('');
                    $('#tab_asset_form #sublocation_text').val('');
                    
                    var options = '<option value="">Select Sub-Location</option>';
        
                    $.each( data.data, function( key, val ) {
                        if(data.total === val.asset_sublocation_id){
                            $('#sublocation_text').val(val.sublocation);
                        }
                       options = options+'<option value="'+val.asset_sublocation_id+'" >'+val.sublocation+'</option>';
                    });
              
                    $("#tab_asset_form #sublocation_id").html(options);
                    $('#tab_asset_form #sublocation_id').val(data.total);
                     
                    bootbox.alert(data.message);
                   
                }
                else{
                     $('#SubLocationModal .status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                }
            }, 'json');
        });

        $(document).on('click', '#SubLocationModal #btncancel', function() {
            $("#SubLocationModal").modal('hide');
        });
        
	
});   
 
 
 var getfromAddressGPS = function() {
	 
    $("#locationform #latitude_decimal").val($("#locationform #lat").val());
    $("#locationform #longitude_decimal").val($("#locationform #long").val());
};

var openLocation = function(type) {
             
             
        if($("#tab_asset_form #labelid").val() === ''){ 
            bootbox.alert('Please select address before '+ type +' location.');
            return false;
        }
           
        $('#locationform').trigger("reset");
        $("#locationform .alert-danger").hide(); 
        $("#locationform span.help-block").remove();
        $("#locationform .has-error").removeClass("has-error");
        $('#locationform #btnsave').button("reset");
        $('#locationform #btndelete').button("reset");
        $('#locationform #btncancel').button("reset");
        $('#locationform #btnsave').removeAttr("disabled");
        $('#locationform #btncancel').removeAttr("disabled");
        $('#locationform #btndelete').removeAttr("disabled");
        $("#locationform .close").css('display', 'block');
        $("#locationModal #loading-img").show();
        $("#locationModal #locgriddiv").hide();
        $("#locationModal .modal-footer").hide();
    
        $("#locationform #address").val($("#tab_asset_form #labelid option:selected").text()); 
        $("#locationform #labelid").val($("#tab_asset_form #labelid").val()); 
        $("#locationform #lat").val($("#tab_asset_form #labelid option:selected").attr('data-latitude')); 
        $("#locationform #long").val($("#tab_asset_form #labelid option:selected").attr('data-longitude')); 
            
        if(type === 'add'){
            $("#locationModal").modal();
            $("#locationModal h4.modal-title").html('Add Location');
            $("#locationform #mode").val('add'); 
            $("#locationform #asset_location_id").val(''); 
            $('#locationform input[name="is_active"]').prop('checked', true);
           setTimeout(function(){ 
                $("#locationModal #loading-img").hide();
                $("#locationModal #locgriddiv").show();
                $("#locationModal .modal-footer").show();

            }, 1000);
        }
        else{
            if($("#tab_asset_form #location_id").val() === ''){ 
                bootbox.alert('Please select location before '+ type +' location.');
                return false;
            }
            
            $("#locationModal").modal();
            $("#locationModal h4.modal-title").html('Edit Location - '+ $("#tab_asset_form #location_id option:selected").text());
            $("#locationform #mode").val('edit'); 
            $("#locationform #asset_location_id").val($("#tab_asset_form #location_id").val()); 
            
            $.get( base_url+"asset/loadassetlocation", {id: $("#tab_asset_form #location_id").val()}, function( response ) {
                
                if (response.success) {
                     
                    $("#locationform #location").val(response.data.location);
                    $("#locationform #notes").html(response.data.notes);
                    $("#locationform #latitude_decimal").val(response.data.latitude_decimal);
                    $("#locationform #longitude_decimal").val(response.data.longitude_decimal);
                    if(parseInt(response.data.is_active) === 1){
                        $('#locationform input[name="is_active"]').prop('checked', true);
                    }
                    else{
                        $('#locationform input[name="is_active"]').prop('checked', false);
                    } 
                    
                    $("#locationModal #loading-img").hide();
                    $("#locationModal #locgriddiv").show();
                    $("#locationModal .modal-footer").show();
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
            
        }
          
};

var openSubLocation = function(type) {
             
             
        if($("#tab_asset_form #location_id").val() === ''){ 
            bootbox.alert('Please select location before '+ type +' sub location.');
            return false;
        }
           
        $('#sublocationform').trigger("reset");
        $("#sublocationform .alert-danger").hide(); 
        $("#sublocationform span.help-block").remove();
        $("#sublocationform .has-error").removeClass("has-error");
        $('#sublocationform #btnsave').button("reset");
        $('#sublocationform #btndelete').button("reset");
        $('#sublocationform #btncancel').button("reset");
        $('#sublocationform #btnsave').removeAttr("disabled");
        $('#sublocationform #btncancel').removeAttr("disabled");
        $('#sublocationform #btndelete').removeAttr("disabled");
        $("#sublocationform .close").css('display', 'block');
        $("#SubLocationModal #loading-img").show();
        $("#SubLocationModal #sublocgriddiv").hide();
        $("#SubLocationModal .modal-footer").hide();
    
        $("#sublocationform #address").val($("#tab_asset_form #labelid option:selected").text()); 
        $("#sublocationform #labelid").val($("#tab_asset_form #labelid").val()); 
        $("#sublocationform #lat").val($("#tab_asset_form #labelid option:selected").attr('data-latitude')); 
        $("#sublocationform #long").val($("#tab_asset_form #labelid option:selected").attr('data-longitude')); 
            
        $("#sublocationform #location").val($("#tab_asset_form #location_id option:selected").text()); 
        $("#sublocationform #location_id").val($("#tab_asset_form #location_id").val()); 
        
        if(type === 'add'){
            $("#SubLocationModal").modal();
            $("#SubLocationModal h4.modal-title").html('Add Sub-Location');
            $("#locationform #mode").val('add'); 
            $('#sublocationform input[name="is_active"]').prop('checked', true);
            $("#locationform #asset_sublocation_id").val(''); 
           setTimeout(function(){ 
                $("#SubLocationModal #loading-img").hide();
                $("#SubLocationModal #sublocgriddiv").show();
                $("#SubLocationModal .modal-footer").show();

            }, 1000);
        }
        else{
            if($("#tab_asset_form #sublocation_id").val() === ''){ 
                bootbox.alert('Please select sub-location before '+ type +' sub-location.');
                return false;
            }
            
            $("#SubLocationModal").modal();
            $("#SubLocationModal h4.modal-title").html('Edit Sub-Location - '+ $("#tab_asset_form #sublocation_id option:selected").text());
            $("#sublocationform #mode").val('edit'); 
            $("#sublocationform #asset_sublocation_id").val($("#tab_asset_form #sublocation_id").val()); 
            
            $.get( base_url+"asset/loadassetsublocation", {id: $("#tab_asset_form #sublocation_id").val()}, function( response ) {
                
                if (response.success) {
                      $("#SubLocationModal #loading-img").hide();
                    $("#SubLocationModal #sublocgriddiv").show();
                    $("#SubLocationModal .modal-footer").show();
                    $("#sublocationform #sublocation").val(response.data.sublocation);
                    $("#sublocationform #notes").html(response.data.notes);
           
                    if(parseInt(response.data.is_active) === 1){
                        $('#sublocationform input[name="is_active"]').prop('checked', true);
                    }
                    else{
                        $('#sublocationform input[name="is_active"]').prop('checked', false);
                    } 
                    
                   
                }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
            
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
    
    var title = $.trim($("#tab_asset_form #labelid").val());
    var latitude_decimal = $.trim($("#tab_asset_form #latitude_decimal").val());
    var longitude_decimal = $.trim($("#tab_asset_form #longitude_decimal").val());

    
  
    if(title !== '' && latitude_decimal !== '') {
        var latitude = parseFloat(latitude_decimal);
        var longitude = parseFloat(longitude_decimal);
        var formatted_address = $('#tab_asset_form #labelid option:selected').text();
        var title = formatted_address;
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
        bootbox.alert('Select address fields and get GPS before view Map.');
    }
};              
 
 var getGPS = function() {
      
     
    var title = $.trim($("#tab_asset_form #labelid").val());
    
    var geocoder = new google.maps.Geocoder();
    //var address = '100 Main Street Burwood NSW 2136 Australia';
    if(title !== '') {
        var address = $('#tab_asset_form #labelid option:selected').text();
        $("span:eq(0)", "#tab_asset_form #getgps").css("display", 'block');
        $("span:eq(1)", "#tab_asset_form #getgps").css("display", 'none');
        //$("#tab_asset_form #latitude_decimal").val('');
        //$("#tab_asset_form #longitude_decimal").val('');
   
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK){
                $("span:eq(0)", "#tab_asset_form #getgps").css("display", 'none');
                $("span:eq(1)", "#tab_asset_form #getgps").css("display", 'block');
                
                if($("#tab_asset_form #latitude_decimal").val() == results[0].geometry.location.lat() && $("#tab_asset_form #longitude_decimal").val() == results[0].geometry.location.lng())
                {
                     bootbox.alert('GPS location verified.');
                }
                else{
                    bootbox.confirm("Based on this address, the GPS Location is: "+results[0].geometry.location.lat()+"/"+results[0].geometry.location.lng()+". Update the existing values?", function(result) {
                        if(result) {
                            $("#tab_asset_form #latitude_decimal").val(results[0].geometry.location.lat());
                            $("#tab_asset_form #longitude_decimal").val(results[0].geometry.location.lng());
                        }
                    });
                }
                
                
            }
            else{
                 bootbox.alert('GPS location not available');
            }
        }); 
    } else {
        bootbox.alert('Please select address fields for get GPS latitude, longitude');
    }
};
 