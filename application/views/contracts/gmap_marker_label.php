var contentString<?php echo $sitenum ?> = '<div id="content">'+
'<div >'+ 
'<span class="infoWindHead" ><big>' + '<?php echo $windowHeading ?>' + '</big>' + '<?php echo $formContent ?>' + '</span>'+
'</div>'+
'<div id="bodyContent" class="infoWindow infoWindow500x90<?php //echo $css['infoWindow'] ?>">'+
'<p><?php if(isset($bodyContent)) echo $bodyContent ?></p>'+
'<p><?php if(isset($bodyContent2)) echo $bodyContent2 ?></p>'+
'<p><?php if(isset($bodyContent3)) echo $bodyContent3 ?></p>'+
'<p><?php if(isset($bodyContent4)) echo $bodyContent4 ?></p>'+
'<p><?php if(isset($bodyContent5)) echo $bodyContent5 ?></p>'+
'</div></div>';

var LatLng<?php echo $sitenum ?> = new google.maps.LatLng(<?php echo $latitude ?>,<?php echo $longitude ?>);
var marker<?php echo $sitenum ?> = new MarkerWithLabel({ position: LatLng<?php echo $sitenum ?>, labelContent: '<?php echo $markerlabel ?>', 
    icon: base_url + 'assets/img/iconsnew/<?php echo $sitenum ?><?php echo $iconcolor ?>.png',
    draggable: false, raiseOnDrag: true, map: map, labelAnchor: new google.maps.Point(22, 0), labelClass: '<?php echo $css['markerlabel'] ?>', labelStyle: {opacity: 0.75},
    contentString: contentString<?php echo $sitenum ?>}); 

google.maps.event.addListener(marker<?php echo $sitenum ?>, "click", showLabel);
bounds.extend(LatLng<?php echo $sitenum ?>);