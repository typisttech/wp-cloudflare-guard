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

namespace WPCFG;

use WPCFG\Vendor\TypistTech\WPBetterSettings\{
    MenuPageConfig, MenuPages, Settings
};

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the plugin.
 */
final class Admin
{
    /**
     * Menu page configs.
     *
     * @var MenuPageConfig[]
     */
    private $menu_page_configs;

    /**
     * Menu pages.
     *
     * @var MenuPages
     */
    private $menu_pages;

    /**
     * Options store.
     *
     * @var OptionStore
     */
    private $option_store;

    /**
     * Settings.
     *
     * @var \WPCFG\Vendor\TypistTech\WPBetterSettings\Settings
     */
    private $settings;

    /**
     * Setting constructor.
     *
     * @param OptionStore $option_store The WPCFG option store.
     */
    public function __construct(OptionStore $option_store)
    {
        $this->option_store = $option_store;
    }

    /**
     * Register this class via WordPress action hooks and filters.
     *
     * @param Loader      $loader       The WPCFG loader.
     * @param OptionStore $option_store The WPCFG option store.
     *
     * @return Admin
     */
    public static function register(Loader $loader, OptionStore $option_store)
    {
        $self = new self($option_store);

        // Adds the plugin admin menu.
        $loader->add_action('admin_menu', $self, 'admin_menu');
        // Initialize the settings class on admin_init.
        $loader->add_action('admin_init', $self, 'admin_init');

        return $self;
    }

    /**
     * Register WPCFG settings.
     *
     * @return void
     */
    public function admin_init()
    {
        $setting_configs = apply_filters('wpcfg_setting_configs', []);
        $this->settings  = new Settings($setting_configs, $this->option_store);
        $this->settings->adminInit();
    }

    /**
     * Add menus and submenus.
     *
     * @return void
     */
    public function admin_menu()
    {
        $menu_page_configs = $this->get_menu_page_configs();
        $this->menu_pages  = new MenuPages($menu_page_configs);
        $this->menu_pages->adminMenu();
    }

    /**
     * Menu page configs getter.
     *
     * @return MenuPageConfig[]
     */
    public function get_menu_page_configs()
    {
        if (empty($this->menu_page_configs)) {
            $this->menu_page_configs = apply_filters('wpcfg_menu_page_configs', []);
        }

        return $this->menu_page_configs;
    }
}
