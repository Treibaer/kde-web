<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 04.01.17
 * Time: 18:07
 */

namespace TBCore\Database;

use TBCore\Model\Session;
use TBCore\Model\User;

/**
 * Class DbSession
 * @package database
 */
class DbSession extends Database
{
    static $databaseName = 'Session';
    static $model = Session::class;

    /**
     * @param User $user
     * @return Session
     */
    public function createSession(User $user, $device = '')
    {
        $tries = 5;
        $success = false;
        while (!$success && $tries > 0) {
            $sessionId = md5($user->getUser() . "" . (time() - 1328907) . '' . $tries . rand(0, 9999999));
            $sql = 'SELECT * FROM ' . static::$databaseName . ' WHERE value="' . $sessionId . '"';
            $sessions = $this->fetchToModel($this->q($sql));
            if (count($sessions) == 0) {
                $session = new Session();
                $session->setIp($_SERVER['SERVER_ADDR']);
                $session->setBrowser($_SERVER['HTTP_USER_AGENT']);
                $session->setDateCreated(time());
                $session->setDateValidation(0);
                $session->setUserId($user->getUserId());
                $session->setDevice($device);
                $session->setValue($sessionId);
                $session->setIsValid(true);
                $id = $this->insert($session);
                $session->setSessionId($id);
                return $session;
            }
            $tries--;
        }
        return null;
    }

    public function validateSessionId($sessionId)
    {
        if (null == $sessionId) {
            return false;
        }
        $cnt = $this->get('*', [['sessionId', '=', $sessionId], ['isValid', '=', true]]);
        return 1 == count($cnt);
    }

    /**
     * @param string $sessionId
     * @return Session
     */
    public function getValidSessionBySessionId($sessionId)
    {
        if (null == $sessionId) {
            return null;
        }
        $sessions = $this->get('*', [['value', '=', $sessionId], ['isValid', '=', true]]);
        if (count($sessions) != 1) {
            return null;
        }
        /** @var Session $session */
        $session = $sessions[0];
        if ($session->getDateValidation() > 0 && $session->getDateValidation() < time()) {
            return null;
        }
        return $session;
    }

    /**
     * @param $sessionId
     * @return User
     */
    public function getUserBySessionId($sessionId)
    {
        $session = $this->getValidSessionBySessionId($sessionId);
        if (null == $session) {
            return null;
        }
        $user = $this->coreDbManager->dbUser()->get('*', [['userId', '=', $session->getUserId()]]);
        return count($user) == 1 ? $user[0] : null;
    }
}
