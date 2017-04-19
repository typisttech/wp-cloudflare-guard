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
use TypistTech\WPCFG\OptionStore;
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
     * The option store.
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * Handler constructor.
     *
     * @param OptionStore $optionStore The WPCFG option store.
     * @param AccessRules $accessRules The api client.
     */
    public function __construct(OptionStore $optionStore, AccessRules $accessRules)
    {
        $this->optionStore = $optionStore;
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
     * @param mixed $event The event expected to be \TypistTech\WPCFG\Blacklist\Event.
     *
     * @return void
     */
    public function handleBlacklist($event = null)
    {
        if (! $event instanceof Event) {
            return;
        }

        $this->accessRules->setEmail($this->optionStore->getEmail());
        $this->accessRules->setAuthKey($this->optionStore->getApiKey());

        $this->accessRules->create(
            $this->optionStore->getZoneId(),
            'block',
            (object) [
                'target' => 'ip',
                'value' => $event->getIpAddress(),
            ],
            $event->getNote()
        );
    }
}
