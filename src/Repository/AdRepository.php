<?php

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * @return
     */
    public function findMoreReservation()
    {
        $entityManager = $this->getEntityManager();

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT count(*),   
            `ad`.`title`,
            `ad`.`slug`,
            `ad`.`price`,
            `ad`.`introduction`,
            `ad`.`content`,
            `ad`.`cover_image`,
            `ad`.`rooms`,
            `booking`.`comment`,
            `comment`.`rating`
        FROM
            `ad`
            INNER JOIN `booking` ON `booking`.`ad_id` = `ad`.`id`
            INNER JOIN `comment` ON `comment`.`ad_id` = `ad`.`id`
        GROUP BY
            `ad`.`title`
        HAVING
            COUNT(*) > 3
        LIMIT 3
        ';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
