<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class GameActivity
 * @TBCore\Annotation\Table("KdeGameActivity")
 * @package TB\Model\Kde
 */
class GameActivity extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @var integer
     */
    protected $kdeGameActivityId = 0;

    /**
     * @var int
     */
    protected $userId = 0;

    /**
     * @var string
     */
    protected $text = "";

    /**
     * @var int
     */
    protected $activityDate = 0;

    /**
     * @var int
     */
    protected $gameId = 0;

    /**
     * @return int
     */
    public function getKdeGameActivityId(): int
    {
        return $this->kdeGameActivityId;
    }

    /**
     * @param int $kdeGameActivityId
     * @return GameActivity
     */
    public function setKdeGameActivityId(int $kdeGameActivityId): GameActivity
    {
        $this->kdeGameActivityId = $kdeGameActivityId;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return GameActivity
     */
    public function setUserId(int $userId): GameActivity
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return GameActivity
     */
    public function setText(string $text): GameActivity
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return int
     */
    public function getActivityDate(): int
    {
        return $this->activityDate;
    }

    /**
     * @param int $activityDate
     * @return GameActivity
     */
    public function setActivityDate(int $activityDate): GameActivity
    {
        $this->activityDate = $activityDate;
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
     * @return GameActivity
     */
    public function setGameId(int $gameId): GameActivity
    {
        $this->gameId = $gameId;
        return $this;
    }
}
