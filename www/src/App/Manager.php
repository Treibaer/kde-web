<?php

namespace KDE;

use TBCore\Api\Manager as CoreManager;
use KDE\Database\DbManager as DatabaseManager;
use KDE\Worker\Manager as WorkerManager;

class Manager
{
    /**
     * @var array
     */
    private $setter;

    public function __construct()
    {
        $this->dbManager = new DatabaseManager($this->core()->database());
    }

    /**
     * @return CoreManager
     */
    public function core()
    {
        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new CoreManager();
        }
        return $this->setter[__FUNCTION__];
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

    private function get($class) {

        if (!isset($this->setter[__FUNCTION__])) {
            $this->setter[__FUNCTION__] = new $class($this);
        }
        return $this->setter[__FUNCTION__];
    }
}
