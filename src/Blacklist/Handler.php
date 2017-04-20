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

namespace TypistTech\WPCFG\Blacklist;

use TypistTech\WPCFG\Cloudflare\AccessRules;
use TypistTech\WPCFG\LoadableInterface;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;

/**
 * Final class Handler.
 *
 * This class handle the blacklist event.
 */
final class Handler implements LoadableInterface
{
    /**
     * The api client.
     *
     * @var AccessRules
     */
    private $accessRules;

    /**
     * Handler constructor.
     *
     * @param AccessRules $accessRules The api client.
     */
    public function __construct(AccessRules $accessRules)
    {
        $this->accessRules = $accessRules;
    }

    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action('wpcfg_blacklist', __CLASS__, 'handleBlacklist'),
        ];
    }

    /**
     * Handle blacklist events.
     *
     * @param Event $event Immutable data transfer object that holds necessary information about this blacklist action.
     *
     * @return void
     */
    public function handleBlacklist(Event $event)
    {
        $this->accessRules->create(
            'block',
            [
                'target' => 'ip',
                'value' => $event->getIpAddress(),
            ],
            $event->getNote()
        );
    }
}
