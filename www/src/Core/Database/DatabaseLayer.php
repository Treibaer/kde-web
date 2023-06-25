<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 22.09.17-22:37
 */

namespace TBCore\Database;

use TBCore\Exception\MissingValueException;
use TBCore\Exception\NoPrimaryKeyException;
use TBCore\Model\Model;

/**
 * Class DatabaseLayer
 * @package database
 */
abstract class DatabaseLayer
{
    /**
     * @param Model $object
     * @throws MissingValueException
     * @throws NoPrimaryKeyException
     * @throws \TBCore\Exception\MissingAnnotationException
     * @throws \TBCore\Exception\MissingQueryType
     * @throws \TBCore\Exception\NotImplementedException
     */
    public function persist(Model $object)
    {
        $this->validate($object);
        $tableName = $object->getTableName();
        $query = new Query();
        $values = [];
        foreach (array_keys($object->getProperties()) as $property) {
            // hide pseudo-properties
            if ($object->isHidden($property)) {
                continue;
            }
            $value = $object->get($property);
            $values[$property] = $value;
            $query->set($property, $value);
        }
        $isUpdate = false;
        $wheres = [];
        foreach ($object->getPrimaryKeys() as $primaryKey) {
            $value = $values[$primaryKey];
            $equals = '=';
            if (is_null($value)) {
                $equals = 'IS';
                $value = NULL;
            }
            if (!empty($value)) {
                $isUpdate = true;
            }
            $wheres[] = " `$primaryKey` $equals '$value'";
        }
        if ($isUpdate) {
            $query
                ->update($tableName)
                ->where(join(' AND ', $wheres));
        } else {
            $query->insert($tableName);
        }
        $sql = $query->getSQL();
        try {
            $this->q($sql);
        } catch (\Exception $e) {
            var_dump(htmlspecialchars($sql));
            die;
        }
    }

    /**
     * @deprecated not completed now, be careful using it
     * idea is to give a model as an filter and return all similar objects of the db of this entity or an empty array
     * @param Model $object
     * @return Model[]
     * @throws MissingValueException
     * @throws NoPrimaryKeyException
     * @throws \TBCore\Exception\MissingAnnotationException
     * @throws \TBCore\Exception\MissingQueryType
     * @throws \TBCore\Exception\NotImplementedException
     */
    public function load(Model $object)
    {
        $this->validate($object);
        $tableName = $object->getTableName();
        $query = new Query();
        $values = [];
        foreach (array_keys($object->getProperties()) as $property) {
            $value = $object->get($property);
            $values[$property] = $value;
        }
        $query->select('*')->from($tableName);

        $wheres = [];
        foreach ($values as $key => $setter) {
            if (!empty($setter)) {
                $wheres[] = $key . ' = "' . $setter . '"';
            }
        }
        $query->where(join(' AND ', $wheres));

        $sql = $query->getSQL();
        return $this->fetchToModelA($this->q($sql), $object);
    }

    /**
     * @deprecated not completed now, be careful using it
     * @param \mysqli_result $q
     * @return Model[]
     */
    public function fetchToModelA($q, $object)
    {
        $r = [];
        while ($l = mysqli_fetch_object($q)) {
            $className = get_class($object);
            $r[] = new $className($l);
        }
        return $r;
    }

    /**
     * @param Model $object
     * @throws MissingValueException
     * @throws NoPrimaryKeyException
     */
    private function validate(Model $object)
    {
        if (!is_subclass_of($object, 'TBCore\Model\Model')) {
            throw new MissingValueException('object class ' . get_class($object) . ' must implement Models\Model');
        }
        //Property Annotations
        $primaryKeys = $object->getPrimaryKeys();
        if (0 == count($primaryKeys)) {
            throw new NoPrimaryKeyException();
        }
    }

    abstract public function q($sql);
}
