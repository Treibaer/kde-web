<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 04.01.17
 * Time: 18:09
 */

namespace TBCore\Database;

use TBCore\Model\User;
use TBCore\Worker\Config;

/**
 * Class DbManager
 * @package database
 */
class DbManager
{
    /**
     * @var \mysqli
     */
    protected $connection;
    /**
     * @var User
     */
    protected $user;

    private $userId = null;

    private function connect()
    {
        $config = [
            "host" => Config::get('database')->host,
            "user" => Config::get('database')->user,
            "password" => Config::get('database')->password,
            'database' => Config::get('database')->database,
            'port' => Config::get('database')->port,
        ];
        $this->connection = mysqli_connect($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
    }

    /**
     * @return null
     */
    public function getUserId()
    {
        return $this->user->getUserId();
    }

    public function __construct($user = null)
    {
        if ($this->connection == null) {
            $this->connect();
        }
        if ($user != null) {
            $this->user = $user;
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return DbManager
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \mysqli
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return DbUser
     */
    public function dbUser()
    {
        return new DbUser($this);
    }

    /**
     * @return DbDummy
     */
    public function dbDummy()
    {
        return new DbDummy($this);
    }

    /**
     * @return DbLog
     */
    public function dbLog()
    {
        return new DbLog($this);
    }

    /**
     * @return DbSession
     */
    public function dbSession()
    {
        return new DbSession($this);
    }
}
