<?php
/**
* @package   Warp Theme Framework
* @file      recent-comments.php
* @version   6.0.0
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright 2007 - 2011 YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

global $comments, $comment;

// init vars
$number   = (int) max(isset($module->params['number']) ? $module->params['number'] : 5, 1);
$comments = get_comments(array('number' => $number, 'status' => 'approve'));

if ($comments) : ?>
<section class="line comments">

	<?php foreach ((array) $comments as $comment) : ?>
	<article>
		
		<?php echo get_avatar($comment, $size = '35', get_bloginfo('template_url').'/images/comments_avatar.png'); ?>
		
		<h4 class="author"><?php echo get_comment_author_link(); ?></h4>

		<p class="meta">
			<time datetime="<?php echo get_comment_date('Y-m-d'); ?>" pubdate><?php comment_date(); ?></time>
			| <a class="permalink" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">#</a>
		</p>
		
		<div class="content"><?php comment_text(); ?></div>
	
	</article>
	<?php endforeach; ?>
	
</section>
<?php endif; ?>