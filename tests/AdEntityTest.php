<?php

namespace App\Tests\Entity;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdEntityTest extends KernelTestCase
{

    private const INVALID_DATE = "Le montant ne peut pas être inférieur à zéro !";
    private const INVALID_MIN_LENGTH = "Le titre doit faire plus de 10 caractères !";
    private const INVALID_MAX_LENGTH = "Le titre ne peut pas faire plus de 255 caractères !";

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');

    }

    public function testAdEntityIsValid(): void
    {
        $ad = new Ad();

        $user = new User();

        $book = new Booking();

        $comment = new Comment();

        $ad
            ->setSlug('annonce-n-1')
            ->setTitle('Annonce numéro 1')
            ->setRooms(3)
            ->setPrice(12.25)
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setContent('<p>Dolor ut dolorem non tenetur quo eum fuga. Fugiat reprehenderit atque quas. Omnis provident est fugiat officia est quae pariatur. Vero esse eveniet et.</p><p>Eligendi ratione iure rem temporibus maxime. Quis iure et maxime fuga quia.</p><p>Nesciunt sapiente saepe nisi perferendis cum. Enim assumenda neque iusto asperiores repudiandae. Vel voluptatem sit nam atque ratione tenetur.</p><p>Quibusdam soluta corporis temporibus repellat ut eveniet eligendi. Iusto et quae cum blanditiis nihil dolorem. Minus sed et molestiae quasi. In assumenda officia nihil rerum quisquam.</p><p>Est non et a velit aut. Cumque voluptas deleniti laboriosam nisi voluptatem sint. Sed nisi ad tenetur.</p>')
            ->setcoverImage('http://coverImage.com')
            ->addBooking($book)
            ->addComment($comment)
            ->setAuthor($user);

        $this->getValidationErrors($ad, 0);
    }


    public function testAmountNotValid(): void
    {
        $ad = new Ad();

        $user = new User();

        $book = new Booking();

        $comment = new Comment();

        $ad
            ->setSlug('annonce-n-1')
            ->setTitle('Annonce numéro 1')
            ->setRooms(3)
            ->setPrice(-12.25)
            ->setIntroduction('Je suis une introduction')
            ->setContent('<p>Dolor ut dolorem non tenetur quo eum fuga. Fugiat reprehenderit atque quas. Omnis provident est fugiat officia est quae pariatur. Vero esse eveniet et.</p><p>Eligendi ratione iure rem temporibus maxime. Quis iure et maxime fuga quia.</p><p>Nesciunt sapiente saepe nisi perferendis cum. Enim assumenda neque iusto asperiores repudiandae. Vel voluptatem sit nam atque ratione tenetur.</p><p>Quibusdam soluta corporis temporibus repellat ut eveniet eligendi. Iusto et quae cum blanditiis nihil dolorem. Minus sed et molestiae quasi. In assumenda officia nihil rerum quisquam.</p><p>Est non et a velit aut. Cumque voluptas deleniti laboriosam nisi voluptatem sint. Sed nisi ad tenetur.</p>')
            ->setcoverImage('http://coverImage.com')
            ->addBooking($book)
            ->addComment($comment)
            ->setAuthor($user);

        $errors = $this->getValidationErrors($ad, 1);

        $this->assertEquals(self::INVALID_DATE, $errors[0]->getMessage());

    }

    public function testMinLengthTitle(): void
    {
        $ad = new Ad();

        $user = new User();

        $book = new Booking();

        $comment = new Comment();

        $ad
            ->setSlug('annonce-n-1')
            ->setTitle('An')
            ->setRooms(3)
            ->setPrice(12.25)
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setContent('<p>Dolor ut dolorem non tenetur quo eum fuga. Fugiat reprehenderit atque quas. Omnis provident est fugiat officia est quae pariatur. Vero esse eveniet et.</p><p>Eligendi ratione iure rem temporibus maxime. Quis iure et maxime fuga quia.</p><p>Nesciunt sapiente saepe nisi perferendis cum. Enim assumenda neque iusto asperiores repudiandae. Vel voluptatem sit nam atque ratione tenetur.</p><p>Quibusdam soluta corporis temporibus repellat ut eveniet eligendi. Iusto et quae cum blanditiis nihil dolorem. Minus sed et molestiae quasi. In assumenda officia nihil rerum quisquam.</p><p>Est non et a velit aut. Cumque voluptas deleniti laboriosam nisi voluptatem sint. Sed nisi ad tenetur.</p>')
            ->setcoverImage('http://coverImage.com')
            ->addBooking($book)
            ->addComment($comment)
            ->setAuthor($user);

        $errors = $this->getValidationErrors($ad, 1);

        $this->assertEquals(self::INVALID_MIN_LENGTH, $errors[0]->getMessage());

    }

    public function testMaxLengthTitle(): void
    {
        $ad = new Ad();

        $user = new User();

        $book = new Booking();

        $comment = new Comment();

        $ad
            ->setSlug('annonce-n-1')
            ->setTitle('AnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerjAnfezgerherheherhjetjejjejejteejejerj')
            ->setRooms(3)
            ->setPrice(12.25)
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setContent('<p>Dolor ut dolorem non tenetur quo eum fuga. Fugiat reprehenderit atque quas. Omnis provident est fugiat officia est quae pariatur. Vero esse eveniet et.</p><p>Eligendi ratione iure rem temporibus maxime. Quis iure et maxime fuga quia.</p><p>Nesciunt sapiente saepe nisi perferendis cum. Enim assumenda neque iusto asperiores repudiandae. Vel voluptatem sit nam atque ratione tenetur.</p><p>Quibusdam soluta corporis temporibus repellat ut eveniet eligendi. Iusto et quae cum blanditiis nihil dolorem. Minus sed et molestiae quasi. In assumenda officia nihil rerum quisquam.</p><p>Est non et a velit aut. Cumque voluptas deleniti laboriosam nisi voluptatem sint. Sed nisi ad tenetur.</p>')
            ->setcoverImage('http://coverImage.com')
            ->addBooking($book)
            ->addComment($comment)
            ->setAuthor($user);

        $errors = $this->getValidationErrors($ad, 1);

        $this->assertEquals(self::INVALID_MAX_LENGTH, $errors[0]->getMessage());

    }

    public function testNotStringTitle(): void
    {
        $ad = new Ad();

        $user = new User();

        $book = new Booking();

        $comment = new Comment();

        $ad
            ->setSlug('annonce-n-1')
            ->setTitle(12)
            ->setRooms(3)
            ->setPrice(12.25)
            ->setIntroduction('Je suis une introduction courte Je suis une introduction courte Je suis une introduction courte')
            ->setContent('Dolor ut dolorem non tenetur quo eum fuga. Fugiat reprehenderit atque quas. Omnis provident est fugiat officia est quae pariatur. Vero esse eveniet et.</p><p>Eligendi ratione iure rem temporibus maxime. Quis iure et maxime fuga quia.</p><p>Nesciunt sapiente saepe nisi perferendis cum. Enim assumenda neque iusto asperiores repudiandae. Vel voluptatem sit nam atque ratione tenetur.</p><p>Quibusdam soluta corporis temporibus repellat ut eveniet eligendi. Iusto et quae cum blanditiis nihil dolorem. Minus sed et molestiae quasi. In assumenda officia nihil rerum quisquam.</p><p>Est non et a velit aut. Cumque voluptas deleniti laboriosam nisi voluptatem sint. Sed nisi ad tenetur.')
            ->setcoverImage('http://coverImage.com')
            ->addBooking($book)
            ->addComment($comment)
            ->setAuthor($user);

        $errors = $this->getValidationErrors($ad, 1);

    }

    


    public function getValidationErrors(Ad $ad, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($ad);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }

}
