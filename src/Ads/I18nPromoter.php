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
use TypistTech\WPCFG\Container;
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
     * The WPCFG container.
     *
     * @var Container
     */
    private $container;

    /**
     * I18nPromoter constructor.
     *
     * @param Admin     $admin     The WPCFG admin.
     * @param Container $container The WPCFG container.
     */
    public function __construct(Admin $admin, Container $container)
    {
        $this->admin = $admin;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action('admin_menu', __CLASS__, 'addYoastI18nModuleToMenuPages'),
        ];
    }

    /**
     * Add Yoast i18n module to all WPCFG menu pages.
     *
     * @return void
     */
    public function addYoastI18nModuleToMenuPages()
    {
        $hooks = array_map(
            [ $this, 'afterOptionFormHookForSlug' ],
            $this->admin->getMenuSlugs()
        );

        array_walk($hooks, [ $this, 'initializeYoastI18nModule' ]);
    }

    /**
     * After option form hook for a option page.
     *
     * @param string $menuSlug Slug of the option page.
     *
     * @return string
     */
    private function afterOptionFormHookForSlug(string $menuSlug): string
    {
        return str_replace('-', '_', $menuSlug . '_after_option_form');
    }

    /**
     * Initialize Yoast i18n module.
     *
     * @param string $hook Hook to display Yoast i18n module.
     *
     * @return void
     */
    private function initializeYoastI18nModule(string $hook)
    {
        $this->container->get(Yoast_I18n_WordPressOrg_v2::class, [
            [
                'textdomain' => 'wp-cloudflare-guard',
                'plugin_name' => 'WP Cloudflare Guard',
                'hook' => $hook,
            ],
        ]);
    }
}
