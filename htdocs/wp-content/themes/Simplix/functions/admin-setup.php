<?php 
function ihupdate() {
	if ( !empty($_POST) ) {
		if ( isset($_POST['ihscheme_file']) ) {
			$ihscheme_file = $_POST['ihscheme_file'];
			update_option('ihscheme', $ihscheme_file, '','');
		}
		if ( isset($_POST['meta_text']) ) {
			$meta = $_POST['meta_text'];
			update_option('ihmetablurb', $meta, '','');
		}
		if ( isset($_POST['login_scheme']) ) {
			$add = $_POST['login_scheme'];
			update_option('ihlogin_scheme', $add, '','');
		}
		
		update_option( 't_css', $_REQUEST[ 't_css' ] );
		update_option( 't_logo', $_REQUEST[ 't_logo' ] );
		update_option( 't_favico', $_REQUEST[ 't_favico' ] );
		update_option( 't_meta_desc', $_REQUEST[ 't_meta_desc' ] );
		
		update_option( 't_arch_page', $_REQUEST[ 't_arch_page' ] );
		
		update_option( 't_advanced_cat', $_REQUEST[ 't_advanced_cat' ] );		
		update_option( 't_featured_entries', $_REQUEST[ 't_featured_entries' ] );
		update_option( 't_featured_category', $_REQUEST[ 't_featured_category' ] );

		update_option( 't_thumb_auto', $_REQUEST[ 't_thumb_auto' ] );
		update_option( 't_googleid', $_REQUEST[ 't_googleid' ] );
		update_option( 't_analytics', $_REQUEST[ 't_analytics' ] );
		update_option( 't_feedburnerurl', $_REQUEST[ 't_feedburnerurl' ] );
		update_option( 't_feedburnercom', $_REQUEST[ 't_feedburnercom' ] );
	
	}
}

// if we can't find theme installed lets go ahead and install all the options that run template.  This should run only one more time for all our existing users, then they will just be getting the upgrade function if it exists.

if (!get_option('nhinstalled')) {

$check_categories = get_categories('hide_empty=0');
		add_option('nhinstalled', $current, 'This options simply tells me if nh has been installed before', $autoload);
		add_option( 't_css', 'style.css', $autoload);
		add_option( 't_logo', 'logo.gif', $autoload);
	
		add_option( 't_favico', '', $autoload);
		add_option( 't_meta_desc', '', 'Enter a blurb about your site here, and it will show up on the &lt;meta name=&quot;description&quot;&gt; tag. Useful for SEO.', $autoload);
		add_option( 't_arch_page', '', $autoload);
		add_option( 't_advanced_cat', $check_categories[0]->cat_ID, $autoload);
		
		add_option( 't_featured_entries', '3', $autoload);
		add_option( 't_featured_category', $check_categories[0]->cat_ID, $autoload);
		add_option( 't_thumb_auto', 'first', $autoload);
		add_option( 't_googleid', '', $autoload);
		add_option( 't_analytics', '', $autoload);
		add_option( 't_feedburnerurl', '', $autoload);
		add_option( 't_feedburnercom', '', $autoload);

}

// Here we handle upgrading our users with new options and such.  If nhinstalled is in the DB but the version they are running is lower than our current version, trigger this event.

elseif (get_option('nhinstalled') < $current) {
}

?>