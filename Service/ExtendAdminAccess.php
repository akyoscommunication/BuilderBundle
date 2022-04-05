<?php
namespace Akyos\BuilderBundle\Service;

use Akyos\CmsBundle\Entity\AdminAccess;
use Akyos\CmsBundle\Repository\AdminAccessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class ExtendAdminAccess
{
    private AdminAccessRepository $adminAccessRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AdminAccessRepository $adminAccessRepository, EntityManagerInterface $entityManager)
    {
        $this->adminAccessRepository = $adminAccessRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Response
     */
    public function setDefaults(): Response
    {
        if (!$this->adminAccessRepository->findOneBy(['name' => 'Builder'])) {
            $adminAccess = new AdminAccess();
            $adminAccess
                ->setName('Builder')
                ->setRoles([])
                ->setIsLocked(true)
            ;
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        if (!$this->adminAccessRepository->findOneBy(['name' => 'Modèles du builder'])) {
            $adminAccess = new AdminAccess();
            $adminAccess
                ->setName('Modèles du builder')
                ->setRoles([])
                ->setIsLocked(true)
            ;
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        if (!$this->adminAccessRepository->findOneBy(['name' => 'Options du builder'])) {
            $adminAccess = new AdminAccess();
            $adminAccess
                ->setName('Options du builder')
                ->setRoles([])
                ->setIsLocked(true)
            ;
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        return new Response('true');
    }
}