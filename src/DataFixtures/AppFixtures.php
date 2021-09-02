<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\EventProvider;
use DateTime;
use App\Entity\User;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\Attendant;
use App\Entity\Comment;
use App\Entity\Friendship;
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

        $faker->addProvider(new EventProvider());

        $categories = [];
        // create 10 categories! Bam!
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->eventCategory());
            $category->setPicture('https://picsum.photos/id/' . mt_rand(100, 500) . '/600/400');

            $categories[] = $category;
            $manager->persist($category);
        }

        $users = [];

        // Create 20 users! Bam!
        for ($i = 0; $i < 20; $i++) {
            $user = new User();

            $firstName = $faker->firstName();
            $lastName  = $faker->lastName();
            $nickName = $firstName.substr($lastName, 0, 1);

            if ($i == 0) {
                $user->setRoles(["ROLE_ADMIN"]);
                $user->setEmail('admin@admin.com');
                $user->setNickname('admin');
            } else {
                $user->setRoles(["ROLE_USER"]);
                $user->setEmail($faker->email());
                $user->setNickname($nickName);
            }

            $user->setPassword($this->passwordHasher->hashPassword($user, 'dada'));
            $user->setFirstname($firstName);
            $user->setLastname($lastName);
            $user->setAvatar('https://api.multiavatar.com/' . mt_rand(1, 500) . '.png');
            $user->setCity($faker->city());
            $user->setIsActive(true);
            $user->setIsVerified(true);
            $user->setBirthday(new DateTimeImmutable('now -' . mt_rand(20, 60) . 'years'));
            $user->setDescription($faker->text(mt_rand(100, 500)));

            $users[] = $user;
            $manager->persist($user);
        }

        $events = [];
        // create 10 events! Bam!
        for ($i = 0; $i < 30; $i++) {
            $event = new Event();
            $event->setTitle($faker->text(mt_rand(15, 50)));
            $event->setDescription($faker->text(mt_rand(100, 250)));
            $event->setEventDate(new DateTimeImmutable('now +' . mt_rand(9, 12) . 'days'));
            $event->setAddress($faker->address());
            $event->setLat($faker->latitude(0, 5));
            $event->setLon($faker->longitude(44, 49));
            $event->setMaxAttendants(mt_rand(3, 20));
            $event->setIsActive(true);
            $event->setAuthor($users[array_rand($users)]);

            for ($j = 0; $j < mt_rand(1, 2); $j++) {
                $event->addCategory($categories[array_rand($categories)]);
            }

            $events[] = $event;
            $manager->persist($event);
        }

        $attendants = [];
        // Create 50 attendants! Bam!
        for ($i = 0; $i < 50; $i++) {
            $attendant = new Attendant();
            $attendant->setUser($users[array_rand($users)]);
            $attendant->setEvent($events[array_rand($events)]);

            $attendants[] = $attendant;
            $manager->persist($attendant);
        }

        $comments = [];
        // Create 30 comments! Bam!
        for ($i = 0; $i < 30; $i++) {
            $comment = new Comment();
            $comment->setAuthor($users[array_rand($users)]);
            $comment->setEvent($events[array_rand($events)]);
            $comment->setContent($faker->text(mt_rand(100, 250)));
            $comment->setCreatedAt(new \DateTimeImmutable());

            $comments[] = $comment;
            $manager->persist($comment);
        }

        // $frienships = [];
        // // Create 30 friendships! Bam!
        // for ($i = 0; $i < 30; $i++) {
        //     $frienship = new Friendship();

        //     $sender = $users[array_rand($users)];
        //     $receiver = $users[array_rand($users)];
        //     $frienship->setSender($sender);
            
        //     while($receiver == $sender)
        //     {
        //         $receiver = $users[array_rand($users)];
        //     }
        //     $frienship->setReceiver($receiver);
        //     $frienship->setStatus(1);

        //     $frienships[] = $frienship;
        //     $manager->persist($frienship);
        // }

        $manager->flush();
    }
}
