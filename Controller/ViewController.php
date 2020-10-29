<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Form\ComponentType;
use Akyos\BuilderBundle\Repository\ComponentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     */
    public function view($type, $typeId, ComponentRepository $componentRepository, Request $request): Response
    {
        $type = urldecode($type);
        $em = $this->getDoctrine()->getManager();
        $el = $em->getRepository($type)->find($typeId);
        $type = 'Page';
        $components = $componentRepository->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true, 'parentComponent' => null], ['position' => 'ASC']);
        $componentTemplates = $em->getRepository(ComponentTemplate::class)->findAll();

        return $this->render('@AkyosBuilder/builder/renderView.html.twig', [
            'componentTemplates' => $componentTemplates,
            'components' => $components,
            'page' => $el,
            'type' => $type,
            'typeId' => $typeId,
        ]);
    }
}