<?php

namespace WPCFG\Bad_Login;

use Mockery;
use phpmock\phpunit\PHPMock;
use WPCFG\Blacklist\Event;
use WPCFG\Loader;
use WPCFG\Option_Store;

/**
 * @coversDefaultClass \WPCFG\Bad_Login\Bad_Login
 */
class Bad_Login_Test extends \Codeception\Test\Unit
{
    use PHPMock;

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_skips_for_empty_username()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => '' ]);
        $this->do_action_mock->expects($this->never());
        $this->bad_login->emit_blacklist_event_if_bad_username('');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_skips_if_no_bad_username_is_saved()
    {
        $this->do_action_mock->expects($this->never());
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-boy');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_skips_for_false_username()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => false ]);
        $this->do_action_mock->expects($this->never());
        $this->bad_login->emit_blacklist_event_if_bad_username(false);
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_skips_for_null_username()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => null ]);
        $this->do_action_mock->expects($this->never());
        $this->bad_login->emit_blacklist_event_if_bad_username(null);
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_skips_when_disabled()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy',
            'disabled'      => '1',
        ]);
        $this->do_action_mock->expects($this->never());
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-boy');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_skips_for_not_bad_username()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => 'bad-boy, bad-girl' ]);
        $this->do_action_mock->expects($this->never());
        $this->bad_login->emit_blacklist_event_if_bad_username('good-boy');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_can_trigger_blacklist_event_when_multiple_bad_usernames_are_saved()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => 'bad-boy, bad-girl' ]);
        $this->do_action_mock->expects($this->exactly(2))
                             ->with(
                                 'wpcfg_blacklist',
                                 $this->isInstanceOf(Event::class)
                             );
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-boy');
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-girl');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_can_trigger_blacklist_event_when_single_bad_username_is_saved()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => 'bad-boy' ]);
        $this->do_action_mock->expects($this->once())
                             ->with(
                                 'wpcfg_blacklist',
                                 $this->isInstanceOf(Event::class)
                             );
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-boy');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_normalize_input_username()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => 'bad-boy, bad-girl' ]);
        $this->do_action_mock->expects($this->once())
                             ->with(
                                 'wpcfg_blacklist',
                                 $this->isInstanceOf(Event::class)
                             );
        $this->bad_login->emit_blacklist_event_if_bad_username('#b!{a}d;-b>oy<?');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login
     */
    public function it_normalize_saved_bad_usernames()
    {
        update_option('wpcfg_bad_login', [ 'bad_usernames' => ' #b!{a}d;-b>oy<?, ?>b}!a{d->>gir!>##l<?girl ' ]);
        $this->do_action_mock->expects($this->exactly(2))
                             ->with(
                                 'wpcfg_blacklist',
                                 $this->isInstanceOf(Event::class)
                             );
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-boy');
        $this->bad_login->emit_blacklist_event_if_bad_username('bad-girl');
    }

    /**
     * @test
     * @covers \WPCFG\Bad_Login\Bad_Login::register
     */
    public function it_hooked_into_wp_authenticate()
    {
        $loader = Mockery::mock(Loader::class, [ 'add_action' ]);
        $loader->shouldReceive('add_action')
               ->with(
                   'wp_authenticate',
                   anInstanceOf(Bad_Login::class),
                   'emit_blacklist_event_if_bad_username'
               )
               ->once();
        Bad_Login::register($loader, new Option_Store);
    }

    protected function _before()
    {
        $this->bad_login      = new Bad_Login(new Option_Store);
        $this->do_action_mock = $this->getFunctionMock(__NAMESPACE__, 'do_action');
    }

    protected function _after()
    {
        delete_option('wpcfg_bad_login');
    }
}
