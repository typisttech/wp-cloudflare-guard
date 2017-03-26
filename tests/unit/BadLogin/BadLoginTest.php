<?php

namespace WPCFG\BadLogin;

use Mockery;
use phpmock\phpunit\PHPMock;
use WPCFG\Blacklist\Event;
use WPCFG\Loader;
use WPCFG\OptionStore;

/**
 * @coversDefaultClass \WPCFG\BadLogin\BadLogin
 */
class BadLoginTest extends \Codeception\Test\Unit
{
    use PHPMock;

    /**
     * @var BadLogin
     */
    private $badLogin;

    // @codingStandardsIgnoreStart
    private $doActionMock;

    // @codingStandardsIgnoreEnd

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testCanTriggerBlacklistEventWhenMultipleBadUsernamesAreSaved()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy, bad-girl',
        ]);
        $this->doActionMock->expects($this->exactly(2))
                           ->with(
                               'wpcfg_blacklist',
                               $this->isInstanceOf(Event::class)
                           );
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-girl');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testCanTriggerBlacklistEventWhenSingleBadUsernameIsSaved()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy',
        ]);
        $this->doActionMock->expects($this->once())
                           ->with(
                               'wpcfg_blacklist',
                               $this->isInstanceOf(Event::class)
                           );
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testNormalizeInputUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy, bad-girl',
        ]);
        $this->doActionMock->expects($this->once())
                           ->with(
                               'wpcfg_blacklist',
                               $this->isInstanceOf(Event::class)
                           );
        $this->badLogin->emitBlacklistEventIfBadUsername('#b!{a}d;-b>oy<?');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testNormalizeSavedBadUsernames()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => ' #b!{a}d;-b>oy<?, ?>b}!a{d->>gir!>##l<?girl ',
        ]);
        $this->doActionMock->expects($this->exactly(2))
                           ->with(
                               'wpcfg_blacklist',
                               $this->isInstanceOf(Event::class)
                           );
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-girl');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForEmptyUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => '',
        ]);
        $this->doActionMock->expects($this->never());
        $this->badLogin->emitBlacklistEventIfBadUsername('');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForFalseUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => false,
        ]);
        $this->doActionMock->expects($this->never());
        $this->badLogin->emitBlacklistEventIfBadUsername(false);
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForNotBadUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => 'bad-boy, bad-girl',
        ]);
        $this->doActionMock->expects($this->never());
        $this->badLogin->emitBlacklistEventIfBadUsername('good-boy');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsForNullUsername()
    {
        update_option('wpcfg_bad_login', [
            'bad_usernames' => null,
        ]);
        $this->doActionMock->expects($this->never());
        $this->badLogin->emitBlacklistEventIfBadUsername(null);
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testSkipsIfNoBadUsernameIsSaved()
    {
        $this->doActionMock->expects($this->never());
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
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
        $this->doActionMock->expects($this->never());
        $this->badLogin->emitBlacklistEventIfBadUsername('bad-boy');
    }

    /**
     * @covers \WPCFG\BadLogin\BadLogin
     */
    public function testsHookedIntoWpAuthenticate()
    {
        $loader = Mockery::mock(Loader::class, [ 'addAction' ]);
        $loader->shouldReceive('addAction')
               ->with(
                   'wp_authenticate',
                   anInstanceOf(BadLogin::class),
                   'emitBlacklistEventIfBadUsername'
               )
               ->once();
        BadLogin::register($loader, new OptionStore);
    }

    protected function _after()
    {
        delete_option('wpcfg_bad_login');
    }

    protected function _before()
    {
        $this->badLogin     = new BadLogin(new OptionStore);
        $this->doActionMock = $this->getFunctionMock(__NAMESPACE__, 'do_action');
    }
}
