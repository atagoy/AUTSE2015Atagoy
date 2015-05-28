<?php if(get_theme_option('about_bar') == 'true') {
?>
    <div class="span-24">
        <div id="aboutbar" class="clearfix">
            <?php if(get_theme_option('about_image')){ ?>
                <img src="<?php echo get_theme_option('about_image'); ?>" class="about_image" align="left" />
            <?php } ?>
            <?php echo get_theme_option('about_content'); ?>
        </div>
    </div>
<?php
}
?>