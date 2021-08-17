<?php

namespace App\DataFixtures;

use App\Entity\Attendant;
use App\Entity\User;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\Category;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [];
        // create 10 categories! Bam!
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('category' . $i);
            $category->setPicture('https://picsum.photos/id/100/600/400');

            $categories[] = $category;
            $manager->persist($category);
        }

        $users = [];
        // create 10 users! Bam!
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail("user$i@user.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword('dada');
            $user->setNickname("dada$i");
            $user->setFirstname("user$i");
            $user->setLastname("dodo$i");
            $user->setAvatar('https://picsum.photos/id/100/300/300');
            $user->setCity("city$i");
            $user->setIsActive(true);
            $user->setCreatedAt(new DateTimeImmutable());
            $users[] = $user;
            $manager->persist($user);
            
        }

        $events = [];
        // create 10 events! Bam!
        for ($i = 0; $i < 10; $i++) {
            $event = new Event();
            $event->setTitle("event $i");
            $event->setDescription("ceci est l'event n°$i, alonzy !");
            $event->setEventDate(new DateTime());
            $event->setAddress("12 rue du dada");
            $event->setCity("city$i");
            $event->setLat("4.25641654897456");
            $event->setLon("4.25641654897456");
            $event->setMaxAttendants(7);
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
        for ($i = 0; $i < 5; $i++) {
            $attendant = new Attendant();
            $attendant->setCreatedAt(new DateTimeImmutable());
            $attendant->setUser($users[array_rand($users)]);
            $attendant->setEvent($events[array_rand($events)]);

            $attendants[] = $attendant;
            $manager->persist($attendant);
        }

        $manager->flush();
    }
}
