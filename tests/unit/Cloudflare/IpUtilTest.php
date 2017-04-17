<?php

declare(strict_types=1);

namespace WPCFG\Cloudflare;

/**
 * @coversDefaultClass \WPCFG\Cloudflare\IpUtil
 */
class IpUtilTest extends \Codeception\Test\Unit
{
    /**
     * @var string
     */
    private $remoteAddrBackup;

    /**
     * @covers \WPCFG\Cloudflare\IpUtil
     */
    public function testGetCurrentIp()
    {
        $remoteAddr = '103.21.244.2';
        $connectingIp = '8.8.8.8';

        $_SERVER['REMOTE_ADDR'] = $remoteAddr;
        $_SERVER['HTTP_CF_CONNECTING_IP'] = $connectingIp;

        $actual = IpUtil::getCurrentIp();

        $this->assertSame($connectingIp, $actual);
    }

    /**
     * @covers \WPCFG\Cloudflare\IpUtil
     */
    public function testGetCurrentIpWhenNotComingThroughCloudflare()
    {
        $remoteAddr = '103.21.244.2';

        $_SERVER['REMOTE_ADDR'] = $remoteAddr;

        $actual = IpUtil::getCurrentIp();

        $this->assertSame($remoteAddr, $actual);
    }

    protected function _after()
    {
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
        $_SERVER['REMOTE_ADDR'] = $this->remoteAddrBackup;
    }

    protected function _before()
    {
        $this->remoteAddrBackup = $_SERVER['REMOTE_ADDR'];
    }
}
