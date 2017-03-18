<?php
namespace Repositories;

class BrandRepository extends AbstractRepository
{
    protected $tableName = 'brands';

    public function getTableName() {
        return $this->tableName;
    }
}