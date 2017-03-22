<?php
namespace Parsers;

class ParserDrive2
{
    const PAGE_LOGBOOKS = 'logbooks';
    const PAGE_LOGBOOK = 'logbook';
    const PAGE_CAR = 'car';
    const PAGE_CARS = 'cars';
    const PAGE_BRANDS = 'brand';
    const PAGE_MODELS = 'model';
    const PAGE_GENERATIONS = 'generation';

    private $siteUrl = 'https://www.drive2.ru';

    /**
     * parse page by parameters
     *
     * @param $pageName
     * @param $selector
     * @param null $gettingParams
     * @return array
     */
    public function parseEntity($pageName, $selector, $gettingParams = null){
        $result = [];
        $logbookUrl = ($pageName == self::PAGE_LOGBOOKS) ? $gettingParams['url'] . 'logbook' : $gettingParams['url'];
        $fullUrl = ($gettingParams) ? $this->siteUrl. $logbookUrl: $this->siteUrl . '/cars/?all';
        if ($page = file_get_html($fullUrl)) {
            $pageContent = $page->find($selector);

            foreach ($pageContent as $key => $item) {
                $result[] = $this->prepareFields($pageName, $item, $gettingParams, $page);
            }
        }
        return $result;
    }

    /** prepare fields
     * @param $pageName
     * @param $item
     * @param $gettingParam
     * @param $page
     * @return array
     */
    private function prepareFields($pageName, $item, $gettingParam, $page)
    {
        $fieldsArray = [];
        switch ($pageName) {
            case self::PAGE_BRANDS:
                $fieldsArray['url'] = $item->attr['href'];
                $fieldsArray['name'] = $item->nodes[0]->_[4];
                break;
            case self::PAGE_MODELS:
                $fieldsArray['brand_id'] = $gettingParam['id'];
                $fieldsArray['url'] = $item->attr['href'];
                $fieldsArray['name'] = $item->nodes[0]->_[4];
                break;
            case self::PAGE_CAR:
                $carsModelPage = $page;
                $imagesDOM = $carsModelPage->find('.js-pichd');
                foreach ($imagesDOM as $image){
                    $fieldsArray['images'][] = $image->attr['src'];
                }
                $carsModelInfo = $carsModelPage->find('.l-main');
                $headerInfo = $carsModelInfo[0]->find('.c-header-main a');
                $allCarUrls = [];
                foreach ($headerInfo as $item){
                    $allCarUrls[] = $item->attr['href'];
                }
                $lastUrl = end($allCarUrls);
                $isGeneration = strpos($lastUrl, 'g');
                $fieldsArray['model_url'] = (!$isGeneration) ? $lastUrl : $allCarUrls[count($allCarUrls) - 2];
                $fieldsArray['generation_url'] = ($isGeneration) ? $lastUrl : null;
                $fieldsArray['url'] = $gettingParam['url'];
                $fieldsArray['name'] = $carsModelPage->find('.c-car-info__caption')[0]->nodes[0]->_[4];
                break;
            case self::PAGE_CARS:
                $fieldsArray['model_id'] = $gettingParam['id'];
                $fieldsArray['image'] = $item->find('img')[0]->src;
                $fieldsArray['url'] = $item->find('a')[0]->href;
                $fieldsArray['name'] = $item->find('.c-car-card__caption a')[0]->nodes[0]->_[4];
                break;
            case self::PAGE_GENERATIONS:
                $fieldsArray['model_id'] = $gettingParam['id'];
                $fieldsArray['url'] = $item->attr['href'];
                $fieldsArray['name'] = $item->nodes[0]->_[4];
                break;
            case self::PAGE_LOGBOOK:
                $textSelector = $item->find('p text');
                $logbookText = '';
                foreach ($textSelector as $text){
                    $logbookText .= $text->_[4];
                }
                $logbookImages = [];
                $images = $item->find('.c-post__pic img');
                foreach ($images as $image){
                    $logbookImages[0] = $image->src;
                }
                $logbookHeader = $page->find('.c-header-main span');
                $logbookTitle = end($logbookHeader);
                $fieldsArray['title'] = $logbookTitle->nodes[0]->_[4];
                $fieldsArray['car_url'] = $page->find('.c-header-main .c-link')[0]->attr['href'];
                $fieldsArray['images'] = $logbookImages;
                $fieldsArray['text'] = $logbookText;
                $fieldsArray['url'] = $gettingParam['url'];
                break;
            case self::PAGE_LOGBOOKS:
                $fieldsArray['url'] = $item->attr['href'];

        }
        return $fieldsArray;
    }
}