<?php

namespace Akyos\BuilderBundle\Twig;

use Akyos\BuilderBundle\Controller\RenderComponentController;
use Akyos\BuilderBundle\Entity\Component;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BuilderExtension extends AbstractExtension
{
    private RenderComponentController $renderComponentController;

    private Environment $environment;

    public function __construct(RenderComponentController $renderComponentController, Environment $environment)
    {
        $this->renderComponentController = $renderComponentController;
        $this->environment = $environment;
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [// If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('slugify', [$this, 'slugify']),];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [new TwigFunction('setGlobals', [$this, 'setGlobals']), new TwigFunction('renderComponent', [$this, 'renderComponent']), new TwigFunction('renderComponentBySlug', [$this, 'renderComponentBySlug']),];
    }

    /**
     * @param array $array
     * @return bool
     */
    public function setGlobals(array $array): bool
    {
        foreach ($array as $k => $g) {
            $this->environment->addGlobal($k, $g);
        }
        return true;
    }

    /**
     * @param Component $component
     * @param false $edit
     * @param null $type
     * @param null $typeId
     * @return string|Response
     * @throws Exception
     */
    public function renderComponent(Component $component, ?bool $edit = false, $type = null, $typeId = null)
    {
        return $this->renderComponentController->renderComponent($component, $edit, $type, $typeId);
    }

    /**
     * @param $componentSlug
     * @param $values
     * @param Component|null $component
     * @param false $edit
     * @param null $type
     * @param null $typeId
     * @return string|Response
     * @throws Exception
     */
    public function renderComponentBySlug($componentSlug, $values, Component $component = null, $edit = false, $type = null, $typeId = null)
    {
        return $this->renderComponentController->renderComponentBySlug($componentSlug, $values, $component, $edit, $type, $typeId);
    }

    /**
     * @param $slug
     * @return string
     */
    public function slugify($slug): string
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
