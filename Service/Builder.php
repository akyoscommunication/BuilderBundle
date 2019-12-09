<?php

namespace Akyos\BuilderBundle\Service;

use Akyos\BuilderBundle\Form\ComponentFieldRenderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Builder extends AbstractController
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getTab()
    {
        $tab = '<li class="nav-item">';
            $tab .= '<a class="nav-link" id="builder-tab" data-toggle="tab" href="#builder" role="tab" aria-controls="builder" aria-selected="false">Builder</a>';
        $tab .= '</li>';
        return $tab;
    }

    public function getTabContent($objectType, $objectId)
    {
        $em = $this->getDoctrine()->getManager();
        $instance_components = $em->getRepository("Akyos\\BuilderBundle\\Entity\\Component")->findBy(["type" => $objectType, "typeId" => $objectId], []);
        $components = $em->getRepository("Akyos\\BuilderBundle\\Entity\\ComponentTemplate")->findAll();

//        $components_forms = array();
//        foreach ($components->getComponentFields() as $component) {
//            $component_forms = $this->createForm( ComponentFieldRenderType::class, $component, ['component' => $component->getId()]);
////            if ($request->getMethod('POST')) {
////                $component_forms->handleRequest($request);
////                if ($component_forms->isSubmitted() && $component_forms->isValid()) {
////                    try {
////                        $em->persist($component);
////                        $em->flush();
////                        $this->get('session')->getFlashBag()->add('success',"Modification du réglage effectuée avec succès !");
////                    } catch (Exception $e) {
////                        $this->get('session')->getFlashBag()->add('danger',"Une erreur s'est produite lors de la modification du réglage, merci de réssayer.");
////                    }
////                }
////            }
//            $components_forms[$component->getTitle()] = $component_forms->createView();
//        }

        return $this->render('@AkyosBuilder/builder/render.html.twig', [
            'type' => $objectType,
            'typeId' => $objectId,
            'instance_components' => $instance_components,
//            'components_forms' => $components_forms,
            'components' => $components,
        ]);
    }
}