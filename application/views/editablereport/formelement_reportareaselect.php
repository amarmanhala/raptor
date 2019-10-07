                <select id="reportAreaSelect" name="reportAreaSelect" title="Select Area" class="show-tick form-control" data-live-search="true" data-size="auto" data-width="100%">
                        <?php foreach($areas as $area) : ?>
                            <option <?php if($area['guid'] == $areavalue_guid): ?>selected<?php endif;?> value="<?php echo $area['guid']; ?>" > <?php echo $area['name']; ?></option>
                        <?php endforeach; ?>
                </select>