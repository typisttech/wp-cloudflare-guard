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

namespace TypistTech\WPCFG\Cloudflare;

use TypistTech\WPCFG\Filter;
use TypistTech\WPCFG\LoadableInterface;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Fields\Email;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Fields\Text;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Pages\MenuPage;
use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\Section;

/**
 * Final class Admin.
 *
 * The admin-specific functionality of Cloudflare settings.
 */
final class Admin implements LoadableInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Filter(__CLASS__, 'wpcfg_pages', 'addPage'),
            new Filter(__CLASS__, 'wpcfg_settings_sections', 'addSettingsSection'),
        ];
    }

    /**
     * Add the menu page config.
     *
     * @todo Test via acceptance test.
     *
     * @param (MenuPage|SubmenuPage)[] $pages Menu and submenu page configurations.
     *
     * @return (MenuPage|SubmenuPage)[]
     */
    public function addPage(array $pages): array
    {
        $pages[] = new MenuPage(
            'wpcfg-cloudflare',
            __('WP Cloudflare Guard', 'wp-cloudflare-guard'),
            __('WP Cloudflare Guard - Cloudflare', 'wp-cloudflare-guard'),
            null,
            'dashicons-shield'
        );

        return $pages;
    }

    /**
     * Add settings section config.
     *
     * @todo Test via acceptance test.
     *
     * @param Section[] $sections Settings section configurations.
     *
     * @return Section[]
     */
    public function addSettingsSection(array $sections): array
    {
        $email = new Email(
            'wpcfg_cloudflare_email',
            __('Cloudflare Email', 'wp-cloudflare-guard')
        );
        $email->getDecorator()
              ->setDescription(
                  __(
                      'The email address associated with your Cloudflare account.',
                      'wp-cloudflare-guard'
                  )
              );

        $apiKey = new Text(
            'wpcfg_cloudflare_api_key',
            __('Global API Key', 'wp-cloudflare-guard')
        );
        $apiKeyDesc = sprintf(
            // Translators: %1$s is the url to Cloudflare document.
            __('Help: <a href="%1$s">Where do I find my Cloudflare API key?</a>', 'wp-cloudflare-guard'),
            esc_url_raw(
                'https://support.cloudflare.com/hc/en-us/articles/200167836-Where-do-I-find-my-CloudFlare-API-key-'
            )
        );
        $apiKey->getDecorator()
               ->setDescription($apiKeyDesc);

        $zoneId = new Text(
            'wpcfg_cloudflare_zone_id',
            __('Zone ID', 'wp-cloudflare-guard')
        );
        $zoneId->getDecorator()
               ->setDescription(
                   __('Zone identifier for this domain', 'wp-cloudflare-guard')
               );

        $sections[] = new Section(
            'wpcfg-cloudflare',
            __('Cloudflare Settings', 'wp-cloudflare-guard'),
            $email,
            $apiKey,
            $zoneId
        );

        return $sections;
    }
}
