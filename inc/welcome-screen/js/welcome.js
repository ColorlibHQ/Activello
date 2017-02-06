jQuery(document).ready(function () {

    /* If there are required actions, add an icon with the number of required actions in the About activello page -> Actions required tab */
    var activello_nr_actions_required = activelloWelcomeScreenObject.nr_actions_required;

    if ((typeof activello_nr_actions_required !== 'undefined') && (activello_nr_actions_required != '0')) {
        jQuery('li.activello-w-red-tab a').append('<span class="activello-actions-count">' + activello_nr_actions_required + '</span>');
    }

    /* Dismiss required actions */
    jQuery(".activello-dismiss-required-action").click(function () {

        var id = jQuery(this).attr('id');
        jQuery.ajax({
            type: "GET",
            data: {action: 'activello_dismiss_required_action', dismiss_id: id},
            dataType: "html",
            url: activelloWelcomeScreenObject.ajaxurl,
            beforeSend: function (data, settings) {
                jQuery('.activello-tab-pane#actions_required h1').append('<div id="temp_load" style="text-align:center"><img src="' + activelloWelcomeScreenObject.template_directory + '/inc/admin/welcome-screen/img/ajax-loader.gif" /></div>');
            },
            success: function (data) {
                location.reload();
                jQuery("#temp_load").remove();
                /* Remove loading gif */
                jQuery('#' + data).parent().slideToggle().remove();
                /* Remove required action box */
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
    });
});
