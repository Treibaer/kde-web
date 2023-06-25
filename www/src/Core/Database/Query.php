<?php


namespace TBCore\Database;

use TBCore\Exception\MissingQueryType;
use TBCore\Exception\MissingValueException;
use TBCore\Exception\NotImplementedException;

/**
 * Class Query
 * @package database
 */
class Query
{
    /**
     * @var QueryType
     */
    private $queryType = null;

    /**
     * @var string[]
     */
    private $selects = [];

    /**
     * @var string
     */
    private $table = '';

    /**
     * @var string[]
     */
    private $leftJoins = [];

    /**
     * @var string
     */
    private $where = '';

    /**
     * @var string
     */
    private $groupBy = '';

    /**
     * @var string
     */
    private $orderBy = '';

    /**
     * @var string
     */
    private $limit = '';

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var mixed[]
     */
    private $setters = [];

    public function select($select, $as = null)
    {
        if (is_array($select) && count($select) > 0 && !is_array($select[0])) {
            foreach ($select as $singleSelect) {
                $this->select($singleSelect);
            }
            return $this;
        }
        $this->queryType = QueryType::$SELECT;
        $this->selects[] = $select . (is_null($as) ? '' : " AS $as");
        return $this;
    }

    /**
     * @param string $table
     * @param string $as
     * @return $this
     */
    public function from($table, $as = null)
    {
        $this->table = $table . (is_null($as) ? '' : " AS $as");
        return $this;
    }

    public function leftJoin($table, $on, $as = null)
    {
        $this->leftJoins[] = 'LEFT JOIN ' . $table . (is_null($as) ? '' : " AS $as") . " ON $on ";
        return $this;
    }

    public function where($where)
    {
        $this->where = 'WHERE ' . $where;
        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->groupBy = 'GROUP BY ' . $groupBy;
        return $this;
    }

    public function orderBy($orderBy)
    {
        $this->orderBy = 'ORDER BY ' . $orderBy;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string
     * @throws MissingQueryType
     * @throws MissingValueException
     * @throws NotImplementedException
     */
    public function getSQL()
    {
        if (null == $this->queryType) {
            throw new MissingQueryType();
        }
        if (empty($this->table)) {
            throw new MissingValueException('no table defined');
        }
        $sql = '';
        switch ($this->queryType) {
            case QueryType::$SELECT:
                $sql = $this->getSelectQuery();
                break;
            case QueryType::$INSERT:
                $sql = $this->getInsertQuery();
                break;
            case QueryType::$UPDATE:
                $sql = $this->getUpdateQuery();
                break;
            case QueryType::$REMOVE:
                $sql = $this->getRemoveQuery();
                break;

        }
        return $sql;
    }

    /**
     * @param $table
     * @return $this
     */
    public function insert(string $table)
    {
        $this->queryType = QueryType::$INSERT;
        $this->from($table);
        return $this;
    }

    /**
     * @param table
     * @return $this
     */
    public function update($table)
    {
        $this->queryType = QueryType::$UPDATE;
        $this->from($table);
        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->setters[$key] = str_replace("'", '"', $value);
    }

    /**
     * @return string
     */
    private function getSelectQuery()
    {
        $sql = 'SELECT ' . join(', ', $this->selects) . ' FROM ' . $this->table . ' '
            . join('', $this->leftJoins) . ' ' . $this->where . ' ' . $this->groupBy . ' '
            . $this->orderBy;
        if (strlen($this->limit) > 0) {
            $sql .= ' LIMIT ' . $this->offset . ', ' . $this->limit;
        }
        return $sql . ';';
    }

    /**
     * @return string
     */
    private function getInsertQuery()
    {
        $sql = 'INSERT INTO ' . $this->table
            . ' (`' . join('`,`', array_keys($this->setters)) . '`)
            VALUES (\'' . join("', '", array_values($this->setters)) . '\');';
        return $sql;
    }

    /**
     * @return string
     */
    private function getUpdateQuery()
    {
        $set = [];
        foreach ($this->setters as $key => $value) {
            $set[] = "`$key` = '$value'";
        }
        $sql = 'UPDATE ' . $this->table . ' SET ' . join(', ', $set) . ' ' . $this->where . ';';
        return $sql;
    }

    /**
     * @return string
     */
    private function getRemoveQuery()
    {
        throw new NotImplementedException();
    }
}
