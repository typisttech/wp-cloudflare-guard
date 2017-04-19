<?php

declare(strict_types=1);

namespace TypistTech\WPCFG\Blacklist;

use Codeception\Test\Unit;

/**
 * @coversDefaultClass TypistTech\WPCFG\Blacklist\Event
 */
class EventTest extends Unit
{
    /**
     * @covers ::getIpAddress
     */
    public function testHasIpAddressGetter()
    {
        $event = new Event('127.0.0.1', 'some note');
        $actual = $event->getIpAddress();
        $this->assertSame('127.0.0.1', $actual);
    }

    /**
     * @covers ::getNote
     */
    public function testHasNoteGetter()
    {
        $event = new Event('127.0.0.1', 'some note');
        $actual = $event->getNote();
        $this->assertSame('some note', $actual);
    }
}
