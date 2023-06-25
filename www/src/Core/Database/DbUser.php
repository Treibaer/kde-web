<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 04.01.17
 * Time: 18:07
 */

namespace TBCore\Database;

use TBCore\Model\User;

class DbUser extends Database
{
    static $databaseName = 'User';
    static $model = User::class;

    /**
     * @param $sessionId
     * @return User
     */
    public function getUserBySessionId($sessionId)
    {
        $user = $this->get('*', [['sessionId', '=', $sessionId]], null);
        return count($user) > 0 ? $user[0] : null;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->get('*', null, [['userId', 'ASC']]);
    }

    /**
     * @return User[]
     */
    public function getUsersAsAssocArray()
    {
        $out = [];
        foreach ($this->getUsers() as $user) {
            $out[$user->getUserId()] = $user;
        }
        return $out;
    }

    /**
     * @param string $user
     * @param string $password
     * @return string
     */
    public function login($user, $password, $device = '')
    {
        //$password = md5($password);
        $query = "SELECT * FROM User WHERE user='$user' AND password='$password'";
        /** @var User[] $user */
        $user = $this->fetchToModel($this->q($query));
        if (count($user) != 1) {
            return null;
        }
        return $user[0]->getSessionid();
        // todo: re-activate session system
        $session = $this->coreDbManager->dbSession()->createSession($user[0], $device);
        if (null != $session) {
            return $session->getValue();
        }
        return null;
    }
}
