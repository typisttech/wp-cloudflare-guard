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

namespace WPCFG\Bad_Login;

use WPCFG\{
	Blacklist\Event, Cloudflare\Ip_Util, Loader, OptionStore
};

/**
 * Final class Bad_Login.
 *
 * This class blacklist login with bad username.
 */
final class Bad_Login {
	/**
	 * Holds the option store.
	 *
	 * @var OptionStore
	 */
	private $option_store;

	/**
	 * Bad_Login constructor.
	 *
	 * @param OptionStore $option_store The WPCFG option store.
	 */
	public function __construct( OptionStore $option_store ) {
		$this->option_store = $option_store;
	}

	/**
	 * Register this class via WordPress action hooks and filters.
	 *
	 * @param Loader       $loader       The WPCFG loader.
	 * @param OptionStore $option_store The WPCFG option store.
	 *
	 * @return void
	 */
	public static function register( Loader $loader, OptionStore $option_store ) {
		$self = new self( $option_store );
		$loader->add_action( 'wp_authenticate', $self, 'emit_blacklist_event_if_bad_username' );
	}

	/**
	 * Emit blacklist event if username is bad.
	 *
	 * @param string|null $username User input.
	 *
	 * @void
	 */
	public function emit_blacklist_event_if_bad_username( $username ) {
		if ( ! $this->should_blacklist( $username ) ) {
			return;
		}

		$note       = sprintf(
			__( 'WPCFG: Try to login with bad username: %1$s', 'wp-cloudflare-guard' ),
			$username
		);
		$current_ip = Ip_Util::get_current_ip();
		$event      = new Event( $current_ip, $note );

		do_action( 'wpcfg_blacklist', $event );
	}

	/**
	 * Check whether blacklist should be performed.
	 *
	 * @param string|null $username User input.
	 *
	 * @return bool
	 */
	private function should_blacklist( $username ) : bool {
		if ( empty( $username ) ) {
			return false;
		}

		if ( $this->is_disabled() ) {
			return false;
		}

		return $this->is_bad_username( $username );
	}

	/**
	 * Check whether bad login is enabled.
	 *
	 * @return bool
	 */
	private function is_disabled() : bool {
		$disabled = $this->option_store->get( 'wpcfg_bad_login', 'disabled' );

		return ( '1' === $disabled );
	}

	/**
	 * Check whether username input is a bad username.
	 *
	 * @param string $input_username User input.
	 *
	 * @return bool
	 */
	private function is_bad_username( string $input_username ) : bool {
		$bad_usernames  = $this->get_normalized_bad_usernames();
		$input_username = $this->normalize( $input_username );

		return in_array( $input_username, $bad_usernames, true );
	}

	/**
	 * Get normalized bad usernames from database.
	 *
	 * @return array
	 */
	private function get_normalized_bad_usernames() : array {
		$bad_usernames = $this->get_bad_usernames();
		$normalized    = array_map( [ $this, 'normalize' ], $bad_usernames );

		return array_filter( $normalized, function ( $username ) {
			return ( ! empty( $username ) );
		} );
	}

	/**
	 * Get bad usernames from database.
	 *
	 * @return array
	 */
	private function get_bad_usernames() : array {
		$bad_usernames = $this->option_store->get( 'wpcfg_bad_login', 'bad_usernames' );
		if ( empty( $bad_usernames ) ) {
			return [];
		}

		return explode( ',', $bad_usernames );
	}

	/**
	 * Normalize username string.
	 *
	 * @param string $username Un-normalized username.
	 *
	 * @return string
	 */
	private function normalize( string $username ) : string {
		return strtolower( trim( sanitize_user( $username, true ) ) );
	}
}
