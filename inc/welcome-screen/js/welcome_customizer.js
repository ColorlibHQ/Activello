jQuery(document).ready(function () {
    var activello_aboutpage = activelloWelcomeScreenCustomizerObject.aboutpage;
    var activello_nr_actions_required = activelloWelcomeScreenCustomizerObject.nr_actions_required;

    /* Number of required actions */
    if ((typeof activello_aboutpage !== 'undefined') && (typeof activello_nr_actions_required !== 'undefined') && (activello_nr_actions_required != '0')) {
        jQuery('#accordion-section-themes .accordion-section-title').append('<a href="' + activello_aboutpage + '"><span class="activello-actions-count">' + activello_nr_actions_required + '</span></a>');
    }


});
