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

use Closure;
use WPCFG\Vendor\Psr\Container\ContainerInterface;

/**
 * Abstract class AbstractHook.
 * Data transfer object that holds WordPress action information.
 */
abstract class AbstractHook
{
    /**
     * The number of arguments that should be passed to the $callback.
     *
     * @var int
     */
    protected $acceptedArgs;

    /**
     * The callback method name.
     *
     * @var string
     */
    protected $callbackMethod;

    /**
     * Identifier of the entry to look for from container.
     *
     * @var string
     */
    protected $classIdentifier;

    /**
     * The name of the WordPress hook that is being registered.
     *
     * @var string
     */
    protected $hook;

    /**
     * The priority at which the function should be fired.
     *
     * @var int
     */
    protected $priority;

    /**
     * Filter constructor.
     *
     * @param string   $classIdentifier Identifier of the entry to look for from container.
     * @param string   $hook            The name of the WordPress hook that is being registered.
     * @param string   $callbackMethod  The callback method name.
     * @param int|null $priority        Optional.The priority at which the function should be fired. Default is 10.
     * @param int|null $acceptedArgs    Optional. The number of arguments that should be passed to the $callback.
     *                                  Default is 1.
     */
    public function __construct(
        string $classIdentifier,
        string $hook,
        string $callbackMethod,
        int $priority = null,
        int $acceptedArgs = null
    ) {
        $this->classIdentifier = $classIdentifier;
        $this->hook = $hook;
        $this->callbackMethod = $callbackMethod;
        $this->priority = $priority ?? 10;
        $this->acceptedArgs = $acceptedArgs ?? 1;
    }

    /**
     * Callback closure getter.
     *
     * The actual callback that WordPress going to fire.
     *
     * @param ContainerInterface $container The container.
     *
     * @return Closure
     */
    abstract public function getCallbackClosure(ContainerInterface $container): Closure;

    /**
     * AcceptedArgs getter.
     *
     * @return int
     */
    public function getAcceptedArgs(): int
    {
        return $this->acceptedArgs;
    }

    /**
     * Hook getter.
     *
     * @return string
     */
    public function getHook(): string
    {
        return $this->hook;
    }

    /**
     * Priority getter.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
