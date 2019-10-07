

$(document).ready(function() {
    
     $(document).on('click', '.progress_order', function(event) {
	 
                $('#sortorder').val($(this).data('sortorder'));
		relocate($(this).data('sortorder'));
	  	event.preventDefault();
	     	return false; 
    });
    $(document).on('click', '#search_customer_progress_btn', function(event) {
 
		relocate($("#sortOrder" ).val());
		event.preventDefault();
	     	return false; 
    });
    $(document).on('click', '#resetfilter', function(event) {
 
        var url = base_url +  'contracts';
	
	$(location).attr('href',url);
    });   
       
});

function relocate(sortorder) {
	var url = base_url +  'contracts?' + $('#techmap').serialize();
	
	$(location).attr('href',url);
}