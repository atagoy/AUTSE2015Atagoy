<?php get_header(); ?>
<div class="span-24" id="contentwrap">
	<div class="span-16">
		<div id="content">	

		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Архивы рубрики &#8216;<?php single_cat_title(); ?>&#8217;</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Записи с меткой &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Архивы за день <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Архивы за месяц <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Архивы за год <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Архивы автора</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Архивы блога</h2>
 	  <?php } ?>

		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?>>
                <?php if ( function_exists("has_post_thumbnail") && has_post_thumbnail() ) { the_post_thumbnail(array(260,200), array("class" => "alignleft post_thumbnail")); } ?>
				<h2 class="title" id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Постоянная ссылка на <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<div class="postdate"><img src="<?php bloginfo('template_url'); ?>/images/date.png" /> <?php the_time('F jS, Y') ?> <img src="<?php bloginfo('template_url'); ?>/images/user.png" /> <?php the_author() ?> <?php if (current_user_can('edit_post', $post->ID)) { ?> <img src="<?php bloginfo('template_url'); ?>/images/edit.png" /> <?php edit_post_link('Редактировать', '', ''); } ?></div>

				<div class="entry">
					<?php the_excerpt() ?>
				</div>

				<div class="postmeta"><img src="<?php bloginfo('template_url'); ?>/images/folder.png" /> Опубликовано в рубрике <?php the_category(', ') ?> <?php if(get_the_tags()) { ?> <img src="<?php bloginfo('template_url'); ?>/images/tag.png" /> <?php  the_tags('Метки: ', ', '); } ?>  <img src="<?php bloginfo('template_url'); ?>/images/comments.png" /> <?php comments_popup_link('Комментариев нет &#187;', '1 комментарий &#187;', '% комментариев &#187;'); ?></div>

			</div>

		<?php endwhile; ?>
		
		<div class="navigation">
			<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
			<div class="alignleft"><?php next_posts_link('&laquo; Предыдущие записи') ?></div>
			<div class="alignright"><?php previous_posts_link('Следующие записи &raquo;') ?></div>
			<?php } ?>
		</div>
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='pagetitle'>Извините, но в рубрике %s еще нет записей.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2 class='pagetitle'>Извините, но записей с этой датой не существует.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='pagetitle'>Извините, но пользователь %s еще не опубликовал ни одной записи.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='pagetitle'>Записей не найдено.</h2>");
		}

	endif;
?>

		</div>
		</div>


<?php get_sidebars(); ?>
	</div>
<?php get_footer(); ?>
