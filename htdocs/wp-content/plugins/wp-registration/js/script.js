/*
 * NOTE: all actions are prefixed by plugin shortnam_action_name
 */

jQuery(function($){
	

	/* =========== Chosen plugin =============== */
    var chosen_options = {	no_results_text: "No option found",
    						width: "98%"};
    $("#wp-registration-box select").chosen(chosen_options);
    /* =========== Chosen plugin =============== */
    
    
	$("#wpregistration-meta").submit(function(e){
		
		e.preventDefault();
		
		var is_ok = validate_update_data( $(this));
		
		if (is_ok) {

			$("#nm-saving-form-meta").html('<img src="' + nm_wpregistration_vars.doing + '">');			

			var data = $(this).serialize();
			data = data + '&action=nm_wpregistration_create_user';
			
			$.post(nm_wpregistration_vars.ajaxurl, data, function(resp) {

				//console.log(resp); return false;
				
				if(resp.status == 'error'){
					//show all sections if hidden
					$("#nm-saving-form-meta").html(resp.message).css('color', 'red');
				}else{
					if(get_option('_redirect_url') != '')
						window.location = get_option('_redirect_url');
					else
						//console.log(resp);
						$("#nm-saving-form-meta").html(resp.message).css('color', 'green');
				}
				
			}, 'json');
		}else{
			
			$(this).find("#nm-saving-form-meta").html(nm_wpregistration_vars.val_notification).css('color', 'red');
		}
		
	});
});



function validate_update_data(form){
	
	var form_data = jQuery.parseJSON( jQuery(form).attr('data-form') );
	var has_error = true;
	var error_in = '';
	
	jQuery.each( form_data, function( key, meta ) {
		
		var type = meta['type'];
		var error_message	= stripslashes( meta['error_message'] );
		
		if (error_message == '')
			error_message = 'It is required!';
			
		console.log('type'+type+' error message '+error_message+'\n\n');
		  
		if(type === 'text' || type === 'textarea' || type === 'select' || type === 'email' || type === 'date'){
			
			var input_control = jQuery('#'+meta['data_name']);
			
			if(meta['required'] === "on" && jQuery(input_control).val() === ''){
				jQuery(input_control).closest('p').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
				error_in = meta['data_name']
			}else{
				jQuery(input_control).closest('p').find('span.errors').html('').css({'border' : '','padding' : '0'});
			}
		}else if(type === 'checkbox'){
			
			//console.log('im error in cb '+error_message);	
			if(meta['required'] === "on" && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length === 0){
				
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('p').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else if(meta['min_checked'] != '' && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length < meta['min_checked']){
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('p').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else if(meta['max_checked'] != '' && jQuery('input:checkbox[name="'+meta['data_name']+'[]"]:checked').length > meta['max_checked']){
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('p').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
			}else{
				
				jQuery('input:checkbox[name="'+meta['data_name']+'[]"]').closest('p').find('span.errors').html('').css({'border' : '','padding' : '0'});
				
				}
		}else if(type === 'radio'){
				
				if(meta['required'] === "on" && jQuery('input:radio[name="'+meta['data_name']+'"]:checked').length === 0){
					jQuery('input:radio[name="'+meta['data_name']+'"]').closest('p').find('span.errors').html(error_message).css('color', 'red');
					has_error = false;
					error_in = meta['data_name']
				}else{
					jQuery('input:radio[name="'+meta['data_name']+'"]').closest('p').find('span.errors').html('').css({'border' : '','padding' : '0'});
				}
		}else if(type === 'masked'){
			
			var input_control = jQuery('#'+meta['data_name']);
			
			if(meta['required'] === "on" && (jQuery(input_control).val() === '' || jQuery(input_control).attr('data-ismask') === 'no')){
				jQuery(input_control).closest('p').find('span.errors').html(error_message).css('color', 'red');
				has_error = false;
				error_in = meta['data_name'];
			}else{
				jQuery(input_control).closest('p').find('span.errors').html('').css({'border' : '','padding' : '0'});
			}
		}
		
	});
	
	if(get_option('_terms_condition') !== ''){
		
		if(jQuery('input:checkbox[name="nm_terms_accepted"]:checked').length === 0){
			has_error = false;
			jQuery('input:checkbox[name="nm_terms_accepted"]').closest('label').css('color', 'red');
		}else{
			jQuery('input:checkbox[name="nm_terms_accepted"]').closest('label').css('color', '');
		}
	}
	
	//console.log( error_in ); return false;
	return has_error;
}

function get_option(key){
	
	/*
	 * TODO: change plugin shortname
	 */
	var keyprefix = 'nm_wpregistration';
	
	key = keyprefix + key;
	
	var req_option = '';
	
	jQuery.each(nm_wpregistration_vars.settings, function(k, option){
		
		//console.log(k);
		
		if (k == key)
			req_option = option;		
	});
	
	//console.log(req_option);
	return req_option;
	
}

function lost_password(){
	jQuery('#div-reset-password').toggle();	
}

function nm_wp_reset_password(){
	jQuery("#nm-doing-reset").html(
			'<img src="' + nm_wpregistration_vars.doing + '">');
			
	username = jQuery('#txt-reset-password').val();
	var data = {action: 'nm_wpregistration_reset_user_password', user_name: username};
	jQuery.post(nm_wpregistration_vars.ajaxurl, data, function(resp) {
			if(resp.status == 'error')
				jQuery("#nm-doing-reset").html(resp.message).css('color', 'red');
			else
				jQuery("#nm-doing-reset").html(resp.message).css('color', 'green');
			
			//jQuery("#nm-doing-reset").html(resp).css('color', 'green');
		//alert(resp);
		}, 'json');
}


/* ================= some global functions ===================== */

function stripslashes (str) {
	  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // +   improved by: Ates Goral (http://magnetiq.com)
	  // +      fixed by: Mick@el
	  // +   improved by: marrtins
	  // +   bugfixed by: Onno Marsman
	  // +   improved by: rezna
	  // +   input by: Rick Waldron
	  // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
	  // +   input by: Brant Messenger (http://www.brantmessenger.com/)
	  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
	  // *     example 1: stripslashes('Kevin\'s code');
	  // *     returns 1: "Kevin's code"
	  // *     example 2: stripslashes('Kevin\\\'s code');
	  // *     returns 2: "Kevin\'s code"
	  return (str + '').replace(/\\(.?)/g, function (s, n1) {
	    switch (n1) {
	    case '\\':
	      return '\\';
	    case '0':
	      return '\u0000';
	    case '':
	      return '';
	    default:
	      return n1;
	    }
	  });
	}