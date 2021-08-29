<?php

namespace App\Tests\Entity;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingEntityTest extends KernelTestCase
{
    private const EMAIL_CONSTRAINT_VALID_MESSAGE = "Veuillez renseigner un email valide !";
    private const DATE_START_CONSTRAINT_MESSAGE = "La date d'arrivée doit être supérieur à la date d'aujourd'hui !";
    private const DATE_END_CONSTRAINT_MESSAGE = "La date de départ doit être plus éloignée que la date d'arrivée !";
    private const NOT_BLANK_CONSTRAINT_MESSAGE = "Vous devez renseigner votre nom de famille";
    private const DESCRIPTION_LENGTH = "Votre description doit faire au moins 100 caractères";
    private const INTRODUCTION_LENGTH = "Votre introduction doit faire au moins 10 caractères";

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');

    }

    public function getUserEntity(){
        $user = new User();

        $user
            ->setEmail("florianbracq42@mail.com")
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

            return $user;
    }

    public function getAdEntity(){
        $ad = new Ad();

        $ad->setTitle('tototototototot')
                ->setCoverImage('http://flo-bracq.com')
                ->setIntroduction('introtrotritortortortortortortorotrottotr')
                ->setContent('introtrotritortortortortortortorotrottotrintrotrotritortortortortortortorotrottotrintrotrotritortortortortortortorotrottotrintrotrotritortortortortortortorotrottotrintrotrotritortortortortortortorotrottotrintrotrotritortortortortortortorotrottotrintrotrotritortortortortortortorotrottotr')
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($this->getUserEntity());
            ;
        
        return $ad;
    }

    public function testUserEntityIsValid(): void
    {

        $booking = new Booking();

        $booking->setBooker($this->getUserEntity())
                        ->setAd($this->getAdEntity())
                        ->setStartDate(new \DateTime('2021-07-02'))
                        ->setEndDate(new \DateTime('2021-08-30'))
                        ->setCreatedAt(new \DateTime())
                        ->setAmount(200)
                        ->setComment('vsgzgzrgzgzegzgzge');
        
        $this->getValidationErrors($booking, 0);
    }

    public function testBookingEntityIsInvalidDateEnd(): void
    {
        $booking = new Booking();

        $booking->setBooker($this->getUserEntity())
                        ->setAd($this->getAdEntity())
                        ->setStartDate(new \DateTime('2021-07-02'))
                        ->setEndDate(new \DateTime('2021-06-30'))
                        ->setCreatedAt(new \DateTime())
                        ->setAmount(200)
                        ->setComment('vsgzgzrgzgzegzgzge');

        $errors = $this->getValidationErrors($booking, 1);

        $this->assertEquals(self::DATE_END_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    

    // public function testUserEntityIsInvalidBecauseNotEntered(): void
    // {
    //     $user = new User();

    //     $user
    //         ->setFirstName('florian')
    //         ->setLastName("bracq")
    //         ->setHash('passwordTest123')
    //         ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
    //         ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
    //         ->setPicture('http://avatar.com')
    //         ->setSlug('florian-bracq');

    //     $errors = $this->getValidationErrors($user, 1);

    //     $this->assertEquals(self::EMAIL_NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    // }

    // public function testUserEntityLengthDescription(): void
    // {
    //     $user = new User();

    //     $user
    //         ->setEmail("florianbracq42@mail.com")
    //         ->setFirstName('florian')
    //         ->setLastName("bracq")
    //         ->setHash('passwordTest123')
    //         ->setDescription('Je suis une description courte')
    //         ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
    //         ->setPicture('http://avatar.com')
    //         ->setSlug('florian-bracq');

    //     $errors = $this->getValidationErrors($user, 1);

    //     $this->assertEquals(self::DESCRIPTION_LENGTH, $errors[0]->getMessage());
    // }

    // public function testUserEntityLengthIntroduction(): void
    // {
    //     $user = new User();

    //     $user
    //         ->setEmail("florianbracq42@mail.com")
    //         ->setFirstName('florian')
    //         ->setLastName("bracq")
    //         ->setHash('passwordTest123')
    //         ->setDescription('Décrire une personne c\'est parler de son physique et de son caractère. Parler du physique d\'une personne c\'est indiquer ses traits physiques. Parler du caractère d\'une personne c\'est indiquer sa manière d\'être. c\'est parler de son physique et de son caractère. Parler du physique d\'une personne c\'est indiquer ses traits physiques. Parler du caractère d\'une personne c\'est indiquer sa manière d\'être.')
    //         ->setIntroduction('Intro')
    //         ->setPicture('http://avatar.com')
    //         ->setSlug('florian-bracq');

    //     $errors = $this->getValidationErrors($user, 1);

    //     $this->assertEquals(self::INTRODUCTION_LENGTH, $errors[0]->getMessage());
    // }

    public function getValidationErrors(Booking $booking, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($booking);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }

}
