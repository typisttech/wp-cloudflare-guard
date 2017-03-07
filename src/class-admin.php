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

use WPCFG\Vendor\WP_Better_Settings\{
	Menu_Page_Config, Menu_Pages, Settings
};

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the plugin.
 */
final class Admin {
	/**
	 * Options store.
	 *
	 * @var Option_Store
	 */
	private $option_store;

	/**
	 * Menu pages.
	 *
	 * @var Menu_Pages
	 */
	private $menu_pages;

	/**
	 * Settings.
	 *
	 * @var \WPCFG\Vendor\WP_Better_Settings\Settings
	 */
	private $settings;

	/**
	 * Menu page configs.
	 *
	 * @var Menu_Page_Config[]
	 */
	private $menu_page_configs;

	/**
	 * Setting constructor.
	 *
	 * @param Option_Store $option_store The WPCFG option store.
	 */
	public function __construct( Option_Store $option_store ) {
		$this->option_store = $option_store;
	}

	/**
	 * Register this class via WordPress action hooks and filters.
	 *
	 * @param Loader       $loader       The WPCFG loader.
	 * @param Option_Store $option_store The WPCFG option store.
	 *
	 * @return Admin
	 */
	public static function register( Loader $loader, Option_Store $option_store ) {
		$self = new self( $option_store );

		// Adds the plugin admin menu.
		$loader->add_action( 'admin_menu', $self, 'admin_menu' );
		// Initialize the settings class on admin_init.
		$loader->add_action( 'admin_init', $self, 'admin_init' );

		return $self;
	}

	/**
	 * Register WPCFG settings.
	 *
	 * @return void
	 */
	public function admin_init() {
		$setting_configs = apply_filters( 'wpcfg_setting_configs', [] );
		$this->settings  = new Settings( $setting_configs, $this->option_store );
		$this->settings->admin_init();
	}

	/**
	 * Add menus and submenus.
	 *
	 * @return void
	 */
	public function admin_menu() {
		$menu_page_configs = $this->get_menu_page_configs();
		$this->menu_pages  = new Menu_Pages( $menu_page_configs );
		$this->menu_pages->admin_menu();
	}

	/**
	 * Menu page configs getter.
	 *
	 * @return Menu_Page_Config[]
	 */
	public function get_menu_page_configs() {
		if ( empty( $this->menu_page_configs ) ) {
			$this->menu_page_configs = apply_filters( 'wpcfg_menu_page_configs', [] );
		}

		return $this->menu_page_configs;
	}
}
