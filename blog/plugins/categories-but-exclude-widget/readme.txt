=== Categories but exclude ===
Contributors: Poselab
Donate link: http://poselab.com/
Tags: widget, exclude, category, categories
Requires at least: 2.8
Tested up to: 3.3.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays all categories except those selected in widget preferences.

== Description ==

A simple plugin that displays a list of all categories except those selected in widget preferences. Unlike other similar plugins like "Almost All Categories Widget", Categories but exclude uses the latest version of Wordpress Widget API, which is multi-widget, which lets you use the wigdet more than once. Mostly, I created the plugin to use it with the plugin "WPML" along with "Widget Logic" to use the same widget for each language.

This will allow you to create a custom category list which will show the categories you want, and will hide the categories you do not want. For example, say you have a particular category you don't want to be displayed named "Announcements". You can use this plugin to display all of your other categories, except the category "Announcements". You are not limited to just excluding one category; you can exclude multiple categories.

Languages:

* Spanish (es_ES) - [PoseLab](http://poselab.com/)
* French (fr_FR) - [TradPress](http://www.tradpress.fr/)

If you have created your own language pack, or have an update of an existing one, you can [send me](mailto:javierpose@gmail.com) your gettext PO and MO so that I can bundle it into the Categories but exclude. You can download the latest POT file [from here](http://plugins.trac.wordpress.org/browser/categories-but-exclude-widget/trunk/categories-but-exclude.pot).


== Installation ==

1. Upload the *.zip copy of this plugin into your WordPress through your 'Plugin' admin page.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place the widget in your desired sidebar through the "widgets" admin page

== Frequently Asked Questions ==

= Where is the “widgets” admin page? =

The “widgets” admin page is found in the administrator part (wp-admin) of your WordPress site. Go to Appearance > Widgets

= How do I find the category ID number? =

First go to your Posts > Categories in the administrator part (wp-admin) of your WordPress site. Click the category you desire to find its ID. Its ID number should then be displayed in the URL section of your browser. (It should say $tag_ID= followed by your category ID number.

== Screenshots ==

1. Categories but exclude widget.

== Changelog ==

= 1.0 =
* Initial Release