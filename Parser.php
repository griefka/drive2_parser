<?php
require 'Parser/simple_html_dom.php';
require 'Parseable.php';


class Parser implements Parseable
{
    private $parseCollector;

    /**
     * Parser constructor.
     * @param $parseCollector
     */
    public function __construct($parseCollector)
    {
        $this->parseCollector = $parseCollector;
    }

    public function parse()
    {
        $brands = $this->parseBrands();
        $models = $this->parseSkeleton('models', '.c-block--content nav span a', $brands);

//        $models = $this->parseModels($brands);
//        $cars = $this->parseCars($models);
//        $logBooks = $this->parseLogbooks($cars);
        return true;
    }

    function parseSkeleton($pageName, $selector, array $gettingParams = null)
    {
        $params['page'] = $pageName;
        foreach ($gettingParams as $gettingParam) {

            if ($page = file_get_html('https://www.drive2.ru' . $gettingParam->url)) {
                $pageContent = $page->find($selector);
                if(empty($pageContent))
                    continue;

                foreach ($pageContent as $key => $item) {
                    $params[$key] = $this->fields($pageName, $item, $gettingParam);
                }
            }
        }
        return $this->parseCollector->collect($params);
    }

    private function fields($pageName, $item, $gettingParam){

        $fieldsArray = [];
        switch ($pageName){
            case 'models':
                $fieldsArray['brand_id'] = $gettingParam->id;
                $fieldsArray['url'] = $item->attr['href'];
                $fieldsArray['name'] = $item->nodes[0]->_[4];
                break;
            case 'cars':
                $fieldsArray['model_id'] = $gettingParam->id;
                $fieldsArray['image'] = $item->find('img')[0]->src;
                $fieldsArray['url'] = $item->find('a')[0]->href;
                $fieldsArray['name'] = $item->find('.c-car-card__caption a')[0]->nodes[0]->_[4];
                break;

        }
        return $fieldsArray;
    }


    function parseBrands()
    {
        $brandsParams['page'] = 'brands';
        if ($brands = file_get_html('https://www.drive2.ru/cars/?all')) {
            $brandsHref = $brands->find('.c-block--content nav span a');
            foreach ($brandsHref as $key => $children) {
                $brandsParams[$key]['url'] = $children->attr['href'];
                $brandsParams[$key]['name'] = $children->nodes[0]->_[4];
            }
        }
        return $this->parseCollector->collect($brandsParams);
    }

    function parseModels(array $brandParams)
    {

        $modelsParams['page'] = 'models';
        foreach ($brandParams as $brandParam) {
            if ($modelPage = file_get_html('https://www.drive2.ru' . $brandParam->url)) {
                $modelsInfo = $modelPage->find('.c-block--content nav span a');
                if (empty($modelsInfo))
                    continue;
                foreach ($modelsInfo as $key => $children) {
                    $modelsParams[$key]['brand_id'] = $brandParam->id;
                    $modelsParams[$key]['url'] = $children->attr['href'];
                    $modelsParams[$key]['name'] = $children->nodes[0]->_[4];
                }
            }
        }
        return $this->parseCollector->collect($modelsParams);
    }

    function parseGenerations(array $modelParams)
    {
        $generationParams['page'] = 'generations';
        foreach ($modelParams as $modelParam) {
            if ($generationPage = file_get_html('https://www.drive2.ru' . $modelParam->url)) {
                $generationInfo = $generationPage->find('.c-block .c-gen-card__caption a');
                if (empty($generationInfo)) {
                    continue;
                }
                foreach ($generationInfo as $key => $children) {
                    $generationParams[$key]['model_id'] = $modelParam->id;
                    $generationParams[$key]['url'] = $children->attr['href'];
                    $generationParams[$key]['name'] = $children->nodes[0]->_[4];
                }
            }
        }
        return $this->parseCollector->collect($generationParams);
    }

    function parseCars(array $modelParams)
    {
        $carParams['page'] = 'cars';
        foreach ($modelParams as $modelParam) {
            if ($carsModelPage = file_get_html('https://www.drive2.ru' . $modelParam->url)) {
                $carsModelInfo = $carsModelPage->find('.c-block--collapse-top .c-car-card--big');
                if (empty($carsModelInfo)) {
                    continue;
                }
                foreach ($carsModelInfo as $key => $children) {
                    $a = 1;
                    $carsModelInfo[$key]['model_id'] = $modelParam->id;
                    $carsModelInfo[$key]['image'] = $children->find('img')[0]->src;
                    $carsModelInfo[$key]['url'] = $children->find('a')[0]->href;
                    $carsModelInfo[$key]['name'] = $children->find('.c-car-card__caption a')[0]->nodes[0]->_[4];
                }
            }
        }
        return $this->parseCollector->collect($carParams);
    }

    function parseLogbooks($carParams)
    {

    }


}