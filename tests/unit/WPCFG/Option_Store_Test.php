<?php
namespace WPCFG;

/**
 * @coversDefaultClass \WPCFG\OptionStore
 */
class Option_Store_Test extends \Codeception\Test\Unit
{
    /**
     * @var OptionStore
     */
    private $option_store;

    /**
     * @covers ::get_email
     */
    public function test_get_email()
    {
        $actual = $this->option_store->get_email();
        $this->assertSame('tester@example.com', $actual);
    }

    /**
     * @covers ::get_api_key
     */
    public function test_get_api_key()
    {
        $actual = $this->option_store->get_api_key();
        $this->assertSame('passkey123', $actual);
    }

    /**
     * @covers ::get_zone_id
     */
    public function test_get_zone_id()
    {
        $actual = $this->option_store->get_zone_id();
        $this->assertSame('two46o1', $actual);
    }

    protected function _before()
    {
        update_option('wpcfg_cloudflare', [
            'email'   => 'tester@example.com',
            'api_key' => 'passkey123',
            'zone_id' => 'two46o1',
        ]);

        $this->option_store = new OptionStore;
    }

    protected function _after()
    {
        delete_option('wpcfg_cloudflare');
    }
}
