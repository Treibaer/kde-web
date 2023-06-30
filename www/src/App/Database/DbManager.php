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
    private User $user;

    public function __construct(private CoreDbManager $coreDbManager)
    {
    }

    /**
     * @return DbDummy
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbDummy(): DbDummy
    {
        return new DbDummy($this->coreDbManager, $this);
    }
    /**
     * @return DbCharacter
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbCharacter(): DbCharacter
    {
        return new DbCharacter($this->coreDbManager, $this);
    }

    /**
     * @return DbCard
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbCard(): DbCard
    {
        return new DbCard($this->coreDbManager, $this);
    }

    /**
     * @return DbBoard
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbBoard(): DbBoard
    {
        return new DbBoard($this->coreDbManager, $this);
    }

    /**
     * @return DbGame
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbGame(): DbGame
    {
        return new DbGame($this->coreDbManager, $this);
    }

    /**
     * @return DbEnemy
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbEnemy(): DbEnemy
    {
        return new DbEnemy($this->coreDbManager, $this);
    }

    /**
     * @return DbUser
     * @throws Exception
     * @throws MissingAnnotationException
     */
    public function dbUser(): DbUser
    {
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
