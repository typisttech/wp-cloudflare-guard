<?php

namespace WPCFG\Blacklist;

use Mockery;
use phpmock\phpunit\PHPMock;
use WPCFG\Action;
use WPCFG\OptionStore;
use WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules;

/**
 * @coversDefaultClass \WPCFG\Blacklist\Handler
 */
class HandlerTest extends \Codeception\Test\Unit
{
    use PHPMock;

    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testHandleBlacklistEvent()
    {
        update_option('wpcfg_cloudflare', [
            'email'   => 'email@example.com',
            'api_key' => 'API_KEY_123',
            'zone_id' => 'abc123',
        ]);
        $event = new Event('127.0.0.1', 'some note');

        $configuration = (object) [
            'target' => 'ip',
            'value'  => '127.0.0.1',
        ];

        $accessRulesMock = Mockery::mock(AccessRules::class);
        $accessRulesMock->shouldReceive('setEmail')
                        ->with('email@example.com')
                        ->once()
                        ->ordered();

        $accessRulesMock->shouldReceive('setAuthKey')
                        ->with('API_KEY_123')
                        ->once()
                        ->ordered();

        $accessRulesMock->shouldReceive('create')
                        ->with(
                            'abc123',
                            'block',
                            equalTo($configuration),
                            'some note'
                        )
                        ->once()
                        ->ordered();

        $handler = new Handler(new OptionStore, $accessRulesMock);
        $handler->handleBlacklist($event);
    }

    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testHookedIntoWpcfgBlacklist()
    {
        $actual = Handler::getActions();

        $expected = [
            new Action('wpcfg_blacklist', 'handleBlacklist'),
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testSkipsForNonBlacklistEvents()
    {
        $accessRulesMock = Mockery::mock(AccessRules::class);
        $accessRulesMock->shouldReceive('setEmail')->never();
        $accessRulesMock->shouldReceive('setAuthKey')->never();
        $accessRulesMock->shouldReceive('create')->never();

        $handler = new Handler(new OptionStore, $accessRulesMock);

        $handler->handleBlacklist();
        $handler->handleBlacklist(null);
        $handler->handleBlacklist(false);
        $handler->handleBlacklist([]);
        $handler->handleBlacklist('');
        $handler->handleBlacklist(123);
    }

    protected function _after()
    {
        delete_option('wpcfg_cloudflare');
    }
}
