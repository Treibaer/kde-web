<?php

namespace KDE;

use TBCore\Api\Manager as CoreManager;
use KDE\Database\DbManager as DatabaseManager;
use KDE\Worker\Manager as WorkerManager;

class Manager
{
    private array $setter;

    public function __construct()
    {
        $this->dbManager = new DatabaseManager($this->core()->database());
    }

    /**
     * @return CoreManager
     */
    public function core(): CoreManager
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new CoreManager();
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return WorkerManager
     */
    public function worker(): WorkerManager
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new WorkerManager($this);
        }
        return $this->setter[__FUNCTION__];
    }

    /**
     * @return DatabaseManager
     */
    public function database(): DatabaseManager
    {
        return $this->dbManager;
    }
}
