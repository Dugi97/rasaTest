<?php

namespace App\Repository;

use App\Entity\BannedProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BannedProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method BannedProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method BannedProduct[]    findAll()
 * @method BannedProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannedProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BannedProduct::class);
    }

    // /**
    //  * @return BannedProduct[] Returns an array of BannedProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BannedProduct
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
