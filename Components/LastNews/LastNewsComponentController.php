<?php
        
namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Akyos\CoreBundle\Entity\Post;
use Akyos\CoreBundle\Repository\PostCategoryRepository;
use Akyos\CoreBundle\Repository\PostTagRepository;
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
    private $postCategoryRepository;
    private $postTagRepository;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, PaginatorInterface $paginator, PostCategoryRepository $postCategoryRepository, PostTagRepository $postTagRepository)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->postTagRepository = $postTagRepository;
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

            if (isset($params['values']['category_filters'], $params['values']['tag_filters']) && $params['values']['category_filters'] && $params['values']['tag_filters']) {
                $params['categories'] = $this->postCategoryRepository->findAll();
                $params['tags'] = $this->postTagRepository->findAll();
                if($this->requestStack->getCurrentRequest()->get('categorie')) {
                    $qb
                        ->innerJoin('p.postCategories', 'pc')
                        ->andWhere($qb->expr()->in('pc.slug', ':catSearch'))
                        ->setParameter('catSearch', $this->requestStack->getCurrentRequest()->get('categorie'))
                    ;
                }
                if($this->requestStack->getCurrentRequest()->get('etiquette')) {
                    $qb
                        ->innerJoin('p.postTags', 'pt')
                        ->andWhere($qb->expr()->in('pt.slug', ':tagSearch'))
                        ->setParameter('tagSearch', $this->requestStack->getCurrentRequest()->get('etiquette'))
                    ;
                }

            } elseif(isset($params['values']['category_filters']) && $params['values']['category_filters']) {
                $params['categories'] = $this->postCategoryRepository->findAll();
                if($this->requestStack->getCurrentRequest()->get('categorie')) {
                    $qb
                        ->innerJoin('p.postCategories', 'pc')
                        ->andWhere($qb->expr()->in('pc.slug', ':catSearch'))
                        ->setParameter('catSearch', $this->requestStack->getCurrentRequest()->get('categorie'))
                    ;
                }
            } elseif(isset($params['values']['tag_filters']) && $params['values']['tag_filters']) {
                $params['tags'] = $this->postTagRepository->findAll();
                if($this->requestStack->getCurrentRequest()->get('etiquette')) {
                    $qb
                        ->innerJoin('p.postTags', 'pt')
                        ->andWhere($qb->expr()->in('pt.slug', ':tagSearch'))
                        ->setParameter('tagSearch', $this->requestStack->getCurrentRequest()->get('etiquette'))
                    ;
                }
            }

            if(!empty($p)) {
                $qb->setParameters($p);
            }

            if(isset($params['values']['paginator']) && $params['values']['paginator']) {
                $params['news'] = $this->paginator->paginate($qb->getQuery(), $this->requestStack->getCurrentRequest()->query->getInt('page',1), isset($params['values']['posts_per_page']) && $params['values']['posts_per_page'] ? $params['values']['posts_per_page'] : 9);
            } else {
                $params['news'] = $qb->getQuery()->getResult();
            }
        } else {
            $params['news'] = $params['values']['news'];
        }

        return $params;
    }
}