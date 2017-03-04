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

declare( strict_types = 1 );

namespace WPCFG\Cloudflare;

use WPCFG\Vendor\CloudFlare\IpRewrite;

/**
 * Final class Ip_Util
 */
final class Ip_Util {
	/**
	 * This class should not be instantiated.
	 */
	private function __construct() {
	}

	/**
	 * Retrieve the real ip address of the user in the current request.
	 *
	 * IpRewrite::getOriginalIP returns $_SERVER['REMOTE_ADDR']
	 * without sanitization, so treat it as superglobal usage
	 * as per WordPress coding standard principle.
	 *
	 * Side effect: If current request is coming through Cloudflare,
	 * $_SERVER["REMOTE_ADDR"] will be rewritten to reflect the end-user's
	 * IP address.
	 *
	 * @return string
	 */
	public static function get_current_ip() : string {
		$ip_rewrite  = new IpRewrite;
		$original_ip = $ip_rewrite->getOriginalIP();

		return sanitize_text_field( wp_unslash( $original_ip ) );
	}
}
