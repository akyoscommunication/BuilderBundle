<?php

namespace Akyos\BuilderBundle\Components\Row;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RowComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/Row/row_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}
