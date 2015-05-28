(function($) {
	$(document).ready( function() {
		$( '#cstmsrch_div_select_all' ).show();
		$( '#cstmsrch_settings_form input' ).bind( "change click select", function() {
			var	$select_all = $( '#cstmsrch_settings_form input#cstmsrch_select_all' ),
				$checkboxes = $( '#cstmsrch_settings_form input[name="cstmsrch_options[]"]' ),
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
				$( '#cstmsrch_settings_notice' ).css( 'display', 'block' );
			};
		});
	});
})(jQuery);
