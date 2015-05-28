<li <?php hybrid_attr( 'comment' ); ?>>

	<header class="comment-meta">
		<cite <?php hybrid_attr( 'comment-author' ); ?>><?php comment_author_link(); ?></cite><br />
		<i class="fa fa-clock-o"></i>
		<time <?php hybrid_attr( 'comment-published' ); ?>><?php printf( __( '%s ago', 'inception' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
		<a <?php hybrid_attr( 'comment-permalink' ); ?>><?php _e( 'Permalink', 'inception' ); ?></a>
		<?php edit_comment_link(); ?>
	</header><!-- .comment-meta -->

<?php /* No closing </li> is needed.  WordPress will know where to add it. */ ?>