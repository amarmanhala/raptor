<?php //var_dump($assets); ?>

<div class="row top-margin">
        <div class="col-md-2"><h4>Assets</h4></div>
        <div class="col-md-3"> 
            <button type="button" id="redirect_btn" data-url="EditableReport/create" class="btn btn-default btn-sm"><i class="fa fa-plus"></i> Add Asset</button>
        </div>
</div>

<table class="table table-striped table-bordered table-condensed table-hover">
    <thead>    
    <tr>
        <th class="col-md-2">Description</th>
        <th class="col-md-2">Category</th>
        <th class="col-md-2">Criticality</th>
        <th class="col-md-2">Desired Condition</th>
        <th class="col-md-2">Actual Condition</th>
        <th class="col-md-2">Remaining Life</th>
    </tr>    
    </thead>    
    <tbody> 
        
        <?php foreach ($assets as $index3=>$asset): ?>
            <tr><td>
                    <?php //echo $asset['description'] . '<br>'; ?>
                    <?php $this->load->view('editablereport/xeditable_element', $asset['x_editable']['description']); ?>
                </td><td>    
                    <?php echo $asset['category_name']; ?>
                </td><td>    
                    <?php //echo $asset['asset_criticality']; ?>  
                    <?php $this->load->view('editablereport/xeditable_element', $asset['x_editable']['asset_criticality']); ?>
                </td><td>    
                    <?php //echo $asset['desired_condition']; ?>
                    <?php $this->load->view('editablereport/xeditable_element', $asset['x_editable']['desired_condition']); ?>
                </td><td>    
                    <?php //echo $asset['actual_condition']; ?> 
                    <?php $this->load->view('editablereport/xeditable_element', $asset['x_editable']['actual_condition']); ?>
                </td><td>    
                    <?php //echo $asset['remaining_life_expectancy']; ?>  
                    <?php $this->load->view('editablereport/xeditable_element', $asset['x_editable']['remaining_life_expectancy']); ?>
            </td></tr>    

        <?php endforeach; ?>
        
    </tbody>    
</table>