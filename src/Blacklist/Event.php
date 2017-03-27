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

namespace WPCFG\Blacklist;

/**
 * Final class Event.
 *
 * Immutable data transfer object.
 */
final class Event
{
    /**
     * IP address to be blacklisted.
     *
     * @var string
     */
    private $ipAddress;

    /**
     * Note of this event.
     *
     * @var string
     */
    private $note;

    /**
     * Event constructor.
     *
     * @param string $ipAddress IP address to be blacklisted.
     * @param string $note      Note of this event.
     */
    public function __construct(string $ipAddress, string $note)
    {
        $this->ipAddress = $ipAddress;
        $this->note      = $note;
    }

    /**
     * Ip address getter.
     *
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * Note getter.
     *
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }
}
