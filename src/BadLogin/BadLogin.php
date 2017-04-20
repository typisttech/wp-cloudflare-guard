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

use TypistTech\WPCFG\Blacklist\Event;
use TypistTech\WPCFG\Cloudflare\Helper;
use TypistTech\WPCFG\LoadableInterface;
use TypistTech\WPCFG\OptionStore;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;

/**
 * Final class BadLogin.
 *
 * This class blacklist login with bad username.
 */
final class BadLogin implements LoadableInterface
{
    /**
     * Cloudflare helper
     *
     * @var Helper
     */
    private $helper;

    /**
     * The WPCFG option store
     *
     * @var OptionStore
     */
    private $optionStore;

    /**
     * BadLogin constructor.
     *
     * @param OptionStore $optionStore The WPCFG option store.
     * @param Helper      $helper      The WPCFG Cloudflare helper.
     */
    public function __construct(OptionStore $optionStore, Helper $helper)
    {
        $this->optionStore = $optionStore;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getHooks(): array
    {
        return [
            new Action('wp_authenticate', __CLASS__, 'emitBlacklistEventIfBadUsername'),
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

        $isBadUsername = apply_filters(
            'wpcfg_is_bad_username',
            $this->isBadUsername($inputUsername),
            $inputUsername
        );

        return true === $isBadUsername;
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
        return in_array(
            $this->normalize($inputUsername),
            $this->getNormalizedBadUsernames(),
            true
        );
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
        return strtolower(
            sanitize_user($username, true)
        );
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
            return ! empty($username);
        });
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

        return new Event(
            $this->helper->getCurrentIp(),
            $note
        );
    }
}
