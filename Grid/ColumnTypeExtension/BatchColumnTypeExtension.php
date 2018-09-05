<?php

namespace PE\Bundle\GridBundle\Grid\ColumnTypeExtension;

use PE\Component\Grid\ColumnType\BatchColumnType;
use PE\Component\Grid\ColumnTypeExtension\AbstractColumnTypeExtension;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BatchColumnTypeExtension extends AbstractColumnTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('action_resolver', function (Options $options, OptionsResolver $value) {
            $value->setDefaults([
                'route'            => null,
                'route_parameters' => []
            ]);

            return $value;
        });
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return BatchColumnType::class;
    }
}