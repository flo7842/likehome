<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create('fr-FR');

        // We manage the admin user
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
       // dd($this->encoder->encodePassword($adminUser, 'password'));
        $adminUser->setFirstName('Florian')
                  ->setLastName('Bracq')
                  ->setEmail('florianbracq42@gmail.com')
                  ->setHash($this->passwordHasher->hashPassword($adminUser, 'password'))
                  ->setPicture('https://pbs.twimg.com/profile_images/1102967485974937600/LgSE31RT_400x400.png')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' .join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // We manage the users
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 4; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            //$hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' .join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($this->passwordHasher->hashPassword($user, 'password'))
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // We manage the ads
        for($i = 1; $i <= 10; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)). '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);
            ;

            for($j = 1; $j <= mt_rand(0, 2); $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // We manage the reservations
            for($j = 1; $j <= mt_rand(0, 6); $j++){
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                // End date management
                $duration  = mt_rand(3, 10);
                $endDate   = (clone $startDate)->modify("+$duration days");

                $amount    = $ad->getPrice() * $duration;
                $booker    = $users[mt_rand(0, count($users) -1)];
                $comment   = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);

                $manager->persist($booking);

                // We manage the comments
                if(mt_rand(0, 1)){
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1, 5))
                            ->setAuthor($booker)
                            ->setAd($ad);

                    $manager->persist($comment);
                }
            }

            $manager->persist($ad);

        }

        $manager->flush();
    }
}
