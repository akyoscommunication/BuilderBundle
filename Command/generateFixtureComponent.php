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

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('')->setHelp('');

        $this->addArgument('id', InputArgument::REQUIRED, 'Id of Component');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ComponentTemplate $component */
        $component = $this->em->getRepository(ComponentTemplate::class)->find($input->getArgument('id'));
        $name = $component->getSlug();

        $nameUCFirst = ucfirst(implode('', array_map(static function ($word) {
            return ucfirst($word);
        }, explode('_', $name))));
        $componentComponentDir = __DIR__ . '/../../../src/Components/' . $nameUCFirst . "/";

        $fixtureContent = "<?php

namespace App\Components\\${nameUCFirst};

use Akyos\BuilderBundle\Service\FixturesHelpers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ${nameUCFirst}" . "Fixtures extends Fixture implements FixtureGroupInterface
{
    private \$fixturesHelpers;

    public function __construct(FixturesHelpers \$fixturesHelpers)
    {
        \$this->fixturesHelpers = \$fixturesHelpers;
    }
    
    public function load(ObjectManager \$manager): void
    {
        \$slug = '" . $component->getSlug() . "';
        \$name = '" . $component->getName() . "';
        \$shortDescription = '" . $component->getShortDescription() . "';
        \$isContainer = " . ($component->getIsContainer() ? 'true' : 'false') . ";
        \$prototype = '" . $component->getPrototype() . "';
        \$componentFields = [
            ";

        foreach ($component->getComponentFields() as $field) {
            $fixtureContent .= '[
                "name" => "' . $field->getName() . '",
                "slug" => "' . $field->getSlug() . '",
                "desc" => "' . $field->getShortDescription() . '",
                "type" => "' . $field->getType() . '",
                "entity" => "' . $field->getEntity() . '",
                "option" => [' . implode(',', array_map(static function ($e) {
                    return '"' . $e . '"';
                }, $field->getFieldValues())) . '],
                "group" => "' . $field->getGroups() . '",
            ],';
        }

        $fixtureContent .= "
        ];

        \$this->fixturesHelpers->updateBdd(\$slug, \$name, \$shortDescription, \$isContainer, \$prototype, \$componentFields);
    }

    /**
     * @inheritDoc
     */
    public static function getGroups(): array
    {
        return ['component', 'component-" . $component->getSlug() . "'];
    }
}";

        /*Fixture*/
        if (!file_exists($componentComponentDir . $nameUCFirst . 'Fixtures.php')) {
            $output->writeln(['Création du Controlleur ' . $componentComponentDir . $nameUCFirst . 'Fixtures.php ...']);
            file_put_contents($componentComponentDir . $nameUCFirst . 'Fixtures.php', $fixtureContent);
            $output->writeln(['Le fichier a bien été créé']);
        }
        $output->writeln(['Edition terminée']);
        return 0;
    }
}