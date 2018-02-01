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

use TypistTech\WPCFG\LoadableInterface;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;
use TypistTech\WPCFG\Vendor\WP_Review_Me;

/**
 * Final class ReviewNotice
 */
final class ReviewNotice implements LoadableInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action('admin_init', __CLASS__, 'run'),
        ];
    }

    /**
     * Initialize WP_Review_Me
     *
     * @return void
     */
    public function run()
    {
        // @codingStandardsIgnoreStart
        $message = __(
            'Hey! <code>WP Cloudflare Guard</code> has been keeping your site safe for a while. You might not realize it, but user reviews are such a great help to us. We would be so grateful if you could take a minute to leave a review on WordPress.org. Many thanks in advance :)',
            'wp-cloudflare-guard'
        );
        // @codingStandardsIgnoreEnd

        new WP_Review_Me(
            [
                'type' => 'plugin',
                'slug' => 'wp-cloudflare-guard',
                'message' => $message,
            ]
        );
    }
}
