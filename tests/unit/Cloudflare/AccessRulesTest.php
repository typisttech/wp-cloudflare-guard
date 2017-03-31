<?php

namespace WPCFG\Cloudflare;

use WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules as CloudflareAccessRules;

/**
 * @coversDefaultClass \WPCFG\Cloudflare\AccessRules
 */
class AccessRulesTest extends \Codeception\Test\Unit
{
    /**
     * @covers \WPCFG\Cloudflare\AccessRules
     */
    public function testInstanceOfCloudflareAccessRules()
    {
        $actual = new AccessRules;
        $this->assertInstanceOf(CloudflareAccessRules::class, $actual);
    }
}
