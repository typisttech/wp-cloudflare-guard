=== WP Cloudflare Guard ===
Contributors: tangrufus, typisttech
Tags: cloudflare, firewall, security, spam
Requires at least: 4.0
Tested up to: 4.7
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connecting WordPress with Cloudflare firewall, protect your WordPress site at DNS level. Automatically create firewall rules to block dangerous IPs.

== Description ==
= Features =

* Integrate with [iThemes Security](https://wordpress.org/plugins/better-wp-security/), [Contact Form 7](https://wordpress.org/plugins/contact-form-7/) and [WordPress Zero Spam](https://wordpress.org/plugins/zero-spam)
* Blacklist IP if attempt to login with bad usernames

= How does WPCFG different from Cloudflare's official plugin? =

At the time of writing, Cloudflare's [official plugin](https://wordpress.org/plugins/cloudflare/) doesn't block any IP for WordPress when other plugins discover dangerous activities. Here comes WPCFG! WPCFG focus on integrating other plugins with Cloudflare.

= Things you need to know =

* You need a Cloudflare account (free plan is okay).
* This plugin was not built by Cloudflare.

If you have written an article about `WPCFG`, do [let me know](https://www.typist.tech/contact-us/).


= Who make this plugin? =

[Tang Rufus](mailto://tangrufus@gmail.com), a freelance developer for hire.
I make [Typist Tech](https://www.typist.tech/) also.

= Requirement =
* PHP 7.0 or later

== Installation ==
1. Download the plugin.
1. Go to the WordPress Plugin menu and activate it.
1. On the admin menu, go to "WP Cloudflare Guard"
1. Fill in your Cloudflare account info
1. That's it!

== Changelog ==
= 1.0.0 =
* Initial release
