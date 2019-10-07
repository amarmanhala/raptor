function addClick() {
    $(this).attr("data-recordid").editable('toggle');
    alert('addClick');
};

$(document).ready(function() {
    //$.fn.editable.defaults.mode = 'popup'; 
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.emptytext = null;

    //$('.editable_checklist').editable({ pk: 1 });  

    $("#newentry_btn").click(function(e) {
        alert('newentry_btn');
    });

    /*
        $('.editable_target.spellcheck')
            .on('init', function(e, editable) {
                console.log('initialized ' + editable.options.name);
       	        editable.input.$input.val('overwriting value of input..');
            }); */

    $('.editable_target').editable({
        toggle: 'manual'
    });
    /*    $('.editable_target').editable({toggle: 'manual'}).on('shown', function(e, editable) {
    	  console.log('editable shown3');
    	  console.log(editable.input.$input.val());
    	  //editable.input.$input.val('overwriting value of input..');	
       }); */

    $('.editable_newvalue').editable({
        ajaxOptions: {
            dataType: 'json'
        },
        success: function(response, newValue) {
            if (!response) {
                return "Unknown error!";
            }

            if (response.success === false) {
                return response.msg;
            }
        }
    });

    $('.editable_target_appendable').editable({}).on('shown', function(e, editable) {
        //console.log('newvaluetarget=' + $(this).attr("data-newvaluetarget"));
        var newvaluetarget = $(this).attr("data-newvaluetarget");
        if (editable) {
            $(".editable-buttons").append('<button type="button" class="btn btn-default editable-add btn-sm" onclick="' +
                //'console.log(\'newvaluetarget2=' + newvaluetarget + '\');' + 
                '$(\'' + newvaluetarget + '\').editable(\'toggle\');' +
                '"><span class="glyphicon glyphicon-plus"></span></button>'
            );
        }
    });

    $('.editable_link').click(function(e) {
        e.stopPropagation();
        e.preventDefault();
        $("#target_" + $(this).attr("data-recordid")).editable('toggle');
        $(this).hide();
    });

    $('.editable_target').on('hidden', function(e, reason) {
        $('#link_' + $(this).attr("data-recordid")).show();
    });

    $(".enable_disable_btn, #enable_disable_btn").click(function(e) {
        $(this).html(toggle($(this).attr('data-tablenum'), $(this).attr('data-id'), $(this).attr('data-enabled')));
    });

    $('.editable_ajax_tags').on('init', function(e, editable) {
        editable.options.select2.ajax.url = $(this).attr("data-ajaxurl");
    });

    $('.editable_ajax_tags').editable({
        select2: {
            tags: true,
            placeholder: 'Select Keywords',
            allowClear: true,
            minimumInputLength: 2,
            id: function(item) {
                return item.text;
            },
            ajax: {
                url: null,
                dataType: 'json',
                type: 'POST',
                data: function(term, page) {
                    //console.log('ajax - data term=' + term);
                    return {
                        query: term
                    };
                },
                results: function(data, page) {
                    //console.log('ajax - results ' + data);
                    return {
                        results: data
                    };
                }
            },
            formatResult: function(item) {
                //console.log('formatResult');
                return item.text;
            },
            formatSelection: function(item) {
                //console.log('formatSelection');
                return item.text;
            },
            initSelection: function(element, callback) {
                //console.log('initSelection');
                var data = [];
                $(element.val().split(",")).each(function(i) {
                    var item = this.split(':');
                    //console.log('item:' + item[1]);

                    data.push({
                        id: item[0],
                        text: item[1]
                    });
                });
                $(element).val('');
                callback(data);
            }
        }
    });

});