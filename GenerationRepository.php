<?php

class GenerationRepository extends AbstractRepository
{
    protected $tableName = 'generations';

    public function getTableName() {
        return $this->tableName;
    }

}