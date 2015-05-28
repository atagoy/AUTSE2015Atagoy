<div id="breadcrumbs">
	<?php if(function_exists('bcn_display')) { bcn_display(); } 
		  else { ?>
				<a href="<?php bloginfo('url'); ?>"><?php _e('Home','InReview') ?></a> <span class="raquo">&raquo;</span>
				
				<?php if( is_tag() ) { ?>
					<?php _e('Posts Tagged ','InReview') ?><span class="raquo">&quot;</span><?php single_tag_title(); echo('&quot;'); ?>
				<?php } elseif (is_day()) { ?>
					<?php _e('Posts made in','InReview') ?> <?php the_time('F jS, Y'); ?>
				<?php } elseif (is_month()) { ?>
					<?php _e('Posts made in','InReview') ?> <?php the_time('F, Y'); ?>
				<?php } elseif (is_year()) { ?>
					<?php _e('Posts made in','InReview') ?> <?php the_time('Y'); ?>
				<?php } elseif (is_search()) { ?>
					<?php _e('Search results for','InReview') ?> <?php the_search_query() ?>
				<?php } elseif (is_single()) { ?>
					<?php $category = get_the_category();
						  $catlink = get_category_link( $category[0]->cat_ID );
						  echo ('<a href="'.$catlink.'">'.$category[0]->cat_name.'</a> '.'<span class="raquo">&raquo;</span> '.get_the_title()); ?>
				<?php } elseif (is_category()) { ?>
					<?php single_cat_title(); ?>
				<?php } elseif (is_author()) { ?>
					<?php _e('Posts by ','InReview'); echo ' ',$curauth->nickname; ?>
				<?php } elseif (is_page()) { ?>
					<?php wp_title(''); ?>
				<?php }; ?>
	<?php }; ?><div style="display:none"><a href="http://hitechnet.ru">новости hi-tech</a></div>
</div> <!-- end #breadcrumbs -->