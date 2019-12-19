<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\Component;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;


class RenderComponentController extends AbstractController
{
    private $appKernel;
    private $em;

    public function __construct(KernelInterface $appKernel, EntityManagerInterface $entityManager)
    {
        $this->appKernel = $appKernel;
        $this->em = $entityManager;
    }

    public function renderComponent(Component $component)
    {
        $slug = $component->getComponentTemplate()->getSlug();

        $builderClassName = '\Akyos\BuilderBundle\Components\\'.$this->camelCase($slug).'\\'.$this->camelCase($slug).'ComponentController';
        $appClassName = '\App\Components\\'.$this->camelCase($slug).'\\'.$this->camelCase($slug).'ComponentController';

        if(class_exists($appClassName)) {
            $view = $appClassName::getTemplateName();
            $params['component'] = $component;
            $params['values'] = array();
            $params['customClasses'] = $component->getCustomClasses();
            $params['customId'] = $component->getCustomId();
            foreach ($component->getComponentValues() as $value) {
                $params['values'][$value->getComponentField()->getSlug()] = $value->getValue();
            }

            $classInstance = new $appClassName($this->em);
            $params = $classInstance->getParameters($params);

            return $this->renderView('@Components/'.$view, $params);

        } elseif(class_exists($builderClassName)) {
            $view = $builderClassName::getTemplateName();
            $params['component'] = $component;
            $params['values'] = array();
            $params['customClasses'] = $component->getCustomClasses();
            $params['customId'] = $component->getCustomId();
            foreach ($component->getComponentValues() as $value) {
                $params['values'][$value->getComponentField()->getSlug()] = $value->getValue();
            }

            $classInstance = new $builderClassName($this->em);
            $params = $classInstance->getParameters($params);

            return $this->renderView($view, $params);

        } else {
            throw $this->createNotFoundException('Le controller pour ce composant n\'existe pas!');
        }
    }

    public function renderComponentBySlug($componentSlug, $values)
    {
        $builderClassName = '\Akyos\BuilderBundle\Components\\'.$this->camelCase($componentSlug).'\\'.$this->camelCase($componentSlug).'ComponentController';
        $appClassName = '\App\Components\\'.$this->camelCase($componentSlug).'\\'.$this->camelCase($componentSlug).'ComponentController';

        if(class_exists($appClassName)) {
            $view = $appClassName::getTemplateName();
            $params['values'] = $values;
            $params = $appClassName::getParameters($params);
            return $this->renderView('@Components/'.$view, $params);

        } elseif(class_exists($builderClassName)) {
            $view = $builderClassName::getTemplateName();
            $params['values'] = $values;
            $params = $builderClassName::getParameters($params);
            return $this->renderView($view, $params);

        } else {
            throw $this->createNotFoundException('Le controller pour ce composant n\'existe pas!');
        }
    }

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
