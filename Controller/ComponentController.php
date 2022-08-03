<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\BuilderBundle\Form\ComponentType;
use Akyos\BuilderBundle\Repository\ComponentFieldRepository;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentValueRepository;
use Akyos\BuilderBundle\Twig\BuilderExtension;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: 'admin/builder/component', name: 'component_')]
class ComponentController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/new', name: 'component_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $component = new Component();
        $form = $this->createForm(ComponentType::class, $component);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($component);
            $entityManager->flush();

            return $this->redirectToRoute('component_index');
        }
        return $this->render('@AkyosBuilder/component/new.html.twig', ['component' => $component, 'form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param Component $component
     * @param ComponentFieldRepository $componentFieldRepository
     * @param BuilderExtension $builderExtension
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Component $component, ComponentFieldRepository $componentFieldRepository, BuilderExtension $builderExtension, EntityManagerInterface $entityManager): Response
    {
        $type = $request->get('type');
        $typeId = $request->get('typeId');
        $form = $this->createForm(ComponentType::class, $component);
        $slug = $component->getComponentTemplate()->getSlug();
        $groups = $componentFieldRepository->getUniqueFieldsGroups($component->getComponentTemplate()->getId());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $params = [];
            /** @var ComponentValue $value */
            foreach ($component->getComponentValues()->getValues() as $value) {
                $params[$value->getComponentField()->getSlug()] = $value->getValue();
            }

            return new Response($builderExtension->renderComponentBySlug($slug, $params, $component, true, $type, $typeId));
        }
        return $this->render('@AkyosBuilder/component/edit.html.twig', ['groups' => $groups, 'component' => $component, 'form' => $form->createView(),]);
    }

    /**
     * @param Request $request
     * @param ComponentRepository $componentRepository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route(path: '/change-component-position', methods: ['POST'])]
    public function changeComponentPosition(Request $request, ComponentRepository $componentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var Component $component */
        $component = $componentRepository->find($request->get('component'));
        $oldParent = $component->getParentComponent();
        $newParent = $request->get('parent') ? $componentRepository->find($request->get('parent')) : null;
        $newPosition = (int)$request->get('position');
        if ($oldParent && $newParent) {
            // TODO : If new and old parent are components
            if ($newParent->getId() !== $oldParent->getId()) {
                foreach ($componentRepository->find($newParent)->getChildComponents()->getValues() as $item) {
                    // TODO : change all children positions and set new position
                    if ($item->getPosition() >= $newPosition) {
                        $item->setPosition($item->getPosition() + 1);
                    }
                }
                $oldParent->removeChildComponent($component);
                $newParent->addChildComponent($component);
                $component->setParentComponent($newParent);
                $component->setPosition($newPosition);

                $entityManager->flush();

                foreach ($oldParent->getChildComponents() as $position => $item) {
                    $item->setPosition($position);
                }
            } else {
                // TODO : if is same parent
                foreach ($componentRepository->find($newParent)->getChildComponents()->getValues() as $item) {
                    // TODO : change all children positions and set new position
                    if (($component->getPosition() > $newPosition) && ($item->getPosition() >= $newPosition) && ($item->getPosition() < $component->getPosition())) {
                        $item->setPosition($item->getPosition() + 1);
                    } elseif (($component->getPosition() < $newPosition) && ($item->getPosition() <= $newPosition) && ($item->getPosition() > $component->getPosition())) {
                        $item->setPosition($item->getPosition() - 1);
                    }
                }
                $component->setPosition($newPosition);

                $entityManager->flush();
            }
        } elseif ($oldParent && !$newParent) {
            // TODO : If OldParent was component and now he don't has any parent
            foreach ($oldParent->getChildComponents() as $position => $item) {
                $item->setPosition($position);
            }
            foreach ($componentRepository->findBy(['parentComponent' => null, 'type' => $component->getType(), 'typeId' => $component->getTypeId(), 'isTemp' => true], ['position' => 'ASC']) as $item) {
                // TODO : change all children positions and set new position
                if ($item->getPosition() >= $newPosition) {
                    $item->setPosition($item->getPosition() + 1);
                }
            }
            $oldParent->removeChildComponent($component);
            $component->setParentComponent(null);
            $component->setPosition($newPosition);

            $entityManager->flush();
        } elseif (!$oldParent && $newParent) {
            // TODO : If OldParent was 'main' and now component has got parent

            foreach ($componentRepository->find($newParent)->getChildComponents()->getValues() as $item) {
                // TODO : change all children positions and set new position
                if ($item->getPosition() >= $newPosition) {
                    $item->setPosition($item->getPosition() + 1);
                }
            }
            $newParent->addChildComponent($component);
            $component->setParentComponent($newParent);
            $component->setPosition($newPosition);

            $entityManager->flush();

            foreach ($componentRepository->findBy(['parentComponent' => null, 'type' => $component->getType(), 'typeId' => $component->getTypeId(), 'isTemp' => true], ['position' => 'ASC']) as $position => $item) {
                $item->setPosition($position);
            }
        } else {
            // TODO : IF is the same parent "main"
            foreach ($componentRepository->findBy(['parentComponent' => null, 'type' => $component->getType(), 'typeId' => $component->getTypeId(), 'isTemp' => true], ['position' => 'ASC']) as $item) {
                if (($component->getPosition() > $newPosition) && ($item->getPosition() >= $newPosition) && ($item->getPosition() < $component->getPosition())) {
                    // TODO : If new position is smaller than old
                    $item->setPosition($item->getPosition() + 1);
                } elseif (($component->getPosition() < $newPosition) && ($item->getPosition() <= $newPosition) && ($item->getPosition() > $component->getPosition())) {
                    // TODO : If new position is bigger than old
                    $item->setPosition($item->getPosition() - 1);
                }
            }
            $component->setPosition($newPosition);

            $entityManager->flush();
        }
        // TODO : Save modifications
        $entityManager->persist($component);
        $entityManager->flush();
        return new JsonResponse('valid');
    }

    /**
     * @param Request $request
     * @param ComponentValueRepository $componentValueRepository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route(path: '/edit/col', methods: ['POST'], options: ['expose' => true])]
    public function changeComponentCol(Request $request, ComponentValueRepository $componentValueRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $col = $request->get('col');
        $valueToChange = $componentValueRepository->findOneValueCol($request->get('component'));
        if ($valueToChange instanceof ComponentValue) {
            $valueToChange->setValue($col);
            $entityManager->flush();
        }
        return new JsonResponse('valid');
    }

    /**
     * @param Component $component
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Component $component, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($component);
        $entityManager->flush();
        return new JsonResponse('valid');
    }
}
