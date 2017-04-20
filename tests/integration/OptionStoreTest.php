<?php

declare(strict_types=1);

namespace TypistTech\WPCFG;

use Codeception\TestCase\WPTestCase;

/**
 * @coversDefaultClass \TypistTech\WPCFG\OptionStore
 */
class OptionStoreTest extends WPTestCase
{
    /**
     * @var \TypistTech\WPCFG\IntegrationTester
     */
    protected $tester;

    /**
     * @var OptionStore
     */
    private $optionStore;

    public function setUp()
    {
        parent::setUp();

        update_option('wpcfg_cloudflare_email', 'tester@example.com');
        update_option('wpcfg_cloudflare_api_key', 'passkey123');
        update_option('wpcfg_cloudflare_zone_id', 'two46o1');
        update_option('wpcfg_bad_login_bad_usernames', 'tom, mary,peter');

        $this->optionStore = new OptionStore;
    }

    /**
     * @covers ::getApiKey
     */
    public function testGetApiKey()
    {
        $actual = $this->optionStore->getApiKey();
        $this->assertSame('passkey123', $actual);
    }

    /**
     * @covers ::getBadUsernames
     */
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
     * @coversNothing
     */
    public function testGetFromContainer()
    {
        $this->assertInstanceOf(
            OptionStore::class,
            $this->tester->getContainer()->get(OptionStore::class)
        );
    }

    /**
     * @covers ::getBadUsernames
     */
    public function testGetSingleBadUsername()
    {
        update_option('wpcfg_bad_login_bad_usernames', 'tom ');

        $actual = $this->optionStore->getBadUsernames();

        $expected = [ 'tom' ];

        $this->assertSame($expected, $actual);
    }

    /**
     * @covers ::getZoneId
     */
    public function testGetZoneId()
    {
        $actual = $this->optionStore->getZoneId();
        $this->assertSame('two46o1', $actual);
    }
}
