<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\BadLogin;

use AspectMock\Test;
use Codeception\TestCase\WPTestCase;
use TypistTech\WPCFG\Action;
use TypistTech\WPCFG\Blacklist\Event;

/**
 * @coversDefaultClass \TypistTech\WPCFG\BadLogin\BadLogin
 */
class BadLoginTest extends WPTestCase
{
    /**
     * @var \TypistTech\WPCFG\IntegrationTester
     */
    protected $tester;

    /**
     * @var BadLogin
     */
    private $badLogin;

    /**
     * @var \AspectMock\Proxy\FuncProxy
     */
    private $doActionMock;

    public function setUp()
    {
        parent::setUp();

        $container = $this->tester->getContainer();
        $this->badLogin = $container->get(BadLogin::class);
        $this->doActionMock = Test::func(__NAMESPACE__, 'do_action', 'done');
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testCanTriggerBlacklistEventWhenMultipleBadUsernamesAreSaved()
    {
        update_option('wpcfg_bad_login_bad_usernames', 'bad-boy, bad-girl');

        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-girl');

        $actual = $this->doActionMock->getCallsForMethod('do_action');

        $expected = [
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: bad-boy') ],
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: bad-girl') ],
        ];

        $this->assertEquals($expected, $actual);
        $this->doActionMock->verifyInvokedMultipleTimes(2);
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testCanTriggerBlacklistEventWhenSingleBadUsernameIsSaved()
    {
        update_option('wpcfg_bad_login_bad_usernames', 'bad-boy');

        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');

        $actual = $this->doActionMock->getCallsForMethod('do_action');

        $expected = [
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: bad-boy') ],
        ];

        $this->assertEquals($expected, $actual);
        $this->doActionMock->verifyInvokedMultipleTimes(1);
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testNormalizeInputUsername()
    {
        update_option('wpcfg_bad_login_bad_usernames', 'bad-boy, bad-girl');

        $this->badLogin->emitBlacklistEventIfBadUsername('#b!{a}d;-b>oy<?');

        $actual = $this->doActionMock->getCallsForMethod('do_action');

        $expected = [
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: #b!{a}d;-b>oy<?') ],
        ];

        $this->assertEquals($expected, $actual);
        $this->doActionMock->verifyInvokedMultipleTimes(1);
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testNormalizeSavedBadUsernames()
    {
        update_option('wpcfg_bad_login_bad_usernames', ' #b!{a}d;-b>oy<?, ?>b}!a{d->>gir!>##l<?girl ');

        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-girl');

        $actual = $this->doActionMock->getCallsForMethod('do_action');

        $expected = [
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: bad-boy') ],
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: bad-girl') ],
        ];

        $this->assertEquals($expected, $actual);
        $this->doActionMock->verifyInvokedMultipleTimes(2);
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testSkipsEmptyUsername()
    {
        update_option('wpcfg_bad_login_bad_usernames', '');

        $this->badLogin->emitBlacklistEventIfBadUsername('');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForNotBadUsername()
    {
        update_option('wpcfg_bad_login_bad_usernames', 'bad-boy, bad-girl');

        $this->badLogin->emitBlacklistEventIfBadUsername('good-boy');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \TypistTech\WPCFG\BadLogin\BadLogin
     */
    public function testSkipsIfNoBadUsernameIsSaved()
    {
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers ::getHooks
     */
    public function testsHookedIntoWpAuthenticate()
    {
        $actual = BadLogin::getHooks();

        $expected = [
            new Action(BadLogin::class, 'wp_authenticate', 'emitBlacklistEventIfBadUsername'),
        ];

        $this->assertEquals($expected, $actual);
    }
}
