<?php

namespace App\Tests\Entities;

use App\Entity\Event;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entity\Event
 */
class EventTest extends TestCase
{
    public function testIsTrue(): void
    {
        $event = new Event();

        $event
            ->setTitle('title')
            ->setDescription('description')
            ->setEventDate(new DateTime('10-10-2020'))
            ->setAddress('address')
            ->setLat('1.0')
            ->setLon('2.0')
            ->setMaxAttendants(10)
            ->setIsActive(true)
        ;

        $this->assertTrue($event->getTitle() === 'title');
        $this->assertTrue($event->getDescription() === 'description');
        $this->assertEquals($event->getEventDate(), new DateTime('10-10-2020'));
        $this->assertTrue($event->getAddress() === 'address');
        $this->assertTrue($event->getLat() === '1.0');
        $this->assertTrue($event->getLon() === '2.0');
        $this->assertTrue($event->getMaxAttendants() === 10);
        $this->assertTrue($event->getIsActive() === true);
    }

    public function testIsFalse(): void
    {
        $event = new Event();

        $event
            ->setTitle('title')
            ->setDescription('description')
            ->setEventDate(new DateTime('now'))
            ->setAddress('address')
            ->setLat('1.0')
            ->setLon('2.0')
            ->setMaxAttendants(10)
            ->setIsActive(true)
        ;

        $this->assertFalse($event->getTitle() === 'false');
        $this->assertFalse($event->getDescription() === 'false');
        $this->assertFalse($event->getEventDate() === new DateTime('tomorrow'));
        $this->assertFalse($event->getAddress() === 'false');
        $this->assertFalse($event->getLat() === 'false');
        $this->assertFalse($event->getLon() === 'false');
        $this->assertFalse($event->getMaxAttendants() === 5);
        $this->assertFalse($event->getIsActive() === false);
    }

    public function testIsEmpty(): void
    {
        $event = new Event();

        $this->assertEmpty($event->getTitle());
        $this->assertEmpty($event->getDescription());
        $this->assertEmpty($event->getEventDate());
        $this->assertEmpty($event->getAddress());
        $this->assertEmpty($event->getLat());
        $this->assertEmpty($event->getLon());
        $this->assertEmpty($event->getMaxAttendants());
    }

    public function testIsString()
    {
        $event = new Event();

        $event
            ->setTitle('title')
            ->setDescription('description')
            ->setAddress('address')
            ->setLat('1.0')
            ->setLon('2.0')
        ;

        $this->assertIsString($event->getTitle());
        $this->assertIsString($event->getDescription());
        $this->assertIsString($event->getAddress());
        $this->assertIsString($event->getLat());
        $this->assertIsString($event->getLon());
    }

    public function testIsBool(): void
    {
        $event = new Event();

        $this->assertTrue($event->getIsActive());
    }

    public function testIsNull()
    {
        $event = new Event();

        $this->assertNull($event->getAddress());
        $this->assertNull($event->getLat());
        $this->assertNull($event->getLon());
    }

    public function testIsEquals()
    {
        $event = new Event();

        $event
            ->setCreatedAt(new DateTimeImmutable('10-10-2020'))
            ->setUpdatedAt(new DateTimeImmutable('10-10-2020'))
        ;

        $this->assertEquals(new DateTimeImmutable('10-10-2020'), $event->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('10-10-2020'), $event->getUpdatedAt());
    }
}
