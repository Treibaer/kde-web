<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 29.11.16
 * Time: 18:18
 */

namespace TBCore\Database;

use TBCore\Worker\Config;
use TBCore\Worker\General\Common;
use TBCore\Worker\Logging;
use TBCore\Worker\LoggingType;
use TBCore\Exception\Exception;
use TBCore\Exception\UserNotDefinedException;
use TBCore\Model\Model;
use TBCore\Model\User;

/**
 * Class Database
 * @package database
 */
class Database extends DatabaseLayer
{
    /**
     * @var string
     */
    private $databaseName = null;

    static $model = null;

    /**
     * @var \mysqli
     */
    protected $connection = null;

    /**
     * @var DbManager
     */
    protected $coreDbManager = null;

    /**
     * @var User
     */
    protected $user = null;

    /**
     * @var Common
     */
    protected $common = null;

    /**
     * @var string
     */
    private $database;

    /**
     * Database constructor.
     * @param DbManager $coreDbManager
     * @throws Exception
     * @throws \TBCore\Exception\MissingAnnotationException
     */
    public function __construct(DbManager $coreDbManager)
    {
        if ($this->connection == null) {
            $this->connection = $coreDbManager->getConnection();
        }
        $this->coreDbManager = $coreDbManager;
        $this->user = $coreDbManager->getUser();
        $this->common = new Common();
        $this->database = Config::get('database')->database;
        if (null === static::$model) {
            throw new Exception('no model assigned: ' . get_class($this));
        }
        /** @var Model $instance */
        $instance = new static::$model;
        $this->databaseName = $instance->getTableName();
    }

    /**
     * @return int
     * @throws UserNotDefinedException
     */
    public function getUserId()
    {
        if (null == $this->coreDbManager->getUserId()) {
            throw new UserNotDefinedException('User not defined');
        }
        return $this->coreDbManager->getUserId();
    }

    /**
     * @param string|array $selects
     * @param Filter[]|null $filters
     * @param array|null $orders
     * @param null $limit
     * @param null $group
     * @return string
     * @throws \TBCore\Exception\MissingAnnotationException
     */
    public function getSQL($selects = '*', array $filters = null, array $orders = null, $limit = null, $group = null)
    {
        $where = '';
        if (!empty($filters)) {
            for ($i = 0; $i < count($filters); $i++) {
                $filter = $filters[$i];
                // dirty fallback
                if ($filters[0] instanceof Filter) {
                    $filter = [$filter->getKey(), $filter->getComparator(), $filter->getValue()];
                }
                if ($i == 0) {
                    $where = 'WHERE ';
                } else {
                    $where .= ' AND ';
                }
                $where .= $filter[0] . ' ' . $filter[1] . ' ';
                if (strtoupper($filter[1]) === 'IN') {
                    $where .= $filter[2];
                } else {
                    $where .= '"' . $filter[2] . '"';
                }
            }
        }
        $order = '';
        if ($orders != null) {
            for ($i = 0; $i < count($orders); $i++) {
                $o = $orders[$i];
                if ($i == 0) {
                    $order = 'ORDER BY ';
                } else {
                    $order .= ', ';
                }
                $order .= $o[0] . ' ' . $o[1];
            }
        }
        $select = '*';
        if (is_array($selects)) {
            for ($i = 0; $i < count($selects); $i++) {
                $s = $selects[$i];
                if ($i == 0) {
                    $select = '';
                } else {
                    $select .= ', ';
                }
                $select .= $s;
            }
        } else {
            if ($selects !== null) {
                $select = $selects;
            }
        }
        /** @var Model $instance */
        $instance = new static::$model;
        $sql = "SELECT $select FROM " . $instance->getTableName() . " $where";
        if ($group != null) {
            $sql .= " GROUP BY $group";
        }
        $sql .= ' ' . $order;
        if ($limit != null) {
            $sql .= " LIMIT $limit";
        }
        return $sql;
    }

    /**
     * @param string|array $selects
     * @param null|array $filters
     * @param null|array $orders
     * @param null|int $limit
     * @return array
     */
    public function get($selects = '*', $filters = null, $orders = null, $limit = null, $group = null)
    {
        return $this->fetchToModel($this->q($this->getSQL($selects, $filters, $orders, $limit, $group)));
    }

