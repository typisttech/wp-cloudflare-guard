<?php

namespace WPCFG;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */
    public function amOnWPCFGSettingPage()
    {
        $I = $this;
        $I->loginAsAdmin();
        $I->waitForText('Dashboard', 10, 'h1');
        $I->seeLink('WP Cloudflare Guard');
        $I->click('WP Cloudflare Guard');
        $I->click('WP Cloudflare Guard');
        $I->waitForText('WP Cloudflare Guard', 10, 'h1');
        $I->seeInCurrentUrl('/wp-admin/admin.php?page=wpcfg_cloudflare');
    }
}
