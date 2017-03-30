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

use WPCFG\AbstractLoadable;
use WPCFG\Action;
use WPCFG\Blacklist\Event;
use WPCFG\Cloudflare\IpUtil;
use WPCFG\OptionStore;

/**
 * Final class BadLogin.
 *
 * This class blacklist login with bad username.
 */
final class BadLogin extends AbstractLoadable
{
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
     */
    public function __construct(OptionStore $optionStore)
    {
        $this->optionStore = $optionStore;
    }

    /**
     * {@inheritdoc}
     */
    public static function getActions(): array
    {
        return [
            new Action('wp_authenticate', 'emitBlacklistEventIfBadUsername'),
        ];
    }

    /**
     * Emit blacklist event if username is bad.
     *
     * @param string|null $username User input.
     *
     * @void
     */
    public function emitBlacklistEventIfBadUsername($username)
    {
        if (! $this->shouldBlacklist($username)) {
            return;
        }

        $note      = sprintf(
            // Translators: %1$s is the bad username.
            _x('WPCFG: Try to login with bad username: %1$s', '%1$s is the bad username', 'wp-cloudflare-guard'),
            $username
        );
        $currentIp = IpUtil::getCurrentIp();
        $event     = new Event($currentIp, $note);

        do_action('wpcfg_blacklist', $event);
    }

    /**
     * Check whether blacklist should be performed.
     *
     * @param string|null $username User input.
     *
     * @return bool
     */
    private function shouldBlacklist($username): bool
    {
        if (empty($username)) {
            return false;
        }

        if ($this->isDisabled()) {
            return false;
        }

        return $this->isBadUsername($username);
    }

    /**
     * Check whether bad login is enabled.
     *
     * @return bool
     */
    private function isDisabled(): bool
    {
        $disabled = $this->optionStore->get('wpcfg_bad_login', 'disabled');

        return ('1' === $disabled);
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
        $badUsernames  = $this->getNormalizedBadUsernames();
        $inputUsername = $this->normalize($inputUsername);

        return in_array($inputUsername, $badUsernames, true);
    }

    /**
     * Get normalized bad usernames from database.
     *
     * @return array
     */
    private function getNormalizedBadUsernames(): array
    {
        $badUsernames = $this->getBadUsernames();
        $normalized   = array_map([ $this, 'normalize' ], $badUsernames);

        return array_filter($normalized, function ($username) {
            return (! empty($username));
        });
    }

    /**
     * Get bad usernames from database.
     *
     * @return array
     */
    private function getBadUsernames(): array
    {
        $badUsernames = $this->optionStore->get('wpcfg_bad_login', 'bad_usernames');
        if (empty($badUsernames)) {
            return [];
        }

        return explode(',', $badUsernames);
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
}
