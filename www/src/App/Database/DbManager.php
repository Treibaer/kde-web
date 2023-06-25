<?php

namespace KDE\Database;

use TBCore\Database\DbManager as CoreDbManager;
use TBCore\Exception\Exception;
use TBCore\Exception\MissingAnnotationException;
use KDE\Model\User;

/**
 * Class DbManager
 * @package TB\Database
 */
class DbManager
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var CoreDbManager
     */
    private $coreDbManager;

    public function __construct(CoreDbManager $coreDbManager)
    {
        $this->coreDbManager = $coreDbManager;
    }

    /**
     * @return DbDummy
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbDummy()
    {
        return new DbDummy($this->coreDbManager, $this);
    }
    /**
     * @return DbCharacter
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbCharacter() {
        return new DbCharacter($this->coreDbManager, $this);
    }

    /**
     * @return DbUser
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbKUser() {
        return new DbUser($this->coreDbManager, $this);
    }

    /**
     * @return DbAchievement
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbAchievement() {
        return new DbAchievement($this->coreDbManager, $this);
    }

    /**
     * @return DbCard
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbCard() {
        return new DbCard($this->coreDbManager, $this);
    }

    /**
     * @return DbBoard
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbBoard() {
        return new DbBoard($this->coreDbManager, $this);
    }

    /**
     * @return DbGame
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbGame() {
        return new DbGame($this->coreDbManager, $this);
    }

    /**
     * @return DbGameActivity
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbGameActivity() {
        return new DbGameActivity($this->coreDbManager, $this);
    }

    /**
     * @return DbEnemy
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbEnemy() {
        return new DbEnemy($this->coreDbManager, $this);
    }

    /**
     * @return DbUser
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbUser() {
        return new DbUser($this->coreDbManager, $this);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return DbManager
     */
    public function setUser(User $user): DbManager
    {
        $this->user = $user;
        return $this;
    }
}
