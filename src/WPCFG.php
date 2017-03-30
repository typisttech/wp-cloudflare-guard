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
use WPCFG\BadLogin\Admin as BadLoginAdmin;
use WPCFG\BadLogin\BadLogin;
use WPCFG\Blacklist\Event;
use WPCFG\Blacklist\Handler;
use WPCFG\Cloudflare\Admin as CloudflareAdmin;
use WPCFG\Cloudflare\IpUtil;
use WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules;

/**
 * Final class WPCFG
 *
 * The core plugin class.
 */
final class WPCFG
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
     * @var Loader $loader Maintains and registers all hooks for the plugin.
     */
    private $loader;

    /**
     * WPCFG constructor.
     */
    public function __construct()
    {
        $this->loader    = new Loader;
        $this->container = new Container;

        $this->initializeContainer();
        $this->initializeLoadables();
    }

    /**
     * Initialize container.
     *
     * @return void
     */
    private function initializeContainer()
    {
        $this->container->initialize();

        $shares = [
            OptionStore::class,
            Admin::class,
            BadLogin::class,
            BadLoginAdmin::class,
            Handler::class,
            CloudflareAdmin::class,
            I18n::class,
        ];
        foreach ($shares as $share) {
            $this->container->share('\\' .$share);
        }

        $addes = [
            Event::class,
            IpUtil::class,
            AccessRules::class,
        ];
        foreach ($addes as $add) {
            $this->container->add('\\' .$add);
        }
    }

    /**
     * Initialize Loadables.
     *
     * Add loadables to container and register their action and filter hooks.
     *
     * @return void
     */
    private function initializeLoadables()
    {
        $loadables = [
            Admin::class,
            BadLogin::class,
            BadLoginAdmin::class,
            Handler::class,
            CloudflareAdmin::class,
            I18n::class,
            I18nPromoter::class,
        ];

        foreach ($loadables as $loadable) {
            $actions = $loadable::getActions();
            array_walk($actions, [ $this, 'addActions' ], $loadable);

            $filters = $loadable::getFilters();
            array_walk($filters, [ $this, 'addFilters' ], $loadable);
        }
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
     * Register action hooks.
     *
     * @param Action $action   Data transfer object that holds action hook information.
     * @param mixed  $key      Unused.
     * @param string $loadable Identifier of the entry to look for inside container.
     *
     * @return void
     */
    private function addActions(Action $action, $key, string $loadable)
    {
        $callbackClosure = function (...$args) use ($loadable, $action) {
            $instance = $this->container->get($loadable);
            $instance->{$action->getCallbackMethod()}(...$args);
        };
        $action->setCallbackClosure($callbackClosure);
        $this->loader->addAction($action);
    }

    /**
     * Register filter hooks.
     *
     * @param Filter $filter   Data transfer object that holds filter hook information.
     * @param mixed  $key      Unused.
     * @param string $loadable Identifier of the entry to look for inside container.
     *
     * @return void
     */
    private function addFilters(Filter $filter, $key, string $loadable)
    {
        $callbackClosure = function (...$args) use ($loadable, $filter) {
            $instance = $this->container->get($loadable);

            return $instance->{$filter->getCallbackMethod()}(...$args);
        };
        $filter->setCallbackClosure($callbackClosure);
        $this->loader->addFilter($filter);
    }

    /**
     * Container getter.
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}
