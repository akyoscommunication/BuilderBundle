<?php
        
namespace Akyos\BuilderBundle\Components\LastNews;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Akyos\BlogBundle\Entity\Post;
use Akyos\BlogBundle\Repository\PostCategoryRepository;
use Akyos\BlogBundle\Repository\PostTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LastNewsComponentController extends AbstractController implements ComponentInterface
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;
    private PaginatorInterface $paginator;
    private PostCategoryRepository $postCategoryRepository;
    private PostTagRepository $postTagRepository;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack, PaginatorInterface $paginator, PostCategoryRepository $postCategoryRepository, PostTagRepository $postTagRepository)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->postTagRepository = $postTagRepository;
    }

    public function getTemplateName(): string
    {
        return '@BuilderComponents/LastNews/lastNews_component.html.twig';
    }

    public function getParameters($params = null)
    {
        if(!isset($params['values']['news'])) {
            /** @var QueryBuilder $qb */
            $qb = $this->em->getRepository(Post::class)->createQueryBuilder('p');
            /** @var Request $currentRequest */
            $currentRequest = $this->requestStack->getCurrentRequest();
            $p = [];

            if (isset($params['values']['cat']) && $params['values']['cat']) {
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
                if($currentRequest->get('categorie')) {
                    $qb
                        ->innerJoin('p.postCategories', 'pc')
                        ->andWhere($qb->expr()->in('pc.slug', ':catSearch'))
                        ->setParameter('catSearch', $currentRequest->get('categorie'))
                    ;
                }
                if($currentRequest->get('etiquette')) {
                    $qb
                        ->innerJoin('p.postTags', 'pt')
                        ->andWhere($qb->expr()->in('pt.slug', ':tagSearch'))
                        ->setParameter('tagSearch', $currentRequest->get('etiquette'))
                    ;
                }

            } elseif(isset($params['values']['category_filters']) && $params['values']['category_filters']) {
                $params['categories'] = $this->postCategoryRepository->findAll();
                if($currentRequest->get('categorie')) {
                    $qb
                        ->innerJoin('p.postCategories', 'pc')
                        ->andWhere($qb->expr()->in('pc.slug', ':catSearch'))
                        ->setParameter('catSearch', $currentRequest->get('categorie'))
                    ;
                }
            } elseif(isset($params['values']['tag_filters']) && $params['values']['tag_filters']) {
                $params['tags'] = $this->postTagRepository->findAll();
                if($currentRequest->get('etiquette')) {
                    $qb
                        ->innerJoin('p.postTags', 'pt')
                        ->andWhere($qb->expr()->in('pt.slug', ':tagSearch'))
                        ->setParameter('tagSearch', $currentRequest->get('etiquette'))
                    ;
                }
            }

            if(!empty($p)) {
                $qb->setParameters($p);
            }

            if(isset($params['values']['paginator']) && $params['values']['paginator']) {
                $params['news'] = $this->paginator->paginate($qb->getQuery(), $currentRequest->query->getInt('page',1), isset($params['values']['posts_per_page']) && $params['values']['posts_per_page'] ? $params['values']['posts_per_page'] : 9);
            } else {
                $params['news'] = $qb->getQuery()->getResult();
            }
        } else {
            $params['news'] = $params['values']['news'];
        }

        return $params;
    }
}