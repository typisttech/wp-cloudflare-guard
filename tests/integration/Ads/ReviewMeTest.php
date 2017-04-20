<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Ads;

use AspectMock\Test;
use TypistTech\WPCFG\Vendor\TypistTech\WPContainedHook\Action;
use TypistTech\WPCFG\Vendor\WP_Review_Me;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Ads\ReviewMe
 */
class ReviewMeTest extends \Codeception\TestCase\WPTestCase
{
    /**
     * @var ReviewMe
     */
    private $reviewMe;

    public function setUp()
    {
        parent::setUp();

        $this->reviewMe = new ReviewMe;
    }

    /**
     * @covers ::getHooks
     */
    public function testHookedIntoAdminInit()
    {
        $actual = ReviewMe::getHooks();

        $expected = [ new Action('admin_init', ReviewMe::class, 'run') ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @covers ::run
     */
    public function testWPReviewMeInitialized()
    {
        $wpReviewMe = Test::double(WP_Review_Me::class);

        $this->reviewMe->run();

        $wpReviewMe->verifyInvokedMultipleTimes('__construct', 1);

        $params = $wpReviewMe->getCallsForMethod('__construct')[0][0];

        $this->assertSame('plugin', $params['type']);
        $this->assertSame('wp-cloudflare-guard', $params['slug']);
        $this->assertInternalType('string', $params['message']);
    }
}
