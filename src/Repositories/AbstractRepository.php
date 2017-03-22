<?php
namespace Repositories;

use PDO;
use PDOException;

abstract class AbstractRepository
{
    abstract public function getTableName();

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    protected function prepareParams($params){
        $fieldNames = [];
        $fieldValues = [];
        foreach ($params as $field => $value) {
            $fieldNames[] = $field;
            $fieldValues[] = $value;
        }
        $fieldNamesString = implode(',', $fieldNames);
        array_walk($fieldNames, function (&$item) {
            $item = ':' . $item;
        });
        $fieldValuesString = implode(',', $fieldNames);
        return [
          'fieldValues'=> $fieldValues,
           'fieldNames' => $fieldNames,
            'fieldNamesString' => $fieldNamesString,
            'fieldValuesString' => $fieldValuesString

        ];
    }

    /** create record
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        $tableName = $this->getTableName();
        $preparedParams = $this->prepareParams($params);
        $fieldNamesString = $preparedParams['fieldNamesString'];
        $fieldValuesString = $preparedParams['fieldValuesString'];
        $sql = "INSERT INTO $tableName ($fieldNamesString) VALUES ($fieldValuesString)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($preparedParams['fieldNames'] as $key => $bindValue) {
            $stmt->bindParam($bindValue, $preparedParams['fieldValues'][$key], PDO::PARAM_STR);
        }
        $stmt->execute();
        return $this->lastRecord();
    }

    /** update record
     * @param $findField
     * @param array $params
     * @return int
     * @internal param array $whereAttribute
     */
    function update($findField, array $params)
    {
        $tableName = $this->getTableName();
        $sql = "UPDATE $tableName SET id=LAST_INSERT_ID(id), ";
        $sql .= $this->updateSet(array_keys($params));
        $sql .= $this->updateWhere(array_keys($findField));
        $stmt = $this->pdo->prepare($sql);
        $preparedParams = $this->prepareParams($params);
        foreach ($preparedParams['fieldNames'] as $key => $bindValue) {
            $stmt->bindParam($bindValue, $preparedParams['fieldValues'][$key], PDO::PARAM_STR);
        }
        $stmt->execute();

        return $this->lastRecord();
    }

    private function updateWhere($whereItems)
    {
        $where = ' WHERE ';
        foreach ($whereItems as $whereItem)
        {
            $where .= (count($whereItems) > 1) ? " AND WHERE $whereItem = :$whereItem" :  "$whereItem = :$whereItem";
        }
        return $where;
    }

    private function updateSet($whereItems)
    {
        $itemsForSetString = '';
        foreach ($whereItems as $whereItem){
            $itemsForSetString .= "$whereItem = :$whereItem, ";
        }
        $itemsForSet = substr($itemsForSetString, 0, -2);
        return $itemsForSet;
    }

    /** create or update record
     * @param $attributeForFind
     * @param $params
     * @return int
     */
    function createOrUpdate($attributeForFind, $params)
    {
        if ($this->findCount($attributeForFind, $params[$attributeForFind])) {
            return $this->update([$attributeForFind=>$params[$attributeForFind]], $params);
        }
        return $this->create($params);
    }

    /** find item by param
     * @param $field
     * @param $value
     * @return mixed
     */
    protected function findCount($field, $value)
    {
        $tableName = $this->getTableName();
        $result = $this->pdo->query("select count(*) from $tableName WHERE $field = '$value'")->fetchColumn();
        return $result;
    }

    /**
     *  return last record
     * @return mixed
     */
    protected function lastRecord(){
        $tableName = $this->getTableName();
        $lastInsertId = $this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare("SELECT * FROM $tableName WHERE id=$lastInsertId");
        $stmt->execute([$lastInsertId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * get all records
     * @return mixed
     */
    public function all()
    {
        $tableName = $this->getTableName();
        $stmt = $this->pdo->prepare("SELECT * FROM $tableName");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /** find item by attribute
     * @param $attribute
     * @param $value
     * @return mixed
     */
    public function findByAttribute($attribute, $value)
    {
        $tableName = $this->getTableName();
        $sql= "SELECT * FROM $tableName WHERE $attribute = :$attribute";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":$attribute", $value, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}