<?php

namespace Akyos\BuilderBundle\Repository;

use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ComponentTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComponentTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComponentTemplate[]    findAll()
 * @method ComponentTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComponentTemplate::class);
    }

    // /**
    //  * @return ComponentTemplate[] Returns an array of ComponentTemplate objects
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
    public function findOneBySomeField($value): ?ComponentTemplate
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
