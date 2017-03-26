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

declare(strict_types=1);

namespace WPCFG\Cloudflare;

use WPCFG\Loader;
use WPCFG\Vendor\TypistTech\WPBetterSettings\FieldConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\MenuPageConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\Sanitizer;
use WPCFG\Vendor\TypistTech\WPBetterSettings\SectionConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\SettingConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\ViewFactory;

/**
 * Final class Admin.
 *
 * The admin-specific functionality of Cloudflare settings.
 */
final class Admin
{
    /**
     * Register this class via WordPress action hooks and filters.
     *
     * @param Loader $loader The WPCFG loader.
     *
     * @return void
     */
    public static function register(Loader $loader)
    {
        $self = new self;
        $loader->addFilter('wpcfg_menu_page_configs', $self, 'addMenuPageConfig');
        $loader->addFilter('wpcfg_setting_configs', $self, 'addSettingConfig');
    }

    /**
     * Add the menu page config.
     *
     * @param MenuPageConfig[] $menuPageConfigs Menu page configurations.
     *
     * @return MenuPageConfig[]
     */
    public function addMenuPageConfig(array $menuPageConfigs) : array
    {
        $menuPageConfigs[] = new MenuPageConfig([
            'menu_slug'    => 'wpcfg_cloudflare',
            'page_title'   => 'WP Cloudflare Guard',
            'menu_title'   => 'WP Cloudflare Guard',
            'option_group' => 'wpcfg_cloudflare',
            'view'         => ViewFactory::build('tabbed-options-page'),
            'icon_url'     => 'dashicons-shield',
        ]);

        return $menuPageConfigs;
    }

    /**
     * Add settings config.
     *
     * @param SettingConfig[] $settingConfig Setting configurations.
     *
     * @return SettingConfig[]
     */
    public function addSettingConfig(array $settingConfig) : array
    {
        $emailField = new FieldConfig([
            'id'                => 'email',
            'title'             => __('Cloudflare Email', 'wp-cloudflare-guard'),
            'view'              => ViewFactory::build('email-field'),
            'desc'              => __(
                'The email address associated with your Cloudflare account.',
                'wp-cloudflare-guard'
            ),
            'sanitize_callback' => [ Sanitizer::class, 'sanitizeEmail' ],
        ]);

        $apiKeyDesc  = sprintf(
            // Translators: %1$s is the url to Cloudflare document.
            _x(
                'Help: <a href="%1$s">Where do I find my Cloudflare API key?</a>',
                '%1$s is the url to Cloudflare document',
                'wp-cloudflare-guard'
            ),
            esc_url_raw(
                'https://support.cloudflare.com/hc/en-us/articles/200167836-Where-do-I-find-my-CloudFlare-API-key-'
            )
        );
        $apiKeyField = new FieldConfig([
            'id'    => 'api_key',
            'title' => __('Global API Key', 'wp-cloudflare-guard'),
            'view'  => ViewFactory::build('text-field'),
            'desc'  => $apiKeyDesc,
        ]);

        $zoneIdField = new FieldConfig([
            'id'    => 'zone_id',
            'title' => __('Zone ID', 'wp-cloudflare-guard'),
            'view'  => ViewFactory::build('text-field'),
            'desc'  => __('Zone identifier for this domain', 'wp-cloudflare-guard'),
        ]);

        $cloudflareSection = new SectionConfig([
            'id'     => 'wpcfg_cloudflare',
            'page'   => 'wpcfg_cloudflare',
            'title'  => __('Cloudflare Settings', 'wp-cloudflare-guard'),
            'fields' => [ $emailField, $apiKeyField, $zoneIdField ],
        ]);

        $settingConfig[] = new SettingConfig([
            'option_group' => 'wpcfg_cloudflare',
            'option_name'  => 'wpcfg_cloudflare',
            'sections'     => [ $cloudflareSection ],
        ]);

        return $settingConfig;
    }
}
