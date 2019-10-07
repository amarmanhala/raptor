    // draw markers on map
    <?php $omsCounter = -1;?>
    <?php foreach ($rows as $key=>$row): ?>
        //start & end point for first leg
        <?php if ($key==0): ?>
            
            var contentStringStart<?php echo $row['id']?> = '<div id="content">'+
            '<div id="siteNotice">'+ '</div>'+
            '<h5 id="firstHeading" class="firstHeading">' + '<?php echo addslashes($row['start_siteline1'])?>' + '</h5>'+
            '<div id="bodyContent" class="markerLabelContent">'+
            '<p></p>'+
            '</div></div>';
            
            var LatLngStart<?php echo $row['id']?> = new google.maps.LatLng(<?php echo $row['actual_start_latitude']?>,<?php echo $row['actual_start_longitude']?>);
            var markerStart<?php echo $row['id']?> = new MarkerWithLabel({ position: LatLngStart<?php echo $row['id']?>, labelContent: '<?php //echo $row['start_siteline1a']?>', 
                icon: base_url + 'itglobal/shared/assets/img/gmapicons/black<?php echo $key + 1?>.png',
                draggable: false, raiseOnDrag: true, map: map, labelAnchor: new google.maps.Point(22, 0), labelClass: 'markerLabelHidden', labelStyle: {opacity: 0.75},
                contentString: contentStringStart<?php echo $row['id']?>}); 
     
            google.maps.event.addListener(markerStart<?php echo $row['id']?>, 'click', showLabel);    
            bounds.extend(LatLngStart<?php echo $row['id']?>);

        <?php endif; ?>
            
            var contentString<?php echo $row['id']?> = '<div id="content">'+
            '<div id="siteNotice">'+ '</div>'+
            '<h5 id="firstHeading" class="firstHeading">' + '<?php echo addslashes($row['end_siteline1'])?>' + '</h5>'+
            '<div id="bodyContent" class="markerLabelContent">'+
            '<p></p>'+
            '</div></div>';
            
            var LatLng<?php echo $row['id']?> = new google.maps.LatLng(<?php echo $row['actual_end_latitude']?>,<?php echo $row['actual_end_longitude']?>);
            var marker<?php echo $row['id']?> = new MarkerWithLabel({ position: LatLng<?php echo $row['id']?>, labelContent: '<?php //echo $row['end_siteline1a']?>', 
                icon: base_url + 'itglobal/shared/assets/img/gmapicons/black<?php echo $key + 2?>.png',
                draggable: false, raiseOnDrag: true, map: map, labelAnchor: new google.maps.Point(22, 0), labelClass: 'markerLabelHidden', labelStyle: {opacity: 0.75},
                contentString: contentString<?php echo $row['id']?>}); 
     
            google.maps.event.addListener(marker<?php echo $row['id']?>, 'click', showLabel);
            bounds.extend(LatLng<?php echo $row['id']?>);
            
            //console.log('');
            <?php if($key == 0): ?>
                <!-- latTHIS=<?php echo $rows[$key]['actual_start_latitude']?> site=<?php echo $key+1?>a  -->
                //console.log('latTHIS=<?php echo $rows[$key]['actual_start_latitude']?> site=<?php echo $key+1?>a');
                
                <?php if(($rows[$key]['actual_start_latitude'] == $rows[$key]['actual_end_latitude'])): ?>
                    <!-- GPS same as next site THIS is 1a -->
                    //console.log('GPS same as next site THIS is 1a');
                    <?php $omsCounter++;?>
                    var oms<?php echo $omsCounter ?> = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true, circleSpiralSwitchover: 8 });
                    oms<?php echo $omsCounter ?>.addMarker(markerStart<?php echo $row['id'] ?>);
                <?php endif; ?>
                //console.log('');    
                  
                <!-- latTHIS=<?php echo $rows[$key]['actual_end_latitude']?> site=<?php echo $key+1?>  -->    
                //console.log('latTHIS=<?php echo $rows[$key]['actual_end_latitude']?> site=<?php echo $key+1?>');
                <?php //if(($rows[$key]['actual_end_latitude'] == $rows[$key+1]['actual_end_latitude']) && ($rows[$key]['actual_end_latitude'] != $rows[$key]['actual_start_latitude']) ): ?>
                <?php if(($key+1) < count($rows) && ($rows[$key]['actual_end_latitude'] == $rows[$key+1]['actual_end_latitude']) && ($rows[$key]['actual_end_latitude'] != $rows[$key]['actual_start_latitude']) ): ?>
                    <!-- GPS same as Next site & differrent to Prev THIS is 1 -->
                    //console.log('GPS same as Next site & differrent to Prev THIS is 1');
                    <?php $omsCounter++;?>
                    var oms<?php echo $omsCounter ?> = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true, circleSpiralSwitchover: 8 });
                    oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['id'] ?>);
                <?php elseif(($key+1) < count($rows) && ($rows[$key]['actual_end_latitude'] == $rows[$key+1]['actual_end_latitude'])): ?>
                    <!-- GPS same as Next site & same as Prev THIS is 1 -->
                    //console.log('GPS same as Next site & same as Prev THIS is 1');
                    oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['id'] ?>);
                <?php endif; ?>
                
            <?php else: ?>
                <!-- latTHIS=<?php echo $rows[$key]['actual_end_latitude']?> site=<?php echo $key+1?>  --> 
                //console.log('latTHIS=<?php echo $rows[$key]['actual_end_latitude']?> site=<?php echo $key+1?>');
                <?php if((($key+1) < count($rows)) && ($rows[$key]['actual_end_latitude'] == $rows[$key+1]['actual_end_latitude']) && ($rows[$key]['actual_end_latitude'] != $rows[$key-1]['actual_end_latitude'])): ?>
                            <!-- GPS same as next site & different to Prev  -->   
                            //console.log('GPS same as next site & different to Prev');
                            <?php $omsCounter++;?>
                            var oms<?php echo $omsCounter ?> = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true, circleSpiralSwitchover: 8 });
                            oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['id'] ?>);
                <?php elseif((($key+1) < count($rows)) && ($rows[$key]['actual_end_latitude'] == $rows[$key+1]['actual_end_latitude'])): ?>
                            <!-- GPS same as next site & same as Prev -->   
                            //console.log('GPS same as next site & same as Prev');
                            oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['id'] ?>);
                <?php elseif(($rows[$key]['actual_end_latitude'] == $rows[$key-1]['actual_end_latitude'])): ?>
                            <!-- GPS same as Prev -->   
                            //console.log('GPS same as Prev');    
                            oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['id'] ?>);
                <?php endif; ?>
            <?php endif; ?>
                      
    <?php endforeach; ?> 