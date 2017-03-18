<?php
namespace Repositories;

class LogbookRepository extends AbstractRepository
{
    protected $tableName = 'logbooks';

    public function getTableName() {
        return $this->tableName;
    }
}