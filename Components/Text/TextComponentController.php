<?php

namespace Akyos\BuilderBundle\Components\Text;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TextComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/Text/text_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}