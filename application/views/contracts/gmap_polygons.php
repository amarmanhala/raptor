<?php foreach ($polygon_rows as $key=>$row): ?> 
   var poly<?php echo $row['id'] ?> = new google.maps.Polygon({
    id: <?php echo $row['id'] ?>,
    paths: [ 
    <?php foreach ($row['points'] as $key2=>$point): ?>
       new google.maps.LatLng(<?php echo str_replace(' ',', ',$point) ?>),
    <?php endforeach; ?>
    ],
    strokeColor: '#000000',
    strokeOpacity: 1,
    strokeWeight: 1,
    fillColor: null,
    fillOpacity: 0.07,
    editable: <?php if($this->gmap_shape_editable): ?>true<?php else: ?>false<?php endif; ?>,
    draggable: false,
    map: map
 });

 //google.maps.event.addListener(poly<?php echo $row['id'] ?>, 'rightclick', deletePolygonVertex);
 google.maps.event.addListener(poly<?php echo $row['id'] ?>, 'rightclick', polygonRightClick);
 
 var poly_path<?php echo $row['id'] ?> = poly<?php echo $row['id'] ?>.getPath();
 poly_path<?php echo $row['id'] ?>.id = <?php echo $row['id'] ?>;
 google.maps.event.addListener(poly_path<?php echo $row['id'] ?>, 'set_at', polygonUpdate);
 google.maps.event.addListener(poly_path<?php echo $row['id'] ?>, 'insert_at', polygonUpdate);
 
 <?php foreach ($row['points'] as $key2=>$point): ?>
     bounds.extend(new google.maps.LatLng(<?php echo str_replace(' ',', ',$point) ?>));
 <?php endforeach; ?>
 
<?php endforeach; ?>
  
   
    
  
  
  
   