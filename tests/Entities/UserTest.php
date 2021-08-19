<?php

namespace App\Tests\Entities;

use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Entity\User
 */
class UserTest extends TestCase
{
    public function testIsTrue(): void
    {
        $user = new User();

        $user
            ->setNickname("yoyo")
            ->setFirstname('yoyo')
            ->setLastname('lastname')
            ->setAvatar('test.com')
            ->setCity('nantes')
            ->setPhone("0600000000")
            ->setDescription('description')
            ->setBirthday(new \DateTime('10-10-2020'))
            ->setIsActive(true)
            ->setEmail('yoyo@yoyo.com')
            ->setRoles(["ROLE_USER"])
            ->setPassword('password')
        ;

        $this->assertTrue($user->getNickname() === 'yoyo');
        $this->assertTrue($user->getFirstname() === 'yoyo');
        $this->assertTrue($user->getLastname() === 'lastname');
        $this->assertTrue($user->getAvatar() === 'test.com');
        $this->assertTrue($user->getCity() === 'nantes');
        $this->assertTrue($user->getPhone() === "0600000000");
        $this->assertTrue($user->getDescription() === 'description');
        $this->assertEquals($user->getBirthday(), new DateTime('10-10-2020'));
        $this->assertTrue($user->getIsActive() === true);
        $this->assertTrue($user->getEmail() === 'yoyo@yoyo.com');
        $this->assertTrue($user->getRoles() === ['ROLE_USER']);
        $this->assertTrue($user->getPassword() === "password");
    }

    public function testIsFalse(): void
    {
        $user = new User();

        $user
            ->setNickname("yoyo")
            ->setFirstname('yoyo')
            ->setLastname('lastname')
            ->setAvatar('test.com')
            ->setCity('nantes')
            ->setPhone("0600000000")
            ->setDescription('description')
            ->setBirthday(new \DateTime('now'))
            ->setIsActive(true)
            ->setEmail('yoyo@yoyo.com')
            ->setRoles(["ROLE_USER"])
            ->setPassword('password')
        ;

        $this->assertFalse($user->getNickname() === 'false');
        $this->assertFalse($user->getFirstname() === 'false');
        $this->assertFalse($user->getLastname() === 'false');
        $this->assertFalse($user->getAvatar() === 'false.com');
        $this->assertFalse($user->getCity() === 'false');
        $this->assertFalse($user->getPhone() === "false");
        $this->assertFalse($user->getDescription() === 'false');
        $this->assertFalse($user->getBirthday() === new DateTime('now'));
        $this->assertFalse($user->getIsActive() === false);
        $this->assertFalse($user->getEmail() === 'false@yoyo.com');
        $this->assertFalse($user->getRoles() === ['ROLE_FALSE']);
        $this->assertFalse($user->getPassword() === "false");
    }

    public function testIsEmpty(): void
    {
        $user = new User();
        $user->setPassword('');

        $this->assertEmpty($user->getNickname());
        $this->assertEmpty($user->getFirstname());
        $this->assertEmpty($user->getLastname());
        $this->assertEmpty($user->getAvatar());
        $this->assertEmpty($user->getCity());
        $this->assertEmpty($user->getPhone());
        $this->assertEmpty($user->getDescription());
        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getRoles());
        $this->assertEmpty($user->getPassword());
    }

    public function testIsString()
    {
        $user = new User();

        $user
            ->setNickname("yoyo")
            ->setFirstname('yoyo')
            ->setLastname('lastname')
            ->setAvatar('test.com')
            ->setCity('nantes')
            ->setPhone("0600000000")
            ->setDescription('description')
            ->setEmail('yoyo@yoyo.com')
            ->setPassword('password')
        ;

        $this->assertIsString($user->getNickname());
        $this->assertIsString($user->getFirstname());
        $this->assertIsString($user->getLastname());
        $this->assertIsString($user->getAvatar());
        $this->assertIsString($user->getCity());
        $this->assertIsString($user->getPhone());
        $this->assertIsString($user->getDescription());
        $this->assertIsString($user->getEmail());
        $this->assertIsString($user->getPassword());
    }

    public function testIsBool(): void
    {
        $user = new User();

        $this->assertTrue($user->getIsActive());
        $this->assertTrue($user->isVerified() === false);
    }

    public function testIsArray()
    {
        $user = new User();

        $user
            ->setRoles(["ROLE_USER"])
        ;

        $this->assertIsArray($user->getRoles());
    }

    public function testIsNull()
    {
        $user = new User();

        $this->assertNull($user->getAvatar());
        $this->assertNull($user->getPhone());
        $this->assertNull($user->getDescription());
    }

    public function testIsEquals()
    {
        $user = new User();

        $user
            ->setCreatedAt(new DateTimeImmutable('10-10-2020'))
            ->setUpdatedAt(new DateTimeImmutable('10-10-2020'))
        ;

        $this->assertEquals(new DateTimeImmutable('10-10-2020'), $user->getCreatedAt());
        $this->assertEquals(new DateTimeImmutable('10-10-2020'), $user->getUpdatedAt());
    }
}
