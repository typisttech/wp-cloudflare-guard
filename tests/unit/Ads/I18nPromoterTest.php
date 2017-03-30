<?php

namespace WPCFG\Ads;

use AspectMock\Test;
use WPCFG\Action;
use WPCFG\Admin;
use WPCFG\UnitTester;
use WPCFG\Vendor\Yoast_I18n_WordPressOrg_v2;

/**
 * @coversDefaultClass \WPCFG\Ads\I18nPromoter
 */
class I18nPromoterTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @var I18nPromoter
     */
    private $i18nPromoter;

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

    public function testYoastI18nWordPressOrgV2Initialized()
    {
        $yoastI18nWordPressOrgV2 = Test::double(Yoast_I18n_WordPressOrg_v2::class);

        $this->i18nPromoter->addYoastI18nModuleToMenuPages();

        $yoastI18nWordPressOrgV2->verifyInvokedMultipleTimes('__construct', 2);
        $yoastI18nWordPressOrgV2->verifyInvokedOnce('__construct', [
            [
                'textdomain'  => 'wp-cloudflare-guard',
                'plugin_name' => 'WP Cloudflare Guard',
                'hook'        => 'wpcfg_cloudflare_after_option_form',
            ],
        ]);
        $yoastI18nWordPressOrgV2->verifyInvokedOnce('__construct', [
            [
                'textdomain'  => 'wp-cloudflare-guard',
                'plugin_name' => 'WP Cloudflare Guard',
                'hook'        => 'wpcfg_bad_login_after_option_form',
            ],
        ]);
    }

    protected function _before()
    {
        $container = $this->tester->getContainer();

        $admin = Test::double(
            $container->get(Admin::class),
            [
                'getMenuSlugs' => [
                    'wpcfg-cloudflare',
                    'wpcfg-bad-login',
                ],
            ]
        );
        $container->share(Admin::class, $admin->getObject());

        $this->i18nPromoter = $container->get(I18nPromoter::class);
    }
}