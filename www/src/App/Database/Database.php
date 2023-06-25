<?php

namespace KDE\Database;

use TBCore\Database\DbManager as CoreDbManager;

class Database extends \TBCore\Database\Database
{
    /**
     * @var DbManager
     */
    protected $dbManager;

    public function __construct(CoreDbManager $coreDbManager, DbManager $dbManager)
    {
        parent::__construct($coreDbManager);
        $this->dbManager = $dbManager;
    }

    function getFieldsOfDbTableRaw($tableName)
    {
        $q = mysqli_query($this->connection, 'DESCRIBE ' . $tableName);
        $fields = [];
        while ($row = mysqli_fetch_array($q)) {
            $fields[] = $row;
        }
        return $fields;
    }
}
