<?php

namespace WPCFG\Blacklist;

use AspectMock\Test;
use WPCFG\Action;
use WPCFG\Cloudflare\AccessRules;

/**
 * @coversDefaultClass \WPCFG\Blacklist\Handler
 */
class HandlerTest extends \Codeception\Test\Unit
{
    /**
     * @var \WPCFG\UnitTester;
     */
    protected $tester;

    /**
     * @var \AspectMock\Proxy\InstanceProxy
     */
    private $accessRules;

    /**
     * @var Handler
     */
    private $handler;

    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testHandleBlacklistEvent()
    {
        $event = new Event('127.0.0.1', 'some note');

        $this->handler->handleBlacklist($event);

        $expectedCreate = [
            'abc123',
            'block',
            (object) [
                'target' => 'ip',
                'value'  => '127.0.0.1',
            ],
            'some note',
        ];

        $this->accessRules->verifyInvokedMultipleTimes('create', 1);
        $actualCreate = $this->accessRules->getCallsForMethod('create')[0];
        $this->assertEquals($expectedCreate, $actualCreate);

        $this->accessRules->verifyInvokedMultipleTimes('setEmail', 1);
        $this->accessRules->verifyInvokedOnce('setEmail', [ 'email@example.com' ]);

        $this->accessRules->verifyInvokedMultipleTimes('setAuthKey', 1);
        $this->accessRules->verifyInvokedOnce('setAuthKey', [ 'API_KEY_123' ]);
    }

    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testHookedIntoWpcfgBlacklist()
    {
        $actual = Handler::getHooks();

        $expected = [
            new Action(Handler::class, 'wpcfg_blacklist', 'handleBlacklist'),
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers \WPCFG\Blacklist\Handler
     */
    public function testSkipsForNonBlacklistEvents()
    {
        $this->handler->handleBlacklist();
        $this->handler->handleBlacklist(null);
        $this->handler->handleBlacklist(false);
        $this->handler->handleBlacklist([]);
        $this->handler->handleBlacklist('');
        $this->handler->handleBlacklist(123);

        $this->accessRules->verifyNeverInvoked('setEmail');
        $this->accessRules->verifyNeverInvoked('setAuthKey');
        $this->accessRules->verifyNeverInvoked('create');
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

        $container = $this->tester->getContainer();

        $this->accessRules = Test::double(
            $container->get(AccessRules::class),
            [
                'create' => null,
            ]
        );
        $container->add(AccessRules::class, $this->accessRules->getObject());

        $this->handler = $container->get(Handler::class);
    }
}
