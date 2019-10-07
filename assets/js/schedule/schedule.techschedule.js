/* global calendermultitech, moment, createinternaltask, bootbox, calendermulti, base_url, base_img_url, calenderdate, modaloverlap, jobdetailfunc*/

"use strict";
$( document ).ready(function() {
    	if (typeof $.fn.fullCalendar === "function") {
                var calender;
                var calenderinit = function(timeslot, calenderdate, defaultview) {
                    calender= $('#calendar').fullCalendar({
                        header: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'month,agendaWeek,agendaDay,agendaMulti' //,agendaMulti
                        },
                        views: {
                           agendaMulti: {
                              //type: 'agendaDay',
                              buttonText: 'multi',
                              type: 'multiColAgenda',
                              duration: { days: 1 },
                              numColumns: Object.keys(calendermultitech).length,
                              columnHeaders: calendermultitech
                           }
                        },
                        slotDuration:timeslot, 
                        defaultView:defaultview,
                        defaultDate: calenderdate,
                        eventLimit:2,   //for show limited event in box show as 3+
                        editable: true,
                        scrollTime: moment(),
                        allDaySlot: false,
                        events: function(start, end, timezone, callback) {
                            var state = $("#calendarform #state").val();
                            var site = $("#calendarform #site").val();
                            var contract = $("#calendarform #contract").val();
                            var technicians = $("#calendarform #technicians").val();
                            var timeslot =  $("#calendarform #timeslot").val();
                            var calenderview = $("#calendarform #calenderview").val();
                        
                             $.ajax({
                                url: base_url+"techschedule/getschedules/q",
                                dataType: 'json',
                                method: 'post',
                                data: {
                                    start: start.format(),
                                    end: end.format(),
                                    state: state,
                                    site: site,
                                    contract: contract,
                                    technicians: technicians,
                                    timeslot: timeslot,
                                    calenderview: calenderview 
                                },
                                success: function(data) {
                                    $("#scheduleinnerloading").css('display','none');
                                    if(data.success) {
                                    var events = [];
                                    $.each(data.data, function(i, item) {
                                        events.push({
                                            id: item.id,
                                            start: item.start,
                                            end: item.end,
                                            title: item.custordref,
                                            jobid: item.jobid,
                                            custordref: item.custordref,
                                            siteline1: item.siteline1,
                                            inprogress : item.inprogress,
                                            completed : item.completed,
                                            column: item.column, 
                                            backgroundColor: item.jobcolor,
                                            custordref1_label: item.custordref1_label
                                        });
                                    });
                                    calender.fullCalendar('removeEvents');
                                    callback(events);
                                    } else {
                                        bootbox.alert(data.message);
                                    }
                                }
                             });
                         },
 
                         eventRender: function(event, element) {

                             var view = calender.fullCalendar('getView');

                             var description = '';    
                             element.find(".fc-title").remove();
                             element.find(".fc-time").remove();
                             element.find(".fc-bg").remove();
                             element.find(".fc-content").remove();
                             var lock = '';
                              
                             
                             if(view.name === 'agendaDay') {
                                 description = lock; 
                                description = description + '&nbsp;&nbsp;' + moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                description = description + '<br />Job ID: ' + event.jobid;
                                description = description + '<br />'+event.custordref1_label+': ' + event.custordref;
                                description = description + '<br /><a href="javascript:void(0);"  id="'+event.jobid+'" class="detailhover"><span class="glyphicon glyphicon-info-sign" style="color: rgb(255, 255, 255); font-size: 19px;"></span></a>';         
                                
                             } else if(view.name === 'agendaWeek') {
                                description = lock+' '+moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                description = description + '<br />Job ID: ' + event.jobid;
                                description = description + '<br />'+event.custordref1_label+': ' + event.custordref;
                                description = description + '<br /><a href="javascript:void(0);" class="detailhover"  id="'+event.jobid+'"><span class="glyphicon glyphicon-info-sign" style="color: rgb(255, 255, 255); font-size: 19px;"></span></a>';
                             }
                             else if(view.name === 'agendaMulti') {
                               description = lock;
                                description = description + '&nbsp;&nbsp;' + moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                description = description + '<br />Job ID: ' + event.jobid;
                                description = description + '<br />'+event.custordref1_label+': ' + event.custordref;
                                description = description + '<br /><a href="javascript:void(0);"  id="'+event.jobid+'" class="detailhover"><span class="glyphicon glyphicon-info-sign" style="color: rgb(255, 255, 255); font-size: 19px;"></span></a>';
                             } else {
                                description = lock+' '+moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                description = description + '<br />Job ID: ' + event.jobid;
                                description = description + '<br />'+event.custordref1_label+': ' + event.custordref;
                                description = description + '<br /><a href="javascript:void(0);"  id="'+event.jobid+'" class="detailhover" ><span class="glyphicon glyphicon-info-sign" style="color: rgb(255, 255, 255); font-size: 19px;"></span></a>';
                             }

                             element.append(description);
                         }   
                    });
                };
               
                var htimeslot = $("#calendarform #timeslot").val();
                var calenderview = $("#calendarform #calenderview").val();
                calenderinit(htimeslot, calenderdate, calenderview);
 
                $(document).on('change', '#timeslot', function() {
               
                    var moment = calender.fullCalendar('getDate');
                    var activedate = moment.format(); 
                    var view = calender.fullCalendar('getView');
                    calender.fullCalendar('destroy'); 
                    $("#calendarform #calenderview").val(view.name);
                    calenderinit($(this).val(), activedate, view.name);
                });

                $(document).on('click', '.fc-month-button', function() {
                     $("#calendarform #calenderview").val('month');
                });
                
                $(document).on('click', '.fc-agendaDay-button', function() {
                     $("#calendarform #calenderview").val('agendaDay');
                });
                
                $(document).on('click', '.fc-agendaWeek-button', function() {
                     $("#calendarform #calenderview").val('agendaWeek');
                });
                
                $(document).on('click', '.fc-agendaMulti-button', function() {
                    $("#calendarform #technicion").val('');
                    $("#calendarform #technicion").selectpicker('refresh');
                    $("#calendarform #technicians").val('');
                    $("#calendarform #technicians").selectpicker('refresh');
            
                    $("#calendarform #calenderview").val('agendaMulti');
                    $("#calendarform #technicion option:selected").prop("selected", false);
                    $("#calendarform #techniciansmultitbl tr").removeAttr('class');
                    calender.fullCalendar('refetchEvents');
                });
             
            $(document).on('click', "#calendar .timesheetbtn", function() {
                var userid = $(this).attr("data-id");
                var contactname = $(this).attr("data-name");
                var moment = $('#calendar').fullCalendar('getDate');
                var activedate = moment.format(); 
                 
                $("#timeSheetModal #timeSheetModalLabel").html('Time Sheet - '+ contactname);
                $("#timeSheetModal").modal();
                $("#timesheettbl").html('');
                $('#timeSheetModal center').show();
                $("#timesheet_form").hide();
                $("#timeSheetModal #exporttimesheet").removeAttr('target');
                $("#timeSheetModal #exporttimesheet").attr('href', 'javascript:void(0);');
           
                $.post( base_url+"techschedule/timesheetschedule/q", { get:1, userid:userid, activedate:activedate }, function( data ) {
                    var result = '';
                    var dtetimestamp = '';
                    if(data.success) {
                        $.each( data.data, function( key, value ) {
                            result = result+'<tr>';
                            result = result+'<td>'+value.dte+'</td>';
                            result = result+'<td>'+value.apptid+'</td>';
                            result = result+'<td>'+value.jobid+'</td>';
                            result = result+'<td>'+value.custordref+'</td>';
                            result = result+'<td>'+value.sitesuburb+'</td>';
                            result = result+'<td>'+value.start+'</td>';
                            result = result+'<td>'+value.duration+'</td>';
                            
                            result = result+'</tr>';
                            dtetimestamp = value.dtetimestamp;
                        });

                        if($.trim(result)!=='') {
                            $("#timeSheetModal #exporttimesheet").attr('target', '_blank');
                            $("#timeSheetModal #exporttimesheet").attr('href', base_url+"schedule/exporttimesheet/"+contactid+"/"+dtetimestamp);
                        }
                        $("#timesheettbl").html(result);
                    }
                    else{
                          bootbox.alert(data.message);
                      }
 
                    $('#timeSheetModal center').hide();
                    $("#timesheet_form").show();
                }, 'json');
            });
            
            $("#timesheet_form #cancel").on('click', function() {
                $("#timeSheetModal").modal('hide');
            });
            
            
            $(document).on('mouseover', "#calendar .detailhover1", function() {
                var jobid = $(this).attr("id");
                jobdetailfunc(jobid);
            });
 
            $(document).on('click', "#calendar .detailhover", function() {
                var jobid = $(this).attr("id");
                jobdetailfunc(jobid);
            });
            
            $(document).on('change', "#calendarform #site, #calendarform #technicians, #calendarform #contract", function() {
                
                
                $("#scheduleinnerloading").css('display','block');
                calender.fullCalendar('refetchEvents');
            });
            
            $(document).on('change', '#calendarform #state', function() {
                $("#calendarform #site").html(''); 
                $("#calendarform #site").selectpicker('refresh'); 
                $("#scheduleinnerloading").css('display','block');
                calender.fullCalendar('refetchEvents');
                
                $.get( base_url+"techschedule/getsites", {state : $("#calendarform #state").val()}, function( response ) {
                    
                    if (response.success) {

                        var optionhtml = '';

                        $.each( response.data, function( key, value ) {
                            optionhtml += '<option value="'+ value.id +'" >'+ value.name +'</option>';

                        });
 
                        $("#calendarform #site").html(optionhtml); 
                        $("#calendarform #site").selectpicker('refresh'); 
                    }
                    else {
                        bootbox.alert(response.message);
                    }
                }, 'json');


            });
            
        }
        
        
        
        if($('#datepickerinline').length) {
        
            $('#datepickerinline').datepicker({
                inline: true,
                todayHighlight:true
            });

            $('#datepickerinline').datepicker().on('changeDate', function (ev) {
                 var date = ev.date;
                 var dd = date.getDate();
                 var mm = date.getMonth() + 1;
                 var y = date.getFullYear();
                 dd = dd < 10 ? '0'+dd : dd;
                 mm = mm < 10 ? '0'+mm : mm;
                 date = y+'-'+mm+'-'+dd;
                 calender.fullCalendar( 'gotoDate', date);
            });
        }
});