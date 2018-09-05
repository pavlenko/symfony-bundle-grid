<?php

namespace PE\Bundle\GridBundle\Twig;

use PE\Component\Grid\View\BaseView;
use PE\Component\Grid\View\CellView;
use PE\Component\Grid\View\ColumnView;
use PE\Component\Grid\View\RowView;

class GridRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var string[]|\Twig_Template[]
     */
    private $defaultThemes;

    /**
     * @var \SplObjectStorage Grid themes, indexed by view
     */
    private $themes;

    /**
     * @var array
     */
    private $varsStack = [];

    /**
     * @var \SplObjectStorage
     */
    private $blocks = [];

    /**
     * Constructor
     *
     * @param \Twig_Environment         $environment
     * @param string[]|\Twig_Template[] $defaultThemes
     */
    public function __construct(\Twig_Environment $environment, array $defaultThemes = [])
    {
        $this->environment   = $environment;
        $this->defaultThemes = $defaultThemes ?: ['@PEGrid/Grid/grid_default.html.twig'];

        $this->themes = new \SplObjectStorage();
        $this->blocks = new \SplObjectStorage();
    }

    /**
     * Set themes for a view
     *
     * @param BaseView           $view
     * @param array|\Traversable $themes
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function setTheme(BaseView $view, $themes)
    {
        $this->themes[$view] = [];

        foreach ($themes as $theme) {
            //TODO do not load theme here
            if (!($theme instanceof \Twig_Template)) {
                $theme = $this->environment->loadTemplate($theme);
            }

            $this->loadBlocks($theme);
            $this->themes[$view] = array_merge($this->themes[$view], [$theme]);
        }
    }

    /**
     * Render a block based on called function name
     *
     * @param string   $name
     * @param BaseView $view
     * @param array    $variables
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error
     */
    public function renderBlock($name, BaseView $view, array $variables = [])
    {
        $viewCacheKey = spl_object_hash($view);

        // The variables are cached globally for a view (instead of for the current suffix)
        if (!isset($this->varsStack[$viewCacheKey])) {
            $this->varsStack[$viewCacheKey] = array();

            // The default variable scope contains all view variables, merged with
            // the variables passed explicitly to the helper
            $scopeVariables = $view->vars;

            $varInit = true;
        } else {
            // Reuse the current scope and merge it with the explicitly passed variables
            $scopeVariables = end($this->varsStack[$viewCacheKey]);

            $varInit = false;
        }

        // Merge the passed with the existing attributes
        if (isset($variables['attr']) && isset($scopeVariables['attr'])) {
            $variables['attr'] = array_replace($scopeVariables['attr'], $variables['attr']);
        }

        // Merge the passed with the exist *label* attributes
        if (isset($variables['label_attr']) && isset($scopeVariables['label_attr'])) {
            $variables['label_attr'] = array_replace($scopeVariables['label_attr'], $variables['label_attr']);
        }

        // Do not use array_replace_recursive(), otherwise array variables cannot be overwritten
        $variables = array_replace($scopeVariables, $variables);

        $this->varsStack[$viewCacheKey][] = $variables;

        // Trim "grid_" prefix for use as block name suffix
        $blockSuffix = substr($name, 5) ?: $name;

        $theme = null;
        if (isset($view->vars['block_prefixes']) && count($view->vars['block_prefixes'])) {
            // Find theme based on block prefixes
            foreach (array_reverse($view->vars['block_prefixes']) as $blockPrefix) {
                $blockName = $blockPrefix . '_' . $blockSuffix;
                if ($theme = $this->findThemeForBlock($blockName, $view)) {
                    $name = $blockName;
                    break;
                }
            }
        }

        if (!$theme) {
            // Find theme based on block name
            $theme = $this->findThemeForBlock($name, $view);
        }

        $template = $theme ?: reset($this->defaultThemes);

        // Render the block
        ob_start();
        $template->displayBlock($name, $this->environment->mergeGlobals($variables));
        $output = ob_get_clean();

        // Clear the stack
        array_pop($this->varsStack[$viewCacheKey]);

        if ($varInit) {
            unset($this->varsStack[$viewCacheKey]);
        }

        return $output;
    }

    /**
     * Find the template where a block is defined
     *
     * @param string   $name
     * @param BaseView $view
     *
     * @return \Twig_Template|null
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function findThemeForBlock($name, BaseView $view)
    {
        // Search themes for current view
        if (isset($this->themes[$view])) {
            foreach ($this->themes[$view] as $theme) {
                //TODO load theme blocks here
                if (in_array($name, $this->blocks[$theme])) {
                    return $theme;
                }
            }
        }

        // Search themes for parent view
        if (
            ($view instanceof RowView || $view instanceof ColumnView || $view instanceof CellView)
            && $view->grid
            && $theme = $this->findThemeForBlock($name, $view->grid)
        ) {
            return $theme;
        }

        // Search default themes
        foreach ($this->defaultThemes as &$theme) {
            if (!($theme instanceof \Twig_Template)) {
                $theme = $this->environment->loadTemplate($theme);
            }

            if (in_array($name, $this->blocks[$theme])) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * Load all blocks from the current theme
     *
     * @param \Twig_Template $theme
     *
     * @throws \Twig_Error_Loader
     */
    private function loadBlocks(\Twig_Template $theme)
    {
        if (isset($this->blocks[$theme])) {
            return;
        }

        $this->blocks[$theme] = array_keys($theme->getBlocks());

        if ($parent = $theme->getParent([])) {
            $this->loadBlocks($parent);
            $this->blocks[$theme] = array_merge($this->blocks[$theme], $this->blocks[$parent]);
        }
    }
}