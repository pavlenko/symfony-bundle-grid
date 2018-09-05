<?php

namespace PE\Bundle\GridBundle\Grid\ColumnTypeExtension;

use PE\Component\Grid\ColumnInterface;
use PE\Component\Grid\ColumnType\ActionsColumnType;
use PE\Component\Grid\ColumnTypeExtension\AbstractColumnTypeExtension;
use PE\Component\Grid\View\CellView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ActionsColumnTypeExtension extends AbstractColumnTypeExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('action_resolver', function (Options $options, OptionsResolver $value) {
            $value->setDefaults([
                'route'            => null,
                'route_parameters' => [],
                'route_fields_map' => [],
            ]);

            return $value;
        });
    }

    /**
     * @inheritDoc
     */
    public function buildCellView(CellView $view, ColumnInterface $column, $row, array $options)
    {
        foreach ($view->vars['actions'] as $action => &$options) {
            if (!empty($options['route'])) {
                $parameters = $options['route_parameters'];

                foreach ($options['route_fields_map'] as $parameter => $field) {
                    $parameters[$parameter] = $column->getDataMapper()->getValue($row, $field);
                }

                $options['url'] = $this->urlGenerator->generate($options['route'], $parameters);
                unset($options['route']);
            }
        };
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return ActionsColumnType::class;
    }
}