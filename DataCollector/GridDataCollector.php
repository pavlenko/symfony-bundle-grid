<?php

namespace PE\Bundle\GridBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class GridDataCollector extends DataCollector
{
    /**
     * @inheritDoc
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // TODO: Implement collect() method.
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'pe_grid';
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        // TODO: Implement reset() method.
    }
}