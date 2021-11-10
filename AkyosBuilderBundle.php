<?php

namespace Akyos\BuilderBundle;

use Akyos\BuilderBundle\DependencyInjection\BuilderBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AkyosBuilderBundle extends Bundle
{
    /**
     * @return BuilderBundleExtension
     */
    public function getContainerExtension(): BuilderBundleExtension
    {
        return new BuilderBundleExtension();
    }
}