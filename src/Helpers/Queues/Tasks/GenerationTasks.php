<?php

namespace Helpers\Queues\Tasks;


use Parsers\ParserDrive2;

class GenerationTasks extends AbstractTask
{

    function prepareContent()
    {
        $allModels = $this->container->get('modelRepository')->all();

        foreach ($allModels as $model){
            $this->content[] = $this->parser->parseEntity(ParserDrive2::PAGE_GENERATIONS, '.c-block .c-gen-card__caption a', $model);
        }

    }

}