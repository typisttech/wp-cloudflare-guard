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

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */
class Loader
{
    /**
     * The array of actions registered with WordPress.
     *
     * @var Action[] $actions The actions registered with WordPress to fire when the plugin loads.
     */
    private $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @var Filter[] $filters The filters registered with WordPress to fire when the plugin loads.
     */
    private $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     */
    public function __construct()
    {
        $this->actions = [];
        $this->filters = [];
    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @param Action $action The action data that is being registered.
     *
     * @return void
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param Filter $filter The filter data that is being registered.
     *
     * @return void
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->filters as $filter) {
            add_filter(
                $filter->getHook(),
                $filter->getCallbackClosure(),
                $filter->getPriority(),
                $filter->getAcceptedArgs()
            );
        }

        foreach ($this->actions as $action) {
            add_action(
                $action->getHook(),
                $action->getCallbackClosure(),
                $action->getPriority(),
                $action->getAcceptedArgs()
            );
        }
    }
}