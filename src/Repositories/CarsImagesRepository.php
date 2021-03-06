<?php

namespace Repositories;


class CarsImagesRepository extends AbstractRepository
{
    protected $tableName = 'cars_images';

    public function getTableName()
    {
        return $this->tableName;
    }

    public function saveImages($carId, array $images)
    {
        $values = '';
        foreach ($images as $image) {
            $values .= "($carId, $image), ";
        }
        $itemsForSet = substr($values, 0, -2);
        $sql ="INSERT INTO car_image (car_id, cars_images_id) VALUES $itemsForSet";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
    }
}