$( document ).ready(function(e) {
	$("a.zoommap").click(function(e){
	        zoomMap($(this).data('targetlat'), $(this).data('targetlng'));
	        e.preventDefault();
	});
});


  function getDirections(originPosition, wayPointArray, destinationPosition, legkey) {
            var request = {
              origin: originPosition,
              destination: destinationPosition,
              waypoints: wayPointArray,
              travelMode: google.maps.DirectionsTravelMode.DRIVING,
              unitSystem: google.maps.DirectionsUnitSystem.METRIC,
              optimizeWaypoints: false
            };

            // directions service
            directionsService = new google.maps.DirectionsService();

	    //console.log(legkey + ' directionsService.route');
            directionsService.route(request, function(response, status, legkey) {

                  if (status == google.maps.DirectionsStatus.OK) {
                    // draw directions polyline on map
                    var polyOpts = {strokeOpacity: 0.6, strokeColor: '#3296FA',strokeWeight: 6};
                    var directionsDisplayOptions = {suppressMarkers: true, suppressInfoWindows: false, preserveViewport: true, polylineOptions: polyOpts };

                    directionsRenderer = new google.maps.DirectionsRenderer(directionsDisplayOptions);
                    directionsRenderer.setMap(map);
                    directionsRenderer.setDirections(response);
                    console.log('directionsRenderer.setDirections()');
                    console.log('response=' + response);
                    console.log(' ');
                  } else {
                    console.log('could not get route');
                    console.log(response);
                    console.log(' ');
                  }
             });
   }


  function zoomMap(lat, lng) {
  	map.setZoom(16);
    	map.setCenter(new google.maps.LatLng(lat, lng));
   }
 
   function mapFitBounds(){
        map.fitBounds(bounds);
   }
   
   function toggleStreetView() {
        var toggle = panorama.getVisible();
        if (toggle == false) {
          panorama.setPov(/** @type {google.maps.StreetViewPov} */({
	      heading: 265,
	      pitch: 0
	  })); 	
          panorama.setPosition(map.getCenter());
          panorama.setVisible(true);
        } else {
          panorama.setVisible(false);
        }
    }
 
   function showLabel(event) {
      var iw = new google.maps.InfoWindow({ content: this.contentString});
      iw.open(map, this);
   }	
 
   function mapRightClick(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        window.prompt ('',lat + " , " + lng);
   }
 
   var deletePolygonVertex = function(event) {
   	var localEvent = event;
   	var localPoly = this;
   
	bootbox.confirm('Do you want to delete this point on the Polygon ?', function(result) {
	    if (localEvent.vertex != null) 
	       	localPoly.getPath().removeAt(localEvent.vertex);
	      
	    savePolygonUpdate(localPoly.getPath()); 
	}); 
   }
 
   function savePolygonUpdate(path){
        var arr=[];
        path.forEach(function(latLng){
            arr.push(latLng.toString().replace("(", "").replace(")", "").replace(",", ""));
        })
        
        //var url = extended_base_url + 'platform/polygonupdate/' + path.id + "/" + encodeURIComponent(arr.join(','));
        var url = extended_base_url + 'polygonupdate/' + path.id + "/" + encodeURIComponent(arr.join(','));
        //window.prompt ('Copy to clipboard: Ctrl+C, Enter', url);
        $.ajax({url: url ,success:function(result){
        	
        }});
   }	
 
   function polygonUpdate(event){
   	savePolygonUpdate(this)
   }
    
    function rectUpdate(event) {
	    var ne = this.getBounds().getNorthEast();
	    var sw = this.getBounds().getSouthWest();
	
	    var contentString = '<b>Rectangle moved.</b><br>' +
	    'north-east: ' + ne.lat() + ', ' + ne.lng() + '<br>' +
	    'south-west: ' + sw.lat() + ', ' + sw.lng();
	
	    // Set the info window's content and position.
	    //infoWindow.setContent(contentString);
	    //infoWindow.setPosition(ne);
	    //infoWindow.open(map);
	
	    //var url = extended_base_url + 'platform/rectangleupdate/'  + this.id + '/' + ne.lat() + '/' + ne.lng() + '/' + sw.lat() + '/' + sw.lng();
	    var url = extended_base_url + 'rectangleupdate/'  + this.id + '/' + ne.lat() + '/' + ne.lng() + '/' + sw.lat() + '/' + sw.lng();
	    $.ajax({url: url ,success:function(result){
	        //location.reload(true);
	    }}); 
   }
   
   function rectCreate(rectangle) {
	    var ne = rectangle.getBounds().getNorthEast();
	    var sw = rectangle.getBounds().getSouthWest();
	            
	    var contentString = 'north-east: ' + ne.lat() + ', ' + ne.lng() + '<br>' +
	           'south-west: ' + sw.lat() + ', ' + sw.lng();
	            
	    var url = extended_base_url + 'platform/rectanglecreate/' + ne.lat() + '/' + ne.lng() + '/' + sw.lat() + '/' + sw.lng();
	    $.ajax({url: url ,success:function(result){
			location.reload(true);	
	    }});
  }
  
    
  function getSelectedSites() {
  	console.log("getSelectedSites() 3");
  	
  	$( ".check-site" ).each(function( index ) {
  	   //console.log("id=" + $(this).attr('id'));
  	   console.log("site");
  	});
  }
  
  function refreshSiteList(selectedsites) {
  	$("#sitelist").html("<img src='" +base_url+ "itglobal/shared/assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");
  	
  	//alert($("#contractRegion").val());
  	
  	//var url = extended_base_url + 'route/listsite/' + encodeURIComponent(selectedsites) + '/' + $('#sortOrder').val() + '/' + $('#routeCustomer').val() + '/' +$('#programMonth').val()+ '/' +encodeURIComponent($("#fmSelect").val()) + 
  	//'/' +encodeURIComponent($("#contractSiteStatus").val()) + '/' +encodeURIComponent($("#contractServiceSelect").val()) + '/' +encodeURIComponent($("#contractServiceSelect2").val()) +  '/' + $("#contractRegion").val()+ '/' +encodeURIComponent($("#addresslabelAttribute").val());
  	
  	var url = extended_base_url + 'route/listsite/' + encodeURIComponent(selectedsites) + '/' + $('#sortOrder').val() + '/' + $('#routeCustomer').val() + '/' +$('#programMonth').val()+ '/' +encodeURIComponent($("#fmSelect").val()) + 
  	'/' +encodeURIComponent($("#contractSiteStatus").val()) + '/' +encodeURIComponent($("#contractServiceSelect").val()) + '/' +encodeURIComponent($("#contractServiceSelect2").val()) +  '/' + $("#contractRegion").val()+ '/' +encodeURIComponent($("#addresslabelAttribute").val()) + '/0';
  	
  	
  	//window.prompt ('Copy to clipboard: Ctrl+C, Enter', url);
  	
  	$.ajax({url: url,success:function(result){
		$('#sitelist').html(result);
	}});
  }
  
  function selectSites(shape) {
	var len = $('.check-site').length;
  	$( ".check-site" ).each(function( index ) {
  		if(shape.getBounds().contains(new google.maps.LatLng($(this).data('lat'), $(this).data('lng'))))
		    $(this).prop( 'checked', true );
		    
		if(index == len - 1) 
	              refreshSiteList(getCheckedSites($('.check-site')));     
  	});
  }
  
  function rectRightClick(event) {
        alert('rectRightClick() ' + this.id);
   }
  
  function polygonRightClick(event) {
    	polygonSelectSites(this);
    }
  
  function polygonSelectSites(polygon) {
  	var len = $('.check-site').length;
  	$('.check-site').each(function( index, element) {
  		if(google.maps.geometry.poly.containsLocation(new google.maps.LatLng($(this).data('lat'), $(this).data('lng')), polygon)) 
  		      $(this).prop( 'checked', true );	
  		      
  		if(index == len - 1) 
	              refreshSiteList(getCheckedSites($('.check-site'))); 
	});  		      
  	
  }  	
    
  function getCheckedSites(sites) {
  	var selectedsites = [0];
  	sites.each(function( index, element) {
  		if($(this).prop( "checked" ))
		   selectedsites.push($(this).data('labelid'));
  	});
  	
  	return selectedsites;
  }
  
  function checkChange(obj){
	if (obj.checked) {
        	$($(obj).attr('target')).prop( 'checked', true );
    	} else {
    		$($(obj).attr('target')).prop( 'checked', false);
    	}
    	
    	refreshSiteList(getCheckedSites($('.check-site'))); 
  }
  