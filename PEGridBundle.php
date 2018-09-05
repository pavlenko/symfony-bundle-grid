<?php

namespace PE\Bundle\GridBundle;

use PE\Bundle\GridBundle\DependencyInjection\Compiler\GridCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PEGridBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GridCompilerPass());
    }
}