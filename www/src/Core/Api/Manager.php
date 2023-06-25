<?php

/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 09.12.17
 * Time: 18:57
 */

namespace TBCore\Api;

use TBCore\Worker\Manager as WorkerManager;
use TBCore\Database\DbManager as DatabaseManager;

class Manager
{
    /**
     * @var array
     */
    private $setter;

    /**
     * @var DatabaseManager
     */
    private $dbManager = null;

    public function __construct()
    {
        $this->dbManager = new DatabaseManager();
    }

    /**
     * @return WorkerManager
     */
    public function worker()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new WorkerManager($this);
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return DatabaseManager
     */
    public function database()
    {
        return $this->dbManager;
    }
}
