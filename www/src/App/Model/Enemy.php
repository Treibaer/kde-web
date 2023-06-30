<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class Enemy
 * @TBCore\Annotation\Table("KdeEnemy")
 * @package TB\Model\Kde
 */
class Enemy extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @var integer
     */
    protected int $kdeEnemyId = 0;
    protected string $name = "";
    protected int $life = 1;
    protected string $fieldImageUrl = "";
    protected string $fullCardUrl = "";
    protected string $type = "";
    public int $stamina = 1;
    public int $mana = 1;

    public function __construct($o = null)
    {
        parent::__construct($o);
    }

    /**
     * @return int
     */
    public function getKdeEnemyId(): int
    {
        return $this->kdeEnemyId;
    }

    /**
     * @param int $kdeEnemyId
     * @return Enemy
     */
    public function setKdeEnemyId(int $kdeEnemyId): Enemy
    {
        $this->kdeEnemyId = $kdeEnemyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Enemy
     */
    public function setName(string $name): Enemy
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getLife(): int
    {
        return $this->life;
    }

    /**
     * @param int $life
     * @return Enemy
     */
    public function setLife(int $life): Enemy
    {
        $this->life = $life;
        return $this;
    }

    /**
     * @return string
     */
    public function getFieldImageUrl(): string
    {
        return $this->fieldImageUrl;
    }

    /**
     * @param string $fieldImageUrl
     * @return Enemy
     */
    public function setFieldImageUrl(string $fieldImageUrl): Enemy
    {
        $this->fieldImageUrl = $fieldImageUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullCardUrl(): string
    {
        return $this->fullCardUrl;
    }

    /**
     * @param string $fullCardUrl
     * @return Enemy
     */
    public function setFullCardUrl(string $fullCardUrl): Enemy
    {
        $this->fullCardUrl = $fullCardUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Enemy
     */
    public function setType(string $type): Enemy
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getStamina(): int
    {
        return $this->stamina;
    }

    /**
     * @param int $stamina
     * @return Enemy
     */
    public function setStamina(int $stamina): Enemy
    {
        $this->stamina = $stamina;
        return $this;
    }

    /**
     * @return int
     */
    public function getMana(): int
    {
        return $this->mana;
    }

    /**
     * @param int $mana
     * @return Enemy
     */
    public function setMana(int $mana): Enemy
    {
        $this->mana = $mana;
        return $this;
    }
}
