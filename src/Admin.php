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

use WPCFG\Vendor\TypistTech\WPBetterSettings\PageRegister;
use WPCFG\Vendor\TypistTech\WPBetterSettings\Pages\MenuPage;
use WPCFG\Vendor\TypistTech\WPBetterSettings\Pages\PageInterface;
use WPCFG\Vendor\TypistTech\WPBetterSettings\Pages\SubmenuPage;
use WPCFG\Vendor\TypistTech\WPBetterSettings\Section;
use WPCFG\Vendor\TypistTech\WPBetterSettings\SettingRegister;

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the plugin.
 */
final class Admin implements LoadableInterface
{
    /**
     * Options store.
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * Menu and submenu pages.
     *
     * @var (MenuPage|SubmenuPage)[]
     */
    private $pages = [];

    /**
     * Sections
     *
     * @var Section[]
     */
    private $sections = [];

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
            new Action(__CLASS__, 'admin_menu', 'registerPages'),
            new Action(__CLASS__, 'admin_init', 'registerSettings'),
        ];
    }

    /**
     * Menu slugs getter.
     *
     * @return string[]
     */
    public function getMenuSlugs(): array
    {
        return array_map(function (PageInterface $page) {
            return $page->getMenuSlug();
        }, $this->getPages());
    }

    /**
     * Page getter.
     *
     * @return (MenuPage|SubmenuPage)[]
     */
    private function getPages(): array
    {
        if (empty($this->pages)) {
            $wpcfgPages = apply_filters('wpcfg_pages', []);

            $typedPages = array_filter($wpcfgPages, function ($page) {
                return $page instanceof MenuPage || $page instanceof SubmenuPage;
            });
            $this->pages = array_values($typedPages);
        }

        return $this->pages;
    }

    /**
     * Add menus and submenus.
     *
     * @return void
     */
    public function registerPages()
    {
        $pageRegister = new PageRegister(
            $this->getPages()
        );
        $pageRegister->run();
    }

    /**
     * Register WPCFG settings.
     *
     * @return void
     */
    public function registerSettings()
    {
        $settingRegister = new SettingRegister(
            $this->optionStore,
            ...$this->getSections()
        );
        $settingRegister->run();
    }

    /**
     * Section getter.
     *
     * @return Section[]
     */
    private function getSections(): array
    {
        if (empty($this->sections)) {
            $wpcfgSettingsSections = apply_filters('wpcfg_settings_sections', []);

            $typedSections = array_filter($wpcfgSettingsSections, function ($section) {
                return $section instanceof Section;
            });
            $this->sections = array_values($typedSections);
        }

        return $this->sections;
    }
}
