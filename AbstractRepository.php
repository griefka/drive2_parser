<?php

abstract class AbstractRepository
{
    abstract public function getTableName();

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    function create(array $params)
    {
        //todo: ??? {хз как по другому вставить
        $tableName = $this->getTableName();
//
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
        $sql = "INSERT INTO $tableName ($fieldNamesString) VALUES ($fieldValuesString)";
        $stmt = $this->pdo->prepare($sql);
        foreach ($fieldNames as $key => $bindValue) {
            $stmt->bindParam($bindValue, $fieldValues[$key], PDO::PARAM_STR);
        }
        $stmt->execute();
        $lastInsertId = $this->pdo->lastInsertId();
        $stmt = $this->pdo->prepare("SELECT * FROM $tableName WHERE id=$lastInsertId");
        $stmt->execute([$lastInsertId]);
        return $stmt->fetchObject();
    }

    function update(array $whereAttributes, array $params)
    {
        //todo: сделать Update
        $tableName = $this->getTableName();
        $sql = "UPDATE $tableName SET ";
        $sql .= "WHERE ";
        foreach ($whereAttributes as $whereAttribute => $whereValue){
            $sql .= "$whereAttribute = :$whereAttribute";
        }
        return 1;
    }

    function createOrUpdate($attributeForFind, $params)
    {
//        if ($this->findCount($attributeForFind, $params[$attributeForFind])) {
            $a = 1;
//
//            return $this->update([$attributeForFind=>$params[$attributeForFind]], $params);
//        }
        return $this->create($params);
    }

    protected function findCount($field, $value)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT count(*) FROM $tableName WHERE $field = :$field";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":$field", $value);
        try {
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $stmt->rowCount();
    }

}