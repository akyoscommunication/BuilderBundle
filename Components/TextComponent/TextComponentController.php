<?php

namespace Akyos\BuilderBundle\Components\TextComponent;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TextComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName()
    {
        return 'text_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}
