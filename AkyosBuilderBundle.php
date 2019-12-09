<?php

namespace Akyos\BuilderBundle;

use Akyos\BuilderBundle\DependencyInjection\BuilderBundleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AkyosBuilderBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new BuilderBundleExtension();
    }
}