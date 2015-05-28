=== Social Media Widget ===
Contributors: bmwebproperties
Tags: social media, twitter, facebook, google+, linkedin, youtube, vimeo, skype, yelp, instagram, social, icons
Requires at least: 2.9.2
Tested up to: 3.5.1
Stable tag: 4.0.2

Adds links to all of your social media and sharing site profiles. Tons of icons come in 3 sizes, 4 icon styles, and 4 animations.

== Description ==

The Social Media Widget is a simple sidebar widget that allows users to input their social media website profile URLs and other subscription options to show an icon on the sidebar to that social media site and more that open up in a separate browser window.

= Features =

* Supports the following social media sites:
	* Facebook
	* Google+
	* Twitter
	* MySpace
	* FriendFeed
	* Orkut
	* Hyves
	* LinkedIn
	* aSmallWorld
	* About.me
	* Skyrock
	* VK
	* Goodreads
	* Github
	* Instagram
	* Flickr
	* Picasa Web Albums
	* Pinterest
	* YouTube
	* Skype
	* Digg
	* Reddit
	* Delicious
	* StumbleUpon
	* Tumblr
	* Buzz
	* Google Talk
	* Vimeo
	* Blogger
	* Wordpress
	* Yelp
	* Last.fm
	* Pandora
	* UStream
	* IMDb
	* Hulu
	* Flixter
	* FourSquare
	* Meetup
	* PlanCast
	* SlideShare
	* DeviantArt
	* Cuttings.me
	* Live365
	* Digital Tunes
	* Soundcloud
 	* BandCamp
	* Etsy
	* Better Business Bureau
	* Merchant Circle
	* Ebay
	* Steam
	* RSS
	* E-mail (mailto: or a link to mailing list service)
	* Plus create 6 of your own using a URL to an icon and URL to the service
	
* Select from the following icon sizes:
	* 16x16
	* 24x24
	* 32x32
	* 64x64
	* Custom
	
* Select from 4 icon packages:
	* Web 2.0 (Default) - Icons from <a href="http://www.iconspedia.com/">various artists</a> 
	* Sketch - <a href="http://theg-force.deviantart.com">Social Icons Hand Drawn</a> by TheG-Force and <a href="http://www.jankoatwarpspeed.com/post/2008/10/20/handycons-a-free-hand-drawn-social-media-icon-set.aspx">Handycons</a> by Janko At Warp Speed
	* Heart - <a href="http://thedesignsuperhero.com/2009/03/heart-v2-free-social-iconset-in-heart-shape/">Heart v2</a> by The Design Superhero
	* Cutout - <a href="http://www.iconspedia.com/pack/icontexto-inside-2222/">Icontexto Inside Icons</a>
	* Custom - These will be unaltered via updates. Make sure you follow the same naming scheme as the other icons (facebook.png, twitter.png). You can look at the other icon packs if you are unsure about the naming. See the FAQ for more information on usage.
	* Note that some of the icons in the packs were created and added for this widget by myself. Not all of the requested social media sites were included so I attempted to create icons that mimicked the original artist icons. These are unattributed to myself. I keep attribution to the original artists since it is there design I modified.
	* All icons in this pack are licensed under the Creative Commons license. Note that some of these are non-commercial only. Please go to the sites linked above to get full information on their allowed uses.

* Choose from multiple animations including:
	* Fade In (you can choose the starting opacity too!)
	* Scale (zoom in)
	* Bounce
	* Combo (All three)
	* Note: Because these are all CSS3 style animations, IE6-7 aren't supported at all, although IE8 will support Scale. In IE6-8, icons will not be animated and use 100% opacity.

* Create your own image alt/title tags for hover-over text (i.e. Follow Us On {service})
	
