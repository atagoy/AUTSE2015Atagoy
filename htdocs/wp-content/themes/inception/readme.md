# Inception

Inception is a Simple, Clean, Flat Fully Responsive and Translation Ready WordPress theme. This theme is built on the top of solid Hybrid Core framework with bootstrap and retina ready Font Awesome icons integration. This theme utilizes HTML 5 conventions, Schema.org markup and Parallax Effect for Header Image. This theme comes with a powerful settings which can be easily customized in live preview mode. Highlighted options are 6 Layouts options for every pages and posts, 2 Layout Styles ( Boxed and Wide ), 4 different Layout Width, Custom Logo/Favicon Upload, Unlimited Color Scheme, 15 Social Icons, Custom CSS, Custom Background, Featured Post Section in Homepage, Header Bar to show contact informations (contact/email/location) and many more.

## About Inception

Inception is built on the top of legendary Hybrid Core framework and Bootstrap 3 for the responsiveness. Several tweaks have been made in order to make theme super awesome. Hybrid Core makes it easier to build child themes and Bootstrap 3 makes the theme super responsive. 

Some of the unique feature are mentioned below :
* Latest HTML 5 Conventions
* Schema.org Markup
* Bootstrap 3 Menu Opens on Hover instead of click
* Multi Level Bootstrap 3 Menu
* Select Mobile Menu
* Retina Ready Font Awesome Icons
* Unlimited Color Scheme ( Single option will help to customize almost every important section of theme )
* 6 Different Layout 
	- 3 Column - Sidebar Content Sidebar
	- 3 Column - Sidebar Sidebar Content
	- 3 Column - Content Sidebar Sidebar
	- 2 Column - Content Sidebar
	- 2 Column - Sidebar Content
	- 1 Column - Full Width
* 2 Different Layout Styles
	- Boxed
	- Wide
* 4 Different Layout Width
	- 1600px
	- 1170px ( default )
	- 992px
	- 768px
* Header Information Bar
	- Phone Number
	- Email
	- Location
* 2 Social Menu with 15 Retina Ready Icons( each on Header and Footer )
* Easy way to enter Custom CSS via customizer live preview
* Custom Logo and Favicon Upload Section
* Parallax Effect for Header Image
* Featured Post Section
* Easy to build child themes

## Copyright and License

The following resources are included/used for making the theme package.

* [Hybrid Base Theme](https://github.com/justintadlock/hybrid-base) by Justin Tadlock - Licensed under the [GPL, version 2 or later](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html). - Re-Licensed under GPL v3

* [Hybrid Core Framework](https://github.com/justintadlock/hybrid-core) by Justin Tadlock - Licensed under the [GPL, version 2 or later](http://www.gnu.org/licenses/old-licenses/gpl-2.0.html). - Re-Licensed under GPL v3

* [Bootstrap 3 Framework](http://getbootstrap.com) by Mark Otto & Jacob Thornton - Licensed under the [MIT License](http://opensource.org/licenses/mit-license.html).

* [Font Awesome](http://fontawesome.io/) by  Dave Gandy - Licensed under the [MIT License](http://opensource.org/licenses/mit-license.html).

* [wp_bootstrap_navwalker](https://github.com/twittem/wp-bootstrap-navwalker) by Edward McIntyre - Licensed under [GPL v2 or later]

* Images located at inception/images (header.png) and used in screenshot both are clicked by me and are licensed GPL v3.

All other resources and theme elements are licensed under the [GNU GPL](http://www.gnu.org/licenses/gpl-3.0.html), version 3 or later.

2014 &copy; [WebYatri Themes](http://webyatri.com/themes).

## Changelog

### Version 1.0.0

* Everything's new!

### Version 1.0.1

* Licensing information for Images located in "images" folder added
* Renamed .pot file for translation
* Added sanitization callback for customizer settings
* Removed Author URI as of now
* Updated version of hybrid core to 2.0.5-beta
* Changed screenshot due to image issues

### Version 1.0.2

* Removed the favicon html when no favicon is uploaded
* Added unminified version of bootstrap css, js and fontawesome css
* Escape $phone_info, $location_info before output in header.php
* Escape $layout_style and $layout_width before output in custom-css.php
* Escape get_theme_mod( 'inception_custom_css' ) ) before output in custom_css.php
* Add proper copyright/license attribution for wp_bootstrap_navwalker in readme.md
* Add proper prefix to function select_mobile_nav_menu in /inc/theme.php.

### Version 1.0.3

* Escape favicon url