    /**
     * @param $table
     * @param $selects
     * @param $filters
     * @param $orders
     * @param $limit
     * @return array
     */
    public function getAsArray($table, $selects, $filters, $orders, $limit)
    {
        $sql = $this->getSQL($selects, $filters, $orders, $limit);
        $from = explode('FROM', $sql);
        $afterFrom = explode(' ', $from[1]);
        $afterFrom[1] = $table;
        $sql = $from[0] . 'FROM ' . join(' ', $afterFrom);
        return $this->fetchToArray($this->q($sql));
    }

    /**
     * @param $name
     * @param $password
     * @return null|string
     */
    public function tryToLogin($name, $password)
    {
        $stmt = mysqli_query($this->connection, "SELECT * FROM User WHERE user='$name' and password='$password'");
        if (mysqli_num_rows($stmt) == 0) {
            return null;
        } else {
            $u = mysqli_fetch_object($stmt);
            if ($u->sessionId != "") {
                return $u->sessionId;
            }
            $sessionId = md5($name . "" . (time() - 1328907));
            mysqli_query($this->connection, "UPDATE User SET sessionId = '$sessionId' where user = '$name'");
            return $sessionId;
        }
    }

    /**
     * @param $sessionId
     * @return null|string
     */
    public function getModuleUrlForSessionId($sessionId)
    {
        $stmt = mysqli_query($this->connection, "SELECT mainModule FROM User WHERE sessionId = '$sessionId'");
        if (mysqli_num_rows($stmt) == 0) {
            return null;
        } else {
            $u = mysqli_fetch_object($stmt);
            $mainModule = $u->mainModule;

            $stmt = mysqli_query($this->connection, "SELECT url FROM Module WHERE moduleId = '$mainModule'");
            if (mysqli_num_rows($stmt) == 0) {
                return null;
            }
            $u = mysqli_fetch_object($stmt);
            return $u->url;
        }
    }

    public function close()
    {
        mysqli_close($this->connection);
        $this->connection = null;
    }

    ### crons start
    public function cronGetKonten()
    {
        $q = 'SELECT Account.* FROM Account WHERE active = 1';

        $query = mysqli_query($this->connection, $q);
        $account = [];
        while ($line = mysqli_fetch_object($query)) {
            $q = mysqli_query($this->connection,
                'SELECT sum(AccountEntry.value) AS plannedSum FROM AccountEntry WHERE planned = 1 AND accountId=' . $line->accountId . ' AND time < ' . (time() + 60 * 60 * 24 * 30));
            while ($line2 = mysqli_fetch_object($q)) {
                $line->plannedSum = $line->value + $line2->plannedSum;
            }
            $q = mysqli_query($this->connection,
                'SELECT sum(AccountEntry.value) AS available FROM AccountEntry WHERE planned = 1 AND value < 0 AND accountId=' . $line->accountId . ' AND time < ' . (time() + 60 * 60 * 24 * 30));
            while ($line2 = mysqli_fetch_object($q)) {
                $line->available = $line->value + $line2->available;
            }
            $account[] = $line;
        }
        return $account;
    }

    ### crons end
    public function getAusgabenPie()
    {
        $groupBy = 'AccountEntry.accountTagId';
        $selectedYear = $this->user->getSelectedYear();
        $from = $this->common->getFromTimestamp($selectedYear);
        $to = $this->common->getToTimestamp($selectedYear);

        $filter = 'AccountEntry.time <= ' . $to . ' AND AccountEntry.time >= ' . $from . ' AND ';

        $sql = "select sum(AccountEntry.value) as sum, AccountEntry.accountTagId, tag.value as TagName, cat.value as CategoryName, cat.accountCategoryId as Category,  AccountEntry.category as SubCategory, AccountCategory.value as SubCategoryName from AccountEntry 
        left join AccountCategory on AccountCategory.accountCategoryId = AccountEntry.category 
        left join AccountCategory as cat on AccountCategory.relationId = cat.accountCategoryId 
        left join AccountTag as tag on tag.accountTagId = AccountEntry.accountTagId
        where $filter AccountEntry.planned = 0 and AccountEntry.userId = " . $this->user->getUserId() . " and cat.accountCategoryId != 32 and AccountEntry.value < 0 group by $groupBy order by sum";

        $q = $this->q($sql);
        return $this->fetchToArray($q);
    }

