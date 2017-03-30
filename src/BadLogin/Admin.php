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

namespace WPCFG\BadLogin;

use WPCFG\AbstractLoadable;
use WPCFG\Filter;
use WPCFG\Vendor\TypistTech\WPBetterSettings\FieldConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\MenuPageConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\SectionConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\SettingConfig;
use WPCFG\Vendor\TypistTech\WPBetterSettings\ViewFactory;

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the Bad Login module.
 */
final class Admin extends AbstractLoadable
{
    /**
     * {@inheritdoc}
     */
    public static function getFilters(): array
    {
        return [
            new Filter('wpcfg_menu_page_configs', 'addMenuPageConfig'),
            new Filter('wpcfg_setting_configs', 'addSettingConfig'),
        ];
    }

    /**
     * Add the menu page config.
     *
     * @todo Test via acceptance test.
     *
     * @param MenuPageConfig[] $menuPageConfigs Menu page configurations.
     *
     * @return array|MenuPageConfig[]
     */
    public function addMenuPageConfig(array $menuPageConfigs): array
    {
        // Create the admin settings page in wp-admin > WP Cloudflare Guard (admin.php?page=wpcfg).
        $menuPageConfigs[] = new MenuPageConfig([
            'menu_slug'    => 'wpcfg_bad_login',
            'page_title'   => 'WP Cloudflare Guard - Bad Login',
            'menu_title'   => 'Bad Login',
            'option_group' => 'wpcfg_bad_login',
            'parent_slug'  => 'wpcfg_cloudflare',
            'view'         => ViewFactory::build('tabbed-options-page'),
        ]);

        return $menuPageConfigs;
    }

    /**
     * Add settings config.
     *
     * @todo Test via acceptance test.
     *
     * @param SettingConfig[] $settingConfig Setting configurations.
     *
     * @return SettingConfig[]
     */
    public function addSettingConfig(array $settingConfig): array
    {
        $enabledField = new FieldConfig([
            'id'    => 'disabled',
            'title' => 'Bad Login',
            'view'  => ViewFactory::build('checkbox-field'),
            'desc'  => __(
                '<b>Disable</b> blacklisting IPs which attempt to login with bad usernames',
                'wp-cloudflare-guard'
            ),
            'label' => __('Disable Bad Login', 'wp-cloudflare-guard'),
        ]);

        $badUsernamesField = new FieldConfig([
            'id'    => 'bad_usernames',
            'title' => __('Bad Usernames', 'wp-cloudflare-guard'),
            'view'  => ViewFactory::build('textarea-field'),
            'desc'  => __('You can define your own bad usernames here, separated by commas.', 'wp-cloudflare-guard'),
        ]);

        $badLoginSection = new SectionConfig([
            'id'     => 'wpcfg_bad_login',
            'page'   => 'wpcfg_bad_login',
            'title'  => __('Cloudflare Settings', 'wp-cloudflare-guard'),
            'fields' => [ $enabledField, $badUsernamesField ],
        ]);

        $settingConfig[] = new SettingConfig([
            'option_group' => 'wpcfg_bad_login',
            'option_name'  => 'wpcfg_bad_login',
            'sections'     => [ $badLoginSection ],
        ]);

        return $settingConfig;
    }
}
