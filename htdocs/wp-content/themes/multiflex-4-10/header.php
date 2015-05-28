<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/layout.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/typography.css" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
<!-- Global IE fix to avoid layout crash when single word size wider than column width -->
<!--[if IE]><style type="text/css"> body {word-wrap: break-word;}</style><![endif]-->
</head>
<body>
  <!-- Main Page Container -->
  <div class="page-container">

   <!--  START COPY here -->

    <!-- A. HEADER -->      
    <div class="header">
      
      <!-- A.1 HEADER TOP -->
      <div class="header-top">
        <div class="round-border-topleft"></div><div class="round-border-topright"></div>      
        
        <!-- Sitelogo and sitename -->
        <a class="sitelogo" href="#" title="Home"></a>
        <div class="sitename">
          <h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1> 
          <h2><?php bloginfo('description'); ?></h2>
        </div>
    	
        <!-- NOT USED, MODIFY TO YOUR NEEDS -->
        <!-- Navigation Level 0 -->
        <div class="nav0">
          <ul>
            <li>&nbsp;</li>
          </ul>
        </div>			

        <!-- Navigation Level 1 -->
        <div class="nav1">
          <ul>
            <li>&nbsp;</li>
          </ul>
        </div>              
      </div>
      
      <!-- END NOT USED, MODIFY TO YOUR NEEDS -->
            
      <!-- A.3 HEADER BOTTOM -->
      <div class="header-bottom">
      
        <!-- Navigation Level 2 (Drop-down menus) -->
        <div class="nav2">
	
          <!-- Navigation item -->
          <ul>
            <li><a href="<?php echo get_settings('home'); ?>">Главная</a></li>
            <?php wp_list_pages('title_li=&depth=1'); ?>
          </ul>
          
        </div>
	  </div>

      <!-- A.4 HEADER BREADCRUMBS -->

      <!-- Breadcrumbs -->
      <div class="header-breadcrumbs">
      	<ul>
        <?php include (TEMPLATEPATH . "/breadcrumbs.php"); ?>
        </ul>
        
        <!-- Search form -->                  
        <div class="searchform">
          <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
            <fieldset>
              <input type="text" class="field"  value="<?php the_search_query(); ?>" name="s" id="s" />
              <input type="submit" name="button" class="button" value="ОК" />
            </fieldset>
          </form>
        </div>
      </div>
    </div>

   <!--  END COPY here -->

    <!-- B. MAIN -->
    <div class="main">
