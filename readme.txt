=== Plugin Name ===
Contributors: kherge
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=kherge%40gmail%2ecom&item_name=Simply%20Feed&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: rss, template
Requires at least: 2.5
Tested up to: 2.6.2
Stable tag: 0.2

This plugins simplifies embedding of RSS feeds within templates.

Requires PHP 5+

== Description ==

This plugins simplifies embedding of RSS feeds within templates.

This is accomplished by using WordPress's fetch_rss() function to retrieve RSS feeds and parse it.

The feed that was fetched will then be boiled down into a simple array that can easily be managed by a developer or theme designer.

The simply_feed() function, that does all this good stuff, can also be used to render the RSS directly to HTML.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `simply-feed.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place`<?php simply_feed( ); ?>` in your templates.

You will need to pass additional arguments to simply_feed( ) to retrieve and display your feed:

1. URL to RSS feed.
2. (optional) Render RSS items? true or false
3. (optional) How many items to render? 0 to infinity (default is 10)

If argument #2 is not set to true, then an array of parsed RSS items will be returned.

== Screenshots ==

1. Example on developer's website.
