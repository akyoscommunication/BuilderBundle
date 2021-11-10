<?php

namespace Akyos\BuilderBundle\Components\Button;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ButtonComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName(): string
    {
        return '@BuilderComponents/Button/button_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}