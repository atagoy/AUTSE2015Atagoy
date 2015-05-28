(function($) {
	$(document).ready( function() {
		$( '#cstmfldssrch_div_select_all' ).show( 0, function() {
			var $select_all = $( '#cstmfldssrch_settings_form input#cstmfldssrch_select_all' )
				$checkboxes = $( '#cstmfldssrch_settings_form input[name="cstmfldssrch_fields_array[]"]:enabled' ),
				$checkboxes_total = $checkboxes.size(),
				$checkboxes_selected = $checkboxes.filter(':checked').size();
			if ( $checkboxes_total == $checkboxes_selected ) {
				$select_all.attr( 'checked', true );
			}
		});
		$( '#cstmfldssrch_settings_form input' ).bind( "change click select", function() {
			var	$select_all = $( '#cstmfldssrch_settings_form input#cstmfldssrch_select_all' ),
				$checkboxes = $( '#cstmfldssrch_settings_form input[name="cstmfldssrch_fields_array[]"]:enabled' ),
				checkboxes_size = $checkboxes.size(),
				checkboxes_selected_size = $checkboxes.filter( ':checked' ).size();
			if ( $( this ).attr( 'id' ) == $select_all.attr( 'id' ) ) {
				if ( $select_all.is( ':checked' ) ) {
					$checkboxes.attr( 'checked', true );
				} else {
					$checkboxes.attr( 'checked', false );
				}
			} else {
				if ( checkboxes_size == checkboxes_selected_size ) {
					$select_all.attr( 'checked', true );
				} else {
					$select_all.attr( 'checked', false );
				}				
			}			
			if ( $( this ).attr( 'type' ) != 'submit' ) {
				$( '.updated.fade' ).css( 'display', 'none' );
				$( '#cstmfldssrch_settings_notice' ).css( 'display', 'block' );
			};
		});
	});
})(jQuery);
