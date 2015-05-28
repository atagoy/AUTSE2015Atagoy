<div id="left-block">
    <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) : ?>
                <div class="categories box">
                    <?php wp_list_categories(array('title_li'=>'<h3>Category:</h3>')); ?>
                </div>
        <div class="box">
        	<h3>Archives:</h3>
        	<ul class="archive">
        	    <?php wp_get_archives('type=monthly'); ?>
        	</ul>
        </div>
        <div class="box">
		    <?php wp_list_bookmarks(array('title_before'=>'<h3>', 'title_after'=>'</h2>', 'category_before'=>'', 'category_after'=>'')); ?>
        </div>
        <div class="box">
        	<h3>Meta:</h3>
        	<ul class="meta">
            	<li><a href="<?php bloginfo('rss2_url'); ?>" title="Syndicate this site using RSS"><abbr title="Really Simple Syndication">RSS</abbr></a></li>
            	<li><a href="<?php bloginfo('comments_rss2_url'); ?>" title="The latest comments to all posts in RSS">Comments <abbr title="Really Simple Syndication">RSS</abbr></a></li>
            	<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
            	<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
            	<?php wp_meta(); ?>
        	</ul>
        </div>
	<?php endif; ?>
</div><!--#left-block-->
