<?php

namespace Akyos\BuilderBundle\Controller;

use Akyos\BuilderBundle\Entity\BuilderOptions;
use Akyos\BuilderBundle\Form\BuilderOptionsType;
use Akyos\BuilderBundle\Repository\BuilderOptionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/builder/options", name="builder_options")
 * @IsGranted ("options-du-builder")
 */
class BuilderOptionsController extends AbstractController
{
	/**
	 * @Route("/", name="", methods={"GET", "POST"})
	 * @param BuilderOptionsRepository $builderOptionsRepository
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 */
    public function index(BuilderOptionsRepository $builderOptionsRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $builderOptions = $builderOptionsRepository->findAll();
        if(!$builderOptions) {
            $builderOptions = new BuilderOptions();
        } else {
            $builderOptions = $builderOptions[0];
        }

        $entities = [];
        $meta = $entityManager->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m) {
            if(!preg_match('/Component|Option|Menu|ContactForm|Seo|User|PostCategory/i', $m->getName())) {
                $entities[] = $m->getName();
            }
        }

        $form = $this->createForm(BuilderOptionsType::class, $builderOptions, [
            'entities' => $entities
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($builderOptions);
            $entityManager->flush();

            return $this->redirectToRoute('builder_options');
        }

        return $this->render('@AkyosBuilder/builder_options/new.html.twig', [
            'builder_option' => $builderOptions,
            'form' => $form->createView(),
        ]);
    }
}
