<?php

namespace Akyos\BuilderBundle\Repository;

use Akyos\BuilderBundle\Entity\BuilderOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BuilderOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuilderOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuilderOptions[]    findAll()
 * @method BuilderOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuilderOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuilderOptions::class);
    }

    // /**
    //  * @return BuilderOptions[] Returns an array of BuilderOptions objects
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
    public function findOneBySomeField($value): ?BuilderOptions
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
