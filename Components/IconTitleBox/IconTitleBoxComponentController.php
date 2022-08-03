<?php

namespace Akyos\BuilderBundle\Components\IconTitleBox;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IconTitleBoxComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/IconTitleBox/iconTitleBox_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}