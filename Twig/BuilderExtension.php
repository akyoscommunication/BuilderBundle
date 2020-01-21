<?php

namespace Akyos\BuilderBundle\Twig;

use Akyos\BuilderBundle\Controller\RenderComponentController;
use Akyos\BuilderBundle\Entity\Component;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BuilderExtension extends AbstractExtension
{
    private $renderComponentController;

    public function __construct(RenderComponentController $renderComponentController)
    {
        $this->renderComponentController = $renderComponentController;
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
            new TwigFunction('renderComponent', [$this, 'renderComponent']),
            new TwigFunction('renderComponentBySlug', [$this, 'renderComponentBySlug']),
        ];
    }

    public function renderComponent(Component $component)
    {
        return $this->renderComponentController->renderComponent($component);
    }

    public function renderComponentBySlug($componentSlug, $values)
    {
        return $this->renderComponentController->renderComponentBySlug($componentSlug, $values);
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
