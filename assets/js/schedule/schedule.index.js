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
                            var technician = $("#calendarform #technicion").val();
                            var technicians = $("#calendarform #technicians").val();
                            var timeslot =  $("#calendarform #timeslot").val();
                            var calenderview = $("#calendarform #calenderview").val();
                            var jobid = $("#calendarform #jobid").val();
                             $.ajax({
                                url: base_url+"schedule/getschedules/q",
                                dataType: 'json',
                                method: 'post',
                                data: {
                                    start: start.format(),
                                    end: end.format(),
                                    technician: technician,
                                    technicians: technicians,
                                    timeslot: timeslot,
                                    calenderview: calenderview,
                                    jobid:jobid
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
                                            title: item.title,
                                            jobid: item.jobid,
                                            jobnumber: item.jobnumber,
                                            status:item.status,
                                            siteline1: item.siteline1,
                                            isinternal: item.isinternal,
                                            islocked: item.islocked,
                                            column: item.column,
                                            completebydate: item.completebydate,
                                            backgroundColor: item.jobcolor
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

                        dayClick: function(date, jsEvent, view) {    
                            var viewname = view.name;

                            if(view.name === 'month') {
                                return false;
                            }   
                             
                            if(createinternaltask === 1) {
                               var time = moment(date).format("HH:mm");
                               var date1 = moment(date).format("DD/MM/YYYY");

                               $('#addinternaljob_form').trigger("reset");
                               $("#addinternaljob_form span.help-block").remove();
                               $("#addinternaljob_form .has-error").removeClass("has-error");
                               $('#addinternaljob_form #modalsave').button("reset");
                                $("#addinternaljob_form #modalsave").removeAttr('disabled'); 
                               $('#addinternaljob_form #cancel').button("reset");
                               $('#addinternaljob_form #starttime').val(time);
                               $('#addinternaljob_form #date').val(date1);
                               $("#addInternalJobModal").modal();
                               $("#addinternaljob_form").show();
                               return false;
                            }
 
                            var jobstatusid = parseInt($("#calendarform #jobstatusid").val());
                         
                            var jobstatus = $("#calendarform #jobstatus").val();
                            if(jobstatusid===10 || jobstatusid===20 || jobstatusid===30 || jobstatusid===40){
                                     
                                var technician = $("#calendarform #technicion").val();
                                if(viewname !== 'agendaMulti') {
                                   if(technician === "") {
                                       bootbox.dialog({
                                           message: "<span class='bigger-110'>Please select technician.</span>",
                                           buttons: 			
                                           {
                                              "button" :
                                               {
                                                   "label" : "Close",
                                                   "className" : "btn-sm btn-primary"
                                               }
                                           }
                                       });
                                       return false;
                                   }
                                }

                                var column = 0;

                                var techname = $("#calendarform #technicion option:selected").text();
                                var jobid = $("#calendarform #jobid").val();
                                var jobnumber = $("#calendarform #jobnumber").val();
                                var moment_time = moment(date).format("hh:mm a");
                                var moment_date = moment(date).format("ddd MMM DD");
                                var message = "Allocate job no. "+jobnumber+" to "+techname+" at "+moment_time+" on "+moment_date+" ?";
                                if(viewname === 'agendaMulti') {
                                    column = date.column;
                                    viewname = 'multi';
                                    techname = '';
                                    if (typeof calendermulti[column] !== 'undefined') {
                                        techname = calendermulti[column];
                                    }
                                    message = "Allocate job no. "+jobnumber+" to "+techname+" at "+moment_time+" on "+moment_date+" ?";
                                }
                                
                                bootbox.dialog({
                                        message: "<span class='bigger-110'>"+message+"</span>",
                                        buttons: 			
                                        {
                                            "click" :
                                            {
                                                "label" : "Ok",
                                                "className" : "btn-sm btn-success",
                                                callback: function() {
                                                    var sdate = date.format();
                                                    var jobid = $("#calendarform #jobid").val();
                                                    if(jobstatusid===40){
                                                        bootbox.dialog({
                                                                message: "<span class='bigger-110'>This job has a status of 'Works Complete' Are you sure you want to schedule a new task?</span>",
                                                                buttons: 			
                                                                {
                                                                    "click" :
                                                                    {
                                                                        "label" : "Ok",
                                                                        "className" : "btn-sm btn-success",
                                                                         callback: function() {
                                                                            var sdate = date.format();
                                                                            var jobid = $("#calendarform #jobid").val();
                                                                            
                                                                            $.post(base_url+"schedule/createschedule/q", { technician:technician, date:sdate, jobid:jobid, view:viewname, column:column,restartjob:1 }, function( data ) {
                                                                                if(data.success) {
                                                                                    if(data.data.success) {
                                                                                        bootbox.hideAll();
                                                                                        data = data.data;
                                                                                        calender.fullCalendar('renderEvent', {
                                                                                             id: data.id,
                                                                                             title: techname,
                                                                                             start: data.start,
                                                                                             end: data.end,
                                                                                             jobid: data.jobid,
                                                                                             jobnumber: data.jobnumber,
                                                                                             status:data.status,
                                                                                             column: data.column,
                                                                                             siteline1: data.siteline1,
                                                                                             islocked: data.islocked,
                                                                                             isinternal: data.isinternal,
                                                                                             completebydate: data.completebydate,
                                                                                             backgroundColor: data.jobcolor
                                                                                        }, true);
                                                                                    }
                                                                                    else {
                                                                                        bootbox.hideAll();
                                                                                        bootbox.dialog({
                                                                                            message: "<span class='bigger-110'>"+data.data.message+"</span>",
                                                                                            title: data.data.title,
                                                                                            buttons: 			
                                                                                            {
                                                                                               "button" :
                                                                                                {
                                                                                                    "label" : "Close",
                                                                                                    "className" : "btn-sm btn-primary"
                                                                                                }
                                                                                            }
                                                                                        });
                                                                                        return false;
                                                                                    }
                                                                                } else {
                                                                                    bootbox.alert(data.message);
                                                                                }
                                                                            }, 'json');  
                                                                             
                                                                             return false;
                                                                        }
                                                                    },
                                                                    "button" :
                                                                    {
                                                                        "label" : "Cancel",
                                                                        "className" : "btn-sm btn-warning"
                                                                    }
                                                                }
                                                        });
                                                    }
                                                    else {
                                                        $.post(base_url+"schedule/createschedule/q", { technician:technician, date:sdate, jobid:jobid, view:viewname, column:column,restartjob:0 }, function( data ) {
                                                            if(data.success) {
                                                                if(data.data.success) {
                                                                    bootbox.hideAll();
                                                                    data = data.data;
                                                                    calender.fullCalendar('renderEvent', {
                                                                        id: data.id,
                                                                        title: techname,
                                                                        start: data.start,
                                                                        end: data.end,
                                                                        jobid: data.jobid,
                                                                        jobnumber: data.jobnumber,
                                                                        status:data.status,
                                                                        column: data.column,
                                                                        siteline1: data.siteline1,
                                                                        islocked: data.islocked,
                                                                        isinternal: data.isinternal,
                                                                        completebydate: data.completebydate,
                                                                        backgroundColor: data.jobcolor
                                                                    }, true);
                                                                }
                                                                else {
                                                                    bootbox.hideAll();
                                                                    bootbox.dialog({
                                                                        message: "<span class='bigger-110'>"+data.data.message+"</span>",
                                                                        title: data.data.title,
                                                                        buttons: 			
                                                                        {
                                                                           "button" :
                                                                            {
                                                                                "label" : "Close",
                                                                                "className" : "btn-sm btn-primary"
                                                                            }
                                                                        }
                                                                    });return false;
                                                                }
                                                            } else {
                                                                bootbox.alert(data.message);
                                                            }
                                                        }, 'json'); 
                                                        return false;
                                                    }
                                                }
                                            },
                                            "button" :
                                            {
                                                "label" : "Cancel",
                                                "className" : "btn-sm btn-warning"
                                            }
                                        }
                                });
 
                            }
                            else {
                                bootbox.dialog({
                                    message: "<span class='bigger-110'>This job is current status is "+ jobstatus +", So Allocation is not possible.</span>",
                                    buttons: 			
                                    {
                                       "button" :
                                        {
                                            "label" : "Close",
                                            "className" : "btn-sm btn-primary"
                                        }
                                    }
                                });
                                return false;
                               }
                             var startdate = date.format();
                             var title = 'title';
                         },
                         eventDrop: function(event, delta, revertFunc) {
                             console.log(event);
                             if(parseInt(event.status) === 1) {
                                bootbox.dialog({
                                    message: "<span class='bigger-110'>You cannot move a task that is in progress.</span>",
                                    buttons: 			
                                    {
                                       "button" :
                                        {
                                            "label" : "Close",
                                            "className" : "btn-sm btn-primary",
                                            callback: function() {
                                                revertFunc();
                                            }
                                        }
                                    }
                                });
                                 return false;
                             }
                            if(parseInt(event.status) === 2) {
                                bootbox.dialog({
                                    message: "<span class='bigger-110'>You cannot move a task that is completed.</span>",
                                    buttons: 			
                                    {
                                       "button" :
                                        {
                                            "label" : "Close",
                                            "className" : "btn-sm btn-primary",
                                            callback: function() {
                                                  revertFunc();
                                            }
                                        }
                                    }
                                });
                                 return false;
                             }
                             bootbox.dialog({
                                     message: "<span class='bigger-110'>Are you sure you want to move this task?</span>",
                                     buttons: 			
                                     {
                                        "click" :
                                         {
                                             "label" : "OK",
                                             "className" : "btn-sm btn-success",
                                             callback: function() {
                                                 $.post(base_url+"schedule/updateschedule/q", { id:event.id, start:event.start.format(), end:event.end.format() }, function( data ) {
                                                    if(data.success) {
                                                    if(data.data.success) {

                                                    }
                                                    else {
                                                        revertFunc();
                                                        bootbox.dialog({
                                                            message: "<span class='bigger-110'>"+data.data.message+"</span>",
                                                            title: data.data.title,
                                                             buttons: 			
                                                            {
                                                               "button" :
                                                                {
                                                                    "label" : "Close",
                                                                    "className" : "btn-sm btn-primary"
                                                                }
                                                            }
                                                        });
                                                    }
                                                    } else {
                                                        revertFunc();
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
                                                revertFunc();
                                            }
                                         }
                                     }
                                 });
                         },
                         eventResize: function(event, delta, revertFunc) {
                             $.post(base_url+"schedule/updateschedule/q", { id:event.id, start:event.start.format(), end:event.end.format() }, function( data ) {
                                    if(data.success) {
                                    if(data.data.success) {

                                    }
                                    else{
                                         revertFunc();
                                         bootbox.dialog({
                                            message: "<span class='bigger-110'>"+data.data.message+"</span>",
                                            title: data.data.title,
                                             buttons: 			
                                            {
                                               "button" :
                                                {
                                                    "label" : "Close",
                                                    "className" : "btn-sm btn-primary"
                                                }
                                            }
                                        });
                                    }
                                    } else {
                                        revertFunc();
                                        bootbox.alert(data.message);
                                        return false;
                                    }
                                }, 'json');
                         },
                         eventRender: function(event, element) {

                             var view = calender.fullCalendar('getView');

                             var description = '';    
                             element.find(".fc-title").remove();
                             element.find(".fc-time").remove();
                             element.find(".fc-bg").remove();
                             element.find(".fc-content").remove();
                             var lock = '';
                             if(parseInt(event.islocked) === 1) {
                                 lock = '<span style="color:black;font-weight:bold;">L</span>';
                             }
                             
                             if(view.name === 'agendaDay') {
                                 description = lock;
                                 if(parseInt(event.isinternal) !== 1) {
                                    description = description+'&nbsp;&nbsp;<a href="'+base_url+'jobs/jobdetail/'+event.jobid+'" target="_blank"><img src="'+base_img_url+'assets/img/infview.gif" /></a>';
                                 }
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img src="'+base_img_url+'assets/img/infedit.gif" data-id="'+event.id+'" class="editappoint" /></a>';
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img id="'+event.jobid+'" src="'+base_img_url+'assets/img/news.gif"  class="detailhover" /></a>';         
                                description = description + '&nbsp;&nbsp;' + moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                description = description + '&nbsp;Job No. ' + event.jobnumber;
                                description = description + '&nbsp;&nbsp;' + event.siteline1;
                                description = description + '&nbsp;&nbsp;' + event.completebydate;
                             } else if(view.name === 'agendaWeek') {
                                description = lock+' '+moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                if(parseInt(event.isinternal) !== 1) {
                                    description = description + '&nbsp;&nbsp;<a href="'+base_url+'jobs/jobdetail/'+event.jobid+'" target="_blank"><img src="'+base_img_url+'assets/img/infview.gif" /></a>';
                                }
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img src="'+base_img_url+'assets/img/infedit.gif" data-id="'+event.id+'" class="editappoint" /></a>';
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img id="'+event.jobid+'" src="'+base_img_url+'assets/img/news.gif"  class="detailhover" /></a>';         
                                description = description + '&nbsp;Job No. ' + event.jobnumber;
                                description = description + '&nbsp;&nbsp;' + event.siteline1;
                                description = description + '&nbsp;&nbsp;' + event.completebydate;
                             }
                             else if(view.name === 'agendaMulti') {
                               description = lock;
                               if(parseInt(event.isinternal) !== 1) {
                                    description = description+'&nbsp;&nbsp;<a href="'+base_url+'jobs/jobdetail/'+event.jobid+'" target="_blank"><img src="'+base_img_url+'assets/img/infview.gif" /></a>';
                               }
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img src="'+base_img_url+'assets/img/infedit.gif" data-id="'+event.id+'" class="editappoint" /></a>';
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img id="'+event.jobid+'" src="'+base_img_url+'assets/img/news.gif"  class="detailhover" /></a>';         
                                description = description + '&nbsp;&nbsp;' + moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                description = description + '&nbsp;Job No. ' + event.jobnumber;
                                description = description + '&nbsp;&nbsp;' + event.siteline1;
                                description = description + '&nbsp;&nbsp;' + event.completebydate;
                             } else {
                                description = lock+' '+moment(event.start).format("HH:mm") + ' - ' + moment(event.end).format("HH:mm");
                                if(parseInt(event.isinternal) !== 1) {
                                    description = description + '<br /><a href="'+base_url+'jobs/jobdetail/'+event.jobid+'" target="_blank"><img src="'+base_img_url+'assets/img/infview.gif" /></a>&nbsp;Job No. ' + event.jobnumber;
                                }
                                description = description + '<br /><a href="javascript:void(0);"><img src="'+base_img_url+'assets/img/infedit.gif" data-id="'+event.id+'" class="editappoint" /></a>';
                                description = description + '&nbsp;&nbsp;<a href="javascript:void(0);"><img id="'+event.jobid+'" src="'+base_img_url+'assets/img/news.gif" class="detailhover" /></a>';         
                                description = description + '<br />' + event.siteline1;
                                description = description + '<br />' + event.completebydate;
                             }

                             element.append(description);
                         }   
                    });
                };
               
                var htimeslot = $("#calendarform #timeslot").val();
                var calenderview = $("#calendarform #calenderview").val();
                calenderinit(htimeslot, calenderdate, calenderview);

                var updateevent = function(start, end, id) {
                    $.post(base_url+"schedule/updateschedule/q", { id:id, start:start, end:end }, function( data ) {
                        if(parseInt(data.success) === 1) {
                            
                        }
                        else{
                            return false;
                        }
                    }, 'json');
                };
                
                $("#timeslot").on('change', function() {
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
                
                $(document).on('click', '#deleteappoint', function() {

                    var message = "<span class='bigger-110'>Are you sure you want to delete this appointment?</span>";
                    var s_id = $("#editappoint_form #happtid").val();
                    var hlock = $("#editappoint_form #hlock").val();
                    var hstatus = $("#editappoint_form #hstatus").val();
                     
                    if(parseInt(hlock) === 1) {
                        bootbox.dialog({
                            message: "<span class='bigger-110'>This appointment is locked and cannot be deleted.</span>",
                            buttons: 			
                            {
                               "button" :
                                {
                                    "label" : "Close",
                                    "className" : "btn-sm btn-primary",
                                    callback: function() {
                                        bootbox.hideAll();
                                        modaloverlap();
                                    }
                                }
                            }
                        });
                        return false;
                    }
                    
                if(parseInt(hstatus) === 0) {
                    bootbox.dialog({
                        message: message,
                        buttons: 			
                        {
                            "click" :
                            {
                                "label" : "Yes",
                                "className" : "btn-sm btn-success",
                                callback: function() {
                                    $.post( base_url+"schedule/deleteschedule/q", { apptid:s_id }, function( data ) {
                                        bootbox.hideAll();
                                        if(data.success) {
                                            $("#editAppointModal").modal('hide');
                                            calender.fullCalendar('removeEvents', s_id);
                                        } else{
                                            bootbox.alert(data.message, function(){ modaloverlap(); });
                                        }
                                    }, 'json');
                                    
                                }
                            },
                            "button" :
                            {
                                "label" : "Cancel",
                                "className" : "btn-sm btn-primary falcon-warning-btn",
                                callback: function() {
                                    bootbox.hideAll();
                                    modaloverlap();
                                }
                            }
                        }
                    });
                }
                else {
                    var st=$('#editAppointModal #status').html();
                     bootbox.dialog({
                        message: "<span class='bigger-110'>This appointment is "+st+" and cannot be deleted.</span>",
                        buttons: 			
                        {
                           "button" :
                            {
                                "label" : "Close",
                                "className" : "btn-sm btn-primary",
                                callback: function() {
                                    bootbox.hideAll();
                                    modaloverlap();
                                }
                            }
                        }
                    });
                    return false;
                }
            });
            
             $(document).on('click', "#calendar .calendersortbtn", function() {
                 var contactid = $(this).attr("data-id");
                var name = $(this).attr("data-name");
                var moment = $('#calendar').fullCalendar('getDate');
                var activedate = moment.format(); 
                
                bootbox.dialog({
                    message: "<span class='bigger-110'>Do you want to sort all open tasks for <b>"+name+"</b> on <b>"+activedate+"</b>?</span>",
                    buttons: 			
                    {
                        "click" :
                        {
                            "label" : "Yes",
                            "className" : "btn-sm btn-success",
                            callback: function() {
                                $.post( base_url+"schedule/sortschedule/q", { contactid:contactid, activedate:activedate }, function( data ) {
                                    if(data.success) {
                                        calender.fullCalendar('refetchEvents');
                                        calender.fullCalendar( 'gotoDate', activedate );
                                        bootbox.hideAll();
                                    }
                                    else {
                                        bootbox.alert(data.message);
                                    }
                                }, 'json');
                            }
                        },
                        "button" :
                        {
                            "label" : "Cancel",
                            "className" : "btn-sm btn-primary falcon-warning-btn",
                            callback: function() {
                                 bootbox.hideAll();
                            }
                        }
                    }
                });
             });
            
            
            $(document).on('click', "#calendar .timesheetbtn", function() {
                var contactid = $(this).attr("data-id");
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
           
                $.post( base_url+"schedule/timesheetschedule/q", { get:1, contactid:contactid, activedate:activedate }, function( data ) {
                    var result = '';
                    var dtetimestamp = '';
                    if(data.success) {
                        $.each( data.data, function( key, value ) {
                            result = result+'<tr>';
                            result = result+'<td>'+value.dte+'</td>';
                            result = result+'<td>'+value.apptid+'</td>';
                            result = result+'<td>'+value.jobnumber+'</td>';
                            result = result+'<td>'+value.sitesuburb+'</td>';
                            result = result+'<td>'+value.start+'</td>';
                            result = result+'<td>'+value.duration+'</td>';
                            result = result+'<td>'+value.notes+'</td>';
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
            
            $("#addinternaljob_form #cancel").on('click', function() {
                $("#addInternalJobModal").modal('hide');
            });
            
             $("#addinternaljob_form #modalsave").on('click', function() {
                var technician = $("#addinternaljob_form #technician");
                var jobid = $("#addinternaljob_form #jobid");
                var activity = $("#addinternaljob_form #activity");
                var date = $("#addinternaljob_form #date");
                var description = $("#addinternaljob_form #description");

                $("#addinternaljob_form span.help-block").remove();
            
                if($.trim(technician.val()) === "") {
                    $(technician).parent().addClass("has-error");
                    $('<span class="help-block">This field is required.</span>').appendTo(technician.parent());
                } else {
                    $(technician).parent().removeClass("has-error");
                }

                if($.trim(jobid.val()) === "") {
                    $(jobid).parent().addClass("has-error");
                    $('<span class="help-block">This field is required.</span>').appendTo(jobid.parent());
                } else {
                    $(jobid).parent().removeClass("has-error");
                }
                
                if($.trim(activity.val()) === "") {
                    $(activity).parent().addClass("has-error");
                    $('<span class="help-block">This field is required.</span>').appendTo(activity.parent());
                } else {
                    $(activity).parent().removeClass("has-error");
                }

                if($.trim(date.val()) === "") {
                    $(date).parent().parent().addClass("has-error");
                    $('<span class="help-block">This field is required.</span>').appendTo(date.parent().parent());
                } else {
                    $(date).parent().parent().removeClass("has-error");
                }
                
                if($.trim(description.val()) === "") {
                    $(description).parent().addClass("has-error");
                    $('<span class="help-block">This field is required.</span>').appendTo(description.parent());
                } else {
                    $(description).parent().removeClass("has-error");
                }

                if($.trim(description.val()) === "" || $.trim(technician.val()) === "" || $.trim(jobid.val()) === "" || $.trim(date.val()) === "" || $.trim(activity.val()) === "") {
                    return false;
                }

                $("#addinternaljob_form #modalsave").button('loading'); 
                $("#addinternaljob_form #cancel").button('loading'); 
                $.post( base_url+"schedule/createinternaltask/q", $("#addinternaljob_form").serialize(), function( data ) {
                    if(data.success) {
                        $("#addinternaljob_form #modalsave").removeAttr('disabled'); 
                        document.location.reload();
                    }
                    else {
                        bootbox.alert(data.message, function(){ modaloverlap(); });
                    }
                       
                }, 'json');
                return false;
            });
            
             $(document).on('click', "#calendar .bumpschedule", function() {
                var contactid = $(this).attr("data-id");
                var name = $(this).attr("data-name");
                var moment = $('#calendar').fullCalendar('getDate');
                var activedate = moment.format(); 
                
                bootbox.dialog({
                    message: "<span class='bigger-110'>Are you sure you want to move all open tasks for <b>"+name+"</b> to the next business day?</span>",
                    buttons: 			
                    {
                        "click" :
                        {
                            "label" : "Yes",
                            "className" : "btn-sm btn-success",
                            callback: function() {

                                $.post( base_url+"schedule/bumpschedule/q", { contactid:contactid, activedate:activedate }, function( data ) {
                                    if(data.success) {
                                        calender.fullCalendar('refetchEvents');
                                        calender.fullCalendar( 'gotoDate', data.data.nextdate );
                                        bootbox.hideAll();
                                    }
                                    else {
                                        bootbox.alert(data.message);
                                    }
                                }, 'json');
                            }
                        },
                        "button" :
                        {
                            "label" : "Cancel",
                            "className" : "btn-sm btn-primary falcon-warning-btn",
                            callback: function() {
                                bootbox.hideAll();
                            }
                        }
                    }
                });
            });
            
            $(document).on('mouseover', "#calendar .detailhover", function() {
                var jobid = $(this).attr("id");
                jobdetailfunc(jobid);
            });
 
            
            
            $(document).on('change', "#calendarform #technicians", function() {
         
                $("#calendarform #technicion").val('');
                $("#calendarform #technicion").selectpicker('refresh');
                $("#scheduleinnerloading").css('display','block');
                calender.fullCalendar('refetchEvents');
            });
            $(document).on('change', "#calendarform #technicion", function() {
                
                $("#calendarform #technicians").val('');
                $("#calendarform #technicians").selectpicker('refresh');
                
                $("#scheduleinnerloading").css('display','block');
                calender.fullCalendar('refetchEvents');
            });
        }
        
        $(document).on('change', "#editappoint_form #activity", function() {
 
            var id = $(this).val();
            var apptid = $('#happtid').val();
            
            $(this).attr('disabled', 'disabled');
            $.post( base_url+"schedule/updateactivity/q", { activityid:id, apptid:apptid }, function( data ) {
                if(data.success){
                    $("#editappoint_form #activity").removeAttr('disabled');
                }
                else {
                    $("#editappoint_form #activity").removeAttr('disabled');
                    bootbox.alert(data.message, function(){ modaloverlap(); });
                }
                
            }, 'json');
        });
        
        $(document).on('change', "#editappoint_form #technician", function() {
 
            var id = $.trim($(this).val());
            if(id === "") {
                return false;
            }
            var techname = $("#editappoint_form #technicion option:selected").text();  
            
            $('#inviteappoint_form').trigger("reset");
            $("#inviteappoint_form span.help-block").remove();
            $("#inviteappoint_form .has-error").removeClass("has-error");
            $('#inviteappoint_form #modalsave').removeAttr("disabled");
            $('#inviteappoint_form #cancel').removeAttr("disabled");
            $("#inviteAppointModalLabel").html('Invite another technician');
            $('#inviteappoint_form #technician').val(id);
            $('#inviteappoint_form #happtid').val($('#editappoint_form #happtid').val());

            $("#inviteAppointModal").modal();
            $("#inviteappoint_form").show();
        });
        
        $("#inviteappoint_form #modalsave").on('click', function() {
             
            var date = $("#inviteappoint_form #date");
            var apptid = $('#inviteappoint_form #happtid').val();
            var id = $('#inviteappoint_form #technician').val();
           
            $("#inviteappoint_form span.help-block").remove();
            $("#inviteappoint_form .has-error").removeClass("has-error");
            
            if($.trim(date.val()) === "") {
                $(date).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(date.parent());
            } else {
                $(date).parent().removeClass("has-error");
            }
            
            if($.trim(date.val()) === ""){
                 return false;
            }
            date = date.val();
            $("#inviteappoint_form #modalsave").button('loading');
            $("#inviteappoint_form #cancel").button('loading');
         
            $.post( base_url+"schedule/inviteappointment/q", { date:date, contactid:id, apptid:apptid }, function( data ) {
                $('#inviteappoint_form #modalsave').removeClass("disabled");
                $('#inviteappoint_form #modalsave').html("Save");
                $('#inviteappoint_form #cancel').removeClass("disabled");
                $('#inviteappoint_form #modalsave').removeAttr("disabled");
                $('#inviteappoint_form #cancel').removeAttr("disabled");
                var options = '<option value="">Select Technician</option>';
                
                if(data.success) {
                    if(data.data.success) {
                        $.each(data.data.invites, function( key, value ) {
                            options = options + '<option value="'+value.contactid+'">'+value.firstname+'</option>';
                        });

                        $("#editappoint_form #technician").removeAttr('disabled');
                        $("#editappoint_form #technician").html(options);
                        $("#inviteAppointModal").modal('hide');
                        calender.fullCalendar('refetchEvents');
                        modaloverlap();
                    }
                    else {
                        bootbox.dialog({
                            message: "<span class='bigger-110'>"+data.data.message+"</span>",
                            title: data.data.title,
                            buttons: 			
                            {
                               "button" :
                                {
                                    "label" : "Close",
                                    "className" : "btn-sm btn-primary"
                                }
                            }
                        });
                        return false;
                    }
                } else {
                    bootbox.alert(data.message);
                }
            }, 'json');    

            return false;
        });
        
        $("#inviteAppointModal #cancel").on('click', function() {
            $("#editappoint_form #technician").removeAttr('disabled');
            $("#editappoint_form #technician").val("");
            $("#inviteAppointModal").modal('hide');
            modaloverlap();
        });
        
        $(document).on('click', '#editAppointModal a.play',  function() {
            
            var id = $("#editappoint_form #happtid").val();
            var p = parseInt($("#editappoint_form #hstatus").val());
            var jobid = $("#editappoint_form #jobid").val();//$("#calendarform #jobid").val();
            if(p===2) {
                return false;
            }
 
            bootbox.dialog({
                message: "<span class='bigger-110'>Are you sure you want to start this task?</span>",
                buttons: 			
                {
                    "click" :
                    {
                        "label" : "Yes",
                        "className" : "btn-sm btn-success",
                        callback: function() {
                            //console.log("yes"); 
                            $.post( base_url+"schedule/startappointment/q", { id:id, jobid:jobid }, function( data ) {
                                //console.log(data);
                                if(data.success) {
                                    populateappointment(data.data);
                                    bootbox.hideAll();
                                    calender.fullCalendar('refetchEvents');
                                    calender.fullCalendar( 'gotoDate', data.data[0].scheduledate);
                                    modaloverlap();
                                }
                                else {
                                    bootbox.alert(data.message);
                                }
                            }, 'json');
                        }
                    },
                    "button" :
                    {
                        "label" : "Cancel",
                        "className" : "btn-sm btn-primary falcon-warning-btn",
                        callback: function() {
                            bootbox.hideAll();
                            modaloverlap();
                        }
                    }
                }
            });
        });
        
        $(document).on('click', '#editAppointModal #lockicon',  function() {
            var islocked = parseInt($("#editappoint_form #hlock").val());
            $('#lockappoint_form').trigger("reset");
            $("#lockappoint_form span.help-block").remove();
            $("#lockappoint_form .has-error").removeClass("has-error");
            $('#lockappoint_form #modalsave').removeAttr("disabled");
            $('#lockappoint_form #cancel').removeAttr("disabled");
            $('#lockappoint_form #happtid').val($('#editappoint_form #happtid').val());
            $('#lockappoint_form #hlock').val($('#editappoint_form #hlock').val());
            if(islocked === 0) {
               $('#lockappoint_form #lockAppointModalLabel').html("Reason for locking appointment:"); 
            }
            if(islocked === 1) {
               $('#lockappoint_form #lockAppointModalLabel').html("Reason for unlocking appointment:"); 
            }
            $("#lockAppointModal").modal();
            $("#lockappoint_form").show();
        });
        
        $("#lockappoint_form #cancel").on('click', function() {
            $("#lockAppointModal").modal('hide');
            modaloverlap();
        });
        
        $("#lockappoint_form #modalsave").on('click', function() {
            
            var apptid = $('#lockappoint_form #happtid').val();
            var reason = $('#lockappoint_form #reason');
            var islock = $('#lockappoint_form #hlock').val();
            
           
            $("#lockappoint_form span.help-block").remove();
            $("#lockappoint_form .has-error").removeClass("has-error");
            
            if($.trim(reason.val()) === "") {
                $(reason).parent().addClass("has-error");
                $('<span class="help-block">This field is required.</span>').appendTo(reason.parent());
            } else {
                $(reason).parent().removeClass("has-error");
            }
            
            if($.trim(reason.val()) === ""){
                 return false;
            }
            reason = reason.val();
  
            $("#lockappoint_form #modalsave").button('loading');
            $("#lockappoint_form #cancel").button('loading');
         
            $.post( base_url+"schedule/lockappointment/q", { reason:reason, apptid:apptid, islock:islock }, function( data ) {
 
                if(data.success) {    
                    $("#lockappoint_form #modalsave").button('reset');
                    $("#lockappoint_form #cancel").button('reset');
                    $("#lockAppointModal").modal('hide');
                    populateappointment(data.data);
                    calender.fullCalendar('refetchEvents');
                    modaloverlap();
                } else {
                    $("#lockappoint_form #modalsave").button('reset');
                    $("#lockappoint_form #cancel").button('reset');
                    $("#lockAppointModal").modal('hide');
                    bootbox.alert(data.message, function(){ modaloverlap(); });
                }
            }, 'json');
            
            return false;
        });
        
        $(document).on('click', '#stopappoint_form input[name="closeaction"]', function() {
            if($('#stopappoint_form input:radio[name=closeaction]:checked').val() === "reallocate"){
                $('#stopappoint_form #stoptech').parent().show();
            } else {
                $('#stopappoint_form #stoptech').parent().hide();
                $("#stopappoint_form #hadjust").val("0");
            }
        });

        $(document).on('click', '#editAppointModal a.stop',  function() {
            
            var id = $("#editappoint_form #happtid").val();
            $("#stopappoint_form #happtid").val(id);
            
                bootbox.dialog({
                    message: "<span class='bigger-110'>Are you sure you want to stop this task?</span>",
                    buttons: 			
                    {
                        "click" :
                        {
                            "label" : "Yes",
                            "className" : "btn-sm btn-success",
                            callback: function() {
                                bootbox.hideAll();
                                $('#stopappoint_form').trigger("reset");
                                $("#stopappoint_form span.help-block").remove();
                                $("#stopappoint_form .has-error").removeClass("has-error");
                                $('#stopappoint_form #modalsave').removeAttr("disabled");
                                $('#stopappoint_form #cancel').removeAttr("disabled");
                                $('#stopappoint_form #happtid').val($('#editappoint_form #happtid').val());
                                $('#stopappoint_form #stoptech').parent().hide();

                                $("#stopAppointModal").modal();
                                $("#stopAppointModal center").show();
                                $("#stopappoint_form").hide();
                                
                                $.post( base_url+"schedule/loadstopappointment/q", { id:id }, function( data ) {
                                    $("#stopAppointModal center").hide();
                                    if(data.success) {
                                        $.each( data.data, function( key, value ) {
                                            $("#stopappoint_form #starttime").html(value.start);
                                            $("#stopappoint_form #endtime").html(value.end);
                                            $("#stopappoint_form #duration").html(value.duration);
                                            $("#stopappoint_form #hadjust").val("0");
                                            $("#stopappoint_form #notes").val("DCFM Job No: #"+value.ownerjobid+", Your Ref: Confirmation that this job has been completed.");
                                            if(value.closeradio === 1) {
                                                $('#stopappoint_form #closeaction3').prop('checked', true);
                                            } else {
                                                $('#stopappoint_form #closeaction1').prop('checked', true);
                                            }
                                            
                                        });
                                        $("#editAppointModal").modal('hide');
                                        $("#stopAppointModal center").hide();
                                        $("#stopappoint_form").show();
                                    }
                                    else{
                                        bootbox.alert(data.message);
                                    }
                                }, 'json');
                            }
                        },
                        "button" :
                        {
                            "label" : "Cancel",
                            "className" : "btn-sm btn-primary falcon-warning-btn",
                            callback: function() {
                                bootbox.hideAll();
                                modaloverlap();
                            }
                        }
                    }
             });
        });
        
        
        $("#stopappoint_form #adjust").on('click', function() {
            var task = $("#stopappoint_form input[type='radio']:checked").val();
            var tech = $("#stopappoint_form #stoptech");
            var id = $("#stopappoint_form #happtid").val();
            $(tech).parent().removeClass('has-error');
            if(task === 'reallocate') {
                if(tech.val()==="") {
                    $(tech).parent().addClass('has-error');
                    return false;
                }
            }
            tech = tech.val();
            $("#stopappoint_form #modalsave").button('loading');
            $("#stopappoint_form #cancel").button('loading');
            $("#stopappoint_form span.help-block").remove();
            $("#stopappoint_form .has-error").removeClass("has-error");
            $("#stopappoint_form #hadjust").val("0");

            $.post( base_url+"schedule/adjustappointment/q", { id:id, task:task, tech:tech }, function( data ) {
                $("#stopappoint_form #modalsave").button('reset');
                $("#stopappoint_form #cancel").button('reset');
                
                if(data.success) {
                    $.each( data.data, function( key, value ) {
                        $("#stopappoint_form #starttime").html(value.start);
                        $("#stopappoint_form #endtime").html(value.end);
                        $("#stopappoint_form #duration").html(value.duration);
                        $("#stopappoint_form #hadjust").val("1");
                        if(data.message !== '') {
                            $("#stopappoint_form #duration").parent().addClass('has-error');
                            $("#stopappoint_form #duration").parent().append('<span class="help-block">'+data.message+'</span>');
                        }
                    });
                } else {
                    bootbox.alert(data.message, function(){ modaloverlap(); });
                }
            }, 'json');
            return false;
        });
        
        $("#stopappoint_form #modalsave").on('click', function() {
            var task = $("#stopappoint_form input[type='radio']:checked").val();
            var tech = $("#stopappoint_form #stoptech");
            var id = $("#stopappoint_form #happtid").val();
            var adjust = $("#stopappoint_form #hadjust").val();
            var notes = $("#stopappoint_form #notes").val();
            $(tech).parent().removeClass('has-error');
            if(task === 'reallocate') {
                if(tech.val()==="") {
                    $(tech).parent().addClass('has-error');
                    return false;
                }
            }
            tech = tech.val();
            $('#stopappoint_form .status').html('');
            $("#stopappoint_form #modalsave").button('loading');
            $("#stopappoint_form #cancel").button('loading');
            $("#stopappoint_form span.help-block").remove();
            $("#stopappoint_form .has-error").removeClass("has-error");
            $.post( base_url+"schedule/closeappointment/q", { id:id, task:task, tech:tech, adjust:adjust, notes:notes }, function( data ) {
                $("#stopappoint_form #modalsave").button('reset');
                $("#stopappoint_form #cancel").button('reset');
                if(data.success) {
                    if(parseInt(data.data.success) === 1) {
                        document.location.reload();
                    }
                    if(parseInt(data.data.success) === 0) {
                        $.each( data.data.data, function( key, value ) {
                            $("#stopappoint_form #starttime").html(value.start);
                            $("#stopappoint_form #endtime").html(value.end);
                            $("#stopappoint_form #duration").html(value.duration);
                        });

                        $("#stopappoint_form #duration").parent().addClass('has-error');
                        $("#stopappoint_form #duration").parent().append('<span class="help-block">'+data.data.msg+'</span>');
                    }
                    if(parseInt(data.data.success) === 3) {
                        $('#stopappoint_form .status').html('<div class="alert alert-danger" >'+data.data.msg+'</div>');
                    }
                }
                else{
                    bootbox.alert(data.message);
                }
            }, 'json');
            return false;
        });
        
        $("#stopAppointModal #cancel").on('click', function() {
            $("#stopAppointModal").modal('hide');
            modaloverlap();
        });
        
        $(document).on('click', '#editAppointModal a.pause',  function() {
            
            var id = $("#editappoint_form #happtid").val();
            var p = parseInt($("#editappoint_form #hstatus").val());
            if(p===0 || p===2) {
                return false;
            }

            bootbox.dialog({
                message: "<span class='bigger-110'>Are you sure you want to pause this task?</span>",
                buttons: 			
                {
                    "click" :
                    {
                        "label" : "Yes",
                        "className" : "btn-sm btn-success",
                        callback: function() {
                           $.post( base_url+"schedule/pauseappointment/q", { id:id }, function( data ) {
                                if(data.success) {
                                    bootbox.hideAll();
                                    $("#editAppointModal").modal('hide');
                                    document.location.reload();
                                }
                                else{
                                    bootbox.alert(data.message);
                                }
                            }, 'json');
                        }
                    },
                    "button" :
                    {
                        "label" : "Cancel",
                        "className" : "btn-sm btn-primary falcon-warning-btn",
                        callback: function() {
                           bootbox.hideAll();
                           modaloverlap();
                        }
                    }
                }
            });
        });
        
        var populateappointment = function(data) {

           var options = '<option value="">Select Technician</option>';
           $.each( data, function( key, value ) {
               $("#editappoint_form #customer").val(value.companyname);
               $("#editappoint_form #contact").val(value.sitecontact);
               $("#editappoint_form #address1").val(value.address1);
               $("#editappoint_form #address2").val(value.siteline3);
               $("#editappoint_form #jobsuburb").val(value.sitesuburb);
               $("#editappoint_form #jobstate").val(value.sitestate);
               $("#editappoint_form #jobpostcode").val(value.sitepostcode);
               $("#editappoint_form #phone").val(value.sitephone);
               $("#editappoint_form #contact").val(value.sitecontact);
               $("#editappoint_form #jobid").val(value.jobid);
               $("#editappoint_form #jobnumber").val(value.jobnumber);
               $("#editappoint_form #dcfmjobid").val(value.ownerjobid);
               $("#editappoint_form #date").val(value.dte);
               $("#editappoint_form #start").val(value.start);
               $("#editappoint_form #activity").val(value.activity_id);
               $("#editappoint_form #technician").val(value.firstname);
               $("#editappoint_form #duration").val(value.duration);
               $("#editappoint_form #apptid").val(value.apptid);
               $("#editappoint_form #description").val(value.jobdescription);
               $("#editappoint_form #lockreason").val(value.lockreason);
               $("#editappoint_form #happtid").val(value.apptid);
               $("#editappoint_form #hstatus").val(value.status);
               $("#editappoint_form #hlock").val(value.islocked);
               $("#editappoint_form .pause").removeClass("active");
               $("#editappoint_form .play").addClass("active");
               $("#editappoint_form .stop").css("display", "none");
               $("#editappoint_form .play").css("display", 'inline');
               if(parseInt(value.status) === 0) {
                    $("#editappoint_form .pause").css("display", "none");
               }
               else{
                   $("#editappoint_form .pause").css("display", "inline");
               }
               if(parseInt(value.status) === 1) {
                   $("#editappoint_form .pause").addClass("active");
                   $("#editappoint_form .play").css("display", 'none');
                   $("#editappoint_form .stop").css("display", "inline");
               }

               if(parseInt(value.status) === 2) {
                    $("#editappoint_form .stop").css("display", "none");
                    $("#editappoint_form .play").removeClass("active");
                    $("#editappoint_form .pause").removeClass("active");
                    $("#editappoint_form #checkboxcomplete").attr("checked", "checked");
                    $("#editappoint_form #checkboxcomplete").attr("disabled", "disabled");
                } else {
                   $("#editappoint_form #checkboxcomplete").removeAttr("checked");
                   $("#editappoint_form #checkboxcomplete").removeAttr("disabled");
                }

               $("#editappoint_form #lockicon i").removeClass("fa-lock");
               $("#editappoint_form #lockicon i").removeClass("fa-unlock-alt");
               if(parseInt(value.islocked) === 0) {
                   $("#editappoint_form #lockreason").parent().parent().css("display", "none");
                   $("#editappoint_form #lockicon i").addClass("fa-unlock-alt");
               } else {
                   $("#editappoint_form #lockreason").parent().parent().css("display", "block");
                   $("#editappoint_form #lockicon i").addClass("fa-lock");
               }

               $("#editappoint_form #status").removeAttr('class');
               if(parseInt(value.status) === 1) {
                    $("#editappoint_form #status").html("In progress");
                   $("#editappoint_form #status").addClass('statusprogress control-label');
               } else if(parseInt(value.status) === 2) {
                   $("#editappoint_form #status").html("Completed");
                   $("#editappoint_form #status").addClass('statuscomplete control-label');
               } else {
                   $("#editappoint_form #status").html("Scheduled");
                   $("#editappoint_form #status").addClass('statusnone control-label');
               }

               $.each(value.invitedata, function( key1, value1 ) {
                   options = options + '<option value="'+value1.contactid+'">'+value1.firstname+'</option>';
               });

           });
           $("#editappoint_form #technician").html(options);
           $("#stopappoint_form #stoptech").html(options);
        };
        
        
        $(document).on('click', '#calendar .editappoint',  function() {
            
            var id = $(this).attr('data-id');
            
            $('#editappoint_form').trigger("reset");
            $("#editappoint_form span.help-block").remove();
            $("#editappoint_form .has-error").removeClass("has-error");
            $('#editappoint_form #modalsave').removeAttr("disabled");
            $("#editappoint_form #technician").removeAttr('disabled');
            $("#editappoint_form #activity").removeAttr('disabled');

            $("#editAppointModal").modal();
            $('#editAppointModal center').show();
            $("#editappoint_form").hide();
            var options = '<option value="">Select Technician</option>';
            $("#editappoint_form #technician").html(options);
  
            $.get( base_url+"schedule/loadappointment/q", { 'get':1, 'id':id }, function( data ) {
                
                $('#editAppointModal center').hide();
                if(data.success){
                    populateappointment(data.data);
                    $("#editappoint_form").show();
                }
                else {
                    bootbox.alert(data.message);
                }
            }, 'json');
        });
        
        $("#editAppointModal #cancel").on('click', function() {
            $("#editAppointModal").modal('hide');
        });
        
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