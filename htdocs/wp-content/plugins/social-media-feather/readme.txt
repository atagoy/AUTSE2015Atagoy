=== Social Media Feather - lightweight social media sharing and follow buttons ===
Contributors: Synved
Donate link: http://synved.com/wordpress-social-media-feather/
Tags: shortcode, shortcodes, link, links, url, permalink, permalinks, time, icons, button, buttons, free, content, plugin, Share, sharing, social share, social sharing, page, pages, widget, CSS, list, media, profile, shortlinks, social, social media, Like, twitter, google, Facebook, Reddit, youtube, vimeo, tumblr, instagram, flickr, foursquare, social media buttons, bookmark, bookmarks, bookmarking, pinterest, linkedin, social links, image, edit, manage, mail, Post, posts, Style, seo, title, filter, follow, following, social follow, social following, Social Media Icons, Social Media Widget, high resolution, retina, ipad
Requires at least: 3.1
Tested up to: 4.2
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Super lightweight, simple, nice, modern looking and effective social media sharing and following buttons and icons on your site quick and easy

== Description ==

[WordPress Social Media Feather](http://synved.com/wordpress-social-media-feather/ "Lightweight WordPress social sharing and following") is a super lightweight free social media WordPress plugin that allows you to quickly and painlessly add **social sharing and following** features to all your posts, pages and custom post types.

The plugin supports adding of social buttons for sharing or following (that is, social buttons that link to your social network profiles pages). The social media buttons can be easily and automatically be added to all your posts, pages or any other **custom post types**.

Check out this introductory tutorial on [how to install and get started with Social Media for WordPress](http://youtu.be/iJAq2nJi6BM) by a helpful user of Social Media Feather!

https://www.youtube.com/watch?v=iJAq2nJi6BM

Now the only social sharing and bookmarking plugin with full support for the **Retina and high resolution displays** as used in iPad 5 and other devices!

The primary goal behind the plugin is to provide very lightweight WordPress social sharing and following that doesn’t add any unnecessary burden to your site and especially on your users, so you can easily add share buttons and social profiles links to your posts and pages automatically and speedily.

What sets WordPress Social Media Feather aside from the plethora of other social sharing and following WordPress plugins is its focus on simplicity, performance and unobtrusive impact. Social share buttons and links to your social pages are fast to setup with automatic display or social widgets.

In order to achieve higher performance the plugin makes no use of JavaScript and as a consequence it’s **really fast** while still providing all the social media functionality you might need, like very professional social share buttons with high quality icons.

By keeping load times at a minimum, you ensure that all the social sharing aspects of your site don’t interfere with those visitors who just want to access the content but are not interested in sharing on social media platforms.

On the other hand, given the widespread focus on WordPress social media integration, your site will still provide social bookmarks and share buttons to improve visibility of your posts and content and improve your overall global reach on social platforms.

The **WordPress social media sharing** offered by the plugin includes all major social sharing buttons providers like Facebook, Twitter, Google+, reddit, Pinterest, tumblr, Linkedin and even e-mail.

It will show social buttons that your users can click to share to facebook or tweet your posts and pages on your site or submit it to reddit and google plus and all other social sharing networks.

The **WordPress social media following** offered by the plugin includes all major social network providers and tools like Facebook, Twitter, Google+, Pinterest, Linkedin, YouTube, tumblr, instagram, flickr, foursquare, vimeo or RSS.

Our social media plugin also offers widgets for sharing and following buttons that you can place in any widgetized area in your site and the widgets also expose some settings and parameters to tweak the appearance of the social buttons. The plugin also provides shortcodes that can be used for the same purpose, creating both share and follow buttons and allowing selection of visibility of different social media networks or reordering how the various social networks appear (see example shortcodes at the bottom).

You can disable automatic rendering of social icons for specific posts by using *Custom Fields*. Simply set a custom field of `synved_social_exclude_share` to "yes" (without quotes) to disable rendering of sharing buttons on the post/page or `synved_social_exclude_follow` to "yes" (without quotes) to remove following buttons from the post or alternatively `synved_social_exclude` to disable both. The *Custom Fields* editor needs to be enabled on your post/page edit screen by clicking at the top right where it says "Screen Options".

= Features =
* Integrated WordPress social sharing for all your posts
* Full support for **Retina** and high resolution displays
* WordPress social sharing and following widgets
* Supports all major providers of social features
* Sharing with Facebook, Twitter, Google+, reddit, Pinterest, tumblr, Linkedin and e-mail
* Following on Facebook, Twitter, Google+, Pinterest, Linkedin, YouTube, tumblr, instagram, flickr, foursquare, vimeo or RSS Feed
* Each social provider can be enabled or disabled
* Ability to select what services each provider will be exposed for
* Full customization for titles and URLs for each provider
* Super lightweight social sharing and following
* Fast unobtrusive social bookmarks for your site
* Comes with a default modern icon set
* For further customization more [social icons skins](http://synved.com/product/feather-extra-social-icons/ "Add 8 extra social icon skins to the Social Media Feather plugin!") are available
* Available skins can be customized with cool effects like [fading and greying out](http://synved.com/product/feather-grey-fade/ "Customize any of the available social icon skins with 2 cool effects!") social icons
* If you like them you can get [social sharing counters](http://synved.com/product/feather-light-prompt/ "Nice lightweight social sharing counters using the Light Prompt addon") that load dynamically, only when necessary, thus not weighing in on visitors who don't use them

= Example Shortcodes =

This shortcode will create a list of social sharing buttons to share content on your site:
`[feather_share]`

This shortcode will create a list of social media sharing buttons to share content on your site, only showing Google+, Twitter and Facebook, in that specific order:
`[feather_share show="google_plus, twitter, facebook" hide="reddit, pinterest, linkedin, tumblr, mail"]`

You can change the order of displayed buttons by changing the order of keywords:
`[feather_share show="twitter, google_plus, facebook" hide="reddit, pinterest, linkedin, tumblr, mail"]`

This shortcode will create a list of social sharing buttons to share content on your site using the "Wheel" icons skin:
`[feather_share skin="wheel"]`

This shortcode will create a list of social media sharing buttons to share content on your site using the default icon skin with a size of 64 pixels:
`[feather_share size="64"]`

You can add a custom CSS class to your share buttons using the "class" attribute:
`[feather_share class="myclass"]`

You can combine all the parameters above to customize the look, for instance using the "Wheel" icon skin at a size of 64 pixels and only showing Google+, Twitter and Facebook, in that specific order:
`[feather_share skin="wheel" size="64" show="google_plus, twitter, facebook" hide="reddit, pinterest, linkedin, tumblr, mail"]`

The next shortcode will create a list of social following buttons that allow visitors to follow you:
`[feather_follow]`

The next shortcode will create a list of social following buttons that allow visitors to follow you, using the "Balloon" icons skin:
`[feather_follow skin="balloon"]`

You can add a custom CSS class to your social profiles buttons using the "class" attribute:
`[feather_follow class="myclass"]`

The next shortcode will create a list of social media following buttons that allow visitors to follow you, using the "Balloon" icons skin with a size of 64 pixels:
`[feather_follow skin="balloon" size="64"]`

= Template Tags =

If you don't want to use shortcodes but instead prefer to use PHP directly, there are 2 PHP functions/template tags you can use.

For sharing buttons you can use:
`if (function_exists('synved_social_share_markup')) echo synved_social_share_markup();`

For following buttons you can use:
`if (function_exists('synved_social_follow_markup')) echo synved_social_follow_markup();`


= Related Links: =

* [WordPress Social Media Plugin Official Page](http://synved.com/wordpress-social-media-feather/ "WordPress Social Media Feather – lightweight WordPress social sharing and following")
* [Extra Social Icons Skins](http://synved.com/product/feather-extra-social-icons/ "Add 8 extra social icon skins to the Social Media Feather plugin!")
* [Grey Fade addon that can grey out and fade out any social icons set](http://synved.com/product/feather-grey-fade/ "Customize any of the available social icon skins with 2 cool effects!")
* [Light Prompt that adds counts for social shares](http://synved.com/product/feather-light-prompt/ "Add counters for social shares using Light Prompt")
* [Our own site](http://synved.com/) where you can see social sharing and following in action
* [Stripefolio theme demo](http://wpdemo.synved.com/stripefolio/) where you can see some of the social sharing and following features in action
* [The free Stripefolio WordPress portfolio theme](http://synved.com/stripefolio-free-wordpress-portfolio-theme/ "A free WordPress theme that serves as a readable blog and a full-screen portfolio showcase") the Official page for the theme in the above demo link

== Installation ==

1. Download the Social Media Feather plugin
2. Simply go under the Plugins page, then click on Add new and select the plugin's .zip file
3. Alternatively you can extract the contents of the zip file directly to your *wp-content/plugins/* folder
4. Finally, just go under Plugins and activate the plugin

== Frequently Asked Questions ==

= How can I see the social icons in action? =

Have a look at [our site](http://synved.com/) or the [Stripefolio portfolio theme demo](http://wpdemo.synved.com/stripefolio/) where you can see the social sharing and following features in action

= How do I disable rendering of sharing / bookmarking buttons on a specific post/page? =

You can achieve this by using *Custom Fields*. Simply set a custom field of `synved_social_exclude_share` to "yes" (without quotes) to disable share buttons on the post or page. Alternatively set `synved_social_exclude` to "yes" (without quotes) to disable both sharing and following.

= How do I disable rendering of social profiles follow buttons on a specific post/page? =

You can achieve this by using *Custom Fields*. Simply set a custom field of `synved_social_exclude_follow` to "yes" (without quotes) to remove following buttons from the post or page. Alternatively set `synved_social_exclude` to "yes" (without quotes) to disable both sharing and following.

== Screenshots ==

1. An example of how the sharing or following buttons appear in the front-end at 64 pixel resolution
2. An example of how the share or follow icons appear in the front-end at 24 pixel resolution
3. An example of how the following or sharing links appear in the front-end using the [Extra Social Icons addon](http://synved.com/product/feather-extra-social-icons/ "Add 8 extra social icon skins to the Social Media Feather plugin!")
4. Showing how using the [Grey Fade addon](http://synved.com/product/feather-grey-fade/ "Customize any of the available social icon skins with 2 cool effects!") transforms the sharing or following buttons in the front-end
5. A demo of how social media providers can be customized in the back-end
6. An view of some of the settings that can be customized in Social Media the back-end
7. This shows the available social sharing and following widgets and their settings 

== Changelog ==

= 1.7.8 =
* Disable credit link by default
 
= 1.7.7 =
* Fixed addon installer's path calculation for rare cases

= 1.7.6 =
* Minor adjustments

= 1.7.5 =
* Updated social network links descriptions to be more clear

= 1.7.4 =
* Cache provider list to improve performance when social buttons are shown many times

= 1.7.3 =
* Strip HTML from titles in sharing links
* Fix for certain Fancybox plugins loading lightboxes on sharing images

= 1.7.2 =
* Added `image` attribute for shortcodes
* Minor adjustments

= 1.7.1 =
* Fix for Easy Digital Downloads adding HTML tags to titles that were then posted to social sharing
* Fix for certain quote characters not being properly converted on share

= 1.7 =
* Performance improvements

= 1.6.15 =
* Fix for PHP notice in rare cases
* Prevent certain fancybox plugins from trying to open fancybox on share/follow icons

= 1.6.14 =
* Adjusted description
* Added documentation

= 1.6.13 =
* Added author_wp variable for built-in WordPress author name

= 1.6.12 =
* Re-compressed all large icon sets to slightly reduce file size

= 1.6.11 =
* Minor tweaks

= 1.6.10 =
* Minor adjustments

= 1.6.9 =
* Added url_trimmed variable that trims extra slashes off of the URL

= 1.6.8 =
* Added short_url variable that always contains the shortened URL

= 1.6.7 =
* Fix automatic displaying of share/follow buttons on single posts only

= 1.6.6 =
* Updated all images to "optimized" versions to silence certain analytical tools

= 1.6.5 =
* Fixed issue for correct detection of home page

= 1.6.4 =
* Added two filters for shortcode parameters: synved_social_shortcode_variable_list and synved_social_shortcode_parameter_list
* Minor adjustments

= 1.6.3 =
* Additional fix for "ghost" prefixes appearing in odd cases for non-single pages

= 1.6.2 =
* Fix for "ghost" prefixes appearing in certain cases for non-single pages

= 1.6.1 =
* Adjusted some descriptions

= 1.6 =
* Added alignment options for both sharing and following buttons
* Minor adjustments

= 1.5.10 =
* Added date variable
* Minor adjustments

= 1.5.9 =
* Fix for RSS feeds displaying double resolution images 
* Minor adjustments

= 1.5.8 =
* Attempt suggesting meta values to Facebook (it seems to ignore them at this time though)
* Fix for esc_url strictness
* Minor adjustments

= 1.5.7 =
* Fixed escaping of quote and double quote characters
* Added mail as follow provider for "contact us" buttons
* Minor adjustments

= 1.5.6 =
* Added %%author%% template variable for URL substitution
* Minor tweaks

= 1.5.5 =
* For automatic display, allow positioning of buttons both before and after post content
* Minor adjustments

= 1.5.4 =
* Pick first image in the post when featured image is not set
* Minor adjustments

= 1.5.3 =
* Added buttons container options
* Misc adjustments

= 1.5.2 =
* Small fix to default URL
* Appearance fix in admin settings page
* Fixed typo

= 1.5.1 =
* Updated Facebook icons according to newest branding changes

= 1.5 =
* Added social providers instagram, flickr and foursquare

= 1.4.4 =
* Fixed titles not displaying certain special characters properly
* Misc adjustments

= 1.4.3 =
* Fixed share URL being incorrect in some instances like subdir installs
* Misc adjustments

= 1.4.2 =
* Fixed some issues on certain windows hosting
* Fixed installation of addons in certain peculiar environments
* Added option for RTL layouts sites

= 1.4.1 =
* Minor fixes and adjustments

= 1.4.0 =
* Added social providers tumblr and vimeo
* Assorted minor fixes and tweaks

= 1.3.4 =
* Fix for potential conflicts with some other plugins

= 1.3.3 =
* Fixed validation error for e-mail link
* Fixed invalid index notices

= 1.3.2 =
* Added ability to specify position for both share and follow buttons
* Added options for prefix and postfix markup for individual buttons sets
* Fixed warning when in debug mode

= 1.3.1 =
* Tweak the new Retina display code to work more accurately
* Fix for share URL being incorrect in certain cases
* Fix for addons being deleted by WordPress on automatic upgrade (this will work from the next version, sorry about that!)

= 1.3.0 =
* Added support for Retina and other high resolution displays
* Fix exclusion checks for custom post types
* Added option to share full URL instead of single post/page URL

= 1.2.3 =
* Fix check for single posts to include all singular pages
* Set image dimensions attributes to match icon size
* Fix for images stacking vertically in some themes
* Fix for automatic follow not shown when automatic share was disabled

= 1.2.2 =
* Added option to limit automatic appending to single post/pages

= 1.2.1 =
* Added Pinterest as sharing and following network
* Added ability to automatically append following buttons as well
* Added ability to disable automatic appending for posts with custom fields
* Added class, show and hide parameters to shortcodes

= 1.0 =
* First public release.

