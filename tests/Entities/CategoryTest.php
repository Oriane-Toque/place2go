<?php

namespace App\Tests\Entities;

use App\Entity\Category;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entities\Category
 */
class CategoryTest extends TestCase
{
    public function testIsTrue(): void
    {
        $category = new Category();

        $category 
            ->setName('test')
            ->setPicture('https://test/com')
        ;

        $this->assertTrue($category->getName() === 'test');
        $this->assertTrue($category->getPicture() === 'https://test/com');
    }

    public function testIsFalse(): void
    {
        $category = new Category();

        $category 
            ->setName('test')
            ->setPicture('https://test/com')
        ;

        $this->assertFalse($category->getName() === 'false');
        $this->assertFalse($category->getPicture() === 'false');
    }

    public function testIsEmpty(): void
    {
        $category = new Category();

        $this->assertEmpty($category->getName());
        $this->assertEmpty($category->getPicture());
    }

    public function testIsString()
    {
        $category = new Category();

        $category
            ->setName('test')
            ->setPicture('https://test/com')
        ;

        $this->assertIsString($category->getName());
        $this->assertIsString($category->getPicture());
    }

    public function testIsEquals()
    {
        $category = new Category();

        $category
            ->setCreatedAt(new DateTimeImmutable('10-10-2020'))
            ->setUpdatedAt(new DateTimeImmutable('10-10-2020'))
        ;

        $this->assertEquals(new DateTimeImmutable('10-10-2020'), $category->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('10-10-2020'), $category->getUpdatedAt());
    }
}
