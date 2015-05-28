<article <?php hybrid_attr( 'post' ); ?>>

	<?php if ( is_singular( get_post_type() ) ) : // If viewing a single post. ?>

		<header class="entry-header">

			<h1 <?php hybrid_attr( 'entry-title' ); ?>><?php single_post_title(); ?></h1>

			<div class="entry-byline">
				<?php hybrid_post_format_link(); ?>
				<i class="fa fa-user"></i>
				<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
				<i class="fa fa-calendar"></i>
				<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
				<i class="fa fa-comment-o"></i>
				<?php comments_popup_link( number_format_i18n( 0 ), number_format_i18n( 1 ), '%', 'comments-link', '' ); ?>
				<?php edit_post_link(); ?>
			</div><!-- .entry-byline -->

		</header><!-- .entry-header -->

		<div <?php hybrid_attr( 'entry-content' ); ?>>
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php hybrid_post_terms( array( 'taxonomy' => 'category', 'text' => __( 'Posted in %s', 'inception' ), 'before' => '<i class="fa fa-folder-open-o"></i>' ) ); ?>
			<?php hybrid_post_terms( array( 'taxonomy' => 'post_tag', 'text' => __( 'Tagged %s', 'inception' ), 'before' => '<br /> <i class="fa fa-tags"></i>' ) ); ?>
		</footer><!-- .entry-footer -->

	<?php else : // If not viewing a single post. ?>

		<?php get_the_image( array( 'size' => 'inception-full' ) ); ?>

		<header class="entry-header">

			<?php the_title( '<h2 ' . hybrid_get_attr( 'entry-title' ) . '><a href="' . get_permalink() . '" rel="bookmark" itemprop="url">', '</a></h2>' ); ?>

			<div class="entry-byline">
				<?php hybrid_post_format_link(); ?>
				<i class="fa fa-user"></i>
				<span <?php hybrid_attr( 'entry-author' ); ?>><?php the_author_posts_link(); ?></span>
				<i class="fa fa-calendar"></i>
				<time <?php hybrid_attr( 'entry-published' ); ?>><?php echo get_the_date(); ?></time>
				<i class="fa fa-comment-o"></i>
				<?php comments_popup_link( number_format_i18n( 0 ), number_format_i18n( 1 ), '%', 'comments-link', '' ); ?>
				<?php edit_post_link(); ?>
			</div><!-- .entry-byline -->

		</header><!-- .entry-header -->

		<div <?php hybrid_attr( 'entry-summary' ); ?>>
			<?php the_excerpt(); ?>
			<?php $count = hybrid_get_gallery_item_count(); ?>
			<p class="gallery-count"><?php printf( _n( 'This gallery contains %s item.', 'This gallery contains %s items.', $count, 'inception' ), $count ); ?></p>
		</div><!-- .entry-summary -->

	<?php endif; // End single post check. ?>

</article><!-- .entry -->