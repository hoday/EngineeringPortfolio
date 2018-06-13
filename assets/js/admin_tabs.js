jQuery(document).ready(function() {

    jQuery('input:radio[name="href_type"]').change(function(ev) {
        console.log('was changed: ' + jQuery(this).attr('id') + ' ' + jQuery(this).prop('checked') + ' ' + jQuery(this).val());
        //jquery(el).prop("checked", true).trigger("click");

        var val = jQuery(this).val();
         if (val === 'file') {
             console.log('file was checked');
             jQuery('#href_type-file').css('display','block');
             jQuery('#href_type-link').css('display','none');
             jQuery('#href_type-link input').val('');

         } else if (val === 'link') {
             console.log('link was checked');
             jQuery('#href_type-file').css('display','none');
             jQuery('#href_type-link').css('display','block');
             jQuery('#href_type-file input').val('');

         }
    });
/*
    jQuery('ul#href_type-tabs a').click(function(event) {
        console.log('clicked');
        var $selected = jQuery('ul#href_type-tabs li');

        // temporarily set all tab links to not be active
        jQuery('ul#href_type-tabs li').each(function(i, el) {
            jQuery(el).addClass('tabs');
            console.log('added tab class to ' + el);
        });
        // select the tab link to be active
        jQuery(this).parent().removeClass('tabs');
        console.log('removed tab class from ' + jQuery(this).parent());


        // temporarily set all tabs panels to not display
        jQuery('ul#href_type-tabs .tabs-panel').each(function(i, el) {
            jQuery(el).css('display','none');
        });

        // select the tab panels to be displayed
        var href = jQuery(this).attr('href');
        var idString = '#' . href;
        jQuery(idString).css('display','block')
    });

    */
});
