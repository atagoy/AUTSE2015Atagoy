<div class="span-7 last">
	
	<div class="sidebar">
    
        
       
        
        <div id="topsearch" > 
    			<?php get_search_form(); ?> 
    	</div>
        
    
		
        <?php
		if(get_theme_option('rssbox') == 'true') {
			?>
    			<div class="rssbox">
    				<a href="<?php bloginfo('rss2_url'); ?>"><img src="<?php bloginfo('template_url'); ?>/images/rss.png"  alt="RSS Feed" title="RSS Feed" style="vertical-align:middle; margin-right: 5px;"  /></a><a href="<?php bloginfo('rss2_url'); ?>"><?php echo get_theme_option('rssboxtext'); ?></a>
    			</div>
    			<?php
    		}
    	?>
    	
    	
		
		<ul>
			<?php 
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

				
				<li><h2><?php _e('Недавние записи'); ?></h2>
			               <ul>
					<?php wp_get_archives('type=postbypost&limit=5'); ?>  
			               </ul>
				</li>
				
				<li><h2>Архивы</h2>
					<ul>
					<?php wp_get_archives('type=monthly'); ?>
					</ul>
				</li>
				
				<li> 
					<h2>Календарь</h2>
					<?php get_calendar(); ?> 
				</li>
				
				<?php wp_list_categories('hide_empty=0&show_count=1&title_li=<h2>Рубрики</h2>'); ?>
				
				<li id="tag_cloud"><h2>Метки</h2>
					<?php wp_tag_cloud('largest=16&format=flat&number=20'); ?>
				</li>
				
				
				<?php include (TEMPLATEPATH . '/recent-comments.php'); ?>
				<?php if (function_exists('get_recent_comments')) { get_recent_comments(); } ?>
				
				
			
					
			<?php endif; ?>
			Благодарность: <a title="бизне на сайте все о финансах" href="http://nizhyn.info/category/biznes">бизнес</a> сайту.  Сайт поддержка: <a title="Кондиционирование и вентиляция" href="http://prozastihi.info/kondicionirovanie-i-ventilyaciya/">строительный успех</a>
		</ul>
		
	</div>
</div>
