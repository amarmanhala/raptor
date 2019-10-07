/* Function to load technician jobs and technicians on map */
/* global google, base_img_url, centerLatlng, jobsArray, base_url, techsArray, bootbox */

"use strict";
$( document ).ready(function() {
    $('input').on('ifChecked', function(event){
        var id = $(this).attr('id');
        if(id === 'showtechnicians') {
            $("#status").val("In Progress").trigger("change");
        }
    });
    
    $(document).on("click", "#techmap #refresh", function() {
       var fromdate = $("#techmap #fromdate").val(); 
       var todate = $("#techmap #fromdate").val();
       if(fromdate === '' || todate === '') {
           bootbox.alert('Select From Date and To Date');
           return false;
       }
       
       LoadGmaps();
       
    });
    
    var fromdate = null;
    var todate = null;
    $(document).on('change', '#fromdate', function() {
        fromdate = $('#fromdate').datepicker("getDate");
        todate = $('#todate').datepicker("getDate");
        $('input[name="todate"]').datepicker('setStartDate', fromdate);
        
        fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
        todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
        if(todate<fromdate) {
            $('#todate').val($('#fromdate').val());
            $('#todate').datepicker('update');
        }
    });
    
    $(document).on('change', '#todate', function() {
        fromdate = $('#fromdate').datepicker("getDate");
        todate = $('#todate').datepicker("getDate");
        $('input[name="fromdate"]').datepicker('setEndDate', todate);
        
        fromdate=new Date(fromdate.getFullYear(),fromdate.getMonth(),fromdate.getDate(),0,0,0);
        todate = new Date(todate.getFullYear(),todate.getMonth(),todate.getDate(),0,0,0);
        if(todate<fromdate) {
            $('#fromdate').val($('#todate').val());
            $('#fromdate').datepicker('update');
        }
    });
    
    var LoadGmaps = function() {
        
        $('.map-overlay').show();
        $.get( base_url+"techmap/loadmapdata", $('#techmap').serialize(), function( response ) {
                
            if (response.success) {
                var jobsArray = response.data.jobs;
                var techsArray = response.data.techs;
                var centerLatlng = response.data.centerlatlong;
                
                
                var myLatlng = new google.maps.LatLng(centerLatlng[0],centerLatlng[1]);
                var myOptions = {
                    zoom: 6,
                    center: myLatlng,
                    disableDefaultUI: true,
                    navigationControl: false,
                    mapTypeControl: false,
                    streetViewControl: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("MyGmaps"), myOptions);
                var iconBase = base_img_url  + 'assets/img/';
                var markerimage ;
                var markers=[];
                var infowindows = [];
                var i;
                for (i = 0; i < jobsArray.length; i++){
                    if(jobsArray[i]['STATUS'] === 'Unscheduled'){ 
                            markerimage = 'black.png';
                    }else if(jobsArray[i]['STATUS'] === 'Scheduled'){ 
                            markerimage = 'green.png';
                    }else if(jobsArray[i]['STATUS'] === 'In Progress'){ 
                            markerimage = 'teal.png';
                    }else if(jobsArray[i]['STATUS'] === 'Completed'){ 
                            markerimage = 'blue.png';
                    }else if(jobsArray[i]['STATUS'] === 'Overdue'){
                            markerimage = 'red.png';
                    }else{ 
                            // for cancelled status
                            markerimage = 'grey.png';
                    }
                    if(jobsArray[i]['siteline1'] === null){
                            jobsArray[i]['siteline1'] = '';
                    }
                    markers[i] = new google.maps.Marker({
                        position: new google.maps.LatLng(jobsArray[i]['latitude_decimal'],jobsArray[i]['longitude_decimal']),
                        map: map,
                        title:jobsArray[i]['siteline1'],
                        icon: iconBase + markerimage
                    });
                    markers[i].index = i;
                    if(jobsArray[i]['siteline1'] === null){
                        jobsArray[i]['siteline1'] = '';
                    }
                    if(jobsArray[i]['address'] === null){
                        jobsArray[i]['address'] = '';
                    }
                    if(jobsArray[i]['suburb'] === null){
                        jobsArray[i]['suburb'] = '';
                    }
                    if(jobsArray[i]['state'] === null){
                        jobsArray[i]['state'] = '';
                    }
                    if(jobsArray[i]['postcode'] === null){
                        jobsArray[i]['postcode'] = '';
                    }
                    if(jobsArray[i]['jobid'] === null){
                        jobsArray[i]['jobid'] = '';
                    }
                    if(jobsArray[i]['custordref'] === null){
                        jobsArray[i]['custordref'] = '';
                    }
                    if(jobsArray[i]['duedate'] === null){
                        jobsArray[i]['duedate'] = '';
                    }
                    if(jobsArray[i]['duetime'] === null){
                        jobsArray[i]['duetime'] = '';
                    }
                    if(jobsArray[i]['jobdescription'] === null){
                        jobsArray[i]['jobdescription'] = '';
                    }

                    infowindows[i] = new google.maps.InfoWindow({
                        content: "<b><center>"+ jobsArray[i]['siteline1'] + "</center> </b></br>" +
                        jobsArray[i]['address'] + "</br>" +
                        jobsArray[i]['suburb'] + " " + jobsArray[i]['state'] + " " + jobsArray[i]['postcode'] + "</br>" +
                        'Job no <a href="'+base_url+'jobs/jobdetail/'+jobsArray[i]['jobid'] +'" target="_blank">' + jobsArray[i]['jobid'] + '</a></br>' + 
                        "Order no : " + jobsArray[i]['custordref'] + "</br>" +
                        "Due: " + jobsArray[i]['duedate'] + " " + jobsArray[i]['duetime'] + "</br>" + 
                        '<center><a title="' + jobsArray[i]['jobdescription'] + '"' + ' href="javascript:void(0)" > Description </a></center>'
                    });
                    google.maps.event.addListener(markers[i], "click", function() {
                            infowindows[this.index].open(map, markers[this.index]);
                    });
                }
                var techStatusColor;
                var techmarkers=[];
                var techinfowindows = [];
                var j;
                for (j = 0; j < techsArray.length; j++){
                    if(techsArray[j]['techstatus'] === 'Working'){ 
                            techStatusColor = '#000000';
                    }else if(techsArray[j]['techstatus'] === 'Travelling'){ 
                            techStatusColor = '#0000FF';
                    }else if(techsArray[j]['techstatus'] === 'Onsite'){ 
                            techStatusColor = '#008000';
                    }else if(techsArray[j]['techstatus'] === 'OFFLINE'){
                            techStatusColor = '#A52A2A';
                    }
                    if(techsArray[j]['techstatus'] === null){
                            techsArray[j]['techstatus'] = '';
                    }
                    if(techsArray[j]['NAME'] === null){
                            techsArray[j]['NAME'] = '';
                    }
                    if(techsArray[j]['arrivaltime'] === null){
                            techsArray[j]['arrivaltime'] = '';
                    }
                    if(techsArray[j]['latitude_decimal'] !== null || techsArray[j]['longitude_decimal'] !== null){
                        techmarkers[j] = new google.maps.Marker({
                            position: new google.maps.LatLng(techsArray[j]['latitude_decimal'],techsArray[j]['longitude_decimal']),
                            map: map,
                            title: techsArray[j]['NAME'],
                            icon: iconBase + 'techicon.png'
                        });	
                        techmarkers[j].index = j;
                        var techJobs = techsArray[j]['jobs'];
                        var content = '';
                        content = content + "<center><b>" + techsArray[j]['NAME'] + "</b></center></br>"; 
                        content = content + '<div class="row"><div class="col-xs-6"><div>Status: <span style=font-weight:bold;color:' + techStatusColor + ">" + techsArray[j]['techstatus'] + "</span></div>"; 
                        content = content + '<div><span style="font-weight:bold">Est Arrival : </span><span style="font-weight:bold;" >' + techsArray[j]['arrivaltime'] + "</span></div></div>" ; 
                        content = content + '<div class="col-xs-6"><i class="fa fa-smile-o" style="font-size:80px"></i></div></div></br>';
                        
                        content = content + '<table class="table table-striped table-condensed table-bordered"> <tr> <th>Job</th> <th>Date</th> <th>Time</th> <th>Address</th> </tr>' ;
                        var z;
                        for(z = 0; z < techJobs.length; z++){
                                content = content + '<tr> <td>' + techJobs[z]['jobid'] + '</td> <td>' + techJobs[z]['dte'] + '</td> <td>' + techJobs[z]['start'] + 
                                '</td> <td>' + techJobs[z]['siteline2'] + '</td> </tr>';
                        }
                        content = content + "</table>";		
                        techinfowindows[j] = new google.maps.InfoWindow({
                                    content:  content


                        });
                        google.maps.event.addListener(techmarkers[j], "click", function() {
                            techinfowindows[this.index].open(map, techmarkers[this.index]);
                        });	
                    }		
                }
                setTimeout(function(){ 
                    $('.map-overlay').hide();

                }, 3000);
                
            }
            else {
                $('.map-overlay').hide();
                bootbox.alert(response.message);
            }
        }, 'json');
        
        
        

    };
    
    LoadGmaps();
    
});


//google.maps.event.addDomListener(window, 'load', LoadGmaps);