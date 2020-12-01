<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\AkyosBuilderBundle;
use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Form\SubmitBuilderType;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Akyos\CoreBundle\Entity\Page;
use Akyos\CoreBundle\Services\CoreService;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder", name="back_builder_", methods={"GET","POST"})
 * Class ViewController
 * @package Akyos\BuilderBundle\Controller
 */
class ViewController extends AbstractController
{
    /**
     * @Route("/view_edit/{type}/{typeId}", name="view_edit", methods={"GET","POST"})
     * @param $type
     * @param $typeId
     * @param ComponentRepository $componentRepository
     * @param Request $request
     *
     * @param Filesystem $filesystem
     * @param KernelInterface $kernel
     * @param CoreService $coreService
     * @param ContainerInterface $container
     * @return Response
     */
    public function view($type, $typeId, ComponentRepository $componentRepository, Request $request, Filesystem $filesystem, KernelInterface $kernel, CoreService $coreService, ContainerInterface $container): Response
    {
        $type = urldecode($type);
        $em = $this->getDoctrine()->getManager();
        $el = $em->getRepository($type)->find($typeId);
        $array = explode('\\', $type);
        $entity = end($array);
        $checkBundle = $coreService->checkIfBundleEnable(AkyosBuilderBundle::class, BuilderOptions::class, $type);

        $array = [
            'andWhere' => [
                'published' => true,
                'props' => 'value'
            ],
            'orWhere' => [
                'published' => true,
                'props' => 'value'
            ],
        ];

        $form = $this->createForm(SubmitBuilderType::class);
        $form->handleRequest($request);
        if ($checkBundle) {
            if (!$form->isSubmitted()) {
                $container->get('render.builder')->initCloneComponents($type, $el->getId());
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if ($checkBundle) {
                $container->get('render.builder')->tempToProd($type, $el->getId());
            }
            $em->flush();

            return $this->redirect($request->getUri());
        } elseif ($form->isSubmitted() && !($form->isValid())) {
            throw $this->createNotFoundException("Formulaire invalide.");
        }

        $components = $componentRepository->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true, 'parentComponent' => null], ['position' => 'ASC']);
        $componentTemplates = $em->getRepository(ComponentTemplate::class)->findAll();

        if ($type === Page::class) {
            $view = $el->getTemplate() ? '/page/'.$el->getTemplate().'.html.twig' : '@AkyosCore/front/content.html.twig';
        } else {
            $view = $filesystem->exists($kernel->getProjectDir()."/templates/${entity}/single.html.twig")
                ? "/${entity}/single.html.twig"
                : '@AkyosCore/front/single.html.twig';
        }

        return $this->render($view, [
            'componentTemplates' => $componentTemplates,
            'components' => $components,
            'page' => $el,
            'type' => $type,
            'typeId' => $typeId,
            'edit' => true,
            'first' => true,
            'form' => $form->createView(),
        ]);
    }
}