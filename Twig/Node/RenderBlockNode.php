<?php

namespace PE\Bundle\GridBundle\Twig\Node;

use PE\Bundle\GridBundle\Twig\GridRenderer;

class RenderBlockNode extends \Twig_Node_Expression_Function
{
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        /* @var $arguments \Twig_Node[] */
        $arguments = iterator_to_array($this->getNode('arguments'));

        $compiler
            ->write('$this->env->getRuntime(')
            ->string(GridRenderer::class)
            ->raw(')->renderBlock(');

        if (isset($arguments[0])) {
            $compiler->string($this->getAttribute('name'));
            $compiler->raw(', ');
            $compiler->subcompile($arguments[0]);

            if (isset($arguments[1])) {
                $compiler->raw(', ');
                $compiler->subcompile($arguments[1]);
            }
        }

        $compiler->raw(')');
    }
}