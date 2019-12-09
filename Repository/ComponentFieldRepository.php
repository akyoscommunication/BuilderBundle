<?php

namespace Akyos\BuilderBundle\Repository;

use Akyos\BuilderBundle\Entity\ComponentField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComponentField|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComponentField|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComponentField[]    findAll()
 * @method ComponentField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComponentField::class);
    }

    // /**
    //  * @return ComponentField[] Returns an array of ComponentField objects
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
    public function findOneBySomeField($value): ?ComponentField
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
