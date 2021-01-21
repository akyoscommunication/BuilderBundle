<?php
namespace Akyos\BuilderBundle\Service;

use Akyos\CoreBundle\Entity\AdminAccess;
use Akyos\CoreBundle\Repository\AdminAccessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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

    public function setDefaults()
    {
        if (!$this->adminAccessRepository->findOneByName("Builder"))
        {
            $adminAccess = new AdminAccess();
            $adminAccess
                ->setName('Builder')
                ->setRoles([])
                ->setIsLocked(true)
            ;
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }
        if (!$this->adminAccessRepository->findOneByName("Modèles du builder"))
        {
            $adminAccess = new AdminAccess();
            $adminAccess
                ->setName('Modèles du builder')
                ->setRoles([])
                ->setIsLocked(true)
            ;
            $this->entityManager->persist($adminAccess);
            $this->entityManager->flush();
        }

        if (!$this->adminAccessRepository->findOneByName("Options du builder"))
        {
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