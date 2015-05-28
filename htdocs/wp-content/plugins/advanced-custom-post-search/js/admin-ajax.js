//ACPS Admin Ajax
jQuery(document).ready(function($){
	
	
	//Find Taxonomies on dropdown select
	$(".acps-post-type").live("change", function(){

		var posttype = $(this).val();
		data = {
			action: "acps_get_results",
			acps_post_type_nonce: acps_vars.acps_nonce,
			acps_post_type: posttype,
			acps_post_id: acps_post_id
		};

     	$.post(ajaxurl, data, function (post_type_response) {
			$(".acps_taxonomies_results").html(post_type_response);
			if($(".acps_taxonomies").hasClass("hidden")){
				$(".acps_taxonomies").removeClass("hidden").fadeIn(300);
			}
		});	
		
		return false;
		
	});
	
	//Find Taxonomies on dropdown select
	$(".acps_form_labels, .acps_taxonomy_value").live("change", function(){
	
	var acps_form_label_fields = {}
	var acps_keyword_option = $('.acps_keyword_input').attr('checked');

	$(".acps_taxonomy_value").each(function(){
		if($(this).attr("checked")=="checked")
		{
			acps_form_label_fields[$(this).next("span").text()] = $(this).val();
		}
	});
	
		if($('.acps_form_labels').attr("checked")=="checked" && $.isEmptyObject(acps_form_label_fields) !== true)
		{
			var posttype = $(this).val();
			data = {
				action: "acps_get_label_fields",
				acps_labels_nonce: acps_vars.acps_nonce,
				acps_form_label_fields: acps_form_label_fields,
				acps_keyword_option: acps_keyword_option,
				acps_post_id: acps_post_id
			};
	
			$.post(ajaxurl, data, function (labels_response) {
			$(".acps_label_results").html(labels_response);
				if($(".acps_form_label_fields").hasClass("hidden")){
				$(".acps_form_label_fields").addClass("active").removeClass("hidden").fadeIn(300);
				if(acps_keyword_option =="checked"){
					if($(".acps_keyword_label_fields").hasClass("hidden")){
						$(".acps_keyword_label_fields").addClass("active").removeClass("hidden").fadeIn(300);
					}
				}
			}
			
			});	
			
			return false;
		}
		else
		{
			$(".acps_form_label_fields").fadeOut(300,function(){ $(this).addClass("hidden").removeClass("active"); $(".acps_label_results").html(""); });
			$(".acps_keyword_label_fields").fadeOut(300,function(){ $(this).addClass("hidden").removeClass("active");
			$(".acps_keyword_label_results > .acps_label_container > input"); });
		}
		
	});
	
	//Find Taxonomies on dropdown select
	$(".acps_keyword_input").live("change", function(){
		
		var acps_form_labels_option = $('.acps_form_labels').attr('checked');
		var acps_keyword_option = false;
		
		if($(this).attr("checked")=="checked" && acps_form_labels_option=="checked")
		{
			acps_keyword_option = 'enabled';

			data = {
					action: "acps_get_keyword_field",
					acps_labels_nonce: acps_vars.acps_nonce,
					acps_labels_option: acps_form_labels_option,
					acps_keyword_option: acps_keyword_option,
					acps_post_id: acps_post_id
				};
			
			$.post(ajaxurl, data, function (keyword_response) {
				$(".acps_keyword_label_results").html(keyword_response);
			});
			
			if($(".acps_keyword_label_fields").hasClass("hidden")){
				$(".acps_keyword_label_fields").addClass("active").removeClass("hidden").fadeIn(300);
			}
		}
		else
		{
			$(".acps_keyword_label_fields").fadeOut(300,function(){ $(this).addClass("hidden").removeClass("active"); $(".acps_keyword_label_results > .acps_label_container > input").val(""); });
		}
		
	});
	
});