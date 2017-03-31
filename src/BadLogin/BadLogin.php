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

namespace WPCFG\BadLogin;

use WPCFG\Action;
use WPCFG\Blacklist\Event;
use WPCFG\Container;
use WPCFG\LoadableInterface;
use WPCFG\OptionStore;

/**
 * Final class BadLogin.
 *
 * This class blacklist login with bad username.
 */
final class BadLogin implements LoadableInterface
{
    /**
     * The WPCFG container.
     *
     * @var Container
     */
    private $container;

    /**
     * Holds the option store.
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * BadLogin constructor.
     *
     * @param OptionStore $optionStore The WPCFG option store.
     * @param Container   $container   The WPCFG container.
     */
    public function __construct(OptionStore $optionStore, Container $container)
    {
        $this->optionStore = $optionStore;
        $this->container   = $container;
    }

    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action(__CLASS__, 'wp_authenticate', 'emitBlacklistEventIfBadUsername'),
        ];
    }

    /**
     * Emit blacklist event if username is bad.
     *
     * @param string|null $inputUsername User input.
     *
     * @void
     */
    public function emitBlacklistEventIfBadUsername($inputUsername)
    {
        if (! $this->shouldBlacklist($inputUsername)) {
            return;
        }

        do_action(
            'wpcfg_blacklist',
            $this->getBlacklistEventForCurrentIp($inputUsername)
        );
    }

    /**
     * Check whether blacklist should be performed.
     *
     * @param mixed $inputUsername User input.
     *
     * @return bool
     */
    private function shouldBlacklist($inputUsername): bool
    {
        if (! is_string($inputUsername) || empty($inputUsername)) {
            return false;
        }

        return $this->isBadUsername($inputUsername);
    }

    /**
     * Check whether username input is a bad username.
     *
     * @param string $inputUsername User input.
     *
     * @return bool
     */
    private function isBadUsername(string $inputUsername): bool
    {
        $badUsernames    = $this->getNormalizedBadUsernames();
        $normalizedInput = $this->normalize($inputUsername);

        return in_array($normalizedInput, $badUsernames, true);
    }

    /**
     * Get normalized bad usernames from database.
     *
     * @return array
     */
    private function getNormalizedBadUsernames(): array
    {
        $normalized = array_map(
            [ $this, 'normalize' ],
            $this->optionStore->getBadUsernames()
        );

        return array_filter($normalized, function ($username) {
            return (! empty($username));
        });
    }

    /**
     * Normalize username string.
     *
     * @param string $username Un-normalized username.
     *
     * @return string
     */
    private function normalize(string $username): string
    {
        return strtolower(trim(sanitize_user($username, true)));
    }

    /**
     * Make blacklist event for current ip and the given username.
     *
     * @param string $username The input username which is bad.
     *
     * @return Event
     */
    private function getBlacklistEventForCurrentIp(string $username): Event
    {
        $note = sprintf(
            // Translators: %1$s is the bad username.
            _x('WPCFG: Try to login with bad username: %1$s', '%1$s is the bad username', 'wp-cloudflare-guard'),
            $username
        );

        return $this->container->get('blacklist-event-for-current-ip', [ $note ]);
    }
}
