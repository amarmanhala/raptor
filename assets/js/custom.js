/* global defaultdateformat, base_url, bootbox, CKEDITOR */

var eventDatePickerCounter = 0;
$( document ).ready(function() {
    
        $('[data-toggle="tooltip"]').tooltip({ html: true, container: 'body' });
        
        $('[data-toggle="tooltip2"]').tooltip({
            html: true,
            container: 'body',
            placement:'auto right'
        });
        $('[id^="typeahead-"].dropdown-menu li a').tooltip({
            html: true,
            container: 'body',
            placement:'auto right'
        });
	if($('.datepicker').length) {
            $('.datepicker').datepicker({autoclose: true, format: defaultdateformat}).on('show', function(){ eventDatePickerCounter = 0; });
             
            $(".datepicker").next('.input-group-addon').click(function() {
                var sss=$(this).prev();
                sss.focus();
                   
            });
        }
         
          //Timepicker
        if($('.timepicker').length) {
            $(".timepicker").timepicker({
                showInputs: false
            });
            
            $(".timepicker").next('.input-group-addon').click(function() {
                var sss=$(this).prev();
                sss.focus();
                   
            });
        }
        
        if($('.datetimepicker').length) {
            $('.datetimepicker').datetimepicker({
                format: defaultdateformat.toUpperCase()+' hh:mm A',
                ignoreReadonly: true,
                sideBySide: true
            });
        }
        
        $(document).on('click', '.datetimepicker + .input-group-addon', function(event) {
            var sss=$(this).prev();
            sss.focus();
        });
        
        $(document).on('click', 'input[type="email"] + .input-group-addon i.fa-envelope', function() {
 
            var sss=$(this).parent().prev();
            if(sss.val()!=""){
                window.open('mailto:'+sss.val());
            }

        });

        $(document).on('click', 'input[type="url"] + .input-group-addon i.fa-link', function() {

             var sss=$(this).parent().prev();
             if(sss.val()!=""){
                window.open(sss.val());
            }

        });
        
        //Enable sidebar toggle
        if ($.cookie('sidebar-cookies') === 'collapsed') {
            $("body").addClass('sidebar-collapse').trigger('collapsed.pushMenu');
        };
      
        //Enable sidebar toggle
        $('.sidebar-toggle').on('click', function (e) {
            if ($("body").hasClass('sidebar-collapse') || $("body").hasClass('sidebar-open')) {
                 $.cookie('sidebar-cookies', 'collapsed', {expires:365, path:'/' });
            } else {
                 $.cookie('sidebar-cookies', 'collapsed', {expires:-365, path:'/' });  
            }

        });
      
        $(document).on('keypress keyup blur', '.allownumericwithdecimal', function(event) {
           //this.value = this.value.replace(/[^0-9\.]/g,'');
            $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if (event.which !== 8 && event.which != 0 && (event.which !== 46 || $(this).val().indexOf('.') !== -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
       
        $(document).on('keypress keyup blur', '.allownumericwithoutdecimal', function(event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if (event.which !== 8 && event.which !== 0 && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
         
         if($("[data-mask]").length) {
             $("[data-mask]").inputmask();
         }
        if($(".select2").length) {
            $(".select2").select2();
        }
        
        if($(".selectpicker").length) {
              $('.selectpicker').each(function() {
                  $(this).selectpicker();
              });
        
        }
        
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        if($("#ckeditor").length) {
            CKEDITOR.replace('ckeditor',
                {
                extraPlugins: 'imageuploader'
                }
            );
        }
       
        
        if (typeof $.fn.iCheck === "function") {
            $('input').iCheck({
             checkboxClass: 'icheckbox_square-grey',
             radioClass: 'iradio_square-grey',
             increaseArea: '20%' // optional
           });
        }
         
	var cities='';
        if($('.suburbtypeahead').length) {
            $('.suburbtypeahead').each(function() {
               
                $(this).typeahead({
                        ajax: {
                            url: base_url + 'ajax/loadpostcode',
                            method: 'get',
                            preDispatch: function (query) {
                              
                                 return {
                                    search: query,
                                    type:'city'
                                };
                            },
                            preProcess: function (data) {

                                if (data.success === false) {
                                    return false;
                                }else{
                                    cities = data.data;
                                    return cities;    
                                }                
                            }
                        },
                         
                        onSelect: function(item) {
                            cities = JSON.stringify(cities);
                            cities = JSON.parse(cities); 
                            var cid=this.id;
                     
                            var suburb=$('#'+cid).attr('data-suburb');
                            var state=$('#'+cid).attr('data-state');
                            var postcode=$('#'+cid).attr('data-postcode');
                            var formid = $('#'+cid).closest('form').attr('id');
                           
                            var territory = 'territory';
                            $.each( cities, function( key, val ) {
                                if($.trim(val.postcodeid) === $.trim(item.value)) {
                                      
                                   
                                    if(formid != ''){
                                        if($('#'+formid+' #'+state).length) {
                                   
                                            $('#'+formid+' #'+state).val(val.state);
                                            if($('#'+formid+' #'+state).hasClass('select2')){
                                                $('#'+formid+' #'+state).select2();
                                            }
                                        }
                                        else{
                                            if($('#'+state).length) {
                                                $(' #'+state).val(val.state);
                                                if($('#'+state).hasClass('select2')){
                                                    $('#'+state).select2();
                                                }
                                            }
                                        }
                                        if($('#'+formid+' #'+postcode).length) {
                                            $('#'+formid+' #'+postcode).val(val.postcode);
                                        }
                                        else{
                                            if($('#'+postcode).length) {
                                                $('#'+postcode).val(val.postcode);
                                            }
                                        }
                                        
                                        if($('#'+formid+' #'+suburb).length) {
                                            $('#'+formid+' #'+suburb).val(val.suburb);
                                        }
                                        else{
                                            if($('#'+suburb).length) {
                                                $('#'+suburb).val(val.suburb);
                                            }
                                        }
                                        
                                        
                                        if($('#'+formid+' #'+territory).length) {
                                            $('#'+formid+' #'+territory).val(val.territory);
                                        }
                                        else{
                                            if($('#'+territory).length) {
                                                $('#'+territory).val(val.territory);
                                            }
                                        }
                                    }else{
                                        if($('#'+state).length) {
                                            $(' #'+state).val(val.state);
                                            if($('#'+state).hasClass('select2')){
                                                $('#'+state).select2();
                                            }
                                        }
                                        
                                        if($('#'+postcode).length) {
                                            $('#'+postcode).val(val.postcode);
                                        }
                                        if($('#'+suburb).length) {
                                            $('#'+suburb).val(val.suburb);
                                        }
                                        if($('#'+territory).length) {
                                            $('#'+territory).val(val.territory);
                                        }
                                    }
                                    


                                }	
                            });
                        },
                        displayField: 'displaytext',
                        valueField: 'postcodeid',
                        id:this.id
                });
            });
        }
        
        $(document).on('change', '.suburbtypeahead', function() {
            
            var id=$(this).attr('data-suburb');
            $(this).val($("#"+id ).val());
             
         });
    
	var postcode='';
         if($('.postcodetypeahead').length) {
            $('.postcodetypeahead').each(function() {
               
                $(this).typeahead({
                    ajax: {
                        url: base_url + 'ajax/loadpostcode',
                        method: 'get',
                        preDispatch: function (query) {
                             return {
                                search: query,
                                type: 'postcode'
                            };
                        },
                        preProcess: function (data) {

                            if (data.success === false) {
                                return false;
                            }else{
                                postcode = data.data;
                                return postcode;    
                            }                
                        }
                    },
                    onSelect: displayPostcode,
                    displayField: 'postcode',
                    valueField: 'postcode'           
                });
            });
         }
	
	var displayPostcode = function(item) {

            postcode = JSON.stringify(postcode);
            postcode = JSON.parse(postcode);

            $.each( postcode, function( key, val ) {
                if(val.postcode === item.value) {

                }	
            });
	 };
   
	
       
       if (typeof $.fn.validate === "function") {
            $.validator.addMethod("regex", function(value, element, regexpr) {
                     if(value===""){
                             return true;
                     }          
                  return regexpr.test(value);
             }, "Please enter a valid requested formet."); 

             $.validator.addMethod("fileval", function(value, element, regexpr) {
                          if(value===""){
                                  return true;
                          }          
                       return regexpr.test(value);
             }, "Please select image/pdf file formet."); 
             
            $.validator.addMethod("validatefile", function(value) {
		 
		if($.trim(value) === '') {
			return false;
		}
		else {
			return true;
		}

            }, 'This field is required.');

            $.validator.addMethod("validaterequired", function(value) {
   
                if($.trim(value) === '') {
                 return false;
                }
                else {
                 return true;
                }

            }, 'This field is required.');
              $.validator.addMethod("validemail", function(value, element, regexpr) {
                return regexpr.test(value);
            }, "Please enter a valid email address."); 

            $.validator.setDefaults({
                ignore: ':not(select:visible, input:visible, textarea:visible, input:hidden)'
            });
      
    
    }
  
    $(document).on('mouseover', ".job-detail-model", function() {
        var jobid = $(this).attr("data-jobid");
        jobdetailfunc(jobid);
    });
    
    if($('#announcementModal').length) {
        $('#announcementModal').modal(); 
        
        $(document).on('click', "#announcementModal #dont_show_again", function() {
            var messageid = $('#announcementModal #messageid').val();
            var checked = $(this).is(":checked");
            var chk = 0;
            if(checked) {
                chk = 1;
            }
            $.post( base_url+"ajax/dontshowannouncement", {messageid : messageid, chk : chk}, function( data ) {
               
            });
        });
    }
    if($('#browserannouncementModal').length) {
        $('#browserannouncementModal').modal(); 
        
        $(document).on('click', "#browserannouncementModal #dont_show_again", function() {
            var messageid = $('#browserannouncementModal #messageid').val();
            var checked = $(this).is(":checked");
            var chk = 0;
            if(checked) {
                chk = 1;
            }
            $.post( base_url+"ajax/dontshowannouncement", {messageid : messageid, chk : chk}, function( data ) {
               
            });
        });
    }
    
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $(document).on('click', '.nav-tabs li > a', function(e){
      
        if($(this).parent('li').hasClass('disabled')){
            return false;
        }
        else{
            $(this).tab('show');
            var scrollmem = $('body').scrollTop() || $('html').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        }
    });
    
    $(document).on('click', '.nav-tabs li.disabled > a[data-toggle=tab]', function(e){
    
        return false;
    });
    
    $(document).on('click', '.browse', function(){
        var file = $(this).parent().parent().parent().find('.file');
        file.trigger('click');
    });
    $(document).on('change', '.file', function(){
      $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
    });
    
    if($('#marketingPanel').length) { 
         
        loadMarketingContent(marketingPostData);
        marketingPauseTime = parseInt(marketingPauseTime)*1000;
        setInterval("loadMarketingContent(marketingPostData)", marketingPauseTime);
        
        $( window ).resize(function() {
            if($( document ).width()>768){
                 $(".control-sidebar-bg, .control-sidebar").css('width', marketingWidth);
                 $(".control-sidebar-open .content-wrapper, .control-sidebar-open .right-side, .control-sidebar-open .main-footer").css('margin-right', marketingWidth);
            }
            else{
                $(".control-sidebar-bg, .control-sidebar").css('width', 0);
                  $(".control-sidebar-open .content-wrapper, .control-sidebar-open .right-side, .control-sidebar-open .main-footer").css('margin-right', 0);
            }
         }); 
    }
    
    $(document).on('click', 'input[name=widget_month]', function() {
        getBudgetWidgetData();
    });
    
    $(document).on('change', '#widget_glcode', function() {
        getBudgetWidgetData();
    });
    
    $( "ul.sidebar-menu li.treeview" ).on('click', function() {
        var treesubmenu = $(this).attr('treesubmenu');
        if(typeof treesubmenu !== typeof undefined && treesubmenu !== false) {
            $(this).removeAttr('treesubmenu');
        } else {
            $(this).attr('treesubmenu', '');
        }
    });
 });   

 
 var readExcelURL = function(input) {
	 
    var ext = $(input).val().split('.').pop().toLowerCase();

    if($.inArray(ext, ['xls','xlsx']) === -1) {
        $(input).val('');

        alert('invalid file format!');
        return false;
    }
     return true;
};


function confirmdelete(atr)
{
    var url=$(atr).attr('href');
    var value=$(atr).attr('data-value');

    bootbox.confirm("Are you sure to delete this record "+value, function(result) {
        if(result) {
            $.post( url, {'action' :  'delete'}, function( data ) {
                bootbox.dialog({
                            message: "<span class='bigger-110'>"+data+"</span>",
                            buttons: 			
                            {
                                "button" :
                                {
                                    "label" : "Ok",
                                    "className" : "btn-sm btn-success"
                                }
                            }
                    });
            });
        }
    });
    return false; 
}
var intVal = function ( i ) {
    return typeof i === 'string' ?  i.replace(/[\$,]/g, '')*1 :
    typeof i === 'number' ?  i : 0;
};

var urldecode = function(url) {
  return decodeURIComponent(url.replace(/\+/g, ' '));
};

var modaloverlap = function() {
    if ($('.modal').hasClass("in")) {
        setTimeout(function() {
           $('body').addClass('modal-open');
        }, 600);
    }
};

var navigationMouseOut = function(e) {
    var treesubmenu = $(e).parent().attr('treesubmenu');
    if (typeof treesubmenu !== typeof undefined && treesubmenu !== false) {
    } else {
        e.removeAttribute('style');
    }
};

var navigationMouseOver = function(e, text, background, border) {
    e.style.color = text;
    e.style.background = background;
    e.style.borderLeftColor = border;
};

var formatCustomDateAMPM = function(date) {
    
    var dd = date.getDate();
    var mm = date.getMonth() + 1;
    var y = date.getFullYear();
    dd = dd < 10 ? '0'+dd : dd;
    mm = mm < 10 ? '0'+mm : mm;
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    hours = hours < 10 ? '0'+hours : hours;
    var strTime = dd+'/'+mm+'/'+y+' '+hours + ':' + minutes + ' ' + ampm;
    return strTime;
};

var formatCustomDate = function(date) {
    
    var dd = date.getDate();
    var mm = date.getMonth() + 1;
    var y = date.getFullYear();
    dd = dd < 10 ? '0'+dd : dd;
    mm = mm < 10 ? '0'+mm : mm;
    var str = dd+'/'+mm+'/'+y;
    return str;
};

var formatAMPM = function(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    hours = hours < 10 ? '0'+hours : hours;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
};

 var getDateTime = function(datestring) {

    var datestringar = datestring.split(" ");
    var datear = datestringar[0].split("/");
    datestringar = convert_to_24h(datestring);
    var timear = datestringar.split(":");
    datestring = new Date(datear[2], datear[1]-1, datear[0], timear[0], timear[1]);
    return datestring;
};
         
var convert_to_24h = function(time_str) {
    var time = time_str.match(/(\d+):(\d+) (\w)/);
    var hours = Number(time[1]);
    var minutes = Number(time[2]);
    var meridian = time[3].toLowerCase();

    if (meridian === 'p' && hours < 12) {
      hours = hours + 12;
    }
    else if (meridian === 'a' && hours === 12) {
      hours = hours - 12;
    }
    return hours+":"+minutes;
};



var jobdetailfunc = function(jobid) {
            
    $("#jobDetailModal").modal();
    $('#jobDetailModal center').show();
    $("#jobDetailModal #jobdetail_form").hide();
    $("#jobDetailModalLabel").html('Job Preview DCFM Job No: '+jobid);
    $.get( base_url+"jobs/loadjobdetail", { 'get':1, 'jobid':jobid }, function( data ) {
        if(data.success){
            var value = data.data;
            $("#jobdetail_form #leaddate").html(value.leaddate);
            $("#jobdetail_form #jobstage").html(value.portaldesc);
            $("#jobdetail_form #companyname").html(value.companyname);
            $("#jobdetail_form #custordref").html(value.custordref);
            
            $("#jobdetail_form #qapproval").html(value.quoteapprovalby + ' on '+ value.qdateaccepted);
            $("#jobdetail_form #japproval").html(value.jobapprovalby + ' on '+ value.jobapprovaldate);
            $("#jobdetail_form #vapproval").html(value.variationapprovalby + ' on '+ value.vapprovaldate);
            if(value.quoteapprovalby === ''){
                $("#jobdetail_form #qapprovaldiv").hide();
            }
            else{
                $("#jobdetail_form #qapprovaldiv").show();
            }
            if(value.jobapprovalby === ''){
                $("#jobdetail_form #japprovaldiv").hide();
            }
            else{
                $("#jobdetail_form #japprovaldiv").show();
            }
            if(value.variationapprovalby === ''){
                $("#jobdetail_form #vapprovaldiv").hide();
            }
            else{
                $("#jobdetail_form #vapprovaldiv").show();
            }
            
            $("#jobdetail_form #site").html(value.site);
            $("#jobdetail_form #sitecontact").html(value.sitecontact);
            $("#jobdetail_form #sitephone").html(value.sitephone);
            $("#jobdetail_form #siteemail").html(value.siteemail);
            $("#jobdetail_form #sitefm").html(value.sitefm);
            $("#jobdetail_form #sitefmph").html(value.sitefmph);
            $("#jobdetail_form #sitefmemail").html(value.sitefmemail);
            $("#jobdetail_form #jobdescription").html(value.jobdescription);
             
            $('#jobDetailModal center').hide();
            $("#jobDetailModal #jobdetail_form").show();
        }
        else{
            bootbox.alert(data.message);
        }
         
    }, 'json');
};
  
  
setInterval('loadnavigationcounter()', 15000);
 

function loadnavigationcounter() {
 
    $.post( base_url+"ajax/loadnavigationcounter", function( response ) {
        if(response.success){
            var counterdata=response.data;
            $.each(counterdata, function(key, value){
                $('ul.sidebar-menu li a small.'+key).html(value);
                $('#marketingSecond li a small.'+key).html(value);
            });
        }
    }, 'json');
}

var marketingPostData = {
    positionThird : 0
};
 
var loadMarketingContent = function(marketingPostData) {
    
    
    if($( document ).width()>767){
        
        //var wrapperWidth = $(".wrapper").width();
        $(".control-sidebar-bg, .control-sidebar").css('width', marketingWidth);
        $(".control-sidebar-open .content-wrapper, .control-sidebar-open .right-side, .control-sidebar-open .main-footer").css('margin-right', marketingWidth);

        var dwelltime = 0;
        $.post( base_url+"ajax/getmarketingcontent", marketingPostData, function( data ) {
            if(data.success) {
                $.each( data.data.marketing_content, function( key, val ) {
                    if(key === 0 && parseInt(val.n) !== 0) {
                        $("#marketingPanel #marketingThird").html(val.content);
                        marketingPostData.positionThird = val.n;
                        dwelltime = parseInt(val.dwelltime)*1000;
                        setTimeout("removeMarketingContent()", dwelltime);
                    }
                });

                var marketing_messages = '';
                $.each( data.data.marketing_messages, function( key, val ) {
                    marketing_messages = marketing_messages + '<tr><td>' + val['content'] + '</td></tr>';
                });

                $("#marketingSecond table").html(marketing_messages);
            }
            else {
                bootbox.alert(data.message);
            }
        }, 'json');
    }
    else{
         $(".control-sidebar-bg, .control-sidebar").css('width', 0);
         $(".control-sidebar-open .content-wrapper, .control-sidebar-open .right-side, .control-sidebar-open .main-footer").css('margin-right', 0);

    }
};

var removeMarketingContent = function() {
    $("#marketingThird").html('');
};


function budgetWidgetChart(xaxis, series, colors) {
    
    $('#budgetWidgetChart').highcharts({
        colors: colors,
        chart: {
            type: 'column',
            height: 250
        },
        credits: {
            enabled: false
        },
        title: {
            text:''
        },
        xAxis: {
            categories: xaxis,
            labels: {
                formatter: function () {
                    var ret = this.value;
                    ret = ret.split('-');
                    if(ret[0] === 'n') {
                        return '<span style="color:red;">'+ret[1]+'</span>';
                        
                    } else {
                        return this.value;
                    }
                }            
            }
        },
        yAxis: {
            min: 0,
            title: {
                enabled:false
            },
            labels: {
                enabled: false
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '{series.name}: ${point.y}'
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: series
    });
}

var getBudgetWidgetData = function() {
    
    if(!document.getElementById('widget_check')) {
        return false;
    }

    var month = $('input[name=widget_month]:checked').val();
    var cdate = $('#widget_current_date').val();
    var ndate = $('#widget_next_date').val();
    var jobid = $('#widget_jobid').val(); //selected current jobid from grids
    var glcode = $('#widget_glcode').val();
    
    $.post( base_url+"budgets/getbudgetwidgetdata", { month: month, cdate: cdate, ndate: ndate, jobid: jobid, glcode: glcode }, function( response ) {

        if(response.success) {
            if(parseInt(response.data.remainingBudget[0]) < 0) {
                $("#widgetBudgetLeftFirst").css('color', 'red');
            } else {
                $("#widgetBudgetLeftFirst").removeAttr('style');
            }
            if(parseInt(response.data.remainingBudget[1]) < 0) {
                $("#widgetBudgetLeftSecond").css('color', 'red');
            } else {
                $("#widgetBudgetLeftSecond").removeAttr('style');
            }
            $("#widgetBudgetLeftFirst").html('$' + response.data.remainingBudget[0]);
            $("#widgetBudgetLeftSecond").html('$' + response.data.remainingBudget[1]);
            budgetWidgetChart(response.data.xaxis, response.data.series, response.data.colors);
        }
        else{
            bootbox.alert(response.message);
        }
    }, 'json');
};

var callBudgetWidget = function() {
    if(document.getElementById('widget_check')) {
        getBudgetWidgetData();
    }
};
callBudgetWidget();