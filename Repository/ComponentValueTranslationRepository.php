<?php

namespace Akyos\BuilderBundle\Repository;

use Akyos\BuilderBundle\Entity\ComponentValueTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComponentValueTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComponentValueTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComponentValueTranslation[]    findAll()
 * @method ComponentValueTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentValueTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComponentValueTranslation::class);
    }

    // /**
    //  * @return ComponentValueTranslation[] Returns an array of ComponentValueTranslation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ComponentValueTranslation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
