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
use TypistTech\WPCFG\Cloudflare\Admin as CloudflareAdmin;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Loader;

/**
 * Final class WPCFG
 *
 * The core plugin class.
 */
final class WPCFG implements LoadableInterface
{
    /**
     * The dependency injection container.
     *
     * @var Container
     */
    private $container;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var Loader Maintains and registers all hooks for the plugin.
     */
    private $loader;

    /**
     * WPCFG constructor.
     */
    public function __construct()
    {
        $this->container = new Container;
        $this->loader = new Loader($this->container);

        $this->container->initialize();

        $loadables = [
            __CLASS__,
            Admin::class,
            BadLogin::class,
            BadLoginAdmin::class,
            Handler::class,
            CloudflareAdmin::class,
            I18n::class,
            I18nPromoter::class,
            ReviewNotice::class,
        ];

        foreach ($loadables as $loadable) {
            /* @var LoadableInterface $loadable */
            $this->loader->add(...$loadable::getHooks());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action(__CLASS__, 'plugin_loaded', 'giveContainer'),
        ];
    }

    /**
     * Expose Container via WordPress action.
     *
     * @return void
     */
    public function giveContainer()
    {
        do_action('wpcfg_get_container', $this->container);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @return void
     */
    public function run()
    {
        $this->loader->run();
    }
}
