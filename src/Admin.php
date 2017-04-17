<?php
/**
 * WP CloudFlare Guard
 *
 * Connecting WordPress with Cloudflare firewall,
 * protect your WordPress site at DNS level.
 * Automatically create firewall rules to block dangerous IPs.
 *
 * @package   WPCFG
 *
 * @author    Typist Tech <wp-cloudflare-guard@typist.tech>
 * @copyright 2017 Typist Tech
 * @license   GPL-2.0+
 *
 * @see       https://www.typist.tech/projects/wp-cloudflare-guard
 * @see       https://wordpress.org/plugins/wp-cloudflare-guard/
 */

declare(strict_types=1);

namespace WPCFG;

use WPCFG\Vendor\TypistTech\WPBetterSettings\MenuPageConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\MenuPages;
use WPCFG\Vendor\TypistTech\WPBetterSettings\Settings;

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the plugin.
 */
final class Admin implements LoadableInterface
{
    /**
     * Menu page configs.
     *
     * @var MenuPageConfig[]
     */
    private $menuPageConfigs;

    /**
     * Menu pages.
     *
     * @var MenuPages
     */
    private $menuPages;

    /**
     * Options store.
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * Settings.
     *
     * @var \WPCFG\Vendor\TypistTech\WPBetterSettings\Settings
     */
    private $settings;

    /**
     * Admin constructor.
     *
     * @param OptionStore $optionStore The WPCFG option store.
     */
    public function __construct(OptionStore $optionStore)
    {
        $this->optionStore = $optionStore;
    }

    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action(__CLASS__, 'admin_menu', 'adminMenu'),
            new Action(__CLASS__, 'admin_init', 'adminInit'),
        ];
    }

    /**
     * Register WPCFG settings.
     *
     * @return void
     */
    public function adminInit()
    {
        $settingConfigs = apply_filters('wpcfg_setting_configs', []);
        $this->settings = new Settings($settingConfigs, $this->optionStore);
        $this->settings->adminInit();
    }

    /**
     * Add menus and submenus.
     *
     * @return void
     */
    public function adminMenu()
    {
        $this->menuPages = new MenuPages(
            $this->getMenuPageConfigs()
        );
        $this->menuPages->adminMenu();
    }

    /**
     * Menu page configs getter.
     *
     * @return MenuPageConfig[]
     */
    private function getMenuPageConfigs()
    {
        if (empty($this->menuPageConfigs)) {
            $this->menuPageConfigs = apply_filters('wpcfg_menu_page_configs', []);
        }

        return $this->menuPageConfigs;
    }

    /**
     * Menu slugs getter.
     *
     * @return string[]
     */
    public function getMenuSlugs(): array
    {
        return array_map(function (MenuPageConfig $menuPageConfig) {
            return $menuPageConfig->menu_slug;
        }, $this->getMenuPageConfigs());
    }
}
