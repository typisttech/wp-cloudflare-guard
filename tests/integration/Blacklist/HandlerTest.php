<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Blacklist;

use AspectMock\Test;
use Codeception\TestCase\WPTestCase;
use TypistTech\WPCFG\Cloudflare\AccessRules;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;

/**
 * @coversDefaultClass TypistTech\WPCFG\Blacklist\Handler
 */
class HandlerTest extends WPTestCase
{
    /**
     * @var \TypistTech\WPCFG\IntegrationTester
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

    public function setUp()
    {
        parent::setUp();

        $container = $this->tester->getContainer();

        $this->accessRules = Test::double(
            $container->get(AccessRules::class),
            [
                'create' => [ true ],
            ]
        );
        $container->add(AccessRules::class, $this->accessRules->getObject());

        $this->handler = $container->get(Handler::class);
    }

    /**
     * @coversNothing
     */
    public function testGetFromContainer()
    {
        $this->assertInstanceOf(
            Handler::class,
            $this->tester->getContainer()->get(Handler::class)
        );
    }

    /**
     * @covers \TypistTech\WPCFG\Blacklist\Handler
     */
    public function testHandleBlacklistEvent()
    {
        $event = new Event('127.0.0.1', 'some note');

        $this->handler->handleBlacklist($event);

        $expectedCreate = [
            'block',
            [
                'target' => 'ip',
                'value' => '127.0.0.1',
            ],
            'some note',
        ];

        $this->accessRules->verifyInvokedMultipleTimes('create', 1);
        $actualCreate = $this->accessRules->getCallsForMethod('create')[0];
        $this->assertEquals($expectedCreate, $actualCreate);
    }

    /**
     * @covers ::getHooks
     */
    public function testHookedIntoWpcfgBlacklist()
    {
        $actual = Handler::getHooks();

        $expected = [
            new Action('wpcfg_blacklist', Handler::class, 'handleBlacklist'),
        ];

        $this->assertEquals($expected, $actual);
    }
}
