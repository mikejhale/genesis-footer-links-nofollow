=== Genesis Footer Links Nofollow ===
Contributors: MikeHale, GaryJ
Tags: genesis, genesiswp, genesis framework, footer, nofollow, seo, commencia
Requires at least: 3.2
Tested up to: 4.6
Stable tag: 0.3.1
License: GPLv2 or later
License URI: http://www.opensource.org/licenses/gpl-license.php

Plugin makes all or specified links in the footer rel=nofollow.  Use of Genesis Theme Framework is required.

== Description ==

This plugin makes all or selected links in the footer of Genesis child themes `rel=nofollow` for SEO benefits. (This does not include Footer Widgets). Optionally, you can exclude footer links on the home page from being set as "nofollow". Footer links that  appear on all pages of a site may be considered as unnatural or spammy by the search engines and will devalue those links.

*This plugin requires the Genesis Theme Framework.*

= Feedback =
* I am open to suggestions and feedback!
* Get in touch with me at [@MikeHale](https://twitter.com/MikeHale) on Twitter
* Or follow me on [+Mike Hale](http://plus.google.com/+MikeHale) on Google Plus.

== Installation ==

1. Upload the entire `genesis-footer-links-nofollow` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to `Settings > Footer Links` to set plugin options

== Frequently Asked Questions ==

= Why should I set footer links as nofollow? =
Google may consider site-wide links as spammy.

= My theme uses Footer Widgets. Will they be affected? =
No. This only changes the links in the Site Footer (Where you'll usually find the copyright)

= Can I apply this to certain domains only? =
Yes. Just enter a comma seperated list of domains to include:

`google.com,yoursite.com`

== Changelog ==

= 0.3.1 =
Code Refactoring by [garyj](http://profiles.wordpress.org/garyj/)

= 0.2 =
Added ability to specify nofollow links by domain.
Replaced Regular Expression matching logic

= 0.1 =
Initial Release
