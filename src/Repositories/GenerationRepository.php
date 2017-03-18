<?php
namespace Repositories;

class GenerationRepository extends AbstractRepository
{
    protected $tableName = 'generations';

    public function getTableName() {
        return $this->tableName;
    }
}