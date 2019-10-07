     <?php  foreach ($rectangle_rows as $key=>$row): ?>
        var rectangle<?php echo $key ?> = new google.maps.Rectangle({
            id:<?php echo $row['id'] ?>,
            strokeColor: '#000000',
            strokeOpacity: 1,
            strokeWeight: 1,
            fillColor: null,
            fillOpacity: 0.07,
            editable: <?php if($this->gmap_shape_editable): ?>true<?php else: ?>false<?php endif; ?>,
            draggable: false,
            map: map,
            bounds: new google.maps.LatLngBounds(
              new google.maps.LatLng(<?php echo $row['south_west_latitude'] ?>, <?php echo $row['south_west_longitude'] ?>),  
              new google.maps.LatLng(<?php echo $row['north_east_latitude'] ?>, <?php echo $row['north_east_longitude'] ?>))
        });
            
        // Add event listeners on the rectangle.
        google.maps.event.addListener(rectangle<?php echo $key ?>, 'bounds_changed', rectUpdate);
        google.maps.event.addListener(rectangle<?php echo $key ?>, 'rightclick', rectRightClick);
        
        var contentStringRect<?php echo $key ?> = '<div id="content">'+
            '<div><span class="infoWindHead" > ' + '<?php echo $row['name'] ?>' + '</span></div>'+ 
            '<div>'+ 
            '<span > N-E:  ' + '<?php echo $row['north_east_latitude'] ?>, <?php echo $row['north_east_longitude'] ?>' + '</span>'+
            '</div>'+
            '<div>'+ 
            '<span > S-W:  ' + '<?php echo $row['south_west_latitude'] ?>, <?php echo $row['south_west_longitude'] ?>' + '</span>'+
            '</div>'+
            '<div id="bodyContent" class="infoWindow300x20">'+
            '</div></div>';

            var LatLngRect<?php echo $key ?> = new google.maps.LatLng(<?php echo $row['center_latitude'] ?>,<?php echo $row['center_longitude'] ?>);
            var markerRect<?php echo $key ?> = new MarkerWithLabel({ 
                position: LatLngRect<?php echo $key ?>, labelContent: '<?php echo $row['name'] ?>', 
                icon: base_url + 'itglobal/shared/assets/img/iconsnew/<?php echo $key+1 ?>black.png',
                draggable: false, raiseOnDrag: true, map: map, labelAnchor: new google.maps.Point(22, 0), labelClass: 'markerLabel180', labelStyle: {opacity: 0.75},
                contentString: contentStringRect<?php echo $key ?>}); 
                
            google.maps.event.addListener(markerRect<?php echo $key ?>, 'click', showLabel); 
            bounds.extend(LatLngRect<?php echo $key ?>);
    <?php  endforeach; ?> 