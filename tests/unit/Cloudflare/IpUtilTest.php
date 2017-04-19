<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Cloudflare;

use AspectMock\Test;
use Codeception\Test\Unit;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Cloudflare\IpUtil
 */
class IpUtilTest extends Unit
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

        $actual = IpUtil::getCurrentIp();

        $this->assertSame($connectingIp, $actual);
    }

    /**
     * @covers ::getCurrentIp
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
        unset($_SERVER['REMOTE_ADDR']);
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
    }

    protected function _before()
    {
        Test::func(__NAMESPACE__, 'wp_unslash', function (string $text) {
            return $text;
        });

        Test::func(__NAMESPACE__, 'sanitize_text_field', function (string $text) {
            return $text;
        });
    }
}
