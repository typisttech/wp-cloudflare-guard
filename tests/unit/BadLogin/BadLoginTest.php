<?php

namespace WPCFG\BadLogin;

use AspectMock\Test;
use WPCFG\Action;
use WPCFG\Blacklist\Event;
use WPCFG\OptionStore;

/**
 * @coversDefaultClass \WPCFG\BadLogin\BadLogin
 */
class BadLoginTest extends \Codeception\Test\Unit
{
    /**
     * @var BadLogin
     */
    private $badLogin;

    /**
     * @var \AspectMock\Proxy\FuncProxy
     */
    private $doActionMock;

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testCanTriggerBlacklistEventWhenMultipleBadUsernamesAreSaved()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy, bad-girl',
        ]);

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
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testCanTriggerBlacklistEventWhenSingleBadUsernameIsSaved()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy',
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');

        $actual = $this->doActionMock->getCallsForMethod('do_action');

        $expected = [
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: bad-boy') ],
        ];

        $this->assertEquals($expected, $actual);
        $this->doActionMock->verifyInvokedMultipleTimes(1);
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testNormalizeInputUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy, bad-girl',
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername('#b!{a}d;-b>oy<?');

        $actual = $this->doActionMock->getCallsForMethod('do_action');

        $expected = [
            [ 'wpcfg_blacklist', new Event('127.0.0.1', 'WPCFG: Try to login with bad username: #b!{a}d;-b>oy<?') ],
        ];

        $this->assertEquals($expected, $actual);
        $this->doActionMock->verifyInvokedMultipleTimes(1);
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testNormalizeSavedBadUsernames()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => ' #b!{a}d;-b>oy<?, ?>b}!a{d->>gir!>##l<?girl ',
        ]);

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
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForEmptyUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => '',
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername('');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForFalseUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => false,
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername(false);

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForNotBadUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy, bad-girl',
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername('good-boy');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForNullUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => null,
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername(null);

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsIfNoBadUsernameIsSaved()
    {
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsWhenDisabled()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy',
            'disabled'      => '1',
        ]);

        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');

        $this->doActionMock->verifyNeverInvoked();
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testsHookedIntoWpAuthenticate()
    {
        $actual = BadLogin::getActions();

        $expected = [
            new Action('wp_authenticate', 'emitBlacklistEventIfBadUsername'),
        ];

        $this->assertEquals($expected, $actual);
    }

    protected function _after()
    {
        delete_option('wpcfg_bad_login');
    }

    protected function _before()
    {
        $this->badLogin     = new BadLogin(new OptionStore);
        $this->doActionMock = Test::func(__NAMESPACE__, 'do_action', 'done');
    }
}
