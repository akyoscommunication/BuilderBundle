<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\AkyosBuilderBundle;
use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Form\SubmitBuilderType;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\CmsBundle\Entity\Page;
use Akyos\CmsBundle\Service\CmsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package Akyos\BuilderBundle\Controller
 */
#[Route(path: '/admin/builder', name: 'back_builder_', methods: ['GET', 'POST'])]
class ViewController extends AbstractController
{
    /**
     * @param $type
     * @param $typeId
     * @param $redirect
     * @param ComponentRepository $componentRepository
     * @param Request $request
     * @param Filesystem $filesystem
     * @param KernelInterface $kernel
     * @param CmsService $cmsService
     * @param ContainerInterface $container
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route(path: '/view_edit/{type}/{typeId}/{redirect}', name: 'view_edit', methods: ['GET', 'POST'])]
    public function view($type, $typeId, $redirect, ComponentRepository $componentRepository, Request $request, Filesystem $filesystem, KernelInterface $kernel, CmsService $cmsService, ContainerInterface $container, EntityManagerInterface $entityManager): Response
    {
        $type = urldecode($type);
        $el = $entityManager->getRepository($type)->find($typeId);
        $array = explode('\\', $type);
        $entity = end($array);
        $checkBundle = $cmsService->checkIfBundleEnable(AkyosBuilderBundle::class, BuilderOptions::class, $type);
        $form = $this->createForm(SubmitBuilderType::class);
        $form->handleRequest($request);
        if ($checkBundle && !$form->isSubmitted()) {
            $container->get('render.builder')->initCloneComponents($type, $el->getId());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($checkBundle) {
                $container->get('render.builder')->tempToProd($type, $el->getId());
            }
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }
        if ($form->isSubmitted() && !($form->isValid())) {
            throw $this->createNotFoundException("Formulaire invalide.");
        }
        $components = $componentRepository->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true, 'parentComponent' => null], ['position' => 'ASC']);
        $componentTemplates = $entityManager->getRepository(ComponentTemplate::class)->findAll();
        if ($type === Page::class) {
            $view = $el->getTemplate() ? '/page/' . $el->getTemplate() . '.html.twig' : '@AkyosCms/front/content.html.twig';
        } else {
            $view = $filesystem->exists($kernel->getProjectDir() . "/templates/${entity}/single.html.twig") ? "/${entity}/single.html.twig" : '@AkyosCms/front/single.html.twig';
        }
        return $this->render($view, ['componentTemplates' => $componentTemplates, 'components' => $components, 'page' => $el, 'element' => $el, 'type' => $type, 'typeId' => $typeId, 'back_url' => urldecode($redirect), 'edit' => true, 'first' => true, 'form' => $form->createView(),]);
    }
}