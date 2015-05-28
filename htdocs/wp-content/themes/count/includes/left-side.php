<div class="left-side">
    
    <?php include (TEMPLATEPATH . "/includes/retrieve-options.php"); ?>
        
        <?php 	/* Widgetized sidebar, if you have the plugin installed. */
            if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('left-sidebar') ) : 
        ?>
        
        <?php
            if($wp_links_disable == "false") {
        ?>
            <div class="links">
                <h2><?php _e('Ссылки'); ?></h2>	    
                <ul><?php get_links('-1', '<li>', '</li>', '', FALSE, 'id', TRUE); ?></ul>
            </div>
                
        <?php } else {} ?>
        
        <?php
            if($wp_archives_disable == "false") {
        ?>
            <div class="archives">
                <h2><?php _e('Архивы'); ?></h2>
                <ul><?php wp_get_archives('type=monthly'); ?></ul>
            </div>
                
        <?php } else {} ?>  
    
    	<?php
            if($wp_recentcomments_disable == "false") {
        ?>
            <div class="comments">
				<?php 
                    if (function_exists('get_recent_comments')) { ?>
                        <h2><?php _e('Отзывы'); ?></h2>
                        <ul><?php get_recent_comments(); ?></ul>
                <?php } ?>   
            </div>
                
        <?php } else {} ?>  
    
    <?php endif; ?>
    
</div>