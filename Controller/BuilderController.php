<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\BuilderBundle\Repository\ComponentTemplateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder", name="templates_builder_")
 */
class BuilderController extends AbstractController
{
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
        /** @var ComponentTemplate $componentTemplate */
        $componentTemplate = $componentTemplateRepository->findOneBy(['id' => $request->get('componentId')]);
        $component->setComponentTemplate($componentTemplate);
        $component->setType($request->get('type'));
        $component->setTypeId((int)$request->get('typeId'));

        if ($request->get('parentComponentId') !== 'main') {
            /** @var Component $parentComponent */
            $parentComponent = $componentRepository->findOneBy(['id' => $request->get('parentComponentId')]);
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
        $component->setTranslatableLocale($request->getLocale());

        foreach ($componentTemplate->getComponentFields() as $componentField) {
            $componentValue = new ComponentValue();
            $componentValue->setComponentField($componentField);
            $componentValue->setComponent($component);
            $componentValue->setTranslatableLocale($request->getLocale());
            if (($componentTemplate->getPrototype() === 'col') && ($componentField->getSlug() === 'col')) {
                $componentValue->setValue(12);
            }
            $em->persist($componentValue);
        }

        $em->persist($component);
        $em->flush();

        return new JsonResponse($component->getId());
    }

    /**
     * @Route("/reset/temp/component/{type}/{typeId}/{redirect}", name="reset_temp_component")
     * @param $type
     * @param $typeId
     *
     * @param $redirect
     * @return Response
     */
    public function resetTemp($type, $typeId, $redirect):Response
    {
        $tempComponents = $this->getDoctrine()->getRepository(Component::class)->findBy(['type' => urldecode($type), 'typeId' => $typeId, 'isTemp' => true]);
        $em = $this->getDoctrine()->getManager();

        foreach ($tempComponents as $tempComponent) {
            $em->remove($tempComponent);
        }
        $em->flush();

        return $this->redirect(urldecode($redirect));
    }
}
