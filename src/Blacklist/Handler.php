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

namespace WPCFG\Blacklist;

use WPCFG\Loader;
use WPCFG\OptionStore;
use WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules;

/**
 * Final class Handler.
 *
 * This class handle the blacklist event.
 */
final class Handler
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
     * Register this class via WordPress action hooks and filters.
     *
     * @param Loader      $loader      The WPCFG loader.
     * @param OptionStore $optionStore The WPCFG option store.
     *
     * @return void
     */
    public static function register(Loader $loader, OptionStore $optionStore)
    {
        $self = new self($optionStore, new AccessRules);
        $loader->addAction('wpcfg_blacklist', $self, 'handleBlacklist');
    }

    /**
     * Handle blacklist events.
     *
     * @param Event $event The event expected to be Blacklist\Event.
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
                'value'  => $event->getIpAddress(),
            ],
            $event->getNote()
        );
    }
}
