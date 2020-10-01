<?php


namespace Akyos\BuilderBundle\Form\Handler;


use Akyos\BuilderBundle\AkyosBuilderBundle;
use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\CoreBundle\Services\CoreService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class BuilderHandler extends AbstractController
{
    private $em;
    private $coreService;

    public function __construct(EntityManagerInterface $em, CoreService $coreService)
    {
        $this->em = $em;
        $this->coreService = $coreService;
    }

    public function edit(FormInterface $form, Request $request, string $entityName, ContainerInterface $container): bool
    {
        $element = $form->getData();
        $check = $this->coreService->checkIfBundleEnable(AkyosBuilderBundle::class, BuilderOptions::class, $entityName);

        $form->handleRequest($request);

        if ($check) {
            if (!$form->isSubmitted()) {
                $container->get('render.builder')->initCloneComponents($entityName, $element->getId());
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            if ($check) {
                $container->get('render.builder')->tempToProd($entityName, $element->getId());
            }

            $this->em->flush();

            $this->addFlash('success', "L'élément à bien été modifié.");
            return true;
        }
        return false;
    }

    public function delete($entity, string $entityName, ContainerInterface $container): void
    {
        if ($this->coreService->checkIfBundleEnable(AkyosBuilderBundle::class, BuilderOptions::class, $entityName)) {
            $container->get('render.builder')->onDeleteEntity($entityName, $entity->getId());
        }
    }
}