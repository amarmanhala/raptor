var polyOptions = {strokeColor: '#000000', strokeOpacity: 1, strokeWeight: 1, fillColor: null, fillOpacity: 0, editable: true };
var drawingManager = new google.maps.drawing.DrawingManager({
    drawingMode: null,
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        <?php foreach ($drawingmanager['drawingmodes'] as $key=>$drawingmode): ?>
            google.maps.drawing.OverlayType.<?php echo $drawingmode; ?>,
        <?php endforeach; ?>     
      ]
    },
    rectangleOptions: polyOptions
});

<?php foreach ($drawingmanager['listeners'] as $key=>$listener): ?>
google.maps.event.addListener(drawingManager, '<?php echo $listener['event']; ?>', function(shape) {
    drawingManager.setDrawingMode(null);
    <?php echo $listener['function']; ?>(shape);
});
<?php endforeach; ?> 
  
drawingManager.setMap(map);