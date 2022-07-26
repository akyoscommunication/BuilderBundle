<?php

namespace Akyos\BuilderBundle\Command;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\CoreBundle\Twig\CoreExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BuilderFixComponentsNamespacesCommand extends Command
{
    protected static $defaultName = 'builder:fix-components-namespaces';
    private EntityManagerInterface $em;
    private CoreExtension $coreExtension;

    public function __construct(EntityManagerInterface $em, CoreExtension $coreExtension)
    {
        $this->em = $em;
        $this->coreExtension = $coreExtension;
    }

    protected function configure()
    {
        $this
            ->setDescription('Fix les namespaces des components pour les mettres en namespace complet.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $components = $this->em->getRepository(Component::class)->findAll();

        foreach ($components as $component) {
            $initType = $component->getType();

            if (!class_exists($initType)) {
                $type = $this->coreExtension->getEntityNameSpace($initType);
                $component->setType($type);
                $this->em->flush();
            }
        }

        $io->success('Changement terminé.');

        return 0;
    }
}
