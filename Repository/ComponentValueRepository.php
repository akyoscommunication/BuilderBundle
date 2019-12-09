<?php

namespace Akyos\BuilderBundle\Repository;

use Akyos\BuilderBundle\Entity\ComponentValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @method ComponentValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComponentValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComponentValue[]    findAll()
 * @method ComponentValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComponentValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComponentValue::class);
    }

    public function findOneValueCol($component)
    {
        try {
            return $this->createQueryBuilder('cv')
                ->innerJoin('cv.component', 'c')
                ->innerJoin('c.componentTemplate', 'ct')
                ->innerJoin('cv.componentField', 'cf')
                ->andWhere('cv.component = :component')
                ->andWhere('cf.slug = :col')
                ->andWhere('ct.prototype = :col')
                ->setParameter('component', $component)
                ->setParameter('col', 'col')
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return 'Aucun résultat';
        }
    }

    /*
    public function findOneBySomeField($value): ?ComponentValue
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
