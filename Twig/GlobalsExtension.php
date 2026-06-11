<?php

declare(strict_types=1);

namespace Akyos\BuilderBundle\Twig;

use Akyos\BuilderBundle\Repository\BuilderOptionsRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalsExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(protected BuilderOptionsRepository $builderOptionsRepository)
    {
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        $builderOptions = $this->builderOptionsRepository->findAll();
        if ($builderOptions) {
            $builderOptions = $builderOptions[0];
        }
        return ['builder_options' => $builderOptions];
    }
}
