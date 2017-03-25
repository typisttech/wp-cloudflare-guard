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

namespace WPCFG\Ads;

use WPCFG\Admin;
use WPCFG\Loader;
use WPCFG\OptionStore;
use WPCFG\Vendor\Yoast_I18n_WordPressOrg_v2;

/**
 * Final class I18n_Promoter
 */
final class I18n_Promoter {
	/**
	 * The WPCFG admin.
	 *
	 * @var Admin
	 */
	private $admin;

	/**
	 * I18n_Promoter constructor.
	 *
	 * @param Admin $admin The WPCFG admin.
	 */
	public function __construct( Admin $admin ) {
		$this->admin = $admin;
	}

	/**
	 * Register this class via WordPress action hooks and filters.
	 *
	 * @param Loader      $loader       The WPCFG loader.
	 * @param OptionStore $option_store The WPCFG option store.
	 * @param Admin       $admin        The WPCFG admin.
	 *
	 * @return void
	 */
	public static function register( Loader $loader, OptionStore $option_store, Admin $admin ) {
		$self = new self( $admin );
		$loader->add_action( 'admin_menu', $self, 'add_yoast_i18n_module_to_all_wpcfg_menu_pages', 20 );
	}

	/**
	 * Add Yoast i18n module to all WPCFG menu pages.
	 *
	 * @return void
	 */
	public function add_yoast_i18n_module_to_all_wpcfg_menu_pages() {
		$menu_page_configs = $this->admin->get_menu_page_configs();

		$hooks = array_map( function ( $menu_page_config ) {
			return str_replace( '-', '_', $menu_page_config->menu_slug ) . '_after_option_form';
		}, $menu_page_configs );

		array_walk( $hooks, function ( $hook ) {
			new Yoast_I18n_WordPressOrg_v2(
				[
					'textdomain'  => 'wp-cloudflare-guard',
					'plugin_name' => 'WP Cloudflare Guard',
					'hook'        => $hook,
				]
			);
		} );
	}
}
