<?php
        
namespace Akyos\BuilderBundle\Components\Slider;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SliderComponentController extends AbstractController implements ComponentInterface
{
    public static function getTemplateName(): string
    {
        return '@BuilderComponents/Slider/slider_component.html.twig';
    }

    public function getParameters($params = null)
    {
        return $params;
    }
}