"use strict";
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
//                latitude_decimal: {  
//                     required: {
//                        depends:function(){
//                            $(this).val($.trim($(this).val()));
//                            return true;
//                        }   
//                    }
//                },
//                longitude_decimal: {  
//                     required: {
//                        depends:function(){
//                            $(this).val($.trim($(this).val()));
//                            return true;
//                        }   
//                    }
//                },
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

    }
    
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
//Google Map
var map;
var markers = [];
var gmarkers = [];
var directionsDisplay;
var directionsService; 
var labelIndex = 0;


var contactType = '';
var openAddContact = function(type) {
    
    $('#addContactForm').trigger("reset");
    $("#addContactForm #contactModalErrorMsg").hide(); 
    $("#addContactForm #contactModalSuccessMsg").hide();
    $("#addContactForm span.help-block").remove();
    $("#addContactForm .has-error").removeClass("has-error");
    
    contactType = type;
    if(type === 'sitefm') {
        $("#addContactForm #FromAddressDiv").hide(); 
        
        $("#addContactForm #role").val('sitefm');
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
