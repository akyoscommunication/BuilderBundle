<?php

namespace Akyos\BuilderBundle\Components\Map;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/Map/map_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}