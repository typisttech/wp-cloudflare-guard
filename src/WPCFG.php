<?php
/**
 * WP CloudFlare Guard
 *
 * Connecting WordPress with Cloudflare firewall,
 * protect your WordPress site at DNS level.
 * Automatically create firewall rules to block dangerous IPs.
 *
 * @package   WPCFG
 * @author    Typist Tech <wp-cloudflare-guard@typist.tech>
 * @copyright 2017 Typist Tech
 * @license   GPL-2.0+
 * @see       https://www.typist.tech/projects/wp-cloudflare-guard
 * @see       https://wordpress.org/plugins/wp-cloudflare-guard/
 */

declare(strict_types=1);

namespace WPCFG;

use WPCFG\Ads\I18nPromoter;
use WPCFG\BadLogin\Admin as Bad_Login_Admin;
use WPCFG\BadLogin\BadLogin;
use WPCFG\Blacklist\Handler;
use WPCFG\Cloudflare\Admin as Cloudflare_Admin;

/**
 * Final class WPCFG
 *
 * The core plugin class.
 */
final class WPCFG
{
    /**
     * The WPCFG admin.
     *
     * @var Admin
     */
    private $admin;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var Loader $loader Maintains and registers all hooks for the plugin.
     */
    private $loader;

    /**
     * The WPCFG option store.
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * WPCFG constructor.
     */
    public function __construct()
    {
        $this->loader      = new Loader;
        $this->optionStore = new OptionStore;
        $this->admin       = Admin::register($this->loader, $this->optionStore);

        $this->registerDependencies();
    }

    /**
     * Register the required dependencies for this plugin.
     *
     * @return void
     */
    private function registerDependencies()
    {
        $modules = [
            BadLogin::class,
            Bad_Login_Admin::class,
            Handler::class,
            Cloudflare_Admin::class,
            I18n::class,
            I18nPromoter::class,
        ];

        array_walk($modules, [ $this, 'registerModule' ]);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @return  void
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * Register core module
     *
     * @param mixed $module The module class name.
     *
     * @return void
     */
    private function registerModule($module)
    {
        $module::register($this->loader, $this->optionStore, $this->admin);
    }
}
