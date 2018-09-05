<?php

namespace PE\Bundle\GridBundle\DependencyInjection\Compiler;

use PE\Component\Grid\Grid;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GridCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $this->processGridTypes($container);
        $this->processColumnTypes($container);
        $this->processRegistry($container);
    }

    private function processGridTypes(ContainerBuilder $container)
    {
        $types = [];
        foreach ($container->findTaggedServiceIds('pe_grid.grid_type') as $id => $tags) {
            $types[$container->getDefinition($id)->getClass()] = new Reference($id);
        }

        $typeExtensions = [];
        foreach ($container->findTaggedServiceIds('pe_grid.grid_type_extension') as $id => $tags) {
            $typeExtensions[] = new Reference($id);
        }

        $definition = $container->getDefinition('pe_grid.grid_extension.container');
        $definition->replaceArgument(0, ServiceLocatorTagPass::register($container, $types));
        $definition->replaceArgument(1, $typeExtensions);
    }

    private function processColumnTypes(ContainerBuilder $container)
    {
        $types = [];
        foreach ($container->findTaggedServiceIds('pe_grid.column_type') as $id => $tags) {
            $types[$container->getDefinition($id)->getClass()] = new Reference($id);
        }

        $typeExtensions = [];
        foreach ($container->findTaggedServiceIds('pe_grid.column_type_extension') as $id => $tags) {
            $typeExtensions[] = new Reference($id);
        }

        $definition = $container->getDefinition('pe_grid.column_extension.container');
        $definition->replaceArgument(0, ServiceLocatorTagPass::register($container, $types));
        $definition->replaceArgument(1, $typeExtensions);
    }

    private function processRegistry(ContainerBuilder $container)
    {
        $gridExtensions = [];
        foreach ($container->findTaggedServiceIds('pe_grid.grid_extension') as $id => $tags) {
            $gridExtensions[] = new Reference($id);
        }

        $columnExtensions = [];
        foreach ($container->findTaggedServiceIds('pe_grid.column_extension') as $id => $tags) {
            $columnExtensions[] = new Reference($id);
        }

        $definition = $container->getDefinition('pe_grid.registry');
        $definition->replaceArgument(0, $gridExtensions);
        $definition->replaceArgument(1, $columnExtensions);
    }
}