<?php

namespace KDE\Database;

use TBCore\Database\DbManager as CoreDbManager;

class Database extends \TBCore\Database\Database
{
    public function __construct(CoreDbManager $coreDbManager, protected DbManager $dbManager)
    {
        parent::__construct($coreDbManager);
    }

    function getFieldsOfDbTableRaw($tableName): array
    {
        $q = mysqli_query($this->connection, 'DESCRIBE ' . $tableName);
        $fields = [];
        while ($row = mysqli_fetch_array($q)) {
            $fields[] = $row;
        }
        return $fields;
    }
}
