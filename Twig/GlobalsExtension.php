<?php

namespace Akyos\BuilderBundle\Twig;

use Akyos\BuilderBundle\Repository\BuilderOptionsRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalsExtension extends AbstractExtension implements GlobalsInterface
{
    protected $builderOptionsRepository;

    public function __construct(BuilderOptionsRepository $builderOptionsRepository)
    {
        $this->builderOptionsRepository = $builderOptionsRepository;
    }

    public function getGlobals(): array
    {
        $builderOptions = $this->builderOptionsRepository->findAll();
        if($builderOptions) {
           $builderOptions = $builderOptions[0];
        }
        return [
            'builder_options' => $builderOptions
        ];
    }
}
