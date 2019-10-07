<!-- sort sites by latitude -->
<?php usort($rows, sortArraybyString('latitude')); ?> 

<?php $omsCounter = 0 ?>
<?php  foreach ($rows as $key=>$row): ?>    
    <?php $omsCounter = 0;?> 
    <?php if(!is_null($row['latitude'])): ?>
        <?php if((($key+1) < count($rows)) && ($rows[$key]['latitude'] == $rows[$key+1]['latitude'] && ($key == 0 || $rows[$key]['latitude'] != $rows[$key-1]['latitude']))): ?>
             <!-- GPS same as the next site but different to previous -->
             var oms<?php echo $omsCounter ?> = new OverlappingMarkerSpiderfier(map, {markersWontMove: true, markersWontHide: true, keepSpiderfied: true, circleSpiralSwitchover: 8 });
             oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['sitenum'] ?>);
        <?php elseif($key > 0 && $rows[$key]['latitude'] == $rows[$key-1]['latitude']): ?>
             <!-- GPS same as the last site -->     
             oms<?php echo $omsCounter ?>.addMarker(marker<?php echo $row['sitenum'] ?>)

             <?php if((($key+1) == count($rows)) || $rows[$key]['latitude'] != $rows[$key+1]['latitude']): ?>
                    <!-- next GPS different to this site --> 
                    <?php $omsCounter++;?>
             <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>                  
<?php  endforeach; ?>  