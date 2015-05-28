<meta http-equiv="content-type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
<link rel="shortcut icon" href="<?php echo $this->warp->path->url('template:favicon.ico');?>" /> 
<?php

wp_enqueue_script('jquery'); 
wp_head();

$style_urls  = array_keys($this->warp->stylesheets->get());
$script_urls = array_keys($this->warp->javascripts->get());

// get compressed styles and scripts
if ($compression = $this->warp->config->get('compression')) {
	$options = array();
	
	if ($compression >= 2) {
		$options['gzip'] = true;
	}

	if ($compression == 3) {
		$options['data_uri'] = true;
	}

	if ($urls = $this->warp->cache->processStylesheets($style_urls, $options)) {
		$style_urls = $urls;
	}

	if ($urls = $this->warp->cache->processJavascripts($script_urls, $options)) {
		$script_urls = $urls;
	}
}

// add styles
foreach ($style_urls as $style) {
	echo '<link rel="stylesheet" href="'.$style.'" type="text/css" />'."\n";
}

// add scripts
foreach ($script_urls as $script) {
	echo '<script type="text/javascript" src="'.$script.'"></script>'."\n";
}

// add style declarations
foreach ($this->warp->stylesheets->getDeclarations() as $type => $style) {
  echo '<style type="'.$type.'">'.$style.'</style>'."\n";
}

// add script declarations
foreach ($this->warp->javascripts->getDeclarations() as $type => $script) {
  echo '<script type="'.$type.'">'.$script.'</script>'."\n";
} 

// add feed link
if (strlen($this->warp->config->get('rss_url',''))) {
    echo '<link href="'.$this->warp->config->get('rss_url').'" rel="alternate" type="application/rss+xml" title="RSS 2.0" />';
}

$this->output('head');