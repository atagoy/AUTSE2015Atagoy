<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

global $comments, $comment;

if (! $number = (int) $params['number']) {
	$number = 5;
}

if ($number < 1) $number = 1;


$comments = get_comments(array('number' => $number, 'status' => 'approve'));

if ($comments) : ?>
<ul class="line comments">
	<?php foreach ((array) $comments as $comment) : ?>
	<li>
		
		<?php echo get_avatar($comment, $size='35', get_bloginfo('template_url').'/warp/images/comments_avatar.png'); ?>
		
		<h3 class="author"><?php echo get_comment_author_link(); ?></h3>
					
		<p class="meta">
			<?php comment_date(); ?>
			| <a class="permalink" href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">#</a>
		</p>
		
		<div class="content"><?php comment_text(); ?></div>
	
	</li>
	<?php endforeach; ?>
	
</ul>
<?php endif; ?>