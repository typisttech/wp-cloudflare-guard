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
 * Final class IpUtil
 */
final class IpUtil
{
    /**
     * This class should not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Retrieve the real ip address of the user in the current request.
     *
     * IpRewrite::getOriginalIP and IpRewrite::getRewrittenIP returns
     * $_SERVER['REMOTE_ADDR'] and $_SERVER['HTTP_CF_CONNECTING_IP']
     * without sanitization, so treat them as superglobal usage
     * as per WordPress coding standard principle.
     *
     * Side effect: If current request is coming through Cloudflare,
     * $_SERVER["REMOTE_ADDR"] will be rewritten to reflect the end-user's
     * IP address.
     *
     * @return string
     */
    public static function getCurrentIp(): string
    {
        $ipRewrite = new IpRewrite;

        $rewrittenIp = $ipRewrite->getRewrittenIP();
        $originalIP = $rewrittenIp ?: $ipRewrite->getOriginalIP();

        return sanitize_text_field(wp_unslash($originalIP));
    }
}
