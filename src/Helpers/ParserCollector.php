<?php
namespace Helpers;



class ParserCollector
{
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function collect($items, $findField){

        $repository = $this->pickRepository($items['page']);
        array_shift($items);

        $fullInfo = [];
        foreach ($items as $item){
            $fullInfo[] = $repository->createOrUpdate($findField, $item);
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

    /** get needed repository
     * @param $repositoryName
     * @return mixed
     */
    private function pickRepository($repositoryName){

        $repositoryClassName = 'Repositories\\'.ucfirst($repositoryName).'Repository';
        return new $repositoryClassName($this->connection);
    }
}

