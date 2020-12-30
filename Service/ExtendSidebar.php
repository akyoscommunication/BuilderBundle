<?php

namespace Akyos\BuilderBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class ExtendSidebar
{
    private $router;
    private $security;

    public function __construct(UrlGeneratorInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function getTemplate($route)
    {
        $template ='';
        if($this->security->isGranted('builder')){
            $template .= '<li class="'.(strpos($route,"templates_builder") !== false ? "active" : "").'"><a href="'.$this->router->generate('templates_builder_index').'">Builder</a></li>';
        }
        if($this->security->isGranted('modeles-du-builder')){
            $template .= '<li class="'.(strpos($route,"builder_template") !== false ? "active" : "").'"><a href="'.$this->router->generate('builder_template_index').'">Template du builder</a></li>';
        }
        ;
        return new Response($template);
    }

    public function getOptionsTemplate($route)
    {
        $template = '';
        if($this->security->isGranted('options-du-builder')){
            $template = '<li class="'.(strpos($route,"builder_options") !== false ? "active" : "").'"><a href="'.$this->router->generate('builder_options').'">BuilderBundle</a></li>';
        }
        return new Response($template);
    }
}