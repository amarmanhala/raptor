<?php foreach ($rows as $row): ?>
<?php $this->load->view('editablereport/add_areatypeareatopic_btn', $row); ?>

<table class="table table-striped table-bordered table-condensed table-hover top-margin5">
    <caption class="caption"><?php $this->load->view('editablereport/xeditable_element', $row['x_editable']['area_description']); ?>
    <thead>    
    <tr>
        <th >Topic</th>
        <th >Yes/No</th>
        <th >Clean/Dirty</th>
        <th >Damaged</th>
        <th >Rating</th>
        <th >Topic Description</th>
        <th >Remedial Action</th>
        <th ></th>
    </tr>    
    </thead>
    <tbody>
       <?php foreach ($row['rows'] as $index2=>$row2): ?> 
        <tr>            
            <td>
                 <?php echo $row2['areatopic_name']; ?>
            </td>
            
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $row2['x_editable']['ynna']); ?>
            </td>
            
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $row2['x_editable']['dirty']); ?>
            </td>
            
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $row2['x_editable']['damaged']); ?>
            </td>
            
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $row2['x_editable']['dirty_rating']); ?>
            </td>
            
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $row2['x_editable']['topic_description']); ?>
            </td>
            
            <td>
                <?php $this->load->view('editablereport/xeditable_element', $row2['x_editable']['remedial_action']); ?>
            </td>
            
            <td class="text-center">
                <button data-url="" data-id="<?php echo $row2['id']; ?>" type="button" class="btn btn-default btn-xs delete_areatypeareatopic_btn">Delete</button>
            </td>
        </tr>   
      <?php endforeach; ?>        
    </tbody>    
</table>    

<?php $this->load->view('editablereport/add_areatypeareatopic_btn', $row); ?>
<?php $this->load->view('editablereport/areatypetopicvalue_photo', $row); ?>
<?php $this->load->view('editablereport/areatypetopicvalue_asset', $row); ?>


<?php endforeach; ?>    

        

