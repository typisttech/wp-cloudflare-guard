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
     * @var array $actions The actions registered with WordPress to fire when the plugin loads.
     */
    private $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @var array $filters The filters registered with WordPress to fire when the plugin loads.
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
     * @param string $hook         The name of the WordPress action that is being registered.
     * @param mixed  $component    A reference to the instance of the object on which the action is defined.
     * @param string $callback     The name of the function definition on the $component.
     * @param int    $priority     Optional. he priority at which the function should be fired. Default is 10.
     * @param int    $acceptedArgs Optional. The number of arguments that should be passed to the $callback.
     *                             Default is 1.
     *
     * @return void
     */
    public function addAction($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $acceptedArgs);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @param array  $hooks        The collection of hooks that is being registered (that is, actions or filters).
     * @param string $hook         The name of the WordPress filter that is being registered.
     * @param mixed  $component    A reference to the instance of the object on which the filter is defined.
     * @param string $callback     The name of the function definition on the $component.
     * @param int    $priority     The priority at which the function should be fired.
     * @param int    $acceptedArgs The number of arguments that should be passed to the $callback.
     *
     * @return array The collection of actions and filters registered with WordPress.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $acceptedArgs)
    {
        $hooks[] = [
            'hook'         => $hook,
            'component'    => $component,
            'callback'     => $callback,
            'priority'     => $priority,
            'acceptedArgs' => $acceptedArgs,
        ];

        return $hooks;
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @param string $hook         The name of the WordPress filter that is being registered.
     * @param mixed  $component    A reference to the instance of the object on which the filter is defined.
     * @param string $callback     The name of the function definition on the $component.
     * @param int    $priority     Optional. he priority at which the function should be fired. Default is 10.
     * @param int    $acceptedArgs Optional. The number of arguments that should be
     *                             passed to the $callback. Default is 1.
     *
     * @return void
     */
    public function addFilter($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $acceptedArgs);
    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], [
                $hook['component'],
                $hook['callback'],
            ], $hook['priority'], $hook['acceptedArgs']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], [
                $hook['component'],
                $hook['callback'],
            ], $hook['priority'], $hook['acceptedArgs']);
        }
    }
}
