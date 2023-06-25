<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 08.01.17
 * Time: 00:47
 */

namespace TBCore\Database;

use Models\Log;

/**
 * Class DbLog
 * @package database
 */
class DbLog extends Database
{
    static $databaseName = "Log";

    /**
     * @return Log[]
     */
    public function getLogs()
    {
        return $this->get('*', null, [['logId', 'DESC']]);
    }
}
