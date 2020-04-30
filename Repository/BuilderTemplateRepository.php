<?php

namespace Akyos\BuilderBundle\Repository;

use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BuilderTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuilderTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuilderTemplate[]    findAll()
 * @method BuilderTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuilderTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BuilderTemplate::class);
    }

    // /**
    //  * @return BuilderTemplate[] Returns an array of BuilderTemplate objects
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
    public function findOneBySomeField($value): ?BuilderTemplate
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
