<?php 
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

// default search
if ($position != 'search') {
	get_search_form();
}

// ajax search
if ($position == 'search') : ?>
		
<div id="searchbox">
	<form action="<?php echo home_url( '/' ); ?>" method="get" role="search">
		<button class="magnifier" type="submit" value="Search"></button>
		<input type="text" value="" name="s" placeholder="<?php _e('search...', 'warp'); ?>" />
		<button class="reset" type="reset" value="Reset"></button>
	</form>
</div>

<script type="text/javascript" src="<?php echo $this->warp->path->url('js:search.js'); ?>"></script>
<script type="text/javascript">
jQuery(function($) {
	$('#searchbox input[name=s]').search({'url': '<?php echo site_url('wp-admin'); ?>/admin-ajax.php?action=warp_search', 'param': 's', 'msgResultsHeader': '<?php _e("Search Results", "warp"); ?>', 'msgMoreResults': '<?php _e("More Results", "warp"); ?>', 'msgNoResults': '<?php _e("No results found", "warp"); ?>'}).placeholder();
});
</script>
<?php endif; ?>