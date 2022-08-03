<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Form\ComponentTemplateType;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/builder', name: 'templates_builder_')]
#[IsGranted('builder')]
class ComponentTemplateController extends AbstractController
{
    /**
     * @param ComponentTemplateRepository $componentTemplateRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(ComponentTemplateRepository $componentTemplateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $componentTemplateRepository->createQueryBuilder('a');
        if ($request->query->get('search')) {
            $query->andWhere('a.name LIKE :keyword OR a.slug LIKE :keyword OR a.shortDescription LIKE :keyword')->setParameter('keyword', '%' . $request->query->get('search') . '%');
        }
        $els = $paginator->paginate($query->getQuery(), $request->query->getInt('page', 1), 12);
        return $this->render('@AkyosCms/crud/index.html.twig', ['els' => $els, 'title' => 'Templates de composants', 'entity' => 'ComponentTemplate', 'route' => 'templates_builder', 'fields' => ['ID' => 'Id', 'Nom' => 'Name', 'Slug' => 'Slug', 'Petite description' => 'ShortDescription',],]);
    }

    /**
     * @param Request $request
     * @param KernelInterface $kernel
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, KernelInterface $kernel, EntityManagerInterface $entityManager): Response
    {
        $componentTemplate = new ComponentTemplate();
        $field = new ComponentField();
        $componentTemplate->addComponentField($field);
        $form = $this->createForm(ComponentTemplateType::class, $componentTemplate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($componentTemplate);
            $entityManager->flush();

            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(['command' => 'app:make:component', // (optional) define the value of command arguments
                'name' => $componentTemplate->getSlug(),]);

            $output = new NullOutput();
            $application->run($input, $output);

            return $this->redirectToRoute('templates_builder_index');
        }
        return $this->render('@AkyosBuilder/templates_builder/new.html.twig', ['component_template' => $componentTemplate, 'form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param ComponentTemplate $componentTemplate
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ComponentTemplate $componentTemplate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ComponentTemplateType::class, $componentTemplate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('templates_builder_index');
        }
        return $this->render('@AkyosBuilder/templates_builder/edit.html.twig', ['component_template' => $componentTemplate, 'form' => $form->createView(),]);
    }

    /**
     * @param ComponentTemplate $componentTemplate
     * @param KernelInterface $kernel
     * @return Response
     * @throws Exception
     */
    #[Route(path: '/{id}/generate-fixture', name: 'generate_fixture', methods: ['GET'])]
    public function generateFixture(ComponentTemplate $componentTemplate, KernelInterface $kernel): Response
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput(['command' => 'app:make:componentFixture', // (optional) define the value of command arguments
            'id' => $componentTemplate->getId(),]);
        $output = new NullOutput();
        $application->run($input, $output);
        return $this->redirectToRoute('templates_builder_edit', ['id' => $componentTemplate->getId()]);
    }

    /**
     * @param Request $request
     * @param ComponentTemplate $componentTemplate
     * @param ComponentRepository $componentRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, ComponentTemplate $componentTemplate, ComponentRepository $componentRepository, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $componentTemplate->getId(), $request->request->get('_token'))) {
            $instanceComponents = $componentRepository->findBy(['componentTemplate' => $componentTemplate->getId()]);

            foreach ($instanceComponents as $instanceComponent) {
                foreach ($instanceComponent->getChildComponents() as $childComponent) {
                    $instanceComponent->removeChildComponent($childComponent);
                    $entityManager->remove($childComponent);
                }
                $entityManager->remove($instanceComponent);
            }

            $entityManager->remove($componentTemplate);
            $entityManager->flush();
        }
        return $this->redirectToRoute('templates_builder_index');
    }
}
