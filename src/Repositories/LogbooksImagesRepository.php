<?php

namespace Repositories;


class LogbooksImagesRepository extends AbstractRepository
{
    protected $tableName = 'logbooks_images';

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
        $sql ="INSERT INTO logbook_images (logbook_id, logbook_image_id) VALUES $itemsForSet";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
    }
}