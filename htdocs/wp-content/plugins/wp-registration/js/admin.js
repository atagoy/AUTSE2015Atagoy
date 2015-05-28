jQuery(function($){

	$('#nm_wpregistration-tabs').tabs();

	//submitting form foreach setting/tabs
	$(".nm-admin-form").submit(function(event){
		event.preventDefault();
		
		$(".nm-saving-settings").html('<img src="'+nm_wpregistration_vars.doing+'" />');
		var form_data = $(this).serialize();
		//console.log(form_data);
		$.post(ajaxurl, form_data, function(resp){
			
			//console.log(resp);
			$(".nm-saving-settings").html(resp);
			window.location.reload(true);
		});
	});
	
	
	 /* =========== wpColorPicker =============== */
    $('.wp-color-field').wpColorPicker();
    /* =========== wpColorPicker =============== */
    
    
    /* =========== Chosen plugin =============== */
    var chosen_options = {	no_results_text: "Sorry, try other option!",
    						width: "100%"};
    $(".the_chosen").chosen(chosen_options);
    /* =========== Chosen plugin =============== */
    
    
    

	var meta_removed;
	
	//attaching hide and delete events for existing meta data
	$("#file-meta-input-holder li").each(function(i, item){
		$(item).find(".ui-icon-carat-2-n-s").click(function(e) {
			$(item).find("table").slideToggle(300);
		});
		// for delete box
		$(item).find(".ui-icon-trash").click(function(e) {
			$("#remove-meta-confirm").dialog("open");
			meta_removed = $(item);
		});	
	});
	
	$('.ui-icon-circle-triangle-n').click(function(e){
		$("#file-meta-input-holder li").find('table').slideUp();
	});
	$('.ui-icon-circle-triangle-s').click(function(e){
		$("#file-meta-input-holder li").find('table').slideDown();
	});
	
	
	
	$("#file-meta-input-holder").sortable({
		revert : true,
		stop : function(event, ui) {
			// console.log(ui);

			// only attach click event when dropped from right panel
			if (ui.originalPosition.left > 20) {
				$(ui.item).find(".ui-icon-carat-2-n-s").click(function(e) {
					$(this).parent('.postbox').find("table").slideToggle(300);
				});

				// for delete box
				$(ui.item).find(".ui-icon-trash").click(function(e) {
					$("#remove-meta-confirm").dialog("open");
					meta_removed = $(ui.item);
				});
			}
		}
	});

	// =========== remove dialog ===========
	$("#remove-meta-confirm").dialog({
		resizable : false,
		height : 160,
		autoOpen : false,
		modal : true,
		buttons : {
			"Remove" : function() {
				$(this).dialog("close");
				meta_removed.remove();
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		}
	});

	$("#nm-input-types li").draggable(
			{
				connectToSortable : "#file-meta-input-holder",
				helper : "clone",
				revert : "invalid",
				stop : function(event, ui) {
					

					$('.ui-sortable .ui-draggable').removeClass(
							'input-type-item').find('div').addClass('postbox');

					// now replacing the icons with arrow
					$('.postbox').find('.ui-icon-arrow-4').removeClass(
							'ui-icon-arrow-4').addClass('ui-icon-carat-2-n-s');
					$('.postbox').find('.ui-icon-placehorder').removeClass(
							'ui-icon-placehorder').addClass(
							'ui-icon ui-icon-trash');

				}
			});

	// ================== new meta form creator ===================
});


/* ================ below script is for admin settings framework ============== */

// saving form meta
function save_file_meta() {

 
	jQuery("#nm-saving-form").html('<img src="'+nm_wpregistration_vars.doing+'" />');
	
	var form_meta_values = new Array(); // {}; //Array();
	jQuery("#file-meta-input-holder li")
			.each(
					function(i, item) {

						var inner_array = {};
						inner_array['type'] = jQuery(item).attr(
								'data-inputtype');

						jQuery(this)
								.find('td.table-column-input')
								.each(
										function(i, col) {

											var meta_input_type = jQuery(col)
													.attr('data-type');
											var meta_input_name = jQuery(col)
													.attr('data-name');
											var cb_value = '';
											if (meta_input_type == 'checkbox') {
												cb_value = (jQuery(this).find('input:checkbox[name="' + meta_input_name + '"]:checked').val() === undefined ? '' : jQuery(this).find('input:checkbox[name="' + meta_input_name + '"]:checked').val());
									inner_array[meta_input_name] = cb_value;
											} else if (meta_input_type == 'textarea') {
												inner_array[meta_input_name] = jQuery(
														this)
														.find(
																'textarea[name="'
																		+ meta_input_name
																		+ '"]')
														.val();
											} else if (meta_input_type == 'select') {
												inner_array[meta_input_name] = jQuery(
														this)
														.find(
																'select[name="'
																		+ meta_input_name
																		+ '"]')
														.val();
											} else if (meta_input_type == 'html-conditions') {
												
												var all_conditions = {};
												var the_conditions = new Array();	//{};
												
												all_conditions['visibility'] = jQuery(
														this)
														.find(
																'select[name="condition_visibility"]')
														.val();
												all_conditions['bound'] = jQuery(
														this)
														.find(
																'select[name="condition_bound"]')
														.val();
												jQuery(this).find('div').each(function(i, div_box){
												
													var the_rule = {};
													
													the_rule['elements'] = jQuery(
															this)
															.find(
																	'select[name="condition_elements"]')
															.val();
													the_rule['operators'] = jQuery(
															this)
															.find(
																	'select[name="condition_operators"]')
															.val();
													the_rule['element_values'] = jQuery(
															this)
															.find(
																	'select[name="condition_element_values"]')
															.val();
													
													the_conditions.push(the_rule);
												});
												
												all_conditions['rules'] = the_conditions;
												inner_array[meta_input_name] = all_conditions;
											}else if (meta_input_type == 'pre-images') {
												var all_preuploads = new Array();
												jQuery(this).find('div.pre-upload-box table').each(function(i, preupload_box){
													var pre_upload_obj = {	link: jQuery(preupload_box).find('input[name="pre-upload-link"]').val(),
															title: jQuery(preupload_box).find('input[name="pre-upload-title"]').val(),
															price: jQuery(preupload_box).find('input[name="pre-upload-price"]').val(),};
													
													all_preuploads.push(pre_upload_obj);
												});
												
												inner_array['images'] = all_preuploads;
												
											} else {
												inner_array[meta_input_name] = jQuery.trim(jQuery(this).find('input[name="'+ meta_input_name+ '"]').val())
												// inner_array.push(temp);
											}

										});

						form_meta_values.push(inner_array);

					});

	//console.log(form_meta_values); return false;
	// ok data is collected, so send it to server now Huh?


	do_action = 'nm_wpregistration_save_file_meta';

	var server_data = {
		action 					: do_action,
		file_meta : form_meta_values
	}
	
	jQuery.post(ajaxurl, server_data, function(resp) {

		console.log(resp);
		if (resp.status == 'success') {

			jQuery("#nm-saving-form").html(resp.message);
			window.location.reload(true);
		}

	}, 'json');
	
}