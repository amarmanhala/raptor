        console.log('');
        wayPointArray = new Array();
        <?php $iCounter=0; ?>
        <?php foreach ($rows as $legkey=>$leg): ?>
            
            //console.info('legkey=<?php echo $legkey + 1; ?>');
            <?php if($iCounter < 7 && $legkey+1 != count($rows)): ?>
                <?php //if($leg['gmap_start_latitude'] != $leg['gmap_end_latitude']): ?>
                    //console.info('end_latitude=<?php echo $leg['gmap_end_latitude']?> end_longitude=<?php echo $leg['gmap_end_longitude']?>');
                    console.info('<?php echo $legkey + 1; ?>  <?php echo $iCounter; ?> lat=<?php echo $leg['gmap_end_latitude']?> long=<?php echo $leg['gmap_end_longitude']?>');
                    waypoint<?php echo $legkey; ?> = new google.maps.LatLng('<?php echo $leg['gmap_end_latitude']?>', '<?php echo $leg['gmap_end_longitude']?>');
                    wayPointArray.push({location: waypoint<?php echo $legkey; ?>, stopover: false});
                <?php //endif; ?>    
            <?php else: ?>
                console.info(' ');    
                originPosition = new google.maps.LatLng('<?php echo $rows[($legkey - $iCounter)]['gmap_start_latitude']?>', '<?php echo $rows[($legkey - $iCounter)]['gmap_start_longitude']?>');
                
                console.info(' ');
                destinationPosition = new google.maps.LatLng('<?php echo $leg['gmap_end_latitude']?>', '<?php echo $leg['gmap_end_longitude']?>');
                
                console.info('<?php echo $legkey + 1; ?> <?php echo $iCounter; ?> getDirections() ');
                console.log('');
                getDirections(originPosition, wayPointArray, destinationPosition);
                wayPointArray = [];
                <?php $iCounter=0; ?>
                console.info(' ');
            <?php endif; ?>
                
            <?php $iCounter++; ?>    
        <?php endforeach; ?>
            
          
    
    