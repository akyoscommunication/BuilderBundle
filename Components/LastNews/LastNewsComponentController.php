<?php
        
namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Akyos\CoreBundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LastNewsComponentController extends AbstractController implements ComponentInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function getTemplateName()
    {
        return '@BuilderComponents/LastNews/lastNews_component.html.twig';
    }

    public function getParameters($params = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->em->getRepository(Post::class)->createQueryBuilder('p');
        $p = [];

        if (isset($params['values']['cat']) and $params['values']['cat']) {
            $qb
                ->innerJoin('p.postCategories', 'ppc')
                ->andWhere($qb->expr()->eq(':cat', 'ppc'))
            ;
            $p['cat'] = $params['values']['cat'];
        }

        $qb
            ->andWhere($qb->expr()->eq('p.published', true))
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(($params['values']['nb'] ?? 3))
        ;

        if(!empty($p)) {
            $qb->setParameters($p);
        }

        $params['news'] = $qb->getQuery()->getResult();

        return $params;
    }
}