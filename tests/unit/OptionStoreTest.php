<?php

declare(strict_types=1);

namespace WPCFG;

/**
 * @coversDefaultClass \WPCFG\OptionStore
 */
class OptionStoreTest extends \Codeception\Test\Unit
{
    /**
     * @var OptionStore
     */
    private $optionStore;

    /**
     * @covers ::getApiKey
     */
    public function testGetApiKey()
    {
        $actual = $this->optionStore->getApiKey();
        $this->assertSame('passkey123', $actual);
    }

    public function testGetBadUsernames()
    {
        $actual = $this->optionStore->getBadUsernames();

        $expected = [ 'tom', 'mary', 'peter' ];

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers ::getEmail
     */
    public function testGetEmail()
    {
        $actual = $this->optionStore->getEmail();
        $this->assertSame('tester@example.com', $actual);
    }

    /**
     * @covers ::getZoneId
     */
    public function testGetZoneId()
    {
        $actual = $this->optionStore->getZoneId();
        $this->assertSame('two46o1', $actual);
    }

    protected function _after()
    {
        delete_option('wpcfg_cloudflare_email');
        delete_option('wpcfg_cloudflare_api_key');
        delete_option('wpcfg_cloudflare_zone_id');
        delete_option('wpcfg_cloudflare_zone_id');
    }

    protected function _before()
    {
        update_option('wpcfg_cloudflare_email', 'tester@example.com');
        update_option('wpcfg_cloudflare_api_key', 'passkey123');
        update_option('wpcfg_cloudflare_zone_id', 'two46o1');
        update_option('wpcfg_bad_login_bad_usernames', 'tom, mary,peter');

        $this->optionStore = new OptionStore;
    }
}
