<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class User
 * @TBCore\Annotation\Table("kde.User")
 * @package KDE\Model\Kde
 */
class User extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @TBCore\Annotation\Type("int")
     * @var int
     */
    public int $userId = 0;
    /**
     * @TBCore\Annotation\Type("varchar")
     * @var string
     */
    protected string $sessionId = "";

    /**
     * @TBCore\Annotation\Type("varchar")
     * @var string
     */
    public string $userName = "";

    /**
     * @TBCore\Annotation\Type("varchar")
     * @var string
     */
    public string $fullName = "";

    /**
     * @TBCore\Annotation\Type("varchar")
     * @var string
     */
    protected string $password = "";

    /**
     * @TBCore\Annotation\Type("varchar")
     * @var string
     */
    public string $email = "";

    /**
     * @TBCore\Annotation\Type("int")
     * @var int
     */
    public int $isAdmin = 0;

    /**
     * @TBCore\Annotation\Type("int")
     * @var int
     */
    public int $canEditGames = 0;

    /**
     * @TBCore\Annotation\Type("int")
     * @var int
     */
    public int $canEditCards = 0;

    /**
     * @TBCore\Annotation\Type("int")
     * @var int
     */
    public int $canSeeUsers = 0;

    /**
     * @TBCore\Annotation\Type("int")
     * @var int
     */
    public int $canEditBoards = 0;

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId . "";
    }

    /**
     * @param int $userId
     * @return User
     */
    public function setUserId(int $userId): User
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     * @return User
     */
    public function setSessionId(string $sessionId): User
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return User
     */
    public function setUserName(string $userName): User
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     * @return User
     */
    public function setFullName(string $fullName): User
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsAdmin(): int
    {
        return $this->isAdmin;
    }

    /**
     * @param int $isAdmin
     * @return User
     */
    public function setIsAdmin(int $isAdmin): User
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return int
     */
    public function getCanEditGames(): int
    {
        return $this->isAdmin || $this->canEditGames;
    }

    /**
     * @param int $canEditGames
     * @return User
     */
    public function setCanEditGames(int $canEditGames): User
    {
        $this->canEditGames = $canEditGames;
        return $this;
    }

    /**
     * @return int
     */
    public function getCanEditCards(): int
    {
        return $this->isAdmin || $this->canEditCards;
    }

    /**
     * @param int $canEditCards
     * @return User
     */
    public function setCanEditCards(int $canEditCards): User
    {
        $this->canEditCards = $canEditCards;
        return $this;
    }

    /**
     * @return int
     */
    public function getCanSeeUsers(): int
    {
        return $this->isAdmin || $this->canSeeUsers;
    }

    /**
     * @param int $canSeeUsers
     * @return User
     */
    public function setCanSeeUsers(int $canSeeUsers): User
    {
        $this->canSeeUsers = $canSeeUsers;
        return $this;
    }

    /**
     * @return int
     */
    public function getCanEditBoards(): int
    {
        return $this->isAdmin || $this->canEditBoards;
    }

    /**
     * @param int $canEditBoards
     * @return User
     */
    public function setCanEditBoards(int $canEditBoards): User
    {
        $this->canEditBoards = $canEditBoards;
        return $this;
    }
}
