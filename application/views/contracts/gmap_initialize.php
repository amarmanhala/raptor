<script type="text/javascript"> 
      var map;
      var bounds = new google.maps.LatLngBounds();
        
      function mapControl(title, action) {     
          var controlUI = document.createElement('div');
          controlUI.style.backgroundColor = 'white';
          controlUI.style.border='1px solid';
          controlUI.style.cursor = 'pointer';
          controlUI.style.textAlign = 'center';
          controlUI.title = title;
          
          var controlText = document.createElement('div');
          controlText.style.fontFamily='Arial,sans-serif';
          controlText.style.fontSize='11px';
          controlText.style.paddingTop = '2px';
          controlText.style.paddingLeft = '4px';
          controlText.style.paddingRight = '4px';
          controlText.innerHTML = title;
          
          var controlDiv = document.createElement('div');
          controlDiv.style.padding = '5px';
          
          controlDiv.appendChild(controlUI);
          controlUI.appendChild(controlText);
          google.maps.event.addDomListener(controlUI, 'click', action);
          
          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(controlDiv);
       }

      function initialize() {
        var redCenter = new google.maps.LatLng(-26.11598592533351, 134.39712524414062);
        var northQld = new google.maps.LatLng(-10.141931686131018 , 142.17613220214844);
        var southTas = new google.maps.LatLng(-43.771093817756494 , 146.75949096679688);
        var eastCoast = new google.maps.LatLng(-29.458731185355315 , 153.77288818359375);
        var westCoast = new google.maps.LatLng(-26.588527147308625 , 113.25531005859375);
        
        // map options
        var myOptions = {
          //zoom: 5,  
          scaleControl: true,   
          //center: new google.maps.LatLng(-26.11598592533351, 134.39712524414062),        
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        // the map
        map = new google.maps.Map(document.getElementById('googleMap<?php echo $mapid ?>'), myOptions);
        panorama = map.getStreetView();
        google.maps.event.addListener(map, "rightclick", mapRightClick);
        var resetControl = new mapControl('<b>Reset<b>', mapFitBounds);
        var streetViewControl = new mapControl('<b>Street View<b>', toggleStreetView);
        
        // draw markers on map
        <?php 
            foreach ($overlays as $key => $overlay) { 
               switch ($overlay) {
                   case 'site_list_marker':
                       $this->load->view('shared/gmap_site_list_marker'); 
                       break;
                   case 'markerswithlabelroute':
                       $this->load->view('shared/gmap_markerswithlabel_route');
                       break;
                   case 'directions':
                       $this->load->view('shared/gmap_directions2'); 
                       break;
                   case 'heatmap':
                       $this->load->view('shared/gmap_heatmap');
                       break;
                   case 'rectangles':
                       $this->load->view('shared/gmap_rectangles');
                       break;
                   case 'polygons':
                       $this->load->view('shared/gmap_polygons');
                       break;
                   case 'drawingmanager':
                       $this->load->view('shared/gmap_drawingmanager');
                       break;
               }
            }  
	?>
        
        //if nothing in bounds
        if(bounds.isEmpty()){
            bounds.extend(northQld);
            bounds.extend(southTas);
            bounds.extend(eastCoast);
            bounds.extend(westCoast);
        }
        
        map.fitBounds(bounds);
    }

    $( document ).ready(function() {
        initialize();
    });
</script>     
 

