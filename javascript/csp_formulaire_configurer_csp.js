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
    })
});

$(document).ready(function() {
    $(".filtre_csp").each(function() {
	$(this).select2({
	    tags: ["'none'", "http://*", "https://*"],
	    tokenSeparators: [",", " "]
	})
    });

});
