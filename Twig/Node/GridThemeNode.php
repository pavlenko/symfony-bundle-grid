<?php

namespace PE\Bundle\GridBundle\Twig\Node;

use PE\Bundle\GridBundle\Twig\GridRenderer;

class GridThemeNode extends \Twig_Node
{
    /**
     * @param \Twig_Node  $node
     * @param \Twig_Node  $resources
     * @param int         $line
     * @param string|null $tag
     * @param bool        $only
     */
    public function __construct(
        \Twig_Node $node,
        \Twig_Node $resources,
        int $line,
        string $tag = null,
        bool $only = false
    ) {
        parent::__construct(['grid' => $node, 'resources' => $resources], ['only' => $only], $line, $tag);
    }

    /**
     * @inheritDoc
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write('$this->env->getRuntime(')
            ->string(GridRenderer::class)
            ->raw(')->setTheme(')
            ->subcompile($this->getNode('grid'))
            ->raw(', ')
            ->subcompile($this->getNode('resources'))
            ->raw(', ')
            ->raw(false === $this->getAttribute('only') ? 'true' : 'false')
            ->raw(");\n");
    }
}