=== Simple User Registration ===
Contributors: nmedia
Donate link: http://www.najeebmedia.com/donate/
Tags: registration form, simple registration, drag drop fields, front end registration, wp registration, signup form, wp signup form, ajax wp form
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allow admin to create user signup form to render on front end using drag and drop easy admin panel

== Description ==
This plugin render wordpress signup form based on fields selected by Admin. Input fields can be placed in registration form using nice drag and drop meta page.
This plugin is also a best combination when use with N-Media other form plugins like:
[File uploader pro](https://wordpress.org/plugins/nmedia-user-file-uploader/)
[Front end repositor manager](https://wordpress.org/plugins/wp-front-end-repository/)
[Member private conversation](https://wordpress.org/plugins/wordpress-member-private-conversation/)
[File sharing plugin](http://najeebmedia.com/n-media-file-sharing-with-ultimate-security/)


= How to use =
* Just create a page and place this shortcode: `[nm-wp-registration]`
* Then setup form fields and other settins from `Admin -> WP Registration` described below section
* Custom login page can also set with shortcode `[nm-wp-login]`

= How it works =
[vimeo http://vimeo.com/112390753]

Plugin Settings
= Basic Settings Tab =
* Signup form title (`it will render as Registration form title if requried`);
* Signup form description  (`it will render description under title if required`)
* Terms and conditions (`nice feature to add a checkbox with provided text to accept before submit`)
* Success message (`this message will displayed when form submitted successfully`)
* Redirects URL (`after registration and login redirect url can be set`)
* Validation message (`display a message when one or more required field(s) not provided`)
* Singup button title
* Signup button text color
* Signup button text font size
* Signup button BG color
* Signup button class
* Form custom CSS editor
* Set email contents for signup completion (`%USERNAME%`, `%USER_PASSWORD%`, `%SITE_NAME%`)
* Set signup form input fields using drag drop editor



= Pro Features =
* Autocomplete, multi select input
* Avartar support
* Edit profile
* Change password form
* Member Direclty
* Login with Facebook
* Access to Support Forum
[Visite for more detail](http://najeebmedia.com/wordpress-plugin/wp-user-registration-form/)


== Installation ==

1. Upload plugin directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. After activation, you can set options from `Comment Fields` menu

== Frequently Asked Questions ==
= How to use =
* Just create a page and place this shortcode: `[nm-wp-registration]`
* Then setup form fields and other settins from `Admin -> WP Registration` described below section

= Where all fields are saved? =
* All fields are saved as User meta and can be seen under profile


== Screenshots ==

1. Admin screen for plugin option page
2. Signup form meta fields in plugin option
3. Front end form
4. Profile fileds in admin


== Changelog ==
= 1.4 =
* Page access restriction for members only
* Page access restriction by Role (PRO Feature)
= 1.3 =
* text localized for translation in login form
* some changes in settings
= 1.2 =
* When user is logged then Registration page will not render the signup form but say 'Hi username'
* When user is logged then Registration page will not render the login form but say 'Hi username'
= 1.1 =
* Email subject is changed
* Email is being sent with admin email and Site title as email header
= 1.0 =
* It is first version, and working perfectly

== Upgrade Notice ==
* Make sure you provide data name field for each input field