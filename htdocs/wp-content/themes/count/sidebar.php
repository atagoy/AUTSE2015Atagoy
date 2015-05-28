<div class="right-side">
	<?php include (TEMPLATEPATH . "/includes/retrieve-options.php"); ?>
	
    <?php 	/* Widgetized sidebar, if you have the plugin installed. */
		if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('right-sidebar') ) : 
	?>
	
	<?php
		if($wp_sponsor_disable == "false") {
	?>
		<div class="sponsors">
            <h2><?php _e('Спонсоры'); ?></h2>	
            <ul>
            	<li><a href="http://graphicriver.net/?ref=mabuc"><img src="<?php bloginfo('template_url')?>/sponsors/gr.png" alt="" title="" /></a></li>    
				<li><a href="http://themeforest.net/?ref=mabuc"><img src="<?php bloginfo('template_url')?>/sponsors/tf.png" alt="" title="" /></a></li>    
            </ul>    
        </div>
			
	<?php } else {} ?>
    
    <?php
		if($wp_category_disable == "false") {
	?>
		<div class="categories">
            <h2><?php _e('Рубрики'); ?></h2>	
            <ul>
                <?php wp_list_categories('sort_column=name&sort_order=asc&style=list&children=false&hierarchical=true&title_li=0&show_count=1'); ?>            	
            </ul>    
        </div>
			
	<?php } else {} ?>
    
		<div class="categories">            
        	<form id="searchform" method="get" action="<?php bloginfo('home'); ?>">
    			<p>
        			<input type="text" id="s" name="s" value="Для поиска нажмите Enter" onblur="if(this.value==''){this.value='Для поиска нажмите Enter';}" onfocus="if(this.value=='Для поиска нажмите Enter'){this.value='';}" />
    			</p>
			</form>        
        </div>
	
    <?php
		if($wp_popular_disable == "false") {
	?>
		<div class="posts">
            <h2><?php _e('Популярное'); ?></h2>
            <ul><?php get_popular_posts(); ?></ul>
        </div>

			
	<?php } else {} ?>
    
    <?php
		if($wp_recent_disable == "false") {
	?>
		<div class="posts">
            <h2><?php _e('Новое на сайте'); ?></h2>
            <ul>
                <?php wp_get_archives('title_li=&type=postbypost&limit=10'); ?>
            </ul>
        </div>
			
	<?php } else {} ?>
    
    <?php endif; ?>
    
</div>