<?php

namespace Helpers\Queues\Tasks;

use Parsers\ParserDrive2;

class LogbookTasks extends AbstractTask
{

    function prepareContent()
    {
        $cars = $this->container->get('carRepository')->all();
        $logbookList = [];
        foreach ($cars as $car){
            $logbookList[] = $this->parser->parseEntity(ParserDrive2::PAGE_LOGBOOKS, '.l-main-column .c-block-card__body .c-post-preview__title a', $car);
        }
        foreach ($logbookList as $logbook){
            $logbookData = [];
            foreach ($logbook as $logbookPage){
                $logbookInfo = $this->parser->parseEntity(ParserDrive2::PAGE_LOGBOOK, '.g-full-size-post .c-block--content .c-post__body', $logbookPage);
                if(empty($logbookInfo))
                    continue;

                $logbookData[] = $this->prepareLogbook($logbookInfo[0]);
            }
            $this->content[] = $logbookData;
        }
    }

    private function prepareLogbook($logbookInfoFromPage)
    {
        $logbookData = [];
        $findCar = $this->container->get('carRepository')->findByAttribute('url', $logbookInfoFromPage['car_url']);
        $logbookData['car_id'] = $findCar['id'];
        $logbookData['title'] = $logbookInfoFromPage['title'];
        $logbookData['text'] = $logbookInfoFromPage['text'];
        $logbookData['images'] = $logbookInfoFromPage['images'];
        $logbookData['url'] = $logbookInfoFromPage['url'];
        return $logbookData;
    }


}