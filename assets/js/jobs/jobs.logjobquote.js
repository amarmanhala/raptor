/* global base_url, bootbox, prioritydata */

$( document ).ready(function() {
  
    var sites='';
    if($('#sitelookup').length) {
        $('#sitelookup').typeahead({
                onSelect: function(item) {
                    selectSiteAddress(item.value);
                        
                },
                
                ajax: {
                    url: base_url + 'logjobquote/loadSiteSearch',
                    method: 'get',
                    loadingClass: "loading-circle",
                    preDispatch: function (query) {
                         return {
                            search: query

                        };
                    },
                    preProcess: function (data) {

                        if (data.success === false) {
                            return false;
                        }else{
                            sites = data.data;
                            return sites;    
                        }                
                    }

                },
            displayField: 'address',
            valueField: 'labelid'  
             
        });
    }
    
    var selectSiteAddress = function (labelid){
        sites = JSON.stringify(sites);
        sites = JSON.parse(sites);

        $.each( sites, function( key, val ) {
            if(val.labelid === labelid) {
                    $("#sitelookuphidden").val(val.sitesuburb + ' ' + val.sitestate);
                    $("#sitelookup").val(val.sitesuburb + ' ' + val.sitestate);
                    $("#labelid").val(val.labelid);
                    $("#siteline1").val(val.siteline1);
                    $("#siteline2").val(val.siteline2);
                    $("#sitesuburb").val(val.sitesuburb);
                    $("#sitestate").val(val.sitestate);
                    $("#sitepostcode").val(val.sitepostcode);
                    $("#sitecontactid").val(val.sitecontactid);
                    $("#sitecontact").val(val.sitecontact);
                    $('#siteemail').val(val.siteemail);
                    
                    if(val.sitemobile != ''){
                        $('#sitephone').attr('data-inputmask','"mask": "9999 999 999"'); 
                        $('#sitephone').attr('pattern','[0-9]{4} [0-9]{3} [0-9]{3}');
                        $('#sitephone').attr('placeholder','xxxx xxx xxx'); 
                        $('#sitephone').val(val.sitemobile); 
                    }
                    else{
                        $('#sitephone').attr('data-inputmask','"mask": "99 9999 9999"'); 
                        $('#sitephone').attr('pattern','[0-9]{2} [0-9]{4} [0-9]{4}');
                        $('#sitephone').attr('placeholder','xx xxxx xxxx');
                        $('#sitephone').val(val.sitephone); 
                    }
                    
                    
                    if($('#use_site_ref_as_custordref').length) {
                        if(parseInt($('#use_site_ref_as_custordref').val()) === 1){
                            $("#custordref").val(val.siteref);
                        }
                    }

                    if($('#use_site_ref_as_custordref2').length) {
                        if(parseInt($('#use_site_ref_as_custordref2').val()) === 1){
                            $("#custordref2").val(val.siteref);
                        }
                    }
                    
                  
                    
                    
                    $('#sitefm').val(val.sitefm);
                    
                    if(val.sitefmmobile != ''){
                        $('#sitefmph').attr('data-inputmask','"mask": "9999 999 999"'); 
                        $('#sitefmph').attr('pattern','[0-9]{4} [0-9]{3} [0-9]{3}');
                        $('#sitefmph').attr('placeholder','xxxx xxx xxx'); 
                    }
                    else{
                        $('#sitefmph').attr('data-inputmask','"mask": "99 9999 9999"'); 
                        $('#sitefmph').attr('pattern','[0-9]{2} [0-9]{4} [0-9]{4}');
                        $('#sitefmph').attr('placeholder','xx xxxx xxxx'); 
                    }
                    if($("[data-mask]").length) {
                        $("[data-mask]").inputmask();
                    }
                    $('#sitefmph').val(val.sitefmph); 
                    $('#sitefmemail').val(val.sitefmemail); 
                    if($("#sitestate.select2").length) {
                        $("#sitestate.select2").select2();
                    }
                    if($("#sitestate.selectpicker").length) {
                        $("#sitestate.selectpicker").select2();
                    }
                  
                }	
        });
    };
    
    $(document).on('change', '#sitelookup', function() {
       
       $(this).val($("#sitelookuphidden" ).val());
       
    });
    
    
    
    $(document).on('change', 'select#custordref', function() {
        
        if($('select#custordref').val() === 'other'){
            $('#custordrefother').show();
           
            if($('select#custordref').hasClass('required')){ 
                $("custordrefother").addClass('required');
                $("custordrefother").attr('required', 'required');
            }
        }
        else{
            $('#custordrefother').hide();
             $("#custordrefother").removeClass('required');
            $("#custordrefother").removeAttr('required');
        }
        
    });
    $('select#custordref').change();
    
    $(document).on('click', '#btnviewposummary', function() {
        $("#customerPOModal").modal();
    });
    $(document).on('click', '#btn_sitelookup', function() {
 
        
        $("#isitelookup").modal();
        $('#isitelookup #loading-img').show();
        $('#isitelookup #sitegriddiv').hide();
        $('#isitelookup #modalok').attr("disabled", "disabled");

        $.get( base_url + 'logjobquote/loadsitelookup/q', { 'get':1 }, function( data ) {
            
            if (data.success === false) {
               
                bootbox.alert(data.message);
                $("#isitelookup").modal('hide');
                return false;
            }else{
              
                sites = data.data;
                var $result = '';
                $.each( sites, function( key, val ) {
                   
                    $result=$result+'<tr id="'+ val.labelid +'" style="cursor: pointer;"><td>'+val.siteline1+'</td>';
                    if ($("#show_custom_attributes").val()=== 'yes'){ 
                        $result = $result +'<td>'+val.BE+'</td><td>'+val.BU+'</td><td>'+val.siteref+'</td>';
                    }
                    $result=$result+'<td>'+val.siteline2+'</td><td>'+val.sitesuburb+'</td><td>'+val.sitestate+'</td><td>'+val.sitepostcode+'</td>';
                    $result=$result+'<td>'+val.sitefmemail+'</td>';
                    $result=$result+'<td>'+val.sitefm+'</td><td>'+val.sitephone+'</td></tr>';
                });
                
                $("#isitelookup #sitegridbody").html($result);
                $('#isitelookup #loading-img').hide();
                $('#isitelookup #sitegriddiv').show(); 
                $('#isitelookup #modalok').removeAttr("disabled");   
            }  
           
        },'json');
		
    });
        
    $(document).on('click', 'input[name="quoterqd"]', function() {

        if($('input[name="quoterqd"]').prop('checked')){
            $("select#notexceed").removeClass('required');
            $("select#notexceed").removeAttr('required');
            $("select#notexceed").attr('disabled', 'disabled');
            $("select#notexceed").select2();
            $("select#notexceed").val('');
        }
        else{
            $("select#notexceed").addClass('required');
            $("select#notexceed").attr('required', 'required');
            $("select#notexceed").removeAttr('disabled', 'disabled');
            $("select#notexceed").select2();
        }
     
        
    });
       
    $(document).on('click', '#isitelookup table tbody tr', function() {

        
        var id = $(this).attr("id");
        selectSiteAddress(id);
       
        $("#isitelookup").modal('hide');
    });
       
    $(document).on('change', '#priority', function() {
        var priority = $('#priority option:selected').text();
        var days =  $('#priority option:selected').val();

 

        if (priority.indexOf('Scheduled') > -1){
            $("#attend").modal();

        }
        else{
            var duedate= new Date(new Date().getTime() + days*24*60*60*1000);
            var dd = duedate.getDate();
            var mm = duedate.getMonth() + 1;
            var y = duedate.getFullYear();

            if(dd < 10) {
                dd = "0" + dd;
            }
            if(mm < 10) {
                mm = "0" + mm;
            }	

            var  formattedDueDate=  dd+'/'+mm+ '/'+y;
            $('#fakeattenddate').val(formattedDueDate);
            var lockdate = $('#responsedatelock').val();
            //if (!lockdate){
              $('#attenddate').val(formattedDueDate);
            //}
        }

      
        $.each( prioritydata, function( key, val ) {
                   
            if(parseInt(val.days_offset) === parseInt(days)){
                if(val.usermessage !== null && val.usermessage !== ''){
                     bootbox.alert(val.usermessage);
                }
                return false;
            }
             
        });


    });
        
    $(document).on('click', '#attend #timebtnok', function() {
        var mustattend = $('#mustattend').is(':checked');
        $('#responsedatelock').val(mustattend);
        $('#attenddate').val($('#attendancedate').val());
        $('#attendtime').val($('#attendancetime').val()); 
        $("#attend").modal('hide');
    });

    $(document).on('click', '#attend #timebtncancel', function() {
        $("#attend").modal('hide');
    });
});   
 
