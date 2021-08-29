<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase
{
    private const UNIQUE_EMAIL_VALID_MESSAGE = "Un autre utilisateur s'est déja inscrit avec cette adresse email, merci de la modifier";
    private const EMAIL_CONSTRAINT_VALID_MESSAGE = "Veuillez renseigner un email valide !";
    private const EMAIL_NOT_BLANK_CONSTRAINT_MESSAGE = "Vous devez renseigner votre Email !";
    private const LAST_NAME_NOT_BLANK_CONSTRAINT_MESSAGE = "Vous devez renseigner votre nom de famille";
    private const FIRST_NAME_NOT_BLANK_CONSTRAINT_MESSAGE = "Vous devez renseigner votre prénom";
    private const DESCRIPTION_LENGTH = "Votre description doit faire au moins 100 caractères";
    private const INTRODUCTION_LENGTH = "Votre introduction doit faire au moins 10 caractères";
    private const INVALID_AVATAR_MESSAGE = "Veuillez donner une URL valide pour votre avatar !";

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');

    }

    public function testUserEntityIsValid(): void
    {
        $user = new User();

        $user
            ->setEmail("florianbracq42@mail.com")
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setIntroduction('Je suis un passionné de la loire et de ma région saumuroise.')
            ->setDescription('Bonjour, passionné de la loire et de ma région saumuroise. Je me ferai un plaisir de vous raconter la faune, la flore et la batellerie traditionnelle de Loire. Caviste de métier, capitaine à mes heures perdues et ébéniste de formation, je vous montrerai mes bateaux que j\'ai construit. Au plaisir de vous accueillir, et surtout de vous rencontrer… Amicalement, Vincent, le Capitaine :)')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $this->getValidationErrors($user, 0);
    }

    public function testUserEntityIsNotValid(): void
    {
        $user = new User();

        $user
            ->setEmail(12)
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $this->getValidationErrors($user, 1);
    }

    public function testUserEntityIsInvalidBecauseEmailBadFormat(): void
    {
        $user = new User();

        $user
            ->setEmail('florian@f')
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::EMAIL_CONSTRAINT_VALID_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseLastNameIsEmpty(): void
    {
        $user = new User();

        $user
            ->setEmail('florian@gmail.com')
            ->setFirstName('florian')
            ->setLastName('')
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::LAST_NAME_NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseFirstNameIsEmpty(): void
    {
        $user = new User();

        $user
            ->setEmail('florian@gmail.com')
            ->setFirstName('')
            ->setLastName('bracq')
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::FIRST_NAME_NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseEmailNotEntered(): void
    {
        $user = new User();

        $user
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::EMAIL_NOT_BLANK_CONSTRAINT_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidUrlAvatar(): void
    {
        $user = new User();

        $user
            ->setFirstName('florian')
            ->setLastName('bracq')
            ->setEmail('florianbracq42g@gmail.com')
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description long Je suis une description long Je suis une description long Je suis une description long Je suis une description lon Je suis une description lon')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('avatar')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::INVALID_AVATAR_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityLengthDescription(): void
    {
        $user = new User();

        $user
            ->setEmail("florianbracq42@mail.com")
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Je suis une description courte')
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::DESCRIPTION_LENGTH, $errors[0]->getMessage());
    }

    public function testUserEntityLengthIntroduction(): void
    {
        $user = new User();

        $user
            ->setEmail("florianbracq42@mail.com")
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Décrire une personne c\'est parler de son physique et de son caractère. Parler du physique d\'une personne c\'est indiquer ses traits physiques. Parler du caractère d\'une personne c\'est indiquer sa manière d\'être. c\'est parler de son physique et de son caractère. Parler du physique d\'une personne c\'est indiquer ses traits physiques. Parler du caractère d\'une personne c\'est indiquer sa manière d\'être.')
            ->setIntroduction('Intro')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::INTRODUCTION_LENGTH, $errors[0]->getMessage());
    }

    public function testUniqueUserInstance(): void
    {
        $user = new User();

        $user
            ->setEmail("florianbracq42@gmail.com")
            ->setFirstName('florian')
            ->setLastName("bracq")
            ->setHash('passwordTest123')
            ->setDescription('Décrire une personne c\'est parler de son physique et de son caractère. Parler du physique d\'une personne c\'est indiquer ses traits physiques. Parler du caractère d\'une personne c\'est indiquer sa manière d\'être. c\'est parler de son physique et de son caractère. Parler du physique d\'une personne c\'est indiquer ses traits physiques. Parler du caractère d\'une personne c\'est indiquer sa manière d\'être.')
            ->setIntroduction('Une introduction pour me présenter brievement')
            ->setPicture('http://avatar.com')
            ->setSlug('florian-bracq');

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::UNIQUE_EMAIL_VALID_MESSAGE, $errors[0]->getMessage());
    }

    public function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }

}
