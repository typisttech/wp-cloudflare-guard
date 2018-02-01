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

namespace TypistTech\WPCFG\Cloudflare;

use TypistTech\WPCFG\Vendor\CloudFlare\IpRewrite;

/**
 * Final class Helper
 */
final class Helper
{
    /**
     * CloudFlare's IpRewrite instance
     *
     * @var IpRewrite
     */
    private $ipRewrite;

    /**
     * Helper constructor.
     *
     * Side effect: If current request is coming through Cloudflare,
     * $_SERVER["REMOTE_ADDR"] will be rewritten to reflect the end-user's
     * IP address.
     */
    public function __construct()
    {
        $this->ipRewrite = new IpRewrite();
    }

    /**
     * Retrieve the real ip address of the user in the current request.
     *
     * @return string IP Address or empty string on error
     */
    public function getCurrentIp(): string
    {
        $rewrittenIp = $this->getRewrittenIp();

        if ('' !== $rewrittenIp) {
            return $rewrittenIp;
        }

        return $this->getOriginalIp();
    }

    /**
     * Gets the re-written IP after IpRewrite::rewrite() is run.
     *
     * IpRewrite::getRewrittenIP returns $_SERVER['HTTP_CF_CONNECTING_IP']
     * without sanitization, so treat them as superglobal usage
     * as per WordPress coding standard principle.
     *
     * @return string IP Address or empty string on error
     */
    private function getRewrittenIp(): string
    {
        return sanitize_text_field(
            wp_unslash(
                $this->ipRewrite->getRewrittenIP()
            )
        );
    }

    /**
     * Get the original IP Address of a given request.
     *
     * IpRewrite::getOriginalIP returns $_SERVER['REMOTE_ADDR']
     * without sanitization, so treat them as superglobal usage
     * as per WordPress coding standard principle.
     *
     * @return string IP Address or empty string on error
     */
    private function getOriginalIp(): string
    {
        return sanitize_text_field(
            wp_unslash(
                $this->ipRewrite->getOriginalIP()
            )
        );
    }
}
