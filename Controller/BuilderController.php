<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\BuilderBundle\Form\ChoiceBuilderTemplateType;
use Akyos\BuilderBundle\Form\MakeTemplateType;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder", name="templates_builder_")
 */
class BuilderController extends AbstractController
{
    private $request;

    public function __construct(RequestStack $request)
    {
        $this->request = $request->getCurrentRequest();
    }

    public function getTab()
    {
        $tab = '<li class="nav-item">';
            $tab .= '<a class="nav-link" id="builder-tab" data-toggle="tab" href="#builder" role="tab" aria-controls="builder" aria-selected="false">Builder</a>';
        $tab .= '</li>';
        return new Response($tab);
    }

    public function getTabContent($objectType, $objectId): Response
    {
        $em = $this->getDoctrine()->getManager();
        $instance_components = $em->getRepository(Component::class)->findBy(['type' => $objectType, 'typeId' => $objectId, 'isTemp' => true, 'parentComponent' => null], ['position' => 'ASC']);
        $components = $em->getRepository(ComponentTemplate::class)->findAll();

        $choiceBuilderTemplateForm = $this->createForm(ChoiceBuilderTemplateType::class);
        $choiceBuilderTemplateForm->handleRequest($this->request);
        if ($choiceBuilderTemplateForm->isSubmitted() && $choiceBuilderTemplateForm->isValid()) {
            /** @var BuilderTemplate $template */
            $template = $choiceBuilderTemplateForm->get('choice_template')->getData();
            $this->cloneTemplateBuilder($objectType, $objectId, $template->getId());

            return $this->redirect($this->request->getUri());
        }

        $makeBuilderTemplateForm = $this->createForm(MakeTemplateType::class);
        $makeBuilderTemplateForm->handleRequest($this->request);
        if ($makeBuilderTemplateForm->isSubmitted() && $makeBuilderTemplateForm->isValid()) {
            /** @var BuilderTemplate $template */
            $templateTitle = $makeBuilderTemplateForm->get('title')->getData();
            $this->makeTemplate($objectType, $objectId, $templateTitle);

            return $this->redirect($this->request->getUri());
        }

        return $this->render('@AkyosBuilder/builder/render.html.twig', [
            'makeBuilderTemplateForm' => $makeBuilderTemplateForm->createView(),
            'choiceBuilderTemplateForm' => $choiceBuilderTemplateForm->createView(),
            'instance_components' => $instance_components,
            'type' => $objectType,
            'typeId' => $objectId,
            'components' => $components,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param ComponentTemplate $componentTemplate
     * @return Response
     */
    public function delete(Request $request, ComponentTemplate $componentTemplate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$componentTemplate->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($componentTemplate);
            $em->flush();
        }

        return $this->redirectToRoute('templates_builder_index');
    }

    /**
     * @Route("/save/instance", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     * @param ComponentTemplateRepository $componentTemplateRepository
     * @param ComponentRepository $componentRepository
     *
     * @return JsonResponse
     */
    public function saveInstances(Request $request, ComponentTemplateRepository $componentTemplateRepository, ComponentRepository $componentRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $component = new Component();
        $componentTemplate = $componentTemplateRepository->findOneBy(array('id' => $request->get('componentId')));

        $component->setComponentTemplate($componentTemplate);
        $component->setType($request->get('type'));
        $component->setTypeId(intval($request->get('typeId')));

        if ($request->get('parentComponentId') !== 'main') {
            $parentComponent = $componentRepository->findOneBy(array('id' => $request->get('parentComponentId')));
            $component->setParentComponent($parentComponent);
            $component->setPosition((int)count($componentRepository->findBy(['type' => $request->get('type'), 'typeId' => $request->get('typeId'), 'parentComponent' => $parentComponent->getId(), 'isTemp' => true])));
        } else {
            $component->setPosition((int)count($componentRepository->findBy(['type' => $request->get('type'), 'typeId' => $request->get('typeId'), 'parentComponent' => null, 'isTemp' => true])));
        }

        $component->setVisibilityXS(true);
        $component->setVisibilityS(true);
        $component->setVisibilityM(true);
        $component->setVisibilityL(true);
        $component->setVisibilityXL(true);
        $component->setIsTemp(true);

        foreach ($componentTemplate->getComponentFields() as $componentField) {
            $componentValue = new ComponentValue();
            $componentValue->setComponentField($componentField);
            $componentValue->setComponent($component);
            if (($componentTemplate->getPrototype() === 'col') && ($componentField->getSlug() === 'col')) {
                $componentValue->setValue(12);
            }
            $em->persist($componentValue);
        }

        $em->persist($component);
        $em->flush();

        return new JsonResponse($component->getId());
    }

    public function initCloneComponents($type, $typeId)
    {
        $em = $this->getDoctrine()->getManager();

        $components = $this->getDoctrine()->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'parentComponent' => null));
        $componentsTemp = $this->getDoctrine()->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => true));
        $componentsProd = $this->getDoctrine()->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => false));

        // Check if there are temp components updated
        if ($componentsProd) {
            $dateTimeProd = $componentsProd[0]->getUpdatedAt();
            foreach ($componentsTemp as $temp) {
                if ($temp->getUpdatedAt() > $dateTimeProd) {
                    $this->addFlash("warning", "L'élément n'est pas à jour en production.");
                    break;
                }
            }
        }

        // Check if there are already temp components
        if (!$componentsTemp) {
            foreach ($components as $component) {
                if ($component instanceof Component) {
                    // If he has childs
                    if (!($component->getParentComponent())) {
                        $this->cloneComponent($component);
                    }
                }
            }
            $em->flush();
        }

        return new Response("true");
    }

    public function tempToProd($type, $typeId)
    {
        $em = $this->getDoctrine()->getManager();

        // Get each components of page.
        $newComponents = $this->getDoctrine()->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId));
        $componentsProd = $this->getDoctrine()->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => false));
        $componentsTemp = $this->getDoctrine()->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => true));

        // TODO => If there are no more temp component.
        if ($componentsProd && !$componentsTemp) {
            foreach ($componentsProd as $deleteComponent) {
                $em->remove($deleteComponent);
            }
            $em->flush();
        } else {

            // Toggle isTemp of each component
            foreach ($newComponents as $newComponent) {
                if ($newComponent instanceof Component) {
                    $newComponent->setIsTemp(!$newComponent->getIsTemp());
                }
            }

//            $em->flush();

            // Get each temp component of page.
//            $deleteComponents = $this->getDoctrine()->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true]);

            // Delete each temp component of page
            foreach ($componentsProd as $deleteComponent) {
                if ($deleteComponent instanceof Component) {
                    $em->remove($deleteComponent);
                }
            }

            $em->flush();
        }

        return new Response("valid");
    }

    /**
     * @Route("/reset/temp/component/{type}/{typeId}", name="reset_temp_component")
     * @param $type
     * @param $typeId
     *
     * @return Response
     */
    public function resetTemp($type, $typeId):Response
    {
        $tempComponents = $this->getDoctrine()->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true]);
        $em = $this->getDoctrine()->getManager();

        foreach ($tempComponents as $tempComponent) {
            $em->remove($tempComponent);
        }
        $em->flush();

        return $this->redirectToRoute(strtolower($type).'_edit', ['id' => $typeId]);
    }

    public function cloneComponent(Component $component, $parent = null, $changeType = null, $changeTypeId = null, int $nextPos = null, $makeTemplate = false)
    {
        $em = $this->getDoctrine()->getManager();

        $clone = clone $component;
        // Clone each component of page and set isTemp to true
        if ($makeTemplate) {
            $clone->setIsTemp(false);
        } else {
            $clone->setIsTemp(true);
        }
        if ($changeType && $changeTypeId) {
            $clone->setType($changeType);
            $clone->setTypeId($changeTypeId);
            $clone->setPosition(intval($nextPos));
        }
        if ($parent && $parent instanceof Component) {
            $clone->setParentComponent($parent);
            $parent->addChildComponent($clone);
        }
        $em->persist($clone);
        $em->flush();

        // Clone each componentValue of component clone
        foreach ($component->getComponentValues() as $componentValue) {
            if ($componentValue instanceof ComponentValue) {
                $cloneValue = clone $componentValue;
                $cloneValue->setComponent($clone);

                // EXPLAIN => BUG !! Quand on clone la value, il faut dire au clone de changer de parent et au parent de perdre l'enfant clone
                if (!$makeTemplate) {
                    $componentValue->setComponent($component);
                    $clone->removeComponentValue($componentValue);
                }

                $clone->addComponentValue($cloneValue);
                $em->persist($cloneValue);
            }
        }
        if ($component->getChildComponents()) {
            foreach ($clone->getChildComponents() as $childComponent) {
                if ($childComponent->getIsTemp() == false) {
                    $childComponent->setParentComponent($component);
                    $clone->removeChildComponent($childComponent);
                }
            }
            foreach ($component->getChildComponents() as $childComponent) {
                $this->cloneComponent($childComponent, $clone, $changeType, $changeTypeId, $nextPos, $makeTemplate);
            }
        }
        $em->flush();

        return new Response("valid");
    }

    public function onDeleteEntity($type, $typeId)
    {
        $em = $this->getDoctrine()->getManager();
        $components = $this->getDoctrine()->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId]);
        foreach ($components as $component) {
            $em->remove($component);
        }

        return new Response("valid");
    }

    protected function cloneTemplateBuilder($type, $typeId, $templateId)
    {
        $componentsOfTemplate = $this->getDoctrine()->getRepository(Component::class)->findBy(['type' => 'BuilderTemplate', 'typeId' => $templateId, 'parentComponent' => null, 'isTemp' => false]);

        // Pour mettre à la suite du type;
        $nextPos = count($this->getDoctrine()->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId, 'parentComponent' => null, 'isTemp' => false]));
        foreach ($componentsOfTemplate as $c) {
            /** @var Component $c */
            $this->cloneComponent($c, null, $type, $typeId, $nextPos);
            $nextPos++;
        }
    }

    public function makeTemplate($type, $typeId, $templateTitle)
    {
        $em = $this->getDoctrine()->getManager();
        $builderTemplate = new BuilderTemplate();
        $builderTemplate->setTitle($templateTitle);
        $em->persist($builderTemplate);
        $em->flush();

        $tempComponents = $this->getDoctrine()->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true, 'parentComponent' => null]);

        foreach ($tempComponents as $c) {
            /** @var Component $c */
            $this->cloneComponent($c, null, 'BuilderTemplate', $builderTemplate->getId(), null, true);
        }
    }
}
