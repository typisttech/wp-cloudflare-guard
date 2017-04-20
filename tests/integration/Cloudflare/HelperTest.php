<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Cloudflare;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Cloudflare\Helper
 */
class HelperTest extends \Codeception\TestCase\WPTestCase
{
    /**
     * @covers ::getCurrentIp
     */
    public function testGetCurrentIp()
    {
        $remoteAddr = '103.21.244.2';
        $connectingIp = '8.8.8.8';

        $_SERVER['REMOTE_ADDR'] = $remoteAddr;
        $_SERVER['HTTP_CF_CONNECTING_IP'] = $connectingIp;

        $helper = new Helper;
        $actual = $helper->getCurrentIp();

        $this->assertSame($connectingIp, $actual);
    }

    /**
     * @covers ::getCurrentIp
     */
    public function testGetCurrentIpWhenNotComingThroughCloudflare()
    {
        $remoteAddr = '103.21.244.2';

        $_SERVER['REMOTE_ADDR'] = $remoteAddr;

        $helper = new Helper;
        $actual = $helper->getCurrentIp();

        $this->assertSame($remoteAddr, $actual);
    }
}
