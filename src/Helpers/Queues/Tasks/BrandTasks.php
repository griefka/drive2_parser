<?php


namespace Helpers\Queues\Tasks;


class BrandTasks extends AbstractTask
{
    protected function prepareContent()
    {
       $this->content[] = $this->parser->parseEntity('brand', '.c-block--content nav span a');
    }
}