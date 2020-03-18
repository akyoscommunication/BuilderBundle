<?php

namespace Akyos\BuilderBundle\Components\Text;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;

class TextComponentController implements ComponentInterface
{
    public function getTemplateName()
    {
        return '@BuilderComponents/Text/text_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}