<?php
require 'AbstractRepository.php';
require 'BrandRepository.php';
require 'ModelRepository.php';
require 'GenerationRepository.php';
require 'CarRepository.php';

class ParserCollector
{
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function collect($items){

        $repository = $this->pickRepository($items['page']);
        array_shift($items);

        $fullInfo = [];
        foreach ($items as $item){
            $fullInfo[] = $repository->createOrUpdate('url', $item);
            if(isset($item['image']))
                $this->saveImage($item['image']);
        }

        return $fullInfo;
    }

    public function saveImage($image){
        $array = explode('.', $image);
        $extension = end($array);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/images/'.time().rand(0,10000).$extension, file_get_contents($image));
    }

    private function pickRepository($repositoryName){

        switch ($repositoryName){
            case 'brands':
                return new BrandRepository($this->connection);
            case 'models':
                return new ModelRepository($this->connection);
            case 'generations':
                return new GenerationRepository($this->connection);
            case 'cars':
                return new CarRepository($this->connection);
        }

        return false;
    }
}