<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class Game
 * @TBCore\Annotation\Table("KdeGame")
 * @package TB\Model\Kde
 */
class Game extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @var integer
     */
    public int $gameId = 0;
    public int $boardId = 0;

    // valid json format
    public string $players = "{}";
    public int $leader = 0;
    public int $creationDate = 0;
    public int $state = 0;

    // valid json format
    public string $playerPositions = "{}";

    // valid json format
    public string $openCards = "[]";

    // valid json format
    public string $libraryCards = "[]";

    // valid json format
    public string $trashCards = "[]";

    // valid json format
    public string $ping = "{}";
    public string $marketPlace = "";
    public int $dice = 0;
    public int $diceTime = 0;
    public string $diceRoller = "";
    protected string $lastUpdatedHash = "";

    // valid json format
    protected string $custom = "{}";

    // valid json format
    public string $bag = "[]";

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     * @return Game
     */
    public function setGameId(int $gameId): Game
    {
        $this->gameId = $gameId;
        return $this;
    }

    /**
     * @return int
     */
    public function getBoardId(): int
    {
        return $this->boardId;
    }

    /**
     * @param int $boardId
     * @return Game
     */
    public function setBoardId(int $boardId): Game
    {
        $this->boardId = $boardId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlayers(): string
    {
        return $this->players;
    }

    /**
     * @param string $players
     * @return Game
     */
    public function setPlayers(string $players): Game
    {
        $this->players = $players;
        return $this;
    }

    /**
     * @return int
     */
    public function getLeader(): int
    {
        return $this->leader;
    }

    /**
     * @param int $leader
     * @return Game
     */
    public function setLeader(int $leader): Game
    {
        $this->leader = $leader;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreationDate(): int
    {
        return $this->creationDate;
    }

    /**
     * @param int $creationDate
     * @return Game
     */
    public function setCreationDate(int $creationDate): Game
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @return Game
     */
    public function setState(int $state): Game
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlayerPositions(): string
    {
        if ($this->playerPositions === "") {
            return "{}";
        }
        return $this->playerPositions;
    }

    /**
     * @param string $playerPositions
     * @return Game
     */
    public function setPlayerPositions(string $playerPositions): Game
    {
        $this->playerPositions = $playerPositions;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenCards(): string
    {
        if ($this->openCards === "") {
            return "[]";
        }
        return $this->openCards;
    }

    /**
     * @param string $openCards
     * @return Game
     */
    public function setOpenCards(string $openCards): Game
    {
        $this->openCards = $openCards;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibraryCards(): string
    {
        if ($this->libraryCards === "") {
            return "[]";
        }
        return $this->libraryCards;
    }

    /**
     * @param string $libraryCards
     * @return Game
     */
    public function setLibraryCards(string $libraryCards): Game
    {
        $this->libraryCards = $libraryCards;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrashCards(): string
    {
        if ($this->trashCards === "") {
            return "[]";
        }
        return $this->trashCards;
    }

    /**
     * @param string $trashCards
     * @return Game
     */
    public function setTrashCards(string $trashCards): Game
    {
        $this->trashCards = $trashCards;
        return $this;
    }

    /**
     * @return string
     */
    public function getPing(): string
    {
        return $this->ping;
    }

    /**
     * @param string $ping
     * @return Game
     */
    public function setPing(string $ping): Game
    {
        $this->ping = $ping;
        return $this;
    }

    /**
     * @return string
     */
    public function getMarketPlace(): string
    {
        return $this->marketPlace;
    }

    /**
     * @param string $marketPlace
     * @return Game
     */
    public function setMarketPlace(string $marketPlace): Game
    {
        $this->marketPlace = $marketPlace;
        return $this;
    }

    /**
     * @return int
     */
    public function getDice(): int
    {
        return $this->dice;
    }

    /**
     * @param int $dice
     * @return Game
     */
    public function setDice(int $dice): Game
    {
        $this->dice = $dice;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustom(): string
    {
        if ($this->custom === "") {
            return "{}";
        }
        return $this->custom;
    }

    /**
     * @param string $custom
     * @return Game
     */
    public function setCustom(string $custom): Game
    {
        $this->custom = $custom;
        return $this;
    }

    /**
     * @return int
     */
    public function getDiceTime(): int
    {
        return $this->diceTime;
    }

    /**
     * @param int $diceTime
     * @return Game
     */
    public function setDiceTime(int $diceTime): Game
    {
        $this->diceTime = $diceTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getDiceRoller(): string
    {
        return $this->diceRoller;
    }

    /**
     * @param string $diceRoller
     * @return Game
     */
    public function setDiceRoller(string $diceRoller): Game
    {
        $this->diceRoller = $diceRoller;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastUpdatedHash(): string
    {
        return $this->lastUpdatedHash;
    }

    /**
     * @param string $lastUpdatedHash
     * @return Game
     */
    public function setLastUpdatedHash(string $lastUpdatedHash): Game
    {
        $this->lastUpdatedHash = $lastUpdatedHash;
        return $this;
    }

    /**
     * @return string
     */
    public function getBag(): string
    {
        return $this->bag;
    }

    /**
     * @param string $bag
     * @return Game
     */
    public function setBag(string $bag): Game
    {
        $this->bag = $bag;
        return $this;
    }
}
