<?php

declare(strict_types=1);

namespace WPCFG;

/**
 * @coversDefaultClass \WPCFG\OptionStore
 */
class OptionStoreTest extends \Codeception\Test\Unit
{
    /**
     * @var OptionStore
     */
    private $optionStore;

    /**
     * @covers \WPCFG\OptionStore
     */
    public function testGetApiKey()
    {
        $actual = $this->optionStore->getApiKey();
        $this->assertSame('passkey123', $actual);
    }

    /**
     * @covers \WPCFG\OptionStore
     */
    public function testGetEmail()
    {
        $actual = $this->optionStore->getEmail();
        $this->assertSame('tester@example.com', $actual);
    }

    /**
     * @covers \WPCFG\OptionStore
     */
    public function testGetZoneId()
    {
        $actual = $this->optionStore->getZoneId();
        $this->assertSame('two46o1', $actual);
    }

    protected function _after()
    {
        delete_option('wpcfg_cloudflare');
    }

    protected function _before()
    {
        update_option('wpcfg_cloudflare', [
            'email' => 'tester@example.com',
            'api_key' => 'passkey123',
            'zone_id' => 'two46o1',
        ]);

        $this->optionStore = new OptionStore;
    }
}
