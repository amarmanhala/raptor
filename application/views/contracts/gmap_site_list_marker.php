<?php  foreach ($rows as $key=>$row): ?>
    <?php if(!is_null($row['latitude'])): ?>
        <?php $this->load->view('contracts/gmap_marker_label', $row); ?>
   <?php endif; ?> 
<?php  endforeach; ?>                  

<?php $this->load->view('contracts/gmap_marker_spiderfier'); ?>  
            