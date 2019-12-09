<?php

namespace Akyos\BuilderBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ExtendSidebar
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    public function getTemplate($route)
    {
        $template = '<li class="'.(strpos($route,"templates_builder") !== false ? "active" : "").'"><a href="'.$this->router->generate('templates_builder_index').'">Builder</a></li>';
        return new Response($template);
    }

    public function getOptionsTemplate($route)
    {
        $template = '<li class="'.(strpos($route,"builder_options") !== false ? "active" : "").'"><a href="'.$this->router->generate('builder_options').'">BuilderBundle</a></li>';
        return new Response($template);
    }
}