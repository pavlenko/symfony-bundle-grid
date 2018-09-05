<?php

namespace PE\Bundle\GridBundle\Twig;

use PE\Bundle\GridBundle\Twig\Node\RenderBlockNode;
use PE\Bundle\GridBundle\Twig\TokenParser\GridThemeTokenParser;

class GridExtension extends \Twig_Extension
{
    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            // renderBlock('grid', GridView)
            new \Twig_SimpleFunction('grid', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_header_row', GridView)
            new \Twig_SimpleFunction('grid_header_row', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_header_column', ColumnView)
            new \Twig_SimpleFunction('grid_header_column', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_header_column_widget', ColumnView)
            new \Twig_SimpleFunction('grid_header_column_widget', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_footer_row', GridView)
            new \Twig_SimpleFunction('grid_footer_row', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_body_row', RowView)
            new \Twig_SimpleFunction('grid_body_row', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_body_cell', CellView)
            new \Twig_SimpleFunction('grid_body_cell', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),

            // renderBlock('grid_body_cell_widget', CellView)
            new \Twig_SimpleFunction('grid_body_cell_widget', null, ['node_class' => RenderBlockNode::class, 'is_safe' => ['html']]),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTokenParsers()
    {
        return array(
            // {% grid_theme grid "SomeBundle::widgets.twig" %}
            new GridThemeTokenParser(),
        );
    }
}