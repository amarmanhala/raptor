/* global bootbox */
"use strict";
 var readExcelURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();
     
    if($.inArray(ext, ['xls','xlsx']) === -1) {
        $(input).val('');
        
        bootbox.alert('invalid file format!');
        return false;
    }
     return true;
};