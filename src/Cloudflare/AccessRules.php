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

use TypistTech\WPCFG\OptionStore;
use TypistTech\WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules as CloudflareAccessRules;

/**
 * Final class AccessRules.
 */
final class AccessRules
{
    /**
     * The api client
     *
     * @var CloudflareAccessRules
     */
    private $client;

    /**
     * The WPCFG option store
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * AccessRules constructor.
     *
     * @param OptionStore                $optionStore The WPCFG option store.
     * @param CloudflareAccessRules|null $client      Optional. The api client.
     */
    public function __construct(OptionStore $optionStore, CloudflareAccessRules $client = null)
    {
        $this->optionStore = $optionStore;
        $this->client = $client ?? new CloudflareAccessRules();
    }

    /**
     * Create access rule (permission needed: #zone:edit)
     * Make a new IP, IP range, or country access rule for the zone.
     *
     * @param string $mode          The action to apply to a matched request.
     * @param array  $configuration Rule configuration.
     * @param string $notes         A personal note about the rule. Typically used as a reminder or explanation for the
     *                              rule.
     *
     * @return array|\WP_Error
     */
    public function create(string $mode, array $configuration, string $notes)
    {
        $this->setUpClient();

        return $this->client->create(
            $this->optionStore->getZoneId(),
            $mode,
            $configuration,
            $notes
        );
    }

    /**
     * Set up client auth key and email.
     *
     * @return void
     */
    private function setUpClient()
    {
        $this->client->setAuthKey(
            $this->optionStore->getApiKey()
        );
        $this->client->setEmail(
            $this->optionStore->getEmail()
        );
    }
}
