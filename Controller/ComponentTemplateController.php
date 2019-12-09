<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Form\ComponentTemplateType;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder", name="templates_builder_")
 */
class ComponentTemplateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ComponentTemplateRepository $componentTemplateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $componentTemplateRepository->findAll(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Templates de composants',
            'entity' => 'ComponentTemplate',
            'route' => 'templates_builder',
            'fields' => array(
                'ID' => 'Id',
                'Nom' => 'Name',
                'Slug' => 'Slug',
                'Petite description' => 'ShortDescription',
            ),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $componentTemplate = new ComponentTemplate();
        $field = new ComponentField();
        $componentTemplate->addComponentField($field);
        $form = $this->createForm(ComponentTemplateType::class, $componentTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($componentTemplate);
            $em->flush();

            return $this->redirectToRoute('templates_builder_index');
        }

        return $this->render('@AkyosBuilder/templates_builder/new.html.twig', [
            'component_template' => $componentTemplate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ComponentTemplate $componentTemplate): Response
    {
        $form = $this->createForm(ComponentTemplateType::class, $componentTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('templates_builder_index');
        }

        return $this->render('@AkyosBuilder/templates_builder/edit.html.twig', [
            'component_template' => $componentTemplate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, ComponentTemplate $componentTemplate, ComponentRepository $componentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$componentTemplate->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $instanceComponents = $componentRepository->findBy(array('componentTemplate' => $componentTemplate->getId()));

            foreach ($instanceComponents as $instanceComponent) {
                foreach ($instanceComponent->getChildComponents() as $childComponent){
                    $instanceComponent->removeChildComponent($childComponent);
                    $em->remove($childComponent);
                }
                $em->remove($instanceComponent);
            }

            $em->remove($componentTemplate);
            $em->flush();
        }

        return $this->redirectToRoute('templates_builder_index');
    }
}
