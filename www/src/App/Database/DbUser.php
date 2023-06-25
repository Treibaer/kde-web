<?php

namespace KDE\Database;

use KDE\Model\User;

/**
 * Class DbUser
 * @package KDE\Database
 */
class DbUser extends Database
{
    static $databaseName = 'User';
    static $model = User::class;

    /**
     * @return User[]
     */
    public function all()
    {
        return $this->get('*');
    }


    /**
     * @param $sessionId
     * @return User
     */
    public function bySessionId($sessionId)
    {
        $user = $this->get('*', [['sessionId', '=', $sessionId]], null);
        return count($user) > 0 ? $user[0] : null;
    }

    /**
     * @param int $userId
     * @return User
     */
    public function byUserId(int $userId)
    {
        $user = $this->get('*', [['userId', '=', $userId]], null);
        return count($user) > 0 ? $user[0] : null;
    }

    /**
     * @param $name
     * @param $password
     * @return null|string
     */
    public function tryToLogin($name, $password)
    {
        $password = $this->encrypt($password);
        /** @var User[] $users */
        $users = $this->get('*', [['userName', '=', $name], ['password', '=', $password]]);
        if (count($users) != 1) {
            return null;
        }
        $user = $users[0];
        if ($user->getSessionId() !== "") {
            return $user->getSessionId();
        }

        $sessionId = $this->encrypt($name . "" . (time() - 1328907));
        $user->setSessionId($sessionId);
        $this->persist($user);
        return $sessionId;
    }

    /**
     * @param string $userName
     * @param string $pass
     * @param string $fullName
     * @param string $email
     * @throws \TBCore\Exception\MissingAnnotationException
     */
    public function createUser(string $userName, string $pass, string $fullName, string $email)
    {
        $user = new User();
        $user->setUserId(0);
        $user->setUserName($userName);
        $user->setPassword($this->encrypt($pass));
        $user->setFullName($fullName);
        $user->setEmail($email);
        $this->insert($user);
    }

    /**
     * @param $pass
     * @return string
     */
    function encrypt($pass): string
    {
        return md5($pass);
    }

    /**
     * @return User[]
     */
    public function getUsersAsAssocArray()
    {
        $out = [];
        foreach ($this->all() as $user) {
            $out[$user->getUserId()] = $user;
        }
        return $out;
    }
}
