<?php if($job_id_readonly) {
    echo $jobids[0]['id'].' - '.$jobids[0]['address'];
    echo '<input type="hidden" name="jobidSelect" id="jobidSelect" value="'.$jobids[0]['id'].'">';
 } else { ?>
<select id="jobidSelect" name="jobidSelect" title="Select JobID" class="form-control">    
        <option value="0">Select</option>
        <?php foreach($jobids as $jobid) : ?>
            <option <?php if($jobid['id'] == $job_id): ?>selected<?php endif;?> value="<?php echo $jobid['id']; ?>" ><?php echo $jobid['name'].' - '.$jobid['address']; ?></option>
        <?php endforeach; ?>
</select>
<?php } ?>
