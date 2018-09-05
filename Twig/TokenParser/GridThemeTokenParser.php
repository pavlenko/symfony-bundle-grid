<?php

namespace PE\Bundle\GridBundle\Twig\TokenParser;

use PE\Bundle\GridBundle\Twig\Node\GridThemeNode;

class GridThemeTokenParser extends \Twig_TokenParser
{
    /**
     * @inheritDoc
     */
    public function parse(\Twig_Token $token)
    {
        $line   = $token->getLine();
        $stream = $this->parser->getStream();

        $form = $this->parser->getExpressionParser()->parseExpression();
        $only = false;

        if ($this->parser->getStream()->test(\Twig_Token::NAME_TYPE, 'with')) {
            $this->parser->getStream()->next();
            $resources = $this->parser->getExpressionParser()->parseExpression();

            if ($this->parser->getStream()->nextIf(\Twig_Token::NAME_TYPE, 'only')) {
                $only = true;
            }
        } else {
            $resources = new \Twig_Node_Expression_Array(array(), $stream->getCurrent()->getLine());
            do {
                $resources->addElement($this->parser->getExpressionParser()->parseExpression());
            } while (!$stream->test(\Twig_Token::BLOCK_END_TYPE));
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new GridThemeNode($form, $resources, $line, $this->getTag(), $only);
    }

    /**
     * @inheritDoc
     */
    public function getTag()
    {
        return 'pe_grid_theme';
    }
}