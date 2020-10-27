<?php
        
namespace Akyos\BuilderBundle\Components\Title;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TitleComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName()
    {
        return '@BuilderComponents/Title/title_component.html.twig';
    }

    public function getParameters($params = null)
    {
        
        return $params;
    }
}