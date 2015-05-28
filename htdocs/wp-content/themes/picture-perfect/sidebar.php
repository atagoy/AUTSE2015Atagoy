<div id="sidebar">
	
		<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar('sidebar1') ) : ?>


<?php wp_list_pages('title_li=' . __('Pages:')); ?>
	<?php wp_list_bookmarks('title_after=&title_before='); ?>
	<?php wp_list_categories('title_li=' . __('Categories:')); ?>




	
	<?php endif; ?>



	
		
</div>