<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Cloudflare;

use AspectMock\Test;
use Codeception\TestCase\WPTestCase;
use TypistTech\WPCFG\Container;
use TypistTech\WPCFG\OptionStore;
use TypistTech\WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules as CloudflareAccessRules;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Cloudflare\AccessRules
 */
class AccessRulesTest extends WPTestCase
{
    /**
     * @var \TypistTech\WPCFG\IntegrationTester
     */
    protected $tester;

    /**
     * @var AccessRules
     */
    private $accessRules;

    /**
     * @var \AspectMock\Proxy\InstanceProxy
     */
    private $cloudflareAccessRules;

    /**
     * @var Container
     */
    private $container;

    public function setUp()
    {
        parent::setUp();

        $this->cloudflareAccessRules = Test::double(
            new CloudflareAccessRules,
            [
                'create' => [ true ],
            ]
        );

        $this->container = $this->tester->getContainer();

        $optionStore = Test::double(
            $this->container->get(OptionStore::class),
            [
                'getApiKey' => 'my-api-key',
                'getEmail' => 'me@example.com',
                'getZoneId' => 'my-zone',
            ]
        )->getObject();
        $this->container->add(OptionStore::class, $optionStore);

        $this->accessRules = new AccessRules(
            $optionStore,
            $this->cloudflareAccessRules->getObject()
        );
    }

    /**
     * @covers ::create
     */
    public function testCreate()
    {
        $this->accessRules->create(
            'block',
            [
                'target' => 'ip',
                'value' => '127.0.0.1',
            ],
            'some note'
        );

        $expectedParams = [
            'my-zone',
            'block',
            [
                'target' => 'ip',
                'value' => '127.0.0.1',
            ],
            'some note',
        ];

        $this->cloudflareAccessRules->verifyInvokedMultipleTimes('setEmail', 1);
        $this->cloudflareAccessRules->verifyInvokedOnce('setEmail', [ 'me@example.com' ]);

        $this->cloudflareAccessRules->verifyInvokedMultipleTimes('setAuthKey', 1);
        $this->cloudflareAccessRules->verifyInvokedOnce('setAuthKey', [ 'my-api-key' ]);

        $this->cloudflareAccessRules->verifyInvokedMultipleTimes('create', 1);
        $this->cloudflareAccessRules->verifyInvokedOnce('create', $expectedParams);
    }

    /**
     * @coversNothing
     */
    public function testGetFromContainer()
    {
        $this->assertInstanceOf(
            AccessRules::class,
            $this->tester->getContainer()->get(AccessRules::class)
        );
    }
}
