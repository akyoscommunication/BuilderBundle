<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/builder', name: 'templates_builder_')]
class BuilderController extends AbstractController
{
    /**
     * @param Request $request
     * @param ComponentTemplate $componentTemplate
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, ComponentTemplate $componentTemplate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $componentTemplate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($componentTemplate);
            $entityManager->flush();
        }
        return $this->redirectToRoute('templates_builder_index');
    }

    /**
     * @param Request $request
     * @param ComponentTemplateRepository $componentTemplateRepository
     * @param ComponentRepository $componentRepository
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route(path: '/save/instance', methods: ['POST'], options: ['expose' => true])]
    public function saveInstances(Request $request, ComponentTemplateRepository $componentTemplateRepository, ComponentRepository $componentRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $component = new Component();
        /** @var ComponentTemplate $componentTemplate */
        $componentTemplate = $componentTemplateRepository->findOneBy(['id' => $request->get('componentId')]);
        $component->setComponentTemplate($componentTemplate);
        $component->setType($request->get('type'));
        $component->setTypeId((int)$request->get('typeId'));
        if ($request->get('parentComponentId') !== 'main') {
            /** @var Component $parentComponent */
            $parentComponent = $componentRepository->findOneBy(['id' => $request->get('parentComponentId')]);
            $component->setParentComponent($parentComponent);
            $component->setPosition(count($componentRepository->findBy(['type' => $request->get('type'), 'typeId' => $request->get('typeId'), 'parentComponent' => $parentComponent->getId(), 'isTemp' => true])));
        } else {
            $component->setPosition(count($componentRepository->findBy(['type' => $request->get('type'), 'typeId' => $request->get('typeId'), 'parentComponent' => null, 'isTemp' => true])));
        }
        $component->setVisibilityXS(true);
        $component->setVisibilityS(true);
        $component->setVisibilityM(true);
        $component->setVisibilityL(true);
        $component->setVisibilityXL(true);
        $component->setIsTemp(true);
        $component->setTranslatableLocale($request->getLocale());
        foreach ($componentTemplate->getComponentFields() as $componentField) {
            $componentValue = new ComponentValue();
            $componentValue->setComponentField($componentField);
            $componentValue->setComponent($component);
            $componentValue->setTranslatableLocale($request->getLocale());
            if (($componentTemplate->getPrototype() === 'col') && ($componentField->getSlug() === 'col')) {
                $componentValue->setValue(12);
            }
            $entityManager->persist($componentValue);
        }
        $entityManager->persist($component);
        $entityManager->flush();
        return new JsonResponse($component->getId());
    }

    /**
     * @param $type
     * @param $typeId
     * @param $redirect
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/reset/temp/component/{type}/{typeId}/{redirect}', requirements: ['redirect' => '.+'], name: 'reset_temp_component')]
    public function resetTemp($type, $typeId, $redirect, EntityManagerInterface $entityManager): Response
    {
        $tempComponents = $entityManager->getRepository(Component::class)->findBy(['type' => urldecode($type), 'typeId' => $typeId, 'isTemp' => true]);
        foreach ($tempComponents as $tempComponent) {
            $entityManager->remove($tempComponent);
        }
        $entityManager->flush();
        return $this->redirect(urldecode($redirect));
    }
}
