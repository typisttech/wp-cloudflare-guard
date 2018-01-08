=== WP Cloudflare Guard ===
Contributors: typisttech, tangrufus
Donate link: https://www.typist.tech/donate/wp-cloudflare-guard/
Tags: cloudflare, firewall, security, spam
Requires at least: 4.8.5
Requires PHP: 7.1.0
Tested up to: 4.9
Stable tag: 0.2.0
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
* You need WordPress 4.7 or later
* You need a Cloudflare account (free plan is okay)
* This plugin was not built by [Cloudflare, Inc](https://www.cloudflare.com/)

> If you like the plugin, feel free to [rate it](https://wordpress.org/support/plugin/wp-cloudflare-guard/reviews/#new-post) or [donate via PayPal](https://www.typist.tech/donate/wp-cloudflare-guard/). Thanks a lot! :)

= For Bloggers =

If you have written an article about `WP Cloudflare Guard`, do [let me know](https://www.typist.tech/contact/). For any questions, shoot me an email at [info@typist.tech](mailto:info@typist.tech)

= For Developers =

WP Cloudflare Guard is open source and hosted on [GitHub](https://github.com/TypistTech/wp-cloudflare-guard). Feel free to make [pull requests](https://github.com/Typisttech/wp-cloudflare-guard/pulls).

= Who make this plugin? =

[Tang Rufus](mailto:info@typist.tech), a freelance developer for hire.
I make [Typist Tech](https://www.typist.tech/) also.

= Support =

To save time so that we can spend it on development, please read the plugin's [FAQs](https://wordpress.org/plugins/wp-cloudflare-guard/faq/) first.
Before requesting support, and ensure that you have updated WP Cloudflare Guard and WordPress to the latest released version and installed PHP 7 or later.

We hang out in the WordPress [support forum](https://wordpress.org/support/plugin/wp-cloudflare-guard) for this plugin.

If you know what `GitHub` is, use [GitHub issues](https://github.com/Typisttech/wp-cloudflare-guard/issues) instead.

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

= Via WP CLI =

1. `$ wp plugin install wp-cloudflare-guard --activate`

== Frequently Asked Questions ==

= What version of PHP do I need? =

PHP 7 or later.

= Is this plugin written by Cloudflare, Inc.? =

No.
This plugin is a [Typist Tech](https://www.typist.tech) project.

= Can I install WP Cloudflare Guard, Sunny and Cloudflare's official plugin at the same time? =

Yes, all of them work together without problems.

* Install [WP Cloudflare Guard](https://wordpress.org/plugins/wp-cloudflare-guard/) if you want to protect your site from bad IPs
* Install [Sunny](https://wordpress.org/plugins/sunny/) if you want to purge CloudFlare's cache automatically
* Install the [official plugin](https://wordpress.org/plugins/cloudflare/) if you can't see the real IP from visitors

= What if WP Cloudflare Guard blacklisted my IP? =

1. Login [CloudFlare](http://cloudflare.com)
1. Select your domain
1. Go `Firewall`
1. Release your IP under `Access Rules`

= Does this plugin available in my language? =

English works out of the box.

Traditional Chinese language pack is available [here](https://translate.wordpress.org/projects/wp-plugins/wp-cloudflare-guard/language-packs).

You can add your own translation at [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/wp-cloudflare-guard).

= How to get support? =

Use the WordPress support forum for this plugin at [https://wordpress.org/support/plugin/wp-cloudflare-guard](https://wordpress.org/support/plugin/wp-cloudflare-guard).

Make sure you have read the plugin's FAQs at [https://wordpress.org/plugins/wp-cloudflare-guard/faq/](https://wordpress.org/plugins/wp-cloudflare-guard/faq/). And, updated WP Cloudflare Guard and WordPress to the latest released version before asking questions.

If you know what `GitHub` is, use [GitHub issues](https://github.com/Typisttech/wp-cloudflare-guard/issues) instead.

= How can I support this plugin? =

If you like the plugin, feel free to:

* Give us a 5-star review on [WordPress.org](https://wordpress.org/support/plugin/wp-cloudflare-guard/reviews/#new-post)
* Donate via [PayPal](https://www.typist.tech/donate/wp-cloudflare-guard/). Thanks a lot! :)
* Translate it at [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/wp-cloudflare-guard).

Besides, `WP Cloudflare Guard` is open source and hosted on [GitHub](https://github.com/TypistTech/wp-cloudflare-guard). Feel free to make pull requests.

Last but not least, you can hire me. Shoot me an email at [info@typist.tech](mailto:info@typist.tech) or use this [contact form](https://www.typist.tech/contact/).

= What if I want more? =

Hire me!

Shoot me an email at [info@typist.tech](mailto:info@typist.tech) or use this [contact form](https://www.typist.tech/contact/).


== Screenshots ==

1. Cloudflare Settings
1. Bad Login Settings
1. Cloudflare Firewall Access Rules

== Changelog ==

Full change log available at [GitHub](https://github.com/TangRufus/wp-cloudflare-guard/blob/master/CHANGELOG.md)

= 0.2.0 =

* Code refactor

= 0.1.3 =

* Add yoast i18n module
* Fix PHP undefined notices

= 0.1.2 =

* Better translation support

= 0.1.1 =

* Better translation support

= 0.1.0 =
* First release

== Upgrade Notice ==

= 0.2.0 =

* You have to re-enter Cloudflare settings.
