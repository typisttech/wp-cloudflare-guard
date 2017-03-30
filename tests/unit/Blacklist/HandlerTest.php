<?php

namespace WPCFG\Blacklist;

use AspectMock\Test;
use WPCFG\Action;
use WPCFG\OptionStore;
use WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules;

/**
 * @coversDefaultClass \WPCFG\Blacklist\Handler
 */
class HandlerTest extends \Codeception\Test\Unit
{
    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testHandleBlacklistEvent()
    {
        $accessRulesMock = Test::double(new AccessRules);
        $event           = new Event('127.0.0.1', 'some note');
        $handler         = new Handler(new OptionStore, $accessRulesMock->getObject());

        $handler->handleBlacklist($event);

        $expectedCreate = [
            'abc123',
            'block',
            (object) [
                'target' => 'ip',
                'value'  => '127.0.0.1',
            ],
            'some note',
        ];

        $accessRulesMock->verifyInvokedMultipleTimes('create', 1);
        $actualCreate = $accessRulesMock->getCallsForMethod('create')[0];
        $this->assertEquals($expectedCreate, $actualCreate);

        $accessRulesMock->verifyInvokedMultipleTimes('setEmail', 1);
        $accessRulesMock->verifyInvokedOnce('setEmail', [ 'email@example.com' ]);

        $accessRulesMock->verifyInvokedMultipleTimes('setAuthKey', 1);
        $accessRulesMock->verifyInvokedOnce('setAuthKey', [ 'API_KEY_123' ]);
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
        $accessRulesMock = Test::double(new AccessRules);
        $handler         = new Handler(new OptionStore, $accessRulesMock->getObject());

        $handler->handleBlacklist();
        $handler->handleBlacklist(null);
        $handler->handleBlacklist(false);
        $handler->handleBlacklist([]);
        $handler->handleBlacklist('');
        $handler->handleBlacklist(123);

        $accessRulesMock->verifyNeverInvoked('setEmail');
        $accessRulesMock->verifyNeverInvoked('setAuthKey');
        $accessRulesMock->verifyNeverInvoked('create');
    }

    protected function _after()
    {
        delete_option('wpcfg_cloudflare');
    }

    protected function _before()
    {
        update_option('wpcfg_cloudflare', [
            'email'   => 'email@example.com',
            'api_key' => 'API_KEY_123',
            'zone_id' => 'abc123',
        ]);
    }
}
