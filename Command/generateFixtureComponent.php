<?php

namespace Akyos\BuilderBundle\Command;

use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class generateFixtureComponent extends Command
{

    protected static $defaultName = 'app:make:componentFixture';

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('')
            ->setHelp('');

        $this->addArgument('id', InputArgument::REQUIRED, 'Id of Component');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ComponentTemplate $component */
        $component = $this->em->getRepository(ComponentTemplate::class)->find($input->getArgument('id'));
        $name = $component->getSlug();

        $componentDir = __DIR__ . '/../../../src/Components/';
        $nameLCFirst = lcfirst(join('', array_map( function($word) { return ucfirst($word); } , explode('_', $name))));
        $nameUCFirst = ucfirst(join('', array_map( function($word) { return ucfirst($word); } , explode('_', $name))));
        $componentComponentDir = __DIR__ . '/../../../src/Components/'.$nameUCFirst."/";

        $fixtureContent = "<?php

namespace App\Components\\${nameUCFirst};

use Akyos\BuilderBundle\Entity\ComponentField;
use Akyos\BuilderBundle\Entity\ComponentTemplate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ${nameUCFirst}"."Fixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager \$manager): void
    {
        \$component = new ComponentTemplate();
        \$component->setName('".$component->getName()."');
        \$component->setSlug('".$component->getSlug()."');
        \$component->setShortDescription('".$component->getShortDescription()."');
        \$component->setIsContainer(".($component->getIsContainer() ? 'true' : 'false').");
        \$component->setPrototype('".$component->getPrototype()."');

        \$componentFieldArray = [
            ";

        foreach ($component->getComponentFields() as $field) {
            $fixtureContent .= '[
                "name" => "'.$field->getName().'",
                "slug" => "'.$field->getSlug().'",
                "desc" => "'.$field->getShortDescription().'",
                "type" => "'.$field->getType().'",
                "entity" => "'.$field->getEntity().'",
                "option" => ['.implode(',', array_map(function ($e) {
                    return '"'.$e.'"';
                }, $field->getFieldValues())).'],
                "group" => "'.$field->getGroups().'",
            ],';
        }

        $fixtureContent .= "
        ];

        foreach (\$componentFieldArray as \$componentField)
        {
            \$newComponentField = new ComponentField();

            \$newComponentField->setComponentTemplate(\$component);

            \$newComponentField->setName(\$componentField['name']);
            \$newComponentField->setSlug(\$componentField['slug']);
            \$newComponentField->setShortDescription(\$componentField['desc']);
            \$newComponentField->setType(\$componentField['type']);
            \$newComponentField->setEntity(\$componentField['entity']);
            \$newComponentField->setFieldValues(\$componentField['option']);
            \$newComponentField->setGroups(\$componentField['group']);

            \$manager->persist(\$newComponentField);
        }

         \$manager->persist(\$component);

        \$manager->flush();
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component'];
    }
}";

        /*Fixture*/
        if(!file_exists($componentComponentDir.$nameUCFirst.'Fixtures.php')) {
            $output->writeln([
                'Création du Controlleur '.$componentComponentDir.$nameUCFirst.'Fixtures.php ...'
            ]);
            file_put_contents($componentComponentDir.$nameUCFirst.'Fixtures.php', $fixtureContent);
            $output->writeln([
                'Le fichier a bien été créé'
            ]);
        }
        $output->writeln([
            'Edition terminée'
        ]);
        return 0;
    }
}