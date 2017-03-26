<?php

namespace WPCFG\Ads;

use Mockery;
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
        $loader = Mockery::mock(Loader::class, [ 'add_action' ]);
        $loader->shouldReceive('addAction')
               ->with(
                   'admin_menu',
                   anInstanceOf(I18nPromoter::class),
                   'addYoastI18nModuleToMenuPages',
                   20
               )
               ->once();

        $optionStore = new OptionStore;
        $admin       = new Admin($optionStore);

        I18nPromoter::register($loader, $optionStore, $admin);
    }
}