    /**
     * @param $filter
     * @return array
     */
    public function getTagData($filter)
    {
        $year = $this->user->getSelectedYear();
        $yearFilter = '';
        if ($year > 0) {
            $yearFilter = 'AccountEntry.time >= ' . $this->common->getFromTimestamp($year) . ' AND ';
            $yearFilter .= 'AccountEntry.time <= ' . $this->common->getToTimestamp($year) . ' AND ';
        }
        $filterString = $this->filterToString($filter);
        $sql = "SELECT SUM(AccountEntry.value) AS sum, tag.value AS Tag, AccountEntry.accountTagId AS tagId FROM AccountEntry
          LEFT JOIN AccountCategory on AccountCategory.accountCategoryId = AccountEntry.category 
          LEFT JOIN AccountCategory AS cat on AccountCategory.relationId = cat.accountCategoryId 
          LEFT JOIN AccountTag AS tag ON AccountEntry.accountTagId = tag.accountTagId
                  where $yearFilter AccountEntry.planned = 0 and AccountEntry.userId = " . $this->user->getUserId() . " and cat.accountCategoryId != 32 and AccountEntry.value < 0 
                  $filterString
                  GROUP BY tag.accountTagId ORDER BY tag asc";

        return $this->fetchToArray($this->q($sql));
    }

