<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\BuilderBundle\Form\ComponentType;
use Akyos\BuilderBundle\Repository\ComponentFieldRepository;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentValueRepository;
use Akyos\BuilderBundle\Twig\BuilderExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/builder/component", name="component_")
 */
class ComponentController extends AbstractController
{
    /**
     * @Route("/new", name="component_new", methods={"GET","POST"})
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $component = new Component();
        $form = $this->createForm(ComponentType::class, $component);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($component);
            $entityManager->flush();

            return $this->redirectToRoute('component_index');
        }

        return $this->render('@AkyosBuilder/component/new.html.twig', [
            'component' => $component,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param Component $component
     * @param ComponentFieldRepository $componentFieldRepository
     *
     * @return Response
     */
    public function edit(Request $request, Component $component, ComponentFieldRepository $componentFieldRepository, BuilderExtension $builderExtension): Response
    {
        $type = $request->get('type');
        $typeId = $request->get('typeId');
        $form = $this->createForm(ComponentType::class, $component);
        $slug = $component->getComponentTemplate()->getSlug();
        $groups = $componentFieldRepository->getUniqueFieldsGroups($component->getComponentTemplate()->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $params = [];
            /** @var ComponentValue $value */
            foreach ($component->getComponentValues()->getValues() as $value) {
                $params[$value->getComponentField()->getSlug()] = $value->getValue();
            }

            return new Response($builderExtension->renderComponentBySlug($slug, $params, $component, true, $type, $typeId));
        }

        return $this->render('@AkyosBuilder/component/edit.html.twig', [
            'groups' => $groups,
            'component' => $component,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Component $component, ComponentRepository $componentRepository): Response
    {
//        if ($this->isCsrfTokenValid('delete'.$component->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($component);
            $entityManager->flush();
//        }

//        foreach ( $componentRepository->findAll(array(), array('position' => 'ASC')) as $key => $resetComponent ) {
//            if ($resetComponent->getParentComponent() === null) {
//                $resetComponent->setPosition($key);
//            }
//        }

        return new JsonResponse('valid');
    }

    /**
     * @Route("/change-component-position", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     *
     * @param ComponentRepository $componentRepository
     *
     * @return JsonResponse
     */
    public function changeComponentPosition(Request $request, ComponentRepository $componentRepository): JsonResponse
    {
        $component = $componentRepository->find($request->get('component'));
        $oldParent = $component->getParentComponent();

        $newParent = $request->get('parent') ? $componentRepository->find($request->get('parent')) : null;
        $newPosition = (int)$request->get('position');

        $em = $this->getDoctrine()->getManager();

        if ( $oldParent && $newParent ) {
            // TODO : If new and old parent are components
            if ($newParent->getId() != $oldParent->getId()) {
                foreach ($componentRepository->find($newParent)->getChildComponents()->getValues() as $item) {
                    // TODO : change all children positions and set new position
                    if ($item->getPosition() >= $newPosition) {
                        $item->setPosition($item->getPosition()+1);
                    }
                }
                $oldParent->removeChildComponent($component);
                $newParent->addChildComponent($component);
                $component->setParentComponent($newParent);
                $component->setPosition($newPosition);

                $em->flush();

                foreach ($oldParent->getChildComponents() as $position => $item) {
                    $item->setPosition($position);
                }
            } else {
                // TODO : if is same parent
                foreach ($componentRepository->find($newParent)->getChildComponents()->getValues() as $item) {
                    // TODO : change all children positions and set new position
                    if (($component->getPosition() > $newPosition) && ($item->getPosition() >= $newPosition) && ($item->getPosition() < $component->getPosition())) {
                        $item->setPosition($item->getPosition()+1);
                    } elseif (($component->getPosition() < $newPosition) && ($item->getPosition() <= $newPosition) && ($item->getPosition() > $component->getPosition())) {
                        $item->setPosition($item->getPosition()-1);
                    }
                }
                $component->setPosition($newPosition);

                $em->flush();
            }
        } elseif ( $oldParent && $newParent == null ) {
            // TODO : If OldParent was component and now he don't has any parent
            foreach ($oldParent->getChildComponents() as $position => $item) {
                $item->setPosition($position);
            }
            foreach ($componentRepository->findBy(array('parentComponent' => null, 'type' => $component->getType(), 'typeId' => $component->getTypeId(), 'isTemp' => true), array('position' => 'ASC')) as $item) {
                // TODO : change all children positions and set new position
                if ($item->getPosition() >= $newPosition) {
                    $item->setPosition($item->getPosition()+1);
                }
            }
            $oldParent->removeChildComponent($component);
            $component->setParentComponent(NULL);
            $component->setPosition($newPosition);

            $em->flush();

        } elseif ( $oldParent == null && $newParent ) {
            // TODO : If OldParent was 'main' and now component has got parent

            foreach ($componentRepository->find($newParent)->getChildComponents()->getValues() as $item) {
                // TODO : change all children positions and set new position
                if ($item->getPosition() >= $newPosition) {
                    $item->setPosition($item->getPosition()+1);
                }
            }
            $newParent->addChildComponent($component);
            $component->setParentComponent($newParent);
            $component->setPosition($newPosition);

            $em->flush();

            foreach ($componentRepository->findBy(array('parentComponent' => null, 'type' => $component->getType(), 'typeId' => $component->getTypeId(), 'isTemp' => true), array('position' => 'ASC')) as $position => $item ) {
                $item->setPosition($position);
            }
        } else {
            // TODO : IF is the same parent "main"
            foreach ($componentRepository->findBy(array('parentComponent' => null, 'type' => $component->getType(), 'typeId' => $component->getTypeId(), 'isTemp' => true), array('position' => 'ASC')) as $item) {
                if (($component->getPosition() > $newPosition) && ($item->getPosition() >= $newPosition) && ($item->getPosition() < $component->getPosition())) {
                    // TODO : If new position is smaller than old
                    $item->setPosition($item->getPosition()+1);
                } elseif (($component->getPosition() < $newPosition) && ($item->getPosition() <= $newPosition) && ($item->getPosition() > $component->getPosition())) {
                    // TODO : If new position is bigger than old
                    $item->setPosition($item->getPosition()-1);
                }
            }
            $component->setPosition($newPosition);

            $em->flush();
        }

        // TODO : Save modifications
        $em->persist($component);
        $em->flush();

        return new JsonResponse('valid');
    }

    /**
     * @Route("/edit/col", methods={"POST"}, options={"expose"=true})
     * @param Request $request
     *
     * @param ComponentRepository $componentRepository
     * @param ComponentValueRepository $componentValueRepository
     *
     * @return JsonResponse
     */
    public function changeComponentCol(Request $request, ComponentRepository $componentRepository, ComponentValueRepository $componentValueRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $col = $request->get('col');
//        $component = $componentRepository->find($request->get('component'));

        $valueToChange = $componentValueRepository->findOneValueCol($request->get('component'));
        if ($valueToChange instanceof ComponentValue) {
            $valueToChange->setValue($col);
            $em->flush();
        }

        return new JsonResponse('valid');
    }
}
