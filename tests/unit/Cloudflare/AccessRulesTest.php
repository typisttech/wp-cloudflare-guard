<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Cloudflare;

use Codeception\Test\Unit;
use TypistTech\WPCFG\Vendor\Cloudflare\Zone\Firewall\AccessRules as CloudflareAccessRules;

/**
 * @coversDefaultClass \TypistTech\WPCFG\Cloudflare\AccessRules
 */
class AccessRulesTest extends Unit
{
    /**
     * @covers \TypistTech\WPCFG\Cloudflare\AccessRules
     */
    public function testInstanceOfCloudflareAccessRules()
    {
        $actual = new AccessRules;
        $this->assertInstanceOf(CloudflareAccessRules::class, $actual);
    }
}
