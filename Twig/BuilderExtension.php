<?php

namespace Akyos\BuilderBundle\Twig;

use Akyos\BuilderBundle\Controller\RenderComponentController;
use Akyos\BuilderBundle\Entity\Component;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BuilderExtension extends AbstractExtension
{
    private $renderComponentController;
    /** @var Environment */
    private $environment;

    public function __construct(RenderComponentController $renderComponentController, Environment $environment)
    {
        $this->renderComponentController = $renderComponentController;
        $this->environment = $environment;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('slugify', [$this, 'slugify']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('setGlobals', [$this, 'setGlobals']),
            new TwigFunction('renderComponent', [$this, 'renderComponent']),
            new TwigFunction('renderComponentBySlug', [$this, 'renderComponentBySlug']),
        ];
    }

    public function setGlobals(array $array)
    {
        foreach ($array as $k => $g) {
            $this->environment->addGlobal($k, $g);
        }
        return true;
    }

    public function renderComponent(Component $component, $edit = false, $type = null, $typeId = null)
    {
        return $this->renderComponentController->renderComponent($component, $edit, $type, $typeId);
    }

    public function renderComponentBySlug($componentSlug, $values, Component $component = null, $edit = false, $type = null, $typeId = null)
    {
        return $this->renderComponentController->renderComponentBySlug($componentSlug, $values, $component, $edit, $type, $typeId);
    }

    public function slugify($slug)
    {

        // replace non letter or digits by -
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);

        // transliterate
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

        // remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // trim
        $slug = trim($slug, '-');

        // remove duplicate -
        $slug = preg_replace('~-+~', '-', $slug);

        // lowercase
        $slug = strtolower($slug);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }
}
