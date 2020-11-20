<?php
        
namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Akyos\CoreBundle\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class LastNewsComponentController extends AbstractController implements ComponentInterface
{
    private $em;
    private $requestStack;
    private $paginator;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
    }


    public function getTemplateName()
    {
        return '@BuilderComponents/LastNews/lastNews_component.html.twig';
    }

    public function getParameters($params = null)
    {
        if(!isset($params['values']['news'])) {
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

            $params['news'] = $this->paginator->paginate($qb->getQuery(), $this->requestStack->getCurrentRequest()->query->getInt('page',1),3);
        } else {
            $params['news'] = $params['values']['news'];
        }

        return $params;
    }
}