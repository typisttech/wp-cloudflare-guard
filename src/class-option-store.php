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

namespace WPCFG;

/**
 * Final class Option_Store
 *
 * The get_option functionality of the plugin.
 */
class Option_Store extends Vendor\WP_Better_Settings\Option_Store {
	/**
	 * Cloudflare email getter.
	 *
	 * @return mixed
	 */
	public function get_email() {
		return $this->get( 'wpcfg_cloudflare', 'email' );
	}

	/**
	 * Cloudflare api key getter.
	 *
	 * @return mixed
	 */
	public function get_api_key() {
		return $this->get( 'wpcfg_cloudflare', 'api_key' );
	}

	/**
	 * Cloudflare zone id getter.
	 *
	 * @return mixed
	 */
	public function get_zone_id() {
		return $this->get( 'wpcfg_cloudflare', 'zone_id' );
	}
}
