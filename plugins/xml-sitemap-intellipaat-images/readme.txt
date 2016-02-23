=== Plugin Name ===
Contributors: labnol
Tags: xml sitemaps, google sitemaps, bing, images, seo, search engines, sitemap, pictures
Requires at least: 2.9.2
Tested up to: 3.5
Stable tag: 2.1.3

The Image Sitemap plugin will generate an XML Sitemap for Images that exist in your WordPress blog. 

== Description ==

XML Sitemaps are a way to tell Google, Bing and other search engines about web pages, images and video content on your site that they may otherwise not discover. 

The Image Sitemap plugin will generate a sitemap for your WordPress blog with all the image URLs that are attached to your blog posts and WordPress pages. 

**Related WordPress plugins:**

* [Google XML Sitemap for Mobile](http://wordpress.org/extend/plugins/google-mobile-sitemap/)
* [Google XML Sitemap for Videos](http://wordpress.org/extend/plugins/xml-sitemaps-for-videos/)

**WordPress Resources:**

* [Must have WordPress Plug-ins](http://www.labnol.org/software/must-have-wordpress-plugins/14034/)
* [Improving WordPress Security](http://www.labnol.org/internet/improve-wordpress-security/24639/)

The Sitemap plugins are written by [Amit Agarwal](http://www.labnol.org/about/ "Amit Agarwal") of [Digital Inspiration](http://www.labnol.org/ "Tech Blog"). For support, you can leave a comment in the [WordPress forum](http://wordpress.org/support/plugin/google-image-sitemap) or send a tweet to [@labnol](http://twitter.com/labnol).

== Installation ==

Here's how you can install the plugin:

1. Upload the plugins folder to the /wp-content/plugins/ directory.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Expand the Tools menu from WordPress dashboard sidebar and select "Image Sitemap."
1. Click the "Generate Sitemap" button to create your XML Sitemap for mobile.
1. Once you have created your Sitemap, you can submit it to Google using Webmaster Tools. 

== Changelog ==
[v2.1.3] Minor fix related to permalinks that could be empty. Thanks Andrea Gianfreda for the tip.

[v2.1.2] Compatible with WordPress 3.5

[v2.1] Data values (including URLs) now use entity escape codes for certain characters.

[v2.0] The plugin has been significantly improved and now includes all images including photo galleries, direct embeds and media attachments.

== Frequently Asked Questions ==

= How can I submit my image sitemap to Google? =

Once you have created your Sitemap, you can submit it to Google using Webmaster Tools. 

= Where's the sitemap file stored? =

You can find the sitemap-image.xml file in your blog's root folder.

= I am getting Permission Denied like errors =

It implies that you don't have write permissions on your blog's root folder. Please use chmod or your FTP manager to set the necessary permissions to 0666.

== Screenshots ==

1. Click the button to generate your mobile sitemap.