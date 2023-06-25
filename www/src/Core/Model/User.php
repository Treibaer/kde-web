<?php
/**
 * Created by PhpStorm.
 * User: hannes
 * Date: 29.12.16
 * Time: 20:04
 */

namespace TBCore\Model;

use TBCore\Annotation;

/**
 * Class User
 * @Annotation\Table("User")
 * @package Models
 */
class User extends Model
{
    /**
     * @Annotation\PrimaryKey
     * @var int
     */
    protected $userId = 0;

    /**
     * @var string
     */
    protected $sessionId = "";

    /**
     * @var string
     */
    protected $icon = "";

    /**
     * @var string
     */
    protected $user = "";

    /**
     * @var int
     */
    protected $accessLevel = 0;

    /**
     * @var string
     */
    protected $uuid = "";

    /**
     * @var int
     */
    protected $selectedYear = 0;

    /**
     * @var string
     */
    protected $fullName = "";

    /**
     * @var int
     */
    protected $registrationDate = 0;

    /**
     * @var int
     */
    protected $lastOnline = 0;

    /**
     * @var string
     */
    protected $password = "";

    /**
     * @var int
     */
    protected $mainModule = 0;

    /**
     * @var int
     */
    protected $lastHealthDataCheck = 0;

    /**
     * @var string
     */
    protected $email = "";

    /**
     * @var int
     */
    protected $darkMode = 0;

    /**
     * @var int
     */
    protected $gameId = 6;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return User
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionid()
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionid
     * @return User
     */
    public function setSessionid($sessionid)
    {
        $this->sessionid = $sessionid;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return User
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     * @return User
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     * @param int $access_level
     * @return User
     */
    public function setAccessLevel($access_level)
    {
        $this->access_level = $access_level;
        return $this;
    }

    /**
     * @return int
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param int $uuid
     * @return User
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return int
     */
    public function getMainModule()
    {
        return $this->mainModule;
    }

    /**
     * @param int $mainModule
     * @return User
     */
    public function setMainModule($mainModule)
    {
        $this->mainModule = $mainModule;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return int
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param int $registrationDate
     * @return User
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastOnline()
    {
        return $this->lastOnline;
    }

    /**
     * @param int $lastOnline
     * @return User
     */
    public function setLastOnline($lastOnline)
    {
        $this->lastOnline = $lastOnline;
        return $this;
    }

    /**
     * @return int
     */
    public function getSelectedYear()
    {
        if ($this->selectedYear == 3000) {
            return (int)date('Y');
        }
        return $this->selectedYear;
    }

    /**
     * @return int
     */
    public function getRealSelectedYear()
    {
        return $this->selectedYear;
    }

    /**
     * @param int $selectedYear
     * @return User
     */
    public function setSelectedYear($selectedYear)
    {
        $this->selectedYear = $selectedYear;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getLastHealthDataCheck()
    {
        return $this->lastHealthDataCheck;
    }

    /**
     * @param int $lastHealthDataCheck
     * @return User
     */
    public function setLastHealthDataCheck($lastHealthDataCheck)
    {
        $this->lastHealthDataCheck = $lastHealthDataCheck;
        return $this;
    }

    /**
     * @return int
     */
    public function getDarkMode() {
        return $this->darkMode;
    }

    /**
     * @param int $darkMode
     * @return User
     */
    public function setDarkMode($darkMode)
    {
        $this->darkMode = $darkMode;
        return $this;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return User
     */
    public function setGameId(int $gameId): User
    {
        $this->gameId = $gameId;
        return $this;
    }
}
