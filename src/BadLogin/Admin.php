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

namespace TypistTech\WPCFG\BadLogin;

use TypistTech\WPCFG\LoadableInterface;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Fields\Textarea;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Pages\SubmenuPage;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Section;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Filter;

/**
 * Final class Admin.
 *
 * The admin-specific functionality of the Bad Login module.
 */
final class Admin implements LoadableInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Filter('wpcfg_pages', __CLASS__, 'addPage'),
            new Filter('wpcfg_settings_sections', __CLASS__, 'addSettingsSection'),
        ];
    }

    /**
     * Add the menu page config.
     *
     * @param (MenuPage|SubmenuPage)[] $pages Menu and submenu page configurations.
     *
     * @return (MenuPage|SubmenuPage)[]
     */
    public function addPage(array $pages): array
    {
        $pages[] = new SubmenuPage(
            'wpcfg-cloudflare',
            'wpcfg-bad-login',
            __('Bad Login', 'wp-cloudflare-guard'),
            __('WP Cloudflare Guard - Bad Login', 'wp-cloudflare-guard')
        );

        return $pages;
    }

    /**
     * Add settings section config.
     *
     * @param Section[] $sections Settings section configurations.
     *
     * @return Section[]
     */
    public function addSettingsSection(array $sections): array
    {
        $badUsernames = new Textarea(
            'wpcfg_bad_login_bad_usernames',
            __('Bad Usernames', 'wp-cloudflare-guard')
        );
        $badUsernames->getDecorator()
                     ->setDescription(
                         __(
                             'You can define your own bad usernames here, separated by commas.',
                             'wp-cloudflare-guard'
                         )
                     );

        $sections[] = new Section(
            'wpcfg-bad-login',
            __('Bad Login', 'wp-cloudflare-guard'),
            $badUsernames
        );

        return $sections;
    }
}
