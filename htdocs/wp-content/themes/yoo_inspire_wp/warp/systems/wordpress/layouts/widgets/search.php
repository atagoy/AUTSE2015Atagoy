<?php 
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// default search
if ($module->position != 'search') {
	get_search_form();
}

// ajax search
if ($module->position == 'search') : ?>
		
<form id="searchbox" action="<?php echo home_url( '/' ); ?>" method="get" role="search">
	<input type="text" value="" name="s" placeholder="<?php _e('search...', 'warp'); ?>" />
	<button type="reset" value="Reset"></button>
</form>

<script src="<?php echo $this['path']->url('js:search.js'); ?>"></script>
<script>
jQuery(function($) {
	$('#searchbox input[name=s]').search({'url': '<?php echo site_url('wp-admin'); ?>/admin-ajax.php?action=warp_search', 'param': 's', 'msgResultsHeader': '<?php _e("Search Results", "warp"); ?>', 'msgMoreResults': '<?php _e("More Results", "warp"); ?>', 'msgNoResults': '<?php _e("No results found", "warp"); ?>'}).placeholder();
});
</script>
<?php endif; ?>