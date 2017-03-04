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

use WPCFG\Loader;
use WPCFG\Vendor\WP_Better_Settings\{
	Field_Config, Menu_Page_Config, Section_Config, Setting_Config, View_Factory
};

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the Bad Login module.
 */
final class Admin {
	/**
	 * Register this class via WordPress action hooks and filters.
	 *
	 * @param Loader $loader The WPCFG loader.
	 *
	 * @return void
	 */
	public static function register( Loader $loader ) {
		$self = new self();
		$loader->add_filter( 'wpcfg_menu_page_configs', $self, 'add_menu_page_config' );
		$loader->add_filter( 'wpcfg_setting_configs', $self, 'add_setting_config' );
	}

	/**
	 * Add the menu page config.
	 *
	 * @param Menu_Page_Config[] $menu_page_configs Menu page configurations.
	 *
	 * @return Menu_Page_Config[]
	 */
	public function add_menu_page_config( array $menu_page_configs ) : array {
		// Create the admin settings page in wp-admin > WP Cloudflare Guard (admin.php?page=wpcfg).
		$menu_page_configs[] = new Menu_Page_Config( [
			'menu_slug'    => 'wpcfg_bad_login',
			'page_title'   => 'WP Cloudflare Guard - Bad Login',
			'menu_title'   => 'Bad Login',
			'option_group' => 'wpcfg_bad_login',
			'parent_slug'  => 'wpcfg_cloudflare',
			'view'         => View_Factory::build( 'tabbed-options-page' ),
		] );

		return $menu_page_configs;
	}

	/**
	 * Add settings config.
	 *
	 * @param Setting_Config[] $setting_config Setting configurations.
	 *
	 * @return Setting_Config[]
	 */
	public function add_setting_config( array $setting_config ) : array {
		$enabled_field = new Field_Config( [
			'id'    => 'disabled',
			'title' => 'Bad Login',
			'view'  => View_Factory::build( 'checkbox-field' ),
			'desc'  => __( '<b>Disable</b> blacklisting IPs which attempt to login with bad usernames', 'wp-cloudflare-guard' ),
			'label' => __( 'Disable Bad Login', 'wp-cloudflare-guard' ),
		] );

		$bad_usernames_field = new Field_Config( [
			'id'    => 'bad_usernames',
			'title' => __( 'Bad Usernames', 'wp-cloudflare-guard' ),
			'view'  => View_Factory::build( 'textarea-field' ),
			'desc'  => __( 'You can define your own bad usernames here, separated by commas.', 'wp-cloudflare-guard' ),
		] );

		$bad_login_section = new Section_Config( [
			'id'     => 'wpcfg_bad_login',
			'page'   => 'wpcfg_bad_login',
			'title'  => __( 'Cloudflare Settings', 'wp-cloudflare-guard' ),
			'fields' => [ $enabled_field, $bad_usernames_field ],
		] );

		$setting_config[] = new Setting_Config( [
			'option_group' => 'wpcfg_bad_login',
			'option_name'  => 'wpcfg_bad_login',
			'sections'     => [ $bad_login_section ],
		] );

		return $setting_config;
	}
}
