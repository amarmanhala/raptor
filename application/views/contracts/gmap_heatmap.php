        var pointarray, heatmap;

        var heatmapData = [
            <?php  foreach ($rows as $key=>$row): ?>
                    new google.maps.LatLng(<?php echo $row['latitude'] ?>,<?php echo $row['longitude'] ?>),
            <?php  endforeach; ?>
        ];

        var pointArray = new google.maps.MVCArray(heatmapData);
       /* heatmap = new google.maps.visualization.HeatmapLayer({
          data: pointArray
        }); */
        
         heatmap = new google.maps.visualization.HeatmapLayer({
            map: map,
            data: pointArray,
            radius: 4,
            dissipate: true,
            maxIntensity: 8,
            gradient : [
                'rgba(0, 255, 255, 0)',
                'rgba(0, 255, 255, 1)',
                'rgba(0, 191, 255, 1)',
                'rgba(0, 127, 255, 1)',
                'rgba(0, 63, 255, 1)',
                'rgba(0, 0, 255, 1)',
                'rgba(0, 0, 223, 1)',
                'rgba(0, 0, 191, 1)',
                'rgba(0, 0, 159, 1)',
                'rgba(0, 0, 127, 1)',
                'rgba(63, 0, 91, 1)',
                'rgba(127, 0, 63, 1)',
                'rgba(191, 0, 31, 1)',
                'rgba(255, 0, 0, 1)'
            ] 
          });
        
        heatmap.setMap(map);
        <?php  foreach ($rows as $key=>$row): ?>
            var LatLng<?php echo $key ?> = new google.maps.LatLng(<?php echo $row['latitude'] ?>,<?php echo $row['longitude'] ?>);
            bounds.extend(LatLng<?php echo $key ?>);
        <?php  endforeach; ?> 