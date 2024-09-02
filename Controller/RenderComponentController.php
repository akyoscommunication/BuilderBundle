<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentField;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

readonly class RenderComponentController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ContainerInterface     $container,
        private Environment            $twig
    ) {}

    /**
     * @param Component $component
     * @param false $edit
     * @param null $type
     * @param null $typeId
     * @return string|Response
     * @throws Exception
     */
    public function renderComponent(Component $component, $edit = false, $type = null, $typeId = null)
    {
        $em = $this->entityManager;
        $slug = $component->getComponentTemplate()->getSlug();

        $params['edit'] = $edit;
        $params['type'] = $type;
        $params['typeId'] = $typeId;
        $params['component'] = $component;
        $params['values'] = [];
        $params['customClasses'] = $component->getCustomClasses();
        $params['customId'] = $component->getCustomId();
        foreach ($component->getComponentValues() as $value) {
            $valueValue = $value->getValue();
            $currentLocale = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
            if($value->getTranslations()) {
                foreach ($value->getTranslations() as $translation) {
                    if($translation->getLocale() === $currentLocale) {
                        $valueValue = $translation->getContent();
                    }
                }
            }
            /** @var ComponentField $componentField */
            $componentField = $value->getComponentField();
            if ($componentField->getType() === 'entity') {
                $params['values'][$componentField->getSlug()] = $em->getRepository($componentField->getEntity())->find((int)$valueValue);
            } else {
                $params['values'][$componentField->getSlug()] = $valueValue;
            }
        }

        $classExists = $this->container->has('component.'.$slug);
        if ($classExists) {
            $componentController = $this->container->get('component.'.$slug);
            $view = $componentController->getTemplateName();
            $params = $componentController->getParameters($params);
            if ($params instanceof Response) {
                return $params;
            }
            return $this->twig->render($view, $params);
        }

        throw new RuntimeException('Le controller pour ce composant n\'existe pas! : ' . $slug);
    }

    /**
     * @param $str
     * @param array $noStrip
     * @return string|string[]
     */
    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);

        //        $str = lcfirst($str);

        return str_replace(" ", "", $str);
    }

    /**
     * @param $componentSlug
     * @param $values
     * @param $component
     * @param $edit
     * @param $type
     * @param $typeId
     * @return string|Response
     * @throws Exception
     */
    public function renderComponentBySlug($componentSlug, $values, $component, $edit, $type, $typeId)
    {
        $classExists = $this->container->has('component.'.$componentSlug);

        $params['values'] = $values;
        $params['component'] = $component;
        $params['edit'] = $edit;
        $params['type'] = $type;
        $params['typeId'] = $typeId;

        if ($classExists) {
            $componentController = $this->container->get('component.'.$componentSlug);
            $view = $componentController->getTemplateName();
            $params = $componentController->getParameters($params);
            if ($params instanceof Response) {
                return $params;
            }
            return $this->twig->render($view, $params);
        }

        throw new RuntimeException('Le controller pour ce composant n\'existe pas!');
    }
}
