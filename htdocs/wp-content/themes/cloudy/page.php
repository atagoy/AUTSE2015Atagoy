<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="main-block">
    <div id="content">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
            <div class="post page" id="post-<?php the_ID(); ?>">
                <div class="title">
                    <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
                </div>
                <div class="entry">
                    <?php the_content('Continue reading &raquo;'); ?>
                </div>
                <div class="link-pages">
                    <?php wp_link_pages() ?>
                </div>
                <?php comments_template(); ?>
            </div>
            <?php endwhile; ?>
            <p><?php next_posts_link('&laquo; Previous Entries') ?> <?php previous_posts_link('Next Entries &raquo;') ?></p>
        <?php else : ?>
            <h2 class="t-center">Not Found</h2>
            <p class="t-center">Sorry, but you are looking for something that isn't here.</p>
        <?php endif; ?>
    </div>
    <?php get_footer(); ?>