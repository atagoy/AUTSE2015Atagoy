<div id="featured">

	<div id="featuredleft">
	
	<ul id="featuredposts" class="featuredposts">
	<li><a href="#" rel="cat1" class="selected"><?php query_posts('cat='.$cat1); ?><?php single_cat_title(); ?></a></li>
	<li><a href="#" rel="cat2"><?php query_posts('cat='.$cat2); ?><?php single_cat_title(); ?></a></li>
	<li><a href="#" rel="cat3"><?php query_posts('cat='.$cat3); ?><?php single_cat_title(); ?></a></li>
	<li><a href="#" rel="cat4"><?php query_posts('cat='.$cat4); ?><?php single_cat_title(); ?></a></li>
	<li><a href="#" rel="cat5"><?php query_posts('cat='.$cat5); ?><?php single_cat_title(); ?></a></li>
	<li><a href="#" rel="cat6"><?php query_posts('cat='.$cat6); ?><?php single_cat_title(); ?></a></li>
	</ul>
	<div class="clear"></div>

	<!-- featured post -->
	<div id="cat1" class="featuredposts_content">
	<?php query_posts('showposts=1&cat='.$cat1); ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="post">

		<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
		<div class="entry"><p><?php the_excerpt_reloaded($featuredexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>

		<div class="endpost">
		<div class="cat"><a href="<?php echo get_category_link($cat1); ?>">Узнать больше: <?php single_cat_title(); ?></a></div>
		<ul class="extra">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li class="comments"><a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
		</div><div class="clear"></div>
		
	</div><div class="clear">
	</div>
	<?php endwhile; ?>
	</div>
	<!-- end -->

	<!-- featured post -->
	<div id="cat2" class="featuredposts_content">
	<?php query_posts('showposts=1&cat='.$cat2); ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="post">

		<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
		<div class="entry"><p><?php the_excerpt_reloaded($featuredexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>

		<div class="endpost">
		<div class="cat"><a href="<?php echo get_category_link($cat2); ?>">Узнать больше: <?php single_cat_title(); ?></a></div>
		<ul class="extra">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li class="comments"><a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
		</div><div class="clear"></div>
		
	</div><div class="clear">
	</div>
	<?php endwhile; ?>
	</div>
	<!-- end -->

	<!-- featured post -->
	<div id="cat3" class="featuredposts_content">
	<?php query_posts('showposts=1&cat='.$cat3); ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="post">

		<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
		<div class="entry"><p><?php the_excerpt_reloaded($featuredexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>

		<div class="endpost">
		<div class="cat"><a href="<?php echo get_category_link($cat3); ?>">Узнать больше: <?php single_cat_title(); ?></a></div>
		<ul class="extra">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li class="comments"><a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
		</div><div class="clear"></div>
		
	</div><div class="clear">
	</div>
	<?php endwhile; ?>
	</div>
	<!-- end -->

	<!-- featured post -->
	<div id="cat4" class="featuredposts_content">
	<?php query_posts('showposts=1&cat='.$cat4); ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="post">

		<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
		<div class="entry"><p><?php the_excerpt_reloaded($featuredexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>

		<div class="endpost">
		<div class="cat"><a href="<?php echo get_category_link($cat4); ?>">Узнать больше:  <?php single_cat_title(); ?></a></div>
		<ul class="extra">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li class="comments"><a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
		</div><div class="clear"></div>
		
	</div><div class="clear">
	</div>
	<?php endwhile; ?>
	</div>
	<!-- end -->

	<!-- featured post -->
	<div id="cat5" class="featuredposts_content">
	<?php query_posts('showposts=1&cat='.$cat5); ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="post">

		<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<div class="time"><?php the_time('l,  j F Y G:i'); ?></div>
		<div class="entry"><p><?php the_excerpt_reloaded($featuredexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>

		<div class="endpost">
		<div class="cat"><a href="<?php echo get_category_link($cat5); ?>">Узнать больше: <?php single_cat_title(); ?></a></div>
		<ul class="extra">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li class="comments"><a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
		</div><div class="clear"></div>
		
	</div><div class="clear">
	</div>
	<?php endwhile; ?>
	</div>
	<!-- end -->

	<!-- featured post -->
	<div id="cat6" class="featuredposts_content">
	<?php query_posts('showposts=1&cat='.$cat6); ?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="thumb"><?php echo get_the_image(array('Thumbnail','My Thumbnail'),'thumbnail'); ?></div>
	<div class="post">

		<h1><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
		<div class="time"><?php the_time('l, j F Y G:i'); ?></div>
		<div class="entry"><p><?php the_excerpt_reloaded($featuredexcerpt, '<a>', FALSE, FALSE, 2); ?></p></div>

		<div class="endpost">
		<div class="cat"><a href="<?php echo get_category_link($cat6); ?>">Узнать больше: <?php single_cat_title(); ?></a></div>
		<ul class="extra">
		<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">Читать полностью</a></li>
		<li class="comments"><a href="<?php the_permalink() ?>#commenting" title="Прокомментировать"><?php comments_number('0 коммент.','1 коммент.','% коммент.'); ?></a></li>
		</ul>
		</div><div class="clear"></div>
		
	</div><div class="clear">
	</div>
	<?php endwhile; ?>
	</div>
	<!-- end -->

	<script type="text/javascript">
	var featuredposts=new ddtabcontent("featuredposts")
	featuredposts.setpersist(true)
	featuredposts.setselectedClassTarget("link")
	featuredposts.init()
	</script>
	
	</div>
	<div id="featuredright">
	
	<ul id="featuredtabs" class="featuredtabs">
	<li><a href="#" rel="popular" class="selected">Популярное</a></li>
	<li><a href="#" rel="recent">Свежее</a></li>
	<li><a href="#" rel="comments">Коммент.</a></li>
	<li><a href="#" rel="tags">Метки</a></li>
	</ul>
	<div class="clear"></div>

	<div id="popular" class="featuredtabs_content">
	<ul>
	<?php
	$now = gmdate("Y-m-d H:i:s",time());
	$lastmonth = gmdate("Y-m-d H:i",gmmktime(date("H"), date("i"), date("s"), date("m")-1,date("d"),date("Y")));
	$popularposts = "SELECT ID, post_title, post_date, comment_count, COUNT($wpdb->comments.comment_post_ID) AS 'popular' FROM $wpdb->posts, $wpdb->comments WHERE comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' AND comment_status = 'open' GROUP BY $wpdb->comments.comment_post_ID ORDER BY popular DESC LIMIT 5";
	$posts = $wpdb->get_results($popularposts);
	$popular = '';
	if($posts){
	foreach($posts as $post){
	$post_title = stripslashes($post->post_title);
	$post_date = stripslashes($post->post_date);
	$comments = stripslashes($post->comment_count);
	$guid = get_permalink($post->ID);
	$popular .= '<li><a href="'.$guid.'" title="'.$post_title.'">'.$post_title.'</a>
	<span> <a href="'.$guid.'#commenting" title="коммент. на '.$post_title.'">'.$comments.' коммент.</a>  '.$post_date.'</span></li>';
	}
	}echo $popular;
	?>
	</ul>
	</div>

	<div id="recent" class="featuredtabs_content">
	<ul>
	<?php query_posts('showposts='.$ajax_posts.'&orderby=date'); ?>
	<?php while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
	<span> <?php comments_popup_link('0 коммент.', '1 коммент.', '% коммент.'); ?>  <?php the_time(' jS F Y G:i'); ?></span></li>
	<?php endwhile; ?>
	</ul>
	</div>

	<div id="comments" class="featuredtabs_content">
	<?php include (TEMPLATEPATH . '/simple_recent_comments.php'); ?>
	<?php if (function_exists('src_simple_recent_comments')) { src_simple_recent_comments($ajax_comments, $ajax_comment_excerpt, '', ''); } ?>
	</div>

	<div id="tags" class="featuredtabs_content">
	<?php wp_tag_cloud('smallest=8&largest=14&number='.$ajax_tags); ?>
	</div>

	<script type="text/javascript">
	var featuredtabs=new ddtabcontent("featuredtabs")
	featuredtabs.setpersist(false)
	featuredtabs.setselectedClassTarget("link")
	featuredtabs.init()
	</script>
	
	</div>
	<div class="clear"></div>

</div>