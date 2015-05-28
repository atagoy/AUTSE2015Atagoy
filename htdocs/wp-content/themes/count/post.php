<div class="blogpost" id="post-<?php the_ID(); ?>">                            
    <h2>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
            <?php the_title(); ?>
        </a>
    </h2>
    <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
        <?php if( get_post_meta( $post->ID, 'largeimg', true ) ) : ?>
                <img src="<?php bloginfo( 'template_directory' ); ?>/includes/timthumb.php?src=<?php echo get_post_meta( $post->ID, "largeimg", true ); ?>&amp;h=250&amp;w=500&amp;zc=1" alt="<?php the_title(); ?>" />
        <?php endif; ?>
    </a>
    <?php content('55'); ?> 
    <div class="comment-counts">
        <?php the_time('d M Y') ?> | <?php comments_popup_link('Ваш отзыв','1 отзыв', 'Отзывов (%)'); ?>                                   
    </div>                            
</div>    