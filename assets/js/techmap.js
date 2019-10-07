/* Function to load technician jobs and technicians on map */
/* global google, base_img_url, centerLatlng, jobsArray, base_url, techsArray */

"use strict";

function LoadGmaps() {
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
            content = content + "<b><center>" + techsArray[j]['NAME'] + "</center> </b></br>"; 
            content = content + '<div class="text-center">Status: <span style=color:' + techStatusColor + ">" + techsArray[j]['techstatus'] + "</span></div></br>"; 
            content = content + '<span style="font-weight:bold">Est Arrival : </span><span style="font-weight:bold; color:blue" >' + techsArray[j]['arrivaltime'] + "</span></br>" ; 
            content = content + '<table class="jobsTable"> <tr> <th>Job</th> <th>Date</th> <th>Time</th> <th>Address</th> </tr>' ;
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
           
}
google.maps.event.addDomListener(window, 'load', LoadGmaps);