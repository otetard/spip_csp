jQuery(function(){
    jQuery(".suite.fieldset").each(function(){
	var me=this;
	jQuery(me).prev(".editer").find("input.checkbox").click(function(){
	    if (jQuery(this).prop("checked"))
		jQuery(me).show('fast');
	    else
		jQuery(me).hide('fast');
	})
    });

    jQuery(".filtre_bloc").each(function(){
	var me=this;
	jQuery(me).prev().find("input.activer_filtre_checkbox").click(function(){
	    if (jQuery(this).prop("checked"))
		jQuery(me).show('fast');
	    else
		jQuery(me).hide('fast');
	})
    });

    jQuery("input[name='filtrer_default']").on('change', function() {
	var me = this;
	jQuery(".filtre_bloc .csp_avertissement").each(function() {
	    if(jQuery(me).is(":checked")) {
		jQuery(this).show('fast');
	    }
	    else
		jQuery(this).hide('fast');
	});
    });
});

$(document).ready(function() {
    $(".filtre_csp").each(function() {
	$(this).select2({
	    tags: [
		'data:',
		'chrome-extension:',
		'about:',
		'javascript:',
		'https://*.twitter.com',
		'https://*.twimg.com',
		'https://*.vine.co',
		'https://*.akamaihd.net',
		'https://*.googleapis.com',
		'https://*.google-analytics.com',
		'https://*.ak.fbcdn.net',
	    ],

	    tokenSeparators: [","]
	})
    });

});

