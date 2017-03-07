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

use WPCFG\{
	Ads\I18n_Promoter, Bad_Login\Admin as Bad_Login_Admin, Bad_Login\Bad_Login, Blacklist\Handler, Cloudflare\Admin as Cloudflare_Admin
};

/**
 * Final class WPCFG
 *
 * The core plugin class.
 */
final class WPCFG {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @var Loader $loader Maintains and registers all hooks for the plugin.
	 */
	private $loader;

	/**
	 * The WPCFG option store.
	 *
	 * @var Option_Store
	 */
	private $option_store;

	/**
	 * The WPCFG admin.
	 *
	 * @var Admin
	 */
	private $admin;

	/**
	 * WPCFG constructor.
	 */
	public function __construct() {
		$this->loader       = new Loader;
		$this->option_store = new Option_Store;
		$this->admin        = Admin::register( $this->loader, $this->option_store );

		$this->register_dependencies();
	}

	/**
	 * Register the required dependencies for this plugin.
	 *
	 * @return void
	 */
	private function register_dependencies() {
		$modules = [
			Bad_Login::class,
			Bad_Login_Admin::class,
			Handler::class,
			Cloudflare_Admin::class,
			I18n::class,
			I18n_Promoter::class,
		];

		array_walk( $modules, [ $this, 'register_module' ] );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @return  void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Register core module
	 *
	 * @param mixed $module The module class name.
	 *
	 * @return void
	 */
	private function register_module( $module ) {
		$module::register( $this->loader, $this->option_store, $this->admin );
	}
}
