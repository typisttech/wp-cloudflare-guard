<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Helper;

use AspectMock\Test;
use Codeception\Module;
use Codeception\TestInterface;

/**
 * Here you can define custom actions
 * All public methods declared in helper class will be available in $I
 */
class Integration extends Module
{
    /**
     * @var string
     */
    private $remoteAddrBackup;

    public function _after(TestInterface $test)
    {
        Test::clean();

        $_SERVER['REMOTE_ADDR'] = $this->remoteAddrBackup;
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);

        delete_option('wpcfg_cloudflare_email');
        delete_option('wpcfg_cloudflare_api_key');
        delete_option('wpcfg_cloudflare_zone_id');
        delete_option('wpcfg_bad_login_bad_usernames');
    }

    public function _before(TestInterface $test)
    {
        $this->remoteAddrBackup = $_SERVER['REMOTE_ADDR'];
    }
}
