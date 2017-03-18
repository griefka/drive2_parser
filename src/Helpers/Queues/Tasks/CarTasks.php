<?php

namespace Helpers\Queues\Tasks;

use Helpers\DBConnection;
use Repositories\BrandRepository;
use Repositories\ModelRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CarTasks extends AbstractTask
{

    function prepareContent()
    {
        $allModels = $this->container->get('modelRepository')->all();
        $cars = [];
        foreach ($allModels as $model) {
            $cars[] = $this->parser->parseEntity('cars', '.c-block--collapse-top .c-car-card--big', $model);
        }

        foreach ($cars as $carPage) {
            $carData = [];
            foreach ($carPage as $key => $car) {
                $carInfo = $this->parser->parseEntity('car', '.l-main', $car);
                if (empty($carInfo))
                    continue;
                $carData[] = $this->prepareCar($carInfo);
            }
            $this->content[] = $carData;
        }

    }

    /** prepare car info for saving
     * @param $carInfoFromPage
     * @return array
     */
    private function prepareCar($carInfoFromPage)
    {
        $carData = [];
        $findModel = $this->container->get('modelRepository')->findByAttribute('url', $carInfoFromPage['model_url']);
        $findGeneration = $this->container->get('generationRepository')->findByAttribute('url', $carInfoFromPage['generation_url']);
        $carData['url'] = $carInfoFromPage['url'];
        $carData['generation_id'] = ($findGeneration) ? $findGeneration['id'] : null;
        $carData['model_id'] = $findModel['id'];
        $carData['images'] = $carInfoFromPage['images'];
        $carData['name'] = $carInfoFromPage['name'];
        return $carData;
    }


}