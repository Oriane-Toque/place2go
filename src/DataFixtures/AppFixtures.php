<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\Attendant;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
   
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $categories = [];
        // create 10 categories! Bam!
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->word());
            $category->setPicture('https://picsum.photos/id/' . mt_rand(100, 500) . '/600/400');

            $categories[] = $category;
            $manager->persist($category);
        }

        $users = [];
        // create 10 users! Bam!
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->passwordHasher->hashPassword($user,'dada'));
            $user->setNickname($faker->userName());
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setAvatar('https://api.multiavatar.com/' . mt_rand(1,500) . '.png');
            $user->setCity($faker->city());
            $user->setIsActive(true);
			$user->setBirthday(new DateTimeImmutable('now -' . mt_rand(20,60) . 'years'));
			$user->setDescription($faker->text(mt_rand(100,500)));

            $users[] = $user;
            $manager->persist($user);
        }

        $events = [];
        // create 10 events! Bam!
        for ($i = 0; $i < 30; $i++) {
            $event = new Event();
            $event->setTitle($faker->text(mt_rand(15,50)));
            $event->setDescription($faker->text(mt_rand(100,250)));
            $event->setEventDate(new DateTimeImmutable('now +' . mt_rand(9,12) . 'days'));
            $event->setAddress($faker->address());
            $event->setLat($faker->latitude(0, 5));
            $event->setLon($faker->longitude(44, 49));
            $event->setMaxAttendants(mt_rand(3,20));
            $event->setIsActive(true);
            $event->setAuthor($users[array_rand($users)]);

            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $event->addCategory($categories[array_rand($categories)]);
            }

            $events[] = $event;
            $manager->persist($event);
        }

        $attendants = [];
        // create 5 events! Bam!
        for ($i = 0; $i < 50; $i++) {
            $attendant = new Attendant();
            $attendant->setUser($users[array_rand($users)]);
            $attendant->setEvent($events[array_rand($events)]);

            $attendants[] = $attendant;
            $manager->persist($attendant);
        }

        $manager->flush();
    }
}