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

namespace TypistTech\WPCFG\Ads;

use TypistTech\WPCFG\Admin;
use TypistTech\WPCFG\LoadableInterface;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;
use TypistTech\WPCFG\Vendor\Yoast_I18n_WordPressOrg_v2;

/**
 * Final class I18nPromoter
 */
final class I18nPromoter implements LoadableInterface
{
    /**
     * The WPCFG admin.
     *
     * @var Admin
     */
    private $admin;

    /**
     * I18nPromoter constructor.
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
    public static function getHooks(): array
    {
        return [
            new Action('admin_menu', __CLASS__, 'run', 20),
        ];
    }

    /**
     * Initialize Yoast i18n module to all WPCFG menu pages.
     *
     * @return void
     */
    public function run()
    {
        $hooks = array_map(
            function (string $menuSlug) {
                return str_replace('-', '_', $menuSlug . '_after_option_form');
            },
            $this->admin->getMenuSlugs()
        );

        foreach ($hooks as $hook) {
            new Yoast_I18n_WordPressOrg_v2(
                [
                    'textdomain' => 'wp-cloudflare-guard',
                    'plugin_name' => 'WP Cloudflare Guard',
                    'hook' => $hook,
                ]
            );
        }
    }
}
