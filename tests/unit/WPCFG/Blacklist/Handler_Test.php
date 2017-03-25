<?php
namespace WPCFG\Blacklist;

use Mockery;
use phpmock\phpunit\PHPMock;
use WPCFG\Loader;
use WPCFG\OptionStore;
use WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules;

/**
 * @coversDefaultClass \WPCFG\Blacklist\Handler
 */
class Handler_Test extends \Codeception\Test\Unit
{
    use PHPMock;

    /**
     * @test
     * @covers \WPCFG\Blacklist\Handler
     */
    public function it_skips_for_non_blacklist_events()
    {
        $access_rules_mock = Mockery::mock(AccessRules::class);
        $access_rules_mock->shouldReceive('setEmail')->never();
        $access_rules_mock->shouldReceive('setAuthKey')->never();
        $access_rules_mock->shouldReceive('create')->never();

        $handler = new Handler(new OptionStore, $access_rules_mock);

        $handler->handle_blacklist();
        $handler->handle_blacklist(null);
        $handler->handle_blacklist(false);
        $handler->handle_blacklist([]);
        $handler->handle_blacklist('');
        $handler->handle_blacklist(123);
    }

    /**
     * @test
     * @covers \WPCFG\Blacklist\Handler
     */
    public function it_handle_blacklist_event()
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

        $access_rules_mock = Mockery::mock(AccessRules::class);
        $access_rules_mock->shouldReceive('setEmail')
                          ->with('email@example.com')
                          ->once()
                          ->ordered();

        $access_rules_mock->shouldReceive('setAuthKey')
                          ->with('API_KEY_123')
                          ->once()
                          ->ordered();

        $access_rules_mock->shouldReceive('create')
                          ->with(
                              'abc123',
                              'block',
                              equalTo($configuration),
                              'some note'
                          )
                          ->once()
                          ->ordered();

        $handler = new Handler(new OptionStore, $access_rules_mock);
        $handler->handle_blacklist($event);
    }

    /**
     * @test
     * @covers ::register
     */
    public function it_hooked_into_wpcfg_blacklist()
    {
        $loader = Mockery::mock(Loader::class, [ 'add_action' ]);
        $loader->shouldReceive('add_action')
               ->with(
                   'wpcfg_blacklist',
                   anInstanceOf(Handler::class),
                   'handle_blacklist'
               )
               ->once();
        Handler::register($loader, new OptionStore);
    }

    protected function _after()
    {
        delete_option('wpcfg_cloudflare');
    }
}
