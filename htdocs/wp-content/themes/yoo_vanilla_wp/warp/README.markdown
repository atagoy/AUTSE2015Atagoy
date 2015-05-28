# Warp Theme Framework #

- Version: 5.5.21
- Date: September 2011
- Author: YOOtheme GmbH
- Website: <http://www.yootheme.com/warp>

## Changelog

	5.5.21
	^ Updated jQuery to 1.6.4 (J)
	^ Changed CSS/JS inclusion for Joomla cache compatiblity (J)

	5.5.20
	# Fixed widget footer id issue (WP)
	^ Updated jQuery to 1.6.3 (J)

	5.5.19
	^ Changed $.fn.spotlight to $.fn.warpspotlight
	# Fixed frontpage added to config overrides (WP)
	# Fixed empty ajax search (WP)
	# Fixed searchbox input line-height
	# Fixed general warp issues (WP_DEBUG=true)

	5.5.18
	+ Added Joomla 1.7 support

	5.5.17
	# Fixed post date formatting (WP)
	^ Updated overrides according to Joomla 1.6.4 (J16)
	^ Updated jQuery to 1.6.2 (J)

	5.5.16
	# Fixed apply shortcodes in ajax search (WP)
	# Fixed ajax search with sef turned on (J)
	^ Updated jQuery to 1.6.1 (J)

	5.5.15
	^ Updated jQuery to version 1.5.2 (J)
	^ Updated overrides according to Joomla 1.6.2 (J16)
	+ Disable auto-linebreaks in content output (WP)
	+ Added german language files (J)
	# Fixed dynamic presets (J16)
	# Fixed the_title_attribute() usage for titles (WP)
	# Fixed home/frontpage display widget options (WP)
	# Fixed "&" in title error (WP)

	5.5.14
	^ Updated overrides according to Joomla 1.6.1 (J16)
	^ Updated jQuery to version 1.5.1 (J)
	- Removed behavior mootools (J16)
	+ Added widget display configuration for categories (WP)
	# Improved widget filtering (WP)
	# Fixed widget options loading (WP)
	# Fixed chrome dropdown bug
	# Fixed backtrack limit error by rendering huge sidebars (WP)
	# Fixed pagination translation for (J16)
	# Fixed iFrame border in com_wrapper (J16)
	# Fixed "Posted in" translation in com_search (J16)
	# Fixed jQuery defer/async issue (J16)

	5.5.13
	^ Changed pagination selector to prevent third party issues
	# Fixed issue introduced with 5.5.12 when a module is published last in the menu

	5.5.12
	^ Changed Data URI filesize limit to 10KB
	+ Added override for com_wrapper (J16)
	+ Added override for mod_articles_archive (J16)
	+ Added override for mod_articles_latest (J16)
	+ Added override for mod_articles_news (J16)
	+ Added override for mod_articles_popular (J16)
	# Fixed XmlHelper special character '&' in menu J1.6
	# Fixed missing space after "Posted in" in com_content overrides for J15
	# Fixed pagination CSS issue with different lanuages
	# Fixed translation in com_contact overrides for J15
	# Improved widget rendering compatibility (WP)
	# Improved page title output (WP)

	5.5.11
	+ Added Joomla 1.6 support (J16)
	+ Added Global config overrides (WP)
	+ Added accordionsmenu.js collapseall option
	# Fixed $.fn.matchHeight
	# Fixed smoothScroller for Opera
	# Fixed special html chars in menu items (WP)
	# Fixed incorrect space in language file (J15)
	# Fixed breadcrumbs show last (J15)
	# Fixed image paths, tooltip and editor buttons in system.css (J15)
	# Fixed accordionsmenu.js default mode

	5.5.10
	^ Moved scripts to jQuery
	^ Optimized accordion, morph scripts
	^ Updated css/js loading in admin theme settings (WP)
	# Fixed search, follower scripts
	# Fixed PHP4 compatible (WP)

	5.5.9
	+ Added translation support
	+ Added accordion feature for menus (WP)
	+ Added 4. column option for drop down menu (WP)
	# Fixed fixed is_home recognize for menu (WP)
	# Fixed missing footer layout added (WP)
	# Fixed missing image for comment children (WP)
	# Fixed widget options post parameter handling (WP)
	# Fixed zebra table CSS bug for IE

	5.5.8
	# Fixed browser engine detection for MooTools 1.2
	# Fixed get active menu item bug
	# Fixed menu.matchHeight for MooTools 1.2
	# Fixed icon markup in com_content item overrides for Joomla 1.5
	# Optimized Data-Uri support in cached CSS
	^ Moved default layouts and menus to main warp directory
	# Fixed pagination CSS
	+ Added new spotlight JS

	5.5.7
	# Fixed Update notifications caching directory bug

	5.5.6
	+ Added Update notifications
	+ Added Verify Files
	+ Added new module layout 1-1-1
	+ Added new Joomla Overrides
	+ Added new Ajax search
    # Fixed simplexml unknown entity bug
    # Fixed alternative image (image_alt.jpg) loading for menus
    # Fixed machtHeight JS for empty elements
    # Fixed fancy menu for Mootools 1.2

	5.5.5
	+ Added Data-Uri support in cached CSS
	
	5.5.4
	# Xml helper recursive error fix (PHP 5.2.0)
	
	5.5.3
	# Menu dropdown fixed (wrong height calculation with Mootools 1.2)
	+ mb_strpos, mb_substr fallback functions added for joomla 1.5
	+ Warp.Follower JS added
	+ Legacy XML helper UTF-8 compatible

	5.5.2
	# Fancy menu minor bugfix
	# Menu CSS classes fixed
	# Cache helper warning in safe-mode fixed
	+ Optimized XML helper

	5.5.1
	# Path helper urls creation fixed
	# Menu generation fixed with tp=1 parameter enabled

	5.5.0
	+ Initial Release

	* -> Security Fix
	# -> Bug Fix
	$ -> Language fix or change
	+ -> Addition
	^ -> Change
	- -> Removed
	! -> Note