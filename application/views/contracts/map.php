<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title text-blue">My Contracts</h3>
    </div>
    <div class="box-header with-border">
        <form name="techmap" id="techmap" method="get" action="" >
             
            <div class="row">
                <div class="col-sm-4 col-md-2"> 
                    <select class= "form-control selectpicker"  multiple=""  data-live-search= "TRUE" title = "All FMs" data-size = "auto" data-width= "100%" name="sitefm[]" id="sitefms">
                        
                        <?php foreach($sitefmcontacts as $val) { 
                            $selected = '';
                            if($this->input->get_post('sitefm') !=NULL){
                                $selected =(string)array_search($val['contactid'], $this->input->get_post('sitefm')); 
                            }
                            ?>
                            <option value="<?php echo $val['contactid'];?>" <?php if($selected!=''){echo 'selected';}?>><?php echo $val['sitefm'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2"> 
                    <select class= "form-control selectpicker"  multiple=""  data-live-search= "TRUE" title = "Any Status" data-size = "auto" data-width= "100%" name="contractsitestatus[]" id="contractsitestatus">
                        <?php foreach ($contractsitestatus as $key => $val) {
                             $selected = '';
                            if($this->input->get_post('contractsitestatus') !=NULL){
                                $selected =(string)array_search($val['id'], $this->input->get_post('contractsitestatus')); 
                            }
                            ?> 
                        <option value="<?php echo $val['id'];?>" <?php if($selected!=''){echo 'selected';}?> data-icon_color="<?php echo $val['icon_color'];?>"><?php echo $val['name'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2">
                    <select class= "form-control selectpicker"  data-live-search= "TRUE" title = "Select a Month" data-size = "auto" data-width= "100%" name="programMonth" id="programMonth">
                  
                         <?php for($monthNum = 1; $monthNum <= 12; $monthNum++):?>
                            <?php $dateObj   = DateTime::createFromFormat('!m', $monthNum);?>
                             <option value="<?php echo ($monthNum*2); ?>" <?php if($this->input->get_post('programMonth') !=NULL){ if($monthNum ==($this->input->get_post('programMonth')/2)) {echo 'selected';}}else{if($monthNum ==date('m')) {echo 'selected';}}  ?>><?php echo $dateObj->format('F'); ?></option>
                         <?php endfor; ?>
                        < 
                    </select>
                </div>
                <div class="col-sm-4 col-md-2"> 
                    <select class= "form-control selectpicker"  multiple=""  data-live-search= "TRUE" title = "All GM Standards" data-size = "auto" data-width= "100%" name="contractGroundServices[]" id="contractGroundServices">
                     
                        <?php foreach ($grounds_standards as $key => $val) {
                             $selected = '';
                            if($this->input->get_post('contractGroundServices') !=NULL){
                                $selected =(string)array_search($val['id'], $this->input->get_post('contractGroundServices')); 
                            }
                            ?> 
                        <option value="<?php echo $val['id'];?>" <?php if($selected!=''){echo 'selected';}?> ><?php echo $val['label'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2"> 
                    <select class= "form-control selectpicker"  multiple=""  data-live-search= "TRUE" title = "All Pest Control Standards" data-size = "auto" data-width= "100%" name="contractPestServices[]" id="contractPestServices">
                 
                        <?php foreach ($pest_standards as $key => $val) {
                          $selected = '';
                            if($this->input->get_post('contractPestServices') !=NULL){
                                $selected =(string)array_search($val['id'], $this->input->get_post('contractPestServices')); 
                            }
                            ?> 
                        <option value="<?php echo $val['id'];?>" <?php if($selected!=''){echo 'selected';}?> ><?php echo $val['label'];?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-sm-4 col-md-2 text-right"> 
                    <div class="input-group input-group">
                        <span class="input-group-btn">
                            <input type="hidden" name="sortorder" id="sortorder" value="<?php echo set_value('sortorder') ?>"/>
                            <button type="reset" id="resetfilter" class="btn btn-warning" title = "Clear Filter" ><i class="fa fa-eraser" title = "Clear Filter" ></i></button>                 
                            <button type="submit" class="btn btn-default btn-refresh" title = "Refresh Data" ><i class= "fa fa-refresh" title = "Refresh Data"></i></button>
                             
                        </span>
                    </div>
                </div>
            </div>
                    
        </form>
     
    </div>
    <div class="box-header with-border">
        <div class="row">
            <div class="col-sm-12">
                <table id="budgetstbl" class="table table-bordered table-striped" style="margin-bottom:0px">
                    <tr class="ccc-header">
                        <td class="col-sm-1">
                            <strong>Status</strong>
                       </td>
                        <?php foreach ($contractsitestatus as $key => $value) { ?>
                        <td class="col-sm-2">
                        <?php  echo img( base_url() .'assets/img/iconsnew/' .$value['icon_color']. 'blank.png'); ?>
                        <?php echo $value['name']; ?>
                        </td>
                     <?php }?>
                    </tr>
            </table>
            </div>
        </div>
        
    </div>
    <div class="box-body">
        <div id="myassetstatus"></div>   
         <?php  if($this->session->flashdata('success')){
         	echo '<div class="alert alert-success">'.$this->session->flashdata('success').'</div>';	
                }
                if($this->session->flashdata('error')) {
         	echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';	
                }
	?>  
        <div class="row">
            <div class="col-sm-4" style="padding-right: 5px;">
                <div   style="overflow: auto; max-height: 650px;">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                    <thead>
                    <tr class="theader">
                        <th class="col-md-1"><?php echo anchor('#', 'Status',  'title="Status" data-sortorder="1" class="progress_order" '); ?></th>

                        <th class="col-md-9"> 
                            <?php echo anchor('#', 'Site',  'title="Site" data-sortorder="2" class="progress_order" '); ?>  
                            <span class="left-margin10">
                                <?php echo anchor('#', 'NS',   'title="NS"   data-sortorder="3" class="progress_order" '); ?>
                                <?php echo anchor('#', 'SN',   'title="SN"   data-sortorder="4" class="progress_order" '); ?>
                                <?php echo anchor('#', 'EW',   'title="EW"   data-sortorder="5" class="progress_order" '); ?>
                                <?php echo anchor('#', 'WE',   'title="WE"   data-sortorder="6" class="progress_order" '); ?>
                            </span>
                        </th>

                        <th class="col-md-1">Grounds</th>
                        <th class="col-md-1">Pest</th>
                    </tr>
                    </thead>

                        <tbody>    

                        <?php foreach ($rows as $key=>$row): ?>
                        <tr>
                            <td>
                                 <a class="zoommap" href="#" data-targetlat="<?php echo $row['latitude'] ?>"  data-targetlng="<?php echo $row['longitude'] ?>" ><img src="<?php echo base_url() .'assets/img/iconsnew/' .($key +1). $row['iconcolor'] . '.png'; ?>"/></a>
                            </td>

                            <td>    
                                <strong><?php echo $row['BUDescription']?></strong>
                                <small><?php echo $row['sitesuburb']?></small>
                                <?php echo anchor_popup("https://maps.google.com/maps?q=" .$row['latitude']. "," .$row['longitude'], 'link', array('width'     =>  '\'+((parseInt(screen.width))* .7)+\'',
            'height'    =>  '\'+((parseInt(screen.height))* .7)+\'',
            'screenx'   =>  '\'+((parseInt(screen.width)* .3)/2)+\'',
            'screeny'   =>  '\'+((parseInt(screen.height)* .3)/2)+\'',
            )); ?>
                            </td>

                            <td>
                                <?php echo $row['GroundsMaintenanceServiceStandard'] ?>
                            </td>

                            <td>
                                <?php echo $row['PestControlServiceStandard'] ?>
                            </td>
                        </tr>

                        <?php endforeach; ?>    
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-8"  style="padding-left: 5px;">
                 <div  id="googleMap<?php echo $mapid ?>" class="myMap3" style="height: 650px;border:1px solid #d2d6de;"></div>
                
            </div>
        </div>
        
    </div><!-- /.box-body -->
 
  </div><!-- /.box -->
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
                   
                       $this->load->view('contracts/gmap_site_list_marker'); 
                       break;
                   case 'markerswithlabelroute':
                       $this->load->view('contracts/gmap_markerswithlabel_route');
                       break;
                   case 'directions':
                       $this->load->view('contracts/gmap_directions2'); 
                       break;
                   case 'heatmap':
                       $this->load->view('contracts/gmap_heatmap');
                       break;
                   case 'rectangles':
                       $this->load->view('contracts/gmap_rectangles');
                       break;
                   case 'polygons':
                       $this->load->view('contracts/gmap_polygons');
                       break;
                   case 'drawingmanager':
                       $this->load->view('contracts/gmap_drawingmanager');
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