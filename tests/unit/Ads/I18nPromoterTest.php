<?php

namespace WPCFG\Ads;

use Mockery;
use WPCFG\Action;
use WPCFG\Admin;
use WPCFG\Loader;
use WPCFG\OptionStore;

/**
 * @coversDefaultClass \WPCFG\Ads\I18nPromoter
 */
class I18nPromoterTest extends \Codeception\Test\Unit
{
    /**
     * @covers \WPCFG\Ads\I18nPromoter
     */
    public function testHookedIntoAdminMenu()
    {
        $actual = I18nPromoter::getActions();

        $expected = [
            new Action('admin_menu', 'addYoastI18nModuleToMenuPages', 20),
        ];

        $this->assertEquals($expected, $actual);
    }
}
