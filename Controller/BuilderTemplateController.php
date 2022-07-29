<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\AkyosBuilderBundle;
use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Akyos\BuilderBundle\Form\BuilderTemplateType;
use Akyos\BuilderBundle\Form\Handler\BuilderHandler;
use Akyos\BuilderBundle\Repository\BuilderTemplateRepository;
use Akyos\CmsBundle\Service\CmsService;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder/templates", name="builder_template_")
 * @IsGranted("modeles-du-builder")
 */
class BuilderTemplateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param BuilderTemplateRepository $builderTemplateRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(BuilderTemplateRepository $builderTemplateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $builderTemplateRepository->createQueryBuilder('a');
        if($request->query->get('search')) {
            $query
                ->andWhere('a.title LIKE :keyword')
                ->setParameter('keyword', '%'.$request->query->get('search').'%')
            ;
        }
        $els = $paginator->paginate($query->getQuery(), $request->query->getInt('page',1),12);

        return $this->render('@AkyosCms/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Templates de page builder',
            'entity' => 'BuilderTemplate',
            'route' => 'builder_template',
            'fields' => [
                'ID' => 'Id',
                'Titre du template' => 'Title',
            ],
        ]);
    }
	
	/**
	 * @Route("/new", name="new", methods={"GET","POST"})
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
    public function new(EntityManagerInterface $entityManager): Response
    {
        $builderTemplate = new BuilderTemplate();
        $builderTemplate->setTitle("Nouveau template");
        $entityManager->persist($builderTemplate);
        $entityManager->flush();

        return $this->redirectToRoute('builder_template_edit', ['id' => $builderTemplate->getId()]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param BuilderTemplate $builderTemplate
     * @param BuilderHandler $builderHandler
     * @param ContainerInterface $container
     * @return Response
     */
    public function edit(Request $request, BuilderTemplate $builderTemplate, BuilderHandler $builderHandler, ContainerInterface $container): Response
    {
        $form = $this->createForm(BuilderTemplateType::class, $builderTemplate);
        if ($builderHandler->edit($form, $request, 'BuilderTemplate', $container)) {
            return $this->redirect($request->getUri());
        }

        return $this->render('@AkyosCms/crud/edit.html.twig', [
            'el' => $builderTemplate,
            'title' => 'BuilderTemplate',
            'entity' => BuilderTemplate::class,
            'route' => 'builder_template',
            'form' => $form->createView(),
        ]);
    }
	
	/**
	 * @Route("/{id}", name="delete", methods={"DELETE"})
	 * @param Request $request
	 * @param BuilderTemplate $builderTemplate
	 * @param CmsService $cmsService
	 * @param ContainerInterface $container
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
    public function delete(Request $request, BuilderTemplate $builderTemplate, CmsService $cmsService, ContainerInterface $container, EntityManagerInterface $entityManager): Response
    {
        $entity = 'BuilderTemplate';
        if ($this->isCsrfTokenValid('delete'.$builderTemplate->getId(), $request->request->get('_token'))) {

            if ($cmsService->checkIfBundleEnable(AkyosBuilderBundle::class, BuilderOptions::class, $entity)) {
                try {
                    $container->get('render.builder')->onDeleteEntity($entity, $builderTemplate->getId());
                } catch (Exception $e) {
                }
            }

            $entityManager->remove($builderTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('builder_template_index');
    }
}
