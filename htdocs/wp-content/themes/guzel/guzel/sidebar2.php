<div class="sidebar">
<ul>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>

<li><h2>Мета</h2>
<ul>
<li><?php wp_register('', ''); ?></li>
<li><?php wp_loginout(); ?></li>
<li><a href="<?php bloginfo('rss2_url'); ?>">Публикации RSS</a></li>
<li><a href="<?php bloginfo('comments_rss2_url'); ?>">Комментарии RSS</a></li>
<li><?php echo base64_decode('PGEgaHJlZj0iaHR0cDovL3d3dy5taXhza2lucy5jb20iIHRpdGxlPSLQotC10LzRiyDQtNC70Y8gV29yZHByZXNzIj5NaXggU2tpbnM8L2E+');?></li>

</ul>
</li>

<?php endif; ?>

</ul>
</div>