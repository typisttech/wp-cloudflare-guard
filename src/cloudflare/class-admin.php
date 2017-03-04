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

use WPCFG\Loader;
use WPCFG\Vendor\WP_Better_Settings\{
	Field_Config, Menu_Page_Config, Sanitizer, Section_Config, Setting_Config, View_Factory
};

/**
 * Final class Admin.
 *
 * The admin-specific functionality of Cloudflare settings.
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
		$menu_page_configs[] = new Menu_Page_Config( [
			'menu_slug'    => 'wpcfg_cloudflare',
			'page_title'   => 'WP Cloudflare Guard',
			'menu_title'   => 'WP Cloudflare Guard',
			'option_group' => 'wpcfg_cloudflare',
			'view'         => View_Factory::build( 'tabbed-options-page' ),
			'icon_url'     => 'dashicons-shield',
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
		$email_field = new Field_Config( [
			'id'                => 'email',
			'title'             => __( 'Cloudflare Email', 'wp-cloudflare-guard' ),
			'view'              => View_Factory::build( 'email-field' ),
			'desc'              => __( 'The email address associated with your Cloudflare account.', 'wp-cloudflare-guard' ),
			'sanitize_callback' => [ Sanitizer::class, 'sanitize_email' ],
		] );

		$api_key_desc  = sprintf(
			__( 'Help: <a href="%1$s">Where do I find my Cloudflare API key?</a>', 'wp-cloudflare-guard' ),
			esc_url_raw( 'https://support.cloudflare.com/hc/en-us/articles/200167836-Where-do-I-find-my-CloudFlare-API-key-' )
		);
		$api_key_field = new Field_Config( [
			'id'    => 'api_key',
			'title' => __( 'Global API Key', 'wp-cloudflare-guard' ),
			'view'  => View_Factory::build( 'text-field' ),
			'desc'  => $api_key_desc,
		] );

		$zone_id_field = new Field_Config( [
			'id'    => 'zone_id',
			'title' => __( 'Zone ID', 'wp-cloudflare-guard' ),
			'view'  => View_Factory::build( 'text-field' ),
			'desc'  => __( 'Zone identifier for this domain', 'wp-cloudflare-guard' ),
		] );

		$cloudflare_section = new Section_Config( [
			'id'     => 'wpcfg_cloudflare',
			'page'   => 'wpcfg_cloudflare',
			'title'  => __( 'Cloudflare Settings', 'wp-cloudflare-guard' ),
			'fields' => [ $email_field, $api_key_field, $zone_id_field ],
		] );

		$setting_config[] = new Setting_Config( [
			'option_group' => 'wpcfg_cloudflare',
			'option_name'  => 'wpcfg_cloudflare',
			'sections'     => [ $cloudflare_section ],
		] );

		return $setting_config;
	}
}
