<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/11001/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php //comments_popup_script(600, 600); ?>
	<?php wp_head(); ?>

	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="http://info.template-help.com/files/ie6_warning/ie6_script.js"></script>  
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/cufon-yui.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/cufon-replace.js"></script>  
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/AvantGarde_Bk_BT_400.font.js"></script>  
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/drop-down-menu.js"></script>	
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/imagepreloader.js"></script>
<script type="text/javascript">
	preloadImages([
		'../images/bg_menu-act.gif']);
</script> 
            
</head><body>
<div id="header">
    <div class="main">
    	<div class="indent-menu">
            <div id="dropmenu">
                <?php
                    wp_page_menu('show_home=0&sort_column=menu_order, post_title&link_before=&link_after=');
                ?>
            </div>
        </div>
        <div class="indent">
        	Help Center<br />
            <img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icon.gif" class="alignMiddle" /> &nbsp;Toll free number: 1-800-123-DATA
        </div>
        <div class="logo"><h1 onclick="location.href='<?php echo get_option('home'); ?>/'"><?php bloginfo('name'); ?></h1></div>
        
        
    </div>
</div>
<?php if (is_home()) : ?>
<div id="content1">
    <div class="main">
    	<div class="indent-main">
        	<div class="container">
            	<div class="col-1">
                	<div class="container">
                    	<img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/icon1.gif"  class="img-left" />
                        <div class="indent"><h2>Welcome</h2></div>
                        <br class="clear" />
                    </div>
                    <p class="txt">Duis aute iruredolor in reprehedeit in voptate sit nvelit esse cillum dolore fumgiat officia desemrunt mollit. Anim in laborum. excepteur sint occaecat.</p>
                    <p class="txt">sunt in culpa qui officia deserunt mollit anim id est laborum. Duis aue irure dolor in reprehenderit in voluptate velit.</p>
                    <a href="#" class="txt1">Mr. John  Hummerhead</a>
                </div>
                <div class="col-2">
                	<h2>We Have Propositions for Everybody</h2>
                	<div class="container">
                    	<div class="col-3">
                        	<div class="box1">
                                <div class="border-top">
                                    <div class="border-bottom">
                                        <div class="border-right">
                                            <div class="border-left">
                                                <div class="corner-top-right">
                                                    <div class="corner-top-left">
                                                        <div class="corner-bottom-left">
                                                            <div class="corner-bottom-right"> 
                                                                <div class="indent-box">
                                                                    <img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/1page_img1.jpg" /><br />
                                                                    <div class="indent1">
                                                                    	<div class="title"><h2>Business Planning</h2></div>
                                                                    </div>
                                                                    <div class="indent2">
                                                                    	<p>Sed laoret aliquam leounelus dolor dapibus elemitum.</p>
                                                                        <a href="#"><img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/button.gif" /></a><br />
                                                                    </div>
                                                                </div>                                     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    	<div class="col-3">
                        	<div class="box1">
                                <div class="border-top">
                                    <div class="border-bottom">
                                        <div class="border-right">
                                            <div class="border-left">
                                                <div class="corner-top-right">
                                                    <div class="corner-top-left">
                                                        <div class="corner-bottom-left">
                                                            <div class="corner-bottom-right"> 
                                                                <div class="indent-box">
                                                                    <img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/1page_img2.jpg" /><br />
                                                                    <div class="indent1">
                                                                    	<div class="title"><h2>Target Marketing</h2></div>
                                                                    </div>
                                                                    <div class="indent2">
                                                                    	<p>Sed laoret aliquam leounelus dolor dapibus elemitum.</p>
                                                                        <a href="#"><img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/button.gif" /></a><br />
                                                                    </div>
                                                                </div>                                     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                        	<div class="box1">
                                <div class="border-top">
                                    <div class="border-bottom">
                                        <div class="border-right">
                                            <div class="border-left">
                                                <div class="corner-top-right">
                                                    <div class="corner-top-left">
                                                        <div class="corner-bottom-left">
                                                            <div class="corner-bottom-right"> 
                                                                <div class="indent-box">
                                                                    <img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/1page_img3.jpg" /><br />
                                                                    <div class="indent1">
                                                                    	<div class="title"><h2>Support Center</h2></div>
                                                                    </div>
                                                                    <div class="indent2">
                                                                    	<p>Sed laoret aliquam leounelus dolor dapibus elemitum.</p>
                                                                        <a href="#"><img alt="" src="<?php bloginfo('stylesheet_directory'); ?>/images/button.gif" /></a><br />
                                                                    </div>
                                                                </div>                                     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div id="content">
    <div class="main">
    	<div class="indent-main">
            <div class="container">
            <?php get_sidebar(); ?>
