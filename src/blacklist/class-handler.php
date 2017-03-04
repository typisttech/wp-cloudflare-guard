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

namespace WPCFG\Blacklist;

use WPCFG\{
	Loader, Option_Store, Vendor\Cloudflare\Zone\Firewall\AccessRules
};

/**
 * Final class Handler.
 *
 * This class handle the blacklist event.
 */
final class Handler {
	/**
	 * The option store.
	 *
	 * @var Option_Store
	 */
	private $option_store;

	/**
	 * The api client.
	 *
	 * @var AccessRules
	 */
	private $access_rules;

	/**
	 * Handler constructor.
	 *
	 * @param Option_Store $option_store The WPCFG option store.
	 * @param AccessRules  $access_rules The api client.
	 */
	public function __construct( Option_Store $option_store, AccessRules $access_rules ) {
		$this->option_store = $option_store;
		$this->access_rules = $access_rules;
	}

	/**
	 * Register this class via WordPress action hooks and filters.
	 *
	 * @param Loader       $loader       The WPCFG loader.
	 * @param Option_Store $option_store The WPCFG option store.
	 *
	 * @return void
	 */
	public static function register( Loader $loader, Option_Store $option_store ) {
		$self = new self( $option_store, new AccessRules );
		$loader->add_action( 'wpcfg_blacklist', $self, 'handle_blacklist' );
	}

	/**
	 * Handle blacklist events.
	 *
	 * @param Event $event The event expected to be Blacklist\Event.
	 *
	 * @return void
	 */
	public function handle_blacklist( $event = null ) {
		if ( ! $event instanceof Event ) {
			return;
		}

		$this->access_rules->setEmail( $this->option_store->get_email() );
		$this->access_rules->setAuthKey( $this->option_store->get_api_key() );

		$this->access_rules->create(
			$this->option_store->get_zone_id(),
			'block',
			(object) [
				'target' => 'ip',
				'value'  => $event->get_ip_address(),
			],
			$event->get_note()
		);
	}
}
