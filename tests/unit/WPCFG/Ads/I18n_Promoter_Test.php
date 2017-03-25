<?php
namespace WPCFG\Ads;

use Mockery;
use WPCFG\Admin;
use WPCFG\Loader;
use WPCFG\OptionStore;

/**
 * @coversDefaultClass \WPCFG\Ads\I18n_Promoter
 */
class I18n_Promoter_Test extends \Codeception\Test\Unit
{
    /**
     * @test
     * @covers ::register
     */
    public function it_hooked_into_admin_menu()
    {
        $loader = Mockery::mock(Loader::class, [ 'add_action' ]);
        $loader->shouldReceive('add_action')
               ->with(
                   'admin_menu',
                   anInstanceOf(I18n_Promoter::class),
                   'add_yoast_i18n_module_to_all_wpcfg_menu_pages',
                   20
               )
               ->once();

        $option_store = new OptionStore;
        $admin = new Admin($option_store);

        I18n_Promoter::register($loader, $option_store, $admin);
    }
}
