<?php

namespace Akyos\BuilderBundle\Service;

use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Akyos\BuilderBundle\Entity\ComponentValue;
use Akyos\BuilderBundle\Entity\ComponentValueTranslation;
use Akyos\BuilderBundle\Form\ChoiceBuilderTemplateType;
use Akyos\BuilderBundle\Form\MakeTemplateType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Translatable\Query\TreeWalker\TranslationWalker;
use Gedmo\Translatable\TranslatableListener;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Builder
{
    private ?Request $request;
    private EntityManagerInterface $em;
    private Environment $environment;
    private ContainerInterface $container;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, Environment $environment, ContainerInterface $container)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->em = $em;
        $this->environment = $environment;
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getTab(): string
    {
        $tab = '<li class="nav-item">';
            $tab .= '<a class="nav-link" id="builder-tab" data-toggle="tab" href="#builder" role="tab" aria-controls="builder" aria-selected="false">Builder</a>';
        $tab .= '</li>';
        return $tab;
    }

    /**
     * @param $objectType
     * @param $objectId
     * @return string|RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getTabContent($objectType, $objectId)
    {
        /** @var QueryBuilder $qbc */
        $qbc = $this->em->getRepository(Component::class)->createQueryBuilder('c');
        $qbc
            ->andWhere($qbc->expr()->eq('c.type', ':type'))
            ->andWhere($qbc->expr()->eq('c.typeId', ':typeId'))
            ->andWhere($qbc->expr()->eq('c.isTemp', true))
            ->andWhere($qbc->expr()->isNull('c.parentComponent'))
            ->orderBy('c.position', 'ASC')
            ->setParameters([
                'type' => $objectType,
                'typeId' => $objectId,
            ]);

        foreach ($this->em->getEventManager()->getListeners() as $event => $listeners) {
            foreach ($listeners as $hash => $listener) {
                if ($listener instanceof TranslatableListener) {
                    $qbc
                        ->getQuery()
                        ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, TranslationWalker::class);
                }
            }
        }

        $instance_components = $qbc
            ->getQuery()
            ->getResult();

        $components = $this->em->getRepository(ComponentTemplate::class)->findAll();

        $choiceBuilderTemplateForm = $this->container->get('form.factory')->create(ChoiceBuilderTemplateType::class, NULL, []);
        $choiceBuilderTemplateForm->handleRequest($this->request);
        if ($choiceBuilderTemplateForm->isSubmitted() && $choiceBuilderTemplateForm->isValid()) {
            /** @var BuilderTemplate $template */
            $template = $choiceBuilderTemplateForm->get('choice_template')->getData();
            $this->cloneTemplateBuilder($objectType, $objectId, $template->getId());

            return new RedirectResponse($this->request->getUri(), 302);
        }

        $makeBuilderTemplateForm = $this->container->get('form.factory')->create(MakeTemplateType::class, NULL, []);
        $makeBuilderTemplateForm->handleRequest($this->request);
        if ($makeBuilderTemplateForm->isSubmitted() && $makeBuilderTemplateForm->isValid()) {
            /** @var BuilderTemplate $template */
            $templateTitle = $makeBuilderTemplateForm->get('title')->getData();
            $this->makeTemplate($objectType, $objectId, $templateTitle);
            return new RedirectResponse($this->request->getUri(), 302);
        }

        return $this->environment->render('@AkyosBuilder/builder/render.html.twig', [
            'makeBuilderTemplateForm' => $makeBuilderTemplateForm->createView(),
            'choiceBuilderTemplateForm' => $choiceBuilderTemplateForm->createView(),
            'instance_components' => $instance_components,
            'type' => $objectType,
            'typeId' => $objectId,
            'components' => $components,
        ]);
    }

    /**
     * @param $type
     * @param $typeId
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function initCloneComponents($type, $typeId): bool
    {
        $components = $this->em->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'parentComponent' => null));
        $componentsTemp = $this->em->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => true));
        $componentsProd = $this->em->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => false));

        // Check if there are temp components updated
        if ($componentsProd) {
            $dateTimeProd = $componentsProd[0]->getUpdatedAt();
            foreach ($componentsTemp as $temp) {
                if ($temp->getUpdatedAt() > $dateTimeProd) {
                    $this->container->get('session')->getFlashBag()->add("warning", "L'élément n'est pas à jour en production.");
                    break;
                }
            }
        }

        // Check if there are already temp components
        if (!$componentsTemp) {
            foreach ($components as $component) {
                // If he has childs
                if (($component instanceof Component) && !($component->getParentComponent())) {
                    $this->cloneComponent($component);
                }
            }
            $this->em->flush();
        }

        return true;
    }

    /**
     * @param $type
     * @param $typeId
     * @return bool
     */
    public function tempToProd($type, $typeId): bool
    {
        // Get each components of page.
        $newComponents = $this->em->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId));
        $componentsProd = $this->em->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => false));
        $componentsTemp = $this->em->getRepository(Component::class)->findBy(array('type' => $type, 'typeId' => $typeId, 'isTemp' => true));

        // TODO => If there are no more temp component.
        if ($componentsProd && !$componentsTemp) {
            foreach ($componentsProd as $deleteComponent) {
                $this->em->remove($deleteComponent);
            }
            $this->em->flush();
        } else {

            // Toggle isTemp of each component
            foreach ($newComponents as $newComponent) {
                if ($newComponent instanceof Component) {
                    $newComponent->setIsTemp(!$newComponent->getIsTemp());
                }
            }

            // Delete each temp component of page
            foreach ($componentsProd as $deleteComponent) {
                if ($deleteComponent instanceof Component) {
                    $this->em->remove($deleteComponent);
                }
            }

            $this->em->flush();
        }

        return true;
    }

    /**
     * @param Component $component
     * @param null $parent
     * @param null $changeType
     * @param null $changeTypeId
     * @param int|null $nextPos
     * @param false $makeTemplate
     * @return bool
     */
    public function cloneComponent(Component $component, $parent = null, $changeType = null, $changeTypeId = null, int $nextPos = null, $makeTemplate = false): bool
    {
        $clone = clone $component;
        // Clone each component of page and set isTemp to true
        if ($makeTemplate) {
            $clone->setIsTemp(false);
        } else {
            $clone->setIsTemp(true);
        }
        if ($changeType && $changeTypeId) {
            $clone->setType($changeType);
            $clone->setTypeId($changeTypeId);
            $clone->setPosition((int)$nextPos);
        }
        if ($parent && $parent instanceof Component) {
            $clone->setParentComponent($parent);
            $parent->addChildComponent($clone);
        }
        $this->em->persist($clone);
        $this->em->flush();

        // Clone each componentValue of component clone
        foreach ($component->getComponentValues() as $componentValue) {
            if ($componentValue instanceof ComponentValue) {

                $cloneValue = clone $componentValue;
                $cloneValue->setComponent($clone);

                // EXPLAIN => BUG !! Quand on clone la value, il faut dire au clone de changer de parent et au parent de perdre l'enfant clone
                if (!$makeTemplate) {
                    $componentValue->setComponent($component);
                    $clone->removeComponentValue($componentValue);
                }

                $clone->addComponentValue($cloneValue);
                $this->em->persist($cloneValue);

                foreach ($componentValue->getTranslations()->getValues() as $trans) {
                    $newTrans = new ComponentValueTranslation();
                    $newTrans->setField('value');
                    $newTrans->setObject($cloneValue);
                    $newTrans->setContent($trans->getContent());
                    $newTrans->setLocale($trans->getLocale());

                    $this->em->persist($newTrans);
                }
            }
        }
        if ($component->getChildComponents()) {
            foreach ($clone->getChildComponents() as $childComponent) {
                if ($childComponent->getIsTemp() === false) {
                    $childComponent->setParentComponent($component);
                    $clone->removeChildComponent($childComponent);
                }
            }
            foreach ($component->getChildComponents() as $childComponent) {
                $this->cloneComponent($childComponent, $clone, $changeType, $changeTypeId, $nextPos, $makeTemplate);
            }
        }
        $this->em->flush();

        return true;
    }

    /**
     * @param $type
     * @param $typeId
     * @return bool
     */
    public function onDeleteEntity($type, $typeId): bool
    {
        $components = $this->em->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId]);
        foreach ($components as $component) {
            $this->em->remove($component);
        }

        return true;
    }

    /**
     * @param $type
     * @param $typeId
     * @param $templateId
     * @return bool
     */
    public function cloneTemplateBuilder($type, $typeId, $templateId): bool
    {
        $componentsOfTemplate = $this->em->getRepository(Component::class)->findBy(['type' => 'BuilderTemplate', 'typeId' => $templateId, 'parentComponent' => null, 'isTemp' => false]);

        // Pour mettre à la suite du type;
        $nextPos = count($this->em->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId, 'parentComponent' => null, 'isTemp' => false]));
        foreach ($componentsOfTemplate as $c) {
            /** @var Component $c */
            $this->cloneComponent($c, null, $type, $typeId, $nextPos);
            $nextPos++;
        }

        return true;
    }

    /**
     * @param $type
     * @param $typeId
     * @param $templateTitle
     * @return bool
     */
    public function makeTemplate($type, $typeId, $templateTitle): bool
    {
        $builderTemplate = new BuilderTemplate();
        $builderTemplate->setTitle($templateTitle);
        $this->em->persist($builderTemplate);
        $this->em->flush();

        $tempComponents = $this->em->getRepository(Component::class)->findBy(['type' => $type, 'typeId' => $typeId, 'isTemp' => true, 'parentComponent' => null]);

        foreach ($tempComponents as $c) {
            /** @var Component $c */
            $this->cloneComponent($c, null, 'BuilderTemplate', $builderTemplate->getId(), null, true);
        }

        return true;
    }
}
