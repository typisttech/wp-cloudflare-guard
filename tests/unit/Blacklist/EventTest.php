<?php

declare(strict_types=1);

namespace WPCFG\Blacklist;

/**
 * @coversDefaultClass \WPCFG\Blacklist\Event
 */
class EventTest extends \Codeception\Test\Unit
{
    /**
     * @covers \WPCFG\Blacklist\Event
     */
    public function testHasIpAddressGetter()
    {
        $event = new Event('127.0.0.1', 'some note');
        $actual = $event->getIpAddress();
        $this->assertSame('127.0.0.1', $actual);
    }

    /**
     * @covers \WPCFG\Blacklist\Event
     */
    public function testHasNoteGetter()
    {
        $event = new Event('127.0.0.1', 'some note');
        $actual = $event->getNote();
        $this->assertSame('some note', $actual);
    }
}
