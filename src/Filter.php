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

use Closure;

/**
 * Class Filter
 *
 * Data transfer object that holds WordPress filter information.
 */
class Filter
{
    /**
     * The number of arguments that should be passed to the $callback.
     *
     * @var integer
     */
    private $acceptedArgs;

    /**
     * The actual callback that WordPress going to fire.
     *
     * @var Closure
     */
    private $callbackClosure;

    /**
     * The callback method name.
     *
     * @var string
     */
    private $callbackMethod;

    /**
     * The name of the WordPress hook that is being registered.
     *
     * @var string
     */
    private $hook;

    /**
     * The priority at which the function should be fired.
     *
     * @var integer
     */
    private $priority;

    /**
     * Filter constructor.
     *
     * @param string  $hook           The name of the WordPress hook that is being registered.
     * @param string  $callbackMethod The callback method name.
     * @param integer $priority       The priority at which the function should be fired. Default is 10.
     * @param integer $acceptedArgs   Optional. The number of arguments that should be passed to the $callback.
     *                                Default is 1.
     */
    public function __construct(string $hook, string $callbackMethod, int $priority = null, int $acceptedArgs = null)
    {
        $this->hook           = $hook;
        $this->callbackMethod = $callbackMethod;
        $this->priority       = $priority ?? 10;
        $this->acceptedArgs   = $acceptedArgs ?? 1;
    }

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
     * Callback closure getter.
     *
     * @return Closure
     */
    public function getCallbackClosure(): Closure
    {
        return $this->callbackClosure;
    }

    /**
     * Callback closure setter.
     *
     * @param Closure $callbackClosure The actual callback that WordPress going to fire.
     */
    public function setCallbackClosure(Closure $callbackClosure)
    {
        $this->callbackClosure = $callbackClosure;
    }

    /**
     * Callback getter.
     *
     * @return string
     */
    public function getCallbackMethod(): string
    {
        return $this->callbackMethod;
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
