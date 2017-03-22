<?php


namespace Helpers\Queues\Tasks;


use Parsers\ParserDrive2;

class BrandTasks extends AbstractTask
{
    protected function prepareContent()
    {
       $this->content[] = $this->parser->parseEntity(ParserDrive2::PAGE_BRANDS, '.c-block--content nav span a');
    }
}