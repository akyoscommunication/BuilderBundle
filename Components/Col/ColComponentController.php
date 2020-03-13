<?php

namespace Akyos\BuilderBundle\Components\Col;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ColComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName()
    {
        return '@BuilderComponents/Col/col_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}
