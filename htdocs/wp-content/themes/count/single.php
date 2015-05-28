<?php 
	get_header(); 
?>
		
	<div class="contents clear"> 
		<?php 
            require_once 'includes/left-side.php';			
        ?>
        
        <?php
			if (have_posts()) : while (have_posts()) : the_post();
		?>
        
        <div class="middle-content">
        	<h2>Блог</h2>    
        	<div class="single">
                <div class="featured-post">
                    <h2><?php the_title(); ?></h2>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка: <?php the_title_attribute(); ?>">
                        <?php if( get_post_meta( $post->ID, 'largeimg', true ) ) : ?>
                                <img src="<?php bloginfo( 'template_directory' ); ?>/includes/timthumb.php?src=<?php echo get_post_meta( $post->ID, "largeimg", true ); ?>&amp;h=250&amp;w=500&amp;zc=1" alt="<?php the_title(); ?>" />
                        <?php endif; ?>
                    </a>
                    <?php the_content(); ?>
                    <?php endwhile; endif; ?>       
                    <div class="bookmarks">
                        <ul class="clear">
                            <!--<li>
                               <a href="http://del.icio.us/post?url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/delicious-icon.png" alt="" title="" />
                                </a>
                            </li>-->
                            <li>
                                <a href="http://www.facebook.com/share.php?u=<?php the_permalink() ?>&amp;t=<?php the_title(); ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/facebook-icon.png" alt="" title="" />
                                </a>
                            </li>
                            <!--<li>
                                <a href="http://www.mixx.com/submit?page_url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/mixx-icon.png" alt="" title="" />
                                </a>
                            </li>
                            <li>
                                <a href="http://reddit.com/submit?url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/reddit-icon.png" alt="" title="" />
                                </a>
                            </li>-->
                            <li>
                                <a href="<?php bloginfo('rss2_url'); ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/rss-icon.png" alt="" title="" />
                                </a>
                            </li>
                            <!--<li>
                                <a href="http://www.stumbleupon.com/submit?url=<?php the_permalink() ?>&amp;title=<?php the_title(); ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/stumbleupon-icon.png" alt="" title="" />
                                </a>
                            </li>-->
                            <li>
                                <a href="http://technorati.com/faves?add=<?php the_permalink() ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/technorati-icon.png" alt="" title="" />
                                </a>
                            </li>
                            <li>
                                <a href="http://twitter.com/home?status=<?php the_permalink() ?>">
                                    <img src="<?php bloginfo('template_url')?>/img/twitter-icon.png" alt="" title="" />
                                </a>
                            </li>
                        </ul>
                    </div> 
                    
                    <div class="comment-counts">
                        <?php the_time('d M Y') ?> | <?php comments_popup_link('Отзывов нет','1 отзыв', 'Отзывов (%)'); ?>                                   
                    </div>  
                </div>
                <?php comments_template(); ?>             
            </div>
		</div>
        
        <?php 
            get_sidebar();
        ?>    	
    </div>
</div>
    
<?php 
	get_footer(); 
?>
