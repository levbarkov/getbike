function Alerts(mess, type, id) {
    $('.alerts').prepend('<div id="'+id+'" class="alert alert-'+type+'">'+mess+'</div>');
    setTimeout(function () {
        $('.alert#'+id).fadeOut('slow', function () {
            $('.alert#'+id).remove();
        });

    }, 5000);
}
function randomInteger(min, max) {
    var rand = min - 0.5 + Math.random() * (max - min + 1)
    rand = Math.round(rand);
    return rand;
}

$(document).ready(function () {

    $('[data-action=change_lead_status]').change(function () {
        //alert($(this).attr('data-sector'));
        var lead_id = $(this).attr('data-lead');
        var lead_status = $(this).val();
        $.ajax({
            type: "POST",
            url: '/admin/zakaz/changestatus?id=' + lead_id,
            data: 'lead_status=' + lead_status,
            success: function (data) {
                //alert(data);
                data = JSON.parse(data);
                if (data.status == 'success') {
                    Alerts(data.text, 'success', randomInteger());
                } else {
                    Alerts(data.text, 'warning', randomInteger());
                }
            }
        });
    });
    $('[data-action=change_rental]').change(function () {
        //alert($(this).attr('data-sector'));
        var lead_id = $(this).attr('data-lead');
        var rental_id = $(this).val();
        if(rental_id == '') rental_id = 0;
            $.ajax({
                type: "POST",
                url: '/admin/zakaz/changerental?id=' + lead_id,
                data: 'rental_id=' + rental_id,
                success: function (data) {
                    //alert(data);
                    data = JSON.parse(data);
                    if (data.status == 'success') {
                        Alerts(data.text, 'success', randomInteger());
                    } else {
                        Alerts(data.text, 'warning', randomInteger());
                    }
                }
            });
    });
    $('._regions [data-action]').click(function () {
       var act = $(this).attr('data-action');
       var r_id = $(this).attr('data-region-id');
       var r_val = $('#region-'+r_id).val();
        $.ajax({
            type: "POST",
            url: '/admin/country/editregion?id=' + r_id,
            data: 'val=' + r_val+'&act='+act,
            success: function (data) {
                //alert(data);
                data = JSON.parse(data);
                if (data.status == 'success') {
                    Alerts(data.text, 'success', randomInteger());
                    if(act == 'dell'){
                        $('#region_id_'+r_id).fadeOut().remove();
                    }
                } else {
                    Alerts(data.text, 'warning', randomInteger());
                }
            }
        });
    });

})