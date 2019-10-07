$(function() {
    'use strict';
    var root_url = base_url.split("/");
    root_url.pop();
    root_url.pop();
    root_url = root_url.join("/");

    var upload_url = root_url + '/itglobal/shared/server/php/';

    $('.fileupload')
        .fileupload({
            url: upload_url,
            dataType: 'json',
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 10000000,
            submit: function(e, data) {},
            done: function(e, data) {
                var guid = $(this).data('guid');
                var activefiles = $(this).fileupload('active');
                $.each(data.result.files, function(index, file) {
                    var url = base_url + "EditableReport/uploadfile/" + escape(file.name) + "/" + $("#jobidSelect").val() + "/" + $("#reportSelect").val() + "/" + guid;
                    console.log(url);
                    console.log('uploading index=' + index + '  file=' + escape(file.name));
                    $.ajax({
                        url: url,
                        data: null,
                        dataType: 'json',
                        success: function(data) {
                            if (!data['result']) {
                                showModal(1, 'Error !', data['message']);
                            }
                            if (activefiles == 1) {
                                console.log('last upload ');
                                $("#main_div").html("<img src='" + base_url + "itglobal/shared/assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");
                                location.reload(true);
                            }
                        }
                    });
                });
            },
            progressall: function(e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            },
            processfail: function(e, data) {
                $('#uploadfiles_alert').empty();
                $('<p/>').text(data.files[data.index].name + " upload failed " + data.files[data.index].error).appendTo('#uploadfiles_alert');
                $('#uploadfiles_alert').show();
            },
            fail: function(e, data) {
                $('#uploadfiles_alert').empty();
                $('<p/>').text("Upload Error : " + data.errorThrown).appendTo('#uploadfiles_alert');
                $('#uploadfiles_alert').show();
            },
        })
        .prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    var $jobidSelect = $('#jobidSelect')
        .on('change', function() {
            var url = base_url + 'EditableReport/index/' + $(this).val();
            window.location = url;
        });

    var $reportSelect = $('#reportSelect')
        .on('change', function() {
            var url = base_url + 'EditableReport/index/' + $('#jobidSelect').val() + '/' + $(this).val();
            window.location = url;
        });

    var $reportAreaSelect = $('#reportAreaSelect')
        .on('change', function() {
            var url = base_url + 'EditableReport/index/' + $('#jobidSelect').val() + '/' + $('#reportSelect').val() + '/' + $(this).val();
            window.location = url;
        });

    var $reportTypeSelect = $('#reportTypeSelect')
        .on('change', function() {
            var url = base_url + 'EditableReport/index/' + $(this).val();
            window.location = url;
        });

    $(".delete_areatypeareatopic_photo_btn").click(function(e) {
        var id = $(this).data('id');

        bootbox.confirm('Are you sure you want to delete this photo ?', function(result) {
            if (result) {
                var url = base_url + 'EditableReport/photodelete/' + id;
                $("#main_div").html("<img src='" + base_url + "itglobal/shared/assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");
                $.ajax({
                    url: url,
                    data: null,
                    dataType: 'json',
                    success: function(data) {
                        if (data['result'])
                            location.reload(true);
                        else {
                            showModal(1, 'Error !', data['message']);
                        }
                    }
                });

            };
        });

        event.preventDefault();
        return false;
    });

    /*$(".add_areatypeareatopic_btn").click(function(e) {
        var id = $(this).data('id');
        var url = base_url + 'admin/areatypetopicvalue/add/' + id;
        window.prompt('Copy to clipboard: Ctrl+C, Enter', url);
        event.preventDefault();
        return false;
    });*/

    $(".delete_areatypeareatopic_btn").click(function(e) {

        var id = $(this).data('id');
        bootbox.confirm('Are you sure you want to delete this line?', function(result) {
            if (result) {
                var url = base_url + 'EditableReport/delete/' + id;
                $("#main_div").html("<img src='" + base_url + "itglobal/shared/assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");
                $.ajax({
                    url: url,
                    data: null,
                    dataType: 'json',
                    success: function(data) {
                        if (data['result'])
                            location.reload(true);
                        else {
                            showModal(1, 'Error !', data['message']);
                        }
                    }
                });

            };
        });

        event.preventDefault();
        return false;
    });

    $(".delete_areatopic_btn").click(function(e) {

        var id = $(this).data('id');
        bootbox.confirm('Do you want to delete the Report Area Topic "' + $(this).data('name') + '" ?', function(result) {
            if (result) {
                var url = base_url + 'admin/areatopic/delete/' + id;

                //window.prompt ('Copy to clipboard: Ctrl+C, Enter', url);

                $("#main_div").html("<img src='" + base_url + "assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");
                $.ajax({
                    url: url,
                    data: null,
                    dataType: 'json',
                    success: function(data) {

                        if (data['result']) {
                            location.reload(true);
                        } else {
                            console.log(data);
                            showModal(1, 'Error !', data['message']);
                        }
                    }
                });
            };
        });

        event.preventDefault();
        return false;
    });

    $(".delete_areatype_btn").click(function(e) {

        var id = $(this).data('id');
        bootbox.confirm('Do you want to delete the Report Area Type "' + $(this).data('name') + '" ?', function(result) {
            if (result) {
                var url = base_url + 'admin/areatype/delete/' + id;

                $("#main_div").html("<img src='" + base_url + "assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");
                $.ajax({
                    url: url,
                    data: null,
                    dataType: 'json',
                    success: function(data) {

                        if (data['result']) {
                            location.reload(true);
                        } else {
                            showModal(1, 'Error !', data['message']);
                        }
                    }
                });
            };

        });

        event.preventDefault();
        return false;
    });

    $(".deletereporttype_btn").click(function(e) {
        var reporttypeid = $(this).data('id');
        bootbox.confirm('Do you want to delete the Report Type "' + $(this).data('name') + '" ?', function(result) {
            if (result) {
                var url = base_url + 'admin/reporttype/delete/' + reporttypeid;
                $("#main_div").html("<img src='" + base_url + "assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");

                $.ajax({
                    url: url,
                    data: null,
                    dataType: 'json',
                    success: function(data) {

                        if (data['result']) {
                            location.reload(true);
                        } else {
                            showModal(1, 'Error !', data['message']);
                        }
                    }
                });
            };
        });
        event.preventDefault();
        return false;
    });

    $(".image_action_btn").click(function(e) {
        var url = base_url + 'EditableReport/actionimage/' + $(this).data('id') + '/' + $(this).data('grouptype') + '/' + $(this).data('groupid') + '/' + $(this).data('actiontype');

        $("#main_div").html("<img src='" + base_url + "assets/img/ajax-loader.gif' class='img-responsive center-block ajax-loader'/>");

        $.ajax({
            url: url,
            data: null,
            dataType: 'json',
            success: function(data) {

                if (data['result']) {
                    location.reload(true);
                } else {
                    showModal(1, 'Error !', data['message']);
                }
            }
        });

        event.preventDefault();
        return false;
    });

    $("#redirect_btn").click(function(e) {
        var url = base_url + $(this).data('url');
        window.location = url;
    });


    if (typeof $.fn.validate === "function") {         

       $("#sharereport_form").validate({
           rules: {
               email: {  
                   validaterequired: true,
                   validemail: /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
               },
               notes: {  
                   validaterequired: true
               }
           },
           errorElement: "span",
           errorClass: "help-block error",
           highlight: function (e) {
               if($(e).parent().is('.input-group')) {
                   $(e).parent().parent().parent().removeClass('has-info').addClass('has-error');
               }
               else{
                  $(e).parent().parent().removeClass('has-info').addClass('has-error');
               } 
           },
           success: function (e) {
               if($(e).parent().is('.input-group')) {
                   $(e).parent().parent().parent().removeClass("has-error");
               }
               else{
                   $(e).parent().parent().removeClass("has-error");
               }
           },
           errorPlacement: function (error, element) {
               if(element.parent().is('.input-group')) {
                   error.appendTo(element.parent().parent());
               }
               else {
                   error.appendTo(element.parent());
               }
           },
           unhighlight: function(e, errorClass, validClass) {
               if($(e).parent().is('.input-group')) {
                   $(e).parent().parent().removeClass("has-error");
               }
               else{
                   $(e).parent().removeClass("has-error");
               }
           },
           submitHandler: function() {
               
               var modeljobid = $(this).attr('data-jobid');
               var modelreportid = $(this).attr('data-reportid');
               
               if(modeljobid === '' || modeljobid === '0') {
                   bootbox.alert('JobID required');
                   return false;
               }
               if(modelreportid == '' || modelreportid == '0') {
                   bootbox.alert('Report required');
                   return false;
               }
               
               $("#sharereport_form #modalsave").button('loading'); 
               $("#sharereport_form #cancel").button('loading'); 
               $("#sharereport_form .close").css('display', 'none');
               $.post( base_url+"EditableReport/sharereport", $("#sharereport_form").serialize(), function( data ) {
                   if(data.success) {
                       $('#sharereport_form').trigger("reset");
                       $('#shareReportModal #status').html('<div class="alert alert-success" >'+ data.message +'</div>');
                       setTimeout(function(){ $("#shareReportModal").modal('hide'); }, 500);
                   }
                   else{
                       $('#sharereport_form #status').html('<div class="alert alert-danger" >'+data.message+'</div>');
                   }
                   $("#sharereport_form #modalsave").button('reset'); 
                   $("#sharereport_form #cancel").button('reset'); 
                   $("#sharereport_form .close").css('display', 'block');
               }, 'json');
               return false;

           }
       });
   }

    $(document).on('click', '#share', function() {

        var modeljobid = $(this).attr('data-jobid');
        var modelreportid = $(this).attr('data-reportid');

    if(modeljobid === '' || modeljobid === '0') {
        bootbox.alert('JobID required');
        return false;
    }
    if(modelreportid == '' || modelreportid == '0') {
        bootbox.alert('Report required');
        return false;
               }
        $('#sharereport_form').trigger("reset");
        $("#sharereport_form span.help-block").remove();
        $("#sharereport_form .has-error").removeClass("has-error");
        $('#sharereport_form #status').html('');
        $("#shareReportModal").modal();
    });
    
     $(document).on('click', '#preview', function() {
         
         
       var modeljobid = $(this).attr('data-jobid');
        var modelreportid = $(this).attr('data-reportid');

        if(modeljobid === '' || modeljobid === '0') {
            bootbox.alert('JobID required');
            return false;
        }
        if(modelreportid == '' || modelreportid == '0') {
            bootbox.alert('Report required');
            return false;
        }

        window.open(base_url+'EditableReport/previewreport/'+modeljobid+'/'+modelreportid);
        
        //$("#previewreport_form").submit();
    });
    
    $(document).on('click', '#savereport', function() {
         
         
       var modeljobid = $(this).attr('data-jobid');
        var modelreportid = $(this).attr('data-reportid');

        if(modeljobid === '' || modeljobid === '0') {
            bootbox.alert('JobID required');
            return false;
        }
        if(modelreportid == '' || modelreportid == '0') {
            bootbox.alert('Report required');
            return false;
        }
        
        var postData = {
          jobid: modeljobid,
          reportid: modelreportid
        };
       $.post( base_url+"EditableReport/savereport", postData, function( data ) {
            if(data.success) {
                bootbox.alert("Report saved successfully.");
            }
            else{
               bootbox.alert(data.message);
            }
        }, 'json');
        
        //$("#previewreport_form").submit();
    });
    
    

    $("#sharereport_form #cancel").on('click', function() {
        $('#sharereport_form #status').html('');
        $("#shareReportModal").modal('hide');
    });

});