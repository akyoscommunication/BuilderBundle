<?php

namespace Akyos\BuilderBundle\Command;

use Akyos\BuilderBundle\Entity\Component;
use Akyos\CoreBundle\Twig\CoreExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'builder:fix-components-namespaces',
)]
class BuilderFixComponentsNamespacesCommand extends Command
{
    private readonly EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, private readonly CoreExtension $coreExtension)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure(): void    {
        $this->setDescription('Fix les namespaces des components pour les mettres en namespace complet.');
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
