                    <div id="footer">
			<span class="copyright">&copy; <?php echo date('Y');?> <?php bloginfo( 'name' ); ?>. All Rights Reserved.</span>
                        <span class="links">
                            <a href="<?php bloginfo('rss2_url'); ?>" title="Syndicate this site using RSS" class="rss">Entries <abbr title="Really Simple Syndication">RSS</abbr></a>
                            <a href="<?php bloginfo('comments_rss2_url'); ?>" title="The latest comments to all posts in RSS" class="rss">Comments <abbr title="Really Simple Syndication">RSS</abbr></a>
                            <?php wp_loginout(); ?>
			    <a href="http://wordpress.searchperience.com" class="powered"><img src="<?php echo get_template_directory_uri(); ?>/img/cubes.gif" alt="Commerce Search" /></a>
                        </span>
                    </div><!--#footer-->
                <!--#do not delete this part#-->
                </div><!--#main-block-->
            </div><!--#main-->
        </div><!--#root-->
	<?php wp_footer(); ?>
    </body>
</html>
