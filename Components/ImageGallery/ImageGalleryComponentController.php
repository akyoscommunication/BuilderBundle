<?php

namespace Akyos\BuilderBundle\Components\ImageGallery;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImageGalleryComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/ImageGallery/imageGallery_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}