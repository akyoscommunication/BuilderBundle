<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\BuilderTemplate;
use Akyos\BuilderBundle\Entity\Component;
use Akyos\BuilderBundle\Form\BuilderTemplateType;
use Akyos\BuilderBundle\Repository\BuilderTemplateRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/builder/templates", name="builder_template_")
 */
class BuilderTemplateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     * @param BuilderTemplateRepository $builderTemplateRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     *
     * @return Response
     */
    public function index(BuilderTemplateRepository $builderTemplateRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $els = $paginator->paginate(
            $builderTemplateRepository->findAll(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('@AkyosCore/crud/index.html.twig', [
            'els' => $els,
            'title' => 'Templates de page builder',
            'entity' => 'BuilderTemplate',
            'route' => 'builder_template',
            'fields' => array(
                'ID' => 'Id',
                'Titre du template' => 'Title',
            ),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param KernelInterface $kernel
     *
     * @return Response
     * @throws Exception
     */
    public function new(Request $request, KernelInterface $kernel): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $builderTemplate = new BuilderTemplate();
        $builderTemplate->setTitle("Nouveau template");
        $entityManager->persist($builderTemplate);
        $entityManager->flush();

        return $this->redirectToRoute('builder_template_edit', ['id' => $builderTemplate->getId()]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     * @param Request $request
     * @param BuilderTemplate $builderTemplate
     * @param BuilderController $builderController
     * @return Response
     */
    public function edit(Request $request, BuilderTemplate $builderTemplate, BuilderController $builderController): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BuilderTemplateType::class, $builderTemplate);
        $form->handleRequest($request);

        if (($this->forward('Akyos\CoreBundle\Controller\CoreBundleController::checkIfBundleEnable', ['bundle' => 'builder', 'entity' => 'BuilderTemplate'])->getContent() === "true")) {
            if (!$form->isSubmitted()) {
                $builderController->initCloneComponents('BuilderTemplate', $builderTemplate->getId());
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->forward('Akyos\CoreBundle\Controller\CoreBundleController::checkIfBundleEnable', ['bundle' => 'builder', 'entity' => 'BuilderTemplate'])->getContent() === "true") {
                $builderController->tempToProd('BuilderTemplate', $builderTemplate->getId());
            }

            $em->flush();

            return $this->redirect($request->getUri());
        } elseif($form->isSubmitted() && !($form->isValid())) {
            throw $this->createNotFoundException("Formulaire invalide.");
        }

        return $this->render('@AkyosCore/crud/edit.html.twig', [
            'el' => $builderTemplate,
            'title' => 'BuilderTemplate',
            'entity' => 'BuilderTemplate',
            'route' => 'builder_template',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param BuilderTemplate $builderTemplate
     * @param BuilderController $builderController
     * @return Response
     */
    public function delete(Request $request, BuilderTemplate $builderTemplate, BuilderController $builderController): Response
    {
        if ($this->isCsrfTokenValid('delete'.$builderTemplate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($this->forward('Akyos\CoreBundle\Controller\CoreBundleController::checkIfBundleEnable', ['bundle' => 'builder', 'entity' => 'BuilderTemplate'])->getContent() === "true") {
                $builderController->onDeleteEntity('BuilderTemplate', $builderTemplate->getId());
            }
            $entityManager->remove($builderTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('builder_template_index');
    }
}
