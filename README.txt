=== WP Cloudflare Guard ===
Contributors: typisttech, tangrufus
Donate link: https://www.typist.tech/donate/wp-cloudflare-guard/
Tags: cloudflare, firewall, security, spam
Requires at least: 4.6
Tested up to: 4.7.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connecting WordPress with Cloudflare firewall, protect your WordPress site at DNS level. Automatically create firewall rules to block dangerous IPs.

== Description ==

Connecting WordPress with Cloudflare firewall, protect your WordPress site at DNS level. Automatically create firewall rules to block dangerous IPs.

= Features =

* Blacklist IP if attempt to login with bad usernames

= Integrations (Coming soon as add-ons) =

* [iThemes Security](https://www.typist.tech/go/ithemes-security/)
* [Contact Form 7](https://wordpress.org/plugins/contact-form-7/)
* [WordPress Zero Spam](https://wordpress.org/plugins/zero-spam)

= How does WP Cloudflare Guard different from Cloudflare's official plugin? =

At the time of writing, Cloudflare's [official plugin](https://wordpress.org/plugins/cloudflare/) doesn't block any IP for WordPress when other plugins discover dangerous activities. Here comes WPCFG! WPCFG focus on integrating other plugins with Cloudflare.

= Compatibility =

* Works with Cloudflare's [official plugin](https://wordpress.org/plugins/cloudflare/)
* Works with [Sunny (Purging CloudFlare caches for WordPress)](https://wordpress.org/plugins/sunny/)

= Things You Need to Know =

* You need PHP 7.0 or later
* You need a Cloudflare account (free plan is okay)
* This plugin was not built by [Cloudflare, Inc](https://www.cloudflare.com/)

> If you like the plugin, feel free to [rate it](https://wordpress.org/support/plugin/wp-cloudflare-guard/reviews/#new-post) or [donate via PayPal](https://www.typist.tech/donate/wp-cloudflare-guard/). Thanks a lot! :)

= For Bloggers =

If you have written an article about `WP Cloudflare Guard`, do [let me know](https://www.typist.tech/contact/). For any questions, shoot me an email at [info@typist.tech](mailto:info@typist.tech)

= For Developers =

WP Cloudflare Guard is open source and hosted on [GitHub](https://github.com/TypistTech/wp-cloudflare-guard). Feel free to make pull requests.

= Who make this plugin? =

[Tang Rufus](mailto:info@typist.tech), a freelance developer for hire.
I make [Typist Tech](https://www.typist.tech/) also.

= Support =

We hang out in the WordPress support forum for this plugin at [https://wordpress.org/support/plugin/wp-cloudflare-guard](https://wordpress.org/support/plugin/wp-cloudflare-guard). However, to save time so that we can spend it on development, please read the plugin's FAQs at [https://wordpress.org/plugins/wp-cloudflare-guard/faq/](https://wordpress.org/plugins/wp-cloudflare-guard/faq/). Before going there, and ensure that you have updated WP Cloudflare Guard and WordPress to the latest released version.

== Installation ==

= Via WordPress admin dashboard =

1. Log in to your site’s Dashboard (e.g. www.your-domain.com/wp-admin)
1. Click on the `Plugins` tab in the left panel, then click “Add New”
1. Search for `WP Cloudflare Guard` and the latest version will appear at the top of the list of results
1. Install it by clicking the `Install Now` link
1. When installation finishes, click `Activate Plugin`

= Via Manual Upload =

1. Download the plugin from [wordpress.org](https://downloads.wordpress.org/plugin/wp-cloudflare-guard.zip)
1. Unzip it
1. Upload it to `wp-content/plugins/`
1. Go to the WordPress plugin menu and activate it

== Frequently Asked Questions ==

= Is this plugin written by Cloudflare, Inc.? =

No.
This plugin is a [Typist Tech](https://www.typist.tech) project.

= Can I install WP Cloudflare Guard, Sunny and Cloudflare's official plugin at the same time? =

Yes, all of them work together without problems.

* Install [WP Cloudflare Guard](https://wordpress.org/plugins/wp-cloudflare-guard/) if you want to protect your site from bad IPs
* Install [Sunny](https://wordpress.org/plugins/sunny/) if you want to purge CloudFlare's cache automatically
* Install the [offical plugin](https://wordpress.org/plugins/cloudflare/) if you can't see the real IP from visitors

= What if WP Cloudflare Guard blacklisted my IP? =

1. Login [CloudFlare](http://cloudflare.com)
1. Select your domain
1. Go `Firewall`
1. Release you IP under `Access Rules`

= What version of PHP do I need? =

PHP 7 or later.

= How to get support? =

Use the WordPress support forum for this plugin at [https://wordpress.org/support/plugin/wp-cloudflare-guard](https://wordpress.org/support/plugin/wp-cloudflare-guard).

Make sure you have read the plugin's FAQs at [https://wordpress.org/plugins/wp-cloudflare-guard/faq/](https://wordpress.org/plugins/wp-cloudflare-guard/faq/). And, updated WP Cloudflare Guard and WordPress to the latest released version before asking questions.

= How can I support this plugin? =

If you like the plugin, feel free to [rate it](https://wordpress.org/support/plugin/wp-cloudflare-guard/reviews/#new-post) or [donate via PayPal](https://www.typist.tech/donate/wp-cloudflare-guard/). Thanks a lot! :)

Besides, `WP Cloudflare Guard` is open source and hosted on [GitHub](https://github.com/TypistTech/wp-cloudflare-guard). Feel free to make pull requests.

= What if I want more? =

Hire me!

Shoot me an email at [info@typist.tech](mailto:info@typist.tech) or use this [contact form](https://www.typist.tech/contact/).


== Screenshots ==

1. Cloudflare Settings
1. Bad Login Settings
1. Cloudflare Firewall Access Rules

== Changelog ==

Full change log available at [GitHub](https://github.com/TangRufus/wp-cloudflare-guard/blob/master/CHANGELOG.md)

= 0.1.0 =
* First release

== Upgrade Notice ==

= 0.1.0 =

* First release
