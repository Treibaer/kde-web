<?php

namespace KDE\Model;

use TBCore\Model\Model;

/**
 * Class Character
 * @TBCore\Annotation\Table("KdeCharacter")
 * @package KDE\Model\Kde
 */
class Character extends Model
{
    /**
     * @TBCore\Annotation\PrimaryKey
     * @var integer
     */
    protected int $characterId = -1;
    protected int $gender = 0;
    protected string $name = "";
    protected string $type = "";
    protected int $life = 1;
    protected int $mana = 1;
    protected int $stamina = 1;
    protected int $attack = 1;
    protected int $defense = 1;
    protected int $magicalAttack = 1;
    protected int $magicalDefense = 1;
    protected int $skill = 1;
    protected int $wisdom = 1;
    protected string $specialSkillText = "";
    protected string $imageUrl = "";
    protected string $fieldImageUrl = "";
    protected string $color = "";
    protected string $fullCardUrl = "";
    protected string $baseSkill0 = "";
    protected string $baseSkill1 = "";
    protected string $advancedSkillOne = "";
    protected string $advancedSkillTwo = "";
    protected string $advancedSkillThree = "";

    /**
     * @return int
     */
    public function getCharacterId(): int
    {
        return $this->characterId;
    }

    /**
     * @param int $characterId
     * @return Character
     */
    public function setCharacterId(int $characterId): Character
    {
        $this->characterId = $characterId;
        return $this;
    }

    /**
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     * @return Character
     */
    public function setGender(int $gender): Character
    {
        $this->gender = $gender;
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
     * @return Character
     */
    public function setName(string $name): Character
    {
        $this->name = $name;
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
     * @return Character
     */
    public function setType(string $type): Character
    {
        $this->type = $type;
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
     * @return Character
     */
    public function setLife(int $life): Character
    {
        $this->life = $life;
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
     * @return Character
     */
    public function setMana(int $mana): Character
    {
        $this->mana = $mana;
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
     * @return Character
     */
    public function setStamina(int $stamina): Character
    {
        $this->stamina = $stamina;
        return $this;
    }

    /**
     * @return int
     */
    public function getAttack(): int
    {
        return $this->attack;
    }

    /**
     * @param int $attack
     * @return Character
     */
    public function setAttack(int $attack): Character
    {
        $this->attack = $attack;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefense(): int
    {
        return $this->defense;
    }

    /**
     * @param int $defense
     * @return Character
     */
    public function setDefense(int $defense): Character
    {
        $this->defense = $defense;
        return $this;
    }

    /**
     * @return int
     */
    public function getMagicalAttack(): int
    {
        return $this->magicalAttack;
    }

    /**
     * @param int $magicalAttack
     * @return Character
     */
    public function setMagicalAttack(int $magicalAttack): Character
    {
        $this->magicalAttack = $magicalAttack;
        return $this;
    }

    /**
     * @return int
     */
    public function getMagicalDefense(): int
    {
        return $this->magicalDefense;
    }

    /**
     * @param int $magicalDefense
     * @return Character
     */
    public function setMagicalDefense(int $magicalDefense): Character
    {
        $this->magicalDefense = $magicalDefense;
        return $this;
    }

    /**
     * @return int
     */
    public function getSkill(): int
    {
        return $this->skill;
    }

    /**
     * @param int $skill
     * @return Character
     */
    public function setSkill(int $skill): Character
    {
        $this->skill = $skill;
        return $this;
    }

    /**
     * @return int
     */
    public function getWisdom(): int
    {
        return $this->wisdom;
    }

    /**
     * @param int $wisdom
     * @return Character
     */
    public function setWisdom(int $wisdom): Character
    {
        $this->wisdom = $wisdom;
        return $this;
    }

    /**
     * @return string
     */
    public function getSpecialSkillText(): string
    {
        return $this->specialSkillText;
    }

    /**
     * @param string $specialSkillText
     * @return Character
     */
    public function setSpecialSkillText(string $specialSkillText): Character
    {
        $this->specialSkillText = $specialSkillText;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     * @return Character
     */
    public function setImageUrl(string $imageUrl): Character
    {
        $this->imageUrl = $imageUrl;
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
     * @return Character
     */
    public function setFieldImageUrl(string $fieldImageUrl): Character
    {
        $this->fieldImageUrl = $fieldImageUrl;
        return $this;
    }


    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Character
     */
    public function setColor(string $color): Character
    {
        $this->color = $color;
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
     * @return Character
     */
    public function setFullCardUrl(string $fullCardUrl): Character
    {
        $this->fullCardUrl = $fullCardUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseSkill0(): string
    {
        return $this->baseSkill0;
    }

    /**
     * @param string $baseSkill0
     * @return Character
     */
    public function setBaseSkill0(string $baseSkill0): Character
    {
        $this->baseSkill0 = $baseSkill0;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseSkill1(): string
    {
        return $this->baseSkill1;
    }

    /**
     * @param string $baseSkill1
     * @return Character
     */
    public function setBaseSkill1(string $baseSkill1): Character
    {
        $this->baseSkill1 = $baseSkill1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdvancedSkillOne(): string
    {
        return $this->advancedSkillOne;
    }

    /**
     * @param string $advancedSkillOne
     * @return Character
     */
    public function setAdvancedSkillOne(string $advancedSkillOne): Character
    {
        $this->advancedSkillOne = $advancedSkillOne;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdvancedSkillTwo(): string
    {
        return $this->advancedSkillTwo;
    }

    /**
     * @param string $advancedSkillTwo
     * @return Character
     */
    public function setAdvancedSkillTwo(string $advancedSkillTwo): Character
    {
        $this->advancedSkillTwo = $advancedSkillTwo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdvancedSkillThree(): string
    {
        return $this->advancedSkillThree;
    }

    /**
     * @param string $advancedSkillThree
     * @return Character
     */
    public function setAdvancedSkillThree(string $advancedSkillThree): Character
    {
        $this->advancedSkillThree = $advancedSkillThree;
        return $this;
    }
}
