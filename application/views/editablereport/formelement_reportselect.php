<?php if($report_id_readonly) {
    foreach($reports as $report) : 
        if($report['id'] == $report_id):
            echo $report['id'].' - '.$report['report_type'];
            echo '<input type="hidden" name="reportSelect" id="reportSelect" value="'.$report_id.'">';
        endif;
     endforeach;
 } else { ?>
<select id="reportSelect" name="reportSelect" title="Select Report" class="show-tick form-control" data-live-search="true" data-size="auto" data-width="100%">    
     <?php if(count($reports)==0){ ?>
    <option value="0">Select Report</option>    
    <?php } ?>
    <?php foreach($reports as $report) : ?>
        <option <?php if($report['id'] == $report_id): ?>selected<?php endif;?> value="<?php echo $report['id']; ?>" > <?php echo $report['id']; ?> - <?php echo $report['report_type']; ?></option>
        <?php endforeach; ?>
</select>
<?php } ?>
