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
        $categories = [];
        // create 10 categories! Bam!
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('category' . $i);
            $category->setPicture('https://picsum.photos/id/' . mt_rand(100, 500) . '/600/400');
            
            $categories[] = $category;
            $manager->persist($category);
        }

        $users = [];
        // create 10 users! Bam!
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail("user$i@user.com");
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->passwordHasher->hashPassword($user,'dada'));
            $user->setNickname("dada$i");
            $user->setFirstname("user$i");
            $user->setLastname("dodo$i");
            $user->setAvatar('https://picsum.photos/id/' .mt_rand(100, 500) . '/300/300');
            $user->setCity("city$i");
            $user->setIsActive(true);
			$user->setBirthday(new DateTime("1996-03-05"));
			$user->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore fuga nam earum facere deserunt quos, pariatur aliquid quisquam accusantium autem beatae accusamus, ipsum reprehenderit sunt sint aperiam? Repellat, explicabo consequuntur.");

            $users[] = $user;
            $manager->persist($user);
        }

        $events = [];
        // create 10 events! Bam!
        for ($i = 0; $i < 20; $i++) {
            $event = new Event();
            $event->setTitle("event $i");
            $event->setDescription("ceci est l'event n°$i, alonzy !");
            $event->setEventDate(new DateTime());
            $event->setAddress("12 rue du dada");
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
        for ($i = 0; $i < 40; $i++) {
            $attendant = new Attendant();
            $attendant->setUser($users[array_rand($users)]);
            $attendant->setEvent($events[array_rand($events)]);

            $attendants[] = $attendant;
            $manager->persist($attendant);
        }

        $manager->flush();
    }
}
