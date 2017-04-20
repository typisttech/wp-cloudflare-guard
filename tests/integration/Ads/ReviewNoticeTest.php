<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Ads;

use AspectMock\Test;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;
use TypistTech\WPCFG\Vendor\WP_Review_Me;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Ads\ReviewNotice
 */
class ReviewNoticeTest extends \Codeception\TestCase\WPTestCase
{
    /**
     * @var \TypistTech\WPCFG\IntegrationTester
     */
    protected $tester;

    /**
     * @var ReviewNotice
     */
    private $reviewNotice;

    public function setUp()
    {
        parent::setUp();

        $this->reviewNotice = new ReviewNotice;
    }

    /**
     * @coversNothing
     */
    public function testGetFromContainer()
    {
        $this->assertInstanceOf(
            ReviewNotice::class,
            $this->tester->getContainer()->get(ReviewNotice::class)
        );
    }

    /**
     * @covers ::getHooks
     */
    public function testHookedIntoAdminInit()
    {
        $actual = ReviewNotice::getHooks();

        $expected = [ new Action('admin_init', ReviewNotice::class, 'run') ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::run
     */
    public function testWPReviewMeInitialized()
    {
        $wpReviewMe = Test::double(WP_Review_Me::class);

        $this->reviewNotice->run();

        $wpReviewMe->verifyInvokedMultipleTimes('__construct', 1);

        $params = $wpReviewMe->getCallsForMethod('__construct')[0][0];

        $this->assertSame('plugin', $params['type']);
        $this->assertSame('wp-cloudflare-guard', $params['slug']);
        $this->assertInternalType('string', $params['message']);
    }
}
