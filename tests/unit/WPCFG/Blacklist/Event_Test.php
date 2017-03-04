<?php
namespace WPCFG\Blacklist;

/**
 * @coversDefaultClass \WPCFG\Blacklist\Event
 */
class Event_Test extends \Codeception\Test\Unit
{
    /**
     * @test
     * @covers \WPCFG\Blacklist\Event
     */
    public function it_has_ip_address_getter()
    {
        $event  = new Event('127.0.0.1', 'some note');
        $actual = $event->get_ip_address();
        $this->assertSame('127.0.0.1', $actual);
    }

    /**
     * @test
     * @covers \WPCFG\Blacklist\Event
     */
    public function it_has_note_getter()
    {
        $event  = new Event('127.0.0.1', 'some note');
        $actual = $event->get_note();
        $this->assertSame('some note', $actual);
    }
}
