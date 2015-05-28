	<?php

	/* please do not remove those lines -- required variables */
	global $feedburner_id;
	global $blog_title;
	global $location;
	global $video;
	/* done..! */

	?>
	

	<div id="sidebar">

	<h2>Подписка</h2>
	<div class="box">
	<p>Получайте новые публикации на почту:</p>
	<form action="http://www.feedburner.com/fb/a/emailverify" method="post" onsubmit="window.open('http://www.feedburner.com/fb/a/emailverifySubmit?feedId=<?php echo $feedburner_id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true" class="subscribe">
	<fieldset>
	<input type="text" class="feedinput" value="email ..." name="email" />
	<input type="submit" class="feedsubmit" value="ок" />
	<input type="hidden" value="http://feeds.feedburner.com/~e?ffid=<?php echo $feedburner_id; ?>" name="url"/>
	<input type="hidden" value="<?php echo $blog_title; ?>" name="title"/>
	<input type="hidden" name="loc" value="<?php echo $location; ?>"/>
	</fieldset>
	</form>
	<p>Получайте новости на Ваш RSS фид ридер</p>
	<p><a href="<?php bloginfo('rss2_url'); ?>" class="feedlink">Новости</a> <a href="<?php bloginfo('comments_rss2_url'); ?>" class="feedlink">Комментарии</a></p>
	</div>

	<?php query_posts('showposts=1&cat='.$video); ?>
	<?php while (have_posts()) : the_post(); ?>
	<h2>Видео на блоге</h2>
	<div class="box"><?php the_content(); ?></div>
	<?php endwhile; ?>

	<?php include (TEMPLATEPATH . "/sidebar1.php"); ?>
	<?php include (TEMPLATEPATH . "/sidebar2.php"); ?>
	<div class="clear"></div>

	<h2>Спонсоры</h2>
	<div class="box ad">
	<?php include (TEMPLATEPATH . "/sidebar_ads.php"); ?>
	<br>
	<?php echo base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj5NaXggU2tpbnM8L2E+');?>
	</div>
	
	</div>