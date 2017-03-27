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

namespace WPCFG\Ads;

use WPCFG\AbstractLoadable;
use WPCFG\Action;
use WPCFG\Admin;
use WPCFG\Vendor\Yoast_I18n_WordPressOrg_v2;

/**
 * Final class I18nPromoter
 */
final class I18nPromoter extends AbstractLoadable
{
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
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * {@inheritdoc}
     */
    public static function getActions(): array
    {
        return [
            new Action('admin_menu', 'addYoastI18nModuleToMenuPages', 20),
        ];
    }

    /**
     * Add Yoast i18n module to all WPCFG menu pages.
     *
     * @return void
     */
    public function addYoastI18nModuleToMenuPages()
    {
        $menuPageConfigs = $this->admin->getMenuPageConfigs();

        $hooks = array_map(function ($menuPageConfig) {
            return str_replace('-', '_', $menuPageConfig->menu_slug) . '_after_option_form';
        }, $menuPageConfigs);

        array_walk($hooks, function ($hook) {
            new Yoast_I18n_WordPressOrg_v2(
                [
                    'textdomain'  => 'wp-cloudflare-guard',
                    'plugin_name' => 'WP Cloudflare Guard',
                    'hook'        => $hook,
                ]
            );
        });
    }
}
