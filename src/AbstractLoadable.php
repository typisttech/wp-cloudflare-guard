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
 * Abstract Class AbstractLoadable
 */
abstract class AbstractLoadable
{
    /**
     * Actions getter.
     *
     * @return Action[]
     */
    public static function getActions(): array
    {
        return [];
    }

    /**
     * Filters getter.
     *
     * @return Filter[]
     */
    public static function getFilters(): array
    {
        return [];
    }
}
