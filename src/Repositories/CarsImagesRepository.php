<?php

namespace Repositories;


class CarsImagesRepository extends AbstractRepository
{
    protected $tableName = 'cars_images';

    public function getTableName() {
        return $this->tableName;
    }

//    public function saveImage($url){
//
//    }
}