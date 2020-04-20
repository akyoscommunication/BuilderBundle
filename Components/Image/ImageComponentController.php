<?php

namespace Akyos\BuilderBundle\Components\Image;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName()
    {
        return '@BuilderComponents/Image/image_component.html.twig';
    }

    public function getParameters($params = null)
    {

        return $params;
    }
}