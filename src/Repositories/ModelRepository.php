<?php
namespace Repositories;

class ModelRepository extends AbstractRepository
{
    protected $tableName = 'models';

    public function getTableName() {
        return $this->tableName;
    }
}