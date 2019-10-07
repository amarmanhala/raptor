        // directions service
        directionsService = new google.maps.DirectionsService();

        // Define our origin position, the start of our trip.
        originPosition = new google.maps.LatLng('<?php echo $rows[0]['gmap_start_latitude']?>', '<?php echo $rows[0]['gmap_start_longitude']?>');
        wayPointArray = new Array();

        // Define 8 waypoints to place between the origin and the destination
        <?php foreach ($rows as $legkey=>$leg): ?>
            
            <?php if($legkey < 7 && $legkey+1 != count($rows)): ?>
                waypoint<?php echo $legkey; ?> = new google.maps.LatLng('<?php echo $leg['gmap_end_latitude']?>', '<?php echo $leg['gmap_end_longitude']?>');
                wayPointArray.push({location: waypoint<?php echo $legkey; ?>, stopover: false});
            <?php else: ?>
                // define our definition position, the last stop of our trip
                destinationPosition = new google.maps.LatLng('<?php echo $leg['gmap_end_latitude']?>', '<?php echo $leg['gmap_end_longitude']?>');
                <?php break; ?>
            <?php endif; ?>    
        <?php endforeach; ?>
            
            

      var request = {
        origin: originPosition,
        destination: destinationPosition,
        waypoints: wayPointArray,
        travelMode: google.maps.DirectionsTravelMode.DRIVING,
        unitSystem: google.maps.DirectionsUnitSystem.METRIC,
        optimizeWaypoints: false
      };

        directionsService.route(request, function(response, status) {

      if (status == google.maps.DirectionsStatus.OK) {
        // draw directions polyline on map
        var polyOpts = {strokeOpacity: 0.6, strokeColor: '#3296FA',strokeWeight: 6};
        var directionsDisplayOptions = {suppressMarkers: true, suppressInfoWindows: false, preserveViewport: true, polylineOptions: polyOpts };

        directionsRenderer = new google.maps.DirectionsRenderer(directionsDisplayOptions);
        directionsRenderer.setMap(map);
        directionsRenderer.setDirections(response);
      } else {
        console.info('could not get route');
        console.info(response);
      }
    });
    
    // draw markers on map
    <?php foreach ($rows as $legkey=>$leg): ?>
        //start & end point for first leg
        <?php if ($legkey==0): ?>
            
            var contentString<?php echo $leg['id']?>a = '<div id="content">'+
            '<div id="siteNotice">'+ '</div>'+
            '<h5 id="firstHeading" class="firstHeading">' + '<?php echo addslashes($leg['start_siteline1'])?>' + '</h5>'+
            '<div id="bodyContent" class="markerLabelContent">'+
            '<p></p>'+
            '</div></div>';
            
            var LatLng<?php echo $leg['id']?>a = new google.maps.LatLng(<?php echo $leg['gmap_start_latitude']?>,<?php echo $leg['gmap_start_longitude']?>);
            var marker<?php echo $leg['id']?>a = new MarkerWithLabel({ position: LatLng<?php echo $leg['id']?>a, labelContent: '<?php //echo $leg['start_siteline1a']?>', 
                icon: base_url + 'itglobal/shared/assets/img/gmapicons/black<?php echo $legkey + 1?>.png',
                draggable: false, raiseOnDrag: true, map: map, labelAnchor: new google.maps.Point(22, 0), labelClass: 'markerLabelHidden', labelStyle: {opacity: 0.75} }); 
     
            var iw<?php echo $leg['id']?>a = new google.maps.InfoWindow({ content: contentString<?php echo $leg['id']?>a});  
            google.maps.event.addListener(marker<?php echo $leg['id']?>a, "click", function(e){ iw<?php echo $leg['id']?>a.open(map, this); });

            bounds.extend(LatLng<?php echo $leg['id']?>a);

        <?php endif; ?>
            
            var contentString<?php echo $leg['id']?> = '<div id="content">'+
            '<div id="siteNotice">'+ '</div>'+
            '<h5 id="firstHeading" class="firstHeading">' + '<?php echo addslashes($leg['end_siteline1'])?>' + '</h5>'+
            '<div id="bodyContent" class="markerLabelContent">'+
            '<p></p>'+
            '</div></div>';
            
            var LatLng<?php echo $leg['id']?> = new google.maps.LatLng(<?php echo $leg['gmap_end_latitude']?>,<?php echo $leg['gmap_end_longitude']?>);
            var marker<?php echo $leg['id']?> = new MarkerWithLabel({ position: LatLng<?php echo $leg['id']?>, labelContent: '<?php //echo $leg['end_siteline1a']?>', 
                icon: base_url + 'itglobal/shared/assets/img/gmapicons/black<?php echo $legkey + 2?>.png',
                draggable: false, raiseOnDrag: true, map: map, labelAnchor: new google.maps.Point(22, 0), labelClass: 'markerLabelHidden', labelStyle: {opacity: 0.75} }); 
     
            var iw<?php echo $leg['id']?> = new google.maps.InfoWindow({ content: contentString<?php echo $leg['id']?>});  
            google.maps.event.addListener(marker<?php echo $leg['id']?>, "click", function(e){ iw<?php echo $leg['id']?>.open(map, this); });

            bounds.extend(LatLng<?php echo $leg['id']?>);
    <?php endforeach; ?> 