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

namespace WPCFG;

use WPCFG\Ads\I18nPromoter;
use WPCFG\BadLogin\Admin as BadLoginAdmin;
use WPCFG\BadLogin\BadLogin;
use WPCFG\Blacklist\Event;
use WPCFG\Blacklist\Handler;
use WPCFG\Cloudflare\AccessRules;
use WPCFG\Cloudflare\Admin as CloudflareAdmin;
use WPCFG\Cloudflare\IpUtil;
use WPCFG\Vendor\League\Container\Container as LeagueContainer;
use WPCFG\Vendor\League\Container\ReflectionContainer;
use WPCFG\Vendor\Yoast_I18n_WordPressOrg_v2;

/**
 * Class Container.
 */
class Container extends LeagueContainer
{
    /**
     * Initialize container.
     *
     * @return void
     */
    public function initialize()
    {
        $this->delegate(new ReflectionContainer);
        $this->add(self::class, $this);

        $this->share('\\' . Admin::class);

        $keys = [
            AccessRules::class,
            BadLogin::class,
            I18nPromoter::class,
            IpUtil::class,
            Handler::class,
            BadLoginAdmin::class,
            CloudflareAdmin::class,
            I18n::class,
            OptionStore::class,
        ];
        foreach ($keys as $key) {
            $this->add('\\' . $key);
        }

        $this->add(Yoast_I18n_WordPressOrg_v2::class, function (array $args) {
            return new Yoast_I18n_WordPressOrg_v2($args);
        });

        $this->add(Event::class, function (string $ip, string $note) {
            return new Event($ip, $note);
        });

        $this->add('blacklist-event-for-current-ip', function (string $note) {
            $ip = $this->call([ IpUtil::class, 'getCurrentIp' ]);

            return $this->get(Event::class, [ $ip, $note ]);
        });
    }
}
