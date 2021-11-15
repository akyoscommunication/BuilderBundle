<?php

namespace Akyos\BuilderBundle\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class createComponent extends Command
{
    protected static $defaultName = 'app:make:component';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('')
            ->setHelp('');

        $this->addArgument('name', InputArgument::REQUIRED, 'Name of Component');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $componentDir = __DIR__ . '/../../../src/Components/';
        $servicesDir = __DIR__ . '/../../../config/';
        $nameLCFirst = lcfirst(implode('', array_map( static function($word) { return ucfirst($word); } , explode('_', $input->getArgument('name')))));
        $nameUCFirst = ucfirst(implode('', array_map( static function($word) { return ucfirst($word); } , explode('_', $input->getArgument('name')))));
        $componentComponentDir = __DIR__ . '/../../../src/Components/'.$nameUCFirst."/";


        $controller_content = "<?php
        
namespace App\Components\\$nameUCFirst;

use Akyos\BuilderBundle\Interfaces\ComponentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class $nameUCFirst"."ComponentController extends AbstractController implements ComponentInterface
{
    public function getTemplateName()
    {
        return '/$nameUCFirst/$nameLCFirst"."_component.html.twig';
    }

    public function getParameters(\$params = null)
    {
        
        return \$params;
    }
}";

        $services_content = "
    component.".$input->getArgument('name').":
      alias: App\Components\\$nameUCFirst\\$nameUCFirst"."ComponentController
      public: true";

        /*Path*/
        if(!file_exists($componentDir.$nameUCFirst)){
            $output->writeln([
                'Création du dossier'.$componentDir.$nameUCFirst.'...'
            ]);
            if (!mkdir($concurrentDirectory = $componentDir . $nameUCFirst) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
            $output->writeln([
                'Le dossier a bien été créé'
            ]);
        }
        /*Controller*/
        if(!file_exists($componentComponentDir.$nameUCFirst.'ComponentController.php')) {
            $output->writeln([
                'Création du Controlleur '.$componentComponentDir.$nameUCFirst.'ComponentController.php ...'
            ]);
            file_put_contents($componentComponentDir.$nameUCFirst.'ComponentController.php', $controller_content);
            $output->writeln([
                'Le fichier a bien été créé'
            ]);
        }
        /*Twig*/
        if(!file_exists($componentComponentDir.$nameLCFirst.'_component.html.twig')) {
            $output->writeln([
                'Création de la vue Twig '.$componentComponentDir.$nameLCFirst.'_component.html.twig ...'
            ]);
            file_put_contents($componentComponentDir.$nameLCFirst.'_component.html.twig', "{{ dump() }}");
            $output->writeln([
                'Le fichier a bien été créé'
            ]);
        }
        $output->writeln([
            'Edition du services.yaml'
        ]);
        file_put_contents($servicesDir.'services.yaml', PHP_EOL.$services_content, FILE_APPEND);
        $output->writeln([
            'Edition terminée'
        ]);
        return 0;
    }
}