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


class RenderComponentController
{
    private EntityManagerInterface $entityManager;
    private ContainerInterface $container;
    private Environment $twig;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container, Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->twig = $twig;
    }

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

        $builderClassName = '\Akyos\BuilderBundle\Components\\'. self::camelCase($slug).'\\'. self::camelCase($slug).'ComponentController';
        $appClassName = '\App\Components\\'. self::camelCase($slug).'\\'. self::camelCase($slug).'ComponentController';

        $params['edit'] = $edit;
        $params['type'] = $type;
        $params['typeId'] = $typeId;
        $params['component'] = $component;
        $params['values'] = array();
        $params['customClasses'] = $component->getCustomClasses();
        $params['customId'] = $component->getCustomId();

        if (class_exists($appClassName)) {
            $view = $appClassName::getTemplateName();
            foreach ($component->getComponentValues() as $value) {
                /** @var ComponentField $componentField */
                $componentField = $value->getComponentField();
                if($componentField->getType()=== 'entity'){
                    $params['values'][$componentField->getSlug()] = $em->getRepository($componentField->getEntity())->find((int)$value->getValue());
                }else{
                    $params['values'][$componentField->getSlug()] = $value->getValue();
                }
            }

            $params = $this->container->get('component.'.strtolower($slug))->getParameters($params);
            if($params instanceof Response) {
                return $params;
            }

            return $this->twig->render('@Components/'.$view, $params);
        }

        if(class_exists($builderClassName)) {
            $view = $builderClassName::getTemplateName();
            foreach ($component->getComponentValues() as $value) {
                /** @var ComponentField $componentField */
                $componentField = $value->getComponentField();
                if($componentField->getType()=== 'entity'){
                    $params['values'][$componentField->getSlug()] = $em->getRepository($componentField->getEntity())->find((int)$value->getValue());
                }else{
                    $params['values'][$componentField->getSlug()] = $value->getValue();
                }
            }

            $params = $this->container->get('component.'.strtolower($slug))->getParameters($params);
            if($params instanceof Response) {
                return $params;
            }
            return $this->twig->render($view, $params);
        }

        throw new RuntimeException('Le controller pour ce composant n\'existe pas! : '.$slug);
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
        $builderClassName = '\Akyos\BuilderBundle\Components\\'. self::camelCase($componentSlug).'\\'. self::camelCase($componentSlug).'ComponentController';
        $appClassName = '\App\Components\\'. self::camelCase($componentSlug).'\\'. self::camelCase($componentSlug).'ComponentController';

        $params['values'] = $values;
        $params['component'] = $component;
        $params['edit'] = $edit;
        $params['type'] = $type;
        $params['typeId'] = $typeId;

        if (class_exists($appClassName)) {
            $view = $appClassName::getTemplateName();
            $params = $this->container->get('component.'.strtolower($componentSlug))->getParameters($params);
            if($params instanceof Response) {
                return $params;
            }
            return $this->twig->render('@Components/'.$view, $params);
        }

        if(class_exists($builderClassName)) {
            $view = $builderClassName::getTemplateName();
            $params = $this->container->get('component.'.strtolower($componentSlug))->getParameters($params);
            if($params instanceof Response) {
                return $params;
            }
            return $this->twig->render($view, $params);
        }

        throw new RuntimeException('Le controller pour ce composant n\'existe pas!');
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
        $str = str_replace(" ", "", $str);
//        $str = lcfirst($str);

        return $str;
    }
}
