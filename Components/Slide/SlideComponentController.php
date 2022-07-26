<?php

namespace Akyos\BuilderBundle\Components\Slide;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SlideComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/Slide/slide_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}