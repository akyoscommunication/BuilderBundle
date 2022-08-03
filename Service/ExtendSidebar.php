<?php

namespace Akyos\BuilderBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class ExtendSidebar
{
    private UrlGeneratorInterface $router;

    private Security $security;

    public function __construct(UrlGeneratorInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * @param $route
     * @return Response
     */
    public function getTemplate($route): Response
    {
        $template = '';
        if ($this->security->isGranted('builder')) {
            $template .= '<li class="' . (strpos($route, "templates_builder") !== false ? "active" : "") . '"><a href="' . $this->router->generate('templates_builder_index') . '">Builder</a></li>';
        }
        if ($this->security->isGranted('modeles-du-builder')) {
            $template .= '<li class="' . (strpos($route, "builder_template") !== false ? "active" : "") . '"><a href="' . $this->router->generate('builder_template_index') . '">Template du builder</a></li>';
        }
        return new Response($template);
    }

    /**
     * @param $route
     * @return Response
     */
    public function getOptionsTemplate($route): Response
    {
        $template = '';
        if ($this->security->isGranted('options-du-builder')) {
            $template = '<li class="' . (strpos($route, "builder_options") !== false ? "active" : "") . '"><a href="' . $this->router->generate('builder_options') . '">BuilderBundle</a></li>';
        }
        return new Response($template);
    }
}