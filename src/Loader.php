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

use InvalidArgumentException;
use WPCFG\Vendor\Psr\Container\ContainerInterface;

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
     * The WPCFG container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * The array of filters registered with WordPress.
     *
     * @var Filter[] $filters The filters registered with WordPress to fire when the plugin loads.
     */
    private $filters;

    /**
     * Initialize the collections used to maintain the actions and filters.
     *
     * @param ContainerInterface $container The plugin container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->actions   = [];
        $this->filters   = [];
    }

    /**
     * Add hooks from LoadableInterface[].
     *
     * @todo Check for LoadableInterface.
     *
     * @param array|string[] ...$classes Array of Loadable classes.
     *
     * @return void
     */
    public function load(string ...$classes)
    {
        foreach ($classes as $loadable) {
            $hooks = $loadable::getHooks();
            $this->add(...$hooks);
        }
    }

    /**
     * Add new hook to the collection to be registered with WordPress.
     *
     * @todo Refactor.
     *
     * @param array|AbstractHook[] ...$hooks Hooks to be registered.
     *                                       Expecting Filters or Actions.
     *
     * @return void
     * @throws InvalidArgumentException If $hooks are not made of Filters or Actions.
     */
    private function add(AbstractHook ...$hooks)
    {
        foreach ($hooks as $hook) {
            if ($hook instanceof Filter) {
                $this->filters[] = $hook;
                continue;
            }

            if ($hook instanceof Action) {
                $this->actions[] = $hook;
                continue;
            }

            $errorMessage = sprintf(
                'Hook must be one of %1$s or %2$s. However, %3$s is given',
                Filter::class,
                Action::class,
                get_class($hook)
            );
            throw new InvalidArgumentException($errorMessage);
        }
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
                $filter->getCallbackClosure($this->container),
                $filter->getPriority(),
                $filter->getAcceptedArgs()
            );
        }

        foreach ($this->actions as $action) {
            add_action(
                $action->getHook(),
                $action->getCallbackClosure($this->container),
                $action->getPriority(),
                $action->getAcceptedArgs()
            );
        }
    }
}
