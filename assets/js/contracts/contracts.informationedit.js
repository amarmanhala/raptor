"use strict";
var app = angular.module('app', ['ui.bootstrap', 'ui.grid', 'ui.grid.pagination', 'ui.grid.autoResize', 'ui.grid.pinning', 'ui.grid.resizeColumns', 'ui.grid.selection', 'ui.grid.edit']); //'ngTouch',    
$( document ).ready(function() {

    if (typeof $.fn.validate === "function") {    
	$("#contractdetailform").validate({
		rules: {
                    name: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    
                    
                    contractref: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    }, 
                    contracttypeid: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    startdate: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    enddate: {  
                        required:  {
                            depends:function(){
                                $(this).val($.trim($(this).val()));
                                return true;
                            }   
                        }
                    },
                    managerid: {  
                        required:  {
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
                    if($(e).parent().is('.input-group') || $(e).parent().is('.radio-inline')) {
			 
                         $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
                    }
                    else{
                       $(e).parent().parent().removeClass('has-info').addClass('has-error');
                    } 
                           
                    
		},
                success: function (e) {
                   if($(e).parent().is('.input-group') || $(e).parent().is('.radio-inline')) {
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
                     if($(e).parent().is('.input-group') || $(e).parent().is('.radio-inline')) {
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
    } 

    $(document).on('change', "#contractdetailform #managerid", function() {
        $("#contractdetailform #phone").val($('#contractdetailform #managerid option:selected').attr('data-phone'));
    });
    
 
    $("#startdate").on('changeDate', function(e) {
        $('input[name="enddate"]').datepicker('setStartDate', e.date);
        if($('input[name="enddate"]').val()!=''){
//            var d1 =e.date; //datepicker (text fields)
//            var d2 = $('#enddate').datepicker( "getDate" ); // datepicker
//            var months;
//            months = (d2.getFullYear() - d1.getFullYear()) * 12;
//            months -= d1.getMonth() + 1;
//            months += d2.getMonth();
//            months = months <= 0 ? 0 : months;
//            $('input[name="months"]').val(months);
        }
    });
    
   
    $("#enddate").on('changeDate', function(e) {
        if($('input[name="startdate"]').val()==''){
            $('input[name="startdate"]').val($('input[name="enddate"]').val());
        }
        
//        var d2 =e.date; //datepicker (text fields)
//        var d1 = $('#startdate').datepicker( "getDate" ); // datepicker
//         
//        var months;
//        months = (d2.getFullYear() - d1.getFullYear()) * 12;
//        months -= d1.getMonth() + 1;
//        months += d2.getMonth();
//        months = months <= 0 ? 0 : months;
//        $('input[name="months"]').val(months);
    });
   $(document).on('change', '#contracted_hoursid', function(e){
        
        $('#divContractHoures').html('');
        if($("#contractdetailform #contracted_hoursid").val() !== ''){
         $.get( base_url+"contracts/getcontractedhours", {id: $("#contractdetailform #contracted_hoursid").val()}, function( response ) {
                
                if (response.success) {
                    
                        var optionhtml = ' <table class="table table-striped table-bordered table-condensed"><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>';
                        optionhtml +='<tr><td>'+ response.data.sun_from +'</td><td>'+ response.data.mon_from +'</td><td>'+ response.data.tue_from +'</td><td>'+ response.data.wed_from +'</td><td>'+ response.data.thu_from +'</td><td>'+ response.data.fri_from +'</td><td>'+ response.data.sat_from +'</td></tr>';
                        optionhtml +='<tr><td>'+ response.data.sun_to +'</td><td>'+ response.data.mon_to +'</td><td>'+ response.data.tue_to +'</td><td>'+ response.data.wed_to +'</td><td>'+ response.data.thu_to +'</td><td>'+ response.data.fri_to +'</td><td>'+ response.data.sat_to +'</td></tr>';
                        optionhtml += ' </table>';
                        $('#divContractHoures').html(optionhtml);
                   
                  }
                else {
                    bootbox.alert(response.message);
                }
            }, 'json');
        }
         
    });
   $(document).on('click', 'ul.nav-tabs .loadingdata', function(e){
        
        var targetdiv = $(this).attr('href');
        $(targetdiv+ " .btn-refresh" ).click();
        $(this).removeClass('loadingdata');
    });
    var hash = window.location.hash;
    if(hash!=''){
        $( "ul.nav.nav-tabs li" ).each(function( index ) {
            var targetdiv = $(this).children('a').attr('href');
            if(hash === targetdiv){
                $(targetdiv+ " .btn-refresh" ).click();
                $(this).children('a').removeClass('loadingdata');
            }
        });
    } 
    else{
        $( "ul.nav.nav-tabs li.active" ).each(function( index ) {
            var targetdiv = $(this).children('a').attr('href');
            $(targetdiv+ " .btn-refresh" ).click();
            $(this).children('a').removeClass('loadingdata');
        });
    }
});