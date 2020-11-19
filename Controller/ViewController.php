<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Repository\ComponentRepository;
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
     * @return Response
     */
    public function view($type, $typeId, ComponentRepository $componentRepository, Request $request, Filesystem $filesystem, KernelInterface $kernel): Response
    {
        $type = urldecode($type);
        $em = $this->getDoctrine()->getManager();
        $el = $em->getRepository($type)->find($typeId);
        $array = explode('\\', $type);
        $entity = end($array);
        $components = $componentRepository->findBy(['type' => $entity, 'typeId' => $typeId, 'isTemp' => true, 'parentComponent' => null], ['position' => 'ASC']);
        $componentTemplates = $em->getRepository(ComponentTemplate::class)->findAll();

        $view = '';
        if ($entity === "Page") {
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
        ]);
    }
}