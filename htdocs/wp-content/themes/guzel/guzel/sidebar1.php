<div class="sidebar" style="margin-right:16px">
<ul>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(1) ) : else : ?>

<li><h2>Ссылки</h2>
<ul>
<?php wp_list_bookmarks('title_li=&categorize=0'); ?>
</ul>
</li>

<li><h2>Архивы</h2>
<ul>
<?php wp_get_archives('type=monthly&limit=12'); ?>
</ul>
</li>

<?php endif; ?>

</ul>
</div>