* Choose whether or not to use the rel="nofollow" tag in your links. (Recommended see http://en.wikipedia.org/wiki/Nofollow)

* Choose whether or not to open links in a new tab (or browser window).

* Align your widget left, center, or right.

* Arrange the order of the icons however you like.

* Choose to the number of icons per row. Currently you can have one icon per row or auto (maximum # based on the width of the container the widget is placed in).

If you like this plugin, please rate it and click "Works" for your Wordpress version!


== Installation ==

Follow the steps below to install the plugin.

1. Upload the social-media-widget folder and all contents to /wp-content/plugins
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance->Widgets and activate the widget (Social Widget), set options and click save

== Screenshots ==
1. Web 2.0 icon pack
2. Cutout icon pack
3. Heart icon pack
4. Sketch icon pack
5. Widget control panel with all sections collapsed
6. Widget control panel with General Settings expanded
7. Widget control panel with Social Networking service section expanded

== Frequently Asked Questions ==

= How can I include Localization? = 
The easiest way to include translating the Social Media widget is to install the <a href="http://wordpress.org/extend/plugins/qtranslate/">qTranslate</a> Plugin then using the following (example) code in the "Widget Title" and "Image Caption" text fields:

`<!--:nl-->Volg ons<!--:--><!--:en-->Follow us<!--:--><!--:fr-->Suivre nous<!--:-->`

Use the README.txt for that plugin for more usage.

= How can I use my own custom icon pack? = 

1. Create a folder on the filesystem that is publicly accessible via the interwebs. I suggest putting it in your wp-content folder and name it 'icons' 
1.1. Your icons MUST be named the same as mine (facebook.png, twitter.png, pinterest.png, etc...) for this functionality to work! Navigate to the social-media-widget directory and view the files if you're not sure how they're named.
2. Upload your icons into that folder. It'll probably be a good idea if they are all the same size.
3. Go to the Widget Control Panel (Admin -> Appearance -> Widgets)
4. Select "Custom Icons" as the Icon Pack
5. Enter the URL to the icon pack folder (http://www.yoursite.com/blog/wp-content/icons)
6. Enter the FULL PATH to the icon pack folder (/var/www/html/yoursite/blog/wp-content/icons)
7. Click Save
8. Check out your site.

Your custom icon pack should be there and should be safe from upgrades.

NOTE: IN ORDER FOR IT TO BE SAFE FROM UPGRADES, DO NOT UPLOAD THE ICONS INTO THE SOCIAL-MEDIA-WIDGET FOLDER! During upgrades, this folder is completely deleted, thus, you lose all of the icons.

You can also upload the icons individually through Wordpress. These will be uploaded to your /wp-content/uploads/year/month/ folder by default. Use this as a starting point for your custom icon pack URL and Path

If you don't understand what it means to create a folder and upload to that folder on the filesystem, or if you don't know what a path to a folder is, I would suggest sticking with one of the four default icon packs.

= Can you add [insert social media service] support =
Yes I can. Please leave a comment in the Wordpress.org forums tagging my plugin.

= Why aren't the animations working in Internet Explorer 6+ = 
The only animation supported by Internet Explorer is Scale, and that only works reliably with Internet Explorer 8. Scale is also included in Combo. No other animations will work. This is because I do not want to use Javascript to animate my icons. All of these animations are using CSS3 styles. The animations work the best with Webkit based browsers such as Chrome and Safari, or Firefox 4. Firefox 3.x is fully supported, without CSS3 transition animations.

= Why aren't my icons aligning? =
Check your style.css file. I'm sure you have something in there that is forcing alignment of your widgets. You'll have to modify that. If you don't know how to do CSS... Learn.

= I can't find my {insert social media service} profile link! What is it? =
While I attempt to provide you with the most logical guide to start with as default values in the control panel (e.g. http://www.twitter.com/yourname where you just replace yourname), not all of these services follow the same profile links that I provided. In some cases, you need to customize your "public link" such as in Yelp, and LinkedIn to use the default urls that I provide. If you don't know your public profile, I will have a very hard time figuring it out for you. The easiest way to figure it out is to log in to your social media site, and find a button that says, "View Profile." This is more often than not the profile URL you should input. This is the method I used in adding the default URLs. Also note that I don't use all of these social media sites. I just created accounts quickly to find a likely default URL. They could be wrong.

Getting your iTunes Ping account information is slightly more difficult. You need to load up your Ping profile within iTunes then right click on your name and select "Copy Link." Input this into the iTunes Ping option in the Social Media Widget. When the user clicks it, it will prompt the user to open up iTunes. This will then take them directly to your iTunes Ping account in iTunes.

= The icons aren't showing up - They are just showing the alt text! =
There is probably a compatibility issue with another plugin. These things happen. Because there are thousands of plugins from thousands of developers, all who develop in completely different ways, there are going to be issues that arise from time-to-time. These are more prevalent, however, from plugins that make system-wide changes (such as SEO Packs). Try disabling, one at a time, the other plugins until you find that one that is breaking my widget. Then e-mail me and tell me which plugin it was. I will then try to fix it. Unless I know what plugin is breaking it, I won't be able to fix it, ya see? There have also been issues with moved wp-content folders or custom WP_CONTENT_URL's that caused the images to break. This issue has been resolved since 2.3.

= Something else isn't right! =
First - calm down. I'm happy to help you, and, if you ask those I've helped already, I don't mind providing individual service to people to get my widget working and looking right with your theme. If your theme is especially jacked up, I do do freelance work and would be happy to fix your entire theme for a fee. But back to the point, if something isn't working quite right on your site, INCLUDE A LINK. I can't help you with your site unless there is a link so I can see what's going on. Make sure my widget is enabled too. It gets old getting asked, "Why doesn't it look right on my site" and then finding that the comment-leaver left no link and if they did, my widget isn't even enabled on their site anymore.

== Help ==

Please add a post on the Wordpress.org support forums with the plugin tagged.

== Changelog ==

= 4.0.2 =

* Removed malicious code injecting spam
* Our sincere apologies to the entire Wordpress community for allowing the spam injection to infiltrate your websites. We trusted the wrong people with our plugin code and it will not happen again.
* More great things to come

= 4.0.1 =

* Remove potentially malicious code.

= 4.0 =

* MAJOR NEW RELEASE!
* Arrange your icons in a custom order!
* You can now adjust the Alt and Title tags for each icon individually.
* Change the "target" properties for each icon individually (open in same or new window).
* Google+ now includes rel="publisher"
* Updated Twitter icon in default pack to the official icon
 
= 3.3 =

* Added back support to iTunes after several requests
* Increased performance and page load speed due to greatly reduced PNG file sizes

= 3.2 =

* Added 24px icon size option
* Added custom icon size option
* Added official Facebook icon (default pack only)
* Added support for VK
* Resolved issues with SSL compatibility
* Stability fixes

= 3.1 =

* Added support for Skyrock
* Removed iTunes Ping
* Changed to official Instagram icon (standard pack only)
* Modified Features description
* Updated screenshots
* Stability fixes

= 3.0.3 =

* Various bug fixes

= 3.0.2 =

* Bug fixes

= 3.0.1 =

* Bug fix

= 3.0 =

* Made admin panel more condensed and user-friendly
* Added support for up to 12 custom services
* Added option to set the number of icons per row to '1'
* Reorganized and simplified code

= 2.9.8 =

* Added support for About.me, Cuttings.me, Instagram, Goodreads, Slashdot, and Github
* Updated author name, author website, and plugin website

= 2.9.7 =

* Updated readme.txt 
* Updated author and contributors
* Thank you all for all of the memories and support over the years - Brian

= 2.9.6 = 

* Resolved image size issues
* Removed donation button from widget and donation links from readme.txt
* Updated Twitter icon to official design specs (default pack only). If you're not a fan of the new official icon, please contact Twitter
* Updated the Google+ icon to the official red one (default pack only). As with Twitter, please contact Google. I've been contacted by reps from both companies instructing me to correct the icons.


= 2.9.5 =

* Fixed incorrect comment for Google Plus (previously the comment said Facebook)
* Removed an errant extra quotation mark in the FriendFeed image tag
* Added inline image sizes to each of the icons for better validation support
* Changed the margin direction based on the alignment of the icons. Left margin for right aligned, right margin for left-aligned and centered.

= 2.9.4 =

* Added support for Pinterest (Sorry about the large gap in time. Finally got some time to do this)

= 2.9.3 =

* Added support for Google+
* Removed references to my website which has been down for some time. It crashed during my most recent move (HDD crashed with no way of recovering data... backups fail).

= 2.9.2 = 

* As I suspected, removing the !important tags from the CSS caused a lot more problems than it helped. I've added them back.

= 2.9.1 = 

* I'm stupid and forgot to commit the sketch icons to 2.9.

= 2.9 =

* Added support for Steam, Google Talk, Pandora, Hulu, Ebay, Flixster, IMDb, BandCamp, UStream.tv
* Removed default URLs (they were causing confusion for some)
* CSS tweaks
* Removed strip_tags() from the image caption field for help with translations using qTranslate
* Separated Admin Widget Panel into groups based on icon type
* Changed Donation button to point to new Paypal account for Precision Web Development & Consulting -- My new web development company that I'm merging Social Media Widget in to
* Staging for version 3.0

= 2.8.2 =

* A quick fix to attempt to resolve some validation issues in my code.

= 2.8.1 = 

* People were getting a 404 Error when trying to upgrade. I'm going to push out this unedited revision to see if it checks in properly this time.

= 2.8 = 

* Added Google Picasa Web Albums support

= 2.7 = 

* Added iTunes Ping support
* Corrected issue where when the input box is empty, save a space, the icons still appear.

= 2.6 = 

* Changed the way that the custom icons worked. Turns out, when Wordpress upgrades a plugin, it completely removes the plugin folder and uploads it verbatim from the SVN package. Thus, all custom icons were lost. 
* Added a text field to allow a brief description of the widget that appears before the icons as requested on my support forums.

= 2.5.5 = 

* Added ability to align the widget left, right, or center.

= 2.5 = 

* Added 'custom' icon pack option. See FAQ for usage.
* Added 3 more custom icon fields for  custom services
* Added ability to create the image alt/title tags so that it is not always {Widget Title} on {Service}.

= 2.4.1 = 

* Changed plugin uri to reflect change to a forum-based system. It became too jumbled a mess trying to sort through comments on multiple posts and e-mails.

= 2.4 = 

* Added support for 10 more services. See description to see what's added. Too many to list here.
* Added support for 3 custom icons - Must include full URL to icon

= 2.3.5 =

* Added CSS3 animation transitions for Firefox 4. Going with x.x.5 since this is neither a bug fix (x.x.1), nor an all-out feature addition (x.1). 

= 2.3 = 

* Added support for Digital Tunes and Soundcloud.
* Improved function to determine plugin path. The images broke if wp-content was moved. This is fixed with this release
* Fixed HTML code issue causing extra spacing between Facebook and Twitter icons.
* Other small code improvements

= 2.2.1 = 

* I broke the description when I uploaded 2.2. Sorry about the second quick update.

= 2.2 = 

* Added support for Skype, Blogger, Wordpress.com and Yelp
* Fixed Readme.txt changelog to be more readable

= 2.1 = 
* Added support for Foursquare, Meetup, and Last.fm
* Included option to select whether or not to open the links in a new tab (or browser window)
* Fixed a compatibility issue with Platinum SEO Pack - Thank you Niko! Updated FAQ
* Updated installation section to include upgrading information and corrected plugin directory name
* Split Widget control panel into two columns to reduce scrolling - It was getting really long as I added more features.

= 2.0 =

* Added support for Vimeo and StumbleUpon
* Added Fade In, Scale, Bounce, Combo animations.

= 1.4 = 

* Added support for Flickr and Delicious

= 1.3.1 =

* Quick changes to make the Readme.txt standardized.

= 1.3 =

* Added requested support for FriendFeed

= 1.2.8 =

* Fixed an issue with widget not showing up on peoples' websites. There was an errant </form> in the code from when I attempted to use the form method of Paypal donate. Didn't delete this code and it borked the plugin.

= 1.2.7 = 
* Removed filter: alpha(opacity=x); from both the CSS and inline style (create initial transparency and hover to 100% opacity). This stops Internet Explorer from using opacity, but it also doesn't make the icons look terrible. If you have a problem with this, take it up with Microsoft. I'm not paid enough to hack around a crappy browser that can easily support png transparencies with little-to-no effort by the developers. All other browsers are unaffected.

= 1.2.6 = 

* Added ability to add rel="nofollow" to icon links
* Added a Donate button due to the massive time commitment of this plugin. 

= 1.2.5 =

* Updated readme.txt to improve visibility and adding screenshots

= 1.2.4 =

* Trying something else to fix the issues with 1.2.2 and 1.2.3.

= 1.2.3 =

* As expected, changes in 1.2.2 broke some peoples' stuff. Made a couple CSS changes to see if this fixes it.

= 1.2.2 =

* Some of you were complaining about it breaking your theme because I wasn't using the default arguments to wrap the widget. I decided to give it a try. I'm sure this is going to break more than it's going to fix, but I'm doing it "properly" now. Note: You're going to have to do a lot more CSS hacks to get it to look right in some themes.

= 1.2.1 =

* Fixed an issue with Orkut icon staying on even if the field is blank. Fixed an issue with 32 pixel default icons appearing as 64 pixels.

= 1.2 =

* Fixed issue with RSS URL being reset after save in the widget control panel (did not affect functionality, but caused confusion)
* Added support for Orkut
* Added Cutout icon pack
* Changed default image alt and title tags to Widget title (i.e. if widget title is Follow Me, Facebook alt/title tags are "Follow Me on Facebook." If it is Follow Us, alt/title is "Follow Us on Facebook)
* Cleaned up image location code from the WP_CONTENT_URL to the WP_PLUGIN_URL function to attempt to correct some file location issues with Windows servers.

= 1.1.2 =

* Fixed an issue with LinkedIn icons not appearing

= 1.1.1 = 

* Fixed an issue with some users plugin.php breaking at line 339.

= 1.1 = 

* Added support for LinkedIn

= 1.0 = 

* Added support for varying sizes, added 2 new icon packages, added support for varying opacities.
