=== Plugin Name ===
Contributors: shane-welland, CreareGroup
Donate link: http://www.creare.co.uk/
Tags: search, form, taxonomies, custom post type, taxonomy filter
Requires at least: 3.0.0
Tested up to: 3.9.1
Stable tag: 1.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A useful plugin for creating search forms & results pages for custom post types & taxonomies.

== Description ==

The Advanced Custom Post Search (ACPS) plugin can be easily integrated into any WordPress theme that uses widgets. Not only this but you can also integrate a search form into any post, page or WordPress template using our simple shortcodes.

Featuring unlimited, fully-customisable forms from which you can quickly search (by taxonomy term[s]) and display your custom post type results.

ACPS harnesses WordPress’ own tax queries to provide flexible results pages that address the lack of built-in taxonomy & custom post type search options.

Additional to this, we have also integrated theme functionality for developers who feel comfortable with adding their own folders and files. For more information on how to do this, see the FAQ.

Here are some of the main features:

* Unlimited form creation
* Select any of your custom post types (including those created by other plugins)
* Taxonomy filters for selected custom post type
* Optional text input field
* Use custom results pages of your own
* Override default results page/loop via a theme
* Widget Support
* Shortcode Support
* ‘Out of the box’ compatibility with numerous WordPress themes (twentyfourteen/ thirteen etc)
* Complete control over form styles

Please visit our website for more [free WordPress plugins](http://www.creare.co.uk/services/extensions/wordpress).

== Installation ==

Installing and using ACPS couldn’t be simpler:

1. Upload `/advanced-custom-post-search/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Access the Form List page through the WordPress admin menu to begin creating forms
5. Save your form and add our widgets to your theme, or use the shortcodes at the bottom of each of the form creation pages
6. If you want to setup form styles and a custom results page then do this via the plugin’s settings page

== Frequently Asked Questions ==

= Do I need custom post types setup before I install the plugin? =

No, but you will need at least one to create a form.

= Do I need taxonomies and/or terms before I install the plugin? =

No, but you will need at least one of each before you can create a functional form.

= My shortcode/widget is showing an ACPS error on my site =

The error specifically addresses configuration issues so check you have custom post types, taxonomies and terms created.

= How do I theme my results page/loop? =

ACPS allows you to override the default results page and loop by adding the files ‘acps-results_loop.php’ and ‘acps-results.php’ in a number of places in your active theme folder. Either copy the file(s) into the ‘/wp-content/[theme_directory]/acps/templates’ or ‘/wp-content/[theme_directory]/‘ directory to override the default templates.

= My results pages aren’t listing any results =

The results pages work off of the standard WP_Query loop and therefore list any options based on the filters you selected. Make sure you have published the posts you are trying to find.

= Can I specify my own base results page? =

As of version 1.1 you are able to setup your own results pages instead of using the default ‘advanced-search’ page, you can access the options for this on the plugin’s settings page.

= My custom results page isn’t displaying results =

Be sure to include the results shortcode [acps_results] in the content area (or in the theme template), without this the results will not load.

= My custom results loop isn’t listing results =

The results section is setup through ACPS’ acps_results class, you need to use ‘$this->acps_args’ in your WP_Query setting otherwise no query parameters will be set.

= How do I style my forms? =

Since version 1.1 you can use the built in form style option which can be found on the plugin’s general settings page or alternatively just include any styles in your own stylesheets. Classes are added to your form(s) to make styling easy, they are based on a number of things including the following: labels/no labels, shortcode/ widget, inside titles/ outside titles, custom container class.

== Screenshots ==

1. Here is the ‘Form List’ page. It’s sole purpose is to allow you to add,edit and trash your search form(s).
2. This is an example of how the ‘Form Edit’ page looks and specifically what sort of options you are likely to expect.
3. This shows the default form in both shortcode (left) and widget (right) format, demonstrated in the ‘twentyfourteen’ theme.
4. Here is a typical results page, also demonstrated in the ‘twentyfourteen’ theme.

== Changelog ==

= 1.2.4 =
* [Added] Multiple terms option to allow multiple taxonomy terms to be searched
* [Fixed] Heading "typo" on front-end form heading

= 1.2.3 =
* [Added] Translation support for taxonomy term dropdowns

= 1.2.2 =
* [Added] ACPS Search form now included when no results are found instead of the default WordPress form
* [Fixed] Support for child themes
* [Added] Additional body tag classes to search results page to mimic WordPress' own
* [Added] Post Count details in results page title
* [Added] Remembered search term on forms (if using plugin as a 'filter')
* [Added] Remembered taxonomy term on forms (if using plugin as a 'filter')
* [Added] Filter to $acps_args variable declaration

= 1.2.1 =
* [Added] Additional select option to search all term in selected taxonomies

= 1.2 =
* [Fixed] Support for default permalinks (now uses $_POST for results pages)

= 1.1 =

* [Fixed] Override bug which stopped you from theming in the ‘wp-content/[current-theme]/acps/templates/’ directory
* [Added] Base results page option
* [Added] Form styling from within WordPress
* [Updated] plugin activation functionality: To handle new settings options
* [Updated] Table/ Menu styling in WordPress admin areas

= 1.0 =

* Initial release.

== Upgrade Notice ==

= 1.0 =

* No upgrades are currently available as this is the initial release.