<?php

namespace KDE\Database;

use KDE\Model\User;
use TBCore\Exception\MissingAnnotationException;
use TBCore\Exception\MissingQueryType;
use TBCore\Exception\MissingValueException;
use TBCore\Exception\NoPrimaryKeyException;
use TBCore\Exception\NotImplementedException;

/**
 * Class DbUser
 * @package KDE\Database
 */
class DbUser extends Database
{
    static string $databaseName = 'User';
    static $model = User::class;

    /**
     * @return User[]
     */
    public function all(): array
    {
        return $this->get('*');
    }


    /**
     * @param $sessionId
     * @return User|null
     */
    public function bySessionId($sessionId): ?User
    {
        $user = $this->get('*', [['sessionId', '=', $sessionId]], null);
        return count($user) > 0 ? $user[0] : null;
    }

    /**
     * @param int $userId
     * @return User|null
     */
    public function byUserId(int $userId): ?User
    {
        $user = $this->get('*', [['userId', '=', $userId]], null);
        return count($user) > 0 ? $user[0] : null;
    }

    /**
     * @param $name
     * @param $password
     * @return null|string
     * @throws MissingAnnotationException
     * @throws MissingQueryType
     * @throws MissingValueException
     * @throws NoPrimaryKeyException
     * @throws NotImplementedException
     */
    public function tryToLogin($name, $password): ?string
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
     * @throws MissingAnnotationException
     */
    public function createUser(string $userName, string $pass, string $fullName, string $email): void
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
    public function getUsersAsAssocArray(): array
    {
        $out = [];
        foreach ($this->all() as $user) {
            $out[$user->getUserId()] = $user;
        }
        return $out;
    }
}
