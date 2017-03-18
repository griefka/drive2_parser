<?php

namespace Helpers\Queues\Tasks;

class ModelTasks extends AbstractTask
{
    function prepareContent()
    {
        $allBrands = $this->container->get('brandRepository')->all();

        foreach ($allBrands as $brand){
            $this->content[] = $this->parser->parseEntity('model', '.c-block--content nav span a', $brand);
        }
    }

}