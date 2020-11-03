<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\AkyosBuilderBundle;
use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Form\BuilderTemplateType;
use Akyos\BuilderBundle\Form\Handler\BuilderHandler;
use Akyos\BuilderBundle\Repository\BuilderTemplateRepository;
use Akyos\CoreBundle\Services\CoreService;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder/templates", name="builder_template_")
 */
class BuilderTemplateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param BuilderTemplateRepository $builderTemplateRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     *
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

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Templates de page builder',
            'entity' => 'BuilderTemplate',
            'route' => 'builder_template',
            'fields' => array(
                'ID' => 'Id',
                'Titre du template' => 'Title',
            ),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param KernelInterface $kernel
     *
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, KernelInterface $kernel): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
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
     * @param BuilderController $builderController
     * @return Response
     */
    public function edit(Request $request, BuilderTemplate $builderTemplate, BuilderHandler $builderHandler, ContainerInterface $container): Response
    {
        $form = $this->createForm(BuilderTemplateType::class, $builderTemplate);
        if ($builderHandler->edit($form, $request, 'BuilderTemplate', $container)) {
            return $this->redirect($request->getUri());
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $builderTemplate,
            'title' => 'BuilderTemplate',
            'entity' => 'BuilderTemplate',
            'route' => 'builder_template',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param BuilderTemplate $builderTemplate
     * @param CoreService $coreService
     * @param ContainerInterface $container
     *
     * @return Response
     */
    public function delete(Request $request, BuilderTemplate $builderTemplate, CoreService $coreService, ContainerInterface $container): Response
    {
        $entity = 'BuilderTemplate';
        if ($this->isCsrfTokenValid('delete'.$builderTemplate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($coreService->checkIfBundleEnable(AkyosBuilderBundle::class, BuilderOptions::class, $entity)) {
                $container->get('render.builder')->onDeleteEntity($entity, $builderTemplate->getId());
            }

            $entityManager->remove($builderTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('builder_template_index');
    }
}
