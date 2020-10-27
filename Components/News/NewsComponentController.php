<?php
        
namespace Akyos\BuilderBundle\Components\News;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName()
    {
        return '@BuilderComponents/News/news_component.html.twig';
    }

    public function getParameters($params = null)
    {
        
        return $params;
    }
}