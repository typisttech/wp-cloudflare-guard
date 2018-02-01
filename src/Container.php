<?php
/**
 * WP CloudFlare Guard
 *
 * Connecting WordPress with Cloudflare firewall,
 * protect your WordPress site at DNS level.
 * Automatically create firewall rules to block dangerous IPs.
 *
 * @package   WPCFG
 *
 * @author    Typist Tech <wp-cloudflare-guard@typist.tech>
 * @copyright 2017 Typist Tech
 * @license   GPL-2.0+
 *
 * @see       https://www.typist.tech/projects/wp-cloudflare-guard
 * @see       https://wordpress.org/plugins/wp-cloudflare-guard/
 */

declare(strict_types=1);

namespace TypistTech\WPCFG;

use TypistTech\WPCFG\Ads\I18nPromoter;
use TypistTech\WPCFG\Ads\ReviewNotice;
use TypistTech\WPCFG\BadLogin\Admin as BadLoginAdmin;
use TypistTech\WPCFG\BadLogin\BadLogin;
use TypistTech\WPCFG\Blacklist\Handler;
use TypistTech\WPCFG\Cloudflare\AccessRules;
use TypistTech\WPCFG\Cloudflare\Admin as CloudflareAdmin;
use TypistTech\WPCFG\Vendor\League\Container\Container as LeagueContainer;
use TypistTech\WPCFG\Vendor\League\Container\ReflectionContainer;

/**
 * Final class Container.
 */
final class Container extends LeagueContainer
{
    /**
     * Initialize container.
     *
     * @return void
     */
    public function initialize()
    {
        $this->delegate(new ReflectionContainer());
        $this->add(self::class, $this);

        $optionStore = new OptionStore();
        $admin = new Admin($optionStore);
        $this->add('\\' . OptionStore::class, $optionStore);
        $this->add('\\' . Admin::class, $admin);

        $keys = [
            AccessRules::class,
            BadLogin::class,
            BadLoginAdmin::class,
            CloudflareAdmin::class,
            Handler::class,
            I18n::class,
            I18nPromoter::class,
            ReviewNotice::class,
        ];
        foreach ($keys as $key) {
            $this->add('\\' . $key);
        }
    }
}
