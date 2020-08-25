jQuery(document).ready(function(jQuery) {
	'use strict';
	jQuery.noConflict();
	
	jQuery('.beAds-meta div').hide();
    jQuery('#videoAdv').on('change', function() {
        var sv = jQuery(this).val();
        switch(sv){
            case 'image':
                jQuery('#image').show();
                jQuery('#video').hide();
                jQuery('#html').hide();
                break;
            case 'video':
                jQuery('#video').show();
                jQuery('#image').hide();
                jQuery('#html').hide();
                break;
            case 'html':
                jQuery('#html').show();
                jQuery('#image').hide();
                jQuery('#video').hide();
                break;
        }
    });	
	
});