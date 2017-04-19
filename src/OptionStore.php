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

namespace TypistTech\WPCFG;

use TypistTech\WPCFG\Vendor\TypistTech\WPBetterSettings\OptionStore as WPBSOptionStore;

/**
 * Final class OptionStore
 *
 * The get_option functionality of the plugin.
 */
final class OptionStore extends WPBSOptionStore
{
    /**
     * Cloudflare api key getter.
     *
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->get('wpcfg_cloudflare_api_key');
    }

    /**
     * Bad usernames getter.
     *
     * @return string[]
     */
    public function getBadUsernames(): array
    {
        $value = $this->get('wpcfg_bad_login_bad_usernames');
        if (empty($value)) {
            return [];
        }

        return array_map(function (string $username) {
            return sanitize_user($username, true);
        }, explode(',', $value));
    }

    /**
     * Cloudflare email getter.
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->get('wpcfg_cloudflare_email');
    }

    /**
     * Cloudflare zone id getter.
     *
     * @return mixed
     */
    public function getZoneId()
    {
        return $this->get('wpcfg_cloudflare_zone_id');
    }
}