    public function getPlaceData($filter)
    {
        $year = $this->user->getSelectedYear();
        $onlyYear = '';
        if ($year > 0) {
            $onlyYear = 'AccountEntry.time >= ' . $this->common->getFromTimestamp($year) . ' AND ';
            $onlyYear .= 'AccountEntry.time <= ' . $this->common->getToTimestamp($year) . ' AND ';
        }
        $filterString = $this->filterToString($filter);

        $query = new Query();
        $query
            ->select('SUM(AccountEntry.value)', 'sum')
            ->select('place.value', 'Place')
            ->select('AccountEntry.accountPlaceId', 'placeId')
            ->from('AccountEntry')
            ->leftJoin('AccountCategory', 'AccountCategory.accountCategoryId = AccountEntry.category')
            ->leftJoin('AccountCategory', 'AccountCategory.relationId = cat.accountCategoryId', 'cat')
            ->leftJoin('AccountPlace', 'AccountEntry.accountPlaceId = place.accountPlaceId', 'place')
            ->where("$onlyYear AccountEntry.planned = 0 and AccountEntry.userId = " . $this->user->getUserId() . " and cat.accountCategoryId != 32 and AccountEntry.value < 0 
                  $filterString")
            ->groupBy('place.accountPlaceId')
            ->orderBy('place ASC');

        $sql = "SELECT sum(AccountEntry.value) as sum, place.value as Place, AccountEntry.accountPlaceId as placeId FROM AccountEntry
        LEFT JOIN AccountCategory on AccountCategory.accountCategoryId = AccountEntry.category 
        LEFT JOIN AccountCategory as cat on AccountCategory.relationId = cat.accountCategoryId 
        left join AccountPlace as place on AccountEntry.accountPlaceId = place.accountPlaceId
                  where AccountEntry.planned = 0 and $onlyYear AccountEntry.userId = " . $this->user->getUserId() . " and cat.accountCategoryId != 32 and AccountEntry.value < 0 
                  $filterString
                  group by place.accountPlaceId order by place asc";
        $sql = $query->getSQL();
        return $this->fetchToArray($this->q($sql));
    }

    /**
     * @param \mysqli_result $q
     * @return array
     */
    public function fetchToArray($q)
    {
        $r = [];
        if ($q == false) {
            $l = new Logging();
            $l->log(LoggingType::FATAL, 'fetchToArray() failed');
        }
        while ($l = mysqli_fetch_object($q)) {
            $r[] = $l;
        }
        return $r;
    }

    /**
     * @param \mysqli_result $q
     * @param bool $jsonable
     * @return Model[]
     */
    public function fetchToModel(\mysqli_result $q, $jsonable = false)
    {
        $r = [];
        if ($q == false) {
            $l = new Logging();
            $l->log(LoggingType::FATAL, 'fetchToModel() failed');
        }
        if ($this->databaseName === null) {
            $l = new Logging();
            $l->log(LoggingType::FATAL, '$databaseName not defined for class ' . get_class($this));
        }
        if (static::$model === null) {
            $l = new Logging();
            $l->log(LoggingType::FATAL, '$model not defined for class ' . get_class($this));
        }

        while ($l = mysqli_fetch_object($q)) {
            $className = static::$model;
            /** @var Model $item */
            $item = new $className($l);
            $r[] = $jsonable ? $item->toJSONableObject() : $item;
        }
        return $r;
    }

    /**
     * @param string $sql
     * @return bool|\mysqli_result
     * @throws \RuntimeException
     */
    public function q($sql)
    {
        $out = mysqli_query($this->connection, $sql);
        $error = mysqli_error($this->connection);
        if (strlen($error) > 0) {
            (new Logging())->log(LoggingType::FATAL, $error);
            throw new \RuntimeException($error);
        } else {
            //(new Logging())->log(LoggingType::QUERY, $sql);
        }
        return $out;
    }

    public function getTables($table = null, $database = null)
    {
        $table = $table == null ? '' : ' IN ' . $table;
        $tableList = array();
        $query = "SHOW TABLES" . $table;
        if ($database !== null) {
            $query .= " from $database";
        }
        $res = mysqli_query($this->connection, $query);
        while ($cRow = mysqli_fetch_array($res)) {
            $tableList[] = $cRow[0];
        }
        return $tableList;
    }

    function getFieldsOfDbTable($tableName)
    {
        $q = mysqli_query($this->connection, 'DESCRIBE ' . $tableName);
        $fields = [];
        while ($row = mysqli_fetch_array($q)) {
            $fields[] = $row['Field'];
        }
        return $fields;
    }

    /**
     * @param Model $object
     * @param $key
     * @param $value
     * @throws \TBCore\Exception\MissingAnnotationException
     */
    public function update(Model $object, $key, $value)
    {
        $className = get_class($object);
        $classProperties = $this->getPropertiesOfAClass($className);
        $sql = "UPDATE " . $object->getTableName() . " ";
        for ($i = 0; $i < count($classProperties); $i++) {
            $property = $classProperties[$i];
            $getProperty = "get" . ucfirst($property);
            if ($i == 0) {
                $sql .= "SET ";
            } else {
                $sql .= ", ";
            }
            $sql .= $property . " = '" . str_replace("\\", "\\\\",
                    str_replace("'", "\"", $object->$getProperty())) . "'";
        }
        $sql .= " WHERE `$key` = '$value'";

        mysqli_query($this->connection, $sql) or print mysqli_error($this->connection);
    }

    /**
     * @param Model $object
     * @return int|string
     * @throws \TBCore\Exception\MissingAnnotationException
     */
    public function insert(Model $object)
    {
        $className = get_class($object);
        $classProperties = $this->getPropertiesOfAClass($className);
        $sql = "INSERT INTO " . $object->getTableName() . " ";
        for ($i = 0; $i < count($classProperties); $i++) {
            $property = $classProperties[$i];
            $getProperty = "get" . ucfirst($property);
            if ($i == 0) {
                $sql .= "SET ";
            } else {
                $sql .= ", ";
            }
            $sql .= $property . " = '" . str_replace("\\", "\\\\",
                    str_replace("'", "\"", $object->$getProperty())) . "'";
        }
        mysqli_query($this->connection, $sql) or print mysqli_error($this->connection);
        return mysqli_insert_id($this->connection);
    }

    /**
     * @param string $className
     * @return array
     */
    private function getPropertiesOfAClass($className)
    {
        /** @var Model $v */
        $v = new $className();
        return array_keys($v->getProperties());
    }

    /**
     * @param [] $filter
     * @return string
     */
    private function filterToString($filter)
    {
        $filterString = '';
        for ($i = 0; $i < count($filter); $i++) {
            $f = $filter[$i];
            $filterString .= 'AND ' . $f[0] . ' ' . $f[1] . ' ' . $f[2] . ' ';
        }
        return $filterString;
    }

    /**
     * @return string[]
     */
    public function getDatabases()
    {
        $result = $this->q('SHOW DATABASES');
        $databases = [];
        while ($line = mysqli_fetch_object($result)) {
            $databases[] = $line->Database;
        }
        return $databases;
    }
}
