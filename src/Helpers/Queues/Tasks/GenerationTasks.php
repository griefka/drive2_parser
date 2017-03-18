<?php

namespace Helpers\Queues\Tasks;


class GenerationTasks extends AbstractTask
{

    function prepareContent()
    {
        $allModels = $this->container->get('modelRepository')->all();

        foreach ($allModels as $model){
            $this->content[] = $this->parser->parseGenerations($model);
        }

    }